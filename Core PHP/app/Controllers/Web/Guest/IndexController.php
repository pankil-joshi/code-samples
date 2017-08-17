<?php

namespace App\Controllers\Web\Guest;

use Quill\Factories\CoreFactory;
use Quill\Factories\ServiceFactory;
use Quill\Factories\ModelFactory;

class IndexController extends \App\Controllers\Web\PublicBaseController {

    function __construct($app = NULL) {

        parent::__construct($app);

        $this->core = CoreFactory::boot(array('View'));
        $this->models = ModelFactory::boot(array('User', 'UserAddress'));
        $this->services = ServiceFactory::boot(array('Jwt'));
    }

    public function index() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit-form'])) {

            $rules = [
                'required' => [['first_name'], ['last_name'], ['email'], ['mobile_number'], ['country'], ['mobile_number_prefix'],
                    ['address_first_name'], ['address_last_name'], ['address_line_1'], ['address_city'], ['address_zip_code'],
                    ['address_country'], ['address_mobile_number_prefix'], ['address_mobile_number']
                ],
                'regex' => [['first_name', "@^([a-zA-Z.\-/' ])+$@"], ['last_name', "@^([a-zA-Z.\-/' ])+$@"]],
                'email' => [['email']],
                'numeric' => [['mobile_number']]
            ];

            $v = new \Quill\Validator($_POST, array(
                'first_name', 'last_name', 'email', 'mobile_number', 'title', 'country', 'mobile_number_prefix', 'timezone', 'terms_accepted',
                'address_first_name', 'address_last_name', 'address_line_1', 'address_line_2', 'address_city', 'address_zip_code', 'address_state', 'address_country', 'address_mobile_number_prefix', 'address_mobile_number'
            ));
            $v->rules($rules);

            if ($v->validate()) {

                $user = $v->sanatized();
                $_SESSION['guest_user'] = $user;
                $checkUser = $this->models->user->getByEmail($user['email']);
                if ($checkUser && $checkUser['is_active'] == 1) {
                    unset($_SESSION['guest_user']);
                    $message['type'] = 'error';
                    $message['errors'][] = ['You already have an account, please <a href="' . $this->app->config('base_url') . 'account/login?redirect=checkout">login</a>.'];
                } else {

                    if ($user['terms_accepted']) {
                        $user['id'] = (!empty($checkUser['id'])) ? $checkUser['id'] : '';

                        $address['first_name'] = $user['address_first_name'];
                        $address['last_name'] = $user['address_last_name'];
                        $address['line_1'] = $user['address_line_1'];
                        $address['line_2'] = $user['address_line_2'];
                        $address['city'] = $user['address_city'];
                        $address['zip_code'] = $user['address_zip_code'];
                        $address['state'] = $user['address_state'];
                        $address['country'] = $user['address_country'];
                        $address['mobile_number_prefix'] = $user['address_mobile_number_prefix'];
                        $address['mobile_number'] = $user['address_mobile_number'];

                        foreach ($user as $key => $value) {

                            if (strpos($key, 'address_') !== false) {

                                unset($user[$key]);
                            }
                        }

                        if ($guestUser = $this->models->user->save($user)) {

                            unset($_SESSION['guest_user']);

                            $address['user_id'] = $guestUser['id'];

                            $this->models->userAddress->save($address);

                            $user['token'] = $this->services->jwt->genrateUserToken($guestUser['id'], $this->app->config('instagram_connect_url'), $this->app->config('base_api_mobile_url'), 1);

                            setcookie('token', $user['token'], time() + 86400, $this->app->config('path'), $this->app->config('domain'), false, true);
                            $_SESSION['guest'] = 1;
                            $this->app->slim->redirect($this->app->config('base_url') . 'checkout');
                        }
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
        $data['countries'] = load_config_one('countries');
        $this->core->view->make('web/guest/index.php', $data);
    }

}
