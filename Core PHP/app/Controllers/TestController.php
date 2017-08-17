<?php

namespace App\Controllers;

use Quill\Factories\ServiceFactory;

/**
 * Test Controller to be used for testiung purposes.
 * 
 * @package App\Controllers
 * @author Pankil Joshi <pankil@prologictechnologies.in>
 * @version 1.0
 * @uses Quill\Factories\ServiceFactory
 */
class TestController extends BaseController{

    function __construct($app) {
        
        parent::__construct($app);
        
        $this->services = ServiceFactory::boot(array('Apns'));
    }
    
    /**
     * Main function to be used for testing.
     * 
     * @param string $param
     */
    public function index($param='') {
        echo password_hash('123456', PASSWORD_DEFAULT);
        exit();
//        echo snake_case('Hello World', ' ');
        $data = json_decode('{"aps":{"alert":{"title":"Tagzie","body":"Complete your purchase of: Test 2","link":"ipaid:\/\/displayProduct.html#55","comment_id":"17842195876152108","badge":1},"sound":"default"}}');
        $this->services->apns->sendPushNotification($data, 'a3c6d6318a3bd9634c2fa0c6b5c0cc67396ad6baebc6fb38ce87955699d60264');
    }

}