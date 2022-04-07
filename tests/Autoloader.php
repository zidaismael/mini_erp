<?php
declare(strict_types=1);

class Autoloader
{

    protected static $directories=[];
    
    /**
     * Add classes directory
     * @param string $directory
     */
    public static function addClassesDirectory(string $directory){
        static::$directories[]=$directory;
    }
    
    /**
     * Static method that stored the static autoloader method in the spl register with the namespace Utils
     */
    public static function registerAutoloader()
    {
        // Add Libraries directory first in include path
        set_include_path(__DIR__ . DIRECTORY_SEPARATOR . get_include_path());
        spl_autoload_register(__NAMESPACE__ . "\\Autoloader::autoload");
    }

    /**
     * Static method to inclure/require the file automaticaly, depend of the type and the origin of the file
     * @param type $className the name of the class, like \namespace\classname or classname
     * @return bool
     */
    public static function autoload(string $className): bool
    {
        
        foreach(static::$directories as $directory){
            $className=trim($className);
            $className=str_replace('\\',DIRECTORY_SEPARATOR,$className);
            $pathName = $directory.DIRECTORY_SEPARATOR.$className.'.php';
            if (file_exists($pathName)) {
                require_once $pathName;
                return true;
            }
        }
        
        return false;
    }
}
