<?php
/**
 *  This file was generated with crodas/SimpleView (https://github.com/crodas/SimpleView)
 *  Do not edit this file.
 *
 */

namespace {


    class base_template_aab8d954813d6a190dfedd5e726e6f8ed2ef081e
    {
        protected $parent;
        protected $child;
        protected $context;

        public function yield_parent($name, $args)
        {
            $method = "section_" . sha1($name);

            if (is_callable(array($this->parent, $method))) {
                $this->parent->$method(array_merge($this->context, $args));
                return true;
            }

            if ($this->parent) {
                return $this->parent->yield_parent($name, $args);
            }

            return false;
        }

        public function do_yield($name, Array $args = array())
        {
            if ($this->child) {
                // We have a children template, we are their base
                // so let's see if they have implemented by any change
                // this section
                if ($this->child->do_yield($name, $args)) {
                    // yes!
                    return true;
                }
            }

            // Do I have this section defined?
            $method = "section_" . sha1($name);
            if (is_callable(array($this, $method))) {
                // Yes!
                $this->$method(array_merge($this->context, $args));
                return true;
            }

            // No :-(
            return false;
        }

    }

    /** 
     *  Template class generated from autoloader.tpl.php
     */
    class class_0e0b04df3e4434b3998ba2ae1d44780d04317374 extends base_template_aab8d954813d6a190dfedd5e726e6f8ed2ef081e
    {

        public function hasSection($name)
        {

            return false;
        }


        public function renderSection($name, Array $args = array(), $fail_on_missing = true)
        {
            if (!$this->hasSection($name)) {
                if ($fail_on_missing) {
                    throw new \RuntimeException("Cannot find section {$name}");
                }
                return "";
            }

        }

        public function enhanceException(Exception $e, $section = NULL)
        {
            if (!empty($e->enhanced)) {
                return;
            }

            $message = $e->getMessage() . "( IN " . 'autoloader.tpl.php';
            if ($section) {
                $message .= " | section: {$section}";
            }
            $message .= ")";

            $object   = new ReflectionObject($e);
            $property = $object->getProperty('message');
            $property->setAccessible(true);
            $property->setValue($e, $message);

            $e->enhanced = true;
        }

        public function render(Array $vars = array(), $return = false)
        {
            try {
                return $this->_render($vars, $return);
            } catch (Exception $e) {
                if ($return) ob_get_clean();
                $this->enhanceException($e);
                throw $e;
            }
        }

        public function _render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }

            echo "<?php\n/**\n *  Autoloader function generated by crodas/Autoloader\n *\n *  https://github.com/crodas/Autoloader\n *\n *  This is a generated file, do not modify it.\n */\n";
            if ($stats) {
                echo "\$GLOBALS['call_" . ($stats) . "'] = 0;\n\$GLOBALS['load_";
                echo $stats . "'] = 0;\n";
            }
            echo "\n";
            $function = 'autoloader_' . uniqid(true);
            $this->context['function'] = $function;
            if ($relative) {
                $dir = '__DIR__' . uniqid(true);
                $this->context['dir'] = $dir;
                echo "define(";
                var_export($dir);
                echo ", defined('__DIR__') ? __DIR__ : dirname(__FILE__));\n";
            }
            echo "\n\n\nfunction ";
            echo $function . "(\$class) {\n    /*\n        This array has a map of (class => file)\n    */\n    static \$classes = ";
            var_export($classes);
            echo ";\n\n";
            if (count($deps) > 0) {
                echo "    static \$deps    = ";
                var_export($deps);
                echo ";\n";
            }
            echo "\n";
            Autoloader\Templates::exec("load", $this->context);
            echo "\n";
            if ($include_psr0) {
                echo "    /**\n     * Autoloader that implements the PSR-0 spec for interoperability between\n     * PHP software.\n     *\n     * kudos to";
                echo "@" . "alganet for this autoloader script.\n     * borrowed from https://github.com/Respect/Validation/blob/develop/tests/bootstrap.php\n     */\n    \$fileParts = explode('\\\\', ltrim(\$class, '\\\\'));\n    if (false !== strpos(end(\$fileParts), '_')) {\n        array_splice(\$fileParts, -1, 1, explode('_', current(\$fileParts)));\n    }\n    \$file = stream_resolve_include_path(implode(DIRECTORY_SEPARATOR, \$fileParts) . '.php');\n    if (\$file) {\n        return require \$file;\n    }\n";
            }
            echo "\n    return false;\n} \n\nspl_autoload_register(";
            var_export($function);
            echo "\n";
            if (!$relative) {
                echo ", true, true\n";
            }
            echo ");\n\n";
            foreach($includes as $include) {

                $this->context['include'] = $include;
                if ($relative) {
                    echo "        require_once " . ($dir) . " . ";
                    var_export($include);
                    echo ";\n";
                }
                else {
                    echo "        require_once ";
                    var_export($include);
                    echo ";\n";
                }
            }
            echo "\n";
            if ($stats) {
                echo "function get" . ($stats) . "() {\n    global \$load_";
                echo $stats . ", \$call_" . ($stats) . ";\n    return array('loaded' => \$load_";
                echo $stats . ", 'calls' => \$call_" . ($stats) . ");\n}\n";
            }

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from index.tpl.php
     */
    class class_a3ffbb3ad03f81c3fd1d93e6556beae4811dc590 extends base_template_aab8d954813d6a190dfedd5e726e6f8ed2ef081e
    {

        public function hasSection($name)
        {

            return false;
        }


        public function renderSection($name, Array $args = array(), $fail_on_missing = true)
        {
            if (!$this->hasSection($name)) {
                if ($fail_on_missing) {
                    throw new \RuntimeException("Cannot find section {$name}");
                }
                return "";
            }

        }

        public function enhanceException(Exception $e, $section = NULL)
        {
            if (!empty($e->enhanced)) {
                return;
            }

            $message = $e->getMessage() . "( IN " . 'index.tpl.php';
            if ($section) {
                $message .= " | section: {$section}";
            }
            $message .= ")";

            $object   = new ReflectionObject($e);
            $property = $object->getProperty('message');
            $property->setAccessible(true);
            $property->setValue($e, $message);

            $e->enhanced = true;
        }

        public function render(Array $vars = array(), $return = false)
        {
            try {
                return $this->_render($vars, $return);
            } catch (Exception $e) {
                if ($return) ob_get_clean();
                $this->enhanceException($e);
                throw $e;
            }
        }

        public function _render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }

            echo "<?php\n/**\n *  Autoloader function generated by crodas/Autoloader\n *\n *  https://github.com/crodas/Autoloader\n *\n *  This is a generated file, do not modify it.\n */\n\nspl_autoload_register(function(\$class) {\n    static \$loaded     = array();\n    static \$namespaces = ";
            var_export($filemap);
            echo ";\n\n    \$class = strtolower(\$class);\n\n    foreach (\$namespaces as \$namespace => \$file) {\n        if (\$namespace !== '-all' && strpos(\$class, \$namespace) !== 0) {\n            // wrong namespace move forward!\n            continue;\n        }\n\n        if (empty(\$loaded[\$namespace])) {\n";
            if ($relative) {
                echo "            \$loaded[\$namespace] = require __DIR__ . \$file;\n";
            }
            else {
                echo "            \$loaded[\$namespace] = require \$file;\n";
            }
            echo "        }\n        return \$loaded[\$namespace]( \$class );\n    }\n});\n\n";
            foreach($includes as $include) {

                $this->context['include'] = $include;
                if ($relative) {
                    echo "        require_once __DIR__ . ";
                    var_export($include);
                    echo ";\n";
                }
                else {
                    echo "        require_once ";
                    var_export($include);
                    echo ";\n";
                }
            }

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from load.tpl.php
     */
    class class_e516b5d47b06ed172138f3ca3ffbb2cc7d52a85a extends base_template_aab8d954813d6a190dfedd5e726e6f8ed2ef081e
    {

        public function hasSection($name)
        {

            return false;
        }


        public function renderSection($name, Array $args = array(), $fail_on_missing = true)
        {
            if (!$this->hasSection($name)) {
                if ($fail_on_missing) {
                    throw new \RuntimeException("Cannot find section {$name}");
                }
                return "";
            }

        }

        public function enhanceException(Exception $e, $section = NULL)
        {
            if (!empty($e->enhanced)) {
                return;
            }

            $message = $e->getMessage() . "( IN " . 'load.tpl.php';
            if ($section) {
                $message .= " | section: {$section}";
            }
            $message .= ")";

            $object   = new ReflectionObject($e);
            $property = $object->getProperty('message');
            $property->setAccessible(true);
            $property->setValue($e, $message);

            $e->enhanced = true;
        }

        public function render(Array $vars = array(), $return = false)
        {
            try {
                return $this->_render($vars, $return);
            } catch (Exception $e) {
                if ($return) ob_get_clean();
                $this->enhanceException($e);
                throw $e;
            }
        }

        public function _render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }

            echo "\$class = strtolower(\$class);\nif (isset(\$classes[\$class])) {\n";
            if ($stats) {
                echo "        \$GLOBALS['call_" . ($stats) . "']++;\n";
            }
            if (count($deps) > 0) {
                echo "    if (!empty(\$deps[\$class])) {\n        foreach (\$deps[\$class] as \$zclass) {\n";
                Autoloader\Templates::exec("validate", array('var' => 'zclass'), $this->context);
                echo "        }\n    }\n";
            }
            Autoloader\Templates::exec("validate", array('var' => 'class'), $this->context);
            echo "    return true;\n}\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from namespace.tpl.php
     */
    class class_31028f15e50ec3b9927c300a53d04bb713e73e85 extends base_template_aab8d954813d6a190dfedd5e726e6f8ed2ef081e
    {

        public function hasSection($name)
        {

            return false;
        }


        public function renderSection($name, Array $args = array(), $fail_on_missing = true)
        {
            if (!$this->hasSection($name)) {
                if ($fail_on_missing) {
                    throw new \RuntimeException("Cannot find section {$name}");
                }
                return "";
            }

        }

        public function enhanceException(Exception $e, $section = NULL)
        {
            if (!empty($e->enhanced)) {
                return;
            }

            $message = $e->getMessage() . "( IN " . 'namespace.tpl.php';
            if ($section) {
                $message .= " | section: {$section}";
            }
            $message .= ")";

            $object   = new ReflectionObject($e);
            $property = $object->getProperty('message');
            $property->setAccessible(true);
            $property->setValue($e, $message);

            $e->enhanced = true;
        }

        public function render(Array $vars = array(), $return = false)
        {
            try {
                return $this->_render($vars, $return);
            } catch (Exception $e) {
                if ($return) ob_get_clean();
                $this->enhanceException($e);
                throw $e;
            }
        }

        public function _render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }

            echo "<?php\n/**\n *  Autoloader function generated by crodas/Autoloader\n *\n *  https://github.com/crodas/Autoloader\n *\n *  This is a generated file, do not modify it.\n */\n\n";
            $function = 'autoloader_' . uniqid(true);
            $this->context['function'] = $function;
            if ($relative) {
                $dir = '__DIR__' . uniqid(true);
                $this->context['dir'] = $dir;
                echo "define(";
                var_export($dir);
                echo ", defined('__DIR__') ? __DIR__ : dirname(__FILE__));\n";
            }
            echo "\nfunction ";
            echo $function . "(\$class) {\n    static \$classes = ";
            echo var_export($classes, true) . ";\n\n";
            if (count($deps) > 0) {
                echo "    static \$deps    = " . (var_export($deps, true)) . ";\n";
            }
            echo "\n";
            Autoloader\Templates::exec("load", $this->context);
            echo "\n    return false;\n}\n\nreturn ";
            var_export($function);
            echo ";\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from validate.tpl.php
     */
    class class_5df81830c135da22bb7e6b9a266d6801c6bfeb2f extends base_template_aab8d954813d6a190dfedd5e726e6f8ed2ef081e
    {

        public function hasSection($name)
        {

            return false;
        }


        public function renderSection($name, Array $args = array(), $fail_on_missing = true)
        {
            if (!$this->hasSection($name)) {
                if ($fail_on_missing) {
                    throw new \RuntimeException("Cannot find section {$name}");
                }
                return "";
            }

        }

        public function enhanceException(Exception $e, $section = NULL)
        {
            if (!empty($e->enhanced)) {
                return;
            }

            $message = $e->getMessage() . "( IN " . 'validate.tpl.php';
            if ($section) {
                $message .= " | section: {$section}";
            }
            $message .= ")";

            $object   = new ReflectionObject($e);
            $property = $object->getProperty('message');
            $property->setAccessible(true);
            $property->setValue($e, $message);

            $e->enhanced = true;
        }

        public function render(Array $vars = array(), $return = false)
        {
            try {
                return $this->_render($vars, $return);
            } catch (Exception $e) {
                if ($return) ob_get_clean();
                $this->enhanceException($e);
                throw $e;
            }
        }

        public function _render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }

            echo "if (\n    ! \$classes[\$";
            echo $var . "][1]( \$" . ($var) . ", false )\n) {\n";
            if ($stats) {
                echo "    \$GLOBALS['load_" . ($stats) . "']++;\n";
            }
            if ($relative) {
                echo "    require " . ($dir) . "  . \$classes[\$" . ($var) . "][0];\n";
            }
            else {
                echo "    require \$classes[\$" . ($var) . "][0];\n";
            }
            echo "}\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

}

namespace Autoloader {


    class Templates
    {
        public static function getAll()
        {
            return array (
                0 => 'autoloader',
                1 => 'index',
                2 => 'load',
                3 => 'namespace',
                4 => 'validate',
            );
        }

        public static function getAllSections($name, $fail = true)
        {
            switch ($name) {
            default:
                if ($fail) {
                    throw new \RuntimeException("Cannot find section {$name}");
                }

                return array();
            }
        }

        public static function exec($name, Array $context = array(), Array $global = array())
        {
            $tpl = self::get($name);
            return $tpl->render(array_merge($global, $context));
        }

        public static function get($name, Array $context = array())
        {
            static $classes = array (
                'autoloader.tpl.php' => 'class_0e0b04df3e4434b3998ba2ae1d44780d04317374',
                'autoloader' => 'class_0e0b04df3e4434b3998ba2ae1d44780d04317374',
                'index.tpl.php' => 'class_a3ffbb3ad03f81c3fd1d93e6556beae4811dc590',
                'index' => 'class_a3ffbb3ad03f81c3fd1d93e6556beae4811dc590',
                'load.tpl.php' => 'class_e516b5d47b06ed172138f3ca3ffbb2cc7d52a85a',
                'load' => 'class_e516b5d47b06ed172138f3ca3ffbb2cc7d52a85a',
                'namespace.tpl.php' => 'class_31028f15e50ec3b9927c300a53d04bb713e73e85',
                'namespace' => 'class_31028f15e50ec3b9927c300a53d04bb713e73e85',
                'validate.tpl.php' => 'class_5df81830c135da22bb7e6b9a266d6801c6bfeb2f',
                'validate' => 'class_5df81830c135da22bb7e6b9a266d6801c6bfeb2f',
            );
            $name = strtolower($name);
            if (empty($classes[$name])) {
                throw new \RuntimeException("Cannot find template $name");
            }

            $class = "\\" . $classes[$name];
            return new $class;
        }
    }

}
