<?php

use AbstractModel;

class Client extends AbstractModel
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
    public $reference;

    /**
     *
     * @var string
     */
    public $lastname;

    /**
     *
     * @var string
     */
    public $firstname;

    /**
     *
     * @var string
     */
    public $address;

    /**
     *
     * @var string
     */
    public $country;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("mini_erp");
        $this->setSource("client");
        $this->hasMany('id', 'Transaction', 'client_id', ['alias' => 'Transaction']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Client[]|Client|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Client|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
