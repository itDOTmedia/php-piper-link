<?php
namespace Idm\PiperLink\Contracts;

interface ICustomersBulkDeletable
{
    /**
      * Deletes multiple customers by given ids.
      *
      * @param string[] $ids The array of customers ids to delete.
      * @param array $arguments
      * @return \Generator<Customer|string> Generates deleted customers with ids on success or error strings on failure.
      */
    public function deleteCustomers(array $ids, array $arguments = []): \Generator;
}