<?php

namespace App\Models;

use Quill\Database as Database;

class Mention extends Database{
    
    public $tableName = 'user_mentions';
    
    public function save($mention) {
        
        if(!empty($mention['id'])) {

            $mention['updated_at'] = gmdate('Y-m-d H:i:s');  
            
            return $this->table()->where(array('id' => $mention['id']))->update($mention);
            
        } else {    
            
            $mention['created_at'] = gmdate('Y-m-d H:i:s');   
            
            return $this->table()->insert($mention); 
            
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