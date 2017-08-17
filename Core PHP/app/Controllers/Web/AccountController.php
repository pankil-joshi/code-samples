<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controllers\Web;

use Quill\Factories\CoreFactory;
use Quill\Factories\ServiceFactory;
use Quill\Factories\ModelFactory;

class AccountController extends \App\Controllers\Web\AccountBaseController {

    function __construct($app = NULL) {

        parent::__construct($app);
        $this->models = ModelFactory::boot(array('User', 'AdminUser'));
        $this->core = CoreFactory::boot(array('View'));
        // Instantiate services.
        $this->services = ServiceFactory::boot(array('EmailNotification'));

        $this->app->config(load_config_one('emailTemplates'));
    }

    public function customerSignup() {

        if ($this->app->user['is_active'] == 1) {

            $this->app->slim->redirect($this->app->config('base_url') . 'account/customer/');
        } elseif (empty($this->app->user['instagram_username'])) {

            $this->app->slim->redirect($this->app->config('base_url') . 'account/login/');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit-form'])) {

            $rules = [
                'required' => [['first_name'], ['last_name'], ['email'], ['mobile_number'], ['title'], ['country'], ['mobile_number_prefix'], ['terms_accepted']],
                'alpha' => [['first_name'], ['last_name'], ['title'], ['country']],
                'email' => [['email']],
                'numeric' => [['mobile_number'], ['age']],
                'accepted' => [['terms_accepted']]
            ];

            $v = new \Quill\Validator($_POST, array('first_name', 'last_name', 'email', 'mobile_number', 'title', 'age', 'country', 'mobile_number_prefix', 'terms_accepted', 'timezone'));
            $v->rule('check_duplicate_user_email', 'email', 'users', 'id', $this->app->user['id'])->message('Email already exists.')->label('Email');
            $v->rules($rules);

            if ($v->validate()) {

                $user = $v->sanatized();

                $user['id'] = $this->app->user['id'];

                if (isset($user['date_of_birth'])) {

                    $user['date_of_birth'] = date('Y-m-d', strtotime($user['date_of_birth']));
                }
                echo 'test';
                if ($user['terms_accepted']) {

                    $user['is_active'] = 1;
                    $user['terms_accepted_ip'] = $_SERVER['REMOTE_ADDR'];
                    $user['terms_accepted_datetime'] = gmdate('Y-m-d H:i:s');
                    if ($user = $this->models->user->save($user)) {

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

                        $redirect = (!empty($_GET['redirect'])) ? $_GET['redirect'] : 'account/customer';
                        $this->app->slim->redirect($this->app->config('base_url') . $redirect);
                    }
                }
            } else {

                $message['type'] = 'error';
                $message['errors'] = $v->errors();
            }

            $data['message'] = $message;
        }


        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $config = load_config_one('stripe');
        $data['stripe_publishable_key'] = $config['stripe_publishable_key'];
        $data['countries'] = load_config_one('countries');
        $data['user'] = $this->app->user;
        $data['meta'] = array('title' => 'Register | Tagzie.com');
        $this->core->view->make('web/account/register/customer.php', $data);
    }

}
