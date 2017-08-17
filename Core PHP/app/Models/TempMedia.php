<?php

namespace App\Models;

use Quill\Database as Database;

class TempMedia extends Database{
    
    public $tableName = 'temp_media';

    public function save($media) {
        
        $subscriptionPackage['created_at'] = gmdate('Y-m-d H:i:s'); 
        
        return $this->table()->insert($media);
                    
    }
    
    public function getAll() {
        
        return $this->table()->select()->where(array())->all();
        
    }
    
    public function deleteRow($mediaId) {
        
        return $this->table()->select()->where(array('media_id' => $mediaId))->delete();
        
    } 
   
}