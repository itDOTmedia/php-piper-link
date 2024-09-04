<?php
namespace Idm\PiperLink\Credentials;

use Idm\PiperLink\Types\GrantType;

/**
 * Authentication credentials using username and password.
 */
class BasicCredentials extends BaseCredentials
{
    public string $basic;
    public ?string $username;
    public ?string $password;

    public function getGrantType():GrantType {
        return GrantType::Basic;
    }

    function __construct(string $basic)
    {
        $this->basic = $basic;
        if ($basic !== null && $basic !== "") {
            $plain = explode(":", base64_decode($this->basic), 2);
            $this->username = $plain[0];
            $this->password = count($plain) === 2 ? $plain[1] : null;
        }
    }
}