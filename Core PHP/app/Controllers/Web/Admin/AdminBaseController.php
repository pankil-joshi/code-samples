<?php

namespace App\Controllers\Web\Admin;

use Quill\Factories\CoreFactory;
//use Quill\Factories\ServiceFactory;
use Quill\Factories\ModelFactory;

class AdminBaseController extends  \App\Controllers\BaseController{
    
    function __construct($app = NULL) {

        parent::__construct($app);
        
        if(!isset($_SESSION['user_data']) && !isset($_SESSION['user_data']['user_id'])) {
            session_destroy();
            $this->slim->redirect($this->app->config('base_url') . 'admin/login/');
        }
        
    }    
}



