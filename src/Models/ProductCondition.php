<?php
namespace Idm\PiperLink\Models;

class ProductCondition
{
    /**
     * The unique product condition id (if any).
     * @var string|null
     */
    public ?string $id = null;

    /**
     * The optional product condition text represenatation.
     * @var string|null
     */
    public ?string $text = null;
}