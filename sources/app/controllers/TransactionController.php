<?php
declare(strict_types=1);

use Exception\ApiException;

class TransactionController extends AbstractController
{
    /**
     * Get transaction
     *
     * @param int $id
     * @return \Phalcon\Http\Response
     */
    public function get(int $id)
    {
        $result = TransactionModel::findFirst($id);
    
        if(empty($result)){
            throw new ApiException("Not found",404);
        }else{
            return $this->output(200, $result->toArray());
        }
    }
}

