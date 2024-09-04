<?php
namespace Idm\PiperLink\Tokens;

use Exception;
use Idm\PiperLink\Configurations;
use Idm\PiperLink\Exceptions\AuthenticationException;
use Idm\PiperLink\StringX;
use Idm\PiperLink\Types\TokenType;
use Pyther\Ioc\Ioc;

class TokenRepository
{
    // ***** Statics *****
    public static function newInstance() {
        $config = Ioc::get(Configurations::class);
        $config->set("tokens.data-source.root", $config->get("root"));
        $class = $config->get("tokens.data-source.class") ?? throw new Exception("No token data source class set!");
        $fqn = __NAMESPACE__."\\".$class;
        $dataSource = new $fqn($config->get("tokens.data-source"));
        return new static($dataSource);
    }

    /**
     * Create a new token value.
     * @param integer $len The length of the token value (should be an even number).
     * @return string The token value in hexadecimal representation-
     */
    public static function createTokenValue(int $len = 512): string {
        return bin2hex(random_bytes(floor($len / 2)));
    }

    // ***** Instance *****

    private ITokenSource $dataSource;

    private function __construct(?ITokenSource $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    /**
     * Save a token using the IDataSource implementation.
     * @param Token $token
     * @return void
     */
    public function save(Token $token) {
        $this->dataSource->save($token);
    }

    public function load(TokenType $tokenType, string $tokenValue) :?Token {
        if (StringX::isNullOrWhiteSpace($tokenValue)) {
            return null;
        }
        return $this->dataSource->load(TokenType::RefreshToken, $tokenValue);
    }

    public function delete(TokenType $tokenType, string $tokenValue) {
        $this->dataSource->delete($tokenType, $tokenValue);
    }

    /**
     * Validates the given access token value.
     * @param [type] $tokenValue
     * @return void
     */
    public function validateAccessToken(?string $tokenValue): ?Token {
        if (StringX::isNullOrWhiteSpace($tokenValue)) {
            return null;
        }
        $token = $this->dataSource->load(TokenType::AccessToken, $tokenValue);
        if ($token === null) {
            return null;
        }
        if (!hash_equals($token->value, $tokenValue)) {
            return null;
        }
        // expired?
        if ($token->isValid()) {
            $this->dataSource->delete(TokenType::AccessToken, $tokenValue);
            return null;
        }
        return $token;
    }

    public function refresh(string $refreshTokenValue, int $newAccessLength, float $newAccessExpires, int $newRefreshLength, float $newRefreshExpires) {
        $token = $this->dataSource->load(TokenType::RefreshToken, $refreshTokenValue);
        
        // validate the refresh token
        if ($token === null) {
            throw new AuthenticationException("invalid token", AuthenticationException::INVALID_TOKEN);
        }

        // delete old access token (if any)
        if (!StringX::isNullOrWhiteSpace($token->reference)) {
            $this->dataSource->delete(TokenType::AccessToken, $token->reference);
        }
        
        // create new access token
        $accessToken = Token::newToken(TokenType::AccessToken, $newAccessLength, $newAccessExpires);
        $accessToken->userId = $token->userId;
        // create new refresh token
        $refreshToken = Token::newToken(TokenType::RefreshToken, $newRefreshLength, $newRefreshExpires);
        $refreshToken->userId = $token->userId;
        // add cross reference
        $accessToken->reference = $refreshToken->value;
        $refreshToken->reference = $accessToken->value;
        $this->save($accessToken);
        $this->save($refreshToken);

        // delete old refresh token
        $this->dataSource->delete(TokenType::RefreshToken, $token->value);

        return [
            "token_type" => "Bearer",
            "access_token" => $accessToken->value,
            "expires_in" => $accessToken->expires,
            "refresh_token" => $refreshToken->value,
            "refresh_token_expires_in" => $refreshToken->expires
        ];
    }

}