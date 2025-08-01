<?php

namespace Idm\PiperLink\Contracts;

use Idm\PiperLink\Models\Order;

interface IOrdersFetchableByNumber
{
    /**
     * Fetch a single order.
     * 
     * @param string $orderNumber The number of the order (visible by the customer)
     * @param array $arguments 
     * @return Order|null The order object or null on error.
     */
    public function getOrderByNumber(string $orderNumber, array $arguments = []): ?Order;
}
