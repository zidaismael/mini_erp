<?php
declare(strict_types = 1);

class ClientController extends AbstactController
{

    public function get($id = null)
    {
        $this->validateData('int', $id, [
            'message' => "Id must be an integer."
        ]);
        
        $clientModel=new Client();
        // var_dump($clientModel);
        // no id passed => list
        if (is_null($id)) {
            
        } else {
            var_dump($id);
        }
        // id passed => get one
        
        return $this->output(200, [
            'toto' => "sdfsdf"
        ]);
    }

    public function post()
    {
        $body = $this->getBody([
            'tot'
        ]);
    }

    public function put()
    {}

    public function delete()
    {}
}

