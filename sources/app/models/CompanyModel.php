<?php
declare(strict_types = 1);

class CompanyModel extends AbstractModel
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
    public $name;

    /**
     *
     * @var double
     */
    public $balance;

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
        $this->setSource("company");
        $this->hasMany('id', 'EmployeeModel', 'company_id', ['alias' => 'Employee']);
        $this->hasMany('id', 'ProductModel', 'company_id', ['alias' => 'Product']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CompanyModel[]|CompanyModel|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CompanyModel|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }
    
    /**
     * Get company by reference
     * @param string $reference
     * @return CompanyModel|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function getByReference(string $reference): ?\Phalcon\Mvc\ModelInterface{
        return parent::findFirst([
            'conditions' => 'reference = :reference:',
            'bind' => ['reference'=> $reference]
        ]);
    }

}
