<?php

namespace App\Models;

use Quill\Database as Database;

class MediaAttributes extends Database{
    
    public $tableName = 'media_attributes';

    public function getById($attributeId) {
        
        return $this->table()->select()->where(array('id' => $attributeId))->one();
        
    }
    
    public function getAll($mediaId) {
        
        return $this->table()
                
                ->select('media_attributes.*')
                
                ->selectGroup(array('value'), 'attribute_values', 'attribute')
                
                ->join('media_attribute_values as attribute_values', 'media_attributes.id', 'attribute_values.attribute_id')
                
                ->where(array('attribute_values.media_id' => $mediaId))->all();
        
    } 

}