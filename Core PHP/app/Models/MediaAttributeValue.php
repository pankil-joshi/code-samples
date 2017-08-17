<?php

namespace App\Models;

use Quill\Database as Database;

class MediaAttributeValue extends Database{
    
    public $tableName = 'media_attribute_values';
    
    public function getByAttributeIdMediaId($attributeId, $mediaId) {
        
        return $this->table()->select()->where(array('attribute_id' => $attributeId, 'media_id' => $mediaId))->one();
        
    }
    
    public function save($attributeValues) {
               
        return $this->table()->upsert($attributeValues); 
        
    }

}