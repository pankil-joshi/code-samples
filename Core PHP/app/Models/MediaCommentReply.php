<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Quill\Database as Database;
/**
 * Description of MediaCommentReply
 *
 * @author harinder
 */
class MediaCommentReply extends Database {
    
    public $tableName = 'media_comment_replies';
    
    public function save($media) {

        if(!empty($media['id'])) {
            
            $media['updated_at'] = gmdate('Y-m-d H:i:s');

            return $this->table()->where(array('id' => $media['id']))->update($media);
            
        } else { 
            
            $media['updated_at'] = gmdate('Y-m-d H:i:s');
            $media['created_at'] = gmdate('Y-m-d H:i:s');
            
            return $this->table()->insert($media); 
            
        }
        
    }
}
