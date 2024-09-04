<?php
namespace Idm\PiperLink\Controllers;

use Exception;
use Idm\PiperLink\Configurations;
use Idm\PiperLink\Contracts\IAuthenticatable;
use Idm\PiperLink\Credentials\ApiKeyCredentials;
use Idm\PiperLink\Credentials\BaseCredentials;
use Idm\PiperLink\Credentials\BasicCredentials;
use Idm\PiperLink\Credentials\BearerCredentials;
use Idm\PiperLink\Credentials\ClientCredentials;
use Idm\PiperLink\Exceptions\AuthenticationException;
use Idm\PiperLink\Exceptions\NotFoundException;
use Idm\PiperLink\PiperLink;
use Idm\PiperLink\StringX;
use Idm\PiperLink\Tokens\Token;
use Idm\PiperLink\Tokens\TokenRepository;
use Idm\PiperLink\Types\TokenType;
use Pyther\Ioc\Ioc;

/**
 * Controller for all "auth/..." routes.
 */
class AuthController extends BaseController
{
    function __construct(PiperLink $piperLink)
    {
        $this->requiredAccessByDefault = false;
        parent::__construct($piperLink);
    }

    function execute(string $path) {
        // never cache any authentication requests
        $this->getResponse()->addHeader("Cache-Control", "no-store");
        $this->getResponse()->addHeader("Pragma", "no-cache");

        if ($this->IsRoute("GET", "auth/methods", $path)) {
            $this->GET_methods();
        } else if ($this->IsRoute("POST", "auth/basic", $path)) {
            $this->POST_basic();
        } else if ($this->IsRoute("POST", "auth/bearer", $path)) {
            $this->POST_bearer();
        } else if ($this->IsRoute("POST", "auth/api-key", $path)) {
            $this->POST_apiKey();
        } else if ($this->IsRoute("POST", "auth/client-credentials", $path)) {
            $this->POST_clientCredentials();
        } else if ($this->IsRoute("POST", "auth/refresh", $path)) {
            $this->POST_refresh();
        } else if ($this->IsRoute("DELETE", "auth/token", $path)) {
            $this->DELETE_token();
        } else if ($this->IsRoute("GET", "auth/test", $path)) {
            $this->GET_test();
        } else {
            throw new NotFoundException();
        }        
    }

    function GET_methods()
    {
        // $instance = $this->getInstance(IAuthenticatable::class);
        $instance = Ioc::get(IAuthenticatable::class);
        $result = [];
        foreach ($instance->authenticationMethods() as $method) {
            $result[] = $method->name;
        }
        $this->getResponse()->setData($result);
    }

    #region Login

    function POST_basic() {
        $header = $this->getRequest()->getAuthorizationHeader();
        if (empty($header)) {   
            throw new Exception("No authorization header given!");
        }
        $basic = preg_match('/Basic\s(\S+)/i', $header, $matches) ? $matches[1] : null;
        if (empty($basic)) {
            throw new Exception("No basic authorization data given!");
        }        
        $userId = $this->checkCredentials(new BasicCredentials($basic));
        if ($this->getRequest()->GET("verify-only") === null) {
            $this->getResponse()->setData($this->createTokens($userId));
        }
    }

    function POST_bearer() {
        $header = $this->getRequest()->getAuthorizationHeader();
        if (empty($header)) {
            throw new Exception("No authorization header given!");
        }
        $bearerToken = preg_match('/Bearer\s(\S+)/i', $header, $matches) ? $matches[1] : null;
        if (empty($bearerToken)) {
            throw new Exception("No bearer authorization token given!");
        }
        $userId = $this->checkCredentials(new BearerCredentials($bearerToken));
        if ($this->getRequest()->GET("verify-only") === null) {
            $this->getResponse()->setData($this->createTokens($userId));
        }
    }

    function POST_apiKey() {
        $apiKey = $this->getRequest()->getHeaders()->get("X-API-Key");
        if (empty($apiKey)) {
            throw new Exception("No \"X-API-Key\" header given!");
        }
        $userId = $this->checkCredentials(new ApiKeyCredentials($apiKey));
        if ($this->getRequest()->GET("verify-only") === null) {
            $this->getResponse()->setData($this->createTokens($userId));
        }
    }

    function POST_clientCredentials() {
        $json = $this->getRequest()->getJsonPayload();
        if (empty($json)) {
            throw new Exception("No json payload given!");
        }
        $userId = $this->checkCredentials(new ClientCredentials(
            $json['client_id'] ?? throw new \Exception("No client_id given!"), 
            $json['client_secret'] ?? throw new \Exception("No client_secret given!")
        ));
        if ($this->getRequest()->GET("verify-only") === null) {
            $this->getResponse()->setData($this->createTokens($userId));
        }
    }

    #endregion

    function POST_refresh() {
        $bearerToken = $this->getBearerTokenValue(true);
        $config = Ioc::get(Configurations::class);
        $result = Ioc::get(TokenRepository::class)->refresh(
            $bearerToken, 
            $config->get("tokens.access.length") ?? 512, 
            $config->get("tokens.access.expires") ?? 86400,
            $config->get("tokens.refresh.length") ?? 512, 
            $config->get("tokens.refresh.expires") ?? 2419200
        );
        $this->getResponse()->setData($result);
    }

    function DELETE_token() {
        $this->checkAccess();
        // delete the refresh token
        $accessToken = $this->getToken(true);
        if (!StringX::isNullOrWhiteSpace($accessToken->reference)) {
            Ioc::get(TokenRepository::class)->delete(TokenType::RefreshToken, $accessToken->reference);
        }
        // delete the access token
        Ioc::get(TokenRepository::class)->delete(TokenType::AccessToken, $accessToken->value);
    }

    function GET_test() {
        $this->getResponse()->setData([
            "access" => $this->checkAccess(true),
            "userId" => $this->getUserId(),
            "remains" => $this->getToken(false) !== null ? $this->getToken(false)->getRemaining() : 0
        ]);
    }

    private function checkCredentials(BaseCredentials $credentials): ?string {
        $instance = Ioc::get(IAuthenticatable::class);
        $user = $instance->verifyCredentials($credentials);
        if ($user === null) {
            throw new AuthenticationException();
        }
        return $user;
    }

    private function createTokens(?string $userId = null) {
        $config = Ioc::get(Configurations::class);

        // create new access token
        $accessToken = Token::newToken(
            TokenType::AccessToken,
            $config->get("tokens.access.length") ?? 512, 
            $config->get("tokens.access.expires") ?? 86400
        );
        $accessToken->userId = $userId;
        
        // create new refresh token
        $refreshToken = Token::newToken(
            TokenType::RefreshToken,
            $config->get("tokens.refresh.length") ?? 512, 
            $config->get("tokens.refresh.expires") ?? 2419200
        );
        $refreshToken->userId = $userId;

        // add cross reference
        $accessToken->reference = $refreshToken->value;
        $refreshToken->reference = $accessToken->value;
        
        $repo = Ioc::get(TokenRepository::class);
        $repo->save($accessToken);
        $repo->save($refreshToken);

        return [
            "token_type" => "Bearer",
            "access_token" => $accessToken->value,
            "expires_in" => $accessToken->expires,
            "refresh_token" => $refreshToken->value,
            "refresh_token_expires_in" => $refreshToken->expires
        ];
    }
}