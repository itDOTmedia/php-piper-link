<?php

namespace Idm\PiperLink\Contracts;

use Idm\PiperLink\Models\Order;

interface IOrdersFetchable
{
    /**
     * Fetch a list of orders.
     * 
     * @param string[]|null $ids The array of orders ids to fetch
     * @param array $arguments 
     * @return \Generator<Order|null> Generate the orders objects on success or null entries on error.
     */
    public function getOrdersByIds(?array $ids, array $arguments = []): \Generator;
}
