<?php
declare(strict_types=1);

use Exception\ApiException;
use Phalcon\Http\Response;

class CompanyEmployeeController extends AbstractController
{
    /**
     * Get Company employee list
     *
     * @param int $id
     * @return \Phalcon\Http\Response
     */
    public function get(int $id): Response
    {
        $company = CompanyModel::findFirst($id);
        
        if(empty($company)){
            throw new ApiException("Not found",404);
        }else{
            $result=$company->getRelated('employee');
            return $this->output(200, $result->toArray());
        }
    }
}

