<?php
namespace Router;

use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\Collection;

class ErpRouter extends Collection
{

    /**
     * basic routes and controllers declarations
     * @var array
     */
    protected $basicRoutes=[
        //list
        'clients' => [
            'controller' => 'ClientController',
            'method' => ['GET']
        ],
        'providers' => [
            'controller' => 'ProviderController',
            'method' => ['GET']
        ],
        'companies' =>  [
            'controller' => 'CompanyController',
            'method' => ['GET']
        ],
        'employees' =>  [
            'controller' => 'EmployeeController',
            'method' => ['GET']
        ],
        
        //single
        'client' => [
            'controller' => 'ClientController',
            'method' => ['POST']
        ],
        'client/{id}' => [
            'controller' => 'ClientController',
            'method' => ['GET','PUT','DELETE']
        ],
        'provider' => [
            'controller' => 'ProviderController',
            'method' => ['POST']
        ],
        'provider/{id}' => [
            'controller' => 'ProviderController',
            'method' => ['GET','PUT','DELETE']
        ],
        'company' =>  [
            'controller' => 'CompanyController',
            'method' => ['POST']
        ],
        'company/{id}' => [
            'controller' => 'CompanyController',
            'method' => ['GET','PUT','DELETE']
        ],
        'employee' =>  [
            'controller' => 'EmployeeController',
            'method' => ['POST']
        ],
        'employee/{id}' => [
            'controller' => 'EmployeeController',
            'method' => ['GET','PUT','DELETE']
        ],
        'product' =>  [
            'controller' => 'ProductController',
            'method' => ['POST']
        ],
        'product/{id}' => [
            'controller' => 'ProductController',
            'method' => ['GET','PUT','DELETE']
        ],
        'transaction' =>  [
            'controller' => 'TransactionController',
            'method' => ['POST']
        ],
        'transaction/{id}' => [
            'controller' => 'TransactionController',
            'method' => ['GET']
        ],
    ];
    
    /**
     * basic routes and controllers declarations
     * @var array
     */
    protected $extendsRoutes=[
        //list
        'company/{id}/products' =>  [
            'controller' => 'CompanyProductController',
            'method' => ['GET']
        ],
        'company/{id}/employees' =>  [
            'controller' => 'CompanyEmployeeController',
            'method' => ['GET']
        ],
        'provider/{id}/products' =>  [
            'controller' => 'ProviderProductController',
            'method' => ['GET']
        ],
        'client/{id}/transactions' =>  [
            'controller' => 'ClientTransactionController',
            'method' => ['GET']
        ],
        'employee/{id}/transactions' =>  [
            'controller' => 'EmployeeTransactionController',
            'method' => ['GET']
        ],
        
        //business routes
        'company/{id}/buyproducts' =>  [
            'controller' => 'CompanyProductController',
            'method' => ['POST'],
            'callback' => 'buyProducts'
        ],
        'company/{id}/sellproducts' =>  [
            'controller' => 'CompanyProductController',
            'method' => ['POST'],
            'callback' => 'sellProducts'
        ]
        
    ];   
    
    /**
     * Dynamically contruct and activate API available routes according to REST rules
     * @param Micro $app
     */
    public function init(Micro $app){
        $erpRoutes=array_merge($this->basicRoutes,$this->extendsRoutes);
        
        $basePath=$app->config->application->baseUri;
        
        foreach($erpRoutes as $path => $route){
            foreach($route['method'] as $method){
                $calledFunctionName=strtolower($method);
                $calledControllerMethod= $route['callback'] ?? $calledFunctionName;
  
                if(class_exists($route['controller']) && method_exists($this, $calledFunctionName) &&  method_exists($route['controller'], $calledControllerMethod)){                  
                    $this->setHandler(new $route['controller'])->setPrefix($basePath.$path)->$calledFunctionName('', $calledControllerMethod);
                    $app->mount($this);
                }
            }
        }
    }
    
    
    /**
     * 
     * @return array
     */
    public function getErpRoutes(): array{
        return $this->basicRoutes;
    }
}

