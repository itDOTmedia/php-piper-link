<?php
namespace Idm\PiperLink\Contracts;

use Idm\PiperLink\Models\Product;

interface IProductsBulkCreatable
{
    /**
     * Create a bulk of new products.
     *
     * @param Product[] $products Array of products to create.
     * @param array $arguments
     * @return \Generator<Product|string> Generates new products on success or error strings on failure.
     */
    public function createProducts(array $products, array $arguments = []): \Generator;
}