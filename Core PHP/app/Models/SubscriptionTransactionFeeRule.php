<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Quill\Database as Database;
/**
 * Description of SubscriptionTransactionFeeRule
 *
 * @author harinder
 */
class SubscriptionTransactionFeeRule extends Database{
    
    public $tableName = 'subscription_transaction_fee_rules';
     
    public function save($transactionFeeRule) {

        $transactionFeeRule['rules'] = serialize($transactionFeeRule['rules']);

        if(!empty($transactionFeeRule['id'])) {
             
            return $this->table()->where(array('id' => $transactionFeeRule['id']))->update($transactionFeeRule);
            
        } else {             
            
            return $this->table()->insert($transactionFeeRule); 
            
        }
        
    }    
    
}
