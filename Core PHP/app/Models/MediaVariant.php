<?php

namespace App\Models;

use Quill\Database as Database;

class MediaVariant extends Database {

    public $tableName = 'media_variants';

    public function getAllByMediaId($mediaId) {

        return $this->table()->select('media_variants.*, stock_items.quantity, stock_items.min_stock_level')
                        ->selectGroup(array('quantity', 'min_stock_level'), 'stock_items', 'stock')
                        ->join('media_stock_items as stock_items', 'media_variants.id', 'stock_items.variant_id')
                        ->where(array('media_variants.media_id' => $mediaId))->all();
    }

    public function getPricesByMediaId($mediaId) {

        return $this->table()->select('min(price) as min_price, max(price) as max_price')
                        ->where(array($this->tableName . '.media_id' => $mediaId))->one();
    }

    public function save($variant) {

        if (isset($variant['id']) && is_numeric($variant['id'])) {

            $variant['updated_at'] = gmdate('Y-m-d H:i:s');

            return $this->table()->where(array('id' => $variant['id']))->update($variant, true)['id'];
        } else {

            $variant['updated_at'] = gmdate('Y-m-d H:i:s');
            $variant['created_at'] = gmdate('Y-m-d H:i:s');

            return $this->table()->insert($variant);
        }
    }

    public function remove($variantId) {

        return $this->table()->where(array('id' => $variantId))->delete();
    }

    public function getById($id) {

        return $this->table()->select('media_variants.*, media_stock_items.*')
                        ->selectGroup(array('user_id'), 'media', 'media')
                        ->join('media_stock_items', 'media_variants.id', 'media_stock_items.variant_id')
                        ->join('media', 'media_variants.media_id', 'media.id')
                        ->where(array('media_variants.id' => $id))
                        ->one();
    }

    public function getQuantity($id) {

        return $this->getCache($id)['quantity'];
    }

    public function getPrice($id) {

        return $this->getCache($id)['price'];
    }

    public function getMerchantId($id) {

        return $this->getCache($id)['media_user_id'];
    }

}
