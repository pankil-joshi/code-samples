<?php

/*
 * This file is to setup all preleminary variables and instantiation of required classes.
 */

$requestId = uniqid(); // Genrate unique identifier to seprate all http requests.

/*
 * Custom exception handler function, prints exception message only.
 * @param type $exception
 */

function exception_handler($exception) {
    
  echo "Uncaught exception: " , $exception->getMessage(), "\n";
  
}

set_exception_handler('exception_handler'); //Set our custom exception handler function as default exception handler, throuhout the execution. 

$path['app'] = __DIR__.'/../app/'; // Set 'app' directory root path.

$path['logs'] = __DIR__.'/../logs/'; // Set 'logs' directory base path.

$app = new Quill\Core(); // Initialize application core object.

$app->is_callback = false;

$app->requestId = $requestId; // Store request id for further use in the application.

$app->userLogger = new Quill\Logger($path['logs'], $requestId); // Instantiate and store logger class to log user activities.

$app->instagramLogger = new Quill\Logger($path['logs'], $requestId); // Instantiate and store logger class to log instagram activities.

$app->globalLogger = new Quill\Logger($path['logs'], $requestId); // Instantiate and store logger class to log instagram activities.

require __DIR__.'/./slim/init.php'; // Load Slim framework.

