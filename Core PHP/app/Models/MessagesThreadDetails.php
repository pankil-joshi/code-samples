<?php

namespace App\Models;

use Quill\Database as Database;

class MessagesThreadDetails extends Database{
    
    public $tableName = 'messages_thread_details';
  
    
    public function save($threadDetails) {
        
        if(!empty($threadDetails['id'])) {
             
            return $this->table()->where(array('id' => $threadDetails['id']))->update($threadDetails);
            
        } else {             
            
            return $this->table()->insert($threadDetails); 
            
        }
        
    }
    
    public function getThreadIdByProductId($productId, $userId) {

        return $this->select('thread_id')->where(array('product_id' => $productId, 'order_id' => 0, 'first_user_id' => $userId))->field();
    }
    
    public function getByThreadId($threadId, $userId) {

        return $this->select()->where(array('thread_id' => $threadId))->one();   
        
    }
    
    public function getThreadIdByOrderId($orderId, $userId, $recepientId) {

        return $this->rawQuery("SELECT messages.id from messages_thread_details  "
                . "JOIN messages ON messages_thread_details.thread_id = messages.id "
                . "JOIN messages_recipients ON messages_thread_details.thread_id = messages_recipients.thread_id "
                . "WHERE  ( messages_thread_details.order_id = {$orderId}  )  and messages_thread_details.dispute = '0' and ("
                . "( messages.sender_id = {$userId}  AND messages_recipients.recipient_id = {$recepientId} ) OR"
                . "( messages.sender_id = {$recepientId}  AND messages_recipients.recipient_id = {$userId} ) "
                . ") LIMIT 1 ")->fetchColumn(0);   
        
    }   
    
    public function getDisputeIdByOrderId($orderId, $userId, $recepientId) {

        return $this->rawQuery("SELECT messages.id from messages_thread_details  "
                . "JOIN messages ON messages_thread_details.thread_id = messages.id "
                . "JOIN messages_recipients ON messages_thread_details.thread_id = messages_recipients.thread_id "
                . "WHERE  ( messages_thread_details.order_id = {$orderId}  ) and messages_thread_details.dispute = '1' and  ("
                . "( messages.sender_id = {$userId}  AND messages_recipients.recipient_id = {$recepientId} ) OR"
                . "( messages.sender_id = {$recepientId}  AND messages_recipients.recipient_id = {$userId} ) "
                . ") LIMIT 1 ")->fetchColumn(0);   
        
    }      
}
