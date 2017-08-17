<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Quill\Database as Database;
/**
 * Description of SubscriptionPackage
 *
 * @author harinder
 */
class SubscriptionPackage extends Database{
    
    public $tableName = 'subscription_packages';
        
    public function save($subscriptionPackage) {
        
        if(!empty($subscriptionPackage['id'])) {
            
            $subscriptionPackage['updated_at'] = gmdate('Y-m-d H:i:s');      
            
            return $this->table()->where(array('id' => $subscriptionPackage['id']))->update($subscriptionPackage);
            
        } else {
            
            $subscriptionPackage['created_at'] = gmdate('Y-m-d H:i:s');
            $subscriptionPackage['updated_at'] = gmdate('Y-m-d H:i:s');
            $subscriptionPackage['code'] = snake_case($subscriptionPackage['name']);
            
            return $this->table()->insert($subscriptionPackage); 
            
        }
        
    }
    
    public function getById($id) {
        
        return $this->table()->select()->where(array('id' => $id))->one();
        
    }    
    public function getAll() {
        
        return $this->table()->select()->where(array('is_active' => 1))->all();
        
    }    
    public function getAllPublic() {
        
        return $this->table()->select()->where(array('is_public' => 1, 'is_active' => 1))->all();
        
    }     
    public function getAllPlans() {
        
        return $this->table()->select()->where(array())->all();
        
    }      
    
    public function getThresholdByUserId($userId)
    {
        return $this->table()->select($this->tableName .'.payment_threshold')
                ->join('merchant_details', $this->tableName .'.id', 'subscription_package_id')
                ->where(array('merchant_details.user_id' => $userId))->field();
        
    }
    
    public function getByUserId($userId)
    {
        return $this->table()->select($this->tableName .'.*')
                ->join('merchant_details', $this->tableName .'.id', 'subscription_package_id')
                ->where(array('merchant_details.user_id' => $userId))->one();
        
    }    
}
