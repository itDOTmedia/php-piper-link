<?php
namespace Idm\PiperLink\Exceptions;

use Idm\PiperLink\StringX;

class AuthenticationException extends \Exception {
    const INVALID_TOKEN = 1;

    public function buildAuthenticateHeader() {
        $parts = [];
        $parts[] = "Bearer";
        $parts[] = "realm=\"piperlink\"";
        if ($this->getCode() == static::INVALID_TOKEN) {
            $parts[] = "error=\"invalid_token\"";
        }
        if (!StringX::isNullOrWhiteSpace($this->getMessage())) {
            $parts[] = "error_description=\"{$this->getMessage()}\"";
        }
        // error_description
        return implode(", ",$parts);
    }
}