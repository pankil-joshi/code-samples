<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Quill\Database;

/**
 * Description of MerchantLedger
 *
 * @author harinder
 */
class MerchantLedger extends Database {

    public $tableName = 'merchant_ledger';

    public function save($entry) {

        if (!empty($entry['id'])) {

            return $this->table()->where(array('id' => $entry['id']))->update($entry);
        } else {

            $entry['created_at'] = gmdate('Y-m-d H:i:s');

            return $this->table()->insert($entry);
        }
    }

    public function getByTransactionIdNewOrder($transactionId, $userId) {

        return $this->table()->select()->where(array('transaction_id' => $transactionId, 'user_id' => $userId, 'type_code' => 'new_order'))->one();
    }
    
    public function getByTransactionIdOrderAmount($transactionId, $userId) {

        return $this->table()->select()->where(array('transaction_id' => $transactionId, 'user_id' => $userId, 'type_code' => 'transaction_fee'))->one();
    }

    public function getAdditionalFee($transactionId, $userId) {

        return $this->table()->select('amount')->where(array('transaction_id' => $transactionId, 'user_id' => $userId, 'type_code' => 'additional_fee'))->field();
    }
    
    public function getEarningsThisMonthByUserId($userId, $options = null) {
        
        return $this->table()->select('sum(case flow when "in" then amount when "out" then -amount end) as amount')
                        ->where(array('user_id' => $userId))
                        ->whereBetween(array('created_at' => array(gmdate('Y-m-d', strtotime('first day of this month UTC')), gmdate('Y-m-d H:i:s'))))
                        ->field();
    }
    
    public function getEarningsBetweenDates($user, $dateRange) {
        
        return $this->rawQuery("select DATE(CONVERT_TZ({$this->tableName}.created_at, '+00:00', '{$user['timezone']}')) as date, sum(case {$this->tableName}.flow when 'in' then {$this->tableName}.amount when 'out' then -{$this->tableName}.amount end) as amount from {$this->tableName}" 
                . " left join {$this->tableName} as child on {$this->tableName}.order_id = child.order_id AND (child.type_code = 'refund_order' OR child.type_code = 'transaction_fee_returned' OR child.type_code = 'cancel_order')"
                . " where DATE(CONVERT_TZ(" . $this->tableName . ".created_at, '+00:00', '{$user['timezone']}')) = '" . $dateRange['start_date'] . "' AND " . $this->tableName . ".user_id = {$user['id']} AND child.id is null"
                . " group by date order by date")->fetch(\PDO::FETCH_ASSOC);
    }

    public function getPendingSettlementToday($user, $threshold) {

        return $this->rawQuery("select {$this->tableName}.*, transaction_fee.amount as transaction_fee, transaction_fee.transaction_fee_rate as transaction_fee_rate from {$this->tableName}" 
                . " left join {$this->tableName} as child on {$this->tableName}.order_id = child.order_id AND (child.type_code = 'refund_order' OR child.type_code = 'transaction_fee_returned' OR child.type_code = 'cancel_order')"
                . " join {$this->tableName} as transaction_fee on {$this->tableName}.order_id = transaction_fee.order_id AND (transaction_fee.type_code = 'transaction_fee')"
                . " where ({$this->tableName}.type_code = 'new_order') AND DATE_ADD(DATE({$this->tableName}.created_at), INTERVAL {$threshold} DAY) = '" . gmdate('Y-m-d') . "' AND " . $this->tableName . ".user_id = {$user['id']} AND child.id is null"
                . " order by id")->fetchAll(\PDO::FETCH_ASSOC);
    }    
    
    public function getEarningsLastMonthByUserId($userId) {

        return $this->table()->select('sum(case flow when "in" then amount when "out" then -amount end) as amount')
                        ->where(array('user_id' => $userId))
                        ->whereBetween(array('created_at' => array(gmdate('Y-m-d', strtotime('first day of last month UTC')), gmdate('Y-m-d H:i:s', strtotime('last month UTC')))))
                        ->field();
    }

    public function getRefundsThisMonthByUserId($userId) {
 
        return $this->table()->rawQuery("SELECT sum(amount) from merchant_ledger  WHERE  ( type_code = 'cancel_order' OR type_code = 'refund_order' )  AND  ( user_id = {$userId}  )  AND  ( created_at BETWEEN '" . gmdate('Y-m-d', strtotime('first day of this month UTC')) . "' AND '". gmdate('Y-m-d H:i:s') ."' )")->fetchColumn(0);
    }

    public function getTransactionFeeThisMonthByUserId($userId) {
        
        return $this->table()->rawQuery("SELECT sum(case flow when 'out' then amount when 'in' then -amount end) as amount from merchant_ledger  WHERE  ( type_code = 'transaction_fee' OR type_code = 'transaction_fee_returned' OR type_code = 'additional_fee' OR type_code = 'additional_fee_returned')  AND  ( user_id = {$userId}  )  AND  ( created_at BETWEEN '" . gmdate('Y-m-d', strtotime('first day of this month UTC')) . "' AND '". gmdate('Y-m-d H:i:s') ."' )")->fetchColumn(0);
    }

    public function getUpcomingSettlementsByUserId($userId, $threshold) {

        return $this->table()->rawQuery("SELECT sum(case flow when 'in' then amount when 'out' then -amount end) as amount, date(created_at) as created_at, DATE_ADD(DATE(created_at),INTERVAL {$threshold} DAY) as settlement_date "
                        . "from merchant_ledger WHERE user_id = {$userId}  AND DATE_ADD(created_at,INTERVAL {$threshold} DAY) > " . gmdate('Y-m-d') . " "
                        . "GROUP BY date(created_at)")->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getAllSettlementsByUserId($userId, $threshold, $options = null) {
        
        $filter = '';
        if(!empty($options['filter']) && !empty($options['month'])) {
            
            $options['year'] = !empty($options['year'])? $options['year'] : gmdate('Y');
            $filter = 'AND MONTH(DATE_ADD(DATE(created_at),INTERVAL ' . $threshold. ' DAY)) = '. $options['month'] . ' AND YEAR(DATE_ADD(DATE(created_at),INTERVAL ' . $threshold. ' DAY)) = '. $options['year'];
        }
        
        $search = '';
        if(!empty($options['search'])) {
            
            $search = 'AND order_id = '. $options['search'];
        }

        return $this->table()->rawQuery("SELECT sum(case flow when 'in' then amount when 'out' then -amount end) as amount, date(created_at) as created_at, DATE_ADD(DATE(created_at),INTERVAL {$threshold} DAY) as settlement_date "
                        . "from merchant_ledger WHERE user_id = {$userId} {$filter} {$search} "
                        . "GROUP BY date(created_at)")->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getCompleteSettlementsByUserId($userId, $threshold)
    {
        
        return $this->table()->rawQuery("SELECT sum(case flow when 'in' then amount when 'out' then -amount end) as amount, date(created_at) as created_at, DATE_ADD(DATE(created_at),INTERVAL {$threshold} DAY) as settlement_date "
                        . "from merchant_ledger WHERE user_id = {$userId}  AND DATE_ADD(created_at,INTERVAL {$threshold} DAY) <= '" . gmdate('Y-m-d') . "' "
                        . "GROUP BY date(created_at)")->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getTodaySettlementsByUserId($userId, $threshold)
    {
        return $this->table()->rawQuery("SELECT sum(case flow when 'in' then amount when 'out' then -amount end) as amount, date(created_at) as created_at, DATE_ADD(DATE(created_at),INTERVAL {$threshold} DAY) as settlement_date "
                        . "from merchant_ledger WHERE user_id = {$userId}  AND DATE(DATE_ADD(created_at,INTERVAL {$threshold} DAY)) = '" . gmdate('Y-m-d') . "' "
                        . "GROUP BY date(created_at)")->fetchAll(\PDO::FETCH_ASSOC);
    }    
    public function getAllByCreatedDate($userId, $createdDate, $options = null) {
        
        $search = '';
        if(!empty($options['search'])) {
            
            $search = 'AND merchant_ledger.order_id = '. $options['search'];
        }

        return $this->table()->rawQuery("SELECT  merchant_ledger.order_id, merchant_ledger.created_at, merchant_ledger.amount, merchant_ledger.type_code, child.amount as transaction_fee, child2.amount as additional_fee, (merchant_ledger.amount - child.amount) - child2.amount as settelement_total "
                . "from merchant_ledger  JOIN merchant_ledger as child ON merchant_ledger.transaction_id = child.transaction_id AND (child.type_code = 'transaction_fee' OR child.type_code = 'transaction_fee_returned') "
                . "JOIN merchant_ledger as child2 ON merchant_ledger.transaction_id = child2.transaction_id AND (child2.type_code = 'additional_fee' OR child2.type_code = 'additional_fee_returned') "
                . "WHERE date(merchant_ledger.created_at) = '{$createdDate}'  AND merchant_ledger.user_id = {$userId} AND ("
                . "(merchant_ledger.type_code = 'new_order' AND child.type_code = 'transaction_fee' AND child2.type_code = 'additional_fee') OR "
                . "(merchant_ledger.type_code = 'cancel_order' AND child.type_code = 'transaction_fee_returned' AND child2.type_code = 'additional_fee_returned') OR "
                . "(merchant_ledger.type_code = 'refund_order' AND child.type_code = 'transaction_fee_returned' AND child2.type_code = 'additional_fee_returned')"        
                . ") {$search} ")->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getAwaitingWithdrawl($userId, $threshold)
    {
        
        return $this->table()->rawQuery("SELECT sum(case {$this->tableName}.flow when 'in' then {$this->tableName}.amount when 'out' then -{$this->tableName}.amount end) as amount "
                        . "from merchant_ledger "
                        . "left join {$this->tableName} as child on {$this->tableName}.order_id = child.order_id AND (child.type_code = 'refund_order' OR child.type_code = 'cancel_order') "                
                        . "WHERE {$this->tableName}.user_id = {$userId}  AND DATE_ADD({$this->tableName}.created_at,INTERVAL {$threshold} DAY) <= '" . gmdate('Y-m-d') . "' AND child.id is null"
                        )->fetch(\PDO::FETCH_ASSOC);
    }
            
    /*
     * Total transections revenue
     */
    public function getAllTransectionsTotal($filter) {
        
             return $this->table()->rawQuery("SELECT sum(amount) as amount, currency_code, AVG(amount) as average from merchant_ledger WHERE type_code = 'new_order' AND DATE(created_at) BETWEEN '" . $filter['start_date'] . "' AND '". $filter['end_date'] ."' GROUP BY currency_code")->fetchAll();
    }

    /*
     * Total transections revenue
     */
    public function getAllRefundedTotal($filter) {

             return $this->table()->rawQuery("SELECT sum(amount) as amount, currency_code, AVG(amount) as average from merchant_ledger WHERE type_code = 'cancel_order' AND DATE(created_at) BETWEEN '" . $filter['start_date'] . "' AND '". $filter['end_date'] ."' GROUP BY currency_code")->fetch();
    
    }    
    
    /*
     * Total transections revenue
     */
    public function getAllCommissionsTotal($filter) {
        return $this->table()->rawQuery("SELECT sum(case flow when 'out' then amount when 'in' then -amount end) as amount, currency_code from merchant_ledger WHERE ( type_code = 'transaction_fee' OR type_code = 'transaction_fee_returned' OR type_code = 'additional_fee' OR type_code = 'additional_fee_returned' ) AND  ( created_at BETWEEN '" . $filter['start_date'] . "' AND '". $filter['end_date'] ."' ) GROUP BY currency_code")->fetchAll();
    
    }
    
}
