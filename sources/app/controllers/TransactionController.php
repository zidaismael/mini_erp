<?php
declare(strict_types=1);

use Exception\ApiException;

class TransactionController extends AbstractController
{
    /**
     * Get transaction
     *
     * @param int $id
     * @return \Phalcon\Http\Response
     */
    public function get(int $id)
    {
        $result = TransactionModel::findFirst($id);
    
        if(empty($result)){
            throw new ApiException("Not found",404);
        }else{
            return $this->output(200, $result->toArray());
        }
    }
    
    
    /**
     * Get Employee transaction list
     *
     * @param int $id
     * @return \Phalcon\Http\Response
     */
    public function getByEmployee(int $id)
    {
        $employee = EmployeeModel::findFirst($id);
    
        if(empty($employee)){
            throw new ApiException("Not found",404);
        }else{
            $result=$employee->getRelated('transaction');
            return $this->output(200, $result->toArray());
        }
    }
    
    /**
     * Get Company transaction list
     *
     * @param int $id
     * @return \Phalcon\Http\Response
     */
    public function getByCompany(int $id)
    {
        $company = CompanyModel::findFirst($id);
    
        if(empty($company)){
            throw new ApiException("Not found",404);
        }else{
            $employees=$company->getRelated('Employee');
            if(empty($employees)){
                return $this->output(200,[]);
            }else{
                $transactions=[];
                
                foreach($employees as $employee){
                    $transactions=TransactionModel::find([
                        'conditions' => 'employee_id = :id:',
                        'bind' => ['id' => $employee->id]
                    ]);
                }
            
                return $this->output(200, $transactions->toArray());
            }
        }
    }
    
    /**
     * Get Provider transaction list
     *
     * @param int $id
     * @return \Phalcon\Http\Response
     */
    public function getByProvider(int $id)
    {
        $provider = ProviderModel::findFirst($id);
    
        if(empty($provider)){
            throw new ApiException("Not found",404);
        }else{
            $result=$provider->getRelated('transaction');
            return $this->output(200, $result->toArray());
        }
    }
    
    /**
     * Get Client transaction list
     *
     * @param int $id
     * @return \Phalcon\Http\Response
     */
    public function getByClient(int $id)
    {
        $client = ClientModel::findFirst($id);
    
        if(empty($client)){
            throw new ApiException("Not found",404);
        }else{
            $result=$client->getRelated('transaction');
            return $this->output(200, $result->toArray());
        }
    }
}

