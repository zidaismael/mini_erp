<?php
declare(strict_types = 1);

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;
use Router\ErpRouter;
use Exception\ApiException;
use Logger\LogHelper;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {
    
    $composerAutoloadFilePath=BASE_PATH.'/../vendor/autoload.php';
    if(file_exists($composerAutoloadFilePath)){
        require_once $composerAutoloadFilePath;
    }

    $container = new FactoryDefault();
    $app = new Micro($container);
    
    //services init
    include APP_PATH . '/config/services.php';
    
    //load config
    $config = $container->getConfig();
    
    //app Autoloader init
    include APP_PATH . '/config/loader.php';
    
    LogHelper::setLogPath($config->application->log->path);

    //construct Mini-ERP routes
    $erpRouter = new ErpRouter();
    $erpRouter->init($app);
    
    $app->handle($_SERVER["REQUEST_URI"]);
    
} catch (\Exception $e) {
    //force not set or unsupported code to 500;
    if(ApiException::validateCode($e->getCode())){
        $errorCode=$e->getCode();
        LogHelper::warning($e->getMessage());
    }else{
        $errorCode=500;
        LogHelper::error($e->getMessage());
    }
    
    LogHelper::debug($e->getTraceAsString());
    
    if($errorCode===503){
        $message="Api unavailable due to maintenance.";
    }else if($errorCode>=500){
        $message="A server technical error occured. Please contact administrators.";
    }else{
        $message=$e->getMessage();
    }
  
    $app->response->setStatusCode($errorCode);
    $app->response->setContent($message);
    $app->response->send();
}
