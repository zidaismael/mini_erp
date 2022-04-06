<?php
declare(strict_types = 1);
namespace ERP;

use \ERP\ERPInterface\BuyerInterface;
use \ERP\ERPInterface\SellerInterface;
use \ERP\Employee;
use \ERP\Company;
use \ERP\Client;
use \Exception\CoreException;

class Transaction
{
    
    const TYPE_SUPPLY = 'supply';
    const TYPE_SELL = 'sell';
    
    /**
     * @var string|null
     */
    protected string $reference;
    
    /**
     * @var \DateTime
     */
    protected \DateTime $date;
    
    /**
     * @var string
     */
    protected string $type;
    
    /**
     * @var Employee
     */
    protected Employee $responsible;
    
    /**
     * @var float
     */
    protected $amount = 0.0;
    
    /**
     * @var array
     */
    protected array $productList=[];
    
    /**
     * @var SellerInterface
     */
    protected SellerInterface $seller;
    
    /**
     * @var BuyerInterface
     */
    protected BuyerInterface $buyer;
    
    public function __construct($reference, Employee $reponsible, SellerInterface $seller, BuyerInterface $buyer){
        
        if(!is_null($reference) && !is_string($reference)){
            throw new CoreException(sprintf("Bad \$reference type. %s",__METHOD__));
        }

        $this->date= new \DateTime('now',new \DateTimezone('UTC'));
        
        if($buyer instanceof Company){
            $this->supply=self::TYPE_SUPPLY;
        }else if($buyer instanceof Client){
            $this->supply=self::TYPE_SELL;
        }
        
        $this->seller=$seller;
        
        $this->buyer=$buyer;
        
        $this->productList=$buyer->getBoughtProductList();
        
        $this->amount=$this->computeAmount();
    }
        
    /**
     * Compute transaction amount
     */
    protected function computeAmount(): float{
        $amount=0.0;
        foreach($this->productList as $product){
            $amount+= $product->getPrice() * $product->getStock();
        }
        
        return $amount;
    }
    
    public function setResponsible(Employee $employee){
        $this->responsible=$employee;
        return $this;
    }
    
    public function getResponsible(): ?Employee{
        return $this->responsible;
    }
    
}

