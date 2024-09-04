<?php
namespace Idm\PiperLink\Models;

/**
 * Model that links a product with a single attribute and attibute value.
 */
class ProductAttributeLink
{
    /**
     * The product attribute by id.
     * @var string|null
     */
    public ?string $attributeId = null;

    /**
     * The product attribute by name.
     * @var string|null
     */
    public ?string $attributeName = null;

    /**
     * The product attribute value by id.
     * @var string|null
     */    
    public ?string $attributeValueId = null;

    /**
     * The (external) product attribute value by name.
     * @var string|null
     */
    public ?string $attributeValueName = null;
}