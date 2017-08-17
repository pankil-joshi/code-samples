<?php

namespace App\Models;

use Quill\Database as Database;

class MediaVariantOption extends Database{
    
    public $tableName = 'media_variant_options';

    public function getAllByMediaId($mediaId, $variantId) {
        
        return $this->table()
                
                ->select('media_variant_options.*')
                
                ->selectGroup(array('value', 'id'), 'option_values', 'option_value')
                
                ->join('media_variant_option_values as option_values', 'media_variant_options.id', 'option_values.media_variant_option_id')
                
                ->where(array('media_variant_options.media_id' => $mediaId, 'option_values.variant_id' => $variantId))->all();
        
    }     
    
    public function save($option) {


        if(isset($option['id']) && is_numeric($option['id'])) {

            return $this->table()->select()->where(array('id' => $option['id']))->one();
            
        }
        elseif($this->table()->select('id')->where(array('code' => $option['code'], 'media_id' => $option['media_id']))->one()) {     
            
            return $this->table()->select('id')->where(array('code' => $option['code'], 'media_id' => $option['media_id']))->one();
            
        } else {
                        
            return $this->table()->insert($option, true); 
            
        }   
        
    }
    
    public function remove($option){
        
        return $this->table()->where(array('code' => $option['code'], 'media_id' => $option['media_id']))->delete();
        
    }    

}