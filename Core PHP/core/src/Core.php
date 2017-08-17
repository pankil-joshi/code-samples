<?php

/**
 * Core
 * 
 * @package Quill
 * @author Pankil Joshi <pankil@prologictechnologies.in>
 * @version 1.0
 */

namespace Quill;

class Core {    
    
    /**
     * Store confiurations.
     * @var array 
     */
    
    private $config = array();
            
    public function __set($name, $value) {
        
        $this->$name = $value;
        
    }
    
    /**
     * Set and/or get configuration variables.
     * 
     * @param string|array $name String if want to set or get one configuration variable, array if you want to set array of configuration variables.
     * @param string $value (optional) Value to be set for specified configuration variable.
     * @return string Get will of specified variable if no value parameter specified.
     * @uses is_array()
     * @uses func_num_args()
     */
    
    public function config($name = null, $value = NULL) {
        
        if(func_num_args() === 0) {
            
            return $this->config;
            
        } else if(is_array($name)) {
            
            $this->config = $this->config + $name;
            
        } else if(func_num_args() === 1) {
            
            return isset($this->config[$name]) ? $this->config[$name] : null;
            
        } else {
            
            $this->config[$name] = $value;
            
        }
        
    }
    
    /**
     * Get array of configuration variables.
     * 
     * @return array
     */
    
    public function configAll() {
        
        return $this->config;
        
    }
    

}