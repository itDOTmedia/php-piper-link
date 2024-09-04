<?php
namespace Idm\PiperLink\Models;

use Idm\PiperLink\Types\BarcodeType;

/**
 * A single barcode value like GTIN/EAN, ISBN, aso.
 */
class Barcode
{
    /**
     * The external barcode id.
     * @var string|null
     */
    public ?string $d = null;

    /**
     * The barcode type.
     * @var BarcodeType|null
     */
    public ?BarcodeType $type = null;

    /**
     * The external name.
     * @var string|null
     */
    public ?string $name = null;

    /**
     * The barcode value.
     * @var string|null
     */
    public ?string $code = null;
}