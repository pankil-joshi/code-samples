<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Quill\Database as Database;
/**
 * Description of PushNotification
 *
 * @author harinder
 */
class PushNotification extends Database {
    
    public $tableName = 'push_notifications';
    
    public function save($notification) {

        if(!empty($notification['id'])) {
            
            $notification['updated_at'] = gmdate('Y-m-d H:i:s');
            
            return $this->table()->where(array('id' => $notification['id']))->update($notification);
            
        } else { 
            
            $notification['updated_at'] = gmdate('Y-m-d H:i:s');
            $notification['created_at'] = gmdate('Y-m-d H:i:s');    
            
            return $this->table()->insert($notification); 
            
        }
        
    }
}
