<?php
namespace Idm\PiperLink\Traits;

use Idm\PiperLink\Models\CustomData;
use Idm\PiperLink\StringX;

trait Customs
{
    /**
     * The models customs array.
     * @var \Idm\PiperLink\Models\CustomData[]
     */
    public array $customs = [];

    /**
     * prepared custom data array.
     * $parsedCustoms[$key] = value
     * $parsedCustoms[$key][$lng] = value
     * @var array
     */
    private array $parsedCustoms = [];

    private function parseCustoms()
    {
        $this->parsedCustoms = [];
        if ($this->customs !== null && count($this->customs) > 0) {
            foreach ($this->customs as $item) {
                if (StringX::isNullOrWhiteSpace($item->language)) {
                    $this->parsedCustoms[$item->key] = $item->value;                    
                } else {
                    $this->parsedCustoms[$item->key] ??= [];
                    $this->parsedCustoms[$item->key][strtolower($item->language)] = $item->value;
                }
            }
        }
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
     * @param string|null $language The optional custom language selector.
     * @return mixed Returns the custom data value if the key (and language) exists, null otherwise.
     */    
    public function getCustomValue(string $key, ?string $language = null): mixed
    {
        return $language == null ? $this->parsedCustoms[$key] ?? null : $this->parsedCustoms[$key][strtolower($language)] ?? null; 
    }

    /**
     * Delete a custom data entry.
     *
     * @param string $key The custom data key.
     * @param string|null $language The optional custom language selector.
     * @return void
     */
    public function deleteCustom(string $key, ?string $language = null)
    {
        $this->customs = array_filter($this->customs, function($e) use($key, $language) { 
            return $e->key !== $key || ($e->language !== $language);
        });
        if (isset($this->parsedCustoms[$key])) {
            if (StringX::isNullOrWhiteSpace($language)) {
                unset($this->parsedCustoms[$key]);
            } else if (is_array($this->parsedCustoms[$key])) {
                unset($this->parsedCustoms[$key][strtolower($language)]);
            }
        }
    }

    /**
     * Set a custom value.
     *
     * @param string $key The custom data key.
     * @param mixed $value The custom data value.
     * @param string|null $language The optional custom language selector.
     * @return void
     */
    public function setCustomValue(string $key, mixed $value, ?string $language = null)
    {
        // delete from "customs" & "parsedCustoms"
        $this->deleteCustom($key, $language);

        // insert (if not null)
        if ($value !== null) {
            // add to "customs"
            $data = new CustomData();
            $data->key = $key;
            $data->language = $language;
            $data->value = $value;
            $this->customs[] = $data;
            // add to "parsedCustoms"
            if (StringX::isNullOrWhiteSpace($language)) {
                $this->parsedCustoms[$key] = $value;
            } else {
                $this->parsedCustoms[$key][strtolower($language)] = $value;
            }
        }
    }
}