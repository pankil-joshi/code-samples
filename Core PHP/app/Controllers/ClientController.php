<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controllers;

/**
 * Description of ClientController
 *
 * @author harinder
 */
class ClientController extends BaseController{
    
    function __construct($app = NULL) {
        
        parent::__construct($app);
        
    }
    public function saveLog() {

        $clientLog =  $this->request->getBody();
        
        $clientLog .= PHP_EOL . "---";

        file_put_contents($this->app->config('client_log_directory') .  DIRECTORY_SEPARATOR . gmdate('Y-m-d-h-i-s') . '.log', $clientLog, FILE_APPEND);
    }
}
