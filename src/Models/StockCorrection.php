<?php
namespace Idm\PiperLink\Models;

/**
 * A stock correction value.
 */
class StockCorrection
{
    /**
     * The optional warehouse id.
     * @var string|null
     */
    public ?string $warehouseId = null;

    /**
     * The final quantity.
     * @var float
     */
    public float $quantity = 0;
}