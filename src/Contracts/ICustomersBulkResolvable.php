<?php
namespace Idm\PiperLink\Contracts;

use Idm\PiperLink\Models\Product;

/**
 * Try to find customers ids by given properties.
 * If the the id is already set, simply return the customer itself.
 * The customer Id may only be set if the customer can be clearly identified!
 */
interface ICustomersBulkResolvable
{
    /**
     * Returns the given customers filled with the 'id' property.
     *
     * @param Customer[]|null $customers The array of customers to find the ids for.
     * @param array $arguments
     * @return \Generator<Customer|null> Generates customer objects.
     */
    public function resolveCustomers(array $customers, array $arguments = []): \Generator;
}