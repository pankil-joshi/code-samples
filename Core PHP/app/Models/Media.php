<?php

namespace App\Models;

use Quill\Database as Database;

class Media extends Database {

    protected $tableName = 'media';
    protected $primarykey = 'id';

    public function save($media) {

        if (!empty($media['id'])) {

            $media['updated_at'] = gmdate('Y-m-d H:i:s');

            return $this->table()->where(array('id' => $media['id']))->update($media);
        } else {

            $media['updated_at'] = gmdate('Y-m-d H:i:s');
            $media['created_at'] = gmdate('Y-m-d H:i:s');

            return $this->table()->insert($media);
        }
    }

    public function getAllPlatinum($filter = array(), $order = array(), $offset = '', $limit = 10, $search = array()) {

        if (!empty($order['order_by']) && $order['order_by'] == 'alpha') {

            $orderBy = 'media.title';
            $order['order'] = 'ASC';
        } elseif (!empty($order['order_by']) && $order['order_by'] == 'value') {

            $orderBy = 'media.price';
        } else {

            $orderBy = 'media.created_at';
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

        if (!empty($search)) {

            $search = array('media.title like ' => '%' . $search . '%');
        } else {

            $search = array();
        }
        return $this->table()->select("{$this->tableName}.*")
                        ->join('merchant_details', "{$this->tableName}.user_id", 'merchant_details.user_id')
                        ->where(array("{$this->tableName}.is_active !=" => 0, "{$this->tableName}.is_deleted =" => 0), true)
                        ->whereBetween($filter)
                        ->whereIn(array('merchant_details.subscription_package_id' =>  array('4', '16')))  
                        ->where($search, true)
                        ->orderBy($orderBy, $order['order'])
                        ->limit(array($offset, $limit))
                        ->all();
    }

    public function getAllActive($filter = array(), $order = array(), $offset = '', $limit = 10, $search = array()) {

        if (!empty($order['order_by']) && $order['order_by'] == 'alpha') {

            $orderBy = 'media.title';
            $order['order'] = 'ASC';
        } elseif (!empty($order['order_by']) && $order['order_by'] == 'value') {

            $orderBy = 'media.price';
        } else {

            $orderBy = 'media.created_at';
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

        if (!empty($search)) {

            $search = array('media.title like ' => '%' . $search . '%');
        } else {

            $search = array();
        }

        if ($limit == 0) {

            return $this->table()->select('media.*, case when users.merchant_deactivate = "1" then "0" when media.is_deleted = "1" then "0" when media.is_active = "0" then "0" when users.is_deleted = "1"  then "0" when merchant_details.subscription_status = "0" then "0" when merchant_details.stripe_charges_enabled = "0" then "0" else media.is_available END AS is_available ')
                            ->join('users', 'media.user_id', 'users.id')
                            ->join('merchant_details', 'media.user_id', 'merchant_details.user_id')
                            ->where(array("{$this->tableName}.is_active !=" => 0, "{$this->tableName}.is_deleted =" => 0), true)
                            ->whereBetween($filter)
                            ->where($search, true)
                            ->orderBy($orderBy, $order['order'])
                            ->all();
        } else {

            return $this->table()->select('media.*, case when users.merchant_deactivate = "1" then "0" when media.is_deleted = "1" then "0" when media.is_active = "0" then "0" when users.is_deleted = "1"  then "0" when merchant_details.subscription_status = "0" then "0" when merchant_details.stripe_charges_enabled = "0" then "0" else media.is_available END AS is_available ')
                            ->join('users', 'media.user_id', 'users.id')
                            ->join('merchant_details', 'media.user_id', 'merchant_details.user_id')
                            ->where(array("{$this->tableName}.is_active !=" => 0, "{$this->tableName}.is_deleted =" => 0), true)
                            ->whereBetween($filter)
                            ->where($search, true)
                            ->limit(array($offset, $limit))
                            ->orderBy($orderBy, $order['order'])
                            ->all();
        }
    }

    public function getAllPending() {

        return $this->table()->select()->where(array('is_active =' => 0, 'is_deleted =' => 0, 'is_archived =' => 0), true)->all();
    }

    public function getAllPendingByUserId() {

        return $this->table()->select("group_concat({$this->tableName}.id) as media_ids, users.id, users.instagram_access_token")
                        ->join('users', "{$this->tableName}.user_id", 'users.id')
                        ->where(array("{$this->tableName}.is_active =" => 0, "{$this->tableName}.is_deleted =" => 0, "{$this->tableName}.is_archived =" => 0), true)
                        ->groupBy("{$this->tableName}.user_id")
                        ->all();
    }

    public function getById($id, $by = array()) {

        if (!empty($by) && $by['field'] == 'instagram_username') {

            $where = array("{$this->tableName}.instagram_username" => $by['value']);
        } elseif (!empty($by) && $by['field'] == 'user_id') {

            $where = array("{$this->tableName}.user_id" => $by['value']);
        } else {

            $where = array();
        }

        return $this->table()->select('media.*, case when users.merchant_deactivate = "1" then "0" when media.is_deleted = "1" then "0" when media.is_active = "0" then "0" when users.is_deleted = "1"  then "0" when merchant_details.subscription_status = "0" then "0" when merchant_details.stripe_charges_enabled = "0" then "0" else media.is_available END AS is_available ')
                        ->selectGroup(array('instagram_username', 'instagram_followed_by', 'instagram_profile_picture', 'merchant_deactivate', 'customer_deactivate'), 'users', 'user')
                        ->join('users', 'media.user_id', 'users.id')
                        ->join('merchant_details', 'media.user_id', 'merchant_details.user_id')
                        ->join('media_stock_items', 'media.id', 'media_stock_items.media_id')
                        ->where($where)
                        ->andOrWhere(array('media.id' => $id, 'media.path' => $id))
                        ->one();
    }

    public function getAllByUserId($userId, $filter = array(), $order = array(), $offset = '', $limit = 10, $status = '', $search = array()) {

        if (!empty($order['order_by']) && $order['order_by'] == 'alpha') {

            $orderBy = 'media.title';
            $order['order'] = 'ASC';
        } elseif (!empty($order['order_by']) && $order['order_by'] == 'value') {

            $orderBy = 'media.price';
        } else {

            $orderBy = 'media.created_at';
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

        if (!empty($status)) {

            if ($status == 'published') {

                $status = array("{$this->tableName}.is_active =" => '1');
            } elseif ($status == 'disabled') {

                $status = array("{$this->tableName}.is_available <>" => '1');
            } else {

                $status = array();
            }
        } else {

            $status = array();
        }

        if ($offset === null || $offset === '') {

            $offset = 0;
        } else {

            $offset = ($offset + 1) * $limit;
        }

        if (!empty($search)) {

            $search = array('media.title like ' => '%' . $search . '%');
        } else {

            $search = array();
        }

        return $this->table()->select('media.*')
                        ->selectGroup(array('instagram_username', 'instagram_followed_by', 'instagram_profile_picture'), 'users', 'user')
                        ->join('users', 'media.user_id', 'users.id')
                        ->whereBetween($filter)
                        ->where($search, true)
                        ->where($status, true)
                        ->where(array('media.user_id' => $userId, 'media.is_deleted' => 0))
                        ->orderBy($orderBy, $order['order'])
                        ->limit(array($offset, $limit))
                        ->all();
    }

    public function hasVariant($id) {

        return $this->table()->select('has_variant')->where(array('id' => $id))->field();
    }

    public function getStock($id) {

        return $this->table()->select('in_stock')->where(array('id' => $id))->field();
    }

    public function softDelete($media) {

        return $this->table()->where(array('id' => $media['id'], 'user_id' => $media['user_id']))->update(array('is_deleted' => 1, 'updated_at' => gmdate('Y-m-d H:i:s')));
    }

    public function addView($id) {

        return $this->table()->where(array('id' => $id))->incrementField('views');
    }

    public function getTaggedByUserId($userId, $filter = array(), $order = array(), $offset = '', $limit = 10, $search = '') {

        if (!empty($order['order_by']) && $order['order_by'] == 'alpha') {

            $orderBy = 'media.title';
            $order['order'] = 'ASC';
        } elseif (!empty($order['order_by']) && $order['order_by'] == 'value') {

            $orderBy = 'media.price';
        } else {

            $orderBy = 'media_comments.created_at';
        }

        if (empty($order['order'])) {

            $order['order'] = 'DESC';
        }

        if (!empty($filter['key']) && $filter['key'] == 'week') {

            $filter = array('DATE(media_comments.created_at)' => array(gmdate('Y-m-d', strtotime('-1 week UTC')), gmdate('Y-m-d')));
        } elseif (!empty($filter['key']) && $filter['key'] == 'fortnight') {

            $filter = array('DATE(media_comments.created_at)' => array(gmdate('Y-m-d', strtotime('-14 days UTC')), gmdate('Y-m-d')));
        } elseif (!empty($filter['key']) && $filter['key'] == 'month') {

            $filter = array('DATE(media_comments.created_at)' => array(gmdate('Y-m-d', strtotime('-1 month UTC')), gmdate('Y-m-d')));
        } elseif (!empty($filter['key']) && $filter['key'] == 'last_month') {

            $filter = array('DATE(media_comments.created_at)' => array(gmdate('Y-m-d', strtotime('first day of -1 month UTC')), gmdate('Y-m-d', strtotime('last day of -1 month UTC'))));
        } elseif (!empty($filter['key']) && $filter['key'] == 'custom') {

            $filter = array('DATE(media_comments.created_at)' => array($filter['start_date'], $filter['end_date']));
        } else {

            $filter = array();
        }

        if ($offset === null || $offset === '') {

            $offset = 0;
        } else {

            $offset = ($offset + 1) * $limit;
        }

        if (!empty($search)) {

            $search = array($this->tableName . '.title like ' => '%' . $search . '%');
        } else {

            $search = array();
        }
        return $this->table()->select($this->tableName . '.*, ' . $this->tableName . '.instagram_username as merchant_instagram_username, media.is_deleted as media_deleted, media_comments.created_at as tagged_at, media_comments.comment_id, merchant_currency_rates.conversion_rate_usd_base as merchant_currency_conversion_factor, customer_currency_rates.conversion_rate_usd_base as customer_currency_conversion_factor, countries.currency_code as customer_currency_code')
                        ->distinct()
                        ->join('media_comments', 'media_comments.instagram_media_id', $this->tableName . '.media_id')
                        ->join('users', 'users.instagram_user_id', 'media_comments.instagram_user_id')
                        ->leftJoin('countries', "users.country", 'countries.country_code')
                        ->leftJoin('currency_rates as merchant_currency_rates', "{$this->tableName}.base_currency_code", 'merchant_currency_rates.currency_code')
                        ->leftJoin('currency_rates as customer_currency_rates', 'countries.currency_code', 'customer_currency_rates.currency_code')
                        ->whereBetween($filter)
                        ->where($search, true)
                        ->where(array('users.id' => $userId, 'media_comments.order_completed' => 0, 'media.is_deleted' => 0))
                        ->orderBy($orderBy, $order['order'])
                        ->limit(array($offset, $limit))
                        ->all();
    }

    public function getTitle($id) {

        return $this->getCache($id)['title'];
    }

    public function getThumbnailImage($id) {

        return $this->getCache($id)['image_thumbnail'];
    }

    public function getMerchantId($id) {

        return $this->getCache($id)['user_id'];
    }

    public function getAvailability($id) {

        return $this->getCache($id)['is_available'];
    }

}
