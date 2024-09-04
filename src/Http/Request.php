<?php
namespace Idm\PiperLink\Http;

/**
 * Holds information about the current request
 */
class Request {
    
    /**
     * The Http Method in UPPER_CASE
     * @var string
     */
    public string $method = "GET";
    private array $GET = [];
    private array $POST = [];

    private Headers $headers;
    private ?string $authorizationHeader = null;

    function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? "GET";
        $this->headers = new Headers();
        $this->headers->parse();
        $this->GET = $_GET;
        $this->POST = $_POST;
    }

    public function getHeaders(): Headers
    {
        return $this->headers;
    }

    public function getAuthorizationHeader()
    {
        if ($this->authorizationHeader === null)
        {
            $this->authorizationHeader = $_SERVER['Authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? null;
            if (!$this->authorizationHeader && function_exists('apache_request_headers'))
            {
                $arh = apache_request_headers();
                $arh = array_combine(array_map('ucwords', array_keys($arh)), array_values($arh));
                if (isset($arh['Authorization'])) {
                    $this->authorizationHeader = trim($arh['Authorization']);
                }
            }
            if (!$this->authorizationHeader)
            {
                $this->authorizationHeader = $this->headers->get("Authorization") ?? "";
            }            
        }
        return $this->authorizationHeader;
    }

    public function getRawPayload():?string
    {
        $result = file_get_contents('php://input');
        return $result !== false ? $result : null;
    }

    public function getJsonPayload(bool $associtive = true, int $flags = 0)
    {
        $raw = $this->getRawPayload();
        return $raw !== null ? json_decode($raw, $associtive, 512, $flags) : null;
    }

    public function GET(?string $key = null, $value = null) {
        // syntax: GET() => return array
        if ($key === null) {
            return $this->GET;
        }
        // syntax: GET($key) => get value by key
        if ($value === null) {
            return $this->GET[$key] ?? null;
        }
        // syntax: GET($key, $value) => set value by key
        else {
            $this->GET[$value] = $value;
        }
    }
    
    public function POST($key = null, $value = null) {
        // syntax: POST() => return array
        if ($key === null) {
            return $this->POST;
        }
        // syntax: POST($key) => get value by key
        if ($value === null) {
            return $this->POST[$key] ?? null;
        }
        // syntax: POST($key, $value) => set value by key
        else {
            $this->POST[$value] = $value;
        }
    }
}