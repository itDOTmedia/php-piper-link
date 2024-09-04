<?php
namespace Idm\PiperLink\Contracts;

use Idm\PiperLink\Models\Customer;

interface ICustomersBulkUpdatable
{
    /**
     * Updates a bulk of customers.
     *
     * @param Customer[] $customers Array of customers to update.
     * @param array $arguments
     * @return \Generator<Customer|string> Generates updated customers on success or error strings on failure.
     */
    public function updateCustomers(array $customers, array $arguments = []): \Generator;
}