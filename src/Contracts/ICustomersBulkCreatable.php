<?php
namespace Idm\PiperLink\Contracts;

use Idm\PiperLink\Models\Customer;

interface ICustomersBulkCreatable
{
    /**
     * Create a bulk of new customers.
     *
     * @param Customer[] $customers Array of customers to create.
     * @param array $arguments
     * @return \Generator<Customer|string> Generates new customers on success or error strings on failure.
     */
    public function createCustomers(array $customers, array $arguments = []): \Generator;
}