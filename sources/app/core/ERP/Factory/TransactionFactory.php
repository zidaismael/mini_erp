<?php
declare(strict_types = 1);

namespace ERP\Factory;

use \ERP\ERPInterface\BuyerInterface;
use \ERP\ERPInterface\SellerInterface;
use \TransactionModel;
use \ERP\Transaction;
use \ERP\Employee;
use \ERP\Product;
use \Exception\CoreException;

class TransactionFactory
{
    
    /**
     * Build transaction collection
     * @param TransactionModel $result
     * @return array
     */
    public static function buildTransactions(TransactionModel $result): array{
        $transactionList=[];
        
        $list=$result->toArray();
        if(empty($list)){
            return [];
        }else{
            foreach($list as $transactionData){
                $product=new Transaction($transactionData['reference'], $transactionData['name'], $transactionData['price'], $transactionData['tax']);
            }
        }
        
        return $transactionList;
    }
}

