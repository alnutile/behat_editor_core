<?php namespace BehatApp\Exceptions;

class BehatAppException extends \Exception {

    public function errorMessage()
    {
        $errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
            .': <b>'.$this->getMessage().'</b> is not a valid E-Mail address';
        return $errorMsg;
    }
}