<?php
namespace Idm\PiperLink\Tokens;

use Exception;
use Idm\PiperLink\StringX;
use Idm\PiperLink\Types\TokenType;

class TokenFileSource implements ITokenSource
{
    private string $accessPath;
    private string $refreshPath;

    public function __construct(array $config)
    {   
        $this->accessPath = realpath($config["root"].($config["access-path"] ?? "TokenFileSource: No access path given!")).DIRECTORY_SEPARATOR;
        $this->refreshPath = realpath($config["root"].($config["refresh-path"] ?? "TokenFileSource: No refresh path given!")).DIRECTORY_SEPARATOR;
    }

    public function save(Token $token) {
        if ($token->type == TokenType::AccessToken) {
            $path = $this->accessPath.substr($token->value, 0, 32);
        }
        else if ($token->type == TokenType::RefreshToken) {
            $path =  $this->refreshPath.substr($token->value, 0, 32);
        }
        else {
            throw new Exception("Invalid token type: {$token->type->name} ({$token->type->value})");
        }
        if (!is_writeable(dirname($path))) {
            throw new Exception("Can't save token!");
        }
        if (file_put_contents($path, $this->SerializeToken($token)) === false) {
            throw new Exception("Can't save token!");
        }
        $token->persistent = true;
    }

    public function load(TokenType $tokenType, string $tokenValue) : ?Token {
        if ($tokenType == TokenType::AccessToken) {
            $path = $this->accessPath.substr($tokenValue, 0, 32);
        }
        else if ($tokenType == TokenType::RefreshToken) {
            $path =  $this->refreshPath.substr($tokenValue, 0, 32);
        } 
        else {
            throw new Exception("Invalid token type: {$tokenType->name} ({$tokenType->value})");
        }
        if (!is_readable($path)) {
            return null;
        }
        $data = file_get_contents($path);
        if ($data === false) {
            return null;
        }
        $token = $this->DeserializeToken($data);
        if ($token === null) {
            return null;
        }
        $token->type = $tokenType;
        return $token;
    }

    public function delete(TokenType $tokenType, string $tokenValue) {
        if ($tokenType == TokenType::AccessToken) {
            $path = $this->accessPath.substr($tokenValue, 0, 32);
        }
        else if ($tokenType == TokenType::RefreshToken) {
            $path =  $this->refreshPath.substr($tokenValue, 0, 32);
        } 
        else {
            throw new Exception("Invalid token type: {$tokenType->name} ({$tokenType->value})");
        }
        if (file_exists($path)) {
            @unlink($path);
        }
    }

    private function SerializeToken(Token $token): string {
        $data = [
            "value" => $token->value,
            "createdAt" => $token->createdAt != null ? $token->createdAt->getTimestamp() : null,
            "expires" => $token->expires,
            "expiresAt" => $token->expiresAt != null ? $token->expiresAt->getTimestamp() : null,
            "reference" => $token->reference != null ? substr($token->reference, 0, 32) : null,
            "userId" => $token->userId
        ]; 
        return json_encode($data, \JSON_PRETTY_PRINT);
    }

    private function DeserializeToken(?string $json): ?Token {
        if (StringX::isNullOrWhiteSpace($json)) {
            return null;
        }
        $data = json_decode($json, true);
        if ($data === false) {
            return null;
        }
        $token = new Token();
        $token->value = $data['value'];
        $token->createdAt = (new \DateTime())->setTimestamp($data['createdAt']);
        $token->expires = $data['expires'];
        $token->expiresAt = (new \DateTime())->setTimestamp($data['expiresAt']);
        $token->reference = $data['reference'];
        $token->userId = $data['userId'];
        $token->persistent = true;
        return $token;
    }
}