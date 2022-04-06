<?php

use \Exception\CoreException;

class ProductModel extends AbstractModel
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
    public $external_reference;

    /**
     *
     * @var string
     */
    public $name;

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
     *
     * @var integer
     */
    public $stock;

    /**
     *
     * @var integer
     */
    public $company_id;

    /**
     *
     * @var integer
     */
    public $provider_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("mini_erp");
        $this->setSource("product");
        $this->hasMany('id', 'RelTransactionProduct', 'product_id', ['alias' => 'RelTransactionProduct']);
        $this->belongsTo('company_id', 'CompanyModel', 'id', ['alias' => 'Company']);
        $this->belongsTo('provider_id', 'ProviderModel', 'id', ['alias' => 'Provider']);
    }
    
    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Product[]|Product|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Product|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

    /**
     * Get product by reference
     * @param string $reference
     * @param bool $useExternal use external reference column (default false)
     * @return Product|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function getByReference(string $reference, bool $useExternal=false): ?\Phalcon\Mvc\ModelInterface{
        $columnName = $useExternal===true ? 'external_reference' : 'reference';
        return parent::findFirst([
            'conditions' => "$columnName = :reference:",
            'bind' => [$columnName=> $reference]
        ]);
    }

    
    /**
     * Get owner products by product references
     * @param string $ownerType Provider or Company
     * @param int $ownerId Provider or Company Id
     * @param array $searchValues
     * @param bool $useExternal use external reference column (default false)
     * @throws CoreException
     * @return Products|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|NULL
     */
    public static function getProductsByReferences(string $ownerType, int $ownerId, array $searchValues, bool $useExternal=false){
    
        $ownerType=strtolower($ownerType);
    
        if(!in_array($ownerType,['provider','company'])){
            throw new CoreException("Parameters must only be provider or company");
        }
    
        if($ownerId<1){
            return null;
        }
         
        $columnName = $useExternal===true ? 'external_reference' : 'reference';
        $condition=sprintf("%s_id = :ownerId: AND %s IN ({%s:array})", $ownerType, $columnName, $columnName);
        $productList = ProductModel::find(['conditions' => $condition, 'bind' => ['ownerId'=> $ownerId, $columnName => $searchValues]]);
        $result=$productList->toArray();
        if(empty($result)){
            throw new CoreException(sprintf("Invalid products reference(s). Search in '%s' column.",$columnName));
        }
    
        return $productList;
    }
    
}
