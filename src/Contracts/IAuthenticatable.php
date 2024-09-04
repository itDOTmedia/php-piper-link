<?php
namespace Idm\PiperLink\Contracts;

interface IAuthenticatable
{
    /**
     * Returns an array of supported authentication methods.
     *
     * @return array
     */
    public function authenticationMethods() : array;

    /**
     * Validates the given credentials. This method must return any non null string on success.
     * This value will be used as the user id on all following requests.
     * If the validation fails, this method have to return null.
     *
     * @param \Idm\PiperLink\Credentials\Credentials $credentials The credentials.
     * @return boolean Returns any user id if the credentials are valid, null otherwise.
     */
    public function verifyCredentials(\Idm\PiperLink\Credentials\BaseCredentials $credentials) : ?string;
}