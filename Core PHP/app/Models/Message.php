<?php

namespace App\Models;

use Quill\Database as Database;

class Message extends Database{
    
    public $tableName = 'messages';

    public function getAllByThreadId($threadId, $userId) {
        
       return $this->table()->select('messages.reply_to_message_id thread_id, messages.*, messages_recipients.*')              
               ->where(array('messages.reply_to_message_id' => $threadId))
               ->join('messages_recipients', 'messages.id', 'messages_recipients.message_id')
               ->andOrWhere(array('messages.sender_id' => $userId, 'messages_recipients.recipient_id' => $userId))->orderBy('messages.sent_at')->all();        
        
    }
    
    public function getRootById($threadId, $userId) {
        
       return $this->table()->select('messages.id, messages.sender_id, messages_recipients.recipient_id')              
               ->join('messages_recipients', 'messages.id', 'messages_recipients.message_id')
               ->where(array('messages.id' => $threadId))
               ->andOrWhere(array('messages.sender_id' => $userId, 'messages_recipients.recipient_id' => $userId))               
               ->one();        
        
    }    
    
    public function save($message) {
        
        if(!empty($message['id'])) {
             
            return $this->table()->where(array('id' => $message['id']))->update($message);
            
        } else {             
            
            $message['sent_at'] = gmdate('Y-m-d H:i:s');
            
            return $this->table()->insert($message); 
            
        }
        
    }
    
    public function getDisputeCount($userId, $status){       

        if(!empty($status)) {
            
            $status = "AND mtd.status = '{$status}'";
        }

        return $this->rawQuery("select count(mtd.thread_id) from "
                                . "messages_thread_details as mtd "
                                . "where mtd.second_user_id = {$userId} and dispute = '1' {$status}")->fetchColumn();
        
    }
    public function getInquiryCount($userId, $status){       

        if(!empty($status)) {
            
            $status = "AND mtd.status = '{$status}'";
        }

        return $this->rawQuery("select count(mtd.thread_id) from "
                                . "messages_thread_details as mtd "
                                . "where mtd.second_user_id = {$userId} and dispute = '0' {$status}")->fetchColumn();
        
    }    
    public function getRecentDisputesByUserId($userId, $status = ''){


        if(!empty($status)) {
            
            $status = "AND mtd.status = '{$status}'";
        }      

        return $this->rawQuery("select * from {$this->tableName} "
                                . "join messages_thread_details as mtd on {$this->tableName}.reply_to_message_id = mtd.thread_id "
                                . "join order_suborders on mtd.order_id = order_suborders.id "
                                . "where mtd.second_user_id = {$userId} and dispute = '1' {$status} group by {$this->tableName}.reply_to_message_id limit 1")->fetchAll(\PDO::FETCH_ASSOC);
        
    }
    public function getCustomerRecentDisputesByUserId($userId, $options = array()){

        if(!empty($options['status'])){
           
            $status = "and mtd.status = '{$options['status']}'";
        } else {
            
            $status = '';     
        }     
        if(!empty($options['order'])){
            
            $order = $options['order']['order'];
        } else {
            
            $order = 'DESC';
        }
        
        $orderBy = "{$this->tableName}.sent_at"; 
        
        $filter = !empty($options['filter'])? $options['filter'] : '';
        
        if(!empty( $filter['key']) && $filter['key'] == 'week') {
            
            $filter = "AND DATE({$this->tableName}.sent_at) BETWEEN '". gmdate('Y-m-d', strtotime('-1 week UTC')) . "' AND '" . gmdate('Y-m-d') . "'";
        } elseif(!empty( $filter['key']) && $filter['key'] == 'fortnight') {
            
            $filter = "AND DATE({$this->tableName}.sent_at) BETWEEN '". gmdate('Y-m-d', strtotime('-14 days UTC')) . "' AND '" . gmdate('Y-m-d') . "'"; 
        } elseif(!empty( $filter['key']) && $filter['key'] == 'month') {
            
            $filter = "AND DATE({$this->tableName}.sent_at) BETWEEN '". gmdate('Y-m-d', strtotime('-1 month UTC')) . "' AND '" . gmdate('Y-m-d') . "'";
        } elseif(!empty( $filter['key']) && $filter['key'] == 'last_month') {
            
            $filter = "AND DATE({$this->tableName}.sent_at) BETWEEN '". gmdate('Y-m-d', strtotime('first day of -1 month UTC')) . "' AND '" . gmdate('Y-m-d', strtotime('last day of -1 month UTC')) . "'";
        } elseif(!empty( $filter['key']) && $filter['key'] == 'custom') {
            
            $filter = "AND DATE({$this->tableName}.sent_at) BETWEEN '". $filter['start_date'] . "' AND '" . $filter['end_date'] . "'";
        } else {
            
            $filter = '';
        }

        return $this->rawQuery("select * from (select messages.text, messages.sender_id, messages.reply_to_message_id, messages.sent_at, mtd.*, media.image_thumbnail, mr.recipient_id, sender.*, merchant.instagram_username as merchant_instagram_username, sender.instagram_username as sender_instagram_username, media.title as media_title from {$this->tableName} "
                                . "join messages_thread_details as mtd on {$this->tableName}.reply_to_message_id = mtd.thread_id "
                                . "join messages_recipients as mr on {$this->tableName}.id = mr.message_id "
                                . "left join media on mtd.product_id = media.id "
                                . "left join users as merchant on media.user_id = merchant.id "
                                . "join users as sender on {$this->tableName}.sender_id = sender.id "
                                . "where mtd.first_user_id = {$userId} and dispute = '1' {$status} {$filter} order by {$orderBy} {$order}) {$this->tableName} group by {$this->tableName}.reply_to_message_id order by {$orderBy} {$order}")->fetchAll(\PDO::FETCH_ASSOC);
        
    } 
    public function getMerchantRecentEnquiriesByUserId($userId, $options = array()){

        if(!empty($options['status'])){
           
            $status = "and mtd.status = '{$options['status']}'";
        } else {
            
            $status = '';     
        }     
        if(!empty($options['order'])){
            
            $order = $options['order']['order'];
        } else {
            
            $order = 'DESC';
        }
        
        $orderBy = "{$this->tableName}.sent_at"; 
        
        $filter = !empty($options['filter'])? $options['filter'] : '';
        
        if(!empty( $filter['key']) && $filter['key'] == 'week') {
            
            $filter = "AND DATE({$this->tableName}.sent_at) BETWEEN '". gmdate('Y-m-d', strtotime('-1 week UTC')) . "' AND '" . gmdate('Y-m-d') . "'";
        } elseif(!empty( $filter['key']) && $filter['key'] == 'fortnight') {
            
            $filter = "AND DATE({$this->tableName}.sent_at) BETWEEN '". gmdate('Y-m-d', strtotime('-14 days UTC')) . "' AND '" . gmdate('Y-m-d') . "'"; 
        } elseif(!empty( $filter['key']) && $filter['key'] == 'month') {
            
            $filter = "AND DATE({$this->tableName}.sent_at) BETWEEN '". gmdate('Y-m-d', strtotime('-1 month UTC')) . "' AND '" . gmdate('Y-m-d') . "'";
        } elseif(!empty( $filter['key']) && $filter['key'] == 'last_month') {
            
            $filter = "AND DATE({$this->tableName}.sent_at) BETWEEN '". gmdate('Y-m-d', strtotime('first day of -1 month UTC')) . "' AND '" . gmdate('Y-m-d', strtotime('last day of -1 month UTC')) . "'";
        } elseif(!empty( $filter['key']) && $filter['key'] == 'custom') {
            
            $filter = "AND DATE({$this->tableName}.sent_at) BETWEEN '". $filter['start_date'] . "' AND '" . $filter['end_date'] . "'";
        } else {
            
            $filter = '';
        }

        return $this->rawQuery("select * from (select messages.text, messages.sender_id, messages.reply_to_message_id, messages.sent_at, mtd.*, media.image_thumbnail, mr.recipient_id, sender.*, merchant.instagram_username as merchant_instagram_username, sender.instagram_username as sender_instagram_username, sender.first_name as sender_first_name, sender.last_name as sender_last_name from {$this->tableName} "
                                . "join messages_thread_details as mtd on {$this->tableName}.reply_to_message_id = mtd.thread_id "
                                . "join messages_recipients as mr on {$this->tableName}.id = mr.message_id "
                                . "left join media on mtd.product_id = media.id "
                                . "left join users as merchant on media.user_id = merchant.id "
                                . "join users as sender on {$this->tableName}.sender_id = sender.id "
                                . "where mtd.second_user_id = {$userId} and dispute = '0' {$status} {$filter} order by {$orderBy} {$order}) {$this->tableName} group by {$this->tableName}.reply_to_message_id order by {$orderBy} {$order}")->fetchAll(\PDO::FETCH_ASSOC);
        
    }   
    public function getMerchantRecentDisputesByUserId($userId, $options = array()){

        if(!empty($options['status'])){
           
            $status = "and mtd.status = '{$options['status']}'";
        } else {
            
            $status = '';     
        }     
        if(!empty($options['order'])){
            
            $order = $options['order']['order'];
        } else {
            
            $order = 'DESC';
        }
        
        $orderBy = "{$this->tableName}.sent_at"; 
        
        $filter = !empty($options['filter'])? $options['filter'] : '';
        
        if(!empty( $filter['key']) && $filter['key'] == 'week') {
            
            $filter = "AND DATE({$this->tableName}.sent_at) BETWEEN '". gmdate('Y-m-d', strtotime('-1 week UTC')) . "' AND '" . gmdate('Y-m-d') . "'";
        } elseif(!empty( $filter['key']) && $filter['key'] == 'fortnight') {
            
            $filter = "AND DATE({$this->tableName}.sent_at) BETWEEN '". gmdate('Y-m-d', strtotime('-14 days UTC')) . "' AND '" . gmdate('Y-m-d') . "'"; 
        } elseif(!empty( $filter['key']) && $filter['key'] == 'month') {
            
            $filter = "AND DATE({$this->tableName}.sent_at) BETWEEN '". gmdate('Y-m-d', strtotime('-1 month UTC')) . "' AND '" . gmdate('Y-m-d') . "'";
        } elseif(!empty( $filter['key']) && $filter['key'] == 'last_month') {
            
            $filter = "AND DATE({$this->tableName}.sent_at) BETWEEN '". gmdate('Y-m-d', strtotime('first day of -1 month UTC')) . "' AND '" . gmdate('Y-m-d', strtotime('last day of -1 month UTC')) . "'";
        } elseif(!empty( $filter['key']) && $filter['key'] == 'custom') {
            
            $filter = "AND DATE({$this->tableName}.sent_at) BETWEEN '". $filter['start_date'] . "' AND '" . $filter['end_date'] . "'";
        } else {
            
            $filter = '';
        }

//        return $this->rawQuery("select *, merchant.instagram_username as merchant_instagram_username, sender.instagram_username as sender_instagram_username, sender.first_name as sender_first_name, sender.last_name as sender_last_name from {$this->tableName} "
//                                . "join messages_thread_details as mtd on {$this->tableName}.reply_to_message_id = mtd.thread_id "
//                                . "join messages_recipients as mr on {$this->tableName}.id = mr.message_id "
//                                . "left join media on mtd.product_id = media.id "
//                                . "left join users as merchant on media.user_id = merchant.id "
//                                . "join users as sender on {$this->tableName}.sender_id = sender.id "
//                                . "where mtd.second_user_id = {$userId} and dispute = '1' {$status} {$filter} group by {$this->tableName}.reply_to_message_id order by {$orderBy} {$order}")->fetchAll(\PDO::FETCH_ASSOC);
                                
        return $this->rawQuery("select * from (select messages.text, messages.sender_id, messages.reply_to_message_id, messages.sent_at, mtd.*, media.image_thumbnail, mr.recipient_id, sender.*, merchant.instagram_username as merchant_instagram_username, sender.instagram_username as sender_instagram_username, sender.first_name as sender_first_name, sender.last_name as sender_last_name from {$this->tableName} "
                                . "join messages_thread_details as mtd on {$this->tableName}.reply_to_message_id = mtd.thread_id "
                                . "join messages_recipients as mr on {$this->tableName}.id = mr.message_id "
                                . "left join media on mtd.product_id = media.id "
                                . "left join users as merchant on media.user_id = merchant.id "
                                . "join users as sender on {$this->tableName}.sender_id = sender.id "
                                . "where mtd.second_user_id = {$userId} and dispute = '1' {$status} {$filter} order by {$orderBy} {$order}) {$this->tableName} group by {$this->tableName}.reply_to_message_id order by {$orderBy} {$order}")->fetchAll(\PDO::FETCH_ASSOC);
        
    }    
    public function getRecentInquiriesByUserId($userId, $status){


        if(!empty($status)) {
            
            $status = "AND mtd.status = '{$status}'";
        }      

        return $this->rawQuery("select *, sender.first_name as sender_first_name, sender.last_name as sender_last_name, sender.country as sender_country, media.title as media_title from {$this->tableName} "
                                . "join messages_thread_details as mtd on {$this->tableName}.reply_to_message_id = mtd.thread_id "
                                . "left join media on mtd.product_id = media.id "
                                . "join users as sender on {$this->tableName}.sender_id = sender.id "
                                . "where mtd.second_user_id = {$userId} and dispute = '0' {$status} group by {$this->tableName}.reply_to_message_id limit 1")->fetchAll(\PDO::FETCH_ASSOC);
        
    }    
    public function getRecentByUserId($userId, $type = '', $specific = '', $options = array()){
        
        if($type !== '') {
            
            $type = "and mtd.dispute = '{$type}'";
            
        }
        
        if($specific) {
            
            $specific = "and mtd.first_user_id <> '{$userId}'";
            
        }
        if(!empty($options['user_type'])) {
            
            if($options['user_type'] == 'customer') {
                
                $specific = "and mtd.first_user_id <> '{$userId}'";
            }
        }
        if(!empty($options['order'])){
            
            $order = $options['order'];
        } else {
            
            $order = 'DESC';
        }
        if(!empty($options['order_by']) && $options['order_by'] == 'alpha') {
            
            $orderBy = 'u.first_name, m.sent_at';
            
        } else {
            
            $orderBy = 'm.sent_at'; 
            
        }        
        if(!empty($options['status'])){
           
            $status = "and mtd.status = '{$options['status']}'";
            
        } else {
            
            $status = '';
                    
        }

        $filter = !empty($options['filter'])? $options['filter'] : '';
        $having = '';
        
        if(!empty( $filter['key']) && $filter['key'] == 'week') {
            
            $filter = "AND DATE(m.sent_at) BETWEEN '". gmdate('Y-m-d', strtotime('-1 week UTC')) . "' AND '" . gmdate('Y-m-d') . "'";
            
        } elseif(!empty( $filter['key']) && $filter['key'] == 'fortnight') {
            
            $filter = "AND DATE(m.sent_at) BETWEEN '". gmdate('Y-m-d', strtotime('-14 days UTC')) . "' AND '" . gmdate('Y-m-d') . "'";

            
        } elseif(!empty( $filter['key']) && $filter['key'] == 'month') {
            
            $filter = "AND DATE(m.sent_at) BETWEEN '". gmdate('Y-m-d', strtotime('-1 month UTC')) . "' AND '" . gmdate('Y-m-d') . "'";
            
        } elseif(!empty( $filter['key']) && $filter['key'] == 'last_month') {
            
            $filter = "AND DATE(m.sent_at) BETWEEN '". gmdate('Y-m-d', strtotime('first day of -1 month UTC')) . "' AND '" . gmdate('Y-m-d', strtotime('last day of -1 month UTC')) . "'";
            
        } elseif(!empty( $filter['key']) && $filter['key'] == 'custom') {
            
            $filter = "AND DATE(m.sent_at) BETWEEN '". $filter['start_date'] . "' AND '" . $filter['end_date'] . "'";
            
        } elseif(!empty( $filter['key']) && $filter['key'] == 'unread') {
            
            $having = 'HAVING unread_count <> 0';
            $filter = '';
        }  elseif(!empty( $filter['key']) && $filter['key'] == 'read') {
            
            $having = 'HAVING unread_count = 0';
            $filter = '';
        }   elseif(!empty( $filter['key']) && $filter['key'] == 'disputes') {
            
            $type = 'AND mtd.dispute = "1"';
            $filter = '';
        } elseif(!empty( $filter['key']) && $filter['key'] == 'enquiries') {
            
            $type = 'AND mtd.dispute = "0"';
            $filter = '';
        } else {
            
            $filter = '';
            
        } 

        return $this->rawQuery("select m.reply_to_message_id thread_id, "
                . "(select count(m4.id) from {$this->tableName} as m4 join messages_recipients as mr2 on m4.id = mr2.message_id where m4.reply_to_message_id = m.reply_to_message_id and mr2.read_at = 0 and mr2.recipient_id = {$userId} ) as unread_count,"
                . " m.text, m.thread_owner_id, m.sender_id, mr.recipient_id, mtd.*, media.image_thumbnail, media.title as media_title, media.instagram_username as merchant_instagram_username, sender.first_name as sender_first_name, sender.last_name as sender_last_name, sender.instagram_username as sender_instagram_username, u.first_name, u.last_name, u.instagram_profile_picture, u.country, m.sent_at from "
                        . "(select m1.id, m1.sender_id, m1.text, m1.reply_to_message_id, m1.sent_at, (select m4.sender_id from {$this->tableName} m4 where m4.id = m1.reply_to_message_id) as thread_owner_id from {$this->tableName} m1 where `m1`.`sent_at` in (select max(`m3`.`sent_at`) AS `id` from {$this->tableName} `m3` group by `m3`.`reply_to_message_id`)) as m "
                        . "join users u on  m.thread_owner_id = u.id "
                                . "join users sender on  m.sender_id = sender.id "
                                . "join messages_recipients as mr on m.id = mr.message_id "
                                . "join messages_thread_details as mtd on m.reply_to_message_id = mtd.thread_id "
                                . "left join media on mtd.product_id = media.id "
                                . "where (m.sender_id = {$userId} or mr.recipient_id = {$userId}) {$type} {$status} {$filter} {$specific} {$having} order by {$orderBy} {$order}")->fetchAll(\PDO::FETCH_ASSOC);
        
    }
    
    
    
}
