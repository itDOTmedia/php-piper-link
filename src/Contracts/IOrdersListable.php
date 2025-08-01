<?php

namespace Idm\PiperLink\Contracts;

use Generator;
use Idm\PiperLink\Models\Order;

interface IOrdersListable
{
    // TODO: Add arguments

    /**
     * Fetch a list of orders by arguments
     * 
     * @param array $arguments 
     * @return \Generator<Order|null> Generate the orders objects on success or null entries on error.
     */
    public function getOrders(array $arguments = []): \Generator;
}
