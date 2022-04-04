<?php
declare(strict_types=1);

use Exception\ApiException;

class EmployeeTransactionController extends AbstractController
{
    /**
     * Get Employee transaction list
     *
     * @param int $id
     * @return \Phalcon\Http\Response
     */
    public function get(int $id)
    {
        $employee = Employee::findFirst($id);
    
        if(empty($employee)){
            throw new ApiException("Not found",404);
        }else{
            $result=$employee->getRelated('transaction');
            return $this->output(200, $result->toArray());
        }
    }
}

