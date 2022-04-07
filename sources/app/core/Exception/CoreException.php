<?php
declare(strict_types = 1);

namespace Exception;

class CoreException extends \Exception
{
    protected $authorizedCodes=[
        500
    ];
    
    public function __construct ($message = null, $code = 500, $previous = null) {
        parent::__construct($message,$code,$previous);
    
        if(!in_array($code,$this->authorizedCodes)){
            throw new \Exception(sprintf("Unsupported exception error code: %d", $code));
        }
    }
}

