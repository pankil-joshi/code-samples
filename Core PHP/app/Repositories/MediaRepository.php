<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories;

use Quill\Factories\ModelFactory;
use Quill\Exceptions\BaseException;

/**
 * Description of Media
 *
 * @author harinder
 */
class MediaRepository {

    private $models = array();

    public function __construct() {

        ModelFactory::setNamespace('\\App\\Models\\');

        $this->models = ModelFactory::boot(array(
                    'Media',
                    'MediaCategories',
                    'MediaAttributes',
                    'MediaVariant',
                    'MediaVariantOption',
                    'MediaPostageOptions',
                    'MediaPostageRuleAttributeValue',
                    'MediaTax',
                    'MerchantDetails',
                    'OrderRating',
                    'User',
                    'OrderSuborder'
        ));
    }

    public function get($id = NULL) {

        $media = $this->models->media->getById($id);

        if ($media && !$media['is_deleted']) {

            $prices = $this->models->mediaVariant->getPricesByMediaId($media['id']);

            $media['min_price'] = $prices['min_price'];
            $media['max_price'] = $prices['max_price'];           
            
            $media['max_price'] = $prices['max_price'];            

            $media['variants'] = $this->getVariants($media['id']);

            $user = $this->models->user->getById($media['user_id']);
            $media['merchant'] = $user;
            //$media['tax_enabled'] = ($user['merchant_legal_entity_business_tax_id'] != '') ? 1 : 0;
            $merchantDetail = $this->models->merchantDetails->getByUserId($media['user_id']);
            $media['tax_enabled'] = $merchantDetail['tax_enabled'];
            $config['app'] = load_config_one('app', array('path'));
            $media['order_hold_period'] = $config['app']['order_hold_period'];
            
//            $media['is_available'] = ($media['is_available'] == 0) ? $media['is_available'] : (int)!$user['merchant_deactivate'];
            
            $media['tax'] = $this->models->mediaTax->getByMediaId($media['id']);
            $media['tax']['tax_on_postage'] = $user['merchant_tax_on_postage'];
            $mediaAttributes = $this->models->mediaAttributes->getAll($media['id']);

            $media['attributes'] = $mediaAttributes;

            $media['categories'] = $this->models->mediaCategories->getAllByMediaId($media['id']);
            $postageOptions = $this->getAllPostageOptionsByMediaId($media['id']);
            $media['postageOptions'] = $postageOptions;
            $media['worldwide_shipping'] = 0;
            $media['express_delivery'] = 0;
            foreach ($postageOptions as $postageOption) {
                
                if($postageOption['geography'] == '*') {
                    
                    $media['worldwide_shipping'] = 1;
                }
                
                if($postageOption['duration'] <= $config['app']['express_delivery_duration']) {
                    
                    $media['express_delivery'] = 1;
                }                
            }
            
            $merchantRating = round($this->models->orderRating->getAverageByMerchantId($media['user_id']));
            $media['seller_rating'] = $merchantRating;
            $media['top_seller'] = ((($merchantRating/5)*100) >= 75 && $this->models->orderSuborder->orderCountLastTenDaysByUserId($media['user_id']) >= 10 && $user['subscription_packages_top_seller_badge'])? 1 : 0;
            $media['verified_seller'] = $user['subscription_packages_verified_badge'];
            $media['merchant_currency_conversion_factor'] = $user['merchant_currency_conversion_factor'];
            $media['conversion_charges_rate'] = 0.02;
            
            return $media;
        } else {
            
            return false;
        }
    }

    public function getVariants($mediaId) {

        $variants = array();

        $mediaVariants = $this->models->mediaVariant->getAllByMediaId($mediaId);

        if ($mediaVariants) {

            foreach ($mediaVariants as $mediaVariant) {

                $variantLabel = array();

                $mediaVariantOptions = $this->models->mediaVariantOption->getAllByMediaId($mediaId, $mediaVariant['id']);

                foreach ($mediaVariantOptions as $mediaVariantOption) {

                    $variantOption['id'] = $mediaVariantOption['id'];
                    $variantOption['label'] = $mediaVariantOption['label'];
                    $variantOption['value_id'] = $mediaVariantOption['option_value_id'];
                    $variantOption['value'] = $mediaVariantOption['option_value_value'];

                    $variantLabel[] = $mediaVariantOption['option_value_value'];
                    $mediaVariant['options'][] = $variantOption;
                }

                $mediaVariant['label'] = implode(' / ', $variantLabel);

                $variants[] = $mediaVariant;
            }
        }

        return $variants;
    }

    public function getAllPostageOptionsByMediaId($mediaId) {

        $postageOptions = $this->models->mediaPostageOptions->getAllByMediaId($mediaId);

        return $postageOptions;
    }

    public function getPostageOptionRatesByMediaId($mediaId) {

        return $this->models->mediaPostageOptions->getRatesByMediaId($mediaId);
    }

}
