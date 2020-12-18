<?php

class AutoLoader
{
    public static $path = '';

    public static function load(string $path)
    {
        AutoLoader::$path = $path;

        spl_autoload_register(function ($classname) {
            $dirs = array_diff(scandir(AutoLoader::$path), ['.', '..']);
        
            foreach ($dirs as $dir) {
                $dir = AutoLoader::$path . '\\' . $dir . DIRECTORY_SEPARATOR;
        
                if (is_dir($dir)) {
                    $filename = $dir . str_replace('\\', '/', $classname) .'.php';
        
                    if (file_exists($filename)) {
                        require_once $filename;
                        break;
                    }
                }
            }
        });
    }
}

AutoLoader::load( __DIR__ );
