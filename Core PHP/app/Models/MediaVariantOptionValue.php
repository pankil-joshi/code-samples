<?php

namespace App\Models;

use Quill\Database as Database;

class MediaVariantOptionValue extends Database{
    
    public $tableName = 'media_variant_option_values';

    public function save($value) {
        
        return $this->table()->upsert($value); 
            
    }

}