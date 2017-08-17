<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Quill\Database as Database;
/**
 * Description of SubscriptionTransactionFeeRuleAttributes
 *
 * @author harinder
 */
class SubscriptionTransactionFeeRuleAttribute extends Database{
    
    public $tableName = 'subscription_transaction_fee_rule_attributes';
     
    public function save($transactionFeeRuleAttribute) {
        
        if(!empty($transactionFeeRuleAttribute['id'])) {
             
            return $this->table()->where(array('id' => $transactionFeeRuleAttribute['id']))->update($transactionFeeRuleAttribute);
            
        } else {             
            
            return $this->table()->insert($transactionFeeRuleAttribute); 
            
        }
        
    }        
    
}
