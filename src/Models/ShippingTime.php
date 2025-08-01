<?php

namespace Idm\PiperLink\Models;

/**
 * Model of a single shipping time.
 */
class ShippingTime
{
    /**
     * The id of the shipping time.
     * @var string
     */
    public string $id;

    /**
     * The name of the shipping time.
     * @var string|null
     */
    public ?string $name;

    /**
     * The shipping time in days.
     * @var integer|null
     */
    public ?int $days;

    /**
     * Create a new instance by id, name and days.
     * @param string $id
     * @param string|null $name
     * @param integer|null $days
     */
    function __construct(string $id = "", ?string $name = null, ?int $days = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->days = $days;
    }
}
