<?php

function output() {
    
    $response = new App\Helpers\Response();
    
    return $response;
}

function ec(&$variable, $defaultValue = '') {
    
    return !empty($variable) ? $variable : $defaultValue;
}

