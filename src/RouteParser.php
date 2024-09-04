<?php
namespace Idm\PiperLink;

class RouteParser {

    private array $parameters = [];

    public function Is(string $mask, string $route): bool
    {
        $this->parameters = [];
        $num = 0;
        for ($i = 0; $i < strlen($route); $i++)
        {
            if ($num >= strlen($mask))
            {
                break;
            }

            $c = $mask[$num];
            $c2 = $route[$i];
            if ($c == '{')
            {
                $num2 = strpos($mask, '}', $num);
                if ($num2 === false)
                {
                    break;
                }

                // $key = $mask.Substring(num + 1, num2 - num - 1);
                $key = substr($mask, $num + 1, $num2 - $num - 1);
                $c3 = ($num2 + 1 < strlen($mask)) ? $mask[$num2 + 1] : null;
                $num = $num2 + 1;
                if ($c3 === '{')
                {
                    break;
                }

                $val = $c3 !== null ? strpos($route, $c3, $i) : strlen($route);
                $num3 = strpos($route, '/', $i);
                if ($num3 === false)
                {
                    $num3 = strlen($route);
                }

                $num4 = min($val, $num3);
                $num5 = $i;
                $value = substr($route, $num5, $num4 - $num5);
                $i = $num4;
                $this->parameters[$key] = $value;
                if ($num == strlen($mask) && $i == strlen($route))
                {
                    return true;
                }

                $num++;
            }
            else
            {
                if ($num + 1 == strlen($mask) && $i + 1 == strlen($route))
                {
                    return true;
                }

                if ($c != $c2)
                {
                    break;
                }

                $num++;
            }
        }

        $this->parameters = [];
        return false;
    }

    public function getParameter(string $name): ?string
    {
        return $this->parameters[$name] ?? null;
    }
}