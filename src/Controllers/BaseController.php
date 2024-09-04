<?php
namespace Idm\PiperLink\Controllers;

use Idm\PiperLink\Exceptions\AccessDeniedException;
use Idm\PiperLink\Exceptions\AuthenticationException;
use Idm\PiperLink\PiperLink;
use Idm\PiperLink\RouteParser;
use Idm\PiperLink\StringX;
use Idm\PiperLink\Tokens\Token;
use Idm\PiperLink\Tokens\TokenRepository;
use Pyther\Ioc\Ioc;

abstract class BaseController
{
    protected PiperLink $piperLink;
    protected RouteParser $routeParser;
    private ?bool $hasAccess = null;
    private ?string $userId = null; 
    private ?Token $token = null;
    protected bool $requiredAccessByDefault = true;

    function __construct(PiperLink $piperLink) {
        $this->piperLink = $piperLink;
        $this->routeParser = new RouteParser();
        if ($this->requiredAccessByDefault) {
            $this->checkAccess();
        }
    }

    abstract function execute(string $path);

    protected function isRoute(string $method, string $mask, string $path): bool
    {
        return $this->getRequest()->method == \strtoupper($method) && $this->routeParser->Is($mask, $path);
    }

    protected function getRouteParameter(string $name) {
        return $this->routeParser->getParameter($name);
    }

    public function getRequest() {
        return $this->piperLink->getRequest();
    }

    public function getResponse() {
        return $this->piperLink->getResponse();
    }

    public function getUserId(): ?string {
        return $this->userId;
    }

    public function getBearerTokenValue(bool $required): ?string {
        $header = $this->getRequest()->getAuthorizationHeader();
        if (empty($header)) {
            if ($required) {
                throw new AuthenticationException("No authorization header given!");
            } else {
                return null;
            }
        }
        $bearerToken = preg_match('/Bearer\s(\S+)/i', $header, $matches) ? $matches[1] : null;
        if (StringX::isNullOrWhiteSpace($bearerToken)) {
            if ($required) {
                throw new AuthenticationException("No token given!");
            } else {
                return null;
            }
        }
        return $bearerToken;
    }

    public function getToken(bool $required): ?Token {
        if ($this->token === null) {
            $bearerToken = $this->getBearerTokenValue($required);            
            $this->token = Ioc::get(TokenRepository::class)->validateAccessToken($bearerToken);
            if ($this->token === null) {
                if ($required) {
                    throw new AuthenticationException("The access token is malformed, revoked or expired.", AuthenticationException::INVALID_TOKEN);
                } else {
                    return null;
                }
            }
        }
        return $this->token;
    }

    public function checkAccess(bool $required = true): bool
    {
        if ($this->hasAccess !== null) {
            return $this->hasAccess;
        }
        $this->hasAccess = false;
        $token = $this->getToken($required);
        $this->hasAccess = $token != null;
        $this->userId = $token != null ? $token->userId : null;
        return $this->hasAccess;
    }

}