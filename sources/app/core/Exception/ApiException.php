<?php
namespace Exception;

class ApiException extends \Exception
{
    protected static array $authorizedCodes=[
        400,
        401,
        403,
        404,
        409,
        503
    ];

    public function __construct ($message = null, $code = null, $previous = null) {
        parent::__construct($message,$code,$previous);
        
        if(!in_array($code, static::$authorizedCodes)){
            throw new CoreException(sprintf("Unsupported api exception error code: %d", $code));
        }
    }
    
    /**
     * Validate api response code
     * @param mixed $code
     * @return true;
     */
    public static function validateCode($code){
        return in_array($code, static::$authorizedCodes);
    }
}

