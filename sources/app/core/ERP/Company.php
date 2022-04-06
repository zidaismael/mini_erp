<?php
declare(strict_types = 1);

namespace ERP;

use \ERP\ERPInterface\BuyerInterface;
use \ERP\ERPInterface\SellerInterface;
use \ERP\Factory\ProductFactory;
use \ERP\Transaction;
use \ERP\Provider;
use \ERP\Product;
use \ERP\Employee;
use \Exception\TransactionException;
use TransactionModel;

class Company implements BuyerInterface, SellerInterface
{
   
    /**
     * @var string $reference
     */
    protected string $reference;
    
    /**
     * @var array $employeeList
     */
    protected array $employeeList=[];
    
    /**
     * @var array $availableProductList
     */
    protected array $availableProductList=[];
    
    /**
     * @var array $boughtProductList
     */
    protected array $boughtProductList=[];
    
    /**
     * @var float $balance
     */
    protected float $balance;
    
    /**
     * Constructor
     * @param string $reference
     * @param float $balance
     * @return \ERP\Client
     */
    public function __construct(string $reference, float $balance){
        $this->reference=$reference;
        $this->balance=$balance;
    }
    
    public function sellProducts(BuyerInterface $buyer, array $products): ?Transaction{
        
    }
        
    /**
     * 
     */
    public function buyProducts(SellerInterface $provider, Employee $employee, array $orderedProducts): ?Transaction{

        $amount=0;
        foreach($orderedProducts as $order){
           
            $product=$provider->getAvailableProductList()[$order['reference']];
            
            //check stocks
            if(!$provider->hasEnougthStock($order['reference'], $order['quantity'])){
                throw new TransactionException(sprintf("Not enougth product %s quantity %d to proceed supply.", $product->getReference(), $product->getStock()));
            }

            $amount+= $product->getPrice()*$order['quantity'];
        }
        
        //check money
        if(!$this->hasEnougthMoney($amount)){
            throw new TransactionException("Company doesn't have enought money to proceed supply.");
        }
        
        //update stocks
        foreach($orderedProducts as $order){ 
            
            //decrease provider ones
            $providerProduct=$provider->getProduct($order['reference']);
            if(!is_null($providerProduct->getStock())){ 
                $providerProduct->setStock($providerProduct->getStock()-$order['quantity']);
            }
            
            $provider->setProduct($providerProduct);
            
            //increase company ones
            if($this->hasProduct($order['reference'], true)){
                //retrieve existing product
                $product=array_filter($this->availableProductList,function($entry) use ($order){ return $entry->getExternalReference() == $order['reference']; });
                $product=array_pop($product);
  
                //update available product
                $product->setStock(($product->getStock()+$order['quantity']));
                $this->availableProductList[$product->getReference()]=$product;

            }else{//add new one
                $product=ProductFactory::build(['reference'=> null, 'external_reference' => $order['reference'], 'name' => $providerProduct->getName(), 'stock' => $order['quantity']]);
                $this->availableProductList[]=$product;
            }  
            
            $this->addBoughtProduct($provider, $this, $order);
        }
        
        //decrease money
        $this->balance-=$amount;
        
        //database records
        $transaction=new Transaction($employee, $provider, $this);
        $transactionModel=new TransactionModel();
        $transactionModel->recordSupply($transaction);
        return $transaction;
    }
    
    /**
     * Populate bought product list
     */
    public function addBoughtProduct(SellerInterface $provider, BuyerInterface $company, array $orderedProduct){

        //constuct order product according to company product 
        $product=$company->getProduct($orderedProduct['reference'], true);
        
        $boughtProduct= clone $product;
        
        if(empty($boughtProduct)){//create new one
            $boughtProduct=ProductFactory::build(['reference'=> null, 'external_reference' => $orderedProduct['reference'], 'name' => $providerProduct->getName(), 'quantity' => $orderedProduct['quantity']]);
        }
        
        $providerProduct=$provider->getProduct($orderedProduct['reference']);
        
        //update with order values
        $boughtProduct->setPrice($providerProduct->getPrice())
            ->setTax($providerProduct->getTax())
            ->setExternalReference($orderedProduct['reference'])
            ->setOrderQuantity($orderedProduct['quantity'])
            //in this case stock info isn't appropriate
            ->setStock(null);
        
        $this->boughtProductList[]=$boughtProduct;
    }
    
    /**
     * Has company enougth money
     * @param float $total
     * @return bool
     */
    public function hasEnougthMoney(float $total): bool{
        return $this->balance >= $total;
    }
    
    /**
     * Has company enougth product stock
     * @param string $productReference
     * @param int $requiredQuantity
     * @return bool
     */
    public function hasEnougthStock(string $productReference, int $requiredQuantity): bool{
        return $product->getStock() >= $requiredQuantity;
    }

    /**
     * Has company got product yet
     * @param string $productReference
     * @param bool $useExternalReference (default false)
     * @return bool
     */
    public function hasProduct(string $productReference, bool $useExternalReference = false): bool{
       return !empty($this->getProduct($productReference, $useExternalReference));
    }
    
    /**
     * Has company got products
     * @return bool
     */
    public function hasProducts():bool{
        return !empty($this->availableProductList);
    }
    
    /**
     * Get product
     * @param string $productReference
     * @param bool $useExternalReference (default false)
     * @return bool
     */
    public function getProduct(string $productReference, bool $useExternalReference = false): ?Product{
        if(!$useExternalReference){
            return $this->availableProductList[$productReference];
        }else{
            $productList=array_filter($this->availableProductList,function($entry) use ($productReference){ return $entry->getExternalReference() == $productReference; });
            return !empty($productList) ? array_pop($productList) : null;
        }
    }
    
    public function setEmployeeList(array $employees){
        $this->employeeList=$employees;
        return $this;
    }
    
    public function setReference(string $reference){
        $this->reference=$reference;
        return $this;
    }
    
    public function setAvailableProductList(array $products){
        foreach($products as $product){
            $this->availableProductList[$product->getReference()]=$product;
        }
        
        return $this;
    }
    
    public function getBoughtProductList() : array{
        return $this->boughtProductList;
    }
    
    public function getAvailableProductList() : array{
        return $this->availableProductList;
    }
    
    public function getReference(){
        return $this->reference;
    }
}

