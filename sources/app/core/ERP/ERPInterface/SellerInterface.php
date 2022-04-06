<?php
namespace ERP\ERPInterface;

use \ERP\Transaction;
use \ERP\ERPInterface\BuyerInterface;
interface SellerInterface
{
    //public function sellProducts(BuyerInterface $buyer, array $products): ?Transaction;
    
    public function hasEnougthStock(string $productReference, int $requiredQuantity): bool;
}

