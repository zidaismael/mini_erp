<?php
declare(strict_types = 1);

namespace ERP;

use \ERP\ERPInterface\SellerInterface;
use \ERP\Transaction;
use \ERP\Product;
use \Exception\CoreException;

class Provider implements SellerInterface
{
    
    /**
     * @var string $reference
     */
    protected string $reference;
    
    /**
     * @var array $availableProductList
     */
    protected array $availableProductList=[];
    
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
     * Has provider got products
     * @return bool
     */
    public function hasProducts():bool{
        return !empty($this->availableProductList); 
    }
    
    /**
     * Has provider enought stock
     * @param string $productReference
     * @param int $requiredQuantity
     * @return bool
     */
    public function hasEnoughtStock(string $productReference, int $requiredQuantity): bool{
        $product=array_filter($this->availableProductList, function($entry) use ($productReference){return $entry->getReference() == $productReference;});
        if(empty($product)){
            throw new CoreException(sprintf("No product reference available for provider %s, %s", $this->reference, $productReference));
        }
        
        $product=array_pop($product);
        
        if(is_null($product->getStock())){
            return true;
        }else{
            return $product->getStock() >= $requiredQuantity;
        }
    }
    
    /**
     * Set product in list
     * @param Product $product
     * @throw \Exception\CoreException
     */
    public function setProduct(Product $product){
        $productReference=$product->getReference();
        if(!array_key_exists($productReference,$this->availableProductList)){
            throw new CoreException(sprintf("Provider product not found %s",$productReference));
        }
        
        $this->availableProductList[$productReference]=$product;
    }

    public function setAvailableProductList(array $products){
        foreach($products as $product){
            $this->availableProductList[$product->getReference()]=$product;
        }
        return $this;
    }
    
    public function getAvailableProductList(): array{
        return $this->availableProductList;
    }
    
    public function getProduct(string $reference): ?Product{
        return $this->availableProductList[$reference];
    }
    
    public function setTransactionList(array $transactionList){
        $this->transactionList=$transactionList;
    }
    
    public function setReference(string $reference){
        $this->reference=$reference;
        return $this;
    }
    
}

