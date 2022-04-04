<?php
declare(strict_types=1);

use Exception\ApiException;
use Exception\DuplicateModelException;
use Exception\ForeignKeyModelException;

class EmployeeController extends AbstractController
{

    /**
     * Get Employee list or one employee
     *
     * @param int $id
     *            default null, if null => list else get one
     * @return \Phalcon\Http\Response
     */
    public function get($id = null)
    {
        if (is_null($id)) { // no id passed => list
            $result = Employee::find();
            $employee = $result->toArray();
        } else { // id passed => get one
                 // input parameters validation
            $this->validateData('int', $id, [
                'message' => "Id must be an integer."
            ]);
            
            $employee = Employee::findFirst($id);
        }

        if (! empty($employee)) { // result in DB
            return $this->output(200, $employee);
        } else { // no result
            if (is_null($id)) {
                return $this->output(200, []);
            } else {
                throw new ApiException("Not found",404);
            }
        }
    }

    /**
     * Create Employee
     *
     * @return \Phalcon\Http\Response
     */
    public function post()
    {
        // get body and check mandatory input parameters validation
        $body = $this->getBody([
            'lastname',
            'firstname',
            'country',
            'contract_date',
            'company_id'
        ]);
        
        $this->validateMandatoryInput($body);
        
        $employee = new Employee();
        $employee->assign($body);
        $this->setReference($employee);
        
        try {
            if ($employee->create()) {
                $result = $employee->findFirst($employee->id);
                return $this->output(201, $result);
            } else {
                // @todo log
                throw new ApiException(sprintf("An error occured on employee creation: %s", json_encode($body)));
            }
        }catch (ForeignKeyModelException $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("One or more object's relations can't be create. Please check your object values.",json_encode($body)), 400);
        } catch (DuplicateModelException $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("Unique information have been duplicate: %s \nbody: %s", $e->getSubject(), json_encode($body)), 409);
        } catch (\Exception $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("An error occured on employee creation: %s", json_encode($body)));
        }
    }

    /**
     * Update Employee
     *
     * @param int $id            
     */
    public function put(int $id)
    {
        
        // get body and check mandatory input parameters validation
        $body = $this->getBody([
            'lastname',
            'firstname',
            'country',
            'contract_date',
            'company_id'
        ]);
        
        // input parameters validation
        $this->validateData('int', $id, [
            'message' => "Id must be an integer."
        ]);
        
        $this->validateMandatoryInput($body);
        
        //check if exists
        $employee=Employee::findFirst($id);
    
        if(empty($employee)){
            throw new ApiException("Not found",404);
        }

        $employee->assign($body);
        
        try {
            if ($employee->save()) {
                $employee = $employee->findFirst($employee->id);
                return $this->output(200, $employee);
            } else {
                // @todo log
                throw new ApiException(sprintf("An error occured on employee update: %s", json_encode($body)));
            }
        }catch (ForeignKeyeModelException $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("Unique information have been duplicate: %s \nbody: %s", $e->getSubject(), json_encode($body)), 400);
        } catch (DuplicateModelException $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("Unique information have been duplicate: %s \nbody: %s", $e->getSubject(), json_encode($body)), 409);
        } catch (\Exception $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("An error occured on employee creation: %s", json_encode($body)));
        }
    }

    /**
     * Delete Employee
     *
     * @param int $id            
     */
    public function delete(int $id)
    {
        // input parameters validation
        $this->validateData('int', $id, [
            'message' => "Id must be an integer."
        ]);
        
        $employee = Employee::findFirst($id);
        
        if (empty($employee)) {
            throw new ApiException("Not found",404);
        } else {
            $employee->delete();
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
            'messageMaximum' => "Employee's lastname must be a string between 2 and 50 characters",
            'includedMinimum' => false,
            'includedMaximum' => false
        ]);

        $this->validateData('string', $body['firstname'], [
            'min' => 2,
            'max' => 50,
            'messageMaximum' => "Employee's firstname must be a string between 2 and 50 characters",
            'includedMinimum' => false,
            'includedMaximum' => false
        ]);
        
        $this->validateData('date', $body['contract_date'], [
            "format"  => "Y-m-d",
            'message' => "Invalid contract date",
        ]);
        $this->validateData('string', $body['country'], [
            'min' => 2,
            'max' => 50,
            'messageMaximum' => "Employee's country must be a string between 2 and 50 characters",
            'includedMinimum' => false,
            'includedMaximum' => false
        ]);
        
        $this->validateData('int', $body['company_id'], [
            'message' => "Invalid company id",
        ]);
        
        if(array_key_exists('birthday',$body)){
            $this->validateData('date', $body['birthday'], [
                "format"  => "Y-m-d",
                'message' => "Invalid birthday date"
            ]);
        }
            
    }

    /**
     * Auto set reference
     * 
     * @param \Phalcon\Mvc\Model $employee            
     */
    protected function setReference(\Phalcon\Mvc\Model $employee)
    {
        $employee->reference = sprintf("EMP_%d", random_int(0, 999999999));
    }

}

