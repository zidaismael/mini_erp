<?php
declare(strict_types = 1);

use ERP\Product;
use Exception\CoreException;
use Exception\TransactionException;
use ERP\Transaction;
use ERP\Employee;

/**
 * Test class only for core component
 */
class CompanyTest extends AbstractTest
{
    public function testHasEnougthMoney()
    {
        $this->assertTrue($this->erpInstances['company']->hasEnougthMoney(10));
        $this->assertFalse($this->erpInstances['company']->hasEnougthMoney(90000.7));
    }
    
    public function testAddBoughtProduct()
    {
        $order = [
            'reference' => 'PROVIDER_PRODUCT_1234',
            'quantity' => 4
        ];
    
        $this->erpInstances['product']->setReference('PROVIDER_PRODUCT_1234');
        $this->erpInstances['provider']->setAvailableProductList([
            $this->erpInstances['product']
        ]);
        
        $companyProduct=clone $this->erpInstances['product'];
        $companyProduct->setReference('')->setExternalReference('PROVIDER_PRODUCT_1234');
        $this->erpInstances['company']->setAvailableProductList([
            $companyProduct
        ]);
        
        $this->erpInstances['company']->addBoughtProduct($this->erpInstances['provider'], $this->erpInstances['company'], $order);
    
        $foundProduct = $this->erpInstances['company']->getBoughtProductList();

        $this->assertNotEmpty($foundProduct);
        $foundProduct = array_pop($foundProduct);
    
        $this->assertInstanceOf(Product::class, $foundProduct);
    
        // company reference product must be place as external reference for client product
        $this->assertEmpty($foundProduct->getReference());
        $this->assertEquals('PROVIDER_PRODUCT_1234', $foundProduct->getExternalReference());
    }
    
    public function testAddBoughtProductException()
    {
        $order = [
            'reference' => 'YYYYYYYYYY',
            'quantity' => 4
        ];
        
        $this->expectException(CoreException::class);
        $this->expectExceptionMessage("Product for company not found YYYYYYYYYY");
        $this->erpInstances['company']->addBoughtProduct($this->erpInstances['provider'], $this->erpInstances['company'], $order);
    }
    
    
    public function testBuyNewProducts()
    {
        $productPrice=3.87;
        $productQuantity=2;
        $companyBalance=100;
        
        $order = [[
            'reference' => 'NEW_PROVIDER_PRODUCT_1234',
            'quantity' => $productQuantity
        ]];
        
        //set availables products
        $this->erpInstances['product']->setReference('NEW_PROVIDER_PRODUCT_1234')->setPrice($productPrice);
        $providerProductInitialStock=$this->erpInstances['product']->getStock();
        
        $this->erpInstances['provider']->setAvailableProductList([
            $this->erpInstances['product']
        ]);
        
       $transaction=$this->erpInstances['company']->setBalance($companyBalance)->buyProducts(
           $this->erpInstances['provider'], 
           $this->erpInstances['employee'],
           $order
       );

       $this->assertInstanceOf(Transaction::class, $transaction);
       $this->assertInstanceOf(Employee::class, $transaction->getResponsible());
       $this->assertEquals('supply', $transaction->getType());
       $this->assertEquals(($productPrice*$productQuantity), $transaction->getAmount());
       $this->assertEquals(($companyBalance-$productQuantity*$productPrice), $this->erpInstances['company']->getBalance());
       
       $companyProducts=$transaction->getBuyer()->getAvailableProductList();
       $this->assertNotEmpty($companyProducts);
       //index [0] because new company product
       $this->assertEquals($productQuantity, $companyProducts[0]->getStock());
       
       $providerProducts=$transaction->getSeller()->getAvailableProductList();
       $this->assertNotEmpty($providerProducts);
       $this->assertEquals(($providerProductInitialStock - $productQuantity), $providerProducts['NEW_PROVIDER_PRODUCT_1234']->getStock());
    }
    
    public function testBuyOldProducts()
    {
        $productPrice=38;
        $productQuantity=17;
        $companyBalance=9000;
        $companyProductInitialStock=44;
        
        $order = [[
            'reference' => 'OLD_PROVIDER_PRODUCT_1234',
            'quantity' => $productQuantity
        ]];
        
        //set availables products
        $initialCompanyProduct= clone  $this->erpInstances['product'];
        $initialCompanyProduct->setReference('ALREADY_MY_PRODUCT_1234')->setExternalReference('OLD_PROVIDER_PRODUCT_1234')
        ->setStock($companyProductInitialStock)->setPrice($productPrice);
        
        $this->erpInstances['company']->setAvailableProductList([
            $initialCompanyProduct
        ]);
        
        $this->erpInstances['product']->setReference('OLD_PROVIDER_PRODUCT_1234')->setPrice($productPrice)->setStock(87);
        $providerProductInitialStock=$this->erpInstances['product']->getStock();
        
        $this->erpInstances['provider']->setAvailableProductList([
            $this->erpInstances['product']
        ]);

       $transaction=$this->erpInstances['company']->setBalance($companyBalance)->buyProducts(
           $this->erpInstances['provider'], 
           $this->erpInstances['employee'],
           $order
       );

       $this->assertInstanceOf(Transaction::class, $transaction);
       $this->assertInstanceOf(Employee::class, $transaction->getResponsible());
      
       $companyProducts=$transaction->getBuyer()->getAvailableProductList();
       $this->assertNotEmpty($companyProducts);
       $this->assertEquals(($companyProductInitialStock+$productQuantity), $companyProducts['ALREADY_MY_PRODUCT_1234']->getStock());
       
    }
    
    public function testBuyProductsNotEnoughtMoney()
    {
        $order = [[
            'reference' => 'NEW_PROVIDER_PRODUCT_1234',
            'quantity' => 4
        ]];
       
        //set availables products
        $this->erpInstances['product']->setReference('NEW_PROVIDER_PRODUCT_1234');
        $this->erpInstances['provider']->setAvailableProductList([
            $this->erpInstances['product']
        ]);
        
        $this->expectException(TransactionException::class);
        $this->expectExceptionMessage("Company doesn't have enought money to proceed supply.");
        
        $this->erpInstances['company']->buyProducts(
            $this->erpInstances['provider'],
            $this->erpInstances['employee'],
            $order
        );
    }
    
    public function testBuyProductsNotEnoughtStock()
    {
        $order = [[
            'reference' => 'NEW_PROVIDER_PRODUCT_1234',
            'quantity' => 90
        ]];
         
        //set availables products
        $this->erpInstances['product']->setReference('NEW_PROVIDER_PRODUCT_1234');
        $this->erpInstances['provider']->setAvailableProductList([
            $this->erpInstances['product']
        ]);
        
        $this->expectException(TransactionException::class);
        $this->expectExceptionMessage("Not enougth product NEW_PROVIDER_PRODUCT_1234 quantity 8 to proceed supply.");
        
        $this->erpInstances['company']->buyProducts(
            $this->erpInstances['provider'],
            $this->erpInstances['employee'],
            $order
            );
    }
    
    
    public function testSellProducts()
    {
        $productQuantity=2;
        
        $order = [[
            'reference' => 'ALREADY_MY_PRODUCT_1234',
            'quantity' => $productQuantity
        ]];
         
        //set availables products
        $this->erpInstances['product']->setReference('ALREADY_MY_PRODUCT_1234');
        $this->erpInstances['company']->setAvailableProductList([
            $this->erpInstances['product']
        ]);
        
        $companyBalance=$this->erpInstances['company']->getBalance();
        $productPrice=$this->erpInstances['product']->getPrice();
 
        $transaction=$this->erpInstances['company']->sellProducts(
            $this->erpInstances['client'],
            $this->erpInstances['employee'],
            $order
        );

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertInstanceOf(Employee::class, $transaction->getResponsible());
        $this->assertEquals('sell', $transaction->getType());
        $this->assertEquals(($productPrice*$productQuantity), $transaction->getAmount());
        $this->assertEquals(($companyBalance+$productQuantity*$productPrice), $this->erpInstances['company']->getBalance());

        $clientProducts=$transaction->getBuyer()->getBoughtProductList();
        $this->assertNotEmpty($clientProducts);
        
        //index [0] because new company product
        $this->assertEquals($productQuantity, $clientProducts[0]->getOrderQuantity());
        
        $companyProducts=$transaction->getSeller()->getAvailableProductList();
        $this->assertNotEmpty($companyProducts);
    }
    
    public function testSellProductsNotEnoughtStock()
    {
        $order = [[
            'reference' => 'ALREADY_MY_PRODUCT_1234',
            'quantity' => 90
        ]];
         
        //set availables products
        $this->erpInstances['product']->setReference('ALREADY_MY_PRODUCT_1234');
        $this->erpInstances['company']->setAvailableProductList([
            $this->erpInstances['product']
        ]);
        
        $this->expectException(TransactionException::class);
        $this->expectExceptionMessage("Not enougth product ALREADY_MY_PRODUCT_1234 quantity 8 to proceed sell.");
        
        $this->erpInstances['company']->sellProducts(
            $this->erpInstances['client'],
            $this->erpInstances['employee'],
            $order
        );
    }
}

