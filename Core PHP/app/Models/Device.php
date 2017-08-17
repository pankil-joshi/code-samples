<?php

namespace App\Models;

use Quill\Database as Database;

class Device extends Database {

    public $tableName = 'user_devices';

    public function saveByUuid($device) {

        $device['is_installed'] = 1;
        $device['updated_at'] = gmdate('Y-m-d H:i:s');
        return $this->table()->upsert($device);
    }

    public function getByUserId($userId) {

        return $this->table()->select()->where(array('user_id' => $userId, 'logout' => 0))->all();
    }

    public function getAllByUserId($userId, $filter = array(), $order = array(), $offset = '', $limit = 10, $search = array()) {

        $orderBy = "{$this->tableName}.updated_at";


        if (empty($order['order']) || $order['order'] == 'undefined') {

            $order['order'] = 'DESC';
        }

        if (!empty($filter['key']) && $filter['key'] == 'week') {

            $filter = array('DATE(' . $this->tableName . '.updated_at)' => array(gmdate('Y-m-d', strtotime('-1 week UTC')), gmdate('Y-m-d')));
        } elseif (!empty($filter['key']) && $filter['key'] == 'fortnight') {

            $filter = array('DATE(' . $this->tableName . '.updated_at)' => array(gmdate('Y-m-d', strtotime('-14 days UTC')), gmdate('Y-m-d')));
        } elseif (!empty($filter['key']) && $filter['key'] == 'month') {

            $filter = array('DATE(' . $this->tableName . '.updated_at)' => array(gmdate('Y-m-d', strtotime('-1 month UTC')), gmdate('Y-m-d')));
        } elseif (!empty($filter['key']) && $filter['key'] == 'last_month') {

            $filter = array('DATE(' . $this->tableName . '.updated_at)' => array(gmdate('Y-m-d', strtotime('first day of -1 month UTC')), gmdate('Y-m-d', strtotime('last day of -1 month UTC'))));
        } elseif (!empty($filter['key']) && $filter['key'] == 'custom') {

            $filter = array('DATE(' . $this->tableName . '.updated_at)' => array($filter['start_date'], $filter['end_date']));
        } else {

            $filter = array();
        }

        if ($offset === null || $offset === '') {

            $offset = 0;
        } else {

            $offset = ($offset + 1) * $limit;
        }

        if (!empty($search)) {

            $search = array("{$this->tableName}.model like " => '%' . $search . '%');
        } else {

            $search = array();
        }
        return $this->table()->select()->where(array('user_id' => $userId))
                        ->whereBetween($filter)
                        ->where($search, true)
                        ->orderBy($orderBy, $order['order'])
                        ->limit(array($offset, $limit))
                        ->all();
    }

    public function getAllCountByUserId($userId, $filter = array(), $search = array()) {

        if (!empty($search)) {

            $search = array("{$this->tableName}.model like " => '%' . $search . '%');
        } else {

            $search = array();
        }
        if (!empty($filter['key']) && $filter['key'] == 'week') {

            $filter = array('DATE(' . $this->tableName . '.updated_at)' => array(gmdate('Y-m-d', strtotime('-1 week UTC')), gmdate('Y-m-d')));
        } elseif (!empty($filter['key']) && $filter['key'] == 'fortnight') {

            $filter = array('DATE(' . $this->tableName . '.updated_at)' => array(gmdate('Y-m-d', strtotime('-14 days UTC')), gmdate('Y-m-d')));
        } elseif (!empty($filter['key']) && $filter['key'] == 'month') {

            $filter = array('DATE(' . $this->tableName . '.updated_at)' => array(gmdate('Y-m-d', strtotime('-1 month UTC')), gmdate('Y-m-d')));
        } elseif (!empty($filter['key']) && $filter['key'] == 'last_month') {

            $filter = array('DATE(' . $this->tableName . '.updated_at)' => array(gmdate('Y-m-d', strtotime('first day of -1 month UTC')), gmdate('Y-m-d', strtotime('last day of -1 month UTC'))));
        } elseif (!empty($filter['key']) && $filter['key'] == 'custom') {

            $filter = array('DATE(' . $this->tableName . '.updated_at)' => array($filter['start_date'], $filter['end_date']));
        } else {

            $filter = array();
        }
        return $this->table()->select('count(id)')
                        ->where(array('user_id' => $userId))
                        ->whereBetween($filter)
                        ->where($search, true)->field();
    }

}
