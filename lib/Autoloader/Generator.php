<?php
/*
  +---------------------------------------------------------------------------------+
  | Copyright (c) 2012 César Rodas                                                  |
  +---------------------------------------------------------------------------------+
  | Redistribution and use in source and binary forms, with or without              |
  | modification, are permitted provided that the following conditions are met:     |
  | 1. Redistributions of source code must retain the above copyright               |
  |    notice, this list of conditions and the following disclaimer.                |
  |                                                                                 |
  | 2. Redistributions in binary form must reproduce the above copyright            |
  |    notice, this list of conditions and the following disclaimer in the          |
  |    documentation and/or other materials provided with the distribution.         |
  |                                                                                 |
  | 3. All advertising materials mentioning features or use of this software        |
  |    must display the following acknowledgement:                                  |
  |    This product includes software developed by César D. Rodas.                  |
  |                                                                                 |
  | 4. Neither the name of the César D. Rodas nor the                               |
  |    names of its contributors may be used to endorse or promote products         |
  |    derived from this software without specific prior written permission.        |
  |                                                                                 |
  | THIS SOFTWARE IS PROVIDED BY CÉSAR D. RODAS ''AS IS'' AND ANY                   |
  | EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED       |
  | WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE          |
  | DISCLAIMED. IN NO EVENT SHALL CÉSAR D. RODAS BE LIABLE FOR ANY                  |
  | DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES      |
  | (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;    |
  | LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND     |
  | ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT      |
  | (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS   |
  | SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE                     |
  +---------------------------------------------------------------------------------+
  | Authors: César Rodas <crodas@php.net>                                           |
  +---------------------------------------------------------------------------------+
*/

namespace Autoloader;

use Symfony\Component\Finder\Finder,
    Notoj\Notoj,
    crodas\FileUtil\Path,
    crodas\FileUtil\File;

class Generator
{
    protected $path;
    protected $stats;
    protected $callback;
    protected $callback_path;

    protected $namespace = "";
    protected $alias = array();
    protected $includes = array();
    protected $lastClass = NULL;

    protected $trait;

    protected $classes;
    protected $classes_obj;
    protected $hasTraits;
    protected $hasInterface;

    protected $parsed = false;

    /** settings */

    protected $relative     = false;
    protected $include_psr0 = true;
    protected $singleFile   = true;


    public function __construct($dir = NULL)
    {
        if (!is_null($dir)) {
            $this->setScanPath($dir);
        }
        $this->trait = defined('T_TRAIT') ? T_TRAIT : "";
        ini_set('xdebug.max_nesting_level', 2000);
    }

    public function singleFile()
    {
        $this->singleFile = true;
        return $this;
    }

    public function multipleFiles()
    {
        $this->singleFile = false;
        return $this;
    }

    public function includeFiles(Array $files)
    {
        /** reset all parser data */
        $this->parsed = false;
        $this->includes = array_merge($this->includes, $files);
        return $this;
    }

    public function relativePaths ($rel = true)
    {
        /** reset all parser data */
        $this->parsed = false;
        $this->relative = $rel;
        return $this;
    }

    public function IncludePSR0Autoloader($psr0 = true)
    {
        /** reset all parser data */
        $this->parserd = false;
        $this->include_psr0 = $psr0;
        return $this;
    }

    protected function checkDirExists($dir)
    {
        if (!is_dir($dir)) {
            throw new \RuntimeException(dirname($dir) . " is not a directory");
        }

        if (!is_writable($dir)) {
            throw new \RuntimeException(dirname($dir) . " is not a writable");
        }

        if (file_exists($dir) && !is_dir($dir)) {
            throw new \RuntimeException("{$output} exists but it isn't a file");
        }
    }

    public function setScanPath($dir) {
        /** reset all parser data */
        $this->parsed = false;

        if ($dir instanceof Finder || is_array($dir)) {
            $this->path = $dir;
            return true;
        }

        $this->checkDirExists($dir);

        $finder = new Finder;
        $this->path = $finder->files()
            ->name('*.php')
            ->in($dir);
        return true;
    }


    public function setStepCallback(\Closure $cbc)
    {
        $this->callback = $cbc;

        return $this;
    }

    public function setPathCallback(\Closure $cbc)
    {
        $this->callback_path = $cbc;
        return $this;
    }

    public function enableStats($name)
    {
        $this->stats = $name;
    }

    public function getNamespacefile($class, $prefix)
    {
        if ($this->callback_path) {
            $path = call_user_func($this->callback_path, $class, $prefix);
            if ($path) {
                return $path;
            }
        }

        $dir  = preg_replace("/.php$/", "/", $prefix) ;
        $file = 'loader:' . str_replace("\\", ".", $class) . ".php";

        if (!is_dir($dir)) {
            if (!mkdir($dir)) {
                throw new \RuntimeException("Cannot create directory {$dir}");
            }
        }

        return $dir . preg_replace("/(\.+|\.\_)/", ".", $file);
    }

    protected function getClassesDeps($classes)
    {
        $deps    = array();
        $allDeps = $this->deps;

        foreach ($classes as $class => $file) {
            if (!empty($allDeps[$class])) {
                $deps[$class] = $allDeps[$class];
            }
        }

        return $deps;
    }

    protected function renderClassesFile($classes, $namespace, $prefix)
    {
        $deps = $this->getClassesDeps($classes);

        // If in our dependency tree we reference
        // to another class which handled in another file
        // we *must* duplicate that class definition in o
        // order to make autoloading simpler
        foreach ($deps as $dep) {
            foreach ($dep as $class) {
                if (empty($classes[$class])) {
                    $classes[$class] = $this->classes[$class];
                }
            }
        }

        $file  = $this->getNamespacefile($namespace, $prefix);
        $nargs = $this->getTemplateArgs($file, compact('classes', 'deps'));
        $code  = Templates::get('namespace')->render($nargs, true);
        File::write($file, $code);

        return $file;
    }

    protected function getClassesByNamespace($namespaces, $allClasses)
    {
        $nsClasses = array();
        foreach (array_keys($namespaces) as $namespace) {
            $classes = array();
            foreach ($allClasses as $class => $file) {
                if (strpos($class, $namespace) === 0) {
                    $classes[$class] = $file;
                    unset($allClasses[$class]);
                }
            }
            $nsClasses[$namespace] = $classes;
        }
        if (count($allClasses) > 0) {
            $nsClasses['-all'] = $allClasses;
        }
        return $nsClasses;
    }

    protected function renderMultiple($output, $namespaces)
    {
        // sort them by length
        uksort($namespaces, function($a, $b) {
            return strlen($b) - strlen($a);
        });

        $prefix = $output;

        $filemap     = array();
        $extraLoader = false;
        $allClasses  = $this->classes;

        foreach ($this->getClassesByNamespace($namespaces, $allClasses) as $namespace => $classes) {
            $file = $this->renderClassesFile($classes, $namespace, $prefix);
            $filemap[$namespace] = $this->relative ? Path::getRelative($file, $output) : $file;
        }

        $nargs = array_merge($this->getTemplateArgs($output), compact('filemap', 'relative', 'extraLoader'));
        $nargs['relative'] = true;
        $code  = Templates::get('index')->render($nargs, true);
        File::Write($output, $code);
    }
    
    protected function renderSingle($output)
    {
        $code = Templates::get('autoloader')
            ->render($this->GetTemplateArgs($output), true);
        File::write($output, $code);
    }

    protected function getClassInfo($class)
    {
        $parts = explode("\\", $class);
        $len   = count($parts)-1;
        $sep   = "\\";

        if ($len == 0) {
            $parts = explode("_", $class);
            $len   = count($parts) - 1;
            $sep   = "_";
        }

        return compact('parts', 'len', 'sep');
    }

    protected function checkIfShouldGroup($classes)
    {
        // group the classes in namespaces
        $namespaces = array();
        foreach ($classes as $class => $file) {
            $info = $this->getClassInfo($class);

            for ($i = 0; $i < $info['len']; $i++) {
                $namespace = implode($info['sep'], array_slice($info['parts'], 0, $i+1)) . $info['sep'];
                if (empty($namespaces[$namespace])) {
                    $namespaces[$namespace] = 0;
                }
                $namespaces[$namespace]++;
            }
        } 

        // return an array with the most common namespaces
        return array_filter($namespaces, function($classes) {
            return $classes >= 5;
        });

    }

    protected function getParser()
    {
        return new \crodas\ClassInfo\ClassInfo;
    }

    protected function buildDepTree($class, &$loaded)
    {
        $deps = array();
        if (isset($loaded[$class->getName()])) {
            return array();
        }
        $loaded[$class->getName()] = true;
        $zdeps = array_merge($class->getInterfaces(), $class->getTraits());
        if ($parent = $class->getParent()) {
            $zdeps  = array_merge(array($class->getParent()), $zdeps);
        }
        
        foreach (array_reverse($zdeps) as $dep){
            if ($dep->isUserDefined()) {
                $deps   = array_merge($deps, $this->buildDepTree($dep, $loaded));
                $deps[] = strtolower($dep->getName());
            }
        }

        return $deps;
    }

    protected function generateClassDependencyTree()
    {
        $types   = array();
        $classes = array();
        $deps    = array();
        foreach ($this->classes_obj as $id => $class) {
            if (!$class->isUserDefined()) {
                continue;
            }
            $types[$class->getType()] = 1;
            $loaded = array();
            $dep = $this->buildDepTree($class, $loaded);
            if (count($dep) > 0) {
                $deps[$id] = array_unique($dep);
            }
            $classes[$id] = array($class->GetFile(), $class->getType() . '_exists');
        }

        $this->hasTraits    = is_callable('trait_exists') && !empty($types['trait']);
        $this->hasInterface = !empty($types['interface']);
        $this->classes      = $classes;
        $this->deps         = $deps;
    }

    protected function getTemplateArgs($file = "", $default = array())
    {
        $args  = array(
            'classes', 'relative', 'deps', 'include_psr0', 
            'stats', 'hasTraits', 'hasInterface', 'includes',
        );

        $return = array();
        foreach ($args as $arg) {
            $return[$arg] = array_key_exists($arg, $default) ? $default[$arg] : $this->$arg;
        }
        $return['relative'] = $return['relative'] && !empty($file);

        if ($return['relative']) {
            foreach ($return['classes'] as $class => $fileClass) {
                $return['classes'][$class][0] = Path::getRelative($fileClass[0], $file);
            }
            foreach ($return['includes'] as $id => $include) {
                $return['includes'][$id] = Path::getRelative($include, $file);
            }
        }

        return $return;
    }

    protected function loadCache($cache, &$Zfiles, &$cached)
    {
        if ($cache && is_file($cache)) {
            $data = unserialize(file_get_contents($cache));
            if (is_array($data) && count($data) == 2) {
                $zfiles = $data[0];
                $cached = $data[1];
            }
        }
    }

    protected function loadClassesFromCache($cached)
    {
        foreach ($cached as $id => $class) {
            if (!empty($hit[$class->getFile()])) {
                $this->classes_obj[$id] = $class;
            }
        }
    }


    protected function saveCache($cache, $zfiles, $files, $cached)
    {
        if ($cache) {
            $tocache = array(array_merge($zfiles, $files), array_merge($cached, $this->classes_obj));
            file_put_contents($cache, serialize($tocache));
        }
    }

    /**
     *  Return a list of php files found which contains at least one
     *  class, interface or trait
     *
     *  @return []
     */
    public function getPHPFiles(&$zfiles)
    {
        $files = array();
        foreach ($this->path as $file) {
            $path = $file->getRealPath();
            if (!empty($zfiles[$path]) && filemtime($path) <= $zfiles[$path]) {
                continue;
            }

            $zfiles[$path] =  filemtime($path);
            if (!preg_match('/\s(class|interface|trait)\s/ismU', file_get_contents($path))) {
                /* no classes */
                continue;
            }
            $files[] = $path;
        }
        return $files;
    }

    public function parse($output, $cache = NULL)
    {
        if (!empty($this->parsed)) {
            return $this->getTemplateArgs($output);
        }

        $dir = realpath(dirname($output));
        $this->checkDirExists($dir);

        $deps      = array();
        $relatives = array();
        $callback  = $this->callback;


        $this->classes_obj = array();
        $callback = !empty($callback) ? $callback : function() {};

        $parser = $this->getParser();
        $zfiles = array();
        $cached = array();
        $files  = array();

        
        $this->loadCache($cache, $zfiles, $cached);
        $files = $this->getPHPFiles($zfiles);

        foreach ($files as $path) {
            $this->currentFile = $path;
            try {
                $parser->parse($path);
                $callback($path, $parser->getClasses());
            } catch (\Exception $e) {
                $callback($path, $parser->getClasses(), $e);
            }
        }

        $this->classes_obj = $parser->getClasses();
        $this->loadClassesFromCache($cached);
        $this->generateClassDependencyTree();
        $this->saveCache($cache, $zfiles, $files, $cached);
        return $this->getTemplateArgs($output);
    }

    public function generate($output, $cache = '')
    {
        $data = $this->parse($output, $cache);
        $this->writeAutoloader($output);
        return $data;
    }

    protected function writeAutoloader($output)
    {
        if (!$this->singleFile) {
            $namespaces = $this->checkIfShouldGroup($this->classes);
            if (count($namespaces) > 0) {
                return $this->renderMultiple($output, $namespaces);
            }
        }

        return $this->renderSingle($output);
    }
}

