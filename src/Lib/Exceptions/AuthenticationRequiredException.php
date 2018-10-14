<?php

namespace Symphony\Shell\Lib\Exceptions;

class AuthenticationRequiredException extends \Exception
{
    public function __construct($message = "Command requires authentication.", $code = 0, Exception $previous = null)
    {
        return parent::__construct($message, $code, $previous);
    }
}
