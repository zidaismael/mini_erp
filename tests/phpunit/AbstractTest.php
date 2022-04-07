<?php
declare(strict_types=1);
use \PHPUnit\Framework\TestCase;


abstract class AbstractTest extends TestCase
{
    
    protected array $erpInstances=[];
    
    protected function setUp(): void{
        $this->erpInstances=ObjectProvider::getErpObjectsWithRandomReferences();
    }
    
    protected function tearDown(): void{
    }
}

