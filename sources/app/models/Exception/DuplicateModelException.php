<?php
declare(strict_types = 1);

namespace Exception;

class DuplicateModelException extends \Exception
{

    protected string $subject = '';

    /**
     * Constructor
     * @param string $message            
     * @param int $code            
     * @param mixed $previous            
     */
    public function __construct(string $message = '', int $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
        
        if ($code == "23000") {
            $info = sscanf($message, "SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '%s' for key
'reference_UNIQUE'");
            $info = array_pop($info);
            if (! is_null($info)) {
                $this->subject = $info;
            }
        }
    }

    /**
     * Subject of duplication
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }
    
    /**
     * Check Error type
     * @param string $message
     * @return string
     */
    public static function isDuplicateError(string $message): bool
    {
        return stripos($message,"Duplicate entry")!== false;
    }
}

