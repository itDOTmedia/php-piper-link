<?php
namespace Idm\PiperLink\Contracts;

use Idm\PiperLink\Models\Product;

interface IProductsBulkFetchable
{
    /**
     * Fetch a list of products.
     *
     * @param string[]|null $ids The array of products ids to fetch or null to fetch all products.
     * @param array $arguments
     * @return \Generator<Product|null> Generate products objects on success or null entries on error.
     */
    public function getProducts(?array $ids, array $arguments = []): \Generator;
}