<?php
namespace Idm\PiperLink\Credentials;

use Idm\PiperLink\Types\GrantType;

/**
 * Authentication credentials using a bearer token.
 */
class BearerCredentials extends BaseCredentials
{    
    public string $bearer;
    
    public function getGrantType():GrantType {
        return GrantType::Bearer;
    }

    function __construct($bearer) {
        $this->bearer = $bearer;
    }
}