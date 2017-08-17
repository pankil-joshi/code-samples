<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Quill\Database as Database;

/**
 * Description of MerchantDetails
 *
 * @author harinder
 */
class MerchantDetailsAdditionalOwners extends Database {

    public $tableName = 'merchant_details_additional_owners';

    public function save($additionalOwner) {
        
        $additionalOwner['updated_at'] = gmdate('Y-m-d H:i:s');
        
        return $this->table()->upsert($additionalOwner);
    }
    
    public function getByUserId($userId) {

        return $this->table()->select()->where(array('user_id' => $userId))->all();
    }    

    public function getNextIndex($userId) {
        
        $nextIndex = $this->table()->select('additional_owner_index')->where(array('user_id' => $userId))->orderBy('id', 'DESC')->field();
        $nextIndex = ($nextIndex === false)? 0 : $nextIndex + 1;
        
        return $nextIndex;
    }     
}
