<?php
namespace J3dyy\CrudHelper\Exceptions;


use Exception;
use Throwable;

class InvalidCastingException extends Exception
{

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
