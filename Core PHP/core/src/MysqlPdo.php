<?php

namespace Quill;

use Quill\Response as Response;
use Quill\Config as Config;



class MysqlPdo{
    
    public  $connection, $response, $config;
    
    private function _getConfig() {
        
        $this->config = new Config();
        
        return $this->config->loadOne('database');
        
    }


    private function _createConnection() {
        
        $this->response = new Response();
        
        $config = $this->_getConfig();

        try {
//            echo "connection...\n";
            $this->connection = new \PDO('mysql:host='.$config['host'].';dbname='.$config['database_name'].';charset=utf8', $config['database_username'], $config['database_password']);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
        } catch (\Exception $exception) {
            
            throw new \Exception('Database connection error: ' . $exception->getMessage());
            
        }
        
    }
    
    public function getConnection() {
        
        if (!$this->connection) {
            
            $this->_createConnection();
            
        }
        
        return $this->connection;
        
    }
}