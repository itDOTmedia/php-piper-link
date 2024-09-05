<?php
namespace Idm\PiperLink\Models;

/**
 * The document item model.
 */
class DocumentItem extends BaseModel
{
    public ?string $key = null;
    public ?string $uuid = null;
    public ?string $parentUuid = null;
    public ?string $displayType = null;
    public ?string $sku = null;
    public ?string $text = null;    
    public ?string $position = null;
    public ?float $quantity = null;
    public ?float $singlePrice = null;
    public ?float $disountValue = null;
    public ?float $totalPrice = null;
    public ?float $vatRate = null;
    public ?\DateTime $shippingDate = null;
}