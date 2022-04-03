<?php
declare(strict_types = 1);

use Phalcon\Http\Request;
use Phalcon\Validation;
use Exception\ {
                ApiException, 
                CoreException
};

/**
 * Controller abstract
 */
class AbstractController extends \Phalcon\Mvc\Controller
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
        $body = json_decode($this->request->getRawBody(), true);
        
        if (empty($body)) {
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
     *
     * @param int $httpCode            
     * @param mixed $content            
     */
    protected function output(int $httpCode = 200, $content=null)
    {
        $this->response->setStatusCode($httpCode);
        
        if(!is_null($content)){
            return $this->response->setJsonContent($content);
        }else{
            return $this->response;
        }
    }

    /**
     * Validate api entries data with principal Phalcon validator
     *
     * @see https://docs.phalcon.io/4.0/en/api/phalcon_validation#validation          
     * @param string $expectedType            
     * @param mixed $data            
     * @param mixed $validatorOptions
     *            Additionnal option of validator (eg: messages, string max length,...)
     * @return bool @throw Exception\ApiException
     */
    protected function validateData(string $expectedType, $data, array $validatorOptions)
    {
        $validator = new Validation();
        $expectedType = strtolower($expectedType);
        
        switch ($expectedType) {
            case 'int':
                $validatorClassName = 'Digit';
                break;
            case 'numeric':
                $validatorClassName = 'Numericality';
                break;
            case 'alphanumeric':
                $validatorClassName = 'Alnum';
                break;
            case 'string':
                $validatorClassName = 'StringLength';
                break;
            case 'date':
                $validatorClassName = 'Date';
                break;
            case 'email':
                $validatorClassName = 'Email';
                break;
            default:
                throw new CoreException(sprintf("Unknown validation type %s %s", $expectedType, __FUNCTION__));
                break;
        }

        $className="Phalcon\Validation\Validator\\$validatorClassName";
        $validator->add("data",new $className($validatorOptions));
        
        $entity=new \stdClass();
        $entity->data=$data;
        
        $messageObject=$validator->validate($entity);
        
        if (count($messageObject)>0) {
            throw new ApiException($messageObject[0], 400);
        } else {
            return true;
        }
    }
}

