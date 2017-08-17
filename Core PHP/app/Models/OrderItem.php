<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Quill\Database as Database;

/**
 * Description of OrderItem
 *
 * @author harinder
 */
class OrderItem extends Database{
   
    public $tableName = 'order_items';

    public function save($orderItem) {
        
        if(!empty($orderItem['id'])) {
             
            return $this->table()->where(array('id' => $orderItem['id']))->update($orderItem, true);
            
        } else {             
            
            return $this->table()->insert($orderItem, true); 
            
        }
        
    }
    
    public function getBySuborderId($suborderId, $userId = null) {
        
        
        if($userId) {
            
            return $this->table()->select($this->tableName . '.*, media.is_deleted as media_deleted')
                    ->selectGroup(array('instagram_username'), 'users', 'user')
                    ->join('users', $this->tableName . '.merchant_id', 'users.id')
                    ->leftJoin('media', $this->tableName . '.media_id', 'media.id')
                    ->where(array('suborder_id' => $suborderId))->all();            
            
        } else {
            
            return $this->table()->select($this->tableName . '.*, media.is_deleted as media_deleted')
                    ->selectGroup(array('instagram_username'), 'users', 'user')
                    ->join('users', $this->tableName . '.merchant_id', 'users.id')
                    ->leftJoin('media', $this->tableName . '.media_id', 'media.id')
                    ->where(array('suborder_id' => $suborderId))->all();
            
        }
        
    }
    
    public function getOneBySuborderId($suborderId, $userId = null) {
        
        return $this->table()->select()->where(array('suborder_id' => $suborderId))->one();
    }
    
    public function getTopTenItems() {
        return $this->table()->select("count({$this->tableName}.media_id) as item_ids, {$this->tableName}.media_id, {$this->tableName}.media_title")
                ->leftJoin('order_suborders', "{$this->tableName}.suborder_id", 'order_suborders.id')
                ->where(array('order_suborders.status <>' => 'pending'), true)
                ->groupBy("{$this->tableName}.media_id")
                ->orderBy("item_ids", 'desc')
                ->limit(10)
                ->all();
    }
        
}
