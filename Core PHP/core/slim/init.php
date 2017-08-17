<?php

//$app->slim = new \Slim\Slim(); // Intitialization of "Slim framework" into $app object.                     
$app->slim = new \Slim\Slim(array(
    'templates.path' => $path['app'].'views'
)); // Intitialization of "Slim framework" into $app object.   
if($_SERVER['HTTP_HOST'] == 'www.tagzie.com') {
    
    $app->slim->config('debug', false);
} else {
    
    $app->slim->config('debug', true);
}


require $path['app'].'Routes.php'; //Load routes.

$app->slim->run(); // Run application using Slim framework's "run()" method.