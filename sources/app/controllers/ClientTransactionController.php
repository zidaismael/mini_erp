<?php
declare(strict_types=1);

class ClientTransactionController extends AbstractController
{
    /**
     * Get Client transaction list
     *
     * @param int $id
     * @return \Phalcon\Http\Response
     */
    public function get(int $id)
    {
        $client = ClientModel::findFirst($id);
    
        if(empty($client)){
            throw new ApiException("Not found",404);
        }else{
            $result=$client->getRelated('transaction');
            return $this->output(200, $result->toArray());
        }
    }
}

