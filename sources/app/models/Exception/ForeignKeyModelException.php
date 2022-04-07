<?php
declare(strict_types = 1);

namespace Exception;

class ForeignKeyModelException extends \Exception
{

    /**
     * Check Error type
     * @param string $message
     * @return string
     */
    public static function isIntegrityError(string $message): bool
    {
        return stripos($message,"foreign key")!== false;
    }
}

