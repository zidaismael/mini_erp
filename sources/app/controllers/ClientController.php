<?php
declare(strict_types=1);

class ClientController extends AbstactController
{
    
    public function get()
    {
        //no id passed => list 
        
        
        //id passed => get one
        
        return $this->output(201,['toto' => "sdfsdf"]);
    }
    
    public function post()
    {
        $body=$this->getBody(['tot']);
    }
    
    public function put()
    {
    }
    
    public function delete()
    {
    }

}

