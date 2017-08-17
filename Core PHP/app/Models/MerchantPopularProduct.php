<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Quill\Database as Database;

/**
 * Description of MerchantPopularProduct
 *
 * @author harinder
 */
class MerchantPopularProduct extends Database{
    
    public $tableName = 'merchant_popular_products';
    
    public function getAllByUserId($userId) {
        
        return $this->table()->select()->where(array('user_id' => $userId))->all();
        
    }    
    
}
