<?php
/**
 *  Autoloader function generated by crodas/Autoloader
 *
 *  https://github.com/crodas/Autoloader
 *
 *  This is a generated file, do not modify it.
 */
#* include("common.tpl.php")

return function($class) {
    // classes {{{
    static $classes = __@classes__;
    // }}}

    #* if (count($deps) > 0)
    // deps {{{
    static $deps    = __@deps__;
    // }}}
    #* end

    if (isset($classes[$class])) {
        #* if (count($deps) > 0)
        if (!empty($deps[$class])) {
            foreach ($deps[$class] as $zclass) {
                #* validate('zclass')
            }
        }
        #* end
        #* validate('class')
        return true;
    }
};
