<?php

namespace App\Models;

use Quill\Database as Database;

class User extends Database {

    public $tableName = 'users';

    public function save($user) {

        if (isset($user['id']) && $user['id']) {

            $user['updated_at'] = gmdate('Y-m-d H:i:s');

            return $this->table()->where(array('id' => $user['id']))->update($user, true);
        } else {

            $user['created_at'] = gmdate('Y-m-d H:i:s');
            $user['updated_at'] = gmdate('Y-m-d H:i:s');

            return $this->table()->insert($user, true);
        }
    }

    public function countCallsByAccessToken($accessToken) {

        $clearedAt = $this->table()->select('instagram_access_token_calls_cleared_at')->where(array('instagram_access_token' => $accessToken))->field();

        if (empty($clearedAt) || ($clearedAt + 3600) < time()) {

            return $this->table()->where(array('instagram_access_token' => $accessToken))->update(array('instagram_access_token_calls_cleared_at' => time(), 'instagram_access_token_calls' => 1));
        } else {

            return $this->table()->where(array('instagram_access_token' => $accessToken))->incrementField('instagram_access_token_calls');
        }
    }

    public function getByInstagramId($instagramId) {

        return $this->table()->select()->where(array('instagram_user_id' => $instagramId))->one();
    }

    public function validateCustomer($id) {

        return $this->table()->select()->where(array("{$this->tableName}.id" => $id, 'is_deleted' => 0, 'customer_deactivate' => 0))
                        ->one();
    }

    public function getById($id) {

        return $this->table()->select("{$this->tableName}.*, merchant_currency_rates.conversion_rate_usd_base as merchant_currency_conversion_factor, customer_currency_rates.conversion_rate_usd_base as customer_currency_conversion_factor, countries.currency_code")
                        ->selectGroup(array(
                            'stripe_subscription_id',
                            'legal_entity_business_tax_id',
                            'legal_entity_business_tax_id',
                            'taxable_countries',
                            'tax_on_postage',
                            'business_currency',
                            'stripe_account_id',
                            'legal_entity_business_name',
                            'legal_entity_address_line1',
                            'legal_entity_address_city',
                            'legal_entity_address_postal_code',
                            'legal_entity_address_state',
                            'legal_entity_address_line1',
                            'business_country',
                            'business_telephone_prefix',
                            'legal_entity_phone_number',
                            'business_email',
                            'subscription_package_id'
                                ), 'merchant_details', 'merchant')
                        ->selectGroup(array(
                            'top_seller_badge',
                            'verified_badge',
                            'support_level_id',
                            'name'
                                ), 'subscription_packages', 'subscription_packages')
                        ->leftJoin('merchant_details', "{$this->tableName}.id", 'merchant_details.user_id')
                        ->leftJoin('subscription_packages', 'merchant_details.subscription_package_id', 'subscription_packages.id')
                        ->leftJoin('countries', "{$this->tableName}.country", 'countries.country_code')
                        ->leftJoin('currency_rates as merchant_currency_rates', 'merchant_details.business_currency', 'merchant_currency_rates.currency_code')
                        ->leftJoin('currency_rates as customer_currency_rates', 'countries.currency_code', 'customer_currency_rates.currency_code')
                        ->where(array("{$this->tableName}.id" => $id))
                        ->one();
    }

    public function getByStripeSubscriptionId($stripeSubscriptionId) {

        return $this->table()->select("{$this->tableName}.*")
                        ->selectGroup(array(
                            'stripe_subscription_id',
                            'legal_entity_business_tax_id',
                            'legal_entity_business_tax_id',
                            'taxable_countries',
                            'business_currency',
                            'stripe_account_id',
                            'legal_entity_business_name',
                            'legal_entity_address_line1',
                            'legal_entity_address_city',
                            'legal_entity_address_postal_code',
                            'legal_entity_address_state',
                            'legal_entity_address_line1',
                            'business_country',
                            'business_email'
                                ), 'merchant_details', 'merchant')
                        ->selectGroup(array(
                            'top_seller_badge',
                            'verified_badge',
                            'name',
                            'rate'
                                ), 'subscription_packages', 'subscription_packages')
                        ->leftJoin('merchant_details', "{$this->tableName}.id", 'merchant_details.user_id')
                        ->leftJoin('subscription_packages', 'merchant_details.subscription_package_id', 'subscription_packages.id')
                        ->where(array('merchant_details.stripe_subscription_id' => $stripeSubscriptionId))
                        ->one();
    }

    public function getByEmail($email) {

        return $this->table()->select()->where(array('email' => $email))->one();
    }

    public function getStripeCustomerId($id) {

        return $this->getCache($id)['stripe_customer_id'];
    }

    public function getFullNameById($id) {

        return $this->table()->select('first_name, last_name')->where(array('id' => $id))->one();
    }

    public function getOrderCustomes($merchant_id) {

        return $this->table()->select($this->tableName . '.*, count(orders.id) as order_count, sum(orders.total) as spend, orders.created_at as order_date, count(order_suborders.id) as suborder_count')
                        ->join('orders', $this->tableName . '.id', 'orders.user_id')
                        ->join('order_suborders', 'orders.id', 'order_suborders.order_id')
                        ->where(array('order_suborders.merchant_id' => $merchant_id))
                        ->where(array('order_suborders.status' => 'in_progress'))
                        ->orwhere(array('order_suborders.status' => 'shipped'))
                        ->orderBy('orders.id', 'ASC')
                        ->all(true, true);
    }

    public function getAllMerchants() {

        return $this->table()->select("{$this->tableName}.*")
                        ->selectGroup(array(
                            'stripe_subscription_id',
                            'legal_entity_business_tax_id',
                            'legal_entity_business_tax_id',
                            'taxable_countries',
                            'business_currency',
                            'stripe_account_id',
                            'legal_entity_business_name',
                            'legal_entity_address_line1',
                            'legal_entity_address_city',
                            'legal_entity_address_postal_code',
                            'legal_entity_address_state',
                            'legal_entity_address_line1',
                            'business_country',
                            'business_email',
                            'stripe_transfers_disabled_first_mail_sent_at'
                                ), 'merchant_details', 'merchant')
                        ->leftJoin('merchant_details', "{$this->tableName}.id", 'merchant_details.user_id')
                        ->where(array('is_merchant' => '1'))->all();
    }

    public function countAll($filter, $search = '') {

        if (isset($filter['key']) && $filter['key'] == 'custom') {
            $filter = array('DATE(' . $this->tableName . '.created_at)' => array($filter['start_date'], $filter['end_date']));
        } else {
            $filter = array();
        }

        if (!empty($search)) {

            $search = array(
                $this->tableName . '.first_name like ' => '%' . $search . '%',
                $this->tableName . '.id like ' => '%' . $search . '%',
                $this->tableName . '.last_name like ' => '%' . $search . '%',
                $this->tableName . '.instagram_username like ' => '%' . $search . '%',
                $this->tableName . '.email like ' => '%' . $search . '%'
            );
        } else {

            $search = array();
        }
        return $this->table()->select('count(id) as users_count')
                        //->orWhere(array("{$this->tableName}.is_active" => '1', "{$this->tableName}.instagram_username" => ''))
                        ->whereBetween($filter)
                        ->andOrWhere($search, true)
                        ->where(array("{$this->tableName}.is_deleted" => 0))
                        ->field();
    }

    public function countAllMerchants($filter) {

        if ($filter == 'custom') {

            $filter = array('DATE(created_at)' => array($filter['start_date'], $filter['end_date']));
        } else {

            $filter = array();
        }

        return $this->table()->select('count(id) as users_count')
                        ->where(array('is_merchant' => '1', 'is_deleted' => 0))
                        ->whereBetween($filter)
                        ->field();
    }

//    public function getAllMerchantsByDates($filter) {
//        if($filter === null || $filter === '') {
//            $filter = array();
//        } else {
//            $filter = array('DATE(created_at)' => array($filter['start_date'], $filter['end_date']));
//        }
//        return $this->table()->select('count(id) as merchants_count')
//                ->where(array('is_merchant' => '1', 'is_deleted' => '0'))
//                ->whereBetween($filter)
//                ->all();
//    }

    public function getList($filter, $order, $offset, $limit = 10, $search = array()) {

        if (isset($filter['key']) && $filter['key'] == 'custom') {
            $filter = array('DATE(' . $this->tableName . '.created_at)' => array($filter['start_date'], $filter['end_date']));
        } else {
            $filter = array();
        }

        if ($offset === null || $offset === '') {

            $offset = 0;
        } else {

            $offset = ($offset + 1) * $limit;
        }

        if (empty($order['order'])) {
            $order['order'] = 'DESC';
        }
        if (!empty($search)) {

            $search = array(
                $this->tableName . '.first_name like ' => '%' . $search . '%',
                $this->tableName . '.id like ' => '%' . $search . '%',
                $this->tableName . '.last_name like ' => '%' . $search . '%',
                $this->tableName . '.instagram_username like ' => '%' . $search . '%',
                $this->tableName . '.email like ' => '%' . $search . '%'
            );
        } else {

            $search = array();
        }

        if ($limit == 0) {

            return $this->table()->select($this->tableName . '.*, countries.currency_code, countries.country_name')
                            ->selectGroup(array(
                                'top_seller_badge',
                                'verified_badge',
                                'name',
                                'rate'
                                    ), 'subscription_packages', 'subscription_packages')
                            ->leftJoin('merchant_details', "{$this->tableName}.id", 'merchant_details.user_id')
                            ->leftJoin('subscription_packages', 'merchant_details.subscription_package_id', 'subscription_packages.id')
                            ->leftjoin('countries', 'countries.country_code', $this->tableName . '.country')
                            //->orWhere(array("{$this->tableName}.is_active" => '1', "{$this->tableName}.instagram_username" => ''))
                            ->whereBetween($filter)
                            ->where(array("{$this->tableName}.is_deleted" => 0))
                            ->andOrWhere($search, true)
                            ->orderBy('created_at', $order['order'])
                            ->all();
        } else {

            return $this->table()->select($this->tableName . '.*, countries.currency_code, countries.country_name')
                            ->selectGroup(array(
                                'top_seller_badge',
                                'verified_badge',
                                'name',
                                'rate'
                                    ), 'subscription_packages', 'subscription_packages')
                            ->leftJoin('merchant_details', "{$this->tableName}.id", 'merchant_details.user_id')
                            ->leftJoin('subscription_packages', 'merchant_details.subscription_package_id', 'subscription_packages.id')
                            ->leftjoin('countries', 'countries.country_code', $this->tableName . '.country')
                            //->orWhere(array("{$this->tableName}.is_active" => '1', "{$this->tableName}.instagram_username" => ''))
                            ->whereBetween($filter)
                            ->where(array("{$this->tableName}.is_deleted" => 0))
                            ->andOrWhere($search, true)
                            ->orderBy('created_at', $order['order'])
                            ->limit(array($offset, $limit))
                            ->all();
        }
    }

//     public function getMerchantsListByDates($filter, $order, $offset, $limit = 10) {
//        if($filter === null || $filter === '') {
//            $filter = array();
//        } else {
//            $filter = array('DATE(created_at)' => array($filter['start_date'], $filter['end_date']));
//        }        
//               
//        if($offset === null || $offset === ''){
//            
//            $offset = 0;
//            
//        } else {
//            
//            $offset = ($offset + 1) * $limit;
//            
//        } 
//        
//        if(empty($order['order'])) {
//            $order['order'] = 'DESC';
//        } 
//        return $this->table()->select($this->tableName . '.*, countries.currency_code, countries.country_name')
//                ->leftjoin('countries', 'countries.country_code', $this->tableName . '.country')
//                ->where(array('is_merchant' => '1', 'is_deleted' => '0'))
//                //->whereBetween($filter)
//                ->orderBy('created_at', $order['order'])
//                ->limit(array($offset, $limit))                
//                ->all();
//    }

    public function getAccessTokenByInstagramUsername($username) {

        return $this->table()->select('instagram_access_token')->where(array('instagram_username' => $username))->field();
    }

    public function getUserMinId($user_id) {
        return $this->table()->select('min_id')->where(array('id' => $user_id))->field();
    }

}
