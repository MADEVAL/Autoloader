<?php
/**
 *  Autoloader function generated by crodas/Autoloader
 *
 *  https://github.com/crodas/Autoloader
 *
 *  This is a generated file, do not modify it.
 */

spl_autoload_register(function($class) {
    static $loaded     = array();
    static $namespaces = __@filemap__;

    $class = strtolower($class);

    foreach ($namespaces as $namespace => $file) {
        if ($namespace !== '*' && strpos($class, $namespace) !== 0) {
            // wrong namespace move forward!
            continue;
        }

        if (empty($loaded[$namespace])) {
            #* if ($relative)
            $loaded[$namespace] = require __DIR__ . $file;
            #* else
            $loaded[$namespace] = require $file;
            #* end
        }
        return $loaded[$namespace]( $class );
    }
});
