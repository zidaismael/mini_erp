<?php
declare(strict_types = 1);

namespace ERP;

use \ERP\ERPInterface\SellerInterface;
use \ERP\ERPInterface\BuyerInterface;
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
     * Has company enougth money
     * @param float $total
     * @return bool
     */
    public function hasEnougthStock(string $productReference, int $requiredQuantity): bool{
        $product=array_filter($this->availableProductList, function($entry) use ($productReference){return $entry->getReference() == $productReference;});
        if(empty($product)){
            throw new CoreException(sprintf("No product reference available for provider %s, %s", $this->reference, $productReference));
        }
        
        $product=array_pop($product);
        
        if($product->getStock()===-1){
            return true;
        }else{
            return $product->getStock() >= $requiredQuantity;
        }
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
    
    /**
     * Set transaction collection
     * @param array $transactionList
     */
    public function setTransactionList(array $transactionList){
        $this->transactionList=$transactionList;
    }
    
    
}

