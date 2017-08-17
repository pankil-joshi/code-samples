<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Quill\Database as Database;

/**
 * Description of ItemTax
 *
 * @author harinder
 */
class ItemTax extends Database{

    public function getById($id) {
        
        return array('name' => '', 'rate' => 0);
        
    }
    
    public function getName($id) {
        
        return $this->getCache($id)['name'];
        
    }
   
    public function getRate($id) {
        
        return $this->getCache($id)['rate'];
        
    }
    
}
