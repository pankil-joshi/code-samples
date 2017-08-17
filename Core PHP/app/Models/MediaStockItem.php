<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Quill\Database as Database;

/**
 * Description of MediaStockItem
 *
 * @author harinder
 */
class MediaStockItem extends Database{
    
    public $tableName = 'media_stock_items';
    
    public function getStock($mediaId, $variantId = '') {

        return $this->table()->select('quantity')->where(array('media_id' => $mediaId, 'variant_id' => $variantId))->field();        
        
    }
    
    public function decrement($mediaId, $variantId, $quantity = 0) {
        
        $this->beginTransaction();
        
        $stock = $this->table()->lock('FOR UPDATE')->select('quantity')->where(array('media_id' => $mediaId, 'variant_id' => $variantId))->field();

        $this->table()->where(array('media_id' => $mediaId, 'variant_id' => $variantId))->update(array('quantity' => ( $stock - $quantity)));
        
        return $this->commit();
        
    }
    
    public function increment($mediaId, $variantId, $quantity = 0) {
        
        $this->beginTransaction();
        
        $stock = $this->table()->lock('FOR UPDATE')->select('quantity')->where(array('media_id' => $mediaId, 'variant_id' => $variantId))->field();
        $this->table()->where(array('media_id' => $mediaId, 'variant_id' => $variantId))->update(array('quantity' => ( $stock + $quantity)));
        
        return $this->commit();
        
    }    
    
    public function save($stockItem) {
        
        $stockItem['updated_at'] = gmdate('Y-m-d H:i:s');
                
        return $this->table()->upsert($stockItem); 
            
    }
    
    public function getLowStockVariants() {
 
        return $this->rawQuery("SELECT {$this->tableName}.*, media.title, media.user_id, media.image_thumbnail from {$this->tableName}"
        . " join media on {$this->tableName}.media_id = media.id"
        . " join users on media.user_id = users.id"
        . " join merchant_details on users.id = merchant_details.user_id"
        . " where {$this->tableName}.quantity <= {$this->tableName}.min_stock_level and {$this->tableName}.min_stock_level <> 0 and media.is_active = 1 and media.is_available = 1 and media.is_deleted = 0");      
    }    
    
}
