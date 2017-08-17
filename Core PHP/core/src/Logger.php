<?php

namespace Quill;

use Exception;
use RuntimeException;
use DateTime;
use DateTimeZone;

use Quill\Config as Config;

class Logger {
    
    private $logDirectory, $logSubDirectory, $config, $requestId;
            
    function __construct($directory = '', $requestId = '') {
        
        if (!empty($directory)) {
            
            $this->setLogDirectory($directory);
            
        } else {
            
            $this->setLogDirectory( __DIR__.'/../../logs/');
            
        }
        
        if (!empty($requestId)) {
            
            $this->setRequestId($requestId);
            
        }
        
        $this->config = new Config();
        
    }
    
    public function log($level, $messgae, $filenamePrefix = '',  $data = array()) {
        
        $config = $this->_getConfig();
        
        if(empty($this->_getRequestId())){
            
            throw new RuntimeException('Request id not set');
        
        }
        
        if($config['log_enabled'] === TRUE) {
            
            $jsonArray['request_id'] = $this->_getRequestId();
            $jsonArray['level'] = $level;
            $jsonArray['message'] = $messgae;
            $jsonArray['data'] = $data;

            try {

                $date = new DateTime('now', new DateTimeZone('UTC'));

                $jsonArray['time'] = $date->format(DateTime::ATOM);

            } catch (Exception $e) {

                throw new RuntimeException($e->getMessage());

            }        

            $jsonString = json_encode($jsonArray).PHP_EOL;

            if (!empty($this->logDirectory)) {

                $logDirectory = $this->logDirectory . DIRECTORY_SEPARATOR . $this->logSubDirectory;

                if (!is_dir($logDirectory)){   

                    throw new RuntimeException('Log directory "'.$logDirectory.'" doesn\'t exists.');

                } else {

                    $filename = (!empty($filenamePrefix))? $filenamePrefix. '-' . $date->format('Y-m-d').'.log': $date->format('Y-m-d').'.log';

                    try {

                        file_put_contents($logDirectory.$filename, $jsonString, FILE_APPEND);

                    } catch (Exception $e) {

                        throw new RuntimeException($e->getMessage());

                    }

                }            

            } else {

                throw new Exception('Log directory is not set.');

            }            
            
        }
        
    }
    
    public function setLogDirectory($directory) {
        
        $this->logDirectory = $directory;
        
    }
    
    public function setLogSubDirectory($directory) {
        
        $this->logSubDirectory = $directory;
        
    }
    
    private function _getConfig() {
        
        return $this->config->loadOne('logging');
        
    }
    
    public function setRequestId($requestId) {
        
        $this->requestId = $requestId;
        
    }
    
    private function _getRequestId() {
        
        return $this->requestId;
        
    }

}