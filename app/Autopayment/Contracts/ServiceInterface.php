<?php

namespace App\Autopayment\Contracts;


interface ServiceInterface {

    public function inquiry();

    public function payment();

    public function setParams(array $params);
    
    public function getParams();

    public function setErrors($message, $error_code);

    public function getErrors();
}