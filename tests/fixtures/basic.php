<?php
/**
 *  Autoloader function generated by crodas/Autoloader
 *
 *  https://github.com/crodas/Autoloader
 *
 *  This is a generated file, do not modify it.
 */

$GLOBALS['call_basicstat'] = 0;
$GLOBALS['load_basicstat'] = 0;



spl_autoload_register(function ($class) {
    /*
        This array has a map of (class => file)
    */

    // classes {{{
    static $classes = array (
  'autoloader\\test\\simple\\bar' => '/home/crodas/projects/mongolico/Autoloader/tests/fixtures/basic/Bar.php',
  'autoloader\\test\\simple\\foo' => '/home/crodas/projects/mongolico/Autoloader/tests/fixtures/basic/Foo.php',
);
    // }}}

    // deps {{{
    static $deps    = array (
);
    // }}}

    $class = strtolower($class);
    if (isset($classes[$class])) {
        $GLOBALS['call_basicstat']++;
        $GLOBALS['load_basicstat']++;
        if (!empty($deps[$class])) {
            foreach ($deps[$class] as $zclass) {
                if (!class_exists($zclass, false)) {
                    $GLOBALS['load_basicstat']++;
                    require $classes[$zclass];
                }
            }
        }

        if (!class_exists($class, false)) {
            require $classes[$class];
        }
        return true;
    }

    /**
     * Autoloader that implements the PSR-0 spec for interoperability between
     * PHP software.
     *
     * kudos to @alganet for this autoloader script.
     * borrowed from https://github.com/Respect/Validation/blob/develop/tests/bootstrap.php
     */
    $fileParts = explode('\\', ltrim($class, '\\'));
    if (false !== strpos(end($fileParts), '_')) {
        array_splice($fileParts, -1, 1, explode('_', current($fileParts)));
    }
    $file = stream_resolve_include_path(implode(DIRECTORY_SEPARATOR, $fileParts) . '.php');
    if ($file) {
        return require $file;
    }
    return false;
}, true, true);


function getbasicstat() {
    global $load_basicstat, $call_basicstat;
    return array('loaded' => $load_basicstat, 'calls' => $call_basicstat);
}
