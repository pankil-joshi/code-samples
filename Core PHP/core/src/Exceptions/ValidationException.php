<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Quill\Exceptions;

/**
 * Description of ValidationException
 *
 * @author harinder
 */
class ValidationException extends \Quill\Exceptions\BaseException{

    private $errors = array();
    private $type = 'validation';

    public function __construct($errors = array()) {

        parent::__construct('Data validation error!', $errors);
        
        $this->errors = $errors;
    }

    public function getArray() {
        
        return $this->errors;
        
    }
    
    public function getType() {
        
        return $this->type;
        
    }    

}
