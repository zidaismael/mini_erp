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
     * @var float
     */
    protected $amount = 0.0;
    
    /**
     * @var array
     */
    protected array $productList=[];
    
    /**
     * @var Employee
     */
    protected Employee $responsible;
    
    /**
     * @var SellerInterface
     */
    protected SellerInterface $seller;
    
    /**
     * @var BuyerInterface
     */
    protected BuyerInterface $buyer;
    
    /**
     * Constructor
     * @param \ERP\Employee $reponsible
     * @param \ERP\ERPInterface\SellerInterface $reponsible
     * @param \ERP\ERPInterface\BuyerInterface $buyer
     */
    public function __construct(Employee $reponsible, SellerInterface $seller, BuyerInterface $buyer){
        $this->reference = sprintf("TRA_%d", random_int(0, 999999999));
        $this->date= new \DateTime('now',new \DateTimezone('UTC'));
        
        if($buyer instanceof Company){
            $this->type=self::TYPE_SUPPLY;
        }else if($buyer instanceof Client){
            $this->type=self::TYPE_SELL;
        }
        
        $this->seller=$seller;      
        $this->buyer=$buyer;   
        $this->productList=$buyer->getBoughtProductList();  
        $this->amount=$this->computeAmount();  
        $this->responsible=$reponsible; 
    }
        
    /**
     * Compute transaction amount
     * @return float
     */
    protected function computeAmount(): float{
        $amount=0.0;
        foreach($this->productList as $product){
            $amount+= $product->getPrice() * $product->getOrderQuantity();
        }
        
        return $amount;
    }
    
    protected function getInfo(): array{
        return [
            'reference' => $this->reference,
            'type' => $this->type,
            'date' => $this->date,
            'products' => $this->productList
        ];
    }
    
    public function getType(): string{
        return $this->type;
    }
    
    public function getDate(): \DateTime{
        return $this->date;
    }
    
    public function getProductList(): array{
        return $this->productList;
    }
    
    public function getAmount(): float{
        return $this->amount;
    }
    
    public function setResponsible(Employee $employee){
        $this->responsible=$employee;
        return $this;
    }
    
    public function getReference(): string{
        return $this->reference;
    }
    
    public function getResponsible(): ?Employee{
        return $this->responsible;
    }
    
    public function getSeller(): SellerInterface{
        return $this->seller;
    }
    
    public function getBuyer(): BuyerInterface{
        return $this->buyer;
    }
    
   
    
}

