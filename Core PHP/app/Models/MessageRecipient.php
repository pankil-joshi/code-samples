<?php

namespace App\Models;

use Quill\Database as Database;

class MessageRecipient extends Database{
    
    public $tableName = 'messages_recipients';    

    public function save($recipient) {
        
        if(!empty($recipient['read_at'])) {
            
            return $this->table()->where(array('id' => $recipient['message_id']))->update($recipient);
            
        } else {             

            return $this->table()->insert($recipient); 
            
        }
        
    }

    public function markThreadRead($threadId, $userId) {
        $recipient['read_at'] = gmdate('Y-m-d H:i:s');
        return $this->table()->where(array('thread_id' => $threadId, 'recipient_id' => $userId))
                ->where(array('read_at <>' => 0), true)
                ->update($recipient);
        
    }
    
    public function getUnreadReplies($userId)
    {
        return $this->table()->select("count({$this->tableName}.id) as count")
                ->where(array('recipient_id' => $userId, 'read_at' => '0000-00-00 00:00:00'))
                ->join('messages', $this->tableName . '.message_id', 'messages.id')
                ->join('users', 'messages.sender_id', 'users.id')
                ->field();
    }
    
    public function getUnreadRepliesWithDetail($user_id)
    {
        return $this->table()->select($this->tableName . '.*, messages.title, messages.text, users.first_name, users.last_name')
                ->join('messages', $this->tableName . '.message_id', 'messages.id')
                ->join('users', 'messages.sender_id', 'users.id')
                ->where(array('recipient_id' => $user_id, 'read_at' => '0000-00-00 00:00:00'))
                ->orderBy('id', 'DESC')
                ->limit(5)
                ->all();
    }
   
}
