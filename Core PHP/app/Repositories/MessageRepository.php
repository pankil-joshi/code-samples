<?php

namespace App\Repositories;

use Quill\Factories\ModelFactory;
use Quill\Exceptions\BaseException;

class MessageRepository {
    
    private $models = array();

    public function __construct() {

        ModelFactory::setNamespace('\\App\\Models\\');

        $this->models = ModelFactory::boot(array(
            'MessageRecent',
            'Message',
            'MessageRecipient',
            'MessagesThreadDetails',
            'User'
        ));
    }
    
    public function postMessage($userId, $message, $threadId = '') {
        
            if(!empty($message['product_id'])) $threadDetails['product_id'] =  $message['product_id']; unset($message['product_id']);
            if(!empty($message['order_id'])) $threadDetails['order_id'] =  $message['order_id']; unset($message['order_id']);
            if(!empty($message['type'])) $threadDetails['type'] =  $message['type']; unset($message['type']);
            
            $message['sender_id'] = $userId;
            if(empty($threadId)){
                
                if(!empty($threadDetails['order_id'])) {
                    
                    $threadId = $this->models->messagesThreadDetails->getThreadIdByOrderId($threadDetails['order_id'], $userId, $message['recipient_id']);
                } elseif(!empty($threadDetails['product_id'])) {
                    
                    $threadId = $this->models->messagesThreadDetails->getThreadIdByProductId($threadDetails['product_id'], $userId, $message['recipient_id']);
                }   

            }

            if($message['recipient_id'] == $userId) {

                throw new BaseException('Bad request');
                
            }
                            
            $recipientId = $message['recipient_id'];
                
            unset($message['recipient_id']);
            
            if(!empty($threadId)) {

                $thread = $this->models->message->getRootById($threadId, $userId);

                $participants = array($thread['sender_id'], $thread['recipient_id']);

                if(!empty($thread && in_array($recipientId, $participants))) {
                    
                    unset($message['recipient_id']);

                    $message['is_root'] = 0;
                    $message['reply_to_message_id'] = $threadId;
                    
                    $messageId = $this->models->message->save($message);

                    if(!empty($messageId)) {

                        $recipient['message_id'] = $messageId;                       
                        $recipient['recipient_id'] = $recipientId;
                        $recipient['thread_id'] = $threadId;
                        
                        return $this->models->messageRecipient->save($recipient);                                               
                    }       
                    
                } else {

                    throw new BaseException('Bad request');                    
                }
                
            } else {
                
                $message['is_root'] = 1;
                
                $messageId = $this->models->message->save($message);
                
                $threadDetails['thread_id'] = $messageId;
                $threadDetails['status'] = 'open';
                
                $threadDetailsId = $this->models->messagesThreadDetails->save($threadDetails);
                
                $message['id'] = $messageId;
                $message['reply_to_message_id'] = $messageId;
                
                $this->models->message->save($message);
                
                if(!empty($messageId)) {
                    
                    $recipient['message_id'] = $messageId;                       
                    $recipient['recipient_id'] = $recipientId;
                    $recipient['thread_id'] = $messageId;
                    
                    return $this->models->messageRecipient->save($recipient);
                        
                }                
                
            }        
        
    }
    
}
