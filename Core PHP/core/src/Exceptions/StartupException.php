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
class StartupException extends \Quill\Exceptions\BaseException{

    private $meta = array();
    private $type;

    public function __construct($message = 'Startup error.', $type = '', $meta = array()) {

        parent::__construct($message);
        
        $this->meta = $meta;
        $this->type = $type;
    }

    public function getMeta() {
        
        return $this->meta;
        
    }
    
    public function getType() {
        
        return $this->type;
        
    }    

}
