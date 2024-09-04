<?php
namespace Idm\PiperLink\Contracts;

use Idm\PiperLink\Models\Customer;

interface ICustomersBulkFetchable
{
    /**
     * Fetch a list of customers.
     *
     * @param string[]|null $ids The array of customers ids to fetch or null to fetch all customers.
     * @param array $arguments
     * @return \Generator<Customer|null> Generate customers objects on success or null entries on error.
     */
    public function getCustomers(?array $ids, array $arguments = []): \Generator;
}