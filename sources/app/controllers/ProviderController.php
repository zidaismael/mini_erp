<?php
declare(strict_types=1);

use Logger\LogHelper;
use Exception\ApiException;
use Exception\DuplicateModelException;
use Phalcon\Http\Response;

class ProviderController extends AbstractController
{

    /**
     * Get Provider list or one provider
     *
     * @param int $id
     *            default null, if null => list else get one
     * @return \Phalcon\Http\Response
     */
    public function get($id = null): Response
    {
        if (is_null($id)) { // no id passed => list
            $result = ProviderModel::find();
            $provider = $result->toArray();
        } else { // id passed => get one
                 // input parameters validation
            $this->validateData('int', $id, [
                'message' => "Id must be an integer."
            ]);
            
            $provider = ProviderModel::findFirst($id);
        }

        if (! empty($provider)) { // result in DB
            return $this->output(200, $provider);
        } else { // no result
            if (is_null($id)) {
                return $this->output(200, []);
            } else {
                throw new ApiException("Not found",404);
            }
        }
    }

    /**
     * Create Provider
     *
     * @return \Phalcon\Http\Response
     */
    public function post(): Response
    {
        // get body and check mandatory input parameters validation
        $body = $this->getBody([
            'name',
            'address',
            'country'
        ]);
        
        $this->validateMandatoryInput($body);
        
        $provider = new ProviderModel();
        $provider->assign($body);
        $this->setReference($provider);
   
        try {
            if ($provider->create()) {
                $result = $provider->findFirst($provider->id);
                return $this->output(201, $result);
            } else {
                // @todo log
                throw new ApiException(sprintf("An error occured on provider creation: %s", json_encode($body)));
            }
        } catch (DuplicateModelException $e) {
            LogHelper::error($e->getMessage());
            throw new ApiException(sprintf("Unique information have been duplicate: %s \nbody: %s", $e->getSubject(), json_encode($body)), 409);
        } catch (\Exception $e) {
            LogHelper::error($e->getMessage());
            throw new ApiException(sprintf("An error occured on provider creation: %s", json_encode($body)));
        }
    }

    /**
     * Update Provider
     *
     * @param int $id            
     */
    public function put(int $id): Response
    {
        
        // get body and check mandatory input parameters validation
        $body = $this->getBody([
            'name',
            'address',
            'country'
        ]);
        
        // input parameters validation
        $this->validateData('int', $id, [
            'message' => "Id must be an integer."
        ]);
        
        $this->validateMandatoryInput($body);
        
        //check if exists
        $provider=ProviderModel::findFirst($id);
    
        if(empty($provider)){
            throw new ApiException("Not found",404);
        }

        $provider->assign($body);
        
        try {
            if ($provider->save()) {
                $provider = $provider->findFirst($provider->id);
                return $this->output(200, $provider);
            } else {
                // @todo log
                throw new ApiException(sprintf("An error occured on provider update: %s", json_encode($body)));
            }
        } catch (DuplicateModelException $e) {
            LogHelper::error($e->getMessage());
            throw new ApiException(sprintf("Unique information have been duplicate: %s \nbody: %s", $e->getSubject(), json_encode($body)), 409);
        } catch (\Exception $e) {
            LogHelper::error($e->getMessage());
            throw new ApiException(sprintf("An error occured on provider creation: %s", json_encode($body)));
        }
    }

    /**
     * Delete Provider
     *
     * @param int $id            
     */
    public function delete(int $id): Response
    {
        // input parameters validation
        $this->validateData('int', $id, [
            'message' => "Id must be an integer."
        ]);
        
        $provider = ProviderModel::findFirst($id);
        
        if (empty($provider)) {
            throw new ApiException("Not found",404);
        } else {
            $provider->delete();
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
        $this->validateData('string', $body['name'], [
            'min' => 2,
            'max' => 100,
            'messageMinimum' => "Provider's name must be a string between 2 and 100 characters",
            'messageMaximum' => "Provider's name must be a string between 2 and 100 characters",
            'includedMinimum' => false,
            'includedMaximum' => false
        ]);
        $this->validateData('string', $body['address'], [
            'min' => 2,
            'max' => 200,
            'messageMinimum' => "Provider's address must be a string between 2 and 200 characters",
            'messageMaximum' => "Provider's address must be a string between 2 and 200 characters",
            'includedMinimum' => false,
            'includedMaximum' => false
        ]);
        $this->validateData('string', $body['country'], [
            'min' => 2,
            'max' => 50,
            'messageMinimum' => "Provider's country must be a string between 2 and 50 characters",
            'messageMaximum' => "Provider's country must be a string between 2 and 50 characters",
            'includedMinimum' => false,
            'includedMaximum' => false
        ]);
    }

    /**
     * Auto set reference
     * 
     * @param \Phalcon\Mvc\Model $provider            
     */
    protected function setReference(\Phalcon\Mvc\Model $provider)
    {
        $provider->reference = sprintf("PRV_%d", random_int(0, 999999999));
    }
}

