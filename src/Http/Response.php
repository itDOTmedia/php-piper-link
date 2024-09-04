<?php
namespace Idm\PiperLink\Http;

class Response {
    public int $statusCode = 200;
    public string $contentType = "JSON";
    public $data = null;

    public function setData($data, $statusCode = 200, $contentType = "JSON") {
        $this->data = $data;
        $this->statusCode = $statusCode;
        $this->contentType = $contentType;
    }

    public function deliver() {
        http_response_code($this->statusCode);
        if ($this->contentType === "JSON") {
            header('Content-Type: application/json; charset=utf-8');
            if ($this->data !== null) {
                echo is_string($this->data) ? $this->data : json_encode($this->data, \JSON_PRETTY_PRINT);
                return;
            }
        } else if ($this->contentType === "TEXT") {
            header('Content-Type: text/plain; charset=utf-8');
            echo  $this->data; 
        } else if ($this->contentType === "HTML") {
            header('Content-Type: text/html; charset=utf-8');
            echo  $this->data; 
        } else {
            header('Content-Type: '.$this->contentType.'; charset=utf-8');
            echo  $this->data; 
        }
    }

    public function addHeader($key, $value) {
        header("{$key}: {$value}");
    }
}