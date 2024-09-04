<?php
namespace Idm\PiperLink\Models;

use Idm\PiperLink\Types\StatusType;

/**
 * Model of a single sales price.
 */
class SalesPrice
{
    /**
     * The sales price list this price belongs to.
     * @var string|null
     */
    public ?string $priceListId = null;

    /**
     * Enable/Disable the given price.
     * @var StatusType|null
     */
    public ?StatusType $status = StatusType::Active;

    /**
     * The gross price value.
     * @var float|null
     */
    public ?float $gross = null;

    /**
     * The net price value.
     * @var float|null
     */
    public ?float $net = null;
}