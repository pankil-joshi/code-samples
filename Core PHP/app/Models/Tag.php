<?php

namespace App\Models;

use Quill\Database as Database;

class Tag extends Database{
    
    public $tableName = 'user_tags';
    
    public function save($tag) {
        
        if(!empty($tag['id'])) {
            
            $tag['updated_at'] = gmdate('Y-m-d H:i:s');      
            
            return $this->table()->where(array('id' => $tag['id']))->update($tag);
            
        } else {
            
            $tag['created_at'] = gmdate('Y-m-d H:i:s');           
            
            return $this->table()->insert($tag); 
            
        }
        
    }

    public function remove($id, $userId) {
        
        return $this->table()->where(array('id' => $id, 'user_id' => $userId))->delete();      
        
    }
    
    public function getByUserId($userId) {
        
        return $this->table()->select()->where(array('user_id' => $userId))->one();
        
    }
    
    public function getAllByUserId($userId) {
        
        return $this->table()->select()->where(array('user_id' => $userId))->all();
        
    }    

}