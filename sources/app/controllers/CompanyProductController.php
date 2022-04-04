<?php
declare(strict_types=1);

use Exception\ApiException;

class CompanyProductController extends AbstractController
{
    /**
     * Get Company product list
     *
     * @param int $id
     * @return \Phalcon\Http\Response
     */
    public function get(int $id)
    {
        $company = CompanyModel::findFirst($id);
    
        if(empty($company)){
            throw new ApiException("Not found",404);
        }
        
        $result=$company->getRelated('product');
        return $this->output(200, $result->toArray());
    }
}

