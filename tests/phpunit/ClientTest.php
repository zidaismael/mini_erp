<?php
declare(strict_types = 1);

use ERP\Product;

/**
 * Test class only for core component
 */
class ClientTest extends AbstractTest
{

    public function testHasEnougthMoney()
    {
        // must always return true for client
        $this->assertTrue($this->erpInstances['client']->hasEnougthMoney(90.7));
    }

    public function testAddBoughtProduct()
    {
        $order = [
            'reference' => 'NEW_PRODUCT_1234',
            'quantity' => 4
        ];
        
        $this->erpInstances['product']->setReference('NEW_PRODUCT_1234');
        
        $this->erpInstances['company']->setAvailableProductList([
            $this->erpInstances['product']
        ]);
        
        $this->erpInstances['client']->addBoughtProduct($this->erpInstances['company'], $this->erpInstances['client'], $order);
        
        $foundProduct = $this->erpInstances['client']->getBoughtProductList();
        
        $this->assertNotEmpty($foundProduct);
        $foundProduct = array_pop($foundProduct);
        
        $this->assertInstanceOf(Product::class, $foundProduct);
        
        // company reference product must be place as external reference for client product
        $this->assertNull($foundProduct->getReference());
        $this->assertEquals('NEW_PRODUCT_1234', $foundProduct->getExternalReference());
    }

    public function testGetProduct()
    {
        $this->testAddBoughtProduct();
        $product = $this->erpInstances['client']->getProduct('NEW_PRODUCT_1234');
        $this->assertInstanceOf(Product::class, $product);
    }
    
    public function testBuyProducts()
    {
        $order = [
            'reference' => 'NEW_PRODUCT_1234',
            'quantity' => 4
        ];
        
        //not necesary method
        $this->assertNull($this->erpInstances['client']->buyProducts(
            $this->erpInstances['company'],
            $this->erpInstances['employee'],
            $order
        ));
    }
}

