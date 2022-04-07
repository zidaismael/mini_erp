<?php

use ERP\Client;
use ERP\Product;
use ERP\Company;
use ERP\Employee;
use ERP\Provider;
use ERP\Transaction;

final class ObjectProvider
{
    public static function getErpObjectsWithStaticReferences(): array{
        $product=new Product("PRODUCT1234", "Imprimante EPSON", 145.99, 10.8);
        $product->setStock(8);
        
        return [
            'client' => new Client("CLIENT1234"),
            'product' => $product,
            'company' => new Company("COMPANY1234", 10.9),
            'employee' => new Employee("EMPLOYEE1234"),
            'provider' => new Provider("PROVIDER1234")
        ];
    }
        
    
    public static function getErpObjectsWithRandomReferences(): array{
        $product=new Product("", "Produit aléatoire", 145.99, 10.8);
        $product->setStock(8);
        
        return [
            'client' => static::setReference(new Client("")),
            'product' => static::setReference($product),
            'company' => static::setReference(new Company("", 10.9)),
            'employee' => static::setReference(new Employee("")),
            'provider' => static::setReference(new Provider(""))
        ];
    }
        
    
    /**
     * Set object reference
     * @param \StdClass $object
     * @throws \Exception
     */
    protected static function setReference($object){
 
        if($object instanceOf Client){
            $prefix='CLI';
        }else if($object instanceOf Product){
            $prefix='PRD';
        }else if($object instanceOf Company){
            $prefix='CPY';
        }else if($object instanceOf Employee){
            $prefix='EMP';
        }else if($object instanceOf Provider){
            $prefix='PRO';
        }else{
            throw new \Exception(sprintf('not available. %s',__METHOD__));
        }
        
        $object->setReference(sprintf("%s_%d", $prefix, random_int(0, 999999999)));
        
        return $object;
    }
}

