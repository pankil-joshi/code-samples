<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;


use Quill\Database as Database;

class UserTaxTemplate extends Database {
    
    public $tableName = 'user_tax_templates';
    
    public function remove($id, $userId) {
        
        return $this->table()->where(array('id' => $id, 'user_id' => $userId))->delete();      
        
    }
    
    public function save($taxTemplate, $userId) {
        
        if(!empty($taxTemplate['id'])) {
            
            $taxTemplate['code'] = snake_case($taxTemplate['label']);
            
            $taxTemplate['updated_at'] = gmdate('Y-m-d H:i:s');
            
            return $this->table()->where(array('id' => $taxTemplate['id'], 'user_id' => $userId))->update($taxTemplate, true);
            
        } else {
            
            $taxTemplate['updated_at'] = gmdate('Y-m-d H:i:s');
            $taxTemplate['created_at'] = gmdate('Y-m-d H:i:s');
            
            $taxTemplate['code'] = snake_case($taxTemplate['label']);
            
            return $this->table()->insert($taxTemplate, true);
            
        }
        
    }

    public function getById($id) {
        
        return $this->table()->select()->where(array('id' => $id, 'user_id' => $userId))->one();
        
    }     
    
    public function getAllByUserId($userId) {
        
        return $this->table()->select('*')->where(array('user_id' => $userId))->all();
        
    }    
}
