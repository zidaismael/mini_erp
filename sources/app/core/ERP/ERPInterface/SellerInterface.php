<?php
namespace ERP\ERPInterface;

use \ERP\Transaction;
use \ERP\ERPInterface\BuyerInterface;
interface SellerInterface
{
    public function hasEnoughtStock(string $productReference, int $requiredQuantity): bool;
}

