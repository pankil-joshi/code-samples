<?php

namespace App\Models;

use Quill\Database as Database;

class MessageRecent extends Database{
    
    public $tableName = 'messages_recent';
    
    public function getAllByUserId($userId) {
        
        return $this->table()->select()->orWhere(array('sender_id' => $userId, 'recipient_id' => $userId))->all();
        
    }
    
}
