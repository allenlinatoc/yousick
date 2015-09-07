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
    public function IsSuccess()
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

    static public function Busy()
    {
        return new ModelResponse(false, 'Service is not available. Please try again later.');
    }
    static public function DataSaveFailed()
    {
        return new ModelResponse(false, "Failure on data saving");
    }
    static public function InvalidRequest()
    {
        return new ModelResponse(false, "Invalid HTTP request. Please check and try again");
    }
    static public function NoData()
    {
        return new ModelResponse(false, 'No data found or empty');
    }

}

?>