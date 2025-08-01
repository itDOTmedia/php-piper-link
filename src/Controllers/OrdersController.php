<?php

namespace Idm\PiperLink\Controllers;

use Idm\PiperLink\Contracts\IOrdersFetchable;
use Idm\PiperLink\Contracts\IOrdersFetchableByNumber;
use Idm\PiperLink\Contracts\IOrdersListable;
use Idm\PiperLink\Exceptions\NotFoundException;
use Idm\PiperLink\PiperLink;
use Idm\PiperLink\StringX;
use Idm\PiperLink\Utils;
use Pyther\Ioc\Ioc;
use Pyther\Json\Json;

/**
 * Controller for all "orders/..." routes.
 */
class OrdersController extends BaseController
{
    public function __construct(PiperLink $piperLink)
    {
        parent::__construct($piperLink);
    }

    public function execute(string $path)
    {
        // no cache
        $this->getResponse()->addHeader("Cache-Control", "no-store");
        $this->getResponse()->addHeader("Pragma", "no-cache");

        // GET
        if ($this->isRoute("GET", "orders", $path)) {
            $this->GET_bulk();
        } else if ($this->isRoute("GET", "orders/{id}", $path)) {
            $id = $this->getRouteParameter("id") ?? throw new \Exception("Invalid order id given!");
            $this->GET_byId($id);
        } else if ($this->isRoute("GET", "orders/by-number/{orderNumber}", $path)) {
            $orderNumber = $this->getRouteParameter("orderNumber") ?? throw new \Exception("Invalid order number given!");
            $this->GET_byNumber($orderNumber);
        }
    }

    private function GET_bulk(): void
    {
        $states = $this->getRequest()->GET("states");
        if (!StringX::isNullOrWhiteSpace($states)) {
            $states = explode(',', $states);
        } else {
            $states = null;
        }

        /** @var IOrdersListable */
        $instance = Ioc::get(IOrdersListable::class);
        $orders = iterator_to_array($instance->getOrders([
            'states' => $states,
            'startDate' => $_GET['startDate'] ?? null,
            'endDate' => $_GET['endDate'] ?? null,
        ]));

        if (count($orders) === 0) {
            $this->getResponse()->setData(Json::serialize([], Utils::$Json), 404);
        } else if (in_array(false, $orders) && in_array(true, $orders)) {
            $this->getResponse()->setData(Json::serialize($orders, Utils::$Json), 207);
        } else if (in_array(false, $orders)) {
            throw new NotFoundException();
        } else {
            $this->getResponse()->setData(Json::serialize($orders, Utils::$Json), 200);
        }
    }

    private function GET_byId(string $id): void
    {
        /** @var IOrdersFetchable */
        $instance = Ioc::get(IOrdersFetchable::class);
        $orders = iterator_to_array($instance->getOrdersByIds([$id]));
        if (count($orders) < 1 || $orders[0] == null) {
            throw new NotFoundException();
        }
        $data = Json::serialize($orders[0], Utils::$Json);
        $this->getResponse()->setData($data);
    }

    private function GET_byNumber(string $orderNumber): void
    {
        /** @var IOrdersFetchableByNumber */
        $instance = Ioc::get(IOrdersFetchableByNumber::class);
        $order = $instance->getOrderByNumber($orderNumber);
        if ($order == null) {
            throw new NotFoundException();
        }
        $data = Json::serialize($order, Utils::$Json);
        $this->getResponse()->setData($data);
    }
}
