<?php
declare(strict_types = 1);

class RelTransactionProduct extends AbstractModel
{

    /**
     *
     * @var integer
     */
    public $transaction_id;

    /**
     *
     * @var integer
     */
    public $product_id;

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
        $this->setSource("rel_transaction_product");
        $this->belongsTo('product_id', '\Product', 'id', ['alias' => 'Product']);
        $this->belongsTo('transaction_id', '\Transaction', 'id', ['alias' => 'Transaction']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return RelTransactionProduct[]|RelTransactionProduct|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return RelTransactionProduct|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
