<?php

/**
 * Core Factory
 * 
 * @package Quill\Factories
 * @author Pankil Joshi <pankil@prologictechnologies.in>
 * @version 1.0
 */

namespace Quill\Factories;

use Quill\Factory;

class CoreFactory extends Factory{    

    /**
     * @var string 
     * @static
     */
    
    protected static $namespace;
    
    /**
     * Instantiate core classes.
     * 
     * @param string|array $classNames
     * @param mixed $args
     * @return object
     */
    
    public static function boot($classNames, $namespace = '', $args = Null) {
        
        return parent::boot($classNames, self::_getNamespace() . $namespace, $args);
        
    }
    
    /**
     * Set namespace of core classes.
     * 
     * @param string $namespace
     */
    
    public static function setNamespace($namespace) {
        
        self::$namespace = $namespace;
        
    }
    
    /**
     * Get namespace of core classes.
     * 
     * @return string
     * @throws \RuntimeException If core namespace is not set.
     */
    
    public static function _getNamespace() {
        
        if (!empty(self::$namespace)) {
            
            return self::$namespace;
            
        } else {
            
            throw new \RuntimeException('Core namespace not set.');
            
        }
        
    }

}