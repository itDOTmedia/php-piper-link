<?php

namespace Idm\PiperLink\Models;

use ArrayAccess;

abstract class BaseModel implements ArrayAccess
{
    #region customs

    /**
     *
     * @var \Idm\PiperLink\Models\CustomData[]
     */
    public array $customs = [];

    public function offsetSet($offset, $value): void
    {
        // BaseModel[] = $value;
        if (is_null($offset)) {
            $this->customs[] = $value;
        }
        // BaseModel[$key] = $value;
        else {
            $this->customs[$offset] = $value;
        }
    }

    public function offsetExists($key): bool
    {
        return isset($this->customs[$key]);
    }

    public function offsetUnset($key): void
    {
        unset($this->customs[$key]);
    }

    public function offsetGet($key): mixed
    {
        return $this->customs[$key] ?? null;
    }

    /**
     * Get custom data object by key and optional language.
     *
     * @param string $key The custom data key.
     * @param string|null $lang The optional custom language selector.
     * @return mixed Returns the custom data object if the key (and language) exists, null otherwise.
     */
    public function getCustom(string $key, ?string $lang = null): ?CustomData
    {
        foreach ($this->customs as $data) {
            if ($data->key === $key && ($lang == null || $data->language == $lang)) {
                return $data;
            }
        }
        
        return null;
    }
    
    /**
     * Get custom value by key and optional language.
     *
     * @param string $key The custom data key.
     * @param string|null $lang The optional custom language selector.
     * @return mixed Returns the custom data value if the key (and language) exists, null otherwise.
     */
    public function getCustomValue(string $key, ?string $lang = null): mixed
    {
        return $this->getCustom($key, $lang)?->value ?? null;
    }

    #endregion
}
