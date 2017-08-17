<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Quill\Database as Database;

class MediaTax extends Database{
    
    public $tableName = 'media_tax';

    public function getRateByMediaId($mediaId) {
        
        return $this->table()->select('rate')->where(array('media_id' => $mediaId))->field();
        
    }         
    
    public function getByMediaId($mediaId) {
        
        return $this->table()->select('rate, inclusive')->where(array('media_id' => $mediaId))->one();
        
    } 
    
    public function save($tax) {

        return $this->table()->upsert($tax);
        
    }    
    
}
