<?php
declare(strict_types=1);

use RelTransactionProductModel;
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
        $transactionModel = TransactionModel::findFirst($id);
    
        if(empty($transactionModel)){
            throw new ApiException("Not found",404);
        }else{
            $products=$this->getRelatedProducts($transactionModel);
            $transaction=$transactionModel->toArray();
            $transaction['products']=$products;
            
            return $this->output(200, $transaction);
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
    
    /**
     * Get related products
     * @param TransactionModel $transactionModel
     * @return array
     */
    protected function getRelatedProducts(TransactionModel $transactionModel): array{
        $relProductList=$transactionModel->getRelated('RelTransactionProduct');
        $relation=$relProductList->toArray();
        
        if(empty($relProductList)){
            return [];
        }else{
            $productIds=array_map(function($entry){return $entry['product_id'];}, $relation);
            $productModelList = ProductModel::find(['conditions' => "id IN ({id:array})", 'bind' => ['id'=> $productIds]]);

            //replace price, tax, quantity and unset stock
            $products=$productModelList->toArray();
            foreach($products as &$product){
                $transactionInfo=array_filter($relation, function($entry) use (&$product){return $entry['product_id']===$product['id'];});
                if(!empty($transactionInfo)){
                    $transactionInfo=array_pop($transactionInfo);
                    $product['quantity']=$transactionInfo['product_quantity'];
                    $product['price']=$transactionInfo['product_price'];
                    $product['tax']=$transactionInfo['product_tax'];
                    unset($product['stock']);
                }
            }
            
            return $products;
        }
    }
}

