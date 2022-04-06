<?php
namespace ERP\Factory;

use \ERP\Product;

class ProductFactory
{
    /**
     * Build product collection
     * @param array $result
     * @return array
     */
    public static function build(array $productData): Product{
        $reference = $productData['reference'] ?? null;
        $price = $productData['price'] ?? null;
        $tax = $productData['tax'] ?? null;
        $product=new Product($reference, $productData['name'], $price, $tax);
   
        if(isset($productData['stock']) && !is_null($productData['stock'])){
            $product->setStock($productData['stock']);
        }
        
        if(isset($productData['quantity']) && !is_null($productData['quantity'])){
            $product->setOrderQuantity($productData['quantity']);
        }
        
        if(!empty($productData['external_reference'])){
            $product->setExternalReference($productData['external_reference']);
        }
        
        return $product;
    }
    
    /**
     * Build product collection
     * @param array $result
     * @return array
     */
    public static function buildProducts(array $productData): array{
        
        if(empty($productData)){
            return [];
        }else{
            $productList=[];
            foreach($productData as $data){
                $product=new Product($data['reference'], $data['name'], $data['price'], $data['tax']);
                if(!is_null($data['stock'])){
                    $product->setStock($data['stock']);
                }
                
                if(!is_null($data['external_reference'])){
                    $product->setExternalReference($data['external_reference']);
                }
                
                $productList[]=$product;
            }
        }
        
        return $productList;
    }
    
}

