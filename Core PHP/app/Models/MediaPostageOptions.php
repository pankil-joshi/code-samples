<?php

namespace App\Models;

use Quill\Database as Database;

class MediaPostageOptions extends Database {
    
    public $tableName = 'media_postage_options';

    public function getAll() {
        
        return $this->table()->select()->where(array())->all();
        
    }
    
    public function getAllByUserId($userId) {
        
        return $this->table()->select('*')->where(array('user_id' => $userId))->all();
        
    }
    
    public function getAllByMediaId($mediaId) {
        
        return $this->table()->select('*')->where(array('media_id' => $mediaId))->all();
        
    }    
    
    public function getRatesByMediaId($mediaId) {
        
        return $this->table()->select('min(rate) as min_rate, max(rate) as max_rate')->where(array('media_id' => $mediaId))->one();
        
    }

    public function getById($id) {
        
        return $this->table()->select()->where(array('id' => $id))->one();
        
    } 
    
    public function getRate($id) {

        return $this->getCache($id)['rate'];
        
    }
    
    public function getLabel($id) {

        return $this->getCache($id)['label'];
        
    }    
    
    public function getDuration($id) {

        return $this->getCache($id)['duration'];
        
    }     
    
    public function save($postageOption) {
        
        if(!empty($postageOption['id'])) {
            
            $postageOption['code'] = snake_case($postageOption['label']);
            
            $postageOption['updated_at'] = gmdate('Y-m-d H:i:s');
            
            return $this->table()->where(array('id' => $postageOption['id']))->update($postageOption, true);
            
        } else {
            
            $postageOption['updated_at'] = gmdate('Y-m-d H:i:s');
            $postageOption['created_at'] = gmdate('Y-m-d H:i:s');
            
            $postageOption['code'] = snake_case($postageOption['label']);
            
            return $this->table()->insert($postageOption, true);
            
        }
        
    }
    
    public function remove($id, $userId) {
        
        return $this->table()->where(array('id' => $id, 'user_id' => $userId))->delete();      
        
    }    
    
    public function getDomesticByMerchant($merchantDetails) {
        
        return $this->table()->select()
                ->join('media_postage_rule_attribute_values', 'media_postage_rule_attribute_values.postage_option_id', $this->tableName. '.id')
                ->where(array($this->tableName . '.user_id' => $merchantDetails['user_id'], 'media_postage_rule_attribute_values.value' => $merchantDetails['business_address_country']))
                ->all();        
        
    }
    
    public function getAllByMediaIdUserAddress($mediaId, $userAddress) {        

        return $this->rawQuery("select distinct {$this->tableName}.* from {$this->tableName} where media_id = {$mediaId} and (geography like '%{$userAddress['country']}%' or (geography = '*'))")->fetchAll(\PDO::FETCH_ASSOC);        
        
    }
    
    public function getAllIdsByMediaIdUserAddress($mediaId, $userAddress) {

        return $this->rawQuery("select distinct {$this->tableName}.id from {$this->tableName} where media_id = {$mediaId} and (geography like '%{$userAddress['country']}%' or (geography = '*'))")->fetchAll(\PDO::FETCH_COLUMN);        
        
    }    

}