<?php
namespace Idm\PiperLink\Credentials;

use Idm\PiperLink\Types\GrantType;

/**
 * Authentication credentials using an api key.
 */
class ApiKeyCredentials extends BaseCredentials
{
    public string $key;

    public function getGrantType():GrantType {
        return GrantType::ApiKey;
    }

    function __construct(string $key)
    {
        $this->key = $key;
    }
}