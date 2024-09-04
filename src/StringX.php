<?php
namespace Idm\PiperLink;

/**
 * String utility methods.
 */
abstract class StringX {

    /**
     * Convert a given String to "PascalCase".
     * @param string $str The string to convert.
     * @param string $separator (optional) Optional word separator characters.
     * @return string Returns the converted string.
     */
    public static function toPascalCase(string $str, string $separator = " -_\t"): string {
        $str = strtolower($str);
        $str = ucwords($str, $separator);
        $str = str_replace(str_split($separator), '', $str);
        return $str;
    }

    /**
     * Convert a given String to "camelCase".
     * @param string $str The string to convert.
     * @param string $separator (optional) Optional word separator characters.
     * @return string Returns the converted string.
     */
    public static function toCamelCase(string $str, string $separator = " -_\t"): string {
        $str = strtolower($str);
        $str = ucwords($str, $separator);
        $str = str_replace(str_split($separator), '', $str);
        if (strlen($str > 0)) {
            $str[0] = strtolower($str[0]);
        }
        return $str;
    }

    /**
     * Convert a given String to "snake_case".
     * @param string $str The string to convert.
     * @param string $separator (optional) Optional word separator characters.
     * @return string Returns the converted string.
     */
    public static function toSnakeCase(string $str, string $separator = " -\t"): string {
        $str = strtolower($str);
        $str = str_replace(str_split($separator), '_', $str);
        return $str;
    }

    /**
     * Convert a given String to "kebab-case".
     * @param string $str The string to convert.
     * @param string $separator (optional) Optional word separator characters.
     * @return string Returns the converted string.
     */
    public static function toKebabCase(string $str, string $separator = " _\t"): string {
        $str = strtolower($str);
        $str = str_replace(str_split($separator), '-', $str);
        return $str;
    }

    /**
     * Convert a given String to a "PascalCase" class name by removing unallowed characters. 
     * @param string $str The string to convert.
     * @param string $separator (optional) Optional word separator characters.
     * @return string Returns the converted string.
     */
    public static function toClassName(string $str, string $separator = " -_\t"): string {
        $str = self::toPascalCase($str, $separator);
        $str = preg_replace("/[^a-zA-Z_0-9\x80-\xff]*/", "", $str);
        return $str;
    }

    /**
     * Convert a given String to a "camelCase" method name by removing unallowed characters. 
     * @param string $str The string to convert.
     * @param string $separator (optional) Optional word separator characters.
     * @return string Returns the converted string.
     */
    public static function toMethodName(string $str, string $separator = " -_\t"): string {
        $str = self::toCamelCase($str, $separator);
        $str = preg_replace("/[^a-zA-Z0-9_\x80-\xff]*/", "", $str);
        return $str;
    }

    /**
     * Return each element of a string separated by a given character.
     *
     * @param string $str
     * @param string $sep
     * @return \Generator
     */
    public static function each(string $str, string $sep): \Generator {
        $s = 0;
        while (($e = strpos($str, $sep, $s)) !== false) {
            yield substr($str, $s, $e - $s); 
            $s = $e + 1;
        }
        yield substr($str, $s);
    }

    public static function getPart(string $str, int $index, string $sep): ?string {
        $idx = 0;
        foreach (StringX::each($str, $sep) as $part) {
            if ($idx == $index) {
                return $part;
            }
            $idx++;
        }
        return null;
    }

    public static function isNullOrEmpty(?string $str): bool
    {
        return $str === null || $str === "";
    }

    public static function isNullOrWhiteSpace(?string $str): bool
    {
        return $str === null || $str === "" || ctype_space($str);
    }
}