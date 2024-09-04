<?php
namespace Idm\PiperLink\Credentials;

use Idm\PiperLink\Types\GrantType;

/**
 * Authentication credentials using client id and client secret.
 */
class ClientCredentials extends BaseCredentials
{
    public string $clientId;
    public string $clientSecret;

    public function getGrantType():GrantType {
        return GrantType::ClientCredentials;
    }

    function __construct(string $clientId, string $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }
}