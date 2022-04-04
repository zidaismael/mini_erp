<?php
namespace Exception;

class DuplicateModelException extends \Exception
{

    protected string $subject = '';

    /**
     *
     * @param string $message            
     * @param mixed $code            
     * @param mixed $previous            
     */
    public function __construct(string $message = null, $code = null, $previous = null)
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
     * subject of duplication
     *
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

