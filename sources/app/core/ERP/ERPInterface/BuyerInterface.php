<?php
declare(strict_types = 1);

namespace ERP\ERPInterface;

use \ERP\Transaction;
use \ERP\Employee;
use \ERP\ERPInterface\SellerInterface;

interface BuyerInterface
{
    public function buyProducts(SellerInterface $seller, Employee $employee, array $orderedProducts): ?Transaction;

    public function hasEnougthMoney(float $total): bool;
    
    public function getBoughtProductList() : array;
    
    public function addBoughtProduct(SellerInterface $seller, BuyerInterface $buyer, array $order);
}

