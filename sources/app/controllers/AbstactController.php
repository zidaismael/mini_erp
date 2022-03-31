<?php
declare(strict_types = 1);

use Phalcon\Http\Request;

class AbstactController extends \Phalcon\Mvc\Controller
{

    /**
     * Get Json body request
     * 
     * @param array $mandatoryKeys
     *            Mandatory key in json body request
     * @throws \Exception
     * @return array
     */
    protected function getBody(array $mandatoryKeys = [])
    {
        $body= json_decode($this->request->getRawBody(),true);
        
        if(empty($body)){
            return [];
        }
    
        // check mandatory key
        if (! empty($mandatoryKeys)) {
            $foundKeys = array_intersect($mandatoryKeys, array_keys($body));
            $notFoundKeys = array_diff($mandatoryKeys, $foundKeys);
            if (! empty($notFoundKeys)) {
                throw new \Exception(sprintf("Mandatory keys in json body not found: %s", implode(", ", $notFoundKeys)), 400);
            }
        }
        
        return $body ?? [];
    }
    
    /**
     * Respond to call in JSON
     * @param int $httpCode
     * @param mixed $content
     */
    protected function output(int $httpCode=200, $content){
        $this->response->setStatusCode($httpCode);
        return $this->response->setJsonContent($content);
    }
}

