<?php

class ModelResponse extends Model
{

    private $success;
    private $message;
    private $data;

    public function __construct($is_success, $message, Model $data = null)
    {
        $this->success = $is_success;
        $this->message = $message;
        $this->data    = $data;
    }

    /**
     * @property success
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @property message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @complex
     * @property data
     */
    public function getData()
    {
        return $this->data;
    }

    public static function busy()
    {
        return new ModelResponse(false, 'System is Busy');
    }

}

?>