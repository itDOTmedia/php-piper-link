<?php
namespace Idm\PiperLink\Contracts;

use Idm\PiperLink\Models\Product;

/**
 * Try to find product ids by given properties.
 * If the the id is already set, simply return the product.
 * The product Id may only be set if the product can be clearly identified!
 */
interface IProductsBulkResolvable
{
    /**
     * Returns the given products filled with the 'id' property.
     *
     * @param Product[]|null $products The array of products to find the id for.
     * @param array $arguments
     * @return \Generator<Product|null> Generate products objects.
     */
    public function resolveProducts(array $products, array $arguments = []): \Generator;
}