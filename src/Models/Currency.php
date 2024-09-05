<?php
namespace Idm\PiperLink\Models;

/**
 * The currency model.
 */
class Currency extends BaseModel
{
    /**
     * ISO 4217 Code (like "EUR", "USD", ...).
     * @var string|null
     */
    public ?string $code = null;

    /**
     * Currency name (like "Euro", ...).
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Currency symbol (like "€", "$", ...).
     * @var string|null
     */
    public ?string $symbol = null;

    public function __toString() {
        return $this->symbol." - ".$this->name;
    }
}