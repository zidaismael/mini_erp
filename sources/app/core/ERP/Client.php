<?php
declare(strict_types = 1);

namespace ERP;

use \ERP\ERPInterface\BuyerInterface;
use \ERP\ERPInterface\SellerInterface;
use \ERP\Factory\ProductFactory;
use \ERP\Transaction;
use \ERP\Product;
use Exception\CoreException;

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
     * Buy products
     * @param SellerInterface $seller
     * @param Employee $employee
     * @param array $orderedProducts
     * @return \ERP\Transaction|null
     */
    public function buyProducts(SellerInterface $seller, Employee $employee, array $orderedProducts): ?Transaction{
        return null;
    }
    
    /**
     * Tell if can buy product
     * @param float $total
     */
    public function hasEnougthMoney(float $total): bool{
        return true;
    }
    
    /**
     * Get product
     * @param string $productReference
     * @return bool
     */
    public function getProduct(string $productReference): ?Product{
        $productList=array_filter($this->boughtProductList,function($entry) use ($productReference){ return $entry->getExternalReference() == $productReference; });
        return !empty($productList) ? array_pop($productList) : null;
    }
    
    
    /**
     * Add product to buy list
     * @param SellerInterface $company
     * @param BuyerInterface $client
     * @param array $orderedProduct
     */
    public function addBoughtProduct(SellerInterface $company, BuyerInterface $client, array $orderedProduct){
    
        //constuct order product
        $companyProduct=$company->getProduct($orderedProduct['reference']);
        if(is_null($companyProduct)){
            throw new CoreException(sprintf("Can't find company product for %s", $orderedProduct['reference']));
        }
        
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
    
    public function getBoughtProductList() : array{
        return $this->boughtProductList;
    }
    
    /**
     * @return string
     */
    public function getReference(){
        return $this->reference;
    }
    
    public function setReference(string $reference){
        $this->reference=$reference;
        return $this;
    }
    
    /**
     * Set transaction collection
     * @param array $transactionList
     */
    public function setTransactionList(array $transactionList): array{
        $this->transactionList=$transactionList;
        return $this;
    }
}

