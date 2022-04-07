<?php
declare(strict_types = 1);

use Phalcon\Mvc\ModelInterface;
use Phalcon\Mvc\Model\Transaction\Manager;
use Exception\DuplicateModelException;
use Exception\ForeignKeyModelException;

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
        
        //exclude reference column update because of app internal management
        $bind=array_diff_key($bind,['reference'=>'']);

        return parent::assign($bind);
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
        }catch(\Exception $e){
             $this->handleException($e);
        } 
    }
    
    /**
     * Update model
     * @throws DuplicateModelException
     * @throws Exception
     * @return bool
     */
    public function save(): bool{
        try{
            return parent::save();
        }catch(\Exception $e){
            $this->handleException($e);
        }
    }
    
    public function getTransaction(){
        $manager=new Manager();
        return $manager->get();
    }
    
    /**
     * Handle exception to throw specific exceptions when necessary
     * @param \Exception $e
     * @throws DuplicateModelException
     * @throws ForeignKeyModelException
     * @throws \\Exception
     */
    protected function handleException(\Exception $e){
        if($e->getCode()=="23000"){
            $errorMessage=$e->getMessage();
        
            if(DuplicateModelException::isDuplicateError($errorMessage)){
                throw new DuplicateModelException($errorMessage, (int) $e->getCode());
            }else if(ForeignKeyModelException::isIntegrityError($errorMessage)){
                throw new ForeignKeyModelException($errorMessage,(int) $e->getCode());
            }
        }
        
        throw $e;
    }
}

