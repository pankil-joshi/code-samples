<?php

namespace App\Controllers\Api\Mobile;

use App\Controllers\Api\Mobile\MobileBaseController as MobileBaseController;
use Quill\Factories\CoreFactory;
use Quill\Factories\ServiceFactory;
use Quill\Factories\ModelFactory;
use Quill\Exceptions\BaseException;
use Quill\Exceptions\ValidationException;

class UserController extends MobileBaseController {

    /**
     * Constructor
     * @param object contains app core.
     */
    function __construct($app = NULL) {

        // Call to parent class constructer.
        parent::__construct($app);

        // Instantiate models.
        $this->models = ModelFactory::boot(array('User', 'Media', 'Tag', 'Device', 'UserAddress', 'Mention', 'UserPostageOption', 'MerchantDetails', 'UserTaxTemplate', 'MessageRecipient', 'OrderSuborder'));

        // Instantiate core classes.
        $this->core = CoreFactory::boot(array('Response', 'Http', 'View'));

        // Instantiate services.
        $this->services = ServiceFactory::boot(array('Stripe', 'Jwt', 'EmailNotification'));

        $this->app->config(load_config_one('emailTemplates'));
    }

    /**
     * @throws Exception
     */
    public function saveUser() {

        $logData = array();
        $logData['post'] = $this->jsonRequest;

        $this->userLogger->log('info', 'User requested to save account info.', $this->app->user['id'], $logData);

        $request = $this->jsonRequest;

        $rules = [
            'alpha' => [['title'], ['country']],
            'email' => [['email']],
            'numeric' => [['mobile_number']],
            'regex' => [['first_name', "@^([a-zA-Z.\-/' ])+$@"], ['last_name', "@^([a-zA-Z.\-/' ])+$@"]]
        ];


        $v = new \Quill\Validator($request, array(
            'first_name',
            'last_name',
            'email',
            'mobile_number',
            'title',
            'date_of_birth',
            'terms_accepted',
            'country',
            'mobile_number_prefix',
            'notify_push_new_message',
            'notify_push_customer_order_status_change',
            'notify_push_merchant_new_order',
            'notify_push_merchant_order_status_change',
            'notify_push_merchant_low_stock',
            'notify_email_new_message',
            'notify_email_customer_order_status_change',
            'notify_email_customer_order_confirmation',
            'notify_email_merchant_new_order',
            'notify_email_merchant_order_status_change',
            'notify_email_merchant_low_stock',
            'accept_promotional_mails',
            'accept_merchant_promotional_mails',
            'accept_thirdparty_mails',
            'age',
            'timezone'
        ));
        $v->rules($rules);
        $v->rule('check_duplicate_user_email', 'email', 'users', 'id', $this->app->user['id'])->message('Email already exists.')->label('Email');
        if ($v->validate()) {

            $user = $v->sanatized();

            $user['id'] = $this->app->user['id'];

            if (isset($user['terms_accepted']) && $user['terms_accepted'] == 1) {

                $user['is_active'] = 1;
                $user['terms_accepted_ip'] = $_SERVER['REMOTE_ADDR'];
                $user['terms_accepted_datetime'] = gmdate('Y-m-d H:i:s');

                $app = array(
                    'base_assets_url' => $this->app->config('base_assets_url'),
                    'app_title' => $this->app->config('app_title'),
                    'master_hashtag' => $this->app->config('master_hashtag'),
                    'feedback_email' => $this->app->config('feedback_email'),
                    'support_email' => $this->app->config('support_email'),
                    'instagram_account_url' => $this->app->config('instagram_account_url'),
                    'twitter_account_url' => $this->app->config('twitter_account_url'),
                    'base_url' => $this->app->config('base_url'),
                    'support_phone_uk' => $this->app->config('support_phone_uk'),
                    'support_phone_int' => $this->app->config('support_phone_int')
                );

                $this->services->emailNotification->sendMail(array('email' => $user['email'], 'name' => $user['first_name'] . ' ' . $user['last_name']), $this->app->config('customer_welcome_subject'), $this->core->view->make('email/customer-welcome.php', array('user' => $user, 'app' => $app), false));
            }

            if (isset($user['date_of_birth'])) {

                $user['date_of_birth'] = date('Y-m-d', strtotime($user['date_of_birth']));
            }

            if ($user = $this->models->user->save($user)) {

                $data['user'] = $user;

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

    public function getUser($id = null) {

        $this->userLogger->log('info', 'User requested to get account info.', $this->app->user['id']);

        $request = $this->jsonRequest;

        $id = (!empty($id)) ? $id : $this->app->user['id'];

        $user = $this->models->user->getById($id);
        $user['unread_messages'] = $this->models->messageRecipient->getUnreadReplies($id);

        unset($user['instagram_access_token']);

        if ($user) {

            $data['user'] = $user;

            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
        } else {

            $this->userLogger->log('error', 'Resource not found', $this->app->user['id']);

            throw new BaseException('Resource not found');
        }
    }

    public function saveAddress($id = NULL) {

        $logData = array();
        $logData['post'] = $this->jsonRequest;

        $this->userLogger->log('info', 'User requested to save address.', $this->app->user['id'], $logData);

        $request = $this->jsonRequest;

        $rules = [
            'required' => [['line_1'], ['city'], ['zip_code'], ['country']],
            'optional' => [['line_2'], ['line_3'], ['is_delivery_address']],
//            'regex' => [['city'], ['state'], ['country']],
            'numeric' => [['mobile_number']]
        ];

        $v = new \Quill\Validator($request, array('first_name', 'last_name', 'mobile_number', 'line_1', 'line_2', 'zip_code', 'state', 'country', 'is_delivery_address', 'city', 'mobile_number_prefix'));
        $v->rules($rules);

        if ($v->validate()) {

            $address = $v->sanatized();
            $address['id'] = $id;
            $address['user_id'] = $this->app->user['id'];

            if (isset($address['is_delivery_address']) && $address['is_delivery_address'] == 1) {

                $defaultAddress = array();
                $defaultAddress['user_id'] = $this->app->user['id'];
                $defaultAddress['is_delivery_address'] = 0;
                $this->models->userAddress->unsetDefault($defaultAddress);
            }

            $addressId = $this->models->userAddress->save($address);

            if ($addressId) {

                $address['id'] = ($id) ? $id : $addressId;
                $data['address'] = $address;

                echo $response = $this->core->response->json($data, FALSE);

                $logData = array();
                $logData['response'] = $response;

                $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
            }
        } else {

            throw new ValidationException($v->errors());
        }
    }

    public function deactivateCustomer() {

        $this->userLogger->log('info', 'User requested to deactivate customer account.', $this->app->user['id']);

        $user = $this->models->user->getById($this->app->user['id']);

        if ($user['is_merchant'] && $user['merchant_deactivate'] == 0) {

            if ($this->ordersCheck()) {

                throw new BaseException('You are currently unable to deactivate your account as you have open orders from your customers.');
            }

            try {

                $this->services->stripe->cancelSubscription($user['merchant_stripe_subscription_id']);
            } catch (\Exception $e) {

                throw new BaseException('Subscription cancellation failed: ' . $e->getMessage());
            }
        }

        $user = array();
        $user['id'] = $this->app->user['id'];
        $user['customer_deactivate'] = 1;
        $user['merchant_deactivate'] = 1;
        $user = $this->models->user->save($user);

        if ($user) {

            $data['message'] = 'Account deactivated successfully.';
            $data['user_type'] = 'customer';

            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
        }
    }

    public function ordersCheck() {

        $suborders = $this->models->orderSuborder->notCompleted($this->app->user['id']);

        return $suborders;
    }

    public function deactivateMerchant() {

        $this->userLogger->log('info', 'User requested to deactivate merchant account.', $this->app->user['id']);

        $user = $this->models->user->getById($this->app->user['id']);

        if ($user['is_merchant']) {

            if ($this->ordersCheck()) {

                throw new BaseException('You are currently unable to deactivate your account as you have open orders from your customers.');
            }

            try {

                $this->services->stripe->cancelSubscription($user['merchant_stripe_subscription_id']);
            } catch (\Exception $e) {

                throw new BaseException('Subscription cancellation failed: ' . $e->getMessage());
            }
        }

        $user = array();
        $user['id'] = $this->app->user['id'];
        $user['merchant_deactivate'] = 1;
        $user = $this->models->user->save($user);

        if ($user) {

            $data['message'] = 'Merchant deactivated successfully.';

            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
        }
    }

    public function setDefaultAddress($id) {

        $logData = array();
        $logData['post'] = $this->jsonRequest;

        $this->userLogger->log('info', 'User requested to set default address.', $this->app->user['id'], $logData);
        $address = array();
        $address['user_id'] = $this->app->user['id'];
        $address['is_delivery_address'] = 0;
        $addressId = $this->models->userAddress->unsetDefault($address);

        $address = array();
        $address['id'] = $id;
        $address['user_id'] = $this->app->user['id'];
        $address['is_delivery_address'] = 1;
        $addressId = $this->models->userAddress->save($address);
        if ($addressId) {

            $data['address'] = $address;

            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
        }
    }

    public function deleteAddress($id) {

        $this->userLogger->log('info', 'User requested to delete address with id: ' . $id, $this->app->user['id']);

        $address = $this->models->userAddress->remove($id, $this->app->user['id']);

        if ($address) {

            $data['message'] = 'Address deleted successully!';

            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
        } else {

            $this->userLogger->log('error', 'Resource not found', $this->app->user['id']);

            throw new BaseException('Resource not found');
        }
    }

    public function getAddress($id = NULL) {

        $logData = array();
        $logData['post'] = $this->jsonRequest;

        $this->userLogger->log('info', 'User requested to get address with id: ' . $id, $this->app->user['id'], $logData);

        $request = $this->jsonRequest;

        $address = $this->models->userAddress->getById($id, $this->app->user['id']);

        if ($address) {

            $data['address'] = $address;

            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
        } else {

            $this->userLogger->log('error', 'Resource not found', $this->app->user['id']);

            throw new BaseException('Resource not found');
        }
    }

    public function listAddresses() {

        $logData = array();
        $logData['post'] = $this->jsonRequest;

        $this->userLogger->log('info', 'User requested to get address list.', $this->app->user['id'], $logData);

        $request = $this->jsonRequest;

        $address = $this->models->userAddress->getAllByUserId($this->app->user['id']);

        if ($address) {

            $data['addresses'] = $address;

            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
        } else {

            $this->userLogger->log('error', 'Resource not found', $this->app->user['id']);

            throw new BaseException('Resource not found');
        }
    }

    public function instagramConnect() {
        $user['token'] = $this->services->jwt->genrateUserToken(2, $this->app->config('instagram_connect_url'), $this->app->config('base_api_mobile_url'));

        setcookie('token', $user['token'], time() + 86400, $this->app->config('path'), $this->app->config('domain'), false, true);


//        if ($this->request->get('response') != 'true') {
//
//            $apiData['client_id'] = $this->app->config('instagram_client_id');
//            $apiData['client_secret'] = $this->app->config('instagram_client_secret');
//            $apiData['grant_type'] = $this->app->config('instagram_grant_type');
//
//            $params = array();
//            $params['uuid'] = $this->request->get('uuid');
//
//            $apiData['redirect_uri'] = $this->app->config('instagram_connect_url') . '?' . http_build_query($params);
//
//            if ($this->request->get('code') !== NULL) {
//
//                $logData = array();
//
//                if ($this->app->config('logging_strict') == FALSE) {
//
//                    $logData['code'] = $this->request->get('code');
//                }
//
//                $this->instagramLogger->log('info', 'Authorization code recieved.', '', $logData);
//
//                $apiData['code'] = $this->request->get('code');
//
//                $logData = array();
//
//                if ($this->app->config('logging_strict') == FALSE) {
//
//                    $logData['posted'] = $apiData;
//                }
//
//                $this->instagramLogger->log('info', 'Instagram access token request.', '', $logData);
//
//                $json = $this->core->http->post($this->app->config('instaram_token_url'), $apiData);
//
//                $instagramData = json_decode($json, TRUE);
//
//                if (empty($instagramData['user'])) {
//
//                    $data = array('error' => "We can not process your request at this time, please try again later.");
//
//                    $this->slim->redirect($this->app->config('instagram_connect_url') . '?response=true#' . http_build_query($data));
//                    exit();
//                }
//
//                $instagramUser = $instagramData['user'];
//
//                $logData = array();
//
//                if ($this->app->config('logging_strict') == FALSE) {
//
//                    $logData['access_token'] = $instagramData['access_token'];
//                    $logData['recieved'] = $instagramData;
//                }
//
//                $this->instagramLogger->log('info', 'Instagram get user info request.', '', $logData);
//
//                $json = $this->core->http->get($this->app->config('instagram_api_url_v1') . 'users/self/?access_token=' . $instagramData['access_token']);
//
//                $instagramUserData = json_decode($json, TRUE);
//
//                if (!isset($instagramUserData['code'])) {
//
//                    $instagramUserData = $instagramUserData['data'];
//
//                    if (!empty($instagramData['access_token'])) {
//
//                        $user = $this->models->user->getByInstagramId($instagramUser['id']);
//
//                        if ($user['customer_deactivate']) {
//
//                            $data = array('error' => "Your account has been deactivated, please contact tagzie at {$this->app->config('support_email')} for more info.");
//
//                            $this->slim->redirect($this->app->config('instagram_connect_url') . '?response=true#' . http_build_query($data));
//                            exit();
//                        }
//
//                        if (!$user) {
//
//                            $userData['instagram_user_id'] = $instagramUserData['id'];
//                            $userData['instagram_username'] = $instagramUserData['username'];
//                            $userData['first_name'] = $instagramUserData['full_name'];
//                            $userData['instagram_access_token'] = $instagramData['access_token'];
//                            $userData['instagram_profile_picture'] = $instagramUserData['profile_picture'];
//                            $userData['instagram_followed_by'] = $instagramUserData['counts']['followed_by'];
//                            $userData['is_active'] = 0;
//
//                            $logData = array();
//                            $logData['user'] = $userData;
//
//                            $this->userLogger->log('info', 'Create user with active state 0.', 'global', $logData);
//
//                            $user = $this->models->user->save($userData);
//
//                            $this->models->tag->save(array('user_id' => $user['id'], 'text' => 'tagzieEnabled'));
//                            $this->models->mention->save(array('user_id' => $user['id'], 'text' => 'tagzieHQ'));
//                            
//                            $device['uuid'] = $this->request->get('uuid');
//                            $device['user_id'] = $user['id'];
//                            $device['logout'] = 0;
//
//                            if (!empty($device['uuid'])) {
//
//                                $this->userLogger->log('info', 'Link device with current user.', $user['id'], array('uuid' => $device['uuid']));
//
//                                $this->models->device->saveByUuid($device);
//                            }
//
//                            $user = $this->models->user->getByInstagramId($instagramUser['id']);
//
//                            $this->userLogger->log('info', 'Genrate token.', $user['id']);
//
//                            $user['token'] = $this->services->jwt->genrateUserToken($user['id'], $this->app->config('instagram_connect_url'), $this->app->config('base_api_mobile_url'));
//
//                            unset($user['instagram_access_token']);
//
//                            setcookie('token', $user['token'], time() + 86400, $this->app->config('path'), $this->app->config('domain'), false, true);
//                            $data = array();
//                            $data['user'] = $user;
//
//                            $data = array('success' => 1);
//
//                            $this->slim->redirect($this->app->config('instagram_connect_url') . '?response=true#' . http_build_query($data));
//
//                            $logData = array();
//                            $logData['response'] = $response;
//
//                            $this->userLogger->log('info', 'Response returned to user', $user['id'], $logData);
//                        } else {
//
//                            $userData['id'] = $user['id'];
//
//                            $userData['instagram_username'] = $instagramUserData['username'];
//                            $userData['instagram_access_token'] = $instagramData['access_token'];
//                            $userData['instagram_profile_picture'] = $instagramUserData['profile_picture'];
//                            $userData['instagram_followed_by'] = $instagramUserData['counts']['followed_by'];
//
//                            $logData = array();
//                            $logData['user'] = $userData;
//
//                            $this->userLogger->log('info', 'Update user.', $user['id'], $logData);
//
//                            $user = $this->models->user->save($userData);
//
//                            $device['uuid'] = $this->request->get('uuid');
//                            $device['user_id'] = $user['id'];
//                            $device['logout'] = 0;
//
//                            if (!empty($device['uuid'])) {
//
//                                $this->userLogger->log('info', 'Link device with current user.', $user['id'], array('uuid' => $device['uuid']));
//
//                                $this->models->device->saveByUuid($device);
//                            }
//
//
//                            $user = $this->models->user->getByInstagramId($instagramUser['id']);
//
//                            $this->userLogger->log('info', 'Genrate token.', $user['id']);
//
//                            $user['token'] = $this->services->jwt->genrateUserToken($user['id'], $this->app->config('instagram_connect_url'), $this->app->config('base_api_mobile_url'));
//
//                            unset($user['instagram_access_token']);
//                            setcookie('token', $user['token'], time() + 86400, $this->app->config('path'), $this->app->config('domain'), false, true);
//                            $data = array();
//                            $data['user'] = $user;
//
//                            $data = array('success' => 1);
//                            $this->userLogger->log('info', 'Redirect url.', $user['id'], array('redirect_url' => $this->app->config('instagram_connect_url') . '?response=true#' . http_build_query($data)));
//                            $this->slim->redirect($this->app->config('instagram_connect_url') . '?response=true#' . http_build_query($data));
//                        }
//                    }
//                } else {
//
//                    $logData = array();
//                    $logData['response'] = $instagramUserData;
//
//                    $this->instagramLogger->log('error', 'Instagram API error.', $instagramUserData);
//
//                    $data = array('error' => 'Instagram API error: ' . $instagramUserData);
//                    $this->instagramLogger->log('info', 'Redirect url.', array('redirect_url' => $this->app->config('instagram_connect_url') . '?response=true#' . http_build_query($data)));
//                    $this->slim->redirect($this->app->config('instagram_connect_url') . '?response=true#' . http_build_query($data));
//                }
//            } else {
//
//                $this->instagramLogger->log('info', 'Instagram authorization request.');
//
//                $this->slim->redirect($this->app->config('instaram_authorize_url') . "?client_id=" . $apiData['client_id'] . "&redirect_uri=" . $apiData['redirect_uri'] . "&response_type=code&scope=" . $this->app->config('instagram_token_scope'));
//            }
//        }
    }

    public function saveTag() {

        $logData = array();
        $logData['post'] = $this->jsonRequest;

        $this->userLogger->log('info', 'User requested to create a tag.', $this->app->user['id'], $logData);

        $request = $this->jsonRequest;

        $rules = [
            'required' => [['text']]
        ];

        $v = new \Quill\Validator($request, array('text'));
        $v->rule('check_duplicate', 'text', 'user_tags', '', '', 'user_id', $this->app->user['id'])->message('This hashtag already exists in your Tag Vault, please select it from the list.')->label('Tag');
        $v->rules($rules);

        if ($v->validate()) {

            $tag = $v->sanatized();
            $tag['user_id'] = $this->app->user['id'];

            if ($this->models->tag->save($tag)) {

                $data['tag'] = $tag;

                echo $response = $this->core->response->json($data, FALSE);

                $logData = array();
                $logData['response'] = $response;

                $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
            }
        } else {

            $logData = array();
            $logData['errors'] = $v->errors();

            $this->userLogger->log('error', 'Data validation failed', $this->app->user['id'], $logData);

            throw new ValidationException($v->errors());
        }
    }

    public function deleteTag($id) {

        $this->userLogger->log('info', 'User requested to delete tag with id: ' . $id, $this->app->user['id']);

        $tag = $this->models->tag->remove($id, $this->app->user['id']);

        if ($tag) {

            $data['message'] = 'Tag deleted successully!';

            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
        } else {

            $this->userLogger->log('error', 'Resource not found', $this->app->user['id']);

            throw new BaseException('Resource not found');
        }
    }

    public function listTags() {

        $this->userLogger->log('info', 'User requested to get list of tags.', $this->app->user['id']);

        $request = $this->jsonRequest;

        $tag = $this->models->tag->getAllByUserId($this->app->user['id']);

        if ($tag) {

            $data['tag'] = $tag;

            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
        } else {

            $this->userLogger->log('error', 'Resource not found', $this->app->user['id']);

            throw new BaseException('Resource not found');
        }
    }

    public function saveMention() {

        $logData = array();
        $logData['post'] = $this->jsonRequest;

        $this->userLogger->log('info', 'User requested to create a mention.', $this->app->user['id'], $logData);

        $request = $this->jsonRequest;

        $rules = [
            'required' => [['text']]
        ];

        $v = new \Quill\Validator($request, array('text'));

        $v->rules($rules);
        $v->rule('check_duplicate', 'text', 'user_mentions', '', '', 'user_id', $this->app->user['id'])->message('This mention already exists in your Tag Vault, please select it from the list.')->label('Mention');
        if ($v->validate()) {

            $mention = $v->sanatized();
            $mention['user_id'] = $this->app->user['id'];

            if ($this->models->mention->save($mention)) {

                $data['mention'] = $mention;

                echo $response = $this->core->response->json($data, FALSE);

                $logData = array();
                $logData['response'] = $response;

                $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
            }
        } else {

            $logData = array();
            $logData['errors'] = $v->errors();

            $this->userLogger->log('error', 'Data validation failed', $this->app->user['id'], $logData);

            throw new ValidationException($v->errors());
        }
    }

    public function deleteMention($id) {

        $this->userLogger->log('info', 'User requested to delete mention with id: ' . $id, $this->app->user['id']);

        $mention = $this->models->mention->remove($id, $this->app->user['id']);

        if ($mention) {

            $data['message'] = 'Mention deleted successully!';

            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
        } else {

            $this->userLogger->log('error', 'Resource not found', $this->app->user['id']);

            throw new BaseException('Resource not found');
        }
    }

    public function listMentions() {

        $this->userLogger->log('info', 'User requested to get list of mentions.', $this->app->user['id']);

        $request = $this->jsonRequest;

        $mention = $this->models->mention->getAllByUserId($this->app->user['id']);

        if ($mention) {

            $data['mention'] = $mention;

            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
        } else {

            $this->userLogger->log('error', 'Resource not found', $this->app->user['id']);

            throw new BaseException('Resource not found');
        }
    }

    public function saveDevice() {

        $logData = array();
        $logData['post'] = $this->jsonRequest;

        $this->userLogger->log('info', 'Request to save device details.', $this->app->user['id'], $logData);

        $request = $this->jsonRequest;

        $rules = [
//            'required' => [['platform'], ['version'], ['model'], ['manufacturer'], ['notification_id']],
            'optional' => [['uuid']]
        ];

        $v = new \Quill\Validator($request, array('platform', 'version', 'uuid', 'model', 'manufacturer', 'notification_id', 'logout'));
        $v->rules($rules);

        if ($v->validate()) {

            $device = $v->sanatized();

            if ($this->models->device->saveByUuid($device)) {

                $data['device'] = $device;

                echo $response = $this->core->response->json($data, FALSE);

                $logData = array();
                $logData['response'] = $response;

                $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
            }
        } else {

            $this->userLogger->log('error', 'Resource not found', $this->app->user['id']);

            throw new ValidationException($v->errors());
        }
    }

    public function saveTaxTemplate($id = NULL) {

        $taxTemplate = $this->jsonRequest;
        $taxTemplate['id'] = $id;
        $taxTemplate['user_id'] = $this->app->user['id'];

        $taxTemplate = $this->models->userTaxTemplate->save($taxTemplate, $this->app->user['id']);

        if ($taxTemplate) {

            $data['postageOption'] = $taxTemplate;

            echo $response = $this->core->response->json($data, FALSE);
        }
    }

    public function deleteTaxTemplate($id) {

        $this->userLogger->log('info', 'User requested to delete tax template with id: ' . $id, $this->app->user['id']);

        $taxTemplate = $this->models->userTaxTemplate->remove($id, $this->app->user['id']);

        if ($taxTemplate) {

            $data['message'] = 'Tax template deleted successully!';

            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
        } else {

            $this->userLogger->log('error', 'Resource not found', $this->app->user['id']);

            throw new BaseException('Resource not found');
        }
    }

    public function listTaxTemplates() {

        $data['taxTemplates'] = $this->models->userTaxTemplate->getAllByUserId($this->app->user['id']);

        echo $response = $this->core->response->json($data, FALSE);
    }

    public function getTaxTemplate($id) {

        $taxTemplate = $this->models->userTaxTemplate->getById($id);

        $data['taxTemplate'] = $taxTemplate;

        echo $response = $this->core->response->json($data, FALSE);
    }

    public function savePostageOption($id = NULL) {

        $postageOption = $this->jsonRequest;

        $postageOption['code'] = snake_case($postageOption['label']);

        $v = new \Quill\Validator($postageOption, array('code', 'label', 'rate', 'duration', 'geography'));

        if (!empty($id)) {

            $v->rule('check_duplicate', 'code', 'user_postage_options', 'id', $id, 'user_id', $this->app->user['id'])->message('Postage option already exists.')->label('Label');
        } else {

            $v->rule('check_duplicate', 'code', 'user_postage_options', '', '', 'user_id', $this->app->user['id'])->message('Postage option already exists.')->label('Label');
        }

        if ($v->validate()) {

            $postageOption = $v->sanatized();

            $postageOption['id'] = $id;
            $postageOption['user_id'] = $this->app->user['id'];
            $merchant = $this->models->merchantDetails->getByUserId($this->app->user['id']);
            $postageOption['rate_currency'] = $merchant['business_currency'];

            $postageOption = $this->models->userPostageOption->save($postageOption, $this->app->user['id']);

            if ($postageOption) {

                $data['postageOption'] = $postageOption;

                echo $response = $this->core->response->json($data, FALSE);
            }
        } else {

            $this->userLogger->log('error', 'Resource not found', $this->app->user['id']);

            throw new ValidationException($v->errors());
        }
    }

    public function deletePostageOption($id) {

        $this->userLogger->log('info', 'User requested to delete postage option with id: ' . $id, $this->app->user['id']);

        $postageOption = $this->models->userPostageOption->remove($id, $this->app->user['id']);

        if ($postageOption) {

            $data['message'] = 'Postage option deleted successully!';

            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
        } else {

            $this->userLogger->log('error', 'Resource not found', $this->app->user['id']);

            throw new BaseException('Resource not found');
        }
    }

    public function listPostageOptions() {

        $data['postageOptions'] = $this->models->userPostageOption->getAllByUserId($this->app->user['id']);

        echo $response = $this->core->response->json($data, FALSE);
    }

    public function getPostageOption($id) {

        $postageOptions = $this->models->userPostageOption->getById($id);

        $data['postageOptions'] = $postageOptions;

        echo $response = $this->core->response->json($data, FALSE);
    }

}
