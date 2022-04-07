<?php
declare(strict_types = 1);

namespace ERP\Factory;

use ERP\Company;
use CompanyModel;
use ProductModel;
use Exception\CoreException;
use \Phalcon\Mvc\ModelInterface;

class CompanyFactory
{
    /**
     * @param ModelInterface $companyModels
     * @param array $productValues Products values to match with
     * @param bool $useExternalProductReference Use external product reference to retrieve owned products (default false)
     * @throws ApiException
     * @return Company
     */
    public static function build(CompanyModel $companyModel, array $productValues, bool $useExternalProductReference=false): Company{
        try{
            $productsModel=ProductModel::getProductsByReferences('company', $companyModel->id, $productValues, $useExternalProductReference);
        }catch(CoreException $e){
            $productsModel=null;
        }
        
        if(!is_null($productsModel)){//mistake on product supply order
            $productList=ProductFactory::buildProducts($productsModel->toArray());
        }else{
            $productList=[];
        }
        
        //construct provider object and relations
 
        $company=new Company($companyModel->reference, $companyModel->balance);
 
        if(!empty($productList)){
            $company->setAvailableProductList($productList);
        }
        
        return $company;
    }
}

