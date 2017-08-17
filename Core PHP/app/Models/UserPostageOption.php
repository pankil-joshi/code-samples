<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Quill\Database as Database;

class UserPostageOption extends Database {
    
    public $tableName = 'user_postage_options';
    
    public function remove($id, $userId) {
        
        return $this->table()->where(array('id' => $id, 'user_id' => $userId))->delete();      
        
    }
    
    public function save($postageOption, $userId) {
        
        if(!empty($postageOption['id'])) {
            
            $postageOption['code'] = snake_case($postageOption['label']);
            
            $postageOption['updated_at'] = gmdate('Y-m-d H:i:s');
            
            return $this->table()->where(array('id' => $postageOption['id'], 'user_id' => $userId))->update($postageOption, true);
            
        } else {
            
            $postageOption['updated_at'] = gmdate('Y-m-d H:i:s');
            $postageOption['created_at'] = gmdate('Y-m-d H:i:s');
            
            $postageOption['code'] = snake_case($postageOption['label']);
            
            return $this->table()->insert($postageOption, true);
            
        }
        
    }

    public function getById($id) {
        
        return $this->table()->select()->where(array('id' => $id, 'user_id' => $userId))->one();
        
    }     
    
    public function getAllByUserId($userId) {
        
        return $this->table()->select('*')->where(array('user_id' => $userId))->all();
        
    }    
}
