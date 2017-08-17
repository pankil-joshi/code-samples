<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Quill\Database as Database;

/**
 * Description of MerchantDetails
 *
 * @author harinder
 */
class MerchantDetails extends Database {

    public $tableName = 'merchant_details';

    public function getByMediaId($mediaId) {

        return $this->table()->select($this->tableName . '.*')
                        ->join('media', 'media.user_id', $this->tableName . '.user_id')
                        ->where(array('media.id' => $mediaId))->one();
    }
    
    public function getByStripeAccountId($stripeAccountId) {

        return $this->table()->select()->where(array('stripe_account_id' => $stripeAccountId))->one();
    }    

    public function save($merchant) {
        
        $merchant['updated_at'] = gmdate('Y-m-d H:i:s');
        
        return $this->table()->upsert($merchant);
    }
    
    public function getByUserId($userId) {

        return $this->table()->select($this->tableName . '.*')
                ->selectGroup(array('instagram_username'), 'users', 'user')
                ->selectGroup(array('name', 'rate', 'transaction_fee', 'payment_threshold', 'eu_transaction_fee'), 'subscription_packages', 'subscripton')
                ->join('users', $this->tableName . '.user_id', 'users.id')
                ->join('subscription_packages', $this->tableName . '.subscription_package_id', 'subscription_packages.id')
                ->where(array('user_id' => $userId))->one();
    }

    public function getAllMerchants() {

        return $this->table()->select()->all();
    }    

}
