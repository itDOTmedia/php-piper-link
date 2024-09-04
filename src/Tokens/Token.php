<?php
namespace Idm\PiperLink\Tokens;

use Idm\PiperLink\Types\TokenType;

class Token {

    #region Statics

    public static function newToken(TokenType $type, int $tokenLength, float $expires) {
        $token = new Token();
        $token->type = $type;
        $token->value = TokenRepository::createTokenValue($tokenLength);
        $token->expires = $expires;
        $token->createdAt = new \DateTime();
        $token->expiresAt = new \DateTime();
        $token->expiresAt->add(new \DateInterval('PT'.$expires.'S'));
        return $token;
    }

    #endregion

    /**
     * Type of the token ("Access" or "Refresh).
     * @var TokenType
     */
    public TokenType $type = TokenType::Unknown;

    /**
     * The token value.
     * @var string
     */
    public string $value;

    /**
     * The local date and time this token was created.
     * @var \DateTime
     */
    public \DateTime $createdAt;

    /**
     * The time this token is valid in seconds.
     * @var integer
     */
    public float $expires;
    
    /**
     * The local date and time this token will expire.
     * @var \DateTime
     */
    public \DateTime $expiresAt;

    /**
     * Optional reference value to another (like Access -> Refresh or Refresh -> Access)
     * @var string|null
     */
    public ?string $reference = null;

    /**
     * Optional user name (from login)
     * @var string|null
     */
    public ?string $userId = null;

    public bool $persistent = false;

    /**
     * Check, if the token is valid or not (expired).
     * @return boolean
     */
    public function isValid(): bool {
        return (new \DateTime()) > $this->expiresAt;
    }

    /**
     * Get the token remaining seconds. If the value is 0.0 or below, the token has no remaining seconds left.
     * @return float
     */
    public function getRemaining(): float {
        return $this->expires - ((new \DateTime())->getTimestamp() - $this->createdAt->getTimestamp());
    }
}