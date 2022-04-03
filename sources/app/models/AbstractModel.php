<?php

use Phalcon\Mvc\ModelInterface;
use Exception\DuplicateModelException;

class AbstractModel extends \Phalcon\Mvc\Model
{
    
    /**
     * Assign with auto field filtering
     * @return array
     */
    public function assign(array $data, $whiteList = NULL, $dataColumnMap = NULL): ModelInterface{
        $model=new \ReflectionClass(static::class);
        $properties=$model->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE );
        
        $modelProperties=[];
        foreach($properties as $property){
            $modelProperties[$property->getName()]='';
        }
        
        $bind=array_intersect_key($data, $modelProperties);
        return parent::assign($data);
    }
    
    
    /**
     * Create model
     * @throws DuplicateModelException
     * @throws Exception
     * @return bool
     */
    public function create(): bool{
        try{
            return parent::create();
           // return parent::create();
        }catch(\Exception $e){
            if($e->getCode()=="23000"){
                throw new DuplicateModelException($e->getMessage(), $e->getCode());
            }else{
                throw $e;
            }
        } 
    }
    
}

