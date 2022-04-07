<?php
declare(strict_types = 1);

namespace ERP;

use \ERP\ERPInterface\BuyerInterface;
use \ERP\ERPInterface\SellerInterface;
use \ERP\Factory\ProductFactory;
use \ERP\Transaction;
use \ERP\Product;

class Client implements BuyerInterface
{
    /**
     * @var string $reference
     */
    protected string $reference;

    /**
     * @var array $boughtProductList
     */
    protected array $boughtProductList=[];
    
    /**
     * Constructor
     * @param string $reference
     */
    public function __construct(string $reference){
        $this->reference=$reference;
    }
    
    /**
     * @return string 
     */
    public function getReference(){
        return $this->reference;
    }
    
    /**
     * Set transaction collection
     * @param array $transactionList
     */
    public function setTransactionList(array $transactionList){
        $this->transactionList=$transactionList;
    }
    
    public function buyProducts(SellerInterface $seller, Employee $employee, array $orderedProducts): ?Transaction{
        return null;
    }
    
    public function hasEnougthMoney(float $total): bool{
        return true;
    }
    
    /**
     * Get product
     * @param string $productReference
     * @param bool $useExternalReference (default false)
     * @return bool
     */
    public function getProduct(string $productReference): ?Product{
        $productList=array_filter($this->boughtProductList,function($entry) use ($productReference){ return $entry->getReference() == $productReference; });
        return !empty($productList) ? array_pop($productList) : null;
    }
    
    public function getBoughtProductList() : array{
        return $this->boughtProductList;
    }
    
    /**
     * Populate bought product list
     * @param \ERP\Product 
     *//*
    public function addBoughtProduct(Product $product, array $orderedProduct){
        //constuct order product according to company product 
        $boughtProduct= clone $product;

        //update with order values
        $boughtProduct->setExternalReference($orderedProduct['reference'])
            ->setOrderQuantity($orderedProduct['quantity']);
        
        //in this case stock info isn't appropriate
        $boughtProduct->setStock(null);
        
        $this->boughtProductList[]=$boughtProduct;
    }*/
    
    /**
     * Populate bought product list
     */
    public function addBoughtProduct(SellerInterface $company, BuyerInterface $client, array $orderedProduct){
    
        //constuct order product
        $companyProduct=$company->getProduct($orderedProduct['reference']);
        $boughtProduct=ProductFactory::build(['reference'=> null, 'external_reference' => $orderedProduct['reference'], 'name' => $companyProduct->getName(), 'quantity' => $orderedProduct['quantity']]);
    
        //update with order values
        $boughtProduct->setPrice($companyProduct->getPrice())
        ->setTax($companyProduct->getTax())
        ->setExternalReference($orderedProduct['reference'])
        ->setOrderQuantity($orderedProduct['quantity'])
        //in this case stock info isn't appropriate
        ->setStock(null);
    
        $this->boughtProductList[]=$boughtProduct;
    }
}

