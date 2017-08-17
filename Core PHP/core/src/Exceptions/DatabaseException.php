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
class DatabaseException extends \Exception{

    private $errors = array();
    private $type = 'database';

    public function __construct($message, $errors = array()) {

        parent::__construct($message);
        
        $this->errors = $errors;

        $pathConfig = load_config_one('path');
        $requestId = uniqid();
        $logData = array();
        $logData['error'] = array('message' => $message, 'backtrace' => parent::getTrace());
        $globalLogger = new \stdClass();
        $globalLogger = new \Quill\Logger($pathConfig['document_root'] . '/logs', $requestId);
        $globalLogger->setLogSubDirectory('http/global/');
        $globalLogger->log('error', 'Database error: ', 'global', $logData);
    }

    public function getArray() {
        
        return $this->errors;
        
    }
    
    public function getType() {
        
        return $this->type;
        
    }    

}
