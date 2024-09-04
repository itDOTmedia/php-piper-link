<?php
namespace Idm\PiperLink\Contracts;

interface IProductsBulkDeletable
{
    /**
      * Deletes multiple products by given ids.
      *
      * @param string[] $ids The array of products ids to delete.
      * @param array $arguments
      * @return \Generator<Product|string> Generates deleted products with ids on success or error strings on failure.
      */
    public function deleteProducts(array $ids, array $arguments = []): \Generator;
}