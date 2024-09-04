<?php
namespace Idm\PiperLink\Models;

use Idm\PiperLink\Types\Operation;
use Idm\PiperLink\Types\StatusType;
use Pyther\Json\Attributes\Json;

/**
 * The products model. 
 */
class Product extends BaseModel
{
    /**
     * The unique product id.
     * @var string|null
     */
    public ?string $id = null;

    /**
     * The product model (is used, if no id is available).
     * @var string|null
     */
    public ?string $model = null;

    /**
     * Optional primary product reference.
     * @var PrimaryProduct|null
     */
    public ?PrimaryProduct $primary = null;

    /**
     * Is the product model "Active" or "Inactive"?
     * @var StatusType|null
     */
    public ?StatusType $status = null;

    /**
     * The product weight in gram.
     * @var integer|null
     */
    public ?int $weight = null;

    /**
     * The product width in mm.
     * @var integer|null
     */
    public ?int $width = null;

    /**
     * The product length in mm.
     * @var integer|null
     */
    public ?int $length = null;

    /**
     * The product height in mm.
     * @var integer|null
     */
    public ?int $height = null;

    /**
     * The product shipping time.
     * @var ShippingTime|null
     */
    public ?ShippingTime $shippingTime = null;

    /**
     * The primary manufacturer for this product.
     * @var Manufacturer|null
     */
    public ?Manufacturer $manufacturer = null;

    /**
     * The vat class this product belongs to.
     * @var VatClass|null
     */
    public ?VatClass $vatClass = null;

    /**
     * The products unit and value used for basic price calculations.
     * @var Unit|null
     */
    public ?Unit $unit = null;

    /**
     * How long does it takes to process this product in hours?
     * This is the time from order creation to parcel notification.
     * @var integer|null
     */
    public ?int $processingTime = null;

    /**
     * The product condition.
     * @var ProductCondition|null
     */
    #[Json("Condition")]
    public ?ProductCondition $condition = null;

    /**
     * Collection of product texts per language.
     * @var ProductTextBundle[]|null
     */
    public ?array $texts = null;

    /**
     * List of all categories.
     * @var Category[]|null
     */
    public ?array $categories = null;

    /**
     * ListPaymentMethods of stock corrections.
     * @var StockCorrection[]|null
     */
    public ?array $stockCorrections = null;

    /**
     * List of sales prices.
     * @var SalesPrice[]|null
     */
    public ?array $salesPrices = null;

    /**
     * List of barcodes.
     * @var Barcode[]|null
     */
    public ?array $barcodes = null;

    /**
     * List of channel states.
     * @var ChannelState[]|null
     */
    public ?array $channels = null;

    /**
     * List of all shipping profiles linked with this product.
     * @var ShippingProfile[]|null
     */
    public ?array $shippingProfiles = null;

    /**
     * List of all attributes linked with this product.
     * Attributes can be used to define a variation of a product.
     * @var ProductAttributeLink[]|null
     */
    public ?array $attributes = null;

    /**
     * List of all property values linked with this product.
     * @var PropertyValue[]|null
     */
    public ?array $properties = null;

    /**
     * List of all tag values linked with this product.
     * @var Tag[]|null
     */
    public ?array $tags = null;

    /**
     * List of all images linked with this product.
     * @var ProductImageLink[]|null
     */
    public ?array $images = null;

    /**
     * List of all product bundle components.
     * @var ProductBundleComponent|null
     */    
    public ?array $bundle = null;

    /**
     * Date and time the product was created.
     * @var \DateTime|null
     */
    public ?\DateTime $createdAt = null;
    
    /**
     * Date and time the product was updated.
     * @var \DateTime|null
     */
    public ?\DateTime $updatedAt = null;
    
    public ?Operation $operation = null;

    public ?string $error = null;

    function __construct(?string $id = null) {
        $this->id = $id;
    }

    #region methods
    
    /**
     * Is this product a primary product?
     * @return boolean
     */
    public function isPrimary(): bool {
        return $this->primary === null;  
    }

    /**
     * Is this product a variantion product?
     * @return boolean
     */
    public function isVariation(): bool {
        return $this->primary !== null;
    }
    
    #endregion
}