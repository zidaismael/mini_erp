<?php
declare(strict_types = 1);

use Exception\ApiException;
use Exception\DuplicateModelException;

class CompanyController extends AbstractController
{

    /**
     * Get Company list or one company
     *
     * @param int $id
     *            default null, if null => list else get one
     * @return \Phalcon\Http\Response
     */
    public function get($id = null)
    {
        if (is_null($id)) { // no id passed => list
            $result = CompanyModel::find();
            $company = $result->toArray();
        } else { // id passed => get one
                 // input parameters validation
            $this->validateData('int', $id, [
                'message' => "Id must be an integer."
            ]);
            
            $company = CompanyModel::findFirst($id);
        }

        if (! empty($company)) { // result in DB
            return $this->output(200, $company);
        } else { // no result
            if (is_null($id)) {
                return $this->output(200, []);
            } else {
                throw new ApiException("Not found",404);
            }
        }
    }

    /**
     * Create Company
     *
     * @return \Phalcon\Http\Response
     */
    public function post()
    {
        // get body and check mandatory input parameters validation
        $body = $this->getBody([
            'name',
            'balance',
            'country'
        ]);
        
        $this->validateMandatoryInput($body);
        
        $company = new CompanyModel();
        $company->assign($body);
        $this->setReference($company);
        
        try {
            if ($company->create()) {
                $result = $company->findFirst($company->id);
                return $this->output(201, $result);
            } else {
                // @todo log
                throw new ApiException(sprintf("An error occured on company creation: %s", json_encode($body)));
            }
        } catch (DuplicateModelException $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("Unique information have been duplicate: %s \nbody: %s", $e->getSubject(), json_encode($body)), 409);
        } catch (\Exception $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("An error occured on company creation: %s", json_encode($body)));
        }
    }

    /**
     * Update Company
     *
     * @param int $id            
     */
    public function put(int $id)
    {
        
        // get body and check mandatory input parameters validation
        $body = $this->getBody([
            'name',
            'balance',
            'country'
        ]);
        
        // input parameters validation
        $this->validateData('int', $id, [
            'message' => "Id must be an integer."
        ]);
        
        $this->validateMandatoryInput($body);
        
        //check if exists
        $company=CompanyModel::findFirst($id);
    
        if(empty($company)){
            throw new ApiException("Not found",404);
        }

        $company->assign($body);
        
        try {
            if ($company->save()) {
                $company = $company->findFirst($company->id);
                return $this->output(200, $company);
            } else {
                // @todo log
                throw new ApiException(sprintf("An error occured on company update: %s", json_encode($body)));
            }
        } catch (DuplicateModelException $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("Unique information have been duplicate: %s \nbody: %s", $e->getSubject(), json_encode($body)), 409);
        } catch (\Exception $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("An error occured on company creation: %s", json_encode($body)));
        }
    }

    /**
     * Delete Company
     *
     * @param int $id            
     */
    public function delete(int $id)
    {
        // input parameters validation
        $this->validateData('int', $id, [
            'message' => "Id must be an integer."
        ]);
        
        $company = CompanyModel::findFirst($id);
        
        if (empty($company)) {
            throw new ApiException("Not found",404);
        } else {
            $company->delete();
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
            'messageMinimum' => "Company's name must be a string between 2 and 100 characters",
            'messageMaximum' => "Company's name must be a string between 2 and 100 characters",
            'includedMinimum' => false,
            'includedMaximum' => false
        ]);
        $this->validateData('numeric', $body['balance'], [
            'message' => "Balance must be numerical"
        ]);
        $this->validateData('string', $body['country'], [
            'min' => 2,
            'max' => 50,
            'messageMinimum' => "Company's country must be a string between 2 and 50 characters",
            'messageMaximum' => "Company's country must be a string between 2 and 50 characters",
            'includedMinimum' => false,
            'includedMaximum' => false
        ]);
    }

    /**
     * Auto set reference
     * 
     * @param \Phalcon\Mvc\Model $company            
     */
    protected function setReference(\Phalcon\Mvc\Model $company)
    {
        $company->reference = sprintf("CPY_%d", random_int(0, 999999999));
    }
}

