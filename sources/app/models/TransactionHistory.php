<?php

class TransactionHistory extends AbstractModel
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
    public $transaction_reference;

    /**
     *
     * @var string
     */
    public $transaction_type;

    /**
     *
     * @var string
     */
    public $seller_reference;

    /**
     *
     * @var string
     */
    public $seller_name;

    /**
     *
     * @var string
     */
    public $buyer_reference;

    /**
     *
     * @var string
     */
    public $buyer_name;

    /**
     *
     * @var string
     */
    public $product_reference;

    /**
     *
     * @var string
     */
    public $product_name;

    /**
     *
     * @var integer
     */
    public $quantity;

    /**
     *
     * @var double
     */
    public $price;

    /**
     *
     * @var double
     */
    public $tax;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("mini_erp");
        $this->setSource("transaction_history");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TransactionHistory[]|TransactionHistory|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TransactionHistory|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
