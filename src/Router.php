<?php
namespace Idm\PiperLink;

use Idm\PiperLink\Exceptions\NotFoundException;

class Router {

    public string $root;

    function __construct(?string $root) {
        $this->root = $root ?? "";
    }

    function getRoot() :string {
        return $this->root;
    }

    function route($path, PiperLink $piperLink) {
        $path = str_replace("\\", "/", $path);
        if (!str_starts_with($path, $this->root)) {
            return false;
        }
        $path = substr($path, strlen($this->root));
        $controllerClassName = (StringX::toClassName(StringX::getPart($path, 0, "/")) ?? "Index") ."Controller";
        $fqn = "\\Idm\\PiperLink\\Controllers\\".$controllerClassName;
        if (!class_exists($fqn)) {
            throw new NotFoundException();
        }
        $controller = new $fqn($piperLink);
        $controller->execute($path);
        return true;
    }
}