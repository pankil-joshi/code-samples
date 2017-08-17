<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Quill\Database as Database;

/**
 * Description of MediaPostageRuleAttributeValue
 *
 * @author harinder
 */
class MediaPostageRuleAttributeValue extends Database{
    
    public $tableName = 'media_postage_rule_attribute_values';    
    
    public function save($postageOptionRule) {

        return $this->table()->upsert($postageOptionRule);        
        
    }
    
    public function getAllByPostageOptionId($postageOptionId) {
        
        return $this->table()->select()->where(array('postage_option_id' => $postageOptionId))->all();        
        
    }
    
}
