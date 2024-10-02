<?php
namespace Idm\PiperLink\Models;

use ArrayAccess;
use Idm\PiperLink\Types\Operation;

abstract class BaseModel implements ArrayAccess
{
    #region customs

    public $customs = [];

    public function offsetSet($offset, $value): void {
        // BaseModel[] = $value;
        if (is_null($offset)) {
            $this->customs[] = $value;
        } 
        // BaseModel[$key] = $value;
        else {
            $this->customs[$offset] = $value;
        }
    }

    public function offsetExists($key): bool {
        return isset($this->customs[$key]);
    }

    public function offsetUnset($key): void {
        unset($this->customs[$key]);
    }

    public function offsetGet($key): mixed {
        return $this->customs[$key] ?? null;
    }

    #endregion
}