<?php
declare(strict_types = 1);

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;
use Router\ErpRouter;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {
    
    $container = new FactoryDefault();
    $app = new Micro($container);
    
    include APP_PATH . '/config/services.php';
    
    // load config
    $config = $container->getConfig();
    
    include APP_PATH . '/config/loader.php';
    
    // construct Mini-ERP routes
    $erpRouter = new ErpRouter();
    $erpRouter->init($app);
    
    $app->handle($_SERVER["REQUEST_URI"]);
} catch (\Exception $e) {
    //@todo log below as errors
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
    
    if($e->getCode()>=500){
        $message="An server technical error occured. Please contact administrators.";
    }else{
        $message=$e->getMessage();
    }
  
    $app->response->setStatusCode($e->getCode());
    $app->response->setContent($message);
    $app->response->send();
}
