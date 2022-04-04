<?php
declare(strict_types = 1);

use Exception\ApiException;
use Exception\DuplicateModelException;

class ClientController extends AbstractController
{

    /**
     * Get Client list or one client
     * 
     * @param int $id
     *            default null, if null => list else get one
     * @return \Phalcon\Http\Response
     */
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
        
        if (! empty($client)) { //result in DB
            $client = is_null($id) ? $client : $client[0];
            return $this->output(200, $client);
        } else { //no result
            if(is_null($id)){
                return $this->output(200, []);
            }else{
                return $this->output(404);
            }
        }
    }

    /**
     * Create Client
     * 
     * @return \Phalcon\Http\Response
     */
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
        
        $this->validateMandatoryInput($body);
        
        $client = new Client();
        $client->assign($body);
    
        try {
            if ($client->create()) {
                $result = $client->find($client->id);
                return $this->output(201, $result->toArray()[0]);
            } else {
                // @todo log
                throw new ApiException(sprintf("An error occured on client creation: %s", json_encode($body)), 503);
            }
        } catch (DuplicateModelException $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("Unique information have been duplicate: %s \nbody: %s", $e->getSubject(), json_encode($body)), 409);
        } catch (\Exception $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("An error occured on client creation: %s", json_encode($body)), 503);
        }
    }

    /**
     * Update Client
     * 
     * @param int $id            
     */
    public function put(int $id)
    {
        
        // get body and check mandatory input parameters validation
        $body = $this->getBody([
            'reference',
            'lastname',
            'firstname',
            'address',
            'country'
        ]);
        
        // input parameters validation
        $this->validateData('int', $id, [
            'message' => "Id must be an integer."
        ]);
        
        $this->validateMandatoryInput($body);
        
        //check if exists
        $result=Client::find($id);
        if(empty($result->toArray())){
            return $this->output(404);
        }
        
        $client = new Client();
        $client->id = $id;
        $client->assign($body);
        
        try {
            if ($client->save()) {
                $result = $client->find($client->id);
                return $this->output(200, $result->toArray()[0]);
            } else {
                // @todo log
                throw new ApiException(sprintf("An error occured on client update: %s", json_encode($body)), 503);
            }
        } catch (DuplicateModelException $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("Unique information have been duplicate: %s \nbody: %s", $e->getSubject(), json_encode($body)), 409);
        } catch (\Exception $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("An error occured on client creation: %s", json_encode($body)), 503);
        }
    }

    /**
     * Delete Client
     * 
     * @param int $id            
     */
    public function delete(int $id)
    {
        // input parameters validation
        $this->validateData('int', $id, [
            'message' => "Id must be an integer."
        ]);
        
        $client = Client::find($id);
        
        if (empty($client->toArray())) {
            return $this->output(404);
        } else {
            $client->delete();
            return $this->output(204);
        }
        
    }

    /**
     * Validate input
     * 
     * @param array $body            
     */
    protected function validateMandatoryInput(array $body)
    {
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
    }
}

