<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

/**
 * Description of MediaRating
 *
 * @author harinder
 */

use Quill\Database as Database;

class MediaReportMedia extends Database{
    
    protected $tableName = 'media_report_media';
    
    public function save($report) {
        
        $report['reported_at'] = gmdate('Y-m-d H:i:s');           
            
        return $this->table()->upsert($report);
        
    }
    
    public function getByMediaIdUserId($mediaId, $userId) {         
            
        return $this->table()->select()->where(array('media_id' => $mediaId, 'user_id' => $userId))->one();
        
    }        
}
