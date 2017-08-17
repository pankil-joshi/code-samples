<?php

/**
 * Factory
 * 
 * @package Quill
 * @author Pankil Joshi <pankil@prologictechnologies.in>
 * @version 1.0
 */

namespace Quill;

class Factory {
    
    /**
     * @var object 
     * @static
     */
    
    private static $objects;
    
    /**
     * @var string 
     * @static
     */
    
    protected static $namespace;

    /**
     * Boot method to instantiate classes.
     * 
     * @param string $classNames
     * @param string|array $namespace
     * @param mixed $args
     * @return object
     * @throws \RuntimeException If class doesn't exist.
     */
    
    public static function boot($classNames, $namespace = '', $args = Null) {
        
        self::$objects  = new \StdClass;
        
        if(is_array($classNames)) {
            
            foreach ($classNames as $className) {
                
                $name = lcfirst($className);
                
                $className = $namespace . $className;
                
                if(class_exists($className)) {

                    self::$objects->$name =  new $className();

                } else {
                
                    throw new \RuntimeException('Class '.$className.' not found');

                }    
                
            }
            
        } else {
            
            $name = lcfirst($classNames);
            
            $className = $namespace . $classNames;
            
            if(class_exists($className)) {
                
                self::$objects->$name =  new $className($args);

            } else {
                
                throw new \RuntimeException('Class '.$className.' not found');
            
            }         
            
        }

        return self::$objects;
        
    }
    
    /**
     * Set namespace classes.
     * 
     * @param string $namespace
     * @abstract
     */
    
    public static function setNamespace($namespace) {
        
        self::$namespace = $namespace;
        
    }
    
    /**
     * Get namespace of model classes.
     * 
     * @return string
     * @throws \RuntimeException If model namespace is not set.
     */
    
    public static function _getNamespace() {
        
        if (!empty(self::$namespace)) {
            
            return self::$namespace;
            
        } else {
            
            throw new \RuntimeException('Namespace not set.');
            
        }
        
    }    
    
}