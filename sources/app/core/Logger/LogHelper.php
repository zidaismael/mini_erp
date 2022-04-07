<?php
declare(strict_types = 1);

namespace Logger;

use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;
use Exception\CoreException;

final class LogHelper
{
    
    protected static string $logPath;
    
    protected static Logger $instance;
    
    private static function getLogger(){
        if(empty(static::$logPath)){
            throw new CoreException(sprintf("You have to specify log path before use it %s",__METHOD__));
        }
        
        if(!isset(static::$instance)){
            static::$instance=new Logger('messages',['main' => new Stream(static::$logPath)]);
        }
        
        return static::$instance;
    }

    public static function debug(string $message){
        static::getLogger()->debug($message);
    }
    
    public static function info(string $message){
        static::getLogger()->info($message);
    }
    
    public static function warning(string $message){
        static::getLogger()->warning($message);
    }
    
    public static function error(string $message){
        static::getLogger()->error($message);
    }
    
    public static function setLogPath(string $path){
        static::$logPath=$path;
    }
}

