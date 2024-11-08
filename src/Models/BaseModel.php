<?php

namespace Idm\PiperLink\Models;

use ArrayAccess;
use Idm\PiperLink\Traits\Customs;
use Pyther\Json\Attributes\JsonComplete;

abstract class BaseModel implements ArrayAccess
{
    use Customs;

    #region ArrayAccess

    public function offsetSet($offset, $value): void
    {
        // BaseModel[] = $value;
        if (is_null($offset)) {
            $this->customs[] = $value;
        }
        // BaseModel[$key] = $value;
        else {
            $this->setCustomValue($offset, $value);
        }
    }

    public function offsetExists($key): bool
    {
        return isset($this->parsedCustoms[$key]);
    }

    public function offsetUnset($key): void
    {
        $this->deleteCustom($key);
    }

    public function offsetGet($key): mixed
    {
        return $this->getCustomValue($key);        
    }

    #endregion

    #[JsonComplete()]
    public function OnDeserializeComplete()
    {
        $this->parseCustoms();
    }
}
