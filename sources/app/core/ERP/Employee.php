<?php
declare(strict_types = 1);

namespace ERP;

class Employee
{
    /**
     * @var string $reference
     */
    protected string $reference;
    
    /**
     * @var array $managedTransactionList
     */
    protected array $managedTransactionList=[];
    
    /**
     * Construct
     * @param string $reference
     * @return \ERP\Client
     */
    public function __construct(string $reference){
        $this->reference=$reference;
    }
    
    /**
     * Add transaction to manage
     * @param Transaction $transaction
     */
    public function addTransaction(Transaction $transaction){
        $this->managedTransactionList[$transaction->getReference()]=$transaction;
    }
    
    /**
     * Set transaction collection
     * @param array $transactionList
     */
    public function setManagedTransactionList(array $transactionList){
        $this->transactionList=$transactionList;
    }
    
    public function getReference(): string{
        return $this->reference;
    }
}

