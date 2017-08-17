<?php

namespace App\Controllers\Api\Mobile;

use App\Controllers\Api\Mobile\MobileBaseController as MobileBaseController;
use Quill\Factories\CoreFactory;
use Quill\Factories\ServiceFactory;
use Quill\Factories\ModelFactory;
use Quill\Factories\RepositoryFactory;
use Quill\Exceptions\BaseException;
use Quill\Exceptions\ValidationException;

class MerchantController extends MobileBaseController {

    function __construct($app = NULL) {

        parent::__construct($app);

        $this->models = ModelFactory::boot(array(
                    'Media',
                    'MerchantPopularProduct',
                    'Order',
                    'MerchantDetails',
                    'OrderSuborder',
                    'OrderItem',
                    'User',
                    'SubscriptionPackage',
                    'MerchantLedger',
                    'Invoice',
                    'MerchantDetailsAdditionalOwners',
                    'MessagesThreadDetails',
                    'MediaStockItem',
                    'Comment',
                    'AffiliateReferralCodes'
        ));

        $this->core = CoreFactory::boot(array('Response', 'View'));

        $this->services = ServiceFactory::boot(array('Stripe', 'Transaction', 'EmailNotification'));

        $this->app->config(load_config_one('emailTemplates'));
        $this->app->config(load_config_one('url'));

        $this->repositories = RepositoryFactory::boot(array('OrderRepository', 'MessageRepository'));
    }

    /**
     * @api {get} /api/mobile/merchant/reports/popular Merchant's popular products.
     * @apiName PopularProducts
     * @apiGroup /api/mobile/merchant/reports
     * @apiDescription Use this api endpoint to get list of merchant's popular products.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     */
    public function listPopular() {

        $this->userLogger->log('info', 'User requested to get popular product list.', $this->app->user['id']);

        $request = $this->jsonRequest;

        $popularProducts = $this->models->merchantPopularProduct->getAllByUserId($this->app->user['id']);

        if ($popularProducts) {

            $data['popular_products'] = $popularProducts;

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
     * @api {get} /api/mobile/merchant/orders Merchant's sale orders.
     * @apiName SaleOrders
     * @apiGroup /api/mobile/merchant/
     * @apiDescription Use this api endpoint to get list of merchant's sale orders.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
      {
      "meta": {
      "success": true,
      "code": 200
      },
      "data": {
      "sale_orders": [
      {
      "id": "7",
      "media_title": "Tesr",
      "status": "in_progress",
      "discount": "0.00",
      "postage": "50.00",
      "tax": "0.00",
      "subtotal": "50.00",
      "total": "100.00",
      "total_paid": null,
      "total_refunded": null,
      "base_currency_code": "USD",
      "order_history": [
      {
      "datetime": "2016-12-06 07:29:42",
      "status": "pending",
      "comment": "Order created!"
      },
      {
      "datetime": "2016-12-06 07:29:51",
      "status": "in_progress",
      "comment": "Order sent to the merchant for processing!"
      }
      ],
      "created_at": "2016-12-06 07:29:42",
      "cancelled_at": null,
      "returned_at": "0000-00-00 00:00:00",
      "cancel_reason": "",
      "shipped_at": "0000-00-00 00:00:00",
      "completed_at": null,
      "refunded_at": null,
      "order_id": "7",
      "merchant_id": "2",
      "postage_option_id": "5",
      "postage_option_label": "Fast",
      "tracking_details": false,
      "delivery_address": {
      "id": "39",
      "line_1": "c 98",
      "line_2": "phase 7",
      "line_3": null,
      "city": "mohali",
      "zip_code": "160055",
      "state": "Punjab",
      "country": "IN",
      "user_id": "4",
      "is_delivery_address": null,
      "created_at": "2016-12-06 07:07:15",
      "updated_at": "2016-12-06 07:07:15",
      "first_name": "pankil",
      "last_name": "joshi",
      "mobile_number": "07696769679",
      "mobile_number_prefix": "+91"
      },
      "payment_status": "paid",
      "expected_dispatch_time": "1 ",
      "expected_transit_time": "1",
      "merchant_currency_conversion_factor": "1.000000000000",
      "tax_enabled": "1",
      "media_id": "2585",
      "order_number": "7-7",
      "user_id": "4",
      "user_first_name": "pankil",
      "user_last_name": "joshi",
      "user_instagram_username": "prologicpankil",
      "user_country": "IN",
      "items": [
      {
      "id": "7",
      "order_id": "7",
      "media_id": "2585",
      "variant_id": "5",
      "ordered_quantity": "1.00",
      "billed_quantity": "1.00",
      "price": "50.00",
      "row_total": "50.00",
      "options": {
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
      "row_tax_rate": "0.00",
      "row_tax": "0.00",
      "row_tax_inclusive": "0",
      "row_discount": "0.00",
      "row_basetotal": "50.00",
      "merchant_id": "2",
      "row_postage": null,
      "suborder_id": "7",
      "media_title": "Tesr",
      "media_thumbnail_image": "https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/14547572_1813811145556171_5889498768205676544_n.jpg?ig_cache_key=MTM5ODUwNTYzNjQzNDkzMzc0NQ%3D%3D.2",
      "user_instagram_username": "justpanku"
      }
      ],
      "thread_id": false
      },
      {
      "id": "4",
      "media_title": "Tesr",
      "status": "in_progress",
      "discount": "0.00",
      "postage": "50.00",
      "tax": "0.00",
      "subtotal": "50.00",
      "total": "100.00",
      "total_paid": null,
      "total_refunded": null,
      "base_currency_code": "GBP",
      "order_history": [
      {
      "datetime": "2016-12-06 07:19:31",
      "status": "pending",
      "comment": "Order created!"
      },
      {
      "datetime": "2016-12-06 07:19:41",
      "status": "in_progress",
      "comment": "Order sent to the merchant for processing!"
      }
      ],
      "created_at": "2016-12-06 07:19:31",
      "cancelled_at": null,
      "returned_at": "0000-00-00 00:00:00",
      "cancel_reason": "",
      "shipped_at": "0000-00-00 00:00:00",
      "completed_at": null,
      "refunded_at": null,
      "order_id": "4",
      "merchant_id": "2",
      "postage_option_id": "5",
      "postage_option_label": "Fast",
      "tracking_details": false,
      "delivery_address": {
      "id": "39",
      "line_1": "c 98",
      "line_2": "phase 7",
      "line_3": null,
      "city": "mohali",
      "zip_code": "160055",
      "state": "Punjab",
      "country": "IN",
      "user_id": "4",
      "is_delivery_address": null,
      "created_at": "2016-12-06 07:07:15",
      "updated_at": "2016-12-06 07:07:15",
      "first_name": "pankil",
      "last_name": "joshi",
      "mobile_number": "07696769679",
      "mobile_number_prefix": "+91"
      },
      "payment_status": "paid",
      "expected_dispatch_time": "1 ",
      "expected_transit_time": "1",
      "merchant_currency_conversion_factor": "0.784795000000",
      "tax_enabled": "1",
      "media_id": "2585",
      "order_number": "4-4",
      "user_id": "4",
      "user_first_name": "pankil",
      "user_last_name": "joshi",
      "user_instagram_username": "prologicpankil",
      "user_country": "IN",
      "items": [
      {
      "id": "4",
      "order_id": "4",
      "media_id": "2585",
      "variant_id": "5",
      "ordered_quantity": "1.00",
      "billed_quantity": "1.00",
      "price": "50.00",
      "row_total": "50.00",
      "options": {
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
      "row_tax_rate": "0.00",
      "row_tax": "0.00",
      "row_tax_inclusive": "0",
      "row_discount": "0.00",
      "row_basetotal": "50.00",
      "merchant_id": "2",
      "row_postage": null,
      "suborder_id": "4",
      "media_title": "Tesr",
      "media_thumbnail_image": "https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/14547572_1813811145556171_5889498768205676544_n.jpg?ig_cache_key=MTM5ODUwNTYzNjQzNDkzMzc0NQ%3D%3D.2",
      "user_instagram_username": "justpanku"
      }
      ],
      "thread_id": false
      }
      ]
      }
      }
     */
    public function listOrders() {

        $this->userLogger->log('info', 'User requested to get sale order list.', $this->app->user['id']);

        $request = $this->jsonRequest;

        $offset = $this->request->get('page');
        $status = $this->request->get('status');
        $search = $this->request->get('search');
        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));

        $saleOrders = $this->models->orderSuborder->getAllByMerchantId($this->app->user['id'], $filter, $order, $offset, 50, $status, $search);

        foreach ($saleOrders as $orderIndex => $order) {

            $history = explode(':::', $order['order_history']);

            $orderHistory = array();

            foreach ($history as $row) {

                $orderHistory[] = unserialize($row);
            }

            $saleOrders[$orderIndex]['order_history'] = $orderHistory;
            $saleOrders[$orderIndex]['tracking_details'] = unserialize($order['tracking_details']);
            $saleOrders[$orderIndex]['delivery_address'] = unserialize($order['delivery_address']);
            $saleOrders[$orderIndex]['items'] = $this->models->orderItem->getBySuborderId($order['id']);
            $saleOrders[$orderIndex]['thread_id'] = $this->models->messagesThreadDetails->getThreadIdByOrderId($order['id'], $this->app->user['id'], $order['user_id']);
            foreach ($saleOrders[$orderIndex]['items'] as $itemIndex => $item) {

                $saleOrders[$orderIndex]['items'][$itemIndex]['options'] = unserialize($item['options']);
            }
        }

        if ($saleOrders) {

            $data['sale_orders'] = $saleOrders;

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
     * @api {get} /api/mobile/merchant/reports/sales Merchant's sales report.
     * @apiName SalesReport
     * @apiGroup /api/mobile/merchant/reports
     * @apiDescription Use this api endpoint to get merchant's sales report.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
      {
      "meta": {
      "success": true,
      "code": 200
      },
      "data": {
      "sales": [
      {
      "month": "12",
      "year": "2016",
      "total_sale": "100.00"
      }
      ],
      "conversions": [
      {
      "rate": "0.0000",
      "year": "2016",
      "month": "12"
      }
      ]
      }
      }
     */
    public function salesByDateRange() {

        $this->userLogger->log('info', 'User requested to get sale reports by date range.', $this->app->user['id']);

        $request = $this->jsonRequest;

        $this->app->user['timezone'] = get_timezone_offset_from_name($this->app->user['timezone']);

        $sales = $this->models->orderSuborder->getAllMonthlyBetweenDatesByUser($this->app->user, null);
        $conversions = $this->models->comment->getMonthlyConversions($this->app->user, null);

        if ($sales) {

            $data['sales'] = $sales;
            $data['conversions'] = $conversions;

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
     * @api {post} /api/mobile/merchant/ Create or update merchant account.
     * @apiName SaveMerchant
     * @apiGroup /api/mobile/merchant
     * @apiDescription Use this api endpoint to create or update merchant account.
     * @apiParamExample {json} Request-Example:
      {"legal_entity_business_name":"Abc","legal_entity_business_name_kana":"","legal_entity_business_name_kanji":"","legal_entity_type":"company","business_registration_number":"123","business_email":"test@example.com","business_website":"www.abc.com","business_category":"Books, Comics & Magazines","business_country":"GB","business_currency":"GBP","legal_entity_business_tax_id":"Tax-123","business_telephone_prefix":"+44","legal_entity_phone_number":"9876543210","legal_entity_address_line1":"Test","legal_entity_address_city":"Test","legal_entity_address_state":"","legal_entity_address_postal_code":"WC2N","legal_entity_address_kana_line1":"","legal_entity_address_kana_city":"","legal_entity_address_kana_state":"","legal_entity_address_kana_postal_code":"","legal_entity_address_kana_town":"","legal_entity_address_kanji_line1":"","legal_entity_address_kanji_city":"","legal_entity_address_kanji_state":"","legal_entity_address_kanji_postal_code":"","legal_entity_address_kanji_town":"","legal_entity_first_name":"John","legal_entity_last_name":"Murphy","legal_entity_kana_first_name":"","legal_entity_kana_last_name":"","legal_entity_kanji_first_name":"","legal_entity_kanji_last_name":"","legal_entity_personal_address_line1":"Test street","legal_entity_personal_address_city":"Town","legal_entity_personal_address_state":"","legal_entity_personal_address_postal_code":"WC2N","legal_entity_dob":"01-12-1991","legal_entity_gender":"","legal_entity_ssn_last_4":"","legal_entity_personal_id_number":"","legal_entity_personal_address_kana_line1":"","legal_entity_personal_address_kana_city":"","legal_entity_personal_address_kana_state":"","legal_entity_personal_address_kana_postal_code":"","legal_entity_personal_address_kana_town":"","legal_entity_personal_address_kanji_line1":"","legal_entity_personal_address_kanji_city":"","legal_entity_personal_address_kanji_state":"","legal_entity_personal_address_kanji_postal_code":"","legal_entity_personal_address_kanji_town":"","bank_name":"Hsbc","bank_account_number":"1234","bank_sort_code":"12-11-22","bank_swift":"","bank_currency":"GBP","subscription_package_id":"1","bank_country":"GB","business_status":"Limited Company","legal_entity_dob_day":"01","legal_entity_dob_month":"12","legal_entity_dob_year":"1991","payment_option":{"data":{"card_id":"card_19ILAIKZVYnQLD5tWBcw4Kc3"},"id":"Stripe"}}
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *  {
     *      "meta": {
     *      "success": true,
     *      "code": 200
     *    },
     *    "data": {
     *      "message": "Merchant account created/updated successfully.",
     *    }
     *  }
     */
    public function saveMerchant() {

        $logData = array();
        $logData['post'] = $this->jsonRequest;

        $this->userLogger->log('info', 'User requested to save account info.', $this->app->user['id'], $logData);

        $request = $this->jsonRequest;

        $rules = [
            'regex' => [['legal_entity_first_name', "@^([a-zA-Z.\-/' ])+$@"], ['legal_entity_last_name', "@^([a-zA-Z.\-/' ])+$@"]],
            'email' => [['business_email']],
            'numeric' => [['legal_entity_phone_number']]
        ];

        $v = new \Quill\Validator($request, array(
            'legal_entity_business_name',
            'legal_entity_type',
            'legal_entity_business_tax_id',
            'legal_entity_phone_number',
            'legal_entity_address_line1',
            'legal_entity_address_city',
            'legal_entity_address_state',
            'legal_entity_address_postal_code',
            'legal_entity_dob_day',
            'legal_entity_dob_month',
            'legal_entity_dob_year',
            'legal_entity_first_name',
            'legal_entity_last_name',
            'legal_entity_personal_address_city',
            'legal_entity_personal_address_line1',
            'legal_entity_personal_address_postal_code',
            'legal_entity_personal_address_state',
            'legal_entity_ssn_last_4',
            'legal_entity_personal_id_number',
            'legal_entity_address_kana_city',
            'legal_entity_address_kana_line1',
            'legal_entity_address_kana_postal_code',
            'legal_entity_address_kana_state',
            'legal_entity_address_kana_town',
            'legal_entity_address_kanji_city',
            'legal_entity_address_kanji_line1',
            'legal_entity_address_kanji_postal_code',
            'legal_entity_address_kanji_state',
            'legal_entity_address_kanji_town',
            'legal_entity_first_name_kana',
            'legal_entity_first_name_kanji',
            'legal_entity_gender',
            'legal_entity_last_name_kana',
            'legal_entity_last_name_kanji',
            'legal_entity_business_name_kana',
            'legal_entity_business_name_kanji',
            'legal_entity_personal_address_kana_city',
            'legal_entity_personal_address_kana_line1',
            'legal_entity_personal_address_kana_postal_code',
            'legal_entity_personal_address_kana_state',
            'legal_entity_personal_address_kana_town',
            'legal_entity_personal_address_kanji_city',
            'legal_entity_personal_address_kanji_line1',
            'legal_entity_personal_address_kanji_postal_code',
            'legal_entity_personal_address_kanji_state',
            'legal_entity_personal_address_kanji_town',
            'business_registration_number',
            'business_currency',
            'bank_name',
            'bank_sort_code',
            'bank_account_number',
            'bank_iban',
            'bank_swift',
            'bank_country',
            'bank_currency',
            'business_category',
            'business_website',
            'business_email',
            'subscription_package_id',
            'business_country',
            'terms_accepted',
            'payment_option',
            'business_telephone_prefix',
            'taxable_countries',
            'tax_rate',
            'tax_on_postage',
            'business_status',
            'legal_entity_additional_owners',
            'tax_enabled',
            'referral_code'
        ));
        $v->rules($rules);

        if ($v->validate()) {

            $merchant = $v->sanatized();
            $merchant['user_id'] = $this->app->user['id'];
            $merchantUser = $this->models->merchantDetails->getByUserId($this->app->user['id']);

            if (!empty($merchant['legal_entity_additional_owners'])) {

                $nextIndex = $this->models->merchantDetailsAdditionalOwners->getNextIndex($this->app->user['id']);
                $legalEntityAdditionalOwnersReindexed = array();
                foreach ($merchant['legal_entity_additional_owners'] as $index => $value) {

                    $legalEntityAdditionalOwnersReindexed[$index + $nextIndex] = $value;
                }
                $merchant['legal_entity_additional_owners'] = $legalEntityAdditionalOwnersReindexed;
            }

            if ($merchantUser['business_country'] == 'JP') {

                throw new BaseException('We do not support your country right now.');
            }
            if (!empty($merchantUser)) {

                $activeMedia = $this->models->media->getAllByUserId($this->app->user['id'], array(), array(), '', 1, 'published');
                if (!empty($activeMedia) && !empty($merchant['tax_enabled']) && $merchantUser['tax_enabled'] != $merchant['tax_enabled']) {
                    throw new BaseException('To enable/disable tax, please delete your product from product manager and repost again after enable/disable tax.');
                }

                $account = $this->services->stripe->getAccount($merchantUser['stripe_account_id']);
            }
            $merchant['business_country'] = (!empty($merchantUser)) ? $merchantUser['business_country'] : $merchant['business_country'];
            $countrySpecs = $this->services->stripe->getCountrySpecs($merchant['business_country']);

            foreach ($countrySpecs->verification_fields->company->minimum as $field) {

                if (isset($merchant[snake_case($field, '.')])) {
                    assign_array_by_path($legalEntity, $field, $merchant[snake_case($field, '.')]);
                }
            }

            if (empty($merchantUser)) {

                $subscriptionPackage = $this->models->subscriptionPackage->getById($merchant['subscription_package_id']);
                $accountDetails = array('country' => $merchant['business_country'],
                    'email' => $merchant['business_email'],
                );

                try {

                    $account = $this->services->stripe->createAccount($accountDetails);
                } catch (\Exception $ex) {

                    throw new BaseException('Error in creating account: ' . $ex->getMessage());
                }
                $merchant['stripe_secret_key'] = $account->keys->secret;
                $merchant['stripe_publishable_key'] = $account->keys->publishable;
                $merchant['stripe_account_id'] = $account->id;
                $merchant['terms_accepted_datetime'] = gmdate('Y-m-d H:i:s');
                $merchant['terms_accepted_ip'] = $_SERVER['REMOTE_ADDR'];

                $account['transfer_schedule'] = array('delay_days' => $subscriptionPackage['payment_threshold'], 'interval' => 'daily');
                $account['tos_acceptance'] = array('date' => time(), 'ip' => $_SERVER['REMOTE_ADDR']);
            }

            if (!empty($merchant['bank_country']) && !empty($merchant['bank_currency']) && !empty($merchant['bank_account_number'])) {

                $externalAccount = array(
                    'object' => 'bank_account',
                    'country' => $merchant['bank_country'],
                    'currency' => $merchant['bank_currency'],
                    'account_number' => $merchant['bank_account_number']
                );

                if (!empty($merchant['bank_sort_code'])) {

                    $externalAccount['routing_number'] = $merchant['bank_sort_code'];
                }
                $account['external_account'] = $externalAccount;
            }

            if (!empty($legalEntity['legal_entity'])) {

                $account->legal_entity = null;
                $account->legal_entity = $legalEntity['legal_entity'];
            }

            try {

                $account->save();
            } catch (\Exception $ex) {

                if ($ex->getMessage() == 'Invalid bic') {

                    $errorMessage = 'Invalid Account Number/IBAN';
                } else {

                    $errorMessage = $ex->getMessage();
                }
                throw new BaseException('Error in creating/updating account: ' . $errorMessage);
            }
            if (empty($merchantUser)) {

                if (empty($subscriptionPackage) || empty($subscriptionPackage['is_public']) || empty($subscriptionPackage['is_active'])) {

                    throw new BaseException('Invalid subscription ID.');
                }

                if (isset($merchant['payment_option'])) {

                    if (strcmp($merchant['payment_option']['id'], 'Stripe') === 0) {

                        if (empty($this->models->user->getStripeCustomerId($this->app->user['id']))) {

                            throw new BaseException('Please add a card for payment.');
                        }

                        try {
                            
                            if (!empty($merchant['referral_code'])) {

                                $referral_details = $this->models->affiliateReferralCodes->getByReferralCode($merchant['referral_code'], $merchant['subscription_package_id']);

                                if (empty($referral_details)) {
                                    throw new BaseException('Referral code not recognized, please check and try again.');
                                }

                                if (empty($referral_details['tracking_for']) && !empty($referral_details['discount'])) {

                                    if ($referral_details['discount'] > $subscriptionPackage['rate']) {
                                        throw new BaseException('Referral code discount can\'t greater than subscription fee.');
                                    }

                                    $affiliate_coupon = $this->services->stripe->createCoupon('TagzieAffCode_' . $this->app->user['id'], $referral_details['discount'], $subscriptionPackage['rate_currency_code']);
                                }
                            }

                            $this->services->stripe->setCustomerDefaultCard($this->models->user->getStripeCustomerId($this->app->user['id']), $merchant['payment_option']['data']['card_id']);

                            $stripeEuCountries = load_config_one('stripeEuCountries');
                            if (in_array($merchant['business_country'], $stripeEuCountries)) {
                                $tax_percent = $this->app->config('tax_percent');
                            }

                            $subscribe = $this->services->stripe->createSubscription($this->models->user->getStripeCustomerId($this->app->user['id']), $merchant['subscription_package_id'], ($subscriptionPackage['discount_enabled']) ? $subscriptionPackage['discount_coupon'] : '', isset($tax_percent) && !empty($tax_percent) ? $tax_percent : '', isset($affiliate_coupon->id) && !empty($affiliate_coupon->id) ? $affiliate_coupon->id : '');

                            $merchant['stripe_subscription_id'] = $subscribe->id;
                            $merchant['current_period_start'] = $subscribe->current_period_start;
                            $merchant['current_period_end'] = $subscribe->current_period_end;
                            $merchant['subscription_package_id'] = $subscribe->plan->id;
                            $merchant['subscription_status'] = ($subscribe->status == 'active' || $subscribe->status == 'trialing') ? 1 : 0;
                            $merchant['stripe_subscription_status'] = $subscribe->status;
                        } catch (\Exception $ex) {

                            throw new BaseException('Error in payment processing: ' . $ex->getMessage());
                        }
                    } else {

                        throw new BaseException('Invalid payment option.');
                    }
                }
            }
            $merchant['stripe_charges_enabled'] = $account->charges_enabled;
            $merchant['stripe_transfers_enabled'] = $account->transfers_enabled;
            $merchant['stripe_transfers_disabled_reason'] = $account->verification->disabled_reason;
            $merchant['stripe_fields_needed'] = implode(',', $account->verification->fields_needed);
            $merchant['stripe_fields_needed_due_by'] = $account->verification->due_by;
            $merchant['stripe_legal_entity_verification_details'] = $account->legal_entity->verification->details;
            $merchant['stripe_legal_entity_verification_status'] = $account->legal_entity->verification->status;
            $merchant['created_at'] = gmdate('Y-m-d H:i:s');

            $legalEntityAdditionalOwners = (!empty($merchant['legal_entity_additional_owners'])) ? $merchant['legal_entity_additional_owners'] : array();

            unset($merchant['payment_option']);
            unset($merchant['legal_entity_additional_owners']);

            if ($this->models->merchantDetails->save($merchant)) {

                if (!empty($legalEntityAdditionalOwners)) {

                    foreach ($legalEntityAdditionalOwners as $index => $legalEntityAdditionalOwner) {

                        $_legalEntityAdditionalOwners = array();
                        $_legalEntityAdditionalOwners['user_id'] = $this->app->user['id'];
                        $_legalEntityAdditionalOwners['additional_owner_index'] = $index;
                        $_legalEntityAdditionalOwners['first_name'] = $legalEntityAdditionalOwner['first_name'];
                        $_legalEntityAdditionalOwners['last_name'] = $legalEntityAdditionalOwner['last_name'];
                        $_legalEntityAdditionalOwners['dob_day'] = $legalEntityAdditionalOwner['dob']['day'];
                        $_legalEntityAdditionalOwners['dob_month'] = $legalEntityAdditionalOwner['dob']['month'];
                        $_legalEntityAdditionalOwners['dob_year'] = $legalEntityAdditionalOwner['dob']['year'];
                        $_legalEntityAdditionalOwners['address_line1'] = $legalEntityAdditionalOwner['address']['line1'];
                        $_legalEntityAdditionalOwners['address_line2'] = $legalEntityAdditionalOwner['address']['line2'];
                        $_legalEntityAdditionalOwners['address_city'] = $legalEntityAdditionalOwner['address']['city'];
                        $_legalEntityAdditionalOwners['address_state'] = $legalEntityAdditionalOwner['address']['state'];
                        $_legalEntityAdditionalOwners['address_postal_code'] = $legalEntityAdditionalOwner['address']['postal_code'];
                        $_legalEntityAdditionalOwners['address_country'] = $legalEntityAdditionalOwner['address']['country'];

                        $this->models->merchantDetailsAdditionalOwners->save($_legalEntityAdditionalOwners);
                    }
                }

                if (empty($merchantUser)) {
                    $user['id'] = $this->app->user['id'];
                    $user['is_merchant'] = 1;

                    $user = $this->models->user->save($user);
                    $merchant = $this->models->user->getById($this->app->user['id']);
                    $app = array(
                        'base_assets_url' => $this->app->config('base_assets_url'),
                        'app_title' => $this->app->config('app_title'),
                        'master_hashtag' => $this->app->config('master_hashtag'),
                        'feedback_email' => $this->app->config('feedback_email'),
                        'sales_email' => $this->app->config('sales_email'),
                        'base_url' => $this->app->config('base_url'),
                        'support_email' => $this->app->config('support_email'),
                        'instagram_account_url' => $this->app->config('instagram_account_url'),
                        'twitter_account_url' => $this->app->config('twitter_account_url'),
                        'support_phone_uk' => $this->app->config('support_phone_uk'),
                        'support_phone_int' => $this->app->config('support_phone_int')
                    );

                    $this->services->emailNotification->sendMail(array('email' => $merchant['merchant_business_email'], 'name' => $merchant['first_name'] . ' ' . $merchant['last_name']), $this->app->config('customer_welcome_subject'), $this->core->view->make('email/merchant-welcome.php', array('user' => $merchant, 'app' => $app), false));
                }
                $data = array();

                $data['merchant'] = "Merchant account created/updated successfully.";

                echo $response = $this->core->response->json($data, FALSE);

                $logData = array();
                $logData['response'] = $response;

                $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
            } else {

                $this->userLogger->log('info', 'Nothing to change.', $this->app->user['id']);

                throw new BaseException('Nothing to change.');
            }
        } else {

            $logData = array();
            $logData['errors'] = $v->errors();

            $this->userLogger->log('error', 'Data validation failed', $this->app->user['id'], $logData);

            throw new ValidationException($v->errors());
        }
    }

    /**
     * @api {post} /api/mobile/merchant/changeSubscription Change Subscription Plan.
     * @apiName ChangeSubscription
     * @apiGroup /api/mobile/merchant
     * @apiDescription Use this api endpoint to change subscription plan.
     * @apiParamExample {json} Request-Example:
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *  {
     *      "meta": {
     *      "success": true,
     *      "code": 200
     *    },
     *    "data": {
     *      "message": "Subscription plan changed successfully.",
     *    }
     *  }
     */
    public function changeSubscription() {

        $request = $this->jsonRequest;

        $rules = [
            'numeric' => [['planid']]
        ];

        $v = new \Quill\Validator($request, array(
            'planid'
        ));
        $v->rules($rules);

        if ($v->validate()) {

            $merchant = $v->sanatized();

            $merchantUser = $this->models->user->getById($this->app->user['id']);

            $subscriptionPackage = $this->models->subscriptionPackage->getById($merchant['planid']);

            if (empty($subscriptionPackage) || empty($subscriptionPackage['is_public']) || empty($subscriptionPackage['is_active'])) {

                throw new BaseException('Invalid subscription ID.');
            }
            try {

                $subscription = $this->services->stripe->changeSubscriptionPlan($merchantUser['merchant_stripe_subscription_id'], $merchant['planid'], ($subscriptionPackage['discount_enabled']) ? $subscriptionPackage['discount_coupon'] : '');
            } catch (\Exception $ex) {

                throw new BaseException('Error in changing subscription plan: ' . $ex->getMessage());
            }

            $app = array(
                'base_assets_url' => $this->app->config('base_assets_url'),
                'app_title' => $this->app->config('app_title'),
                'master_hashtag' => $this->app->config('master_hashtag'),
                'feedback_email' => $this->app->config('feedback_email'),
                'sales_email' => $this->app->config('sales_email'),
                'base_url' => $this->app->config('base_url'),
                'support_email' => $this->app->config('support_email'),
                'instagram_account_url' => $this->app->config('instagram_account_url'),
                'twitter_account_url' => $this->app->config('twitter_account_url'),
                'support_phone_uk' => $this->app->config('support_phone_uk'),
                'support_phone_int' => $this->app->config('support_phone_int')
            );

            $this->services->emailNotification->sendMail(array('email' => $merchantUser['merchant_business_email'], 'name' => $merchantUser['first_name'] . ' ' . $merchantUser['last_name']), $this->app->config('merchant_subscription_change_subject'), $this->core->view->make('email/merchant-subscription-change.php', array('user' => $merchantUser, 'app' => $app, 'subscription' => $subscriptionPackage), false));

            $merchant = array();
            $merchant['user_id'] = $this->app->user['id'];
            $merchant['subscription_package_id'] = $subscription->plan->id;
            $this->models->merchantDetails->save($merchant);

            $account = $this->services->stripe->getAccount($merchantUser['merchant_stripe_account_id']);
            $account->transfer_schedule = array('delay_days' => $subscriptionPackage['payment_threshold'], 'interval' => 'daily');
            $account->save();

            $data = array();

            $data['merchant'] = "Subscription plan changed successfully.";

            echo $response = $this->core->response->json($data, FALSE);
        } else {

            $logData = array();
            $logData['errors'] = $v->errors();

            $this->userLogger->log('error', 'Data validation failed', $this->app->user['id'], $logData);

            throw new ValidationException($v->errors());
        }
    }

    /**
     * @api {get} /api/mobile/merchant/ Get merchant account details.
     * @apiName GetMerchant
     * @apiGroup /api/mobile/merchant
     * @apiDescription Use this api endpoint to get merchant account details.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
      {
      "meta": {
      "success": true,
      "code": 200
      },
      "data": {
      "merchant": {
      "id": "130",
      "user_id": "2",
      "legal_entity_business_name": "Abc",
      "business_registration_number": "",
      "business_currency": "USD",
      "bank_name": "HSBC",
      "bank_sort_code": "40-35-19",
      "bank_account_number": "41470477",
      "bank_iban": null,
      "bank_swift": "",
      "business_category": "Antiques",
      "business_website": "",
      "invoice_tax_inclusive": "0",
      "taxable_countries": "",
      "tax_rate": "0.00",
      "tax_on_postage": "0",
      "business_email": "pankil@prologictechnologies.in",
      "business_status": "Limited Company",
      "legal_entity_type": "company",
      "legal_entity_phone_number": "111",
      "legal_entity_business_tax_id": "tin1111",
      "legal_entity_address_line1": "ecc",
      "legal_entity_address_city": "rcvf",
      "legal_entity_address_postal_code": "WC2N",
      "legal_entity_address_state": "",
      "legal_entity_dob_day": "01",
      "legal_entity_dob_month": "12",
      "legal_entity_dob_year": "1991",
      "legal_entity_first_name": "Pankil",
      "legal_entity_last_name": "Joshi",
      "legal_entity_personal_address_city": "jkhj",
      "legal_entity_personal_address_line1": "hebhe",
      "legal_entity_personal_address_postal_code": "WC2N",
      "legal_entity_personal_address_state": "",
      "legal_entity_ssn_last_4": "",
      "legal_entity_personal_id_number": "",
      "legal_entity_address_kana_city": "",
      "legal_entity_address_kana_line1": "",
      "legal_entity_address_kana_postal_code": "",
      "legal_entity_address_kana_state": "",
      "legal_entity_address_kana_town": "",
      "legal_entity_address_kanji_city": "",
      "legal_entity_address_kanji_line1": "",
      "legal_entity_address_kanji_postal_code": "",
      "legal_entity_address_kanji_state": "",
      "legal_entity_address_kanji_town": "",
      "legal_entity_first_name_kana": "",
      "legal_entity_first_name_kanji": "",
      "legal_entity_gender": "",
      "legal_entity_last_name_kana": "",
      "legal_entity_last_name_kanji": "",
      "legal_entity_business_name_kana": "",
      "legal_entity_business_name_kanji": "",
      "legal_entity_personal_address_kana_city": "",
      "legal_entity_personal_address_kana_line1": "",
      "legal_entity_personal_address_kana_postal_code": "",
      "legal_entity_personal_address_kana_state": "",
      "legal_entity_personal_address_kana_town": "",
      "legal_entity_personal_address_kanji_city": "",
      "legal_entity_personal_address_kanji_line1": "",
      "legal_entity_personal_address_kanji_postal_code": "",
      "legal_entity_personal_address_kanji_state": "",
      "legal_entity_personal_address_kanji_town": "",
      "bank_country": "GB",
      "bank_currency": "GBP",
      "business_country": "GB",
      "terms_accepted": null,
      "current_period_start": "1479990917",
      "current_period_end": "1480944914",
      "subscription_status": "1",
      "business_telephone_prefix": "+44",
      "terms_accepted_datetime": "2016-12-05 13:35:16",
      "terms_accepted_ip": "122.173.210.249",
      "subscription_package_id": "1",
      "stripe_subscription_id": "sub_9gk60ZlQh3xX7B",
      "stripe_account_id": "acct_19NMciCPkAmaDY1D",
      "stripe_secret_key": "sk_live_ZIEkVhKVdjWeLuAukqH6e76y",
      "stripe_publishable_key": "pk_live_Uwl9AgR4Peyds73ZscZCBFpq",
      "stripe_transfers_disabled_reason": null,
      "created_at": "2016-12-05 13:41:35",
      "updated_at": "2016-12-05 15:10:16",
      "stripe_subscription_status": "active",
      "stripe_charges_enabled": "1",
      "stripe_transfers_enabled": "1",
      "stripe_fields_needed": "legal_entity.additional_owners.0.verification.document,legal_entity.additional_owners.1.verification.document,legal_entity.verification.document",
      "stripe_fields_needed_due_by": null,
      "stripe_legal_entity_verification_details": "The image supplied was not readable. It may have been too blurry or not well lit",
      "stripe_legal_entity_verification_status": "unverified",
      "user_instagram_username": "justpanku",
      "subscripton_name": "Bronze",
      "subscripton_rate": "0.00",
      "subscripton_transaction_fee": "6.50",
      "subscripton_payment_threshold": "27",
      "subscripton_eu_transaction_fee": "6.50",
      "additional_owners": [
      {
      "id": "4",
      "additional_owner_index": "1",
      "user_id": "2",
      "first_name": "Pankil",
      "last_name": "Joshi",
      "dob_day": "01",
      "dob_month": "12",
      "dob_year": "1991",
      "address_line1": "Ghfjthfkfth",
      "address_line2": "Dtyjdthdjufjth",
      "address_city": "Htdhtdhtd",
      "address_state": "Ghthfjt",
      "address_postal_code": "WC2N",
      "address_country": "GB",
      "updated_at": "2016-12-05 13:41:35"
      },
      {
      "id": "3",
      "additional_owner_index": "0",
      "user_id": "2",
      "first_name": "Pankil",
      "last_name": "Joshi",
      "dob_day": "01",
      "dob_month": "12",
      "dob_year": "1991",
      "address_line1": "Kitty ft",
      "address_line2": "Thrthrtf",
      "address_city": "Batala",
      "address_state": "Punjab",
      "address_postal_code": "WC2N",
      "address_country": "GB",
      "updated_at": "2016-12-05 13:38:57"
      }
      ]
      }
      }
      }
     */
    public function getMerchant() {

        $this->userLogger->log('info', 'User requested to get merchant details.', $this->app->user['id']);

        $request = $this->jsonRequest;

        $merchant = $this->models->merchantDetails->getByUserId($this->app->user['id']);
        $merchant['additional_owners'] = $this->models->merchantDetailsAdditionalOwners->getByUserId($this->app->user['id']);

        if ($merchant) {

            $data['merchant'] = $merchant;

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
     * @api {get} /api/mobile/merchant/balance/ Get stripe account balance.
     * @apiName GetStripeAccountBalance
     * @apiGroup /api/mobile/merchant
     * @apiDescription Use this api endpoint to get stripe account balance.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
      {
      "meta": {
      "success": true,
      "code": 200
      },
      "data": {
      "balance": {
      "object": "balance",
      "available": [
      {
      "currency": "gbp",
      "amount": 0,
      "source_types": {
      "card": 0
      }
      }
      ],
      "livemode": false,
      "pending": [
      {
      "currency": "gbp",
      "amount": 16509,
      "source_types": {
      "card": 16509
      }
      }
      ]
      }
      }
      }
     */
    public function getStripeBalance() {

        $merchant = $this->models->merchantDetails->getByUserId($this->app->user['id']);
        try {

            $data['balance'] = $this->services->stripe->getBalance($merchant['stripe_account_id']);
        } catch (\Exception $ex) {

            throw new BaseException('Error in getting account balance: ' . $ex->getMessage());
        }

        echo $response = $this->core->response->json($data, FALSE);
    }

    /**
     * @api {get} /api/mobile/merchant/reports/earnings/ Get merchant earnings.
     * @apiName GetMerchantEarnings
     * @apiGroup /api/mobile/merchant/reports
     * @apiDescription Use this api endpoint to get merchant's earnings.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
      {
      "meta": {
      "success": true,
      "code": 200
      },
      "data": {
      "earnings": {
      "this_month": "186.54",
      "last_month": null,
      "refunds": null,
      "transaction_fee": "13.00",
      "settlements": [
      {
      "amount": "186.54",
      "created_at": "2016-12-06",
      "settlement_date": "2017-01-02",
      "entries": [
      {
      "order_id": "4",
      "created_at": "2016-12-06 07:19:34",
      "amount": "100.00",
      "type_code": "new_order",
      "transaction_fee": "6.50",
      "settelement_total": "93.50"
      },
      {
      "order_id": "7",
      "created_at": "2016-12-06 07:29:45",
      "amount": "100.00",
      "type_code": "new_order",
      "transaction_fee": "6.50",
      "settelement_total": "93.50"
      }
      ]
      }
      ]
      }
      }
      }
     */
    public function getEarnings() {

        $this->userLogger->log('info', 'User requested to get earnings.', $this->app->user['id']);
        $options = array();
        $options['filter'] = $this->request->get('filter');
        $options['month'] = $this->request->get('month');
        $options['search'] = $this->request->get('search');

        $earningsThisMonth = $this->models->merchantLedger->getEarningsThisMonthByUserId($this->app->user['id'], $options);
        $earningsLastMonth = $this->models->merchantLedger->getEarningsLastMonthByUserId($this->app->user['id'], $options);
        $refunds = $this->models->merchantLedger->getRefundsThisMonthByUserId($this->app->user['id'], $options);
        $transactionFee = $this->models->merchantLedger->getTransactionFeeThisMonthByUserId($this->app->user['id'], $options);


        $settlements = $this->models->merchantLedger->getAllSettlementsByUserId($this->app->user['id'], $this->models->subscriptionPackage->getThresholdByUserId($this->app->user['id']), $options);

        foreach ($settlements as $index => $settlement) {

            $settlements[$index]['entries'] = $this->models->merchantLedger->getAllByCreatedDate($this->app->user['id'], $settlement['created_at'], $options);
        }

        if (empty($earningsThisMonth) && empty($earningsLastMonth)) {


            $this->userLogger->log('error', 'Resource not found', $this->app->user['id']);

            throw new BaseException('Resource not found');
        } else {
            $data['earnings']['this_month'] = $earningsThisMonth;
            $data['earnings']['last_month'] = $earningsLastMonth;
            $data['earnings']['refunds'] = $refunds;
            $data['earnings']['transaction_fee'] = $transactionFee;
            $data['earnings']['settlements'] = $settlements;

            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
        }
    }

    public function getEarningsBetweenDates() {

        $this->userLogger->log('info', 'User requested to get earnings between dates.', $this->app->user['id']);

        $filter = $this->request->get('filter');
        $startDate = $this->request->get('startDate');

        $this->app->user['timezone'] = get_timezone_offset_from_name($this->app->user['timezone']);

        if (!empty($filter) && $filter == 'custom' && !empty($startDate)) {

            for ($i = 0; $i <= 13; $i++) {

                $earning['date'] = gmdate('j M', strtotime($startDate . ' + ' . $i . ' days UTC'));
                $earning['amount'] = $this->models->merchantLedger->getEarningsBetweenDates($this->app->user, array('start_date' => gmdate('Y-m-d', strtotime($startDate . ' + ' . $i . ' days UTC'))))['amount'];
                $earnings_fourteen_days[] = $earning;
            }

            $data['earnings_fourteen_days'] = $earnings_fourteen_days;

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
     * @api {get} /api/mobile/merchant/countrySpecs/:country/ Get Stripe country specifications.
     * @apiName GetStripeCountrySpecs
     * @apiGroup /api/mobile/merchant
     * @apiDescription Use this api endpoint to get stripe country kyc requirements.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
      {
      "meta": {
      "success": true,
      "code": 200
      },
      "data": {
      "countrySpecs": {
      "id": "GB",
      "object": "country_spec",
      "default_currency": "gbp",
      "supported_bank_account_currencies": {
      "eur": [
      "AT",
      "BE",
      "CH",
      "DE",
      "DK",
      "ES",
      "FI",
      "FR",
      "GB",
      "IE",
      "IT",
      "LU",
      "NL",
      "NO",
      "PT",
      "SE"
      ],
      "dkk": [
      "DK"
      ],
      "gbp": [
      "GB"
      ],
      "nok": [
      "NO"
      ],
      "sek": [
      "SE"
      ],
      "usd": [
      "US"
      ]
      },
      "supported_payment_currencies": [
      "usd",
      "aed",
      "afn",
      "all",
      "amd",
      "ang",
      "aoa",
      "ars",
      "aud",
      "awg",
      "azn",
      "bam",
      "bbd",
      "bdt",
      "bgn",
      "bif",
      "bmd",
      "bnd",
      "bob",
      "brl",
      "bsd",
      "bwp",
      "bzd",
      "cad",
      "cdf",
      "chf",
      "clp",
      "cny",
      "cop",
      "crc",
      "cve",
      "czk",
      "djf",
      "dkk",
      "dop",
      "dzd",
      "egp",
      "etb",
      "eur",
      "fjd",
      "fkp",
      "gbp",
      "gel",
      "gip",
      "gmd",
      "gnf",
      "gtq",
      "gyd",
      "hkd",
      "hnl",
      "hrk",
      "htg",
      "huf",
      "idr",
      "ils",
      "inr",
      "isk",
      "jmd",
      "jpy",
      "kes",
      "kgs",
      "khr",
      "kmf",
      "krw",
      "kyd",
      "kzt",
      "lak",
      "lbp",
      "lkr",
      "lrd",
      "lsl",
      "ltl",
      "mad",
      "mdl",
      "mga",
      "mkd",
      "mnt",
      "mop",
      "mro",
      "mur",
      "mvr",
      "mwk",
      "mxn",
      "myr",
      "mzn",
      "nad",
      "ngn",
      "nio",
      "nok",
      "npr",
      "nzd",
      "pab",
      "pen",
      "pgk",
      "php",
      "pkr",
      "pln",
      "pyg",
      "qar",
      "ron",
      "rsd",
      "rub",
      "rwf",
      "sar",
      "sbd",
      "scr",
      "sek",
      "sgd",
      "shp",
      "sll",
      "sos",
      "srd",
      "std",
      "svc",
      "szl",
      "thb",
      "tjs",
      "top",
      "try",
      "ttd",
      "twd",
      "tzs",
      "uah",
      "ugx",
      "uyu",
      "uzs",
      "vnd",
      "vuv",
      "wst",
      "xaf",
      "xcd",
      "xof",
      "xpf",
      "yer",
      "zar",
      "zmw"
      ],
      "supported_payment_methods": [
      "alipay",
      "card",
      "stripe"
      ],
      "verification_fields": {
      "individual": {
      "minimum": [
      "external_account",
      "legal_entity.address.city",
      "legal_entity.address.line1",
      "legal_entity.address.postal_code",
      "legal_entity.dob.day",
      "legal_entity.dob.month",
      "legal_entity.dob.year",
      "legal_entity.first_name",
      "legal_entity.last_name",
      "legal_entity.type",
      "tos_acceptance.date",
      "tos_acceptance.ip"
      ],
      "additional": [
      "legal_entity.verification.document"
      ]
      },
      "company": {
      "minimum": [
      "external_account",
      "legal_entity.additional_owners",
      "legal_entity.address.city",
      "legal_entity.address.line1",
      "legal_entity.address.postal_code",
      "legal_entity.business_name",
      "legal_entity.business_tax_id",
      "legal_entity.dob.day",
      "legal_entity.dob.month",
      "legal_entity.dob.year",
      "legal_entity.first_name",
      "legal_entity.last_name",
      "legal_entity.personal_address.city",
      "legal_entity.personal_address.line1",
      "legal_entity.personal_address.postal_code",
      "legal_entity.type",
      "tos_acceptance.date",
      "tos_acceptance.ip"
      ],
      "additional": [
      "legal_entity.verification.document"
      ]
      }
      }
      }
      }
      }
     */
    public function getRequiredFields($country) {

        try {

            $data['countrySpecs'] = $this->services->stripe->getCountrySpecs($country);
        } catch (\Exception $ex) {

            throw new BaseException('Error in getting country specs: ' . $ex->getMessage());
        }

        echo $response = $this->core->response->json($data, FALSE);
    }

    public function uploadFile() {

        if (isset($_FILES["file"])) {

            $ext = end((explode(".", $_FILES["file"]["name"]))); # extra () to prevent notice
            $tempFile = sha1_file($_FILES['file']['tmp_name']);

            if (!move_uploaded_file(
                            $_FILES['file']['tmp_name'], sprintf($this->app->config('verification_documents_directory') . '/%s.%s', $tempFile, $ext
                            )
                    )) {
                throw new BaseException('Failed to move uploaded file.');
            }

            $file = $this->services->stripe->uploadFile(sprintf($this->app->config('verification_documents_directory') . '/%s.%s', $tempFile, $ext));
            $file = json_decode($file, true);

            $account = $this->services->stripe->getAccount($this->models->merchantDetails->getByUserId($this->app->user['id'])['stripe_account_id']);
            $verification = array('verification' => array('document' => $file['id']));
            $account->legal_entity = null;
            $account->legal_entity = $verification;
            try {

                $account->save();
            } catch (\Exception $ex) {

                throw new BaseException('Error in uploading document: ' . $ex->getMessage());
            }

            $data['merchant'] = $this->models->merchantDetails->getByUserId($this->app->user['id']);

            echo $response = $this->core->response->json($data, FALSE);
        } else {

            BaseException('Please select a file.');
        }
    }

    public function uploadFileAdditionalOwners($ownerIndex) {

        if (isset($_FILES["file"])) {

            $ext = end((explode(".", $_FILES["file"]["name"]))); # extra () to prevent notice
            $tempFile = sha1_file($_FILES['file']['tmp_name']);

            if (!move_uploaded_file(
                            $_FILES['file']['tmp_name'], sprintf($this->app->config('verification_documents_directory') . '/%s.%s', $tempFile, $ext
                            )
                    )) {
                throw new BaseException('Failed to move uploaded file.');
            }

            $file = $this->services->stripe->uploadFile(sprintf($this->app->config('verification_documents_directory') . '/%s.%s', $tempFile, $ext));
            $file = json_decode($file, true);

            $account = $this->services->stripe->getAccount($this->models->merchantDetails->getByUserId($this->app->user['id'])['stripe_account_id']);
            $additionalOwners = array();
            $additionalOwners['additional_owners'][$ownerIndex] = array('verification' => array('document' => $file['id']));
            $account->legal_entity = null;
            $account->legal_entity = $additionalOwners;
            try {

                $account->save();
            } catch (\Exception $ex) {

                throw new BaseException('Error in uploading document: ' . $ex->getMessage());
            }

            $data['merchant'] = $this->models->merchantDetails->getByUserId($this->app->user['id']);

            echo $response = $this->core->response->json($data, FALSE);
        } else {

            BaseException('Please select a file.');
        }
    }

    public function processSettlements() {

        $merchants = $this->models->user->getAllMerchants();

        foreach ($merchants as $merchant) {
            $amount = 0;
            $numberOfOrders = 0;
            $recievedAmount = 0;
            $user = $this->app->user->getById($merchant['user_id']);
            $merchant['timezone'] = $user['timezone'];
            $settlements = $this->models->merchantLedger->getPendingSettlementToday($merchant, $this->models->subscriptionPackage->getThresholdByUserId($merchant['id']));

            foreach ($settlements as $settlement) {

                if (!empty($settlement['amount'])) {

                    $items = array();
                    $amount = $amount + $settlement['transaction_fee'];
                    $numberOfOrders++;
                    $orderIds[] = $settlement['order_id'];
                    $item['order_id'] = $settlement['order_id'];
                    $item['transaction_fee_rate'] = $settlement['transaction_fee_rate'];
                    $item['transaction_fee'] = $settlement['transaction_fee'];
                    $item['order_total'] = $settlement['amount'];
                    $item['recieved_amount'] = $settlement['amount'] - $settlement['transaction_fee'];
                    $recievedAmount = $recievedAmount + $item['recieved_amount'];

                    $items[] = $item;
                }
            }

            if ($amount > 0) {

                $invoice['items'] = serialize($items);
                $invoice['amount'] = $amount;
                $invoice['recieved_amount'] = $recievedAmount;
                $invoice['order_ids'] = implode(',', $orderIds);
                $invoice['type'] = 'fees';
                $invoice['number_of_orders'] = $numberOfOrders;
                $invoice['status'] = 'paid';
                $invoice['user_id'] = $merchant['id'];

                $invoice = $this->models->invoice->save($invoice);

                if (!file_exists($this->app->config('tagzie_invoices_directory') . '/TGZ-' . $invoice['id'] . '.pdf')) {

                    $data['invoice'] = $invoice;
                    $data['merchant'] = $merchant;
                    $data['countries'] = load_config_one('countries');
                    $data['currencies'] = load_config_one('currencies');
                    $data['app'] = $this->app->config();
                    $dompdf = new \Dompdf\Dompdf();
                    $dompdf->set_option('isHtml5ParserEnabled', true);
                    $dompdf->set_option('isRemoteEnabled', true);
                    $dompdf->set_option('defaultFont', 'Ubuntu');
                    $dompdf->loadHtml($this->core->view->make('pdf/tagzie-invoice.php', $data, false));
                    $dompdf->render();
                    $pdf = $dompdf->output();
                    file_put_contents($this->app->config('tagzie_invoices_directory') . '/TGZ-' . $invoice['id'] . '.pdf', $pdf);
                }
            }
        }
    }

    public function getInvoice($id) {

        $invoice = $this->models->invoice->getById($id, $this->app->user['id']);

        if (!empty($invoice)) {

            $file = $this->app->config('tagzie_invoices_directory') . '/TGZ-' . $id . '.pdf';

            output_file($file);
        } else {

            $this->app->slim->notFound();
        }
    }

    /**
     * @api {get} /api/mobile/merchant/orders/:id/cancel/ Cancel sale order.
     * @apiName CancelSaleOrder
     * @apiGroup /api/mobile/merchant/orders
     * @apiDescription Use this api endpoint to cancel sale order by merchant.
     * @apiParamExample {json} Request-Example:
     *     {
     *       "amount": "20",
     *       "reason": "Some text"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *  {
     *      "meta": {
     *      "success": true,
     *      "code": 200
     *    },
     *    "data": {
     *      "message": "Order updated successfully.",
     *    }
     *  }
     */
//    public function cancel($id) {
//
//        $request = $this->jsonRequest;
//
//        $_suborder = $this->models->orderSuborder->getById($id);
//        $order = $this->models->order->getById($id);
//
//        if ($_suborder['status'] == 'in_progress' || $_suborder['status'] == 'pending') {
//
//            $suborder = array('status' => 'cancelled', 'id' => $id, 'cancelled_at' => gmdate('Y-m-d H:i:s'));
//
//            $transactionEntryDescription = 'Order cancelled.';
//            $transactionEntryType = 'cancel_order';
//            $verb = 'cancelled';
//        } elseif ($_suborder['status'] == 'shipped' || $_suborder['status'] == 'returned') {
//
//            $suborder = array('status' => 'refunded', 'id' => $id);
//
//            $transactionEntryDescription = 'Order refunded.';
//            $transactionEntryType = 'refund_order';
//            $verb = 'refunded';
//        }
//
//        $tansection_amt = $this->models->merchantLedger->getByTransactionIdOrderAmount($order['payment_transaction_id'], $_suborder['merchant_id']);
//        $refundAmount = (!empty($request['amount']) && $request['amount'] != $_suborder['total']) ? $request['amount'] : 0;
//        $additionalFee = $this->models->merchantLedger->getAdditionalFee($order['payment_transaction_id'], $_suborder['merchant_id']);
//
//        if ($refundAmount > $_suborder['total']) {
//
//            throw new BaseException('Refund amount can\'t be greater than order total.');
//        }
//        if ($transection_id = $this->services->transaction->refund($order['payment_transaction_id'], $refundAmount)) {
//
//            $suborder['refunded_at'] = gmdate('Y-m-d H:i:s');
//            $suborder['total_refunded'] = (!empty($refundAmount)) ? $refundAmount : $_suborder['total'];
//
//            $suborder['cancel_reason'] = (!empty($request['reason'])) ? $request['reason'] : '';
//
//            $message = 'Order #' . $id . ' was ' . $verb . ' because of the following reason: ' . $request['reason'];
//
//            $this->repositories->messageRepository->postMessage($_suborder['merchant_id'], array('order_id' => $id, 'text' => $message, 'recipient_id' => $order['user_id']));
//
//            $suborder = $this->models->orderSuborder->updateByMerchantId($suborder, $_suborder['merchant_id']);
//
//            $newOrderEntry = $this->models->merchantLedger->getByTransactionIdNewOrder($order['payment_transaction_id'], $_suborder['merchant_id']);
//
//            $entry['amount'] = ($refundAmount > 0) ? $refundAmount : getMoney(($newOrderEntry['amount']));
//            $entry['flow'] = 'out';
//            $entry['description'] = $transactionEntryDescription;
//            $entry['type_code'] = $transactionEntryType;
//            $entry['currency_code'] = $newOrderEntry['currency_code'];
//            $entry['user_id'] = $_suborder['merchant_id'];
//            $entry['order_id'] = $id;
//            $entry['transaction_id'] = $transection_id['id'];
//            $this->models->merchantLedger->save($entry);
//
//            $entry['amount'] = ($refundAmount > 0) ? getMoney($refundAmount * ( $tansection_amt['transaction_fee_rate'] / 100 )) : getMoney(($tansection_amt['amount']));
//            $entry['flow'] = 'in';
//            $entry['description'] = 'Transaction fee returned';
//            $entry['type_code'] = 'transaction_fee_returned';
//            $entry['currency_code'] = $newOrderEntry['currency_code'];
//            $entry['user_id'] = $_suborder['merchant_id'];
//            $entry['order_id'] = $id;
//            $entry['transaction_id'] = $transection_id['id'];
//            $this->models->merchantLedger->save($entry);
//
//            $entry['amount'] = ($refundAmount > 0) ? getMoney(($additionalFee * ($refundAmount / $_suborder['total']))) : getMoney(($additionalFee));
//            $entry['flow'] = 'in';
//            $entry['description'] = 'Additional fee returned';
//            $entry['type_code'] = 'additional_fee_returned';
//            $entry['currency_code'] = $newOrderEntry['currency_code'];
//            $entry['user_id'] = $_suborder['merchant_id'];
//            $entry['order_id'] = $id;
//            $entry['transaction_id'] = $transection_id['id'];
//            $this->models->merchantLedger->save($entry);
//        }
//
//        if (!empty($suborder)) {
//
//            $orderItems = $this->models->orderItem->getBySuborderId($id);
//
//            foreach ($orderItems as $item) {
//
//                $this->models->mediaStockItem->increment($item['media_id'], $item['variant_id'], $item['ordered_quantity']);
//            }
//            $app = array(
//                'base_assets_url' => $this->app->config('base_assets_url'),
//                'domain' => $this->app->config('domain'),
//                'base_url' => $this->app->config('base_url'),
//                'app_title' => $this->app->config('app_title'),
//                'master_hashtag' => $this->app->config('master_hashtag'),
//                'feedback_email' => $this->app->config('feedback_email'),
//                'sales_email' => $this->app->config('sales_email'),
//                'support_email' => $this->app->config('support_email'),
//                'instagram_account_url' => $this->app->config('instagram_account_url'),
//                'twitter_account_url' => $this->app->config('twitter_account_url'),
//                'support_phone_uk' => $this->app->config('support_phone_uk'),
//                'support_phone_int' => $this->app->config('support_phone_int')
//            );
//
//            $user = $this->models->user->getById($_suborder['user_id']);
//
//            $orderArray = $this->models->orderSuborder->getById($id);
//            $orderArray['tracking_details'] = unserialize($_suborder['tracking_details']);
//            $orderArray['merchant'] = $this->models->merchantDetails->getByUserId($this->app->user['id']);
//
//            if ($_suborder['status'] == 'in_progress' || $_suborder['status'] == 'pending') {
//
//                $this->addHistoryToSubOrder($id, 'Order cancelled', 'cancelled');
//
//                $this->services->emailNotification->sendMail(array('email' => $user['email'], 'name' => $user['first_name'] . ' ' . $user['last_name']), sprintf($this->app->config('customer_order_cancelled_subject'), '#TGZ' . $orderArray['id']), $this->core->view->make('email/order-cancellation.php', array('user' => $user, 'app' => $app, 'order' => $orderArray, 'countries' => load_config_one('countries'), 'currencies' => load_config_one('currencies')), false));
//            } elseif ($_suborder['status'] == 'shipped' || $_suborder['status'] == 'returned') {
//
//                $this->addHistoryToSubOrder($id, 'Order refunded', 'refunded');
//
//                $this->services->emailNotification->sendMail(array('email' => $user['email'], 'name' => $user['first_name'] . ' ' . $user['last_name']), sprintf($this->app->config('customer_order_refunded_subject'), '#TGZ' . $orderArray['id']), $this->core->view->make('email/order-refund.php', array('user' => $user, 'app' => $app, 'order' => $orderArray, 'countries' => load_config_one('countries'), 'currencies' => load_config_one('currencies')), false));
//            }
//
//            if (file_exists($this->app->config('invoices_directory') . '/TZ-' . $id . '.pdf')) {
//
//                unlink($this->app->config('invoices_directory') . '/TZ-' . $id . '.pdf');
//            }
//
//            $data['message'] = 'Order updated successfully.';
//
//            $this->core->response->json($data);
//        } else {
//
//            throw new BaseException('Nothing to change!');
//        }
//    }

    public function cancel($id) {

        $request = $this->jsonRequest;

        $_suborder = $this->models->orderSuborder->getById($id);
        $order = $this->models->order->getById($id);

        $flag = '';

        $request['action_request'] = isset($request['action_request']) ? $request['action_request'] : '';

        if ($_suborder['status'] == 'in_progress' || $_suborder['status'] == 'pending' || ($request['action_request'] == 'accept_cancellation' && $_suborder['status'] == 'requested_cancellation')) {

            $suborder = array('status' => 'cancelled', 'id' => $id, 'cancelled_at' => gmdate('Y-m-d H:i:s'));

            $transactionEntryDescription = 'Order cancelled.';
            $transactionEntryType = 'cancel_order';
            $verb = 'cancelled';
        } elseif ($_suborder['status'] == 'shipped' || $_suborder['status'] == 'returned') {

            $suborder = array('status' => 'refunded', 'id' => $id);

            $transactionEntryDescription = 'Order refunded.';
            $transactionEntryType = 'refund_order';
            $verb = 'refunded';
        } else if ($request['action_request'] == 'decline_cancellation' && $_suborder['status'] == 'requested_cancellation') {
            $suborder = array('status' => 'declined', 'id' => $id, 'declined_at' => gmdate('Y-m-d H:i:s'));

            $suborder['decline_reason'] = (!empty($request['decline_reason'])) ? $request['decline_reason'] : '';

            $message = 'Cancellation request for Order #TGZ' . $id . ' has been declined for the following reason: ' . $request['decline_reason'];

            $this->repositories->messageRepository->postMessage($_suborder['merchant_id'], array('order_id' => $id, 'text' => $message, 'recipient_id' => $order['user_id']));

            $suborder = $this->models->orderSuborder->updateByMerchantId($suborder, $_suborder['merchant_id']);
        }

        if ($request['action_request'] != 'decline_cancellation') {

            $tansection_amt = $this->models->merchantLedger->getByTransactionIdOrderAmount($order['payment_transaction_id'], $_suborder['merchant_id']);
            $refundAmount = (!empty($request['amount']) && $request['amount'] != $_suborder['total']) ? $request['amount'] : 0;
            $additionalFee = $this->models->merchantLedger->getAdditionalFee($order['payment_transaction_id'], $_suborder['merchant_id']);

            if ($refundAmount > $_suborder['total']) {

                throw new BaseException('Refund amount can\'t be greater than order total.');
            }
            if ($transection_id = $this->services->transaction->refund($order['payment_transaction_id'], $refundAmount)) {

                $suborder['refunded_at'] = gmdate('Y-m-d H:i:s');
                $suborder['total_refunded'] = (!empty($refundAmount)) ? $refundAmount : $_suborder['total'];

                if ($request['action_request'] == 'accept_cancellation' && $_suborder['status'] == 'requested_cancellation') {
                    $flag = 'Requested for cancellation';
                    $suborder['cancel_reason'] = $flag;
                    $request['reason'] = $flag;
                    $message = 'Order #TGZ' . $id . ' has been ' . $verb . ' and a refund has been issued to your original payment method in full.';
                } else {
                    $suborder['cancel_reason'] = (!empty($request['reason'])) ? $request['reason'] : '';
                    $message = 'Order #TGZ' . $id . ' has been ' . $verb . ' for the following reason: ' . $request['reason'];
                }

                $this->repositories->messageRepository->postMessage($_suborder['merchant_id'], array('order_id' => $id, 'text' => $message, 'recipient_id' => $order['user_id']));

                $suborder = $this->models->orderSuborder->updateByMerchantId($suborder, $_suborder['merchant_id']);

                $newOrderEntry = $this->models->merchantLedger->getByTransactionIdNewOrder($order['payment_transaction_id'], $_suborder['merchant_id']);

                $entry['amount'] = ($refundAmount > 0) ? $refundAmount : getMoney(($newOrderEntry['amount']));
                $entry['flow'] = 'out';
                $entry['description'] = $transactionEntryDescription;
                $entry['type_code'] = $transactionEntryType;
                $entry['currency_code'] = $newOrderEntry['currency_code'];
                $entry['user_id'] = $_suborder['merchant_id'];
                $entry['order_id'] = $id;
                $entry['transaction_id'] = $transection_id['id'];
                $this->models->merchantLedger->save($entry);

                $entry['amount'] = ($refundAmount > 0) ? getMoney($refundAmount * ( $tansection_amt['transaction_fee_rate'] / 100 )) : getMoney(($tansection_amt['amount']));
                $entry['flow'] = 'in';
                $entry['description'] = 'Transaction fee returned';
                $entry['type_code'] = 'transaction_fee_returned';
                $entry['currency_code'] = $newOrderEntry['currency_code'];
                $entry['user_id'] = $_suborder['merchant_id'];
                $entry['order_id'] = $id;
                $entry['transaction_id'] = $transection_id['id'];
                $this->models->merchantLedger->save($entry);

                $entry['amount'] = ($refundAmount > 0) ? getMoney(($additionalFee * ($refundAmount / $_suborder['total']))) : getMoney(($additionalFee));
                $entry['flow'] = 'in';
                $entry['description'] = 'Additional fee returned';
                $entry['type_code'] = 'additional_fee_returned';
                $entry['currency_code'] = $newOrderEntry['currency_code'];
                $entry['user_id'] = $_suborder['merchant_id'];
                $entry['order_id'] = $id;
                $entry['transaction_id'] = $transection_id['id'];
                $this->models->merchantLedger->save($entry);
            }
        }

        if (!empty($suborder)) {

            $orderItems = $this->models->orderItem->getBySuborderId($id);

            foreach ($orderItems as $item) {

                $this->models->mediaStockItem->increment($item['media_id'], $item['variant_id'], $item['ordered_quantity']);
            }
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

            $user = $this->models->user->getById($_suborder['user_id']);

            $orderArray = $this->models->orderSuborder->getById($id);
            $orderArray['tracking_details'] = unserialize($_suborder['tracking_details']);
            $orderArray['merchant'] = $this->models->merchantDetails->getByUserId($this->app->user['id']);

            if ($_suborder['status'] == 'in_progress' || $_suborder['status'] == 'pending' || ($request['action_request'] == 'accept_cancellation' && $_suborder['status'] == 'requested_cancellation')) {

                $this->addHistoryToSubOrder($id, 'Order cancelled', 'cancelled');

                $this->services->emailNotification->sendMail(array('email' => $user['email'], 'name' => $user['first_name'] . ' ' . $user['last_name']), sprintf($this->app->config('customer_order_cancelled_subject'), '#TGZ' . $orderArray['id']), $this->core->view->make('email/order-cancellation.php', array('user' => $user, 'flag' => $flag, 'app' => $app, 'order' => $orderArray, 'countries' => load_config_one('countries'), 'currencies' => load_config_one('currencies')), false));
            } elseif ($_suborder['status'] == 'shipped' || $_suborder['status'] == 'returned') {

                $this->addHistoryToSubOrder($id, 'Order refunded', 'refunded');

                $this->services->emailNotification->sendMail(array('email' => $user['email'], 'name' => $user['first_name'] . ' ' . $user['last_name']), sprintf($this->app->config('customer_order_refunded_subject'), '#TGZ' . $orderArray['id']), $this->core->view->make('email/order-refund.php', array('user' => $user, 'app' => $app, 'order' => $orderArray, 'countries' => load_config_one('countries'), 'currencies' => load_config_one('currencies')), false));
            } else if ($request['action_request'] == 'decline_cancellation' && $_suborder['status'] == 'requested_cancellation') {
                $this->addHistoryToSubOrder($id, 'Order declined', 'declined');

                $this->services->emailNotification->sendMail(array('email' => $user['email'], 'name' => $user['first_name'] . ' ' . $user['last_name']), sprintf($this->app->config('customer_order_declined_subject'), '#TGZ' . $orderArray['id']), $this->core->view->make('email/order-declined.php', array('user' => $user, 'app' => $app, 'order' => $orderArray, 'countries' => load_config_one('countries'), 'currencies' => load_config_one('currencies')), false));
            }

            if ($request['action_request'] != 'decline_cancellation' && file_exists($this->app->config('invoices_directory') . '/TZ-' . $id . '.pdf')) {

                unlink($this->app->config('invoices_directory') . '/TZ-' . $id . '.pdf');
            }

            $data['message'] = 'Order updated successfully.';

            $this->core->response->json($data);
        } else {

            throw new BaseException('Nothing to change!');
        }
    }

    public function getMoney($amount) {

        return round($amount, 2);
    }

    private function addHistoryToSubOrder($subOrderId, $comment, $status) {

        $history = '';
        $history['datetime'] = gmdate('Y-m-d H:i:s');
        $history['status'] = $status;
        $history['comment'] = $comment;

        $history = serialize($history);

        $this->models->orderSuborder->saveHistory($subOrderId, $history);
    }

    public function updateStripeAccount() {

        $merchantUsers = $this->models->user->getAllMerchants();

        if (!empty($merchantUsers)) {

            foreach ($merchantUsers as $user) {

                $stripeAccount = $this->services->stripe->getAccount($user['merchant_stripe_account_id']);

                $merchantDetails = array();
                $merchantDetails['user_id'] = $user['id'];
                $merchantDetails['stripe_charges_enabled'] = $stripeAccount->charges_enabled;
                $merchantDetails['stripe_transfers_enabled'] = $stripeAccount->transfers_enabled;
                $merchantDetails['stripe_transfers_disabled_reason'] = $stripeAccount->verification->disabled_reason;
                $merchantDetails['stripe_fields_needed'] = implode(',', $stripeAccount->verification->fields_needed);
                $merchantDetails['stripe_fields_needed_due_by'] = $stripeAccount->verification->due_by;
                $merchantDetails['stripe_legal_entity_verification_details'] = $stripeAccount->legal_entity->verification->details;
                $merchantDetails['stripe_legal_entity_verification_status'] = $stripeAccount->legal_entity->verification->status;

                $periodPassed = false;

                if ($user['merchant_stripe_transfers_disabled_first_mail_sent_at'] == 0) {

                    $periodPassed = true;
                    $merchantDetails['stripe_transfers_disabled_first_mail_sent_at'] = strtotime(gmdate('Y-m-d') . ' UTC');
                } elseif (time() >= ($user['merchant_stripe_transfers_disabled_first_mail_sent_at'] + (60 * 60 * 24 * 4))) {

                    $periodPassed = true;
                } elseif (time() >= ($user['merchant_stripe_transfers_disabled_first_mail_sent_at'] + (60 * 60 * 24 * 6))) {

                    $periodPassed = true;
                } elseif (time() >= $stripeAccount->verification->due_by && time() <= ($stripeAccount->verification->due_by + (60 * 60 * 24 * 1))) {

                    $periodPassed = true;
                }

                if (empty($stripeAccount->verification->due_by)) {

                    $merchantDetails['stripe_transfers_disabled_first_mail_sent_at'] = 0;
                }

                if ((!$stripeAccount->transfers_enabled || !$stripeAccount->transfers_enabled) && ($stripeAccount->legal_entity->verification->status != 'pending' || $stripeAccount->legal_entity->additional_owners->verification->status != 'pending') && $periodPassed) {

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

                    $this->services->emailNotification->sendMail(array('email' => $user['merchant_business_email'], 'name' => $user['first_name'] . ' ' . $user['last_name']), $this->app->config('merchant_account_updated'), $this->core->view->make('email/merchant-account-updated.php', array('user' => $user, 'app' => $app, 'stripe_account' => $stripeAccount), false));
                }

                $this->models->merchantDetails->save($merchantDetails);
            }
        }
    }

}
