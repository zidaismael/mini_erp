<?php
declare(strict_types = 1);

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;
use Router\ErpRouter;
use Exception\ApiException;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {

    $container = new FactoryDefault();
    $app = new Micro($container);
    
    //services init
    include APP_PATH . '/config/services.php';
    
    //load config
    $config = $container->getConfig();
    
    //app Autoloader init
    include APP_PATH . '/config/loader.php';

    //construct Mini-ERP routes
    $erpRouter = new ErpRouter();
    $erpRouter->init($app);
    
 
    $app->handle($_SERVER["REQUEST_URI"]);
    
} catch (\Exception $e) {
    //force not set or unsupported code to 500;
    if(ApiException::validateCode($e->getCode())){
        $errorCode=$e->getCode();
    }else{
        $errorCode=500;
    }
    
    //@todo log below as errors
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
    
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
