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
            $result = Company::find();
        } else { // id passed => get one
                 // input parameters validation
            $this->validateData('int', $id, [
                'message' => "Id must be an integer."
            ]);
            
            $result = Company::find($id);
        }
        
        $company = $result->toArray();
        
        if (! empty($company)) { // result in DB
            $company = is_null($id) ? $company : $company[0];
            return $this->output(200, $company);
        } else { // no result
            if (is_null($id)) {
                return $this->output(200, []);
            } else {
                return $this->output(404);
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
        die('uyguyyg');
        // get body and check mandatory input parameters validation
        $body = $this->getBody([
            'name',
            'balance',
            'country'
        ]);
        
        $this->validateMandatoryInput($body);
        
        $company = new Company();
        $company->assign($body);
        $this->setReference($company);
        
        try {
            if ($company->create()) {
                $result = $company->find($company->id);
                return $this->output(201, $result->toArray()[0]);
            } else {
                // @todo log
                throw new ApiException(sprintf("An error occured on company creation: %s", json_encode($body)), 503);
            }
        } catch (DuplicateModelException $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("Unique information have been duplicate: %s \nbody: %s", $e->getSubject(), json_encode($body)), 409);
        } catch (\Exception $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("An error occured on company creation: %s", json_encode($body)), 503);
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
        
        $company = new Company();
        $company->id = $id;
        $company->assign($body);
        
        try {
            if ($company->save()) {
                $result = $company->find($company->id);
                return $this->output(200, $result->toArray()[0]);
            } else {
                // @todo log
                throw new ApiException(sprintf("An error occured on company update: %s", json_encode($body)), 503);
            }
        } catch (DuplicateModelException $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("Unique information have been duplicate: %s \nbody: %s", $e->getSubject(), json_encode($body)), 409);
        } catch (\Exception $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("An error occured on company creation: %s", json_encode($body)), 503);
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
        
        $company = Company::find($id);
        
        if (empty($company->toArray())) {
            return $this->output(404);
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
            'messageMaximum' => "Company's lastname must be a string between 2 and 100 characters",
            'includedMinimum' => false,
            'includedMaximum' => false
        ]);
        $this->validateData('numeric', $body['balance'], [
            'message' => "Balance must be numerical"
        ]);
        $this->validateData('string', $body['country'], [
            'min' => 2,
            'max' => 50,
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
        $company->reference = sprintf("CPY%.10d", random_int(0, 999999999));
    }
}

