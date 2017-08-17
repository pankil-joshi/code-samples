<?php

namespace App\Models;

use Quill\Database as Database;

class AdminUser extends Database{
    
    protected $tableName = 'admin_user';
    
    protected $primarykey = 'user_id';


    public function getUserDetailsByUsername($user_name) {
        return $this->table()->select()
                ->where(array('username' => $user_name, 'is_active' => '1'))
                ->one();
    } 
}