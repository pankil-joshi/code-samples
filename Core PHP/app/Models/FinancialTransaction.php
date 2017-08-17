<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Quill\Database as Database;

/**
 * Description of FinancialTransactions
 *
 * @author harinder
 */
class FinancialTransaction extends Database{
    
    public $tableName = 'financial_transactions';


    public function save($transaction) {
        
        if(!empty($transaction['id'])) {
            
            $transaction['updated_at'] = gmdate('Y-m-d H:i:s');
                    
            return $this->table()->where(array('id' => $transaction['id']))->update($transaction, true);
            
        } else {
            
            $transaction['updated_at'] = gmdate('Y-m-d H:i:s');
            $transaction['created_at'] = gmdate('Y-m-d H:i:s');
            
            return $this->table()->insert($transaction, true); 
            
        }
        
    }
    
    public function getById($id) {
        
        return $this->table()->select()->where(array('id' => $id))->one();
        
    }    
    
}
