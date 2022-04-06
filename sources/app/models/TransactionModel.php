<?php
use \ERP\Transaction;
use ProductModel;
use CompanyModel;
use EmployeeModel;
use Exception\CoreException;
use Phalcon\Mvc\Model\Transaction\Failed;

class TransactionModel extends AbstractModel
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $date;

    /**
     *
     * @var string
     */
    public $reference;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $employee_id;

    /**
     *
     * @var integer
     */
    public $client_id;

    /**
     *
     * @var integer
     */
    public $product_quantity;

    /**
     *
     * @var double
     */
    public $product_price;

    /**
     *
     * @var double
     */
    public $product_tax;
    
    /**
     * 
     * @var unknown
     */
    protected $transactionModel;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("mini_erp");
        $this->setSource("transaction");
        $this->hasMany('id', 'RelTransactionProduct', 'id_transaction', ['alias' => 'RelTransactionProduct']);
        $this->belongsTo('client_id', 'ClientModel', 'id', ['alias' => 'Client']);
        $this->belongsTo('employee_id', 'EmployeeModel', 'id', ['alias' => 'Employee']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Transaction[]|Transaction|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Transaction|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
    return parent::findFirst($parameters);
}
    
    /**
     * Get product by reference
     * @param string $reference
     * @return Product|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function getByReference(string $reference): ?\Phalcon\Mvc\ModelInterface{
        return parent::findFirst([
            'conditions' => 'reference = :reference:',
            'bind' => ['reference'=> $reference]
        ]);
    }
    
    /**
     * Save supply transaction
     * @param \ERP\Transaction $transaction
     * @return bool
     */
    public function recordSupply(Transaction $transaction): bool{ 
        try{
            $databaseTransaction=$this->getTransaction();
            $this->setTransaction($databaseTransaction);
            
            //create transaction
            $this->date=$transaction->getDate()->format('Y-m-d H:i:s');
            $this->type=$transaction->getType();
            $this->reference=$transaction->getReference();
            
            $employeeModel=EmployeeModel::getByReference($transaction->getResponsible()->getReference());
            $this->employee_id=$employeeModel->id;
           
            if($this->save()===false){
                $databaseTransaction->rollback("Can't save transaction. Aborting save...");
            }
            
            //update provider stock
            $providerProductList=$transaction->getSeller()->getAvailableProductList();
            foreach($providerProductList as $product){
           
                $productModel=ProductModel::getByReference($product->getReference());
                $productModel->setTransaction($databaseTransaction);
                
                $productModel->stock=$product->getStock();
                if($productModel->save()===false){
                    $databaseTransaction->rollback("Can't update provider product. Aborting save...");
                }
            }
            
            //create or update company products
            $companyProductId=[];
            $companyProductList=$transaction->getBuyer()->getAvailableProductList();
            foreach($companyProductList as $product){
                if(is_null($product->getReference())){
                    $productModel=new ProductModel();
                    $productModel->reference=$product->generateReference();
                    $productModel->external_reference=$product->getExternalReference();
                    $productModel->name=$product->getName();
                    
                    $company=CompanyModel::getByReference($transaction->getBuyer()->getReference());
                    $productModel->company_id=$company->id;
                }else{
                    $productModel=ProductModel::getByReference($product->getReference());
                }
    
                $productModel->setTransaction($databaseTransaction);
                
                $productModel->stock=$product->getStock();
                if($productModel->save()===false){
                    $databaseTransaction->rollback("Can't update provider product. Aborting save...");
                }else{
                    $registeredProduct=$productModel->findFirst($productModel->id);
                    $companyProductId[$registeredProduct->reference]=(int) $productModel->id;
                }
            }
            
            //create transaction products relations
            $productList=$transaction->getProductList();
            foreach($productList as $product){
                $relTransactionProduct=new RelTransactionProduct();
                $relTransactionProduct->setTransaction($databaseTransaction);
            
                $relTransactionProduct->transaction_id=$this->id;
            
                $relTransactionProduct->product_id=$companyProductId[$product->getReference()];
                $relTransactionProduct->product_quantity=$product->getOrderQuantity();
               
                $relTransactionProduct->product_price=$product->getPrice();
                $relTransactionProduct->product_tax=$product->getTax();
              
                if($relTransactionProduct->save()===false){
                    $databaseTransaction->rollback("Can't save transaction's relation. Aborting save...");
                }
            }
            
            //update company balance
            $companyModel=CompanyModel::getByReference($transaction->getBuyer()->getReference());
            $companyModel->setTransaction($databaseTransaction);
            $companyModel->balance-=$transaction->getAmount();

            if($companyModel->save()===false){
                $databaseTransaction->rollback("Can't update company balance. Aborting save...");
            }
            
            $databaseTransaction->commit();
            
            return true;
        }catch (Failed $e){
            throw new CoreException($e->getMessage());
        }
        
        return false;
    }

}
