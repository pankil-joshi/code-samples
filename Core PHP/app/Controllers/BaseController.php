<?php

namespace App\Controllers;

use Quill\Factories\CoreFactory; 
use Quill\Factories\ServiceFactory;
use Quill\Factories\ModelFactory;
use Quill\Factories\RepositoryFactory;
use Quill\Exceptions\BaseException;

/**
 * Base Controller to be extended by all controllers of the project.
 * 
 * @package App\Controllers
 * @author Pankil Joshi <pankil@prologictechnologies.in>
 * @version 1.0
 * @uses Quill\Factories\CoreFactory
 * @uses Quill\Factories\ServiceFactory
 * @uses Quill\Factories\ModelFactory
 * @uses Quill\Factories\RepositoryFactory
 * @uses Quill\Exceptions\BaseException
 */

class BaseController {
    
    /**
     *
     * @var object It contains instance of Quill\Core class.
     */
    protected $app; 
    
    /**
     *
     * @var object It contains instance of \Slim\Slim class.
     */
    protected $slim; 
    
    /**
     *
     * @var object It contains instance of \Slim\Http\Request class.
     */
    protected $request;
    
    /**
     *
     * @var array It contains loaded configuration variables. 
     */
    protected $config;
    
    /**
     *
     * @var object This property contains logger object.
     */
    protected $userLogger;
    
    /**
     *
     * @var object This property contains logger object.
     */    
    protected $instagramLogger;
    
    /**
     *
     * @var object This property contains logger object.
     */     
    protected $mediaLogger;
    
    /**
     *
     * @var object This property contains core classes objects.
     */    
    protected $core;
    
    /**
     *
     * @var object This property contains services classes objects.
     */    
    protected $services;
    
    /**
     *
     * @var object This property contains repository classes objects.
     */    
    protected $repositories;
    
    /**
     *
     * @var object This property contains model classes objects.
     */    
    protected $models;
    
    function __construct($app = NULL) {   
                        
        $app->config(load_config_one('app', array('path', 'url'))); //Load app configuration variables.
        
        CoreFactory::setNamespace('\\Quill\\'); //Set framework core namespace.
        ServiceFactory::setNamespace('\\App\\Services\\'); //Set services namespace.
        ModelFactory::setNamespace('\\App\\Models\\'); //Set models namespace.
        RepositoryFactory::setNamespace('\\App\\Repositories\\'); //Set repositories namespace.
        
        if (NULL == $app) {

            throw new BaseException('Instance of app is not passed.'); 
        } else {             

            $this->app = $app;
            $app->config(load_config_one('instagram')); //Load instagram configuration variables.

            /**
             * If called from local file system or is a callback url then set the required variables.
             */
            if(!$app->config('is_cli') && !$app->is_callback) {

                $this->slim = $app->slim;
                $this->app->user = $app->slim->user;
                $this->request = $app->slim->request;
 
                $app->config(load_config_one('jwt', array('url', 'path'))); //Load jwt configuration variables, jwt configurations require url and path configuration variables.

                $this->userLogger = $app->userLogger;
                $this->userLogger->setLogSubDirectory('http/users/'); //Set user logger directory.
                $this->globalLogger = $app->globalLogger;
                $this->globalLogger->setLogSubDirectory('http/global/'); //Set user logger directory.                
                $this->instagramLogger = $app->instagramLogger;        
                $this->instagramLogger->setLogSubDirectory('http/instagram/'); //Set instagram logger directory.
            } 
        }
    }
    
    
    /**
     * This medthod is used to set local properties.
     * 
     * @param string $key
     * @param mixed $value
     */
    protected function set($key, $value) {
        
        $this->$key = $value;
    }
    
    /**
     * This medthod is used to get local properties.
     * 
     * @param string $key
     */    
    protected function get($key) {
        
        if($this->has($this->$key)) {
            
            return $this->$key;
        }
    }
    
    /**
     * This medthod is used to check if local property exists.
     * 
     * @param string $property
     */    
    protected function has($property) {
        
        if(!empty($property)) {
            
            return true;
        } else {
            
            return false;  
        }
    }
}