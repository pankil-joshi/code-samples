<?php

namespace App\Models;

use Quill\Database as Database;

class MediaMediaCategories extends Database{
    
    public $tableName = 'media_media_categories';
    
    public function save($mediaCategory) {
               
        return $this->table()->insert($mediaCategory);
        
    }
    
    public function clear($mediaId) {
        
        $this->table()->where(array('media_id' => $mediaId))->delete();
        
    }

}