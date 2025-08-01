<?php

namespace Idm\PiperLink\Models;

use Idm\PiperLink\Types\OrderItemType;

/**
 * The order item model. An order item can be a product, shipping cost, a coupon, aso. defined by the 'ItemType'.
 */
class OrderItem extends BaseModel
{
    /**
     * The item id (must be unique per order).
     * @var string|null
     */
    public ?string $id = null;

    /**
     * The type of this item (product, shipping cost, coupon, ...).
     * @var OrderItemType
     */
    public OrderItemType $itemType = OrderItemType::Product;

    /**
     * The item's Stock-Keeping-Unit.
     * @var string|null
     */
    public ?string $sku = null;

    /**
     * The item's model.
     * @var string|null
     */
    public ?string $model = null;

    /**
     * The name of the item.
     * @var string|null
     */
    public ?string $name = null;

    /**
     * The item quantity.
     * @var float
     */
    public float $quantity = 0.0;

    /**
     * The position of the item in the order (one-based!).
     * @var int
     */
    public int $position;

    /**
     * The price currency.
     * @var Currency|null
     */
    public ?Currency $currency = null;

    /**
     * The final net price without discounts.
     * @var float
     */
    public float $netBasePrice;

    /**
     * The surcharge as absolute value.
     * @var float
     */
    public float $surcharge = 0.0;

    /**
     * The item VAT value like (1.23 €).
     * @var float
     */
    public float $vatValue;

    /**
     * List of all item VATs.
     * @var Vat[]
     */
    public array $vats = [];

    /**
     * Is the discount in percent or in absolute value?
     * @var bool
     */
    public bool $disountIsPercent = false;

    /**
     * Discount value in percent or as absolute value.
     * @var float
     */
    public float $disountValue = 0.0;

    /**
     * What items apply to the discount.
     * @var OrderItem[]|null
     */
    public ?array $discountedItems = null;

    /**
     * Optional shipping time of the item.
     * @var ShippingTime|null
     */
    public ?ShippingTime $shippingTime = null;
}
