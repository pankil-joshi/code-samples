<?php

namespace App\Models;

use Quill\Database as Database;

class GeneralSetting extends Database{
    
    public $tableName = 'general_settings';
    
    public function getMintagId() {
        
        return $this->table()->select('min_tag_id')->where(array('id' => 1))->field();
        
    }
    
    public function save($setting) {

        return $this->table()->where(array('id' => 1))->update($setting, true);
    }    
    
}
