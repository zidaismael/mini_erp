<?php
declare(strict_types=1);

class ProviderProductController extends AbstractController
{
    /**
     * Get Provider product list
     *
     * @param int $id
     * @return \Phalcon\Http\Response
     */
    public function get(int $id)
    {
        $provider = Provider::findFirst($id);
    
        if(empty($provider)){
            throw new ApiException("Not found",404);
        }else{
            $result=$provider->getRelated('product');
            return $this->output(200, $result->toArray());
        }
    }
}

