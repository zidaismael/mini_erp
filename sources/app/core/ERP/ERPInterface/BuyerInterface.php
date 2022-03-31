<?php
namespace ERP\ERPInterface;

interface BuyerInterface
{
    abstract public function buy($product, $quantity);
}

