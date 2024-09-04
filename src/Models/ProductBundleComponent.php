<?php
namespace Idm\PiperLink\Models;

use Idm\PiperLink\Types\Operation;

class ProductBundleComponent
{
    /**
     * The component id.
     * @var string|null
     */
    public ?string $id = null;

    /**
     * The product id of this component.
     * @var string|null
     */
    public ?string $productId = null;

    /**
     * The component quantity.
     * @var float|null
     */
    public ?float $quantity = null;

    public Operation $operation = Operation::Unknown;

}