<?php
namespace Idm\PiperLink\Contracts;

use Idm\PiperLink\Models\Product;

/**
 * Try to find customers ids by given properties.
 * If the the id is already set, simply return the customer.
 * The cutomer Id may only be set if the customer can be clearly identified!
 */
interface ICustomersBulkResolvable
{
    /**
     * Returns the given customers filled with the 'id' property.
     *
     * @param Customer[]|null $customers The array of products to find the id for.
     * @param array $arguments
     * @return \Generator<Customer|null> Generate customer objects.
     */
    public function resolveCustomers(array $customers, array $arguments = []): \Generator;
}