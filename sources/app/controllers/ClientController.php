<?php
declare(strict_types = 1);

use Exception\ApiException;
use Exception\DuplicateModelException;

class ClientController extends AbstractController
{

    public function get($id = null)
    {
        if (is_null($id)) { // no id passed => list
            $result = Client::find();
        } else { // id passed => get one
                 
            // input parameters validation
            $this->validateData('int', $id, [
                'message' => "Id must be an integer."
            ]);
            
            $result = Client::find($id);
        }
        
        $client = $result->toArray();
        
        if (! empty($client)) {
            $client = is_null($id) ? $client : $client[0];
            return $this->output(200, $client);
        } else {
            return $this->output(404);
        }
    }

    public function post()
    {
        // get body and check mandatory input parameters validation
        $body = $this->getBody([
            'reference',
            'lastname',
            'firstname',
            'address',
            'country'
        ]);
        
        // validate input field values
        $this->validateData('string', $body['reference'], [
            'min' => 10,
            'max' => 10,
            'messageMaximum' => "Client's reference must be a 10 characters string",
            'messageMinimum' => "Client's reference must be a 10 characters string",
            'includedMinimum' => false,
            'includedMaximum' => false
        ]);
        
        $this->validateData('string', $body['lastname'], [
            'min' => 2,
            'max' => 50,
            'messageMaximum' => "Client's lastname must be a string between 2 and 50 characters",
            'includedMinimum' => false,
            'includedMaximum' => false
        ]);
        $this->validateData('string', $body['firstname'], [
            'min' => 2,
            'max' => 50,
            'messageMaximum' => "Client's firstname must be a string between 2 and 50 characters",
            'includedMinimum' => false,
            'includedMaximum' => false
        ]);
        $this->validateData('string', $body['address'], [
            'min' => 2,
            'max' => 255,
            'messageMaximum' => "Client's address must be a string between 2 and 255 characters",
            'includedMinimum' => false,
            'includedMaximum' => false
        ]);
        $this->validateData('string', $body['country'], [
            'min' => 2,
            'max' => 50,
            'messageMaximum' => "Client's country must be a string between 2 and 50 characters",
            'includedMinimum' => false,
            'includedMaximum' => false
        ]);
        
        $client = new Client();
        $client->assign($body);

        try{
            if ($client->create()) {
                $result = $client->find($client->id);
                return $this->output(201, $result->toArray()[0]);
            } else {
                //@todo log
                throw new ApiException(sprintf("An error occured on client creation: %s", json_encode($body)), 503);
            }
        }catch(DuplicateModelException $e){
            //@todo log
            throw new ApiException(sprintf("Unique information have been duplicate: %s", json_encode($body)), 409);
        }catch(\Exception $e){
            //@todo log
            throw new ApiException(sprintf("An error occured on client creation: %s", json_encode($body)), 503);
        }
    }

    public function put($id)
    {}

    public function delete($id)
    {}
}

