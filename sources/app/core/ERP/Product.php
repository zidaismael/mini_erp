<?php
declare(strict_types = 1);

namespace ERP;

class Product
{
    
    /**
     * @var string|null $reference
     */
    protected $reference;
    
    /**
     * External reference (represent provider product reference for company product's to track it)
     * @var string $externalReference
     */
    protected string $externalReference;
    
    /**
     * @var string $name
     */
    protected string $name;
    
    /**
     * @var float|null $price 
     */
    protected $price;
    
    /**
     * @var float|null $tax
     */
    protected $tax;
    
    /**
     * @var int|null $orderQuantity
     */
    protected $orderQuantity;
    
    /**
     * @var int|null $stock
     */
    protected $stock;
    
    
    /**
     * Constructor
     * @param string $reference
     * @param string $name
     * @param float|null $price
     * @param float|null $tax
     */
    public function __construct($reference, string $name, $price, $tax){
        $this->name=$name;
        
        if(!is_null($reference) && !is_string($reference)){
            throw new \InvalidArgumentException(sprintf("Bad \$reference format %s",__METHOD__));
        }else{
            $this->reference=$reference;
        }
        
        if(!is_null($price) && !is_float($price)){
            throw new \InvalidArgumentException(sprintf("Bad \$price format %s",__METHOD__));
        }else{
            $this->price=$price;
        }
        
        if(!is_null($tax) && !is_float($tax)){
            throw new \InvalidArgumentException(sprintf("Bad \$tax format %s",__METHOD__));
        }else{
            $this->tax=$tax;
        }    
    }
    
    /**
     * Generate product reference
     * @return string 
     */
    public function generateReference(): string{
        return sprintf("PRD_%d", random_int(0, 999999999));
    }
    
    public function setStock($stock){
        $this->stock=$stock;
        return $this;
    }
    
    public function setOrderQuantity(int $quantity){
        $this->orderQuantity=$quantity;
        return $this;
    }
    
    public function setPrice(float $price){
        $this->price=$price;
        return $this;
    }
    
    public function setTax(float $tax){
        $this->tax=$tax;
        return $this;
    }
    
    public function setExternalReference(string $reference){
        $this->externalReference=$reference;
        return $this;
    }
    
    public function setReference(string $reference){
        $this->reference=$reference;
        return $this;
    }
    
    public function getName(): string{
        return $this->name;
    }
    
    public function getPrice(): ?float{
        return $this->price;
    }
    
    public function getTax(): ?float{
        return $this->tax;
    }
    
    public function getStock(): ?int{
        return $this->stock;
    }
    
    public function getOrderQuantity(): ?int{
        return $this->orderQuantity;
    }
    
    public function getReference(): ?string{
        return $this->reference;
    }
    
    public function getExternalReference(): string{
        return $this->externalReference;
    }
    
    
}

