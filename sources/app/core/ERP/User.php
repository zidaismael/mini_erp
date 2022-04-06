<?php
declare(strict_types = 1);

namespace ERP;

use Phalcon\Mvc\Model\Resultset\Simple;
use Model\User;

class User
{
    /**
     * 
     * @var Phalcon\Mvc\Model\Resultset\Simple
     */
    protected $model;
    
    /**
     * access type
     * @var string
     */
    protected $access;
    
    /**
     * role name
     * @var string
     */
    protected $role;
    
    /**
     * Login to app
     * @param string $password
     * @return boolean
     */
    public function authenticate(string $password){
        return false;
    }
    
    public function canAccess(){
        
    }
    
    protected function setUserInfo(){
        $this->model=$model;
        $this->access=$model->getAccessType();
    }
}

