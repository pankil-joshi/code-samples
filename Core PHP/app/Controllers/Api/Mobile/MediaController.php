<?php

namespace App\Controllers\Api\Mobile;

use App\Controllers\Api\Mobile\MobileBaseController as MobileBaseController;
use Quill\Factories\CoreFactory;
use Quill\Factories\ServiceFactory;
use Quill\Factories\RepositoryFactory;
use Quill\Factories\ModelFactory;
use Quill\Exceptions\BaseException;
use Quill\Exceptions\ValidationException;

/**
 * Media Controller, contains all methods related to instagram media/products.
 * 
 * @package App\Controllers\Api\Mobile
 * @author Pankil Joshi <pankil@prologictechnologies.in>
 * @version 1.0
 * @uses Quill\Factories\CoreFactory
 * @uses Quill\Factories\ServiceFactory
 * @uses Quill\Factories\ModelFactory
 * @uses Quill\Exceptions\BaseException
 * @uses Quill\Factories\RepositoryFactory;
 * @extend App\Controllers\Api\Mobile\MobileBaseController
 */
class MediaController extends MobileBaseController {

    private $mediaId, $mediaAvailability;

    function __construct($app = NULL) {

        parent::__construct($app);

        $this->models = ModelFactory::boot(array(
                    'User',
                    'Media',
                    'TempMedia',
                    'MediaCategories',
                    'MediaTypes',
                    'MediaPostageOptions',
                    'MediaPostageRuleAttributeValue',
                    'MediaAttributes',
                    'MediaVariant',
                    'MediaVariantOption',
                    'MediaVariantOptionValue',
                    'MediaAttributeValue',
                    'MediaMediaCategories',
                    'MediaStockItem',
                    'UserAddress',
                    'MerchantDetails',
                    'MediaTax',
                    'MediaReportMedia',
                    'MessagesThreadDetails',
                    'GeneralSetting'
        ));

        $this->core = CoreFactory::boot(array('Response', 'Http', 'View'));
        $this->repositories = RepositoryFactory::boot(array('MediaRepository'));

        $this->services = ServiceFactory::boot(array('Uid'));
        $this->app->config(load_config_one('emailTemplates')); //Load email template configuration variable.
    }

    /**
     * @api {get} /api/mobile/media/:id Get media details.
     * @apiName GetMedia
     * @apiGroup api/mobile/media
     * @apiDescription Use this api endpoint to get details of a media/product.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
      {
      "meta": {
      "success": true,
      "code": 200
      },
      "data": {
      "media": {
      "id": "2585",
      "media_id": "1398505636434933745_2958632796",
      "instagram_user_id": "2958632796",
      "caption_text": "Tesr\n£50 + Tax + Postage (GBP)\n\nDispatched within 1 days.\n\nTagzie users comment with #tagzie to purchase, or you can purchase from \nhttps://www.tagzie.com/r/8KNa93",
      "media_link": "https://www.instagram.com/p/BNofXd8FffxEuoGK7bEsGZ01HY2eCBh92KVW0U0/",
      "created_time": "1480934885",
      "image_low_resolution": "https://scontent.cdninstagram.com/t51.2885-15/s320x320/e35/14547572_1813811145556171_5889498768205676544_n.jpg?ig_cache_key=MTM5ODUwNTYzNjQzNDkzMzc0NQ%3D%3D.2",
      "image_thumbnail": "https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/14547572_1813811145556171_5889498768205676544_n.jpg?ig_cache_key=MTM5ODUwNTYzNjQzNDkzMzc0NQ%3D%3D.2",
      "image_standard_resolution": "https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/14547572_1813811145556171_5889498768205676544_n.jpg?ig_cache_key=MTM5ODUwNTYzNjQzNDkzMzc0NQ%3D%3D.2",
      "is_deleted": "0",
      "title": "Tesr",
      "description": "Ghhhh",
      "uid": "8KNa93",
      "is_active": "1",
      "type_id": null,
      "user_id": "2",
      "likes": "0",
      "in_stock": null,
      "price": "50.00",
      "has_variant": "1",
      "is_available": "1",
      "created_at": "2016-12-05 10:47:47",
      "updated_at": "2016-12-05 10:49:02",
      "activated_at": null,
      "views": "0",
      "instagram_views": "0",
      "base_currency_code": "GBP",
      "instagram_username": "prologic16",
      "path": "tesr-2585",
      "user_instagram_username": "justpanku",
      "user_instagram_followed_by": "72",
      "user_instagram_profile_picture": "https://scontent.cdninstagram.com/t51.2885-19/s150x150/14553152_598235427046956_2804273981094363136_a.jpg",
      "user_merchant_deactivate": "0",
      "user_customer_deactivate": "0",
      "min_price": "50.00",
      "max_price": "50.00",
      "variants": [
      {
      "id": "6",
      "media_id": "2585",
      "price": "50.00",
      "is_default": "1",
      "created_at": "2016-12-05 10:47:47",
      "updated_at": "2016-12-05 10:47:47",
      "quantity": "5",
      "min_stock_level": "1",
      "stock_quantity": "5",
      "stock_min_stock_level": "1",
      "label": ""
      }
      ],
      "merchant": {
      "id": "2",
      "instagram_user_id": "1916455276",
      "instagram_username": "justpanku",
      "first_name": "Pankil",
      "email": "Pankil@prologictechnologies.in",
      "mobile_number": "7696769679",
      "instagram_access_token": "1916455276.2b2af65.6a6352efc0a34fa2ae2b127d4bb08f99",
      "is_active": "1",
      "last_name": "Joshi",
      "instagram_followed_by": "72",
      "instagram_profile_picture": "https://scontent.cdninstagram.com/t51.2885-19/s150x150/14553152_598235427046956_2804273981094363136_a.jpg",
      "stripe_customer_id": "cus_9bYG2uTWr4X7ME",
      "is_merchant": "1",
      "title": "Mr",
      "date_of_birth": "1991-12-01",
      "terms_accepted": "1",
      "terms_accepted_ip": "203.134.198.137",
      "terms_accepted_datetime": "2016-12-05 10:30:06",
      "created_at": "2016-11-21 16:55:04",
      "updated_at": "2016-12-05 10:30:07",
      "base_currency": null,
      "timezone": "Asia/Calcutta",
      "country": "IN",
      "mobile_number_prefix": "+91",
      "accept_promotional_mails": "1",
      "accept_thirdparty_mails": "1",
      "accept_merchant_promotional_mails": "0",
      "notify_push_new_message": "1",
      "notify_push_customer_order_status_change": "1",
      "notify_push_merchant_new_order": "0",
      "notify_push_merchant_order_status_change": "0",
      "notify_push_merchant_low_stock": "0",
      "notify_email_new_message": "0",
      "notify_email_customer_order_status_change": "0",
      "notify_email_customer_order_confirmation": "0",
      "notify_email_merchant_new_order": "0",
      "notify_email_merchant_order_status_change": "0",
      "notify_email_merchant_low_stock": "0",
      "merchant_deactivate": "0",
      "customer_deactivate": "0",
      "is_deleted": "0",
      "deleted_at": null,
      "guest": null,
      "age": "25",
      "merchant_currency_conversion_factor": "0.787958000000",
      "customer_currency_conversion_factor": "68.121560000000",
      "currency_code": "INR",
      "merchant_stripe_subscription_id": "sub_9geYh0X4up1pzw",
      "merchant_legal_entity_business_tax_id": "123",
      "merchant_taxable_countries": "",
      "merchant_tax_on_postage": "0",
      "merchant_business_currency": "GBP",
      "merchant_stripe_account_id": "acct_19NHFgGiGlGCygFv",
      "merchant_legal_entity_business_name": "ABC",
      "merchant_legal_entity_address_line1": "hjbgh",
      "merchant_legal_entity_address_city": "gfcg",
      "merchant_legal_entity_address_postal_code": "WC2N",
      "merchant_legal_entity_address_state": "",
      "merchant_business_country": "GB",
      "merchant_business_telephone_prefix": "+44",
      "merchant_legal_entity_phone_number": "123",
      "merchant_business_email": "pankil@prologictechnologies.in",
      "subscription_packages_top_seller_badge": null,
      "subscription_packages_verified_badge": null,
      "subscription_packages_support_level_id": "2"
      },
      "tax_enabled": 1,
      "order_hold_period": 14,
      "tax": {
      "rate": "0.01",
      "inclusive": "0",
      "tax_on_postage": "0"
      },
      "attributes": [
      {
      "id": "1",
      "code": "dispatch_days",
      "label": "Dispatch Days",
      "backend_type": "int",
      "frontend_type": "text",
      "is_required": "1",
      "user_defined": "0",
      "attribute_value": "1"
      }
      ],
      "categories": [
      {
      "id": "52",
      "code": "accessories",
      "label": "Accessories",
      "parent_id": "51",
      "active": "1",
      "media_id": "2585",
      "category_id": "52"
      }
      ],
      "postageOptions": [
      {
      "id": "5",
      "code": "fast",
      "label": "Fast",
      "rate": "50.00",
      "duration": "1",
      "media_id": "2585",
      "user_id": "2",
      "rate_currency": "GBP",
      "geography": "*",
      "created_at": "2016-12-05 10:47:47",
      "updated_at": "2016-12-05 10:47:47"
      }
      ],
      "worldwide_shipping": 1,
      "express_delivery": 1,
      "seller_rating": 0,
      "top_seller": 0,
      "verified_seller": null,
      "merchant_currency_conversion_factor": "0.787958000000",
      "reported": 0,
      "thread_id": false,
      "customer_currency_conversion_factor": "68.121560000000"
      }
      }
      }
     */
    public function getMedia($id) {

        if ($this->request->get('destination') == 'customer_view') {

            $this->models->media->addView($id);
        }

        $media = $this->repositories->mediaRepository->get($id);

        $media['reported'] = ($this->models->mediaReportMedia->getByMediaIdUserId($id, $this->app->user['id'])) ? 1 : 0;
        $media['thread_id'] = $this->models->messagesThreadDetails->getThreadIdByProductId($id, $this->app->user['id']);
        $user = $this->models->user->getById($this->app->user['id']);

        $media['customer_currency_conversion_factor'] = $user['customer_currency_conversion_factor'];

        if ($media) {

            $data['media'] = $media;

            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
        } else {

            $this->userLogger->log('error', 'Resource not found', $this->app->user['id']);

            throw new BaseException('Resource not found');
        }
    }

    /**
     * @api {get} /api/mobile/media/:id/variants Get media variants.
     * @apiName GetVariants
     * @apiGroup api/mobile/media
     * @apiDescription Use this api endpoint to get media/product variants.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
      {
      "meta": {
      "success": true,
      "code": 200
      },
      "data": {
      "variants": [
      {
      "id": "5",
      "media_id": "2585",
      "price": "50.00",
      "is_default": "0",
      "created_at": "2016-12-05 10:12:00",
      "updated_at": "2016-12-05 10:12:00",
      "quantity": "5",
      "min_stock_level": "1",
      "stock_quantity": "5",
      "stock_min_stock_level": "1",
      "options": [
      {
      "id": "1",
      "label": "Size",
      "value_id": "1",
      "value": "L"
      }
      ],
      "label": "L"
      },
      {
      "id": "6",
      "media_id": "2585",
      "price": "50.00",
      "is_default": "0",
      "created_at": "2016-12-05 10:47:47",
      "updated_at": "2016-12-05 10:47:47",
      "quantity": "5",
      "min_stock_level": "1",
      "stock_quantity": "5",
      "stock_min_stock_level": "1",
      "options": [
      {
      "id": "2",
      "label": "Color",
      "value_id": "2",
      "value": "Red"
      }
      ],
      "label": "Red"
      }
      ]
      }
      }
     */
    public function listVariants($mediaId) {

        $variants = $this->repositories->mediaRepository->getVariants($mediaId);

        if ($variants) {

            $data['variants'] = $variants;

            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
        } else {

            $this->userLogger->log('error', 'Resource not found', $this->app->user['id']);

            throw new BaseException('Resource not found');
        }
    }

    /**
     * @api {post} /api/mobile/media/[:id] Create or update media/product.
     * @apiName SaveMedia
     * @apiGroup api/mobile/media
     * @apiDescription Use this api endpoint to create or update media/product.
     * @apiParamExample {json} Request-Example:
     *     {
     *       "subject": "Some text",
     *       "message": "Some text"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
      {
      "meta": {
      "success": true,
      "code": 200
      },
      "data": {
      "variants": [
      {
      "id": "5",
      "media_id": "2585",
      "price": "50.00",
      "is_default": "0",
      "created_at": "2016-12-05 10:12:00",
      "updated_at": "2016-12-05 10:12:00",
      "quantity": "5",
      "min_stock_level": "1",
      "stock_quantity": "5",
      "stock_min_stock_level": "1",
      "options": [
      {
      "id": "1",
      "label": "Size",
      "value_id": "1",
      "value": "L"
      }
      ],
      "label": "L"
      },
      {
      "id": "6",
      "media_id": "2585",
      "price": "50.00",
      "is_default": "0",
      "created_at": "2016-12-05 10:47:47",
      "updated_at": "2016-12-05 10:47:47",
      "quantity": "5",
      "min_stock_level": "1",
      "stock_quantity": "5",
      "stock_min_stock_level": "1",
      "options": [
      {
      "id": "2",
      "label": "Color",
      "value_id": "2",
      "value": "Red"
      }
      ],
      "label": "Red"
      }
      ]
      }
      }
     */
    public function saveMedia($id = NULL) {
        
        $rules = [];

        $v = new \Quill\Validator($this->jsonRequest, array(
            'title',
            'description',
            'categories',
            'variants',
            'postageOptions',
            'attributes',
            'is_refundable_disabled',
            'tax'
        ));
        $v->rules($rules);

        if ($v->validate()) {
            $this->models->media->beginTransaction();

            $logData = array();
            $logData['post'] = $this->jsonRequest;

            if (!empty($id)) {

                $_media = $this->models->media->getById($id, array('field' => 'user_id', 'value' => $this->app->user['id']));

                if (empty($_media)) {

                    throw new BaseException('Resource not found or you don\'t have access to the resource.');
                }

                $this->userLogger->log('info', 'User requested to update media details.', $this->app->user['id'], $logData);
            } else {

                $this->userLogger->log('info', 'User requested to create new media.', $this->app->user['id'], $logData);
            }


            $media = $v->sanatized();

            $media['user_id'] = $this->app->user['id'];

            if (empty($id)) {

                $merchant = $this->models->merchantDetails->getByUserId($this->app->user['id']);
                $media['base_currency_code'] = $merchant['business_currency'];
            }

            if (isset($media['variants']) && count($media['variants']) > 0) {

                $media['has_variant'] = 1;

                $variants = $media['variants'];
                unset($media['variants']);
            }

            if (!empty($media['attributes']))
                $attributes = $media['attributes'];
            unset($media['attributes']);

            if (!empty($media['categories']))
                $categories = $media['categories'];
            unset($media['categories']);

            if (!empty($media['postageOptions']))
                $postageOptions = $media['postageOptions'];
            unset($media['postageOptions']);

            if (!empty($media['tax']))
                $tax = $media['tax'];
            unset($media['tax']);

            $media['id'] = $id;
            $media['user_id'] = $this->app->user['id'];

            $this->mediaId = (!empty($id)) ? $id : $this->models->media->save($media);

            if (isset($variants)) {

                $this->_saveVariants($variants);
            }

            if (isset($attributes)) {

                $this->_addAttributes($attributes);
            }

            if (isset($categories)) {

                $this->_addCategories($categories);
            }

            if (isset($tax)) {

                $this->_addTax($tax);
            }

            if (isset($postageOptions)) {

                $this->_addPostageOptions($postageOptions);
            }

            if ($this->mediaId) {

                $uId = $this->services->uid->genrate($this->mediaId);
                $media['id'] = $this->mediaId;
                $media['uid'] = $uId;

                if (!empty($this->mediaAvailability)) {

                    $media['is_available'] = $this->mediaAvailability;
                }

                if (empty($id)) {

                    $media['path'] = toAscii($media['title']) . '-' . $this->mediaId;
                }

                if (!empty($id) && $_media['is_archived'] == 1) {

                    $media['is_archived'] = 0;
                }
                $this->models->media->save($media);
                $this->models->media->commit();
                $media = $this->repositories->mediaRepository->get($this->mediaId);

                unset($media['redirect_id']);

                $media['redirect_link'] = $this->app->config('shortner_url') . $media['uid'];

                $data['media'] = $media;

                echo $response = $this->core->response->json($data, FALSE);
            }
        } else {
            
            $logData = array();
            $logData['errors'] = $v->errors();

            $this->userLogger->log('error', 'Data validation failed', $this->app->user['id'], $logData);

            throw new ValidationException($v->errors());            
        }
    }

    /**
     * @api {delete} /api/mobile/media/[:id] Delete media/product.
     * @apiName DeleteMedia
     * @apiGroup api/mobile/media
     * @apiDescription Use this api endpoint to delete media/product.
     * @apiParamExample {json} Request-Example:
     *     {
     *       "subject": "Some text",
     *       "message": "Some text"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
      {
      "meta": {
      "success": true,
      "code": 200
      },
      "data": {
      "deleted": "true"
      }
      }
     */
    public function delete($id) {

        $media['id'] = $id;
        $media['user_id'] = $this->app->user['id'];

        $media = $this->models->media->softDelete($media);

        if (!empty($media)) {


            $data['deleted'] = true;

            echo $response = $this->core->response->json($data, FALSE);
        }
    }

    /**
     * @api {get} /api/mobile/media/ List media/products.
     * @apiName ListMedia
     * @apiGroup api/mobile/media
     * @apiDescription Use this api endpoint to get list of media/products.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
      {
      "meta": {
      "success": true,
      "code": 200
      },
      "data": {
      "media": [
      {
      "id": "2585",
      "media_id": "1398505636434933745_2958632796",
      "instagram_user_id": "2958632796",
      "caption_text": "Tesr\n£50 + Tax + Postage (GBP)\n\nDispatched within 1 days.\n\nTagzie users comment with #tagzie to purchase, or you can purchase from \nhttps://www.tagzie.com/r/8KNa93",
      "media_link": "https://www.instagram.com/p/BNofXd8FffxEuoGK7bEsGZ01HY2eCBh92KVW0U0/",
      "created_time": "1480934885",
      "image_low_resolution": "https://scontent.cdninstagram.com/t51.2885-15/s320x320/e35/14547572_1813811145556171_5889498768205676544_n.jpg?ig_cache_key=MTM5ODUwNTYzNjQzNDkzMzc0NQ%3D%3D.2",
      "image_thumbnail": "https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/14547572_1813811145556171_5889498768205676544_n.jpg?ig_cache_key=MTM5ODUwNTYzNjQzNDkzMzc0NQ%3D%3D.2",
      "image_standard_resolution": "https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/14547572_1813811145556171_5889498768205676544_n.jpg?ig_cache_key=MTM5ODUwNTYzNjQzNDkzMzc0NQ%3D%3D.2",
      "is_deleted": "0",
      "title": "Tesr",
      "description": "Ghhhh",
      "uid": "8KNa93",
      "is_active": "1",
      "type_id": null,
      "user_id": "2",
      "likes": "0",
      "in_stock": null,
      "price": "50.00",
      "has_variant": "1",
      "is_available": "1",
      "created_at": "2016-12-05 10:47:47",
      "updated_at": "2016-12-05 10:49:02",
      "activated_at": null,
      "views": "0",
      "instagram_views": "0",
      "base_currency_code": "GBP",
      "instagram_username": "prologic16",
      "path": "tesr-2585",
      "user_instagram_username": "justpanku",
      "user_instagram_followed_by": "72",
      "user_instagram_profile_picture": "https://scontent.cdninstagram.com/t51.2885-19/s150x150/14553152_598235427046956_2804273981094363136_a.jpg",
      "user_merchant_deactivate": "0",
      "user_customer_deactivate": "0",
      "min_price": "50.00",
      "max_price": "50.00",
      "variants": [
      {
      "id": "5",
      "media_id": "2585",
      "price": "50.00",
      "is_default": "0",
      "created_at": "2016-12-05 10:12:00",
      "updated_at": "2016-12-05 10:12:00",
      "quantity": "5",
      "min_stock_level": "1",
      "stock_quantity": "5",
      "stock_min_stock_level": "1",
      "options": [
      {
      "id": "1",
      "label": "Size",
      "value_id": "1",
      "value": "L"
      },
      {
      "id": "2",
      "label": "Color",
      "value_id": "2",
      "value": "Red"
      }
      ],
      "label": "L / Red"
      },
      {
      "id": "6",
      "media_id": "2585",
      "price": "50.00",
      "is_default": "0",
      "created_at": "2016-12-05 10:47:47",
      "updated_at": "2016-12-05 10:47:47",
      "quantity": "5",
      "min_stock_level": "1",
      "stock_quantity": "5",
      "stock_min_stock_level": "1",
      "options": [
      {
      "id": "1",
      "label": "Size",
      "value_id": "3",
      "value": "L"
      },
      {
      "id": "2",
      "label": "Color",
      "value_id": "4",
      "value": "Blue"
      }
      ],
      "label": "L / Blue"
      }
      ],
      "merchant": {
      "id": "2",
      "instagram_user_id": "1916455276",
      "instagram_username": "justpanku",
      "first_name": "Pankil",
      "email": "Pankil@prologictechnologies.in",
      "mobile_number": "7696769679",
      "instagram_access_token": "1916455276.2b2af65.6a6352efc0a34fa2ae2b127d4bb08f99",
      "is_active": "1",
      "last_name": "Joshi",
      "instagram_followed_by": "72",
      "instagram_profile_picture": "https://scontent.cdninstagram.com/t51.2885-19/s150x150/14553152_598235427046956_2804273981094363136_a.jpg",
      "stripe_customer_id": "cus_9bYG2uTWr4X7ME",
      "is_merchant": "1",
      "title": "Mr",
      "date_of_birth": "1991-12-01",
      "terms_accepted": "1",
      "terms_accepted_ip": "124.253.254.13",
      "terms_accepted_datetime": "2016-12-05 11:53:35",
      "created_at": "2016-11-21 16:55:04",
      "updated_at": "2016-12-05 12:27:59",
      "base_currency": null,
      "timezone": "Asia/Calcutta",
      "country": "IN",
      "mobile_number_prefix": "+91",
      "accept_promotional_mails": "1",
      "accept_thirdparty_mails": "1",
      "accept_merchant_promotional_mails": "0",
      "notify_push_new_message": "1",
      "notify_push_customer_order_status_change": "1",
      "notify_push_merchant_new_order": "0",
      "notify_push_merchant_order_status_change": "0",
      "notify_push_merchant_low_stock": "0",
      "notify_email_new_message": "0",
      "notify_email_customer_order_status_change": "0",
      "notify_email_customer_order_confirmation": "0",
      "notify_email_merchant_new_order": "0",
      "notify_email_merchant_order_status_change": "0",
      "notify_email_merchant_low_stock": "0",
      "merchant_deactivate": "0",
      "customer_deactivate": "0",
      "is_deleted": "0",
      "deleted_at": null,
      "guest": null,
      "age": "25",
      "merchant_currency_conversion_factor": "0.786313000000",
      "customer_currency_conversion_factor": "68.194920000000",
      "currency_code": "INR",
      "merchant_stripe_subscription_id": "sub_9geYh0X4up1pzw",
      "merchant_legal_entity_business_tax_id": "123",
      "merchant_taxable_countries": "",
      "merchant_tax_on_postage": "0",
      "merchant_business_currency": "GBP",
      "merchant_stripe_account_id": "acct_19NHFgGiGlGCygFv",
      "merchant_legal_entity_business_name": "ABC",
      "merchant_legal_entity_address_line1": "hjbgh",
      "merchant_legal_entity_address_city": "gfcg",
      "merchant_legal_entity_address_postal_code": "WC2N",
      "merchant_legal_entity_address_state": "",
      "merchant_business_country": "GB",
      "merchant_business_telephone_prefix": "+44",
      "merchant_legal_entity_phone_number": "123",
      "merchant_business_email": "pankil@prologictechnologies.in",
      "subscription_packages_top_seller_badge": null,
      "subscription_packages_verified_badge": null,
      "subscription_packages_support_level_id": "2"
      },
      "tax_enabled": 1,
      "order_hold_period": 14,
      "tax": {
      "rate": "0.01",
      "inclusive": "0",
      "tax_on_postage": "0"
      },
      "attributes": [
      {
      "id": "1",
      "code": "dispatch_days",
      "label": "Dispatch Days",
      "backend_type": "int",
      "frontend_type": "text",
      "is_required": "1",
      "user_defined": "0",
      "attribute_value": "1"
      }
      ],
      "categories": [
      {
      "id": "52",
      "code": "accessories",
      "label": "Accessories",
      "parent_id": "51",
      "active": "1",
      "media_id": "2585",
      "category_id": "52"
      }
      ],
      "postageOptions": [
      {
      "id": "5",
      "code": "fast",
      "label": "Fast",
      "rate": "50.00",
      "duration": "1",
      "media_id": "2585",
      "user_id": "2",
      "rate_currency": "GBP",
      "geography": "*",
      "created_at": "2016-12-05 10:47:47",
      "updated_at": "2016-12-05 10:47:47"
      }
      ],
      "worldwide_shipping": 1,
      "express_delivery": 1,
      "seller_rating": 0,
      "top_seller": 0,
      "verified_seller": null,
      "merchant_currency_conversion_factor": "0.786313000000"
      }
      ]
      }
      }
      }
     */
    public function listMedia() {

        $media = array();

        $this->userLogger->log('info', 'User requested to get media list.', $this->app->user['id']);

        $request = $this->jsonRequest;

        $offset = ($this->request->get('page') - 1);
        $search = $this->request->get('search');
        $status = $this->request->get('status');
        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));

        $_media = $this->models->media->getAllByUserId($this->app->user['id'], $filter, $order, $offset, 50, $status, $search);

        foreach ($_media as $index => $row) {

            $media[] = $this->repositories->mediaRepository->get($row['id']);
        }

        if ($media) {

            $data['media'] = $media;

            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
        } else {

            $this->userLogger->log('error', 'Resource not found', $this->app->user['id']);

            throw new BaseException('Resource not found');
        }
    }

    /**
     * @api {get} /api/mobile/media/categories List product categoriess.
     * @apiName ListCategories
     * @apiGroup api/mobile/media
     * @apiDescription Use this api endpoint to get list product categories.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
      {
      "meta": {
      "success": true,
      "code": 200
      },
      "data": {
      "categories": [
      {
      "id": "1",
      "code": "antiques",
      "label": "Antiques",
      "parent_id": "0",
      "active": "1"
      },
      {
      "id": "2",
      "code": "antique_clocks",
      "label": "Antique Clocks",
      "parent_id": "1",
      "active": "1"
      },
      {
      "id": "3",
      "code": "antique_furniture",
      "label": "Antique Furniture",
      "parent_id": "1",
      "active": "1"
      },
      {
      "id": "4",
      "code": "antiquities",
      "label": "Antiquities",
      "parent_id": "1",
      "active": "1"
      },
      {
      "id": "5",
      "code": "carpets_rugs",
      "label": "Carpets & Rugs",
      "parent_id": "1",
      "active": "1"
      },
      {
      "id": "6",
      "code": "decorative_arts",
      "label": "Decorative Arts",
      "parent_id": "1",
      "active": "1"
      },
      {
      "id": "7",
      "code": "fabric_textiles",
      "label": "Fabric/Textiles",
      "parent_id": "1",
      "active": "1"
      },
      {
      "id": "8",
      "code": "manuscripts",
      "label": "Manuscripts",
      "parent_id": "1",
      "active": "1"
      },
      {
      "id": "9",
      "code": "maps",
      "label": "Maps",
      "parent_id": "1",
      "active": "1"
      }
      ]
      }
      }
     */
    public function listMediaCategories() {

        $this->userLogger->log('info', 'User requested to get categories list.', $this->app->user['id']);

        $request = $this->jsonRequest;

        $mediaCategories = $this->models->mediaCategories->getAll();

        if ($mediaCategories) {

            $data['categories'] = $mediaCategories;

            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
        } else {

            $this->userLogger->log('error', 'Resource not found', $this->app->user['id']);

            throw new BaseException('Resource not found');
        }
    }

    /**
     * @api {get} /api/mobile/media/types List product types.
     * @apiName ListTypes
     * @apiGroup api/mobile/media
     * @apiDescription Use this api endpoint to get list product types.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
      {
      "meta": {
      "success": true,
      "code": 200
      },
      "data": {
      "types": [
      {
      "id": "1",
      "code": "physical_product",
      "label": "Physical product"
      }
      ]
      }
      }
     */
    public function listMediaTypes() {

        $this->userLogger->log('info', 'User requested to get media type list.', $this->app->user['id']);

        $request = $this->jsonRequest;

        $mediaTypes = $this->models->mediaTypes->getAll();

        if ($mediaTypes) {

            $data['types'] = $mediaTypes;

            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
        } else {

            $this->userLogger->log('error', 'Resource not found', $this->app->user['id']);

            throw new BaseException('Resource not found');
        }
    }

    /*
     * 
     */

    private function _getMoreMedia() {

        $instagramMedia = $this->core->http->get($this->app->config('instagram_api_url_v1') . 'tags/' . str_replace('#', '', $this->app->config('master_hashtag')) . '/media/recent?access_token=' . $this->models->user->getAccessTokenByInstagramUsername($this->app->config('master_instagram_account')) . '&min_tag_id=' . $this->models->generalSetting->getMintagId());

        $this->models->user->countCallsByAccessToken($this->models->user->getAccessTokenByInstagramUsername($this->app->config('master_instagram_account')));

        $instagramMedia = json_decode($instagramMedia, true);

        if (!empty($instagramMedia['data'])) {

            foreach ($instagramMedia['data'] as $row) {

                $user = $this->models->user->getByInstagramId($row['user']['id']);
                if ($user) {

                    if (stristr($row['caption']['text'], $this->app->config('master_hashtag')) !== FALSE) {

                        preg_match_all('!https?://\S+!', $row['caption']['text'], $matches);

                        if (!empty($matches)) {

                            $keyFound = false;

                            foreach ($matches[0] as $match) {

                                if (strpos($match, $this->app->config('base_url')) !== false) {

                                    $keyFound = true;
                                    $url = $match;
                                }
                            }
                            if ($keyFound) {

                                $uId = substr($url, strrpos($url, '/') + 1);
                                if (!empty($uId)) {

                                    $media = $this->models->media->getById($this->services->uid->decode($uId)[0]);

                                    if (!empty($media['id']) && !$media['is_active'] && $media['user_id'] == $user['id']) {

                                        $mediaData = array();
                                        $mediaData['id'] = $this->services->uid->decode($uId)[0];
                                        $mediaData['media_id'] = $row['id'];
                                        $mediaData['is_active'] = 1;
                                        $mediaData['activated_at'] = gmdate('Y-m-d H:i:s');
                                        $mediaData['activation_method'] = 'method_2';
                                        $mediaData['instagram_user_id'] = $row['user']['id'];
                                        $mediaData['instagram_username'] = $row['user']['username'];
                                        $mediaData['caption_text'] = $row['caption']['text'];
                                        $mediaData['media_link'] = $row['link'];
                                        $mediaData['created_time'] = $row['created_time'];
                                        $mediaData['image_low_resolution'] = $row['images']['low_resolution']['url'];
                                        $mediaData['image_thumbnail'] = $row['images']['thumbnail']['url'];
                                        $mediaData['likes'] = $row['likes']['count'];
                                        $mediaData['image_standard_resolution'] = $row['images']['standard_resolution']['url'];

//                        $this->mediaLogger->log('info', 'Service to save pending media stareted.', '', array('media' => $mediaData));

                                        $_media = $this->models->media->save($mediaData);
                                        $merchant = $this->models->user->getById($media['user_id']);
                                        if (!empty($_media)) {

                                            $this->models->tempMedia->deleteRow($row['id']);
                                            $data = array('title' => 'Product Manager', 'message' => 'Your product ' . $media['title'] . ' is now active.', 'extra' => array('link' => 'ipaid://productManager.html', 'image' => $mediaData['image_thumbnail']));
                                            $pushNotification = new \App\Services\PushNotification();
                                            $pushNotification->sendToUserDevices($user['id'], $data);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if (!empty($instagramMedia['pagination']['min_tag_id'])) {

            $setting['min_tag_id'] = $instagramMedia['pagination']['min_tag_id'];
            $this->models->generalSetting->save($setting);
        }
    }

    private function _getMoreUserMedia($media) {

        foreach ($media as $media_user) {

            $media_ids = [];

            $instagramMedia = $this->core->http->get($this->app->config('instagram_api_url_v1') . 'users/self/media/recent/?access_token=' . $media_user['instagram_access_token'] . '&min_id=' . $this->models->user->getUserMinId($media_user['id']));

            $this->models->user->countCallsByAccessToken($media_user['instagram_access_token']);

            $instagramMedia = json_decode($instagramMedia, true);

            if (!empty($instagramMedia['data'])) {

                foreach ($instagramMedia['data'] as $row) {

                    $user = $this->models->user->getByInstagramId($row['user']['id']);
                    if ($user) {

                        if (stristr($row['caption']['text'], $this->app->config('master_hashtag')) !== FALSE) {

                            preg_match_all('!https?://\S+!', $row['caption']['text'], $matches);

                            if (!empty($matches)) {

                                $keyFound = false;

                                foreach ($matches[0] as $match) {

                                    if (strpos($match, $this->app->config('base_url')) !== false) {

                                        $keyFound = true;
                                        $url = $match;
                                    }
                                }
                                if ($keyFound) {

                                    $uId = substr($url, strrpos($url, '/') + 1);
                                    if (!empty($uId)) {
                                        $media = $this->models->media->getById($this->services->uid->decode($uId)[0]);

                                        if (!empty($media['id']) && !$media['is_active'] && $media['user_id'] == $user['id']) {

                                            $mediaData = array();
                                            $mediaData['id'] = $this->services->uid->decode($uId)[0];
                                            $mediaData['media_id'] = $row['id'];
                                            $mediaData['is_active'] = 1;
                                            $mediaData['activated_at'] = gmdate('Y-m-d H:i:s');
                                            $mediaData['activation_method'] = 'method_3';
                                            $mediaData['instagram_user_id'] = $row['user']['id'];
                                            $mediaData['instagram_username'] = $row['user']['username'];
                                            $mediaData['caption_text'] = $row['caption']['text'];
                                            $mediaData['media_link'] = $row['link'];
                                            $mediaData['created_time'] = $row['created_time'];
                                            $mediaData['image_low_resolution'] = $row['images']['low_resolution']['url'];
                                            $mediaData['image_thumbnail'] = $row['images']['thumbnail']['url'];
                                            $mediaData['likes'] = $row['likes']['count'];
                                            $mediaData['image_standard_resolution'] = $row['images']['standard_resolution']['url'];

//                                          $this->mediaLogger->log('info', 'Service to save pending media stareted.', '', array('media' => $mediaData));

                                            $_media = $this->models->media->save($mediaData);
                                            $merchant = $this->models->user->getById($media['user_id']);
                                            if (!empty($_media)) {

                                                $this->models->tempMedia->deleteRow($row['id']);
                                                $media_ids[] = $this->services->uid->decode($uId)[0];
                                                $data = array('title' => 'Product Manager', 'message' => 'Your product ' . $media['title'] . ' is now active.', 'extra' => array('link' => 'ipaid://productManager.html', 'image' => $mediaData['image_thumbnail']));
                                                $pushNotification = new \App\Services\PushNotification();
                                                $pushNotification->sendToUserDevices($user['id'], $data);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            /*
             * Check invalid media and update so never process again in cron
             */
            $media_to_process = explode(',', $media_user['media_ids']);
            $media_processed = $media_ids;
            $media_invalid = array_diff($media_to_process, $media_processed);

            if (!empty($media_invalid)) {

                foreach ($media_invalid as $media_value) {

                    $media_data = array();
                    $media_data['id'] = $media_value;
                    //$media_data['is_archived'] = 1;
                    $this->models->media->save($media_data);
                }
            }

            if (!empty($instagramMedia['pagination']['min_id'])) {

                $userData = array();
                $userData['id'] = $media_user['id'];
                $userData['min_id'] = $instagramMedia['pagination']['min_id'];
                $this->models->user->save($userData);
            }
        }
    }

    public function savePendingMediaByTag() {

//        $this->mediaLogger = $this->app->mediaLogger;
//        $this->mediaLogger->setLogSubDirectory('cli/media/');
//        $this->mediaLogger->log('info', 'Service to save pending media by tag started.');

        $media = $this->models->media->getAllPending();

        if (!empty($media)) {

            $this->_getMoreMedia();
        }
    }

    public function savePendingMediaByUser() {

        $media = $this->models->media->getAllPendingByUserId();
        if (!empty($media)) {
            $this->_getMoreUserMedia($media);
        }
    }

    public function savePendingMedia() {

        $this->mediaLogger = $this->app->mediaLogger;
        $this->mediaLogger->setLogSubDirectory('cli/media/');
        $this->mediaLogger->log('info', 'Service to save pending media stareted.');

        $media = $this->models->tempMedia->getAll();

        foreach ($media as $row) {


            $user = $this->models->user->getByInstagramId($row['instagram_user_id']);

            $logData = array();
            $logData['media'] = array('instagram_media_id' => $row['media_id']);

            if ($this->app->config('logging_strict') == FALSE) {

                $logData['access_token'] = $user['instagram_access_token'];
            }

            $this->mediaLogger->log('info', 'Get media details from Instagram.', '', $logData);

            $mediaDetails = $this->core->http->get($this->app->config('instagram_api_url_v1') . 'media/' . $row['media_id'] . '?access_token=' . $user['instagram_access_token']);

            $this->models->user->countCallsByAccessToken($user['instagram_access_token']);

            $mediaDetails = json_decode($mediaDetails, TRUE);

            $logData = array();
            $logData['recieved'] = $mediaDetails;

            $this->mediaLogger->log('info', 'Response from Instagram.', '', $logData);

            if (!empty($mediaDetails['data'])) {

                $user = $this->models->user->getByInstagramId($mediaDetails['data']['user']['id']);
                if ($user) {

                    if (stristr($mediaDetails['data']['caption']['text'], $this->app->config('master_hashtag')) !== FALSE) {

                        preg_match_all('!https?://\S+!', $mediaDetails['data']['caption']['text'], $matches);

                        if (!empty($matches)) {
                            $keyFound = false;

                            foreach ($matches[0] as $match) {

                                if (strpos($match, $this->app->config('base_url')) !== false) {

                                    $keyFound = true;
                                    $url = $match;
                                }
                            }
                            if ($keyFound) {

                                $uId = substr($url, strrpos($url, '/') + 1);
                                if (!empty($uId)) {
                                    $pendingMedia = $this->models->media->getById($this->services->uid->decode($uId)[0]);

                                    if (!empty($pendingMedia['id']) && !$pendingMedia['is_active'] && $pendingMedia['user_id'] == $user['id']) {

                                        $mediaData = array();
                                        $mediaData['id'] = $this->services->uid->decode($uId)[0];
                                        $mediaData['media_id'] = $row['media_id'];
                                        $mediaData['is_active'] = 1;
                                        $mediaData['activated_at'] = gmdate('Y-m-d H:i:s');
                                        $mediaData['activation_method'] = 'method_1';
                                        $mediaData['instagram_user_id'] = $mediaDetails['data']['user']['id'];
                                        $mediaData['instagram_username'] = $mediaDetails['data']['user']['username'];
                                        $mediaData['caption_text'] = $mediaDetails['data']['caption']['text'];
                                        $mediaData['media_link'] = $mediaDetails['data']['link'];
                                        $mediaData['created_time'] = $mediaDetails['data']['created_time'];
                                        $mediaData['image_low_resolution'] = $mediaDetails['data']['images']['low_resolution']['url'];
                                        $mediaData['image_thumbnail'] = $mediaDetails['data']['images']['thumbnail']['url'];
                                        $mediaData['likes'] = $mediaDetails['data']['likes']['count'];
                                        $mediaData['image_standard_resolution'] = $mediaDetails['data']['images']['standard_resolution']['url'];

                                        $this->mediaLogger->log('info', 'Service to save pending media stareted.', '', array('media' => $mediaData));

                                        $this->models->media->save($mediaData);

                                        $this->mediaLogger->log('info', 'Delete media from temporary table.', '', array('media' => array('id' => $row['media_id'])));

                                        $data = array('title' => 'Product Manager', 'message' => 'Your product ' . $pendingMedia['title'] . ' is now active.', 'extra' => array('link' => 'ipaid://productManager.html', 'image' => $mediaData['image_thumbnail']));
                                        $pushNotification = new \App\Services\PushNotification();
                                        $pushNotification->sendToUserDevices($user['id'], $data);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $this->models->tempMedia->deleteRow($row['media_id']);
        }
    }

    public function instagramCallback() {

        $logData = array();
        $logData['server'] = $_SERVER;
        $this->app->instagramLogger->setLogSubDirectory('http/instagram/');

        $this->app->instagramLogger->log('info', 'Callback url triggred.', '', $logData);

        if (isset($_GET['hub_challenge'])) {

            $logData = array();
            $logData['hub_challenge'] = $_GET['hub_challenge'];

            $this->app->instagramLogger->log('info', 'Callback url verification.', '', $logData);


            echo $_GET['hub_challenge'];
        } else {

            $logData = array();
            $logData['post'] = json_decode(file_get_contents('php://input'), true);

            $this->app->instagramLogger->log('info', 'Instagram media update recieved.', '', $logData);

            foreach (json_decode(file_get_contents('php://input'), true) as $row) {

                $mediaData['media_id'] = $row['data']['media_id'];
                $mediaData['instagram_user_id'] = $row['object_id'];

                $this->models->tempMedia->save($mediaData);
            }
        }
    }

    private function _saveVariants($variants) {

        foreach ($variants as $variant) {

            $variant['media_id'] = $this->mediaId;

            if (isset($variant['is_deleted']) && $variant['is_deleted'] === 1) {

                $this->models->mediaVariant->remove($variant['id']);
            } else {

                if ($variant['quantity'] == 0) {

                    $this->mediaAvailability = 0;
                } else {

                    $this->mediaAvailability = 1;
                }

                unset($variant['is_deleted']);
                if (empty($variant['id']) && !empty($variant['is_default'])) {

                    unset($variant['options']);

                    $variantQuantity = $variant['quantity'];

                    unset($variant['quantity']);

                    $variantStockLevel = 0;

                    if (!empty($variant['min_stock_level']))
                        $variantStockLevel = $variant['min_stock_level'];
                    unset($variant['min_stock_level']);

                    $variant['is_default'] = 1;

                    $variantId = $this->models->mediaVariant->save($variant);
                    $stockItem = array();
                    $stockItem['media_id'] = $this->mediaId;
                    $stockItem['variant_id'] = $variantId;
                    $stockItem['quantity'] = $variantQuantity;
                    $stockItem['min_stock_level'] = $variantStockLevel;

                    $this->models->mediaStockItem->save($stockItem);
                }
                else {

                    $variantQuantity = $variant['quantity'];
                    unset($variant['quantity']);
                    $variantStockLevel = 0;
                    if (!empty($variant['min_stock_level'])) {
                        $variantStockLevel = $variant['min_stock_level'];
                    }
                    unset($variant['min_stock_level']);
                    if (!empty($variant['options'])) {
                        $options = $variant['options'];
                    }
                    unset($variant['options']);
                    $variantId = $this->models->mediaVariant->save($variant);
                    $stockItem = array();
                    $stockItem['media_id'] = $this->mediaId;
                    $stockItem['variant_id'] = $variantId;

                    if (!empty($variantStockLevel)) {

                        $stockItem['min_stock_level'] = $variantStockLevel;
                    }
                    if (!empty($variantQuantity)) {

                        $stockItem['quantity'] = $variantQuantity;
                    }

                    $this->models->mediaStockItem->save($stockItem);

                    if (!empty($options)) {

                        foreach ($options as $row) {
                            $option = array();
                            if (!empty($row['id']))
                                $option['id'] = $row['id'];
                            $option['label'] = $row['label'];
                            $option['code'] = snake_case($option['label']);
                            $option['media_id'] = $this->mediaId;

                            if (isset($row['is_deleted']) && $row['is_deleted'] === 1) {

                                $this->models->mediaVariantOption->remove($option);
                            }

                            $_option = $this->models->mediaVariantOption->save($option);
                            $value = array();
                            if (!empty($row['value_id']))
                                $value['id'] = $row['value_id'];
                            $value['media_variant_option_id'] = $_option['id'];
                            $value['value'] = $row['value'];
                            $value['variant_id'] = $variantId;

                            $this->models->mediaVariantOptionValue->save($value);
                        }
                    }
                }
            }
        }
    }

    private function _addAttributes($attributes) {

        foreach ($attributes as $key => $value) {

            $attribute['attribute_id'] = $key;
            $attribute['media_id'] = $this->mediaId;
            $attribute['value'] = $value;

            $this->models->mediaAttributeValue->save($attribute);
        }
    }

    private function _addCategories($categories) {

        $this->models->mediaMediaCategories->clear($this->mediaId);

        foreach ($categories as $id) {

            $category['category_id'] = $id;
            $category['media_id'] = $this->mediaId;

            $this->models->mediaMediaCategories->save($category);
        }
    }

    private function _addTax($tax) {

        $tax['media_id'] = $this->mediaId;

        $this->models->mediaTax->save($tax);
    }

    /**
     * @api {post} /api/mobile/media/:id/postageOptions List media postage options.
     * @apiName MediaPostageOptions
     * @apiGroup api/mobile/media
     * @apiDescription Use this api endpoint to get list product's postage options.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     */
    public function listPostageOptionsByMediaId($id) {

        $request = $this->jsonRequest;

        $userAddressess = $this->models->userAddress->getById($request['address_id'], $this->app->user['id']);

        $merchantDetails = $this->models->merchantDetails->getByMediaId($id);

        $postageOptions = $this->models->mediaPostageOptions->getAllByMediaIdUserAddress($id, $userAddressess);

        $media = $this->models->media->getById($id);
        $merchantDetails = $this->models->merchantDetails->getByUserId($media['user_id']);
        $userAddress = $this->models->userAddress->getById($request['address_id'], $this->app->user['id']);
        $taxRate = 0;

        if ($merchantDetails['taxable_countries'] == '*' || strpos($merchantDetails['taxable_countries'], $userAddress['country']) !== false) {

            $taxRate = $this->models->mediaTax->getRateByMediaId($id);
        }

        $data['taxRate'] = $taxRate;
        $data['postageOptions'] = $postageOptions;

        echo $response = $this->core->response->json($data, FALSE);
    }

    public function _addPostageOptions($postageOptions) {

        foreach ($postageOptions as $postageOption) {

            if (!empty($postageOption['id']) && $postageOption['is_deleted'] == 1) {
                unset($postageOption['is_deleted']);
                $this->models->mediaPostageOptions->remove($postageOption['id'], $this->app->user['id']);
            } else {
                unset($postageOption['is_deleted']);
                $postageOption['media_id'] = $this->mediaId;
                $postageOption['user_id'] = $this->app->user['id'];
                $merchant = $this->models->merchantDetails->getByUserId($this->app->user['id']);
                $postageOption['rate_currency'] = $merchant['business_currency'];

                $this->models->mediaPostageOptions->save($postageOption);
            }
        }
    }

    /**
     * @api {get} /api/mobile/media/tagged List tagged media.
     * @apiName TaggedMedia
     * @apiGroup api/mobile/media
     * @apiDescription Use this api endpoint to get list of tagged media.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
      {
      "meta": {
      "success": true,
      "code": 200
      },
      "data": {
      "media": [
      {
      "id": "2585",
      "media_id": "1398505636434933745_2958632796",
      "instagram_user_id": "2958632796",
      "caption_text": "Tesr\n£50 + Tax + Postage (GBP)\n\nDispatched within 1 days.\n\nTagzie users comment with #tagzie to purchase, or you can purchase from \nhttps://www.tagzie.com/r/8KNa93",
      "media_link": "https://www.instagram.com/p/BNofXd8FffxEuoGK7bEsGZ01HY2eCBh92KVW0U0/",
      "created_time": "1480934885",
      "image_low_resolution": "https://scontent.cdninstagram.com/t51.2885-15/s320x320/e35/14547572_1813811145556171_5889498768205676544_n.jpg?ig_cache_key=MTM5ODUwNTYzNjQzNDkzMzc0NQ%3D%3D.2",
      "image_thumbnail": "https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/14547572_1813811145556171_5889498768205676544_n.jpg?ig_cache_key=MTM5ODUwNTYzNjQzNDkzMzc0NQ%3D%3D.2",
      "image_standard_resolution": "https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/14547572_1813811145556171_5889498768205676544_n.jpg?ig_cache_key=MTM5ODUwNTYzNjQzNDkzMzc0NQ%3D%3D.2",
      "is_deleted": "0",
      "title": "Tesr",
      "description": "Ghhhh",
      "uid": "8KNa93",
      "is_active": "1",
      "type_id": null,
      "user_id": "2",
      "likes": "0",
      "in_stock": null,
      "price": "50.00",
      "has_variant": "1",
      "is_available": "1",
      "created_at": "2016-12-05 10:47:47",
      "updated_at": "2016-12-05 10:49:02",
      "activated_at": null,
      "views": "0",
      "instagram_views": "0",
      "base_currency_code": "GBP",
      "instagram_username": "prologic16",
      "path": "tesr-2585",
      "merchant_instagram_username": "prologic16",
      "tagged_at": "2016-12-06 05:28:32",
      "comment_id": "17867387227010618",
      "merchant_currency_conversion_factor": "0.784795000000",
      "customer_currency_conversion_factor": "0.928850000000",
      "customer_currency_code": "EUR",
      "min_price": "50.00",
      "max_price": "50.00"
      }
      ]
      }
      }
     */
    public function listTagged() {

        $media = array();

        $this->userLogger->log('info', 'User requested to get media list.', $this->app->user['id']);

        $request = $this->jsonRequest;

        $offset = $this->request->get('page');
        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));
        $search = $this->request->get('search');
        $taggedProducts = $this->models->media->getTaggedByUserId($this->app->user['id'], $filter, $order, $offset, 50, $search);

        foreach ($taggedProducts as $index => $taggedProduct) {

            $prices = $this->models->mediaVariant->getPricesByMediaId($taggedProduct['id']);

            $taggedProducts[$index]['min_price'] = $prices['min_price'];
            $taggedProducts[$index]['max_price'] = $prices['max_price'];
        }

        $data['media'] = $taggedProducts;

        echo $response = $this->core->response->json($data, FALSE);
    }

    public function getTaxRateByMediaId($mediaId) {
        $request = $this->jsonRequest;
        $media = $this->models->media->getById($mediaId);
        $merchantDetails = $this->models->merchantDetails->getByUserId($media['user_id']);
        $userAddress = $this->models->userAddress->getById($request['address_id'], $this->app->user['id']);
        $taxRate = 0;

        if ($merchantDetails['taxable_countries'] == '*' || strpos($merchantDetails['taxable_countries'], $userAddress['country']) !== false) {

            $taxRate = $this->models->mediaTax->getRateByMediaId($mediaId);
        }

        $data['taxRate'] = $taxRate;

        echo $response = $this->core->response->json($data, FALSE);
    }

    /**
     * @api {post} /api/mobile/media/:id/report Report media/product.
     * @apiName ReportMedia
     * @apiGroup api/mobile/media
     * @apiDescription Use this api endpoint to report media/product.
     * @apiParamExample {json} Request-Example:
      {"type":"offensive"}
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
      {"meta":{"success":true,"code":200},"data":{"message":"Media reported successfully!"}}
     */
    public function report($id) {
        $this->services->osTickets = new \App\Services\OsTickets();
        $this->services->emailNotification = new \App\Services\EmailNotification();
        $media = $this->models->media->getById($id);

        if (!empty($media)) {

            $request = $this->jsonRequest;
            $user = $this->models->user->getById($media['user_id']);

            $report = array();
            $report['user_id'] = $this->app->user['id'];
            $report['media_id'] = $id;
            $report['type'] = $request['type'];

            $rating = $this->models->mediaReportMedia->save($report);
            $this->services->osTickets->name = $user['first_name'] . ' ' . $user['last_name'];
            $this->services->osTickets->email = $user['merchant_business_email'];
            $this->services->osTickets->subject = 'Product violating our terms and conditions';
            $this->services->osTickets->message = '';
            $this->services->osTickets->alertuser = 0;

            $ticket = $this->services->osTickets->createTicket();
            $report['ticket_number'] = $ticket;

            if ($report) {

                $app = array(
                    'base_assets_url' => $this->app->config('base_assets_url'),
                    'domain' => $this->app->config('domain'),
                    'base_url' => $this->app->config('base_url'),
                    'app_title' => $this->app->config('app_title'),
                    'master_hashtag' => $this->app->config('master_hashtag'),
                    'feedback_email' => $this->app->config('feedback_email'),
                    'sales_email' => $this->app->config('sales_email'),
                    'support_email' => $this->app->config('support_email'),
                    'report_email' => $this->app->config('report_email'),
                    'instagram_account_url' => $this->app->config('instagram_account_url'),
                    'twitter_account_url' => $this->app->config('twitter_account_url'),
                    'support_phone_uk' => $this->app->config('support_phone_uk'),
                    'support_phone_int' => $this->app->config('support_phone_int')
                );

                $this->services->emailNotification->sendMail(array('email' => $user['email'], 'name' => $user['first_name'] . ' ' . $user['last_name']), $this->app->config('product_violation_subject'), $this->core->view->make('email/product-violation.php', array('user' => $user, 'app' => $app, 'media' => $media, 'report' => $report), false));

                $data['message'] = 'Media reported successfully!';

                echo $response = $this->core->response->json($data, FALSE);
            }
        } else {

            throw new BaseException('Media ID invalid.');
        }
    }

}
