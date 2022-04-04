<?php
declare(strict_types=1);

use Exception\ApiException;
use ERP\Company;

class CompanyProductController extends AbstractController
{
    /**
     * Get Company product list
     *
     * @param int $id
     * @return \Phalcon\Http\Response
     */
    public function get(int $id)
    {
        $company=$this->getCompany($id);
        $result=$company->getRelated('product');
        return $this->output(200, $result->toArray());
    }
    
    
    public function buyProducts(int $id)
    {
        $company=$this->getCompany($id);
        
        $body = $this->getBody([
            'employee_reference',
            'provider_reference'
        ]);
        
        $this->validateMandatoryInput($body);
        
        $provider=$this->getProvider($id);
        $employee=$this->getEmployee($id);
        
        //business logical
        
        
    }
    
    
    public function sellProducts(int $id)
    {
        $companyModel=$this->getCompany($id);
        $body = $this->getBody([
            'employee_reference',
            'client_reference'
        ]);
        
        $this->validateMandatoryInput($body);
        
        $company=new Company($companyModel->reference, $companyModel->balance);
        var_dump($company);
    }
    
    
    /**
     * Get company model
     * @param int $id
     * @throws ApiException
     * @return Company|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|NULL
     */
    protected function getCompany(int $id){
        $company = CompanyModel::findFirst($id);
        
        if(empty($company)){
            throw new ApiException("Company not found",404);
        }
        
        return $company;
    }
    
    /**
     * Get provider model
     * @param int $id
     * @throws ApiException
     * @return Provider|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|NULL
     */
    protected function getProvider(int $id){
        $provider = ProviderModel::findFirst($id);
    
        if(empty($provider)){
            throw new ApiException("Provider not found",404);
        }
    
        return $provider;
    }
    
    /**
     * Get client model
     * @param int $id
     * @throws ApiException
     * @return Client|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|NULL
     */
    protected function getClient(int $id){
        $client = ClientModel::findFirst($id);
    
        if(empty($client)){
            throw new ApiException("Client not found",404);
        }
    
        return $client;
    }
    
    /**
     * Get employee model
     * @param int $id
     * @throws ApiException
     * @return Client|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|NULL
     */
    protected function getEmployee(int $id){
        $employee = EmployeeModel::findFirst($id);
    
        if(empty($employee)){
            throw new ApiException("Employee not found",404);
        }
    
        return $employee;
    }
    
    
    /**
     * Validate input
     *
     * @param array $body
     */
    protected function validateMandatoryInput(array $body)
    {
        // validate input field values
        $this->validateData('string', $body['employee_reference'], [
            'min' => 2,
            'max' => 100,
            'messageMinimum' => "Employee reference must be a string between 2 and 100 characters",
            'messageMaximum' => "Employee reference must be a string between 2 and 100 characters",
            'includedMinimum' => false,
            'includedMaximum' => false
        ]);
        
        if(array_key_exists('provider_reference', $body)){
            $this->validateData('string', $body['provider_reference'], [
                'min' => 2,
                'max' => 100,
                'messageMinimum' => "Provider reference must be a string between 2 and 100 characters",
                'messageMaximum' => "Provider reference must be a string between 2 and 100 characters",
                'includedMinimum' => false,
                'includedMaximum' => false
            ]);
        }
        
        if(array_key_exists('client_reference', $body)){
            $this->validateData('string', $body['client_reference'], [
                'min' => 2,
                'max' => 100,
                'messageMinimum' => "Client reference must be a string between 2 and 100 characters",
                'messageMaximum' => "Client reference must be a string between 2 and 100 characters",
                'includedMinimum' => false,
                'includedMaximum' => false
            ]);
        }
    }
}

