<?php

namespace Quill;

class Response {
    
    const SUCCESS_CODE = 200;
    const FAILURE_CODE = 500;

    function __construct() {      
        
    }
    
    public function json(Array $data, $print = TRUE, Array $meta = array()) {
        
        $meta['success'] = (isset($meta['success']))? $meta['success'] : TRUE; 
        
        $this->_setContentTypeJson();
        
        if(empty($meta['code'])) $meta['code'] = ($meta['success'])? self::SUCCESS_CODE : self::FAILURE_CODE;
        
        $this->_setHttpStatusCode($meta['code']);
        
        $response = array();
        $response['meta'] = $meta;
        $response['data'] = $data;
        
        if($print){ 
            
            echo $this->prepareJsonFromArray($response);
            
        } else { return $this->prepareJsonFromArray($response); }
        
    }
    
    private function _setContentTypeJson() {
        
        header('Content-Type: application/json');
        
    }
    
    private function _setHttpStatusCode($code) {
        
        http_response_code($code);
        
    }
    
    public function prepareJsonFromArray(array $array) {
        
        return json_encode($array);
        
    }

}