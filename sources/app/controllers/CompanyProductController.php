<?php
declare(strict_types=1);

use Exception\ApiException;
use ERP\Provider;
use ERP\Company;
use ERP\Employee;
use ERP\Client;
use ERP\Factory\ProviderFactory;
use ERP\Factory\CompanyFactory;
use Exception\CoreException;
use Exception\TransactionException;
use TransactionModel;
use EmployeeModel;
use Phalcon\Http\Response;

class CompanyProductController extends AbstractController
{
    /**
     * Get Company product list
     *
     * @param int $id
     * @return \Phalcon\Http\Response
     */
    public function get(int $id): Response
    {
        $company=$this->getCompany($id);
        $result=$company->getRelated('product');
        return $this->output(200, $result->toArray());
    }
    
    
    public function buyProducts(int $id): Response
    {
        //checks
        $body = $this->getBody([
            'employee_reference',
            'provider_reference'
        ]);
        
        $this->validateMandatoryInput($body);
        
        $companyModel=$this->getCompany($id);
        $employeeModel=EmployeeModel::getByReference($body['employee_reference']);
        if($employeeModel->company->id != $id){
            throw new ApiException("Employee is not given company one's.", 400);
        }
        
        //get provider products states
        $providerModel=$this->getProvider($body['provider_reference']);
        $productReferenceList=array_map(function($entry){return $entry['reference'];}, $body['products']);
        
        //build provider ERP object
        $provider=ProviderFactory::build($providerModel,$productReferenceList);
        if(!$provider->hasProducts()){
            throw new ApiException("Invalid or missing provider products in list",400);
        }
       
        //build company ERP object
        $company=CompanyFactory::build($companyModel, $productReferenceList, true);
        $employee=new Employee($body['employee_reference']);
        
        try{
            //buy products and update database
            $transaction=$company->buyProducts($provider, $employee, $body['products']);
        }catch(TransactionException $e){
            throw new ApiException($e->getMessage(), 403);
        }

        $transactionModel=TransactionModel::getByReference($transaction->getReference());
        return $this->output(200, $transactionModel); 
    }
    
    
    public function sellProducts(int $id): Response
    {
        //checks
        $body = $this->getBody([
            'employee_reference',
            'client_reference'
        ]);
        
        $this->validateMandatoryInput($body);
        
        $companyModel=$this->getCompany($id);
        $employeeModel=EmployeeModel::getByReference($body['employee_reference']);
        if($employeeModel->company->id != $id){
            throw new ApiException("Employee is not given company one's.", 400);
        }
        
        $clientModel=ClientModel::getByReference($body['client_reference']);
        if(is_null($clientModel)){
            throw new ApiException("Client not found", 404);
        }
        
        $productReferenceList=array_map(function($entry){return $entry['reference'];}, $body['products']);
        $company=CompanyFactory::build($companyModel,$productReferenceList);
        
        if(!$company->hasProducts()){
            throw new ApiException("Invalid or missing provider products in list",400);
        }
        
        //build company ERP object
        $employee=new Employee($body['employee_reference']);
        $client=new Client($body['client_reference']);
  
        try{
            //sell products and update database
            $transaction=$company->sellProducts($client, $employee, $body['products']);
        }catch(TransactionException $e){
            throw new ApiException($e->getMessage(), 403);
        }

        $transactionModel=TransactionModel::getByReference($transaction->getReference());
        return $this->output(200, $transactionModel); 
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
     * @param string $reference
     * @throws ApiException
     * @return Provider|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|NULL
     */
    protected function getProvider(string $reference){
        $provider = ProviderModel::findFirst(['conditions' => 'reference = :reference:' , 'bind' => ['reference'=> $reference]]);
    
        if(empty($provider)){
            throw new ApiException("Provider not found",404);
        }
    
        return $provider;
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

