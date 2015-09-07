<?php

class InputFormatException extends Exception
{

    protected $error_message;

    public function __construct($msg, $errornumber = null)
    {
        $this->error_message = $msg;
    }

    public function getErrorMessage()
    {
        return $this->error_message;
    }

}