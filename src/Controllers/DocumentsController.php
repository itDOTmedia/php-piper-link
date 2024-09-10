<?php
namespace Idm\PiperLink\Controllers;

use Idm\PiperLink\Contracts\IDocumentsBulkCreateable;
use Idm\PiperLink\Contracts\IDocumentsBulkDeletable;
use Idm\PiperLink\Contracts\IDocumentsBulkFetchable;
use Idm\PiperLink\Contracts\IDocumentsBulkResolvable;
use Idm\PiperLink\Contracts\IDocumentsBulkUpdatable;
use Idm\PiperLink\Exceptions\NotFoundException;
use Idm\PiperLink\Models\Document;
use Idm\PiperLink\PiperLink;
use Idm\PiperLink\StringX;
use Idm\PiperLink\Types\Operation;
use Idm\PiperLink\Utils;
use Pyther\Ioc\Ioc;
use Pyther\Json\Json;

/**
 * Controller for all "documents/..." routes.
 */
class DocumentsController extends BaseController
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
        if ($this->IsRoute("POST", "documents/bulk", $path)) {
            $this->POST_bulk();
        }
        /*else if ($this->IsRoute("POST", "documents", $path)) {
            $this->POST();
        }*/
        // PUT
        /*else if ($this->IsRoute("PUT", "documents/{id}", $path)) {
            $id = $this->getRouteParameter("id") ?? throw new \Exception("Invalid document id given!");
            $this->PUT_byId($id);
        }*/
        else if ($this->IsRoute("PUT", "documents", $path)) {
            $this->PUT_bulk();
        } 
        // GET
        /*else if ($this->IsRoute("GET", "documents/{id}", $path)) {
            $id = $this->getRouteParameter("id") ?? throw new \Exception("Invalid document id given!");
            $this->GET_byId($id);
        }*/
        else if ($this->IsRoute("GET", "documents", $path)) {
            $this->GET_bulk();
        } 
        // DELETE
        /*else if ($this->IsRoute("DELETE", "documents/{id}", $path)) {
            $id = $this->getRouteParameter("id") ?? throw new \Exception("Invalid document id given!");
            $this->DELETE_byId($id);
        }*/
        else if ($this->IsRoute("DELETE", "documents", $path)) {
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
        /** @var IDocumentsBulkCreateable */
        $instance = Ioc::get(IDocumentsBulkCreateable::class);
        $result = [];
        $json = $this->getRequest()->getRawPayload();
        $documents = Json::deserializeArrayOf($json, Document::class, Utils::$Json);
        foreach ($instance->createDocuments($documents, []) as $item) {
            if (is_string($item)) {
                $error = $item;
                $item = new Document();
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
        /** @var IDocumentsBulkUpdatable */
        $instance = Ioc::get(IDocumentsBulkUpdatable::class);
        $result = [];
        $json = $this->getRequest()->getRawPayload();
        $documents = Json::deserializeArrayOf($json, Document::class, Utils::$Json);
        foreach ($instance->updateDocuments($documents, []) as $item) {
            if (is_string($item)) {
                $error = $item;
                $item = new Document();
                $item->id = $documents[count($result)]->id;
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

        /** @var IDocumentsBulkFetchable */
        $instance = Ioc::get(IDocumentsBulkFetchable::class);
        $documents = iterator_to_array($instance->getDocuments($ids, [
            'offset' => $_GET['offset'] ?? null,
            'limit' => $_GET['limit'] ?? null
        ]));

        if (count($documents) === 0) {
            $this->getResponse()->setData(Json::serialize([], Utils::$Json), 404);
        } else if (in_array(false, $documents) && in_array(true, $documents)) {
            $this->getResponse()->setData(Json::serialize($documents, Utils::$Json), 207);
        } else if (in_array(false, $documents)) {
            throw new NotFoundException();
        } else {
            $this->getResponse()->setData(Json::serialize($documents, Utils::$Json), 200);
        }        
    }

    function DELETE_bulk()
    {
        /** @var IDocumentsBulkDeletable */
        $instance = Ioc::get(IDocumentsBulkDeletable::class);
        $result = [];
        $json = $this->getRequest()->getRawPayload();
        $documents = Json::deserializeArrayOf($json, Document::class, Utils::$Json);
        $documents = $this->resolveDocuments($documents);
        $ids = array_map(function($document) { return $document->id;}, $documents);

        foreach ($instance->deleteDocuments($ids, []) as $item) {
            if (is_string($item)) {
                $error = $item;
                $item = new Document();
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

    private function resolveDocuments(array $documents): array {
        try {
            /** @var IDocumentsBulkResolvable */
            $instance = Ioc::get(IDocumentsBulkResolvable::class);
            $result = \iterator_to_array($instance->resolveDocuments($documents, []));
            // if (count($result) === count($documents)) {
            $documents = $result;
            // }
        } catch (\Exception $ex) {

        }
        return $documents;
    }
}