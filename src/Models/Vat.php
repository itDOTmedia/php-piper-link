<?php
namespace Idm\PiperLink\Models;

/**
 * The order vat entry. Every order have one vat entry per vat rate. 
 * It contains calculated values based on the underlying `VatClass`.
 */
class Vat
{
    /**
     * This is a foreign key to VatClass.Id.
     * @var string
     */
    public string $vatClassId;

    /**
     * The vat rate in percent (like 19 for 19%).
     * @var float
     */
    public float $rate;

    /**
     * The vat value (like 17.48).
     * @var float
     */
    public float $value;

    /**
     * The vat's net amount used by the current vat rate (like 92.01).
     * @var float
     */
    public float $netTotal;

    /**
     * The vat's groß amount used by the current vat rate (like 109.49).
     * @var float
     */
    public float $grossTotal;

    public function __construct(string $vatClassId = "", float $rate = 0, float $value = 0, float $netTotal = 0, float $grossTotal = 0)
    {
        $this->vatClassId = $vatClassId;
        $this->rate = $rate;
        $this->value = $value;
        $this->netTotal = $netTotal;
        $this->grossTotal = $grossTotal;
    }
}