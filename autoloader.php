<?php

/**
 * Register a custom auto loader for non composer enviroments.
 */
spl_autoload_register(function($fqn) {
    $root =  __DIR__.DIRECTORY_SEPARATOR."src".DIRECTORY_SEPARATOR;
    $namespace = "Idm\\PiperLink\\";

    // echo "fqn = " . $fqn. ", \r\n";
    if (preg_match("#^".preg_quote($namespace)."#", $fqn)) {
        $classname = str_replace($namespace, "", $fqn);
        $filename = preg_replace("#\\\\#", DIRECTORY_SEPARATOR, $classname).".php";
        // echo "className = " . $classname. ", \r\n";
        // echo "filename = " . $filename. ", \r\n";
        $fullpath = $root.$filename;
        // echo "fullpath = " . $fullpath. ", \r\n";
        if (file_exists($fullpath)) {
            include_once $fullpath;
        }
    }
}, true, false);