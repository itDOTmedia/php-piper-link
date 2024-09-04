<?php
namespace Idm\PiperLink\Tokens;

use Idm\PiperLink\Types\TokenType;

interface ITokenSource
{
    /**
     * Save a token to the data source.
     * @param Token $token The token to save
     * @return void
     */
    public function save(Token $token);

    /**
     * Load a token from the data source.
     * @param TokenType $tokenType The type of the token.
     * @param string $tokenValue The value of the token.
     * @return Token|null Returns a token on success, null otherwise.
     */
    public function load(TokenType $tokenType, string $tokenValue) : ?Token;

    /**
     * Deletes a token from the data source.
     * @param TokenType $tokenType The type of the token.
     * @param string $tokenValue The value of the token.
     * @return void
     */
    public function delete(TokenType $tokenType, string $tokenValue);
}