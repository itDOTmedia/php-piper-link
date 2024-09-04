<?php
namespace Idm\PiperLink\Credentials;

use Idm\PiperLink\Types\GrantType;

/**
 * Base class for all credentials.
 */
abstract class BaseCredentials
{
    public abstract function getGrantType(): GrantType;
}