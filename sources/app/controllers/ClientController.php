<?php
declare(strict_types=1);

class ClientController extends \Phalcon\Mvc\Controller
{
    
    public function get()
    {
        return $this->response->setJsonContent(['toto' => 'tata']);
    }

}

