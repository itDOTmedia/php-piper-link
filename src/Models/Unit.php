<?php
namespace Idm\PiperLink\Models;

/**
 * Model of a single unit value.
 */
class Unit
{
    /**
     * The id of this unit.
     * @var string|null
     */
    public ?string $id = null;

    /**
     * The code for Units of Measure used in international trade (UN/ECE Recommendation N°20) like "LTR" for litre.
     * @var string|null
     */
    public ?string $code = null;

    /**
     * The unit name in default language like "Litre" or "Liter".
     * @var string|null
     */
    public ?string $name = null;

    /**
     * The value for a given unit (like "5.2" for "5.2 litre").
     * @var float|null
     */
    public ?float $value = null;

    /**
     * Show or hide the basic price.
     * @var bool|null
     */
    public ?bool $showBasicPrice = null;
}