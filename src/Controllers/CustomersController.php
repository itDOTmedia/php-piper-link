<?php
namespace Idm\PiperLink\Controllers;

use Idm\PiperLink\Contracts\ICustomersBulkCreatable;
use Idm\PiperLink\Contracts\ICustomersBulkDeletable;
use Idm\PiperLink\Contracts\ICustomersBulkFetchable;
use Idm\PiperLink\Contracts\ICustomersBulkResolvable;
use Idm\PiperLink\Contracts\ICustomersBulkUpdatable;
use Idm\PiperLink\Exceptions\NotFoundException;
use Idm\PiperLink\Models\Customer;
use Idm\PiperLink\PiperLink;
use Idm\PiperLink\StringX;
use Idm\PiperLink\Types\Operation;
use Idm\PiperLink\Utils;
use Pyther\Ioc\Ioc;
use Pyther\Json\Json;

/**
 * Controller for all "customers/..." routes.
 */
class CustomersController extends BaseController
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

        // POST
        if ($this->IsRoute("POST", "customers/bulk", $path)) {
            $this->POST_bulk();
        }
        else if ($this->IsRoute("POST", "customers", $path)) {
            $this->POST();
        }
        // PUT
        else if ($this->IsRoute("PUT", "customers/{id}", $path)) {
            $id = $this->getRouteParameter("id") ?? throw new \Exception("Invalid customer id given!");
            $this->PUT_byId($id);
        }
        else if ($this->IsRoute("PUT", "customers", $path)) {
            $this->PUT_bulk();
        } 
        // GET
        else if ($this->IsRoute("GET", "customers/{id}", $path)) {
            $id = $this->getRouteParameter("id") ?? throw new \Exception("Invalid customer id given!");
            $this->GET_byId($id);
        }
        else if ($this->IsRoute("GET", "customers", $path)) {
            $this->GET_bulk();
        } 
        // DELETE
        else if ($this->IsRoute("DELETE", "customers/{id}", $path)) {
            $id = $this->getRouteParameter("id") ?? throw new \Exception("Invalid customer id given!");
            $this->DELETE_byId($id);
        }
        else if ($this->IsRoute("DELETE", "customers", $path)) {
            $this->DELETE_bulk();
        }
        // other
        else
            throw new NotFoundException();
    }

    #region bulk routes

    function POST_bulk()
    {
        /** @var ICustomersBulkCreatable */
        $instance = Ioc::get(ICustomersBulkCreatable::class);
        $result = [];
        $json = $this->getRequest()->getRawPayload();

        $customers = Json::deserializeArrayOf($json, Customer::class, Utils::$Json);
        foreach ($instance->createCustomers($customers, []) as $item) {
            if (is_string($item)) {
                $error = $item;
                $item = new Customer();
                $item->operation = Operation::Error;
                $item->error = $error;
            } else {
                $item->operation = Operation::Insert;
            }
            $result[] = $item;
        }

        $statusCode = 207;
        if (Utils::all($result, function($e) { return $e->operation === Operation::Insert; })) $statusCode = 200;
        else if (Utils::all($result, function($e) { return $e->error === "Resource not found!"; })) $statusCode = 404;
        else if (Utils::all($result, function($e) { return $e->operation !== Operation::Insert; })) $statusCode = 400;

        $this->getResponse()->setData(Json::serialize($result, $statusCode < 400 ? Utils::$Json : Utils::$JsonMinimal), $statusCode);
    }

    function PUT_bulk()
    {
        /** @var ICustomersBulkUpdatable */
        $instance = Ioc::get(ICustomersBulkUpdatable::class);
        $result = [];
        $json = $this->getRequest()->getRawPayload();   
        $customers = Json::deserializeArrayOf($json, Customer::class, Utils::$Json);
        
        foreach ($instance->updateCustomers($customers, []) as $item) {
            if (is_string($item)) {
                $error = $item;
                $item = new Customer();
                $item->id = $customers[count($result)]->id;
                $item->operation = Operation::Error;
                $item->error = $error == "404" ? "Resource not found!" : $error;
            } else {
                $item->operation = Operation::Update;
            }           
            $result[] = $item;
        }
        
        $statusCode = 207;
        if (Utils::all($result, function($e) { return $e->operation === Operation::Update; })) $statusCode = 200;
        else if (Utils::all($result, function($e) { return $e->error === "Resource not found!"; })) $statusCode = 404;
        else if (Utils::all($result, function($e) { return $e->operation !== Operation::Update; })) $statusCode = 400;

        $this->getResponse()->setData(Json::serialize($result, $statusCode < 400 ? Utils::$Json : Utils::$JsonMinimal), $statusCode);
    }    

    function GET_bulk() {        
        $ids = $this->getRequest()->GET("ids");
        if (!StringX::isNullOrWhiteSpace($ids)) {
            $ids = explode(',', $ids);
        } else {
            $ids = null;
        }

        /** @var ICustomersBulkFetchable */
        $instance = Ioc::get(ICustomersBulkFetchable::class);
        $customers = iterator_to_array($instance->getCustomers($ids, [
            'offset' => $_GET['offset'] ?? null,
            'limit' => $_GET['limit'] ?? null
        ]));

        if (count($customers) === 0) {
            $this->getResponse()->setData(Json::serialize([], Utils::$Json), 404);
        } else if (in_array(false, $customers) && in_array(true, $customers)) {
            $this->getResponse()->setData(Json::serialize($customers, Utils::$Json), 207);
        } else if (in_array(false, $customers)) {
            throw new NotFoundException();
        } else {
            $this->getResponse()->setData(Json::serialize($customers, Utils::$Json), 200);
        }        
    }

    function DELETE_bulk()
    {
        /** @var ICustomersBulkDeletable */
        $instance = Ioc::get(ICustomersBulkDeletable::class);
        $result = [];
        $json = $this->getRequest()->getRawPayload();
        $customers = Json::deserializeArrayOf($json, Customer::class, Utils::$Json);
        $customers = $this->resolveCustomers($customers);
        $ids = array_map(function($customer) { return $customer->id;}, $customers);

        foreach ($instance->deleteCustomers($ids, []) as $item) {
            if (is_string($item)) {
                $error = $item;
                $item = new Customer();
                $item->operation = Operation::Error;
                $item->error = $error == "404" ? "Resource not found!" : $error;
                $item->id = $ids[count($result)] ?? null;
            } else {
                $item->operation = Operation::Delete;
            }            
            $result[] = $item;
        }

        $statusCode = 207;
        if (Utils::all($result, function($e) { return $e->operation === Operation::Delete; })) $statusCode = 200;
        else if (Utils::all($result, function($e) { return $e->error === "Resource not found!"; })) $statusCode = 404;
        else if (Utils::all($result, function($e) { return $e->operation !== Operation::Delete; })) $statusCode = 400;

        $this->getResponse()->setData(Json::serialize($result, Utils::$JsonMinimal), $statusCode);
    }

    #endregion

    #region single routes (untested!!!)

    function POST()
    {
        $instance = Ioc::get(ICustomersBulkUpdatable::class);
        $json = $this->getRequest()->getRawPayload();
        $statusCode = 200;        
        $customer = Json::deserialize($json, Customer::class, Utils::$Json);
        $result = $instance->createCustomers([$customer], []);    
        if (is_string($result)) {
            $error = $result;
            $result = new Customer();
            $result->operation = Operation::Error;
            $result->error = $error;
            $statusCode == 400;
        } else {
            $result->operation = Operation::Insert;
        }
        $this->getResponse()->setData(Json::serialize($result, $statusCode < 400 ? Utils::$Json : Utils::$JsonMinimal), $statusCode);
    }

    function PUT_byId()
    {
        $instance = Ioc::get(ICustomersBulkUpdatable::class);
        $json = $this->getRequest()->getRawPayload();
        $statusCode = 200;        
        $customer = Json::deserialize($json, Customer::class, Utils::$Json);
        $result = $instance->updateCustomers([$customer], []);    
        if (is_string($result)) {
            $error = $result;
            $result = new Customer();
            $result->operation = Operation::Error;
            $result->error = $error == "404" ? "Resource not found!" : $error;
            $statusCode = $error == "404" ? 404 : 400;
        } else {
            $result->operation = Operation::Update;
        }
        $this->getResponse()->setData(Json::serialize($result, $statusCode < 400 ? Utils::$Json : Utils::$JsonMinimal), $statusCode);
    }
    
    function GET_byId(string $id)
    {
        $instance = Ioc::get(ICustomersBulkFetchable::class);
        $customers = $instance->getCustomers([$id]) ?? throw new NotFoundException();
        if (count($customers) < 1 || $customers[0] === null) {
            throw new NotFoundException();
        }
        $data = Json::serialize($customers[0], Utils::$Json);
        $this->getResponse()->setData($data);
    }

    function DELETE_byId(string $id)
    {
        $instance = Ioc::get(ICustomersBulkDeletable::class);
        $customers = iterator_to_array($instance->deleteCustomers([$id]));
        $item = $customers[0] ?? null;

        if ($item != null) {
            $item->operation = Operation::Delete;
        }
        
        if ($customers === null || $customers[0] == null) {
            $this->getResponse()->setData(Json::serialize($item, Utils::$JsonMinimal), 404);
        } else {
            $this->getResponse()->setData(Json::serialize($item, Utils::$JsonMinimal), 200);
        }
    }

    #endregion

    private function resolveCustomers(array $customers): array {
        try {
            /** @var ICustomersBulkResolvable */
            $instance = Ioc::get(ICustomersBulkResolvable::class);
            $result = \iterator_to_array($instance->resolveCustomers($customers, []));
            // if (count($result) === count($customers)) {
            $customers = $result;
            // }
        } catch (\Exception $ex) {

        }
        return $customers;
    }
}