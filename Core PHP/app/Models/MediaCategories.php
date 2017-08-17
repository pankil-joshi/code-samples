<?php

namespace App\Models;

use Quill\Database as Database;

class MediaCategories extends Database{
    
    public $tableName = 'media_categories';

    public function getAll() {
        
        return $this->table()->select()->where(array('active' => 1))->all();
        
    } 
    
    public function getAllByMediaId($mediaId) {
        
        return $this->table()->select()
                ->join('media_media_categories', 'media_categories.id', 'media_media_categories.category_id')
                ->where(array('media_media_categories.media_id' => $mediaId))->all();
        
    }
    
    public function getAllParent() {
        
        return $this->table()->select()
                ->where(array('parent_id' => 0, 'active' => 1))->all();
        
    } 

}