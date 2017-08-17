<?php

namespace App\Controllers\Api\Mobile;

use Quill\Factories\CoreFactory;
use Quill\Factories\ServiceFactory;
use Quill\Factories\ModelFactory;
use Quill\Factories\RepositoryFactory;
use Quill\Exceptions\BaseException;

/**
 * Admin Controller, contains all methods related to superadmin.
 * $2y$10$WCZ5H2Mj7dJCedscfT38J.UFt7PcCvA32QvBCEb8O.5WoVH3Hudte
 * @package App\Controllers\Api\Mobile
 * @author Pankil Joshi <pankil@prologictechnologies.in>
 * @version 1.0
 * @uses Quill\Factories\CoreFactory
 * @uses Quill\Factories\ServiceFactory
 * @uses Quill\Factories\ModelFactory
 * @uses Quill\Exceptions\BaseException
 * @uses Quill\Factories\RepositoryFactory;
 * @extend \App\Controllers\Web\Admin\AdminBaseController
 */
class AdminUserController extends \App\Controllers\Web\Admin\AdminBaseController {

    /**
     * Constructor
     * @param object contains app core.
     */
    function __construct($app = NULL) {

        // Call to parent class constructer.
        parent::__construct($app);

        $app->slim->config('debug', false);

        // Instantiate models.
        $this->models = ModelFactory::boot(array('User', 'OrderSuborder', 'Order', 'MerchantLedger', 'OrderItem', 'MediaStockItem', 'MerchantDetails', 'SubscriptionPackage', 'Media'));

        // Instantiate core classes.
        $this->core = CoreFactory::boot(array('Response', 'Http', 'View'));

        // Instantiate services.
        $this->services = ServiceFactory::boot(array('Stripe', 'Jwt', 'EmailNotification', 'Transaction'));

        // Instantiate repositories.
        $this->repositories = RepositoryFactory::boot(array('MessageRepository'));

        // Load email templates.
        $this->app->config(load_config_one('emailTemplates'));

        $this->app->slim->config('debug', false);
        $app = $this->app;
        $this->request = $this->app->slim->request;
        if ($this->request->isPost()) {

            $this->jsonRequest = json_decode($this->request->getBody(), TRUE);

            if ($this->request->headers->get('CONTENT_TYPE') == 'application/json') {

                if (!$this->jsonRequest) {

                    throw new BaseException('Invalid request format.');
                }
            }
        }
        $this->app->slim->error(function (\Exception $exception) use ($app) {
            $this->app->slim->render('jsonException.php', array('exception' => $exception));
        });
    }

    /**
     * @api {get} /admin/delete_user/:id Delete user.
     * @apiName deleteUser
     * @apiGroup admin
     * @apiDescription Use this api endpoint to delete a user by superadmin.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *  {
     *      "meta": {
     *      "success": true,
     *      "code": 200
     *    },
     *    "data": {
     *      "message": "success"
     *    }
     *  }
     */
    public function deleteUser($id) {

        $this->userLogger->log('info', 'Admin requested to delete user with id: ' . $id, $_SESSION['user_data']['user_id']);
        $user_details = $this->models->user->getById($id);
        
        if ($user_details['is_merchant'] && $user_details['merchant_deactivate'] == 0) {

            if ($this->ordersCheck($id)) {

                throw new BaseException('You are currently unable to delete merchant as he/she has open orders from customers.');
            }

            try {

                $this->services->stripe->cancelSubscription($user_details['merchant_stripe_subscription_id']);
            } catch (\Exception $e) {

                throw new BaseException('Subscription cancellation failed: ' . $e->getMessage());
            }
        }
        
        $data = array(
            'id' => $id,
            'is_deleted' => '1',
            'deleted_at' => date('Y-m-d H:i:s')
        );

        $response = $this->models->user->save($data);

        if ($response) {

            $data['message'] = 'success';

            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $_SESSION['user_data']['user_id'], $logData);
        } else {

            $this->userLogger->log('error', 'Resource not found', $_SESSION['user_data']['user_id']);

            throw new BaseException('Resource not found');
        }
    }

    /**
     * @api {get} /admin/update_user/ Update user.
     * @apiName updateUser
     * @apiGroup admin
     * @apiDescription Use this api endpoint to update a user by superadmin.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *  {
     *      "meta": {
     *      "success": true,
     *      "code": 200
     *    },
     *    "data": {
     *      "message": "success"
     *    }
     *  }
     */
    public function updateUser() {
        $id = $this->request->get('id');
        $status = $this->request->get('action');
        if ($id == '' || $status == '') {
            throw new BaseException('Unauthorized access!');
        }
        $this->userLogger->log('info', 'Admin requested to update user details with id: ' . $id, $_SESSION['user_data']['user_id']);
        $user_details = $this->models->user->getById($id);

        $data = array(
            'id' => $id,
            'merchant_deactivate' => $status,
            'customer_deactivate' => $status
        );
        $response = $this->models->user->save($data);

        if ($response) {

            $data['message'] = 'success';
            $data['status'] = $status;
            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $_SESSION['user_data']['user_id'], $logData);
        } else {

            $this->userLogger->log('error', 'Resource not found', $_SESSION['user_data']['user_id']);

            throw new BaseException('Resource not found');
        }
    }

    /**
     * @api {get} /admin/cancel_order/:id Cancel order.
     * @apiName cancelOrder
     * @apiGroup admin
     * @apiDescription Use this api endpoint to cancel an order by superadmin.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *  {
     *      "meta": {
     *      "success": true,
     *      "code": 200
     *    },
     *    "data": {
     *      "message": "Order updated successfully!"
     *    }
     *  }
     */
    public function cancelOrder($id) {

        $request = $this->jsonRequest;

        $_suborder = $this->models->orderSuborder->getById($id);

        if (!empty($_suborder)) {

            $order = $this->models->order->getById($_suborder['order_id']);

            if ($_suborder['status'] == 'in_progress' || $_suborder['status'] == 'pending') {

                $suborder = array('status' => 'cancelled', 'id' => $id, 'cancelled_at' => gmdate('Y-m-d H:i:s'));

                $transactionEntryDescription = 'Order cancelled.';
                $transactionEntryType = 'cancel_order';
                $verb = 'cancelled';
            } elseif ($_suborder['status'] == 'shipped' || $_suborder['status'] == 'returned') {

                $suborder = array('status' => 'refunded', 'id' => $id);

                $transactionEntryDescription = 'Order refunded.';
                $transactionEntryType = 'refund_order';
                $verb = 'refunded';
            }

            $tansection_amt = $this->models->merchantLedger->getByTransactionIdOrderAmount($order['payment_transaction_id'], $_suborder['merchant_id']);
            $refundAmount = (!empty($request['amount']) && $request['amount'] != $_suborder['total']) ? $request['amount'] : 0;
            $additionalFee = $this->models->merchantLedger->getAdditionalFee($order['payment_transaction_id'], $_suborder['merchant_id']);

            if ($refundAmount > $_suborder['total']) {

                throw new BaseException('Refund amount can\'t be greater than order total.');
            }
            if ($transection_id = $this->services->transaction->refund($order['payment_transaction_id'], $refundAmount)) {

                $suborder['refunded_at'] = gmdate('Y-m-d H:i:s');

                $suborder['cancel_reason'] = (!empty($request['reason'])) ? $request['reason'] : '';

                $message = 'Order #' . $id . ' was ' . $verb . ' because of the following reason: ' . $suborder['cancel_reason'];

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

            if (!empty($suborder)) {

                $orderItems = $this->models->orderItem->getBySuborderId($id);

                foreach ($orderItems as $item) {

                    $this->models->mediaStockItem->increment($item['media_id'], $item['variant_id'], $item['ordered_quantity']);
                }

                if ($_suborder['status'] == 'in_progress' || $_suborder['status'] == 'pending') {

                    $this->addHistoryToSubOrder($id, 'Order cancelled', 'cancelled');
                } elseif ($_suborder['status'] == 'shipped' || $_suborder['status'] == 'returned') {

                    $this->addHistoryToSubOrder($id, 'Order refunded', 'refunded');
                }

                $data['message'] = 'Order updated successfully!';
                if (file_exists($this->app->config('invoices_directory') . '/TZ-' . $id . '.pdf')) {

                    unlink($this->app->config('invoices_directory') . '/TZ-' . $id . '.pdf');
                }
                $this->core->response->json($data);
            } else {

                throw new BaseException('Nothing to change!');
            }
        } else {

            throw new BaseException('Order not found.');
        }
    }

    private function addHistoryToSubOrder($subOrderId, $comment, $status) {

        $history = '';
        $history['datetime'] = gmdate('Y-m-d H:i:s');
        $history['status'] = $status;
        $history['comment'] = $comment;

        $history = serialize($history);

        $this->models->orderSuborder->saveHistory($subOrderId, $history);
    }

    /**
     * @api {post} /admin/mobile/merchant/changeSubscription Change Subscription Plan.
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
    public function changeSubscription($userId) {

        $request = json_decode($this->app->slim->request->getBody(), true);

        $merchant = $request;

        $merchantUser = $this->models->user->getById($userId);

        $subscriptionPackage = $this->models->subscriptionPackage->getById($merchant['planid']);

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
        $this->services->emailNotification = new \App\Services\EmailNotification();
        $this->services->emailNotification->sendMail(array('email' => $merchantUser['merchant_business_email'], 'name' => $merchantUser['first_name'] . ' ' . $merchantUser['last_name']), $this->app->config('merchant_subscription_change_subject'), $this->core->view->make('email/merchant-subscription-change.php', array('user' => $merchantUser, 'app' => $app, 'subscription' => $subscriptionPackage), false));

        $merchant = array();
        $merchant['user_id'] = $userId;
        $merchant['subscription_package_id'] = $subscription->plan->id;
        $this->models->merchantDetails->save($merchant);

        $account = $this->services->stripe->getAccount($merchantUser['merchant_stripe_account_id']);
        $account->transfer_schedule = array('delay_days' => $subscriptionPackage['payment_threshold'], 'interval' => 'daily');
        $account->save();

        $data = array();

        $data['message'] = "Subscription plan changed successfully.";
        $data['subscription']['plan_name'] = $subscriptionPackage['name'];

        echo $response = $this->core->response->json($data, FALSE);
    }

    /**
     * @api {post} /api/web/admin/merchant/ Update merchant account.
     * @apiName UpdateMerchant
     * @apiGroup /api/web/admin/merchant
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
    public function updateMerchant() {

        $logData = array();
        $logData['post'] = $this->jsonRequest;

        $this->userLogger->log('info', 'merchant requested to update account info.', $logData['post']['user_id'], $logData);

        $request = $this->jsonRequest;

        $rules = [
            'alpha' => [['legal_entity_first_name'], ['legal_entity_last_name']],
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
            'tax_enabled'
        ));
        $v->rules($rules);

        if ($v->validate()) {

            $merchant = $v->sanatized();
            $merchant['user_id'] = $request['user_id'];
            $merchantUser = $this->models->merchantDetails->getByUserId($merchant['user_id']);

            if (!empty($merchant['legal_entity_additional_owners'])) {

                $nextIndex = $this->models->merchantDetailsAdditionalOwners->getNextIndex($merchant['user_id']);
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

                $activeMedia = $this->models->media->getAllByUserId($merchant['user_id']);
                if (!empty($activeMedia) && !empty($merchant['tax_enabled']) &&  $merchantUser['tax_enabled'] != $merchant['tax_enabled']) {
                    throw new BaseException('To enable/disable tax, please delete your prodnct from product manager and repost again after enable/disable tax.');
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
                        $_legalEntityAdditionalOwners['user_id'] = $merchant['user_id'];
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

                $data = array();

                $data['merchant'] = "Merchant account updated successfully.";

                echo $response = $this->core->response->json($data, FALSE);

                $logData = array();
                $logData['response'] = $response;

                $this->userLogger->log('info', 'Response returned to user', $merchant['user_id'], $logData);
            } else {

                $this->userLogger->log('info', 'Nothing to change.', $merchant['user_id']);

                throw new BaseException('Nothing to change.');
            }
        } else {

            $logData = array();
            $logData['errors'] = $v->errors();

            $this->userLogger->log('error', 'Data validation failed', $merchant['user_id'], $logData);

            throw new ValidationException($v->errors());
        }
    }
    
    public function ordersCheck($id) {

        $suborders = $this->models->orderSuborder->notCompleted($id);

        return $suborders;
    }

}
