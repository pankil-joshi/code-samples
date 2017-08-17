<?php

namespace App\Models;

use Quill\Database as Database;

class Order extends Database {

    public $tableName = 'orders';

    public function save($order) {

        if (!empty($order['id'])) {

            return $this->table()->where(array('id' => $order['id']))->update($order, true);
        } else {
            
            $order['created_at'] = gmdate('Y-m-d H:i:s');
            
            return $this->table()->insert($order, true);
        }
    }

    public function getAllByMerchantUserId($merchantUserId) {

        return $this->table()->select('orders.*')
                        ->join('order_items', 'orders.id', 'order_items.order_id')
                        ->where(array('order_items.merchant_id' => $merchantUserId))
                        ->groupBy('orders.id')
                        ->all();
    }

    public function saveHistory($id, $history) {

        return $this->rawQuery("update {$this->tableName} set order_history = concat($history.' \n', order_history) where id = {$id}");
    }
    
    public function getById($id) {
        
        return $this->table()->select()->where(array('id' => $id))->one();
        
    }
    
    public function getPendingOrders($user_id) {
        return $this->table()->select($this->tableName . '.*, order_items.media_title, order_items.media_thumbnail_image, order_items.options, order_suborders.order_history, order_suborders.delivery_address, order_suborders.postage_option_label')
                ->join('order_items', $this->tableName . '.id', 'order_items.order_id')
                ->join('order_suborders', $this->tableName . '.id', 'order_suborders.order_id')
                ->where(array($this->tableName . '.user_id' => $user_id, 'order_suborders.status' => 'pending'))
                ->orderBy('id', 'DESC')
                ->limit(5)
                ->all();
        
    }
    
    public function getCustomerOrders($user_id) {
        return $this->table()->select($this->tableName . '.*, order_items.media_title, order_items.media_thumbnail_image, order_items.options, order_suborders.order_history, order_suborders.delivery_address, order_suborders.status, order_suborders.postage_option_label')
                ->join('order_items', $this->tableName . '.id', 'order_items.order_id')
                ->join('order_suborders', $this->tableName . '.id', 'order_suborders.order_id')
                ->where(array($this->tableName . '.user_id' => $user_id))
                ->orWhere(array('order_suborders.status' => 'in_progress', 'order_suborders.status' => 'shipped'))
                ->orderBy('id', 'DESC')
                ->limit(5)
                ->all();
    }
    
    public function getAllOrders($user_id) {
        return $this->table()->select($this->tableName . '.*, order_items.media_title, order_items.media_thumbnail_image, order_items.options, order_suborders.order_history, order_suborders.delivery_address, order_suborders.postage_option_label, order_suborders.status')
                ->join('order_items', $this->tableName . '.id', 'order_items.order_id')
                ->join('order_suborders', $this->tableName . '.id', 'order_suborders.order_id')
                ->where(array($this->tableName . '.user_id' => $user_id))
                ->orderBy('id', 'DESC')
                ->all();
    }
    
    public function getOrdersBySelectedStatus($user_id,$status) {
        if($status == 'All')
        {
            return $this->table()->select($this->tableName . '.*, order_items.media_title, order_items.media_thumbnail_image, order_items.options, order_suborders.order_history, order_suborders.delivery_address, order_suborders.postage_option_label, order_suborders.status')
                ->join('order_items', $this->tableName . '.id', 'order_items.order_id')
                ->join('order_suborders', $this->tableName . '.id', 'order_suborders.order_id')
                ->where(array($this->tableName . '.user_id' => $user_id))
                ->orderBy('id', 'DESC')
                ->all();
        }else {
            return $this->table()->select($this->tableName . '.*, order_items.media_title, order_items.media_thumbnail_image, order_items.options, order_suborders.order_history, order_suborders.delivery_address, order_suborders.postage_option_label, order_suborders.status')
                ->join('order_items', $this->tableName . '.id', 'order_items.order_id')
                ->join('order_suborders', $this->tableName . '.id', 'order_suborders.order_id')
                ->where(array($this->tableName . '.user_id' => $user_id, 'order_suborders.status' => $status))
                ->orderBy('id', 'DESC')
                ->all();
        }
            
    }

}
