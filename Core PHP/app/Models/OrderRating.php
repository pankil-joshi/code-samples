<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

/**
 * Description of MediaRating
 *
 * @author harinder
 */

use Quill\Database as Database;

class OrderRating extends Database{
    
    protected $tableName = 'order_ratings';
    
    public function save($rating) {
        
        $rating['rated_at'] = gmdate('Y-m-d H:i:s');           
            
        return $this->table()->upsert($rating);
        
    }
    
    public function getAverageByMerchantId($userId) {         
            
        return $this->table()->select('avg(rating)')
                ->join('order_suborders', $this->tableName . '.suborder_id', 'order_suborders.id')
                ->where(array('order_suborders.merchant_id' => $userId))->field();
        
    }
}
