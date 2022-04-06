<?php
namespace ERP\Factory;

use ERP\Provider;
use ProviderModel;
use ProductModel;
use Exception\ApiException;
use \Phalcon\Mvc\ModelInterface;

class ProviderFactory
{
    /**
     * @param ModelInterface $providerModel
     * @param array $productValues Products values to match with
     * @throws ApiException
     * @return Company
     */
    public static function build(ProviderModel $providerModel, array $productValues): Provider{
        $productsModel=ProductModel::getProductsByReferences('provider', $providerModel->id, $productValues);
        if(!is_null($productsModel)){//mistake on product supply order
            $productList=ProductFactory::buildProducts($productsModel->toArray());
        }else{
            $productList=[];
        }
        
        //construct provider object and relations
        $provider=new Provider($providerModel->reference);
        
        if(!empty($productList)){
            $provider->setAvailableProductList($productList);
        }
        
        return $provider;
    }
}

