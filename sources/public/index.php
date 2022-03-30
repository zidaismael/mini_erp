<?php
declare(strict_types=1);

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;
use Router\ErpRouter;
use Response\ErpResponse;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');


try {
    
   $container = new FactoryDefault();
   $app = new Micro($container);
   
   include APP_PATH . '/config/services.php';
    
   //load config
   $config = $container->getConfig();
    
   include APP_PATH . '/config/loader.php';
 
   //construct Mini-ERP routes
   $erpRouter=new ErpRouter();
   $erpRouter->init($app);
   
   //set response dependency to output json
   $container->set('responses',function(){new ErpResponse();});

   $app->handle(
       $_SERVER["REQUEST_URI"]
   );
   
} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
