<?php

namespace App\Autopayment;

/**
* 
*/
abstract class Service
{
    public $params;
    public $service;
    public $errors;
    
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setErrors($message, $error_code)
    {
        $this->errors = ['error_code' => $error_code, 'message' => $message];
        return $this;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}