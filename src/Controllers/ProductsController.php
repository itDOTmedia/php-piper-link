<?php
namespace Idm\PiperLink\Controllers;

use Idm\PiperLink\Contracts\IProductsBulkCreatable;
use Idm\PiperLink\Contracts\IProductsBulkDeletable;
use Idm\PiperLink\Contracts\IProductsBulkFetchable;
use Idm\PiperLink\Contracts\IProductsBulkResolvable;
use Idm\PiperLink\Contracts\IProductsBulkUpdatable;
use Idm\PiperLink\Exceptions\NotFoundException;
use Idm\PiperLink\Models\Product;
use Idm\PiperLink\PiperLink;
use Idm\PiperLink\StringX;
use Idm\PiperLink\Types\Operation;
use Idm\PiperLink\Utils;
use Pyther\Ioc\Ioc;
use Pyther\Json\Json;

class ProductsController extends BaseController
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
        if ($this->IsRoute("POST", "products/bulk", $path)) {
            $this->POST_bulk();
        }
        /*else if ($this->IsRoute("POST", "products", $path)) {
            $this->POST();            
        }*/

        // PUT
        /*else if ($this->IsRoute("PUT", "products/{id}", $path)) {
            $id = $this->getRouteParameter("id") ?? throw new \Exception("Invalid product id given!");
            $this->PUT_byId($id);
        }*/
        else if ($this->IsRoute("PUT", "products", $path)) {
            $this->PUT_bulk();
        }

        // GET
        /*else if ($this->IsRoute("GET", "products/{id}", $path)) {
            $id = $this->getRouteParameter("id") ?? throw new \Exception("Invalid product id given!");
            $this->GET_byId($id);
        }*/
        else if ($this->IsRoute("GET", "products", $path)) {
            $this->GET_bulk();              
        }

        // DELETE
        /*else if ($this->IsRoute("DELETE", "products/{id}", $path)) {
            $id = $this->getRouteParameter("id") ?? throw new \Exception("Invalid product id given!");
            $this->DELETE_byId($id);
        }*/
        else if ($this->IsRoute("DELETE", "products", $path)) {
            $this->DELETE_bulk();
        }
        // other
        else {
            throw new NotFoundException();
        }        
    }

    #region bulk routes

    function POST_bulk()
    {
        /** @var IProductsBulkCreatable */
        $instance = Ioc::get(IProductsBulkCreatable::class);
        $result = [];
        $json = $this->getRequest()->getRawPayload();

        $products = Json::deserializeArrayOf($json, Product::class, Utils::$Json);
        foreach ($instance->createProducts($products, []) as $item) {
            if (is_string($item)) {
                $error = $item;
                $item = new Product();
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
        /** @var IProductsBulkUpdatable */
        $instance = Ioc::get(IProductsBulkUpdatable::class);
        $result = [];
        $json = $this->getRequest()->getRawPayload();
        $products = Json::deserializeArrayOf($json, Product::class, Utils::$Json);
        $products = $this->resolveProducts($products);

        foreach ($instance->updateProducts($products, []) as $item) {
            if (is_string($item)) {
                $error = $item;
                $item = new Product();
                $item->operation = Operation::Error;
                $item->error = $error == "404" ? "Resource not found!" : $error;
                $item->id = $products[count($result)]->id;
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

        /** @var IProductsBulkFetchable */
        $instance = Ioc::get(IProductsBulkFetchable::class);
        $products = iterator_to_array($instance->getProducts($ids, [
            'offset' => $_GET['offset'] ?? null,
            'limit' => $_GET['limit'] ?? null
        ]));

        if (count($products) === 0) {
            $this->getResponse()->setData(Json::serialize([], Utils::$Json), 404);
        } else if (in_array(false, $products) && in_array(true, $products)) {
            $this->getResponse()->setData(Json::serialize($products, Utils::$Json), 207);
        } else if (in_array(false, $products)) {
            throw new NotFoundException();
        } else {
            $this->getResponse()->setData(Json::serialize($products, Utils::$Json), 200);
        }        
    }

    function DELETE_bulk()
    {
        /** @var IProductsBulkDeletable */
        $instance = Ioc::get(IProductsBulkDeletable::class);
        $result = [];
        $json = $this->getRequest()->getRawPayload();

        $products = Json::deserializeArrayOf($json, Product::class, Utils::$Json);
        $products = $this->resolveProducts($products);

        $ids = array_map(function($product) { return $product->id;}, $products);

        foreach ($instance->deleteProducts($ids, []) as $item) {
            if (is_string($item)) {
                $error = $item;
                $item = new Product();
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

        $this->getResponse()->setData(Json::serialize($result, $statusCode < 400 ? Utils::$Json : Utils::$JsonMinimal), $statusCode);
    }

    #endregion

    #region single routes (untested!!!)

    /*
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
    */

    /*
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
    */

    /*
    function GET_byId(string $id) {
        $instance = Ioc::get(ICustomersBulkFetchable::class);
        $customers = $instance->getCustomers([$id]) ?? throw new NotFoundException();
        if (count($customers) < 1 || $customers[0] === null) {
            throw new NotFoundException();
        }
        $data = Json::serialize($customers[0], Utils::$Json);
        $this->getResponse()->setData($data);
    }
    */

    /*
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
    */

    #endregion


    private function resolveProducts(array $products): array {
        try {
            /** @var IProductsBulkResolvable */
            $instance = Ioc::get(IProductsBulkResolvable::class);
            $result = \iterator_to_array($instance->resolveProducts($products, []));
            // if (count($result) === count($products)) {
            $products = $result;
            // }
        } catch (\Exception $ex) {

        }
        return $products;
    }
}