<?php
namespace Idm\PiperLink;

use Pyther\Json\JsonSettings;
use Pyther\Json\NamingPolicies\CamelToPascalNamingPolicy;
use Pyther\Json\Types\EnumFormat;

abstract class Utils {

    public static $Json;
    public static $JsonMinimal;
}

Utils::$Json = new JsonSettings();
Utils::$Json->setNamingPolicy(new CamelToPascalNamingPolicy());
Utils::$Json->setEnumFormat(EnumFormat::Name);

Utils::$JsonMinimal = new JsonSettings();
Utils::$JsonMinimal->setNamingPolicy(new CamelToPascalNamingPolicy());
Utils::$JsonMinimal->setEnumFormat(EnumFormat::Name);
Utils::$JsonMinimal->setSkipNull();
Utils::$JsonMinimal->setSkipEmptyArray();
