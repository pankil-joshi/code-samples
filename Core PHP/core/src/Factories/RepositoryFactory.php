<?php

/**
 * Repository Factory
 * 
 * @package Quill\Factories
 * @author Pankil Joshi <pankil@prologictechnologies.in>
 * @version 1.0
 */

namespace Quill\Factories;

use Quill\Factory;

class RepositoryFactory extends Factory{
    
    /**
     * @var string 
     * @static
     */
    
    protected static $namespace;    
    
    /**
     * Instantiate repository classes.
     * 
     * @param string|array $classNames
     * @param mixed $args
     * @return object
     */    
    
    public static function boot($classNames, $namespace = '', $args = Null) {
        
        return parent::boot($classNames, self::_getNamespace() . $namespace, $args);
        
    }
    
    /**
     * Set namespace of service classes.
     * 
     * @param string $namespace
     */
    
    public static function setNamespace($namespace) {
        
        self::$namespace = $namespace;
        
    }
    
    /**
     * Get namespace of service classes.
     * 
     * @return string
     * @throws \RuntimeException If service namespace is not set.
     */
    
    public static function _getNamespace() {
        
        if (!empty(self::$namespace)) {
            
            return self::$namespace;
            
        } else {
            
            throw new \RuntimeException('Repository namespace not set.');
            
        }
        
    }

}