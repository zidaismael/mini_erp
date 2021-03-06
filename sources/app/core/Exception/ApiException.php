<?php
declare(strict_types = 1);

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

    /**
     * Constuctor
     * @param string|null $message
     * @param mixed $code
     * @param mixed $previous
     * @throws CoreException
     */
    public function __construct (string $message = null, $code = null, $previous = null) {
        parent::__construct($message,$code,$previous);
        
        if(!in_array($code, static::$authorizedCodes)){
            throw new CoreException(sprintf("Unsupported api exception error code: %d => %s", $code, $message));
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

