<?php

if(empty($_SERVER['HTTP_HOST']) && !empty($argv[1]) && $argv[1] == 'staging') {
    
    $_SERVER['HTTP_HOST'] = 'staging.tagzie.com';
} elseif(empty($_SERVER['HTTP_HOST']) && !empty($argv[1]) && $argv[1] == 'production') {
    
    $_SERVER['HTTP_HOST'] = 'www.tagzie.com';
} 

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/helpers.php';