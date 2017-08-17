<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controllers\Api\Mobile;

use App\Controllers\Api\Mobile\MobileBaseController as MobileBaseController;
use Quill\Factories\CoreFactory;
use Quill\Factories\ModelFactory;
use Quill\Factories\ServiceFactory;

/**
 * Inventory Controller, contains all methods related to inventory management.
 * 
 * @package App\Controllers\Api\Mobile
 * @author Pankil Joshi <pankil@prologictechnologies.in>
 * @version 1.0
 * @uses Quill\Factories\CoreFactory
 * @uses Quill\Factories\ModelFactory
 * @extend App\Controllers\Api\Mobile\MobileBaseController
 */
class InventoryController extends MobileBaseController {

    function __construct($app = NULL) {

        // Call to parent class constructer.        
        parent::__construct($app);

        /**
         * Required model classes instantiated.
         */
        $this->models = ModelFactory::boot(array(
                    'MediaStockItem',
                    'User',
                    'MediaVariantOption'
        ));

        /**
         * Required core classes instantiated.
         */
        $this->core = CoreFactory::boot(array('Response', 'View'));

        $this->services = ServiceFactory::boot(array('EmailNotification'));
        $this->app->config(load_config_one('emailTemplates'));
        $this->app->config(load_config_one('url'));
    }

    /**
     * Get low stock vraiants.
     * 
     * @uses \App\Models\MediaStockItem->getLowStockVariants()
     */
    public function getLowStockVariants() {

        foreach ($this->models->mediaStockItem->getLowStockVariants() as $row) {
            
            $user = $this->models->user->getById($row['user_id']);
            
            if ($user['notify_push_merchant_low_stock']) {
                
                $data = array('title' => 'Product Manager', 'message' => 'Your product ' . $row['title'] . ' is lower than the stock limit of ' . $row['min_stock_level'] . ' units.', 'extra' => array('link' => 'ipaid://productManager.html'));

                $pushNotification = new \App\Services\PushNotification();
                $pushNotification->sendToUserDevices($row['user_id'], $data);

                $app = array(
                    'base_assets_url' => $this->app->config('base_assets_url'),
                    'domain' => $this->app->config('domain'),
                    'base_url' => $this->app->config('base_url'),
                    'app_title' => $this->app->config('app_title'),
                    'master_hashtag' => $this->app->config('master_hashtag'),
                    'feedback_email' => $this->app->config('feedback_email'),
                    'sales_email' => $this->app->config('sales_email'),
                    'support_email' => $this->app->config('support_email'),
                    'instagram_account_url' => $this->app->config('instagram_account_url'),
                    'twitter_account_url' => $this->app->config('twitter_account_url'),
                    'support_phone_uk' => $this->app->config('support_phone_uk'),
                    'support_phone_int' => $this->app->config('support_phone_int')
                );

                $mediaVariantOptions = $this->models->mediaVariantOption->getAllByMediaId($row['id'], $row['variant_id']);
                $variantLabel = array();
                foreach ($mediaVariantOptions as $mediaVariantOption) {

                    $variantLabel[] = $mediaVariantOption['option_value_value'];
                }

                $row['label'] = implode(' / ', $variantLabel);

                $this->services->emailNotification->sendMail(array('email' => $user['merchant_business_email'], 'name' => $user['first_name'] . ' ' . $user['last_name']), $this->app->config('low_stock_email_subject'), $this->core->view->make('email/low-product-quantity.php', array('user' => $user, 'app' => $app, 'media' => $row, 'countries' => load_config_one('countries')), false));
            }
        }
    }

}
