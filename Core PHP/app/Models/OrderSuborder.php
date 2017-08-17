<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Quill\Database as Database;

/**
 * Description of OrderSuborder
 *
 * @author harinder
 */
class OrderSuborder extends Database {

    public $tableName = 'order_suborders';

    public function save($subOrder) {

        if (!empty($subOrder['id'])) {

            return $this->table()->where(array('id' => $subOrder['id']))->update($subOrder, true);
        } else {
            
            $subOrder['created_at'] = gmdate('Y-m-d H:i:s');
                    
            return $this->table()->insert($subOrder, true);
        }
    }

    public function updateByOrderId($subOrder) {

        return $this->table()->where(array('order_id' => $subOrder['order_id']))->update($subOrder, true);
    }
    
    public function updateByMerchantId($subOrder, $merchantId) {

        return $this->table()->where(array('id' => $subOrder['id'], 'merchant_id' => $merchantId))->update($subOrder, true);
    }

    public function saveHistoryByOrderId($id, $history) {

        return $this->rawQuery("update {$this->tableName} set order_history = concat(order_history, ':::{$history}') where order_id = {$id}");
    }
    
    public function saveHistory($id, $history) {

        return $this->rawQuery("update {$this->tableName} set order_history = concat(order_history, ':::{$history}') where id = {$id}");
    }
    
    public function getAllByMerchantId($merchantId, $filter = array(), $order = array(), $offset = '', $limit = 10, $status= '', $search = array()) {

        if($order['order_by'] == 'alpha') {
            
            $orderBy = $this->tableName . '.media_title';
            $order['order'] = 'ASC';
            
        } elseif($order['order_by'] == 'value') {
            
            $orderBy = $this->tableName . '.total'; 
            
        } else {
            
            $orderBy = $this->tableName . '.created_at'; 
            
        }

        if(empty($order['order'])) {
            
            $order['order'] = 'DESC';
        }        
        
        if($filter['key'] == 'week') {
            
            $filter = array('DATE(' . $this->tableName . '.created_at)' => array(gmdate('Y-m-d', strtotime('-1 week UTC')), gmdate('Y-m-d')));
            
        } elseif($filter['key'] == 'fortnight') {
            
            $filter = array('DATE(' . $this->tableName . '.created_at)' => array(gmdate('Y-m-d', strtotime('-14 days UTC')), gmdate('Y-m-d')));
            
        } elseif($filter['key'] == 'month') {
            
            $filter = array('DATE(' . $this->tableName . '.created_at)' => array(gmdate('Y-m-d', strtotime('-1 month UTC')), gmdate('Y-m-d')));
            
        } elseif($filter['key'] == 'last_month') {
            
            $filter = array('DATE(' . $this->tableName . '.created_at)' => array(gmdate('Y-m-d', strtotime('first day of -1 month UTC')), gmdate('Y-m-d', strtotime('last day of -1 month UTC'))));
            
        } elseif($filter['key'] == 'custom') {
            
            $filter = array('DATE(' . $this->tableName . '.created_at)' => array($filter['start_date'], $filter['end_date']));
            
        } else {
            
            $filter = array();
            
        }        
               
        if($offset === null || $offset === ''){
            
            $offset = 0;
            
        } else {
            
            $offset = ($offset + 1) * $limit;
            
        }
        
        if(!empty($status) && $status != 'disputed'){
            
            $status = array( $this->tableName . '.status =' => $status);
            
        } elseif(!empty($status) && $status == 'disputed'){
            
            $status = array( 'messages_thread_details.dispute =' => '1');
            
        } else {
            
            $status = array( $this->tableName . '.status !=' => 'pending');
                    
        }
        if(!empty($search)){
            
            $search = array($this->tableName . '.media_title like ' => '%' . $search . '%', $this->tableName . '.id like ' => '%' . $search . '%');
        } else {
            
            $search = array();
        }

        return $this->table()->select($this->tableName . '.*, concat(' . $this->tableName . '.order_id, -' . $this->tableName . '.id) as order_number, orders.user_id')
                ->selectGroup(array('first_name', 'last_name', 'instagram_username', 'country'), 'users', 'user')
                ->join('orders', 'orders.id', $this->tableName . '.order_id')
                ->join('users', 'users.id', 'orders.user_id')
                ->leftJoin('messages_thread_details', "{$this->tableName}.id", 'messages_thread_details.order_id')
                ->whereBetween($filter)
                ->orWhere($search, true)                
                ->where(array('merchant_id' => $merchantId))
                ->where($status,true)
                ->orderBy($orderBy, $order['order'])
                ->groupBy("{$this->tableName}.id")
                ->limit(array($offset, $limit))
                ->all();
    }
    public function getById($id, $userId = null, $owner = 'customer') {
        
        if(!empty($userId)) {
            
            if($owner == 'merchant') {
                
                $owner = "{$this->tableName}.merchant_id";
            } else {
                
                $owner = 'orders.user_id';
            }
            
            $user = array($owner => $userId);         
        } else {
            
            $user = array();
        }

        return $this->table()->select($this->tableName . '.*, orders.user_id, users.notify_push_customer_order_status_change user_notify_push_customer_order_status_change, orders.customer_currency_code, orders.customer_currency_conversion_factor')
                ->join('orders', 'orders.id', $this->tableName . '.order_id')
                ->join('users', 'users.id', 'orders.user_id')
                ->where(array($this->tableName . '.id' => $id))
                ->where($user)
                ->one();
    }

    public function notCompleted($merchantId) {
        
        return $this->table()->select('id')
                ->where(array('merchant_id' => $merchantId))
                ->whereIn(array('status' => array('pending', 'in_progress', 'returned', 'shipped')))->all();
    }
    
    public function complete($orderHoldPeriod) {
        
        $history = array();
        $history['datetime'] = gmdate('Y-m-d H:i:s');
        $history['status'] = 'completed';
        $history['comment'] = 'Order completed.';

        $history = serialize($history);

        return $this->rawQuery("UPDATE order_suborders SET status = 'completed' , completed_at = '".  gmdate('Y-m-d H:i:s')."', order_history = concat(order_history, ':::{$history}') WHERE ( status = 'shipped' ) AND ( DATE_ADD(shipped_at, INTERVAL {$orderHoldPeriod} DAY) >= '".gmdate('Y-m-d H:i:s')."' )");
    }

    public function orderCountLastTenDaysByUserId($merchantId) {
        
        return $this->table()->select('count(id)')
                ->where(array('merchant_id' => $merchantId))
                ->where(array('status !=' => 'pending', 'created_at >= ' => gmdate('Y-m-d H:i:s', strtotime('- 30 days UTC'))), true)->field();    
        
    }
    
    public function getAllByUserId($userId, $filter = array(), $order = array(), $offset = '', $limit = 10, $status = '', $search = array()) {

        if(!empty($order['order_by']) && $order['order_by'] == 'alpha') {
            
            $orderBy = $this->tableName . '.media_title';
            $order['order'] = 'ASC';
            
        } elseif(!empty($order['order_by']) &&  $order['order_by'] == 'value') {
            
            $orderBy = $this->tableName . '.total'; 
            
        } else {
            
            $orderBy = $this->tableName . '.created_at'; 
            
        }

        if(empty($order['order']) || $order['order'] == 'undefined') {
            
            $order['order'] = 'DESC';
        }        
        
        if(!empty($filter['key']) && $filter['key'] == 'week') {
            
            $filter = array('DATE(' . $this->tableName . '.created_at)' => array(gmdate('Y-m-d', strtotime('-1 week UTC')), gmdate('Y-m-d')));
            
        } elseif(!empty($filter['key']) && $filter['key'] == 'fortnight') {
            
            $filter = array('DATE(' . $this->tableName . '.created_at)' => array(gmdate('Y-m-d', strtotime('-14 days UTC')), gmdate('Y-m-d')));
            
        } elseif(!empty($filter['key']) && $filter['key'] == 'month') {
            
            $filter = array('DATE(' . $this->tableName . '.created_at)' => array(gmdate('Y-m-d', strtotime('-1 month UTC')), gmdate('Y-m-d')));
            
        } elseif(!empty($filter['key']) && $filter['key'] == 'last_month') {
            
            $filter = array('DATE(' . $this->tableName . '.created_at)' => array(gmdate('Y-m-d', strtotime('first day of -1 month UTC')), gmdate('Y-m-d', strtotime('last day of -1 month UTC'))));
            
        } elseif(!empty($filter['key']) && $filter['key'] == 'custom') {
            
            $filter = array('DATE(' . $this->tableName . '.created_at)' => array($filter['start_date'], $filter['end_date']));
            
        } else {
            
            $filter = array();
            
        }        
               
        if($offset === null || $offset === ''){
            
            $offset = 0;
            
        } else {
            
            $offset = ($offset + 1) * $limit;
            
        }       
        
        if(!empty($status) && $status != 'disputed'){
            
            $status = array( $this->tableName . '.status =' => $status);
            
        } elseif(!empty($status) && $status == 'disputed'){
            
            $status = array( 'messages_thread_details.dispute =' => '1');
            
        } else {
            
            $status = array( $this->tableName . '.status !=' => 'pending');
                    
        }
        
        if(!empty($search)){
            
            $search = array($this->tableName . '.media_title like ' => '%' . $search . '%', $this->tableName . '.id like ' => '%' . $search . '%' );
        } else {
            
            $search = array();
        }      

        return $this->table()->select($this->tableName . '.*, concat(' . $this->tableName . '.order_id, -' . $this->tableName . '.id) as order_number, orders.user_id, order_ratings.rating as rating, orders.customer_currency_code, orders.customer_currency_conversion_factor, media.is_refundable_disabled')
                ->join('orders', $this->tableName . '.order_id', 'orders.id')
                ->leftJoin('order_ratings', array($this->tableName . '.id' => 'order_ratings.suborder_id', 'order_ratings.user_id' => $userId))
                ->leftJoin('messages_thread_details', "{$this->tableName}.id", 'messages_thread_details.order_id')
                ->leftJoin('media', "{$this->tableName}.media_id", 'media.id')
                ->whereBetween($filter)   
                ->orWhere($search, true)
                ->where(array('orders.user_id' => $userId))
                ->where($status, true)
                ->orderBy($orderBy, $order['order'])
                ->limit(array($offset, $limit))
                ->groupBy("{$this->tableName}.id")
                ->all();        
    }   
    public function getAllCountByUserId($userId, $filter = array(), $status = '', $search = array()) {
        
        if(!empty($filter['key']) && $filter['key'] == 'week') {
            
            $filter = array('DATE(' . $this->tableName . '.created_at)' => array(gmdate('Y-m-d', strtotime('-1 week UTC')), gmdate('Y-m-d')));
            
        } elseif(!empty($filter['key']) && $filter['key'] == 'fortnight') {
            
            $filter = array('DATE(' . $this->tableName . '.created_at)' => array(gmdate('Y-m-d', strtotime('-14 days UTC')), gmdate('Y-m-d')));
            
        } elseif(!empty($filter['key']) && $filter['key'] == 'month') {
            
            $filter = array('DATE(' . $this->tableName . '.created_at)' => array(gmdate('Y-m-d', strtotime('-1 month UTC')), gmdate('Y-m-d')));
            
        } elseif(!empty($filter['key']) && $filter['key'] == 'last_month') {
            
            $filter = array('DATE(' . $this->tableName . '.created_at)' => array(gmdate('Y-m-d', strtotime('first day of -1 month UTC')), gmdate('Y-m-d', strtotime('last day of -1 month UTC'))));
            
        } elseif(!empty($filter['key']) && $filter['key'] == 'custom') {
            
            $filter = array('DATE(' . $this->tableName . '.created_at)' => array($filter['start_date'], $filter['end_date']));
            
        } else {
            
            $filter = array();
            
        }                            
        
        if(!empty($status) && $status != 'disputed') {
            
            $status = array( $this->tableName . '.status =' => $status);
            
        } elseif(!empty($status) && $status == 'disputed'){
            
            $status = array( 'messages_thread_details.dispute =' => '1');
            
        } else {
            
            $status = array( $this->tableName . '.status !=' => 'pending');
                    
        }
        if(!empty($search)) {
            
            $search = array($this->tableName . '.media_title like ' => '%' . $search . '%');
        } else {
            
            $search = array();
        }        
        return $this->table()->select('count(DISTINCT ' . $this->tableName . '.id)')
                ->join('orders', $this->tableName . '.order_id', 'orders.id')
                ->leftJoin('messages_thread_details', "{$this->tableName}.id", 'messages_thread_details.order_id')
                ->whereBetween($filter)     
                ->where($search, true)
                ->where(array('orders.user_id' => $userId))
                ->where($status, true)   
                ->field();        
    }    
    public function getAllMonthlyBetweenDatesByUser($user, $dateRange) {

        return $this->rawQuery("select MONTH(CONVERT_TZ(" . $this->tableName . ".completed_at, '+00:00', '{$user['timezone']}')) as month, YEAR(CONVERT_TZ(" . $this->tableName . ".completed_at, '+00:00', '{$user['timezone']}')) as year, sum(" . $this->tableName . ".total) as total_sale from " . $this->tableName . " where " . $this->tableName . ".completed_at between '" . gmdate('Y-m-d H:i:s', strtotime('- 6 months UTC')) . "' and '" . gmdate('Y-m-d H:i:s') . "' and " . $this->tableName . ".merchant_id = {$user['id']} group by month order by year, month")->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function updateOrderStatusByMerchant($order_id,$data){
        return $this->table()->where(array('order_id' => $order_id))->update($data, true);
        
    }
    
    public function getPendingOrdersByMerchantId($merchant_id) {
        return $this->table()->select('count(id) as count')->where(array('merchant_id' => $merchant_id, 'status' => 'in_progress'))->one();
    }
    
    public function getCustomerOrdersByMerchantId($merchant_id) {
        return $this->table()->select($this->tableName . '.*, orders.*')
                ->join('orders', $this->tableName . '.order_id', 'orders.id')
                ->where(array($this->tableName . '.merchant_id' => $merchant_id))
                ->where(array($this->tableName . '.status <>' => 'pending'), true)
                ->all();
    }
    
    public function getTopTenMerchants() {
        return $this->table()->select('sum(' . $this->tableName . '.total) as merchants, users.id, users.instagram_username')
                ->join('users', $this->tableName . '.merchant_id', 'users.id')
                ->groupBy($this->tableName . '.merchant_id')
                ->orderBy('merchants', 'desc')
                ->limit(10)
                ->all();
    }
    
    public function awaitingFeedbackCount($userId) {
        return $this->table()->select("count({$this->tableName}.id)")
                ->join('orders', $this->tableName . '.order_id', 'orders.id')
                ->leftJoin('order_ratings', "{$this->tableName}.id", 'order_ratings.suborder_id')
                ->where(array('order_ratings.id is' => null, "{$this->tableName}.status !=" => 'pending'), true)
                ->where(array('orders.user_id' => $userId))                        
                ->field();
    }    

}
