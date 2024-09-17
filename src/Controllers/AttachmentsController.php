<?php
namespace Idm\PiperLink\Controllers;

use Idm\PiperLink\Contracts\IAttachmentsBulkCreatable;
use Idm\PiperLink\Contracts\IAttachmentsBulkDeletable;
use Idm\PiperLink\Contracts\IAttachmentsBulkFetchable;
use Idm\PiperLink\Contracts\IAttachmentsBulkResolvable;
use Idm\PiperLink\Contracts\IAttachmentsBulkUpdatable;
use Idm\PiperLink\Exceptions\NotFoundException;
use Idm\PiperLink\Models\Attachment;
use Idm\PiperLink\PiperLink;
use Idm\PiperLink\StringX;
use Idm\PiperLink\Types\Operation;
use Idm\PiperLink\Utils;
use Pyther\Ioc\Ioc;
use Pyther\Json\Json;

/**
 * controller for all "/attachments/..." routes 
 */
class AttachmentsController extends BaseController
{
    function __construct(PiperLink $piperLink)
    {
        parent::__construct($piperLink);
    }

    public function execute(string $path)
    {
        // no cache
        $this->getResponse()->addHeader("Cache-Control", "no-store");
        $this->getResponse()->addHeader("Pragma", "no-cache");

        // POST
        if ($this->IsRoute("POST", "attachments/bulk", $path)) {
            $this->POST_bulk();
        }
        /*
        else if ($this->IsRoute("POST", "attachments", $path)) {
            $this->POST();
        }
        // PUT
        else if ($this->IsRoute("PUT", "attachments/{id}", $path)) {
            $id = $this->getRouteParameter("id") ?? throw new \Exception("Invalid customer id given!");
            $this->PUT_byId($id);
        }
        */
        else if ($this->IsRoute("PUT", "attachments", $path)) {
            $this->PUT_bulk();
        } 
        // GET
        /*else if ($this->IsRoute("GET", "attachments/{id}", $path)) {
            $id = $this->getRouteParameter("id") ?? throw new \Exception("Invalid customer id given!");
            $this->GET_byId($id);
        }
        */
        else if ($this->IsRoute("GET", "attachments", $path)) {
            $this->GET_bulk();
        } 
        // DELETE
        /*else if ($this->IsRoute("DELETE", "attachments/{id}", $path)) {
            $id = $this->getRouteParameter("id") ?? throw new \Exception("Invalid customer id given!");
            $this->DELETE_byId($id);
        }*/
        else if ($this->IsRoute("DELETE", "attachments", $path)) {
            $this->DELETE_bulk();
        }
        // other
        else
            throw new NotFoundException();
    }

    #region POST

    function POST_bulk()
    {
        /** @var IAttachmentsBulkCreatable */
        $instance = Ioc::get(IAttachmentsBulkCreatable::class);
        
        $result = [];
        $json = $this->getRequest()->getRawPayload();
        $attachments = Json::deserializeArrayOf($json, Attachment::class, Utils::$Json);
        foreach ($instance->createAttachments($attachments, []) as $item) {
            if (is_string($item)) {
                $error = $item;
                $item = $attachments[count($result)];
                $item->operation = Operation::Error;
                $item->error = $error;
            } else {
                $item->operation = Operation::Insert;
            }
            if (!StringX::isNullOrWhiteSpace($item->base64) && strlen($item->base64) > 20) {
                $item->base64 = substr($item->base64, 0, 10)."......".substr($item->base64, -10);
            }
            $result[] = $item;
        }

        $statusCode = 207;
        if (Utils::all($result, function($e) { return $e->operation === Operation::Insert; })) $statusCode = 200;
        else if (Utils::all($result, function($e) { return $e->error === "Resource not found!"; })) $statusCode = 404;
        else if (Utils::all($result, function($e) { return $e->operation !== Operation::Insert; })) $statusCode = 400;

        $this->getResponse()->setData(Json::serialize($result, $statusCode < 400 ? Utils::$Json : Utils::$JsonMinimal), $statusCode);
    }

    #endregion

    #region PUT

    function PUT_bulk()
    {
        /** @var IAttachmentsBulkUpdatable */
        $instance = Ioc::get(IAttachmentsBulkUpdatable::class);
        $result = [];
        $json = $this->getRequest()->getRawPayload();
        $attachments = Json::deserializeArrayOf($json, Attachment::class, Utils::$Json);
        foreach ($instance->updateAttachments($attachments, []) as $item) {
            if (is_string($item)) {
                $error = $item;
                $item = new Attachment();
                $item->id = $attachments[count($result)]->id;
                $item->operation = Operation::Error;
                $item->error = $error == "404" ? "Resource not found!" : $error;
            } else {
                $item->operation = Operation::Update;
            }
            if (!StringX::isNullOrWhiteSpace($item->base64) && strlen($item->base64) > 20) {
                $item->base64 = substr($item->base64, 0, 10)."......".substr($item->base64, -10);
            }
            $result[] = $item;
        }

        $statusCode = 207;
        if (Utils::all($result, function($e) { return $e->operation === Operation::Update; })) $statusCode = 200;
        else if (Utils::all($result, function($e) { return $e->error === "Resource not found!"; })) $statusCode = 404;
        else if (Utils::all($result, function($e) { return $e->operation !== Operation::Update; })) $statusCode = 400;

        $this->getResponse()->setData(Json::serialize($result, $statusCode < 400 ? Utils::$Json : Utils::$JsonMinimal), $statusCode);
    }    

    #endregion

    #region GET

    function GET_bulk() {        
        $ids = $this->getRequest()->GET("ids");
        if (!StringX::isNullOrWhiteSpace($ids)) {
            $ids = explode(',', $ids);
        } else {
            $ids = null;
        }

        /** @var IAttachmentsBulkFetchable */
        $instance = Ioc::get(IAttachmentsBulkFetchable::class);
        $attachments = iterator_to_array($instance->getAttachments($ids, [
            'offset' => $_GET['offset'] ?? null,
            'limit' => $_GET['limit'] ?? null
        ]));

        if (count($attachments) === 0) {
            $this->getResponse()->setData(Json::serialize([], Utils::$Json), 404);
        } else if (in_array(false, $attachments) && in_array(true, $attachments)) {
            $this->getResponse()->setData(Json::serialize($attachments, Utils::$Json), 207);
        } else if (in_array(false, $attachments)) {
            throw new NotFoundException();
        } else {
            $this->getResponse()->setData(Json::serialize($attachments, Utils::$Json), 200);
        }        
    }

    #endregion

    #region DELETE

    function DELETE_bulk()
    {
        /** @var IAttachmentsBulkDeletable */
        $instance = Ioc::get(IAttachmentsBulkDeletable::class);
        $result = [];
        $json = $this->getRequest()->getRawPayload();
        $attachments = Json::deserializeArrayOf($json, Attachment::class, Utils::$Json);
        $attachments = $this->resolveAttachments($attachments);
        $ids = array_map(function($attachment) { return $attachment->id;}, $attachments);

        foreach ($instance->deleteAttachments($ids, []) as $item) {
            if (is_string($item)) {
                $error = $item;
                $item = new Attachment();
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

    private function resolveAttachments(array $attachments): array {
        try {
            /** @var IAttachmentsBulkResolvable */
            $instance = Ioc::get(IAttachmentsBulkResolvable::class);
            $result = \iterator_to_array($instance->resolveAttachments($attachments, []));
            // if (count($result) === count($attachments)) {
            $attachments = $result;
            // }
        } catch (\Exception $ex) {

        }
        return $attachments;
    }    

}
