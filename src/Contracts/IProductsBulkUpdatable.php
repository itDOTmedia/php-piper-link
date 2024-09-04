<?php
namespace Idm\PiperLink\Contracts;

use Idm\PiperLink\Models\Product;

interface IProductsBulkUpdatable
{
    /**
     * Updates a bulk of products.
     *
     * @param Products[] $products Array of products to update.
     * @param array $arguments
     * @return \Generator<Product|string> Generates updated products on success or error strings on failure.
     */
    public function updateProducts(array $products, array $arguments = []): \Generator;
}