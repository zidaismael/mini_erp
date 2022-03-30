<?php

class Transaction extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $date;

    /**
     *
     * @var string
     */
    public $reference;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $employee_id;

    /**
     *
     * @var integer
     */
    public $client_id;

    /**
     *
     * @var integer
     */
    public $product_quantity;

    /**
     *
     * @var double
     */
    public $product_price;

    /**
     *
     * @var double
     */
    public $product_tax;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("mini_erp");
        $this->setSource("transaction");
        $this->hasMany('id', 'RelTransactionProduct', 'id_transaction', ['alias' => 'RelTransactionProduct']);
        $this->belongsTo('client_id', '\Client', 'id', ['alias' => 'Client']);
        $this->belongsTo('employee_id', '\Employee', 'id', ['alias' => 'Employee']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Transaction[]|Transaction|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Transaction|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
