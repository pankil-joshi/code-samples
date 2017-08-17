<?php

namespace App\Models;

use Quill\Database as Database;

class Invoice extends Database {

    protected $tableName = 'invoices';

    public function save($invoice) {

        if (!empty($invoice['id'])) {

            $invoice['updated_at'] = gmdate('Y-m-d H:i:s');

            return $this->table()->where(array('id' => $invoice['id']))->update($invoice, true);
        } else {

            $invoice['id'] = null;
            $invoice['updated_at'] = gmdate('Y-m-d H:i:s');
            $invoice['created_at'] = gmdate('Y-m-d H:i:s');

            return $this->table()->insert($invoice, true);
        }
    }
    
    public function updateByInvoiceId($invoice) {

            $invoice['updated_at'] = gmdate('Y-m-d H:i:s');

            return $this->table()->where(array('stripe_invoice_id' => $invoice['stripe_invoice_id']))->update($invoice, true);
    }    

    public function getSettlementInvoicesByUserId($userId, $filter = array(), $order = array(), $offset = '', $limit = 10, $type = '') {

        if (!empty($order['order_by']) && $order['order_by'] == 'value') {

            $orderBy = $this->tableName . '.amount';
        } else {

            $orderBy = $this->tableName . '.created_at';
        }

        if (empty($order['order'])) {

            $order['order'] = 'DESC';
        }

        if (!empty($filter['key']) && $filter['key'] == 'week') {

            $filter = array('DATE(' . $this->tableName . '.created_at)' => array(gmdate('Y-m-d', strtotime('-1 week UTC')), gmdate('Y-m-d')));
        } elseif (!empty($filter['key']) && $filter['key'] == 'fortnight') {

            $filter = array('DATE(' . $this->tableName . '.created_at)' => array(gmdate('Y-m-d', strtotime('-14 days UTC')), gmdate('Y-m-d')));
        } elseif (!empty($filter['key']) && $filter['key'] == 'month') {

            $filter = array('DATE(' . $this->tableName . '.created_at)' => array(gmdate('Y-m-d', strtotime('-1 month UTC')), gmdate('Y-m-d')));
        } elseif (!empty($filter['key']) && $filter['key'] == 'last_month') {

            $filter = array('DATE(' . $this->tableName . '.created_at)' => array(gmdate('Y-m-d', strtotime('first day of -1 month UTC')), gmdate('Y-m-d', strtotime('last day of -1 month UTC'))));
        } elseif (!empty($filter['key']) && $filter['key'] == 'custom') {

            $filter = array('DATE(' . $this->tableName . '.created_at)' => array($filter['start_date'], $filter['end_date']));
        } else {

            $filter = array();
        }

        if ($offset === null || $offset === '') {

            $offset = 0;
        } else {

            $offset = ($offset + 1) * $limit;
        }
        if (!empty($type) && $type == 'subscription') {

            $type = array($this->tableName . '.type =' => 'subscription');
        } elseif (!empty($type) && $type == 'settlement') {

            $type = array($this->tableName . '.type =' => 'fees');
        } else {

            $type = array();
        }
        return $this->table()->select()
                        ->where(array('user_id' => $userId))
                        ->whereBetween($filter)
                        ->where($type, true)
                        ->orderBy($orderBy, $order['order'])
                        ->limit(array($offset, $limit))
                        ->all();
    }

    public function getById($id, $userId = null) {

        if (!empty($userId)) {

            $user = array('user_id' => $userId);
        } else {

            $user = array();
        }
        return $this->table()->select()
                        ->where(array('id' => $id))
                        ->where($user)
                        ->all();
    }
    
    public function getByInvoiceId($invoiceId) {

        return $this->table()->select()
                        ->where(array('stripe_invoice_id' => $invoiceId))
                        ->all();
    }    

    /*
     * Get merchants subscription total between dates
     */

    public function getMerchantsSubscriptionTotalByDates($filter) {
        return $this->table()->select('sum(amount) as revenue_total, base_currency_code')
                        ->whereBetween(array('DATE(created_at)' => array($filter['start_date'], $filter['end_date'])))
                        ->one();
    }

}
