<?php

namespace App\Models;

use Quill\Database as Database;

class Comment extends Database{
    
    public $tableName = 'media_comments';

    public function save($comment) {
        
        if(!empty($comment['id'])) {

            $comment['updated_at'] = gmdate('Y-m-d H:i:s'); 
            
            return $this->table()->where(array('id' => $comment['id']))->update($comment);
            
        } else {           
            
            $comment['created_at'] = gmdate('Y-m-d H:i:s');
            $comment['updated_at'] = gmdate('Y-m-d H:i:s'); 
            
            return $this->table()->insert($comment); 
            
        }
        
    }
    
    public function updateByInstagramCommentId($comment) {
        
        $comment['updated_at'] = gmdate('Y-m-d H:i:s'); 
            
        return $this->table()->where(array('comment_id' => $comment['comment_id']))->update($comment);

    }    
    
    public function getByInstagramCommentId($commentId) {
        
        return $this->table()->select()->where(array('comment_id' => $commentId))->one();
        
    }

    public function getMonthlyConversions($user) {
        
        return $this->table()->select(" (avg({$this->tableName}.order_completed = '1')*100) as rate, year({$this->tableName}.created_at) as year, month({$this->tableName}.created_at) as month")
                ->join('media', $this->tableName . '.instagram_media_id', 'media_id')
                ->where(array('media.user_id' => $user['id']))
                ->groupBy("month({$this->tableName}.created_at)")
                ->all();
        
    }
    
}