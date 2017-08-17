<?php

namespace App\Models;

use Quill\Database as Database;

class MediaTypes extends Database{
    
    public $tableName = 'media_types';

    public function getAll() {
        
        return $this->table()->select()->where(array())->all();
        
    }    

}