<?php
namespace Idm\PiperLink\Models;

/**
 * Model of a single vat rate (not value) for a given country.
 */
class VatClass
{
    /**
     * The unique vat class id.
     * @var string|null
     */
    public ?string $id = null;

    /**
     * The optional country this vat rate belongs to.
     * @var Country|null
     */
    public ?Country $country = null;

    /**
     * The optional tax id number for the given country.
     * @var string|null
     */
    public ?string $taxIdNumber = null;

    /**
     * The vat rate value (like "19.00").
     * @var float|null
     */
    public ?float $rate = null;

    /**
     * The vat name for this rate.
     * @var string|null
     */
    public ?string $name = null;
}