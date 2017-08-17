<?php

/**
 * Configurations
 * 
 * @package Quill\Factories
 * @author Pankil Joshi <pankil@prologictechnologies.in>
 * @version 1.0
 */

namespace Quill;

class Config {
    
    /**
     * Constructor 
     * 
     * Check if configuration direcory is set while instantiation, else set configuration directory: '__DIR__.'/../../config/'.
     * 
     * @param string $path Configuration directory.
     */      
    
    function __construct($path='') {
        
        $this->path = $path ? $path : __DIR__.'/../../config/'; 
        
    }
    
    /**
     * Load all configuration variables from specified configuration direcoty.
     * 
     * @return array Array of configuration variables.
     * @uses \RecursiveIteratorIterator()
     * @uses \RecursiveDirectoryIterator()
     * @uses \RecursiveIteratorIterator()
     */
    
    public function load() {
        
        $path = $this->path;

        $objects = new \RecursiveIteratorIterator(
                
                new \RecursiveDirectoryIterator(
                        
                        $path, 
                        \RecursiveDirectoryIterator::SKIP_DOTS), 
                \RecursiveIteratorIterator::SELF_FIRST
               
                );
        
        foreach($objects as $object){
            
            if(strcasecmp($object->getBasename('.php'), 'app') === 0)   {
                               
                $config = include $object->getRealPath();
                
                continue;
                
            }               
            
            if(strcasecmp($object->getExtension(), 'php') === 0)   {

                $config[$object->getBasename('.php')] = include $object->getRealPath();

            }              
                     
        }
        
        return $config;
        
    }
    
    /**
     * Load configuration variables from specified file and configuration direcoty.
     * 
     * @param string $file Name of the configuration file to be loaded, without '.php' extention.
     * @param array $require (optional) Required configuration variables for specified configuration file.
     * @return array Array of configuration variables.
     */
    
    public function loadOne($file, $require = array()) {

        $config = array();
        
        if(!empty($require)) {
            
            foreach ($require as $name) {

                $fileName = $this->path.$name.'.php';

                if(is_readable($fileName)){

                    $config += (include realpath($fileName));

                }                 
                
            }

            $fileName = $this->path.$file.'.php';

            $config = $config + (include realpath($fileName));
            
        } else {
            
            $fileName = $this->path.$file.'.php';

            if(is_readable($fileName)){

                $config = include realpath($fileName);

            }            
            
        } 
        
        return $config;
        
    }
    
    /**
     * Load configuration variables from specified array of files and configuration direcoty.
     * 
     * @param array $files Array of names of the configuration files to be loaded, without '.php' extention.
     * @return array Array of configuration variables.
     */
    
    public function loadMulti($files) {
        
        $config = array();
        
        foreach ($files as $file) {
            
            $file = $this->path.$file.'.php';

            if(is_readable($file)){

                $config = $config + (include realpath($file));

            }              
            
        }   
        
        return $config;
        
    }

}