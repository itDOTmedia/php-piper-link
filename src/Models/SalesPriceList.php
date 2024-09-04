<?php
namespace Idm\PiperLink\Models;

/**
 * Model of a single price list.
 */
class SalesPriceList extends BaseModel
{
    /**
     * The unique price list id.
     * @var string|null
     */
    public ?string $id = null;

    /**
     * The display name of the price list.
     * @var string|null
     */
    public ?string $name = null; 

    /**
     * Optional sorting priority.
     * @var integer|null
     */
    public ?int $sorting = null;
}