<?php

declare(strict_types = 1);

namespace ERP;

use ERPInterface\BuyerInterface;
use \Transaction;
use \Product;

class Client implements BuyerInterface
{
    /**
     * @var string $reference
     */
    protected string $reference;

    /**
     * @var array $order 
     */
    protected array $order = [];
    
    
    /**
     * @var array $transactionList
     */
    protected array $transactionList = [];
    
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
     * Add product to client order
     * @param SellerInterface $seller
     * @param Product $product
     * @param int $quantity
     */
    public function buyProduct(SellerInterface $seller, Product $product, int $quantity){
       $productReference=$product->getReference(); 
        
       if(!array_key_exists($productReference,$this->order)){
            $this->order[$productReference]= ['product' => $product, 'quantity' => $quantity];
       }else{
           $this->order[$productReference]['quantity']+=$quantity;
       }
    }
    
    /**
     * Set transaction collection
     * @param array $transactionList
     */
    public function setTransactionList(array $transactionList){
        $this->transactionList=$transactionList;
    }
}

