<?php
declare(strict_types = 1);

namespace ERP\Factory;

use \TransactionModel;
use \ERP\Transaction;
use \ERP\Product;

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

