<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services;

//use Quill\Factories\CoreFactory;
//use Quill\Exceptions\BaseException;

/**
 * Description of Stripe
 *
 * @author harinder
 */
class Wordpress {
    
    public function loadBlogHeader () {
        
        $this->config = load_config_one('path');

        require $this->config['document_root'] . '/blog/wp-blog-header.php';        
    }
}
