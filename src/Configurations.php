<?php
namespace Idm\PiperLink;

class Configurations
{
    private $data = [];

    function __construct(?string $path)
    {
        if ($path !== null) {
            $this->load($path);
        }
        $root = (string)$this->get("root");
        if ($root) {
            if (str_starts_with($root, "./")) $root = realpath(dirname($path).DIRECTORY_SEPARATOR.$root).DIRECTORY_SEPARATOR;
            if (!str_ends_with($root, DIRECTORY_SEPARATOR)) $root = DIRECTORY_SEPARATOR;
            $this->set("root", $root);        
        }
        // $this->set("tokens.data-source.root", $this->get("root"));  
    }
    
    public function load($path) {
        // load json
        $json = @file_get_contents($path);
        if ($json === false) {
            throw new \Exception("configuration file not found!");
        }
        // parse json
        try {
            $data = json_decode($json, true, 512, \JSON_THROW_ON_ERROR);
        } catch(\Exception $ex) {
            throw new \Exception("Configuration file decoder says \"{$ex->getMessage()}\"!");
        }
        $this->data = $data;
        $this->data = $this->parse($data);
    }


    public function get(string $path) {
        $ptr = &$this->data;
        foreach (StringX::each($path, '.') as $p) {
            if (isset($ptr[$p])) {
                $ptr = &$ptr[$p];
            } else {
                return NULL;
            }
        }
        return $ptr;
    }

    public function set(string $path, mixed $value) {
        $ptr = &$this->data;
        foreach (StringX::each($path, '.') as $p) {
            if (!isset($ptr[$p])) {
                $ptr[$p] = [];
            } 
            $ptr = &$ptr[$p];
        }
        $ptr = $value;
        return $this;
    }

    private function parse($src) {
        if (is_array($src)) {
            $out = [];
            foreach ($src as $key => $value) {
                $idxEqual = strpos($key, "=");
                if ($idxEqual === false) {
                    $out[$key] = $this->parse($value);            
                    continue;
                }
                $l = substr($key, 0, $idxEqual);
                $r = substr($key, $idxEqual + 1);
                if (call_user_func_array([$this, 'get'], explode('.', $l)) == $r) {
                    if (is_array($value)) {
                        $out = array_merge($out, $this->parse($value)); 
                    } else {
                        $out[$key] = $value;
                    } 
                    continue;
                }
            }
        } else {
            $out = $src;
        }
        return $out;
    }
}