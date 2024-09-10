<?php
namespace Idm\PiperLink;

use Pyther\Json\JsonSettings;
use Pyther\Json\NamingPolicies\CamelToPascalNamingPolicy;
use Pyther\Json\Types\EnumFormat;

abstract class Utils {

    public static $Json;
    public static $JsonMinimal;

    public static function count(array $array, callable $callback): int {
        $result = 0;
        foreach ($array as $item) {
            if ($callback($item) === true) {
                $result++;
            }
        }
        return $result;
    }

    public static function all(array $array, callable $callback): bool {
        foreach ($array as $item) {
            if ($callback($item) !== true) {
                return false;
            }
        }
        return true;
    }
    
    public static function any(array $array, callable $callback): int {
        foreach ($array as $item) {
            if ($callback($item) === true) {
                return true;
            }
        }
        return false;
    }    
}

Utils::$Json = new JsonSettings();
Utils::$Json->setNamingPolicy(new CamelToPascalNamingPolicy());
Utils::$Json->setEnumFormat(EnumFormat::Name);

Utils::$JsonMinimal = new JsonSettings();
Utils::$JsonMinimal->setNamingPolicy(new CamelToPascalNamingPolicy());
Utils::$JsonMinimal->setEnumFormat(EnumFormat::Name);
Utils::$JsonMinimal->setSkipNull();
Utils::$JsonMinimal->setSkipEmptyArray();
