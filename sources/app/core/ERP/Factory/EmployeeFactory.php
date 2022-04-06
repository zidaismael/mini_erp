<?php
namespace ERP\Factory;

use \ERP\Employee;
use \EmployeeModel;

class EmployeeFactory
{
    
    /**
     * Build employee
     * @param EmployeeModel $result
     * @return array
     */
    public static function build(EmployeeModel $result): Employee{
        return new Employee($result->reference);
    }
    
    /**
     * Build employees collection
     * @param EmployeeModel $result
     * @return array
     */
    public static function buildEmployees(EmployeeModel $result): array{
        $employeeList=[];
        
        $list=$result->toArray();
        if(empty($list)){
            return [];
        }else{
            foreach($list as $employeeData){
                $employeeList[]=new Employee($employeeData['reference']);
            }
        }
        
        return $employeeList;
    }
}

