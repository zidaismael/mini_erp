<?php
declare(strict_types = 1);

use Logger\LogHelper;
use Exception\ApiException;
use Exception\DuplicateModelException;
use \Phalcon\Http\Response;

class ClientController extends AbstractController
{

    /**
     * Get Client list or one client
     *
     * @param int $id default null, if null => list else get one
     * @return \Phalcon\Http\Response
     */
    public function get($id = null): Response
    {
        if (is_null($id)) { // no id passed => list
            $result = ClientModel::find();
            $client = $result->toArray();
        } else { // id passed => get one
                 // input parameters validation
            $this->validateData('int', $id, [
                'message' => "Id must be an integer."
            ]);
            
            $client = ClientModel::findFirst($id);
        }
        
        if (! empty($client)) { // result in DB
            return $this->output(200, $client);
        } else { // no result
            if (is_null($id)) {
                return $this->output(200, []);
            } else {
                throw new ApiException("Not found", 404);
            }
        }
    }

    /**
     * Create Client
     *
     * @return \Phalcon\Http\Response
     */
    public function post(): Response
    {
        // get body and check mandatory input parameters validation
        $body = $this->getBody([
            'lastname',
            'firstname',
            'address',
            'country'
        ]);
        
        $this->validateMandatoryInput($body);
        
        $client = new ClientModel();
        $client->assign($body);
        $this->setReference($client);
        
        try {
            if ($client->create()) {
                $result = $client->findFirst($client->id);
                return $this->output(201, $result);
            } else {
                throw new ApiException(sprintf("An error occured on client creation: %s", json_encode($body)));
            }
        } catch (DuplicateModelException $e) {
            LogHelper::error($e->getMessage());
            throw new ApiException(sprintf("Unique information have been duplicate: %s \nbody: %s", $e->getSubject(), json_encode($body)), 409);
        } catch (\Exception $e) {
            LogHelper::error($e->getMessage());
            throw new ApiException(sprintf("An error occured on client creation: %s", json_encode($body)));
        }
    }

    /**
     * Update Client
     *
     * @param int $id            
     */
    public function put(int $id): Response
    {
        
        // get body and check mandatory input parameters validation
        $body = $this->getBody([
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
        
        // check if exists
        $client = ClientModel::findFirst($id);
        
        if (empty($client)) {
            throw new ApiException("Not found", 404);
        }
        
        $client->assign($body);
        
        try {
            if ($client->save()) {
                $client = $client->findFirst($client->id);
                return $this->output(200, $client);
            } else {
                throw new ApiException(sprintf("An error occured on client update: %s", json_encode($body)));
            }
        } catch (DuplicateModelException $e) {
            LogHelper::error($e->getMessage());
            throw new ApiException(sprintf("Unique information have been duplicate: %s \nbody: %s", $e->getSubject(), json_encode($body)), 409);
        } catch (\Exception $e) {
            LogHelper::error($e->getMessage());
            throw new ApiException(sprintf("An error occured on client creation: %s", json_encode($body)));
        }
    }

    /**
     * Delete Client
     *
     * @param int $id            
     */
    public function delete(int $id): Response
    {
        // input parameters validation
        $this->validateData('int', $id, [
            'message' => "Id must be an integer."
        ]);
        
        $client = ClientModel::findFirst($id);
        
        if (empty($client)) {
            throw new ApiException("Not found", 404);
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
        $this->validateData('string', $body['lastname'], [
            'min' => 2,
            'max' => 50,
            'messageMinimum' => "Client's lastname must be a string between 2 and 50 characters",
            'messageMaximum' => "Client's lastname must be a string between 2 and 50 characters",
            'includedMinimum' => false,
            'includedMaximum' => false
        ]);
        $this->validateData('string', $body['firstname'], [
            'min' => 2,
            'max' => 50,
            'messageMinimum' => "Client's firstname must be a string between 2 and 50 characters",
            'messageMaximum' => "Client's firstname must be a string between 2 and 50 characters",
            'includedMinimum' => false,
            'includedMaximum' => false
        ]);
        $this->validateData('string', $body['address'], [
            'min' => 2,
            'max' => 255,
            'messageMinimum' => "Client's address must be a string between 2 and 255 characters",
            'messageMaximum' => "Client's address must be a string between 2 and 255 characters",
            'includedMinimum' => false,
            'includedMaximum' => false
        ]);
        $this->validateData('string', $body['country'], [
            'min' => 2,
            'max' => 50,
            'messageMinimum' => "Client's country must be a string between 2 and 50 characters",
            'messageMaximum' => "Client's country must be a string between 2 and 50 characters",
            'includedMinimum' => false,
            'includedMaximum' => false
        ]);
    }

    /**
     * Auto set reference
     *
     * @param \Phalcon\Mvc\Model $client            
     */
    protected function setReference(\Phalcon\Mvc\Model $client)
    {
        $client->reference = sprintf("CLI_%d", random_int(0, 999999999));
    }
}

