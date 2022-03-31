<?php
namespace Router;

use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\Collection;

class ErpRouter extends Collection
{
    /**
     * api basepath
     * @var string
     */
    protected $baseApiPath='/api';
    
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
            'method' => ['POST','PUT','DELETE']
        ],
        'client/{id}' => [
            'controller' => 'ClientController',
            'method' => ['GET']
        ],
        'provider' => [
            'controller' => 'ProviderController',
            'method' => ['POST','PUT','DELETE']
        ],
        'provider/{id}' => [
            'controller' => 'ProviderController',
            'method' => ['GET']
        ],
        'company' =>  [
            'controller' => 'CompanyController',
            'method' => ['POST','PUT','DELETE']
        ],
        'company/{id}' => [
            'controller' => 'CompanyController',
            'method' => ['GET']
        ],
        'employee' =>  [
            'controller' => 'EmployeeController',
            'method' => ['POST','PUT','DELETE']
        ],
        'employee/{id}' => [
            'controller' => 'EmployeeController',
            'method' => ['GET']
        ],
        'product' =>  [
            'controller' => 'ProductController',
            'method' => ['POST','PUT','DELETE']
        ],
        'product/{id}' => [
            'controller' => 'ProductController',
            'method' => ['GET']
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
        
        //single
        'company/{id}/product' =>  [
            'controller' => 'CompanyProductController',
            'method' => ['POST']
        ],
        'company/{id}/employee' =>  [
            'controller' => 'CompanyEmployeeController',
            'method' => ['POST']
        ],
        'provider/{id}/product' =>  [
            'controller' => 'ProviderProductController',
            'method' => ['POST']
        ],
        'client/{id}/transaction' =>  [
            'controller' => 'ClientTransactionController',
            'method' => ['POST']
        ],
        'employee/{id}/transaction' =>  [
            'controller' => 'EmployeeTransactionController',
            'method' => ['POST']
        ],
    ];   
    
    /**
     * Dynamically contruct and activate API available routes according to REST rules
     * @param Micro $app
     */
    public function init(Micro $app){
        $erpRoutes=array_merge($this->basicRoutes,$this->extendsRoutes);
        
        foreach($erpRoutes as $path => $route){
            foreach($route['method'] as $method){
                $calledFunctionName=strtolower($method);
  
                if(class_exists($route['controller']) && method_exists($this, $calledFunctionName) &&  method_exists($route['controller'], $calledFunctionName)){                  
                    $this->setHandler(new $route['controller'])->setPrefix($this->baseApiPath)->$calledFunctionName('/'.$path, $calledFunctionName);
                }
            }
        }

        $app->mount($this);
    }
    
    
    /**
     * 
     * @return array
     */
    public function getErpRoutes(): array{
        return $this->basicRoutes;
    }
}

