<?php
namespace ERP\ERPInterface;

interface SellerInterface
{
    abstract public function sell($product, $quantity);
}

