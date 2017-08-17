<?php

namespace App\Helpers;

class Response {
    
    const SUCCESS_CODE = 200;
    const FAILURE_CODE = 500;
    
    public function json() {
        
        return $this;
    }
    
    public function success($data = null, $code = self::SUCCESS_CODE) {
        
        $response = array();
        $response['meta'] = array('success' => true, 'code' => $code);
        $response['data'] = $data;
        
        return response()->json($response, $code);
    }    
    
    public function error($data = null, $code = self::FAILURE_CODE) {
        
        $response = array();
        $response['meta'] = array('success' => false, 'code' => $code);
        $response['data']['errors'] = $data;
        
        return response()->json($response, $code);
    }
    
}
