<?php
namespace Idm\PiperLink\Models;

/**
 * Defines the model of a single product text bundle which contains a collection of texts for a specified language.
 */
class ProductTextBundle
{
    /**
     * The language code for all texts in this bundle.
     * @var string
     */
    public string $language = "en";

    /**
     * The name of the product.
     * @var string|null
     */
    public ?string $name = null;

    /**
     * The description of the product.
     * @var string|null
     */
    public ?string $description = null;

    /**
     * The short/preview description of the product.
     * @var string|null
     */
    public ?string $shortDescription = null;

    /**
     * The product technical data.
     * @var string|null
     */
    public ?string $technicalData = null;

    /**
     * The meta description of the product.
     * @var string|null
     */
    public ?string $metaDescription = null;

    /**
     * The meta keywords of the product.
     * @var string|null
     */
    public ?string $metaKeywords = null;
}