<?php

namespace App\Controllers\Web\Merchant;

use Quill\Factories\CoreFactory;
//use Quill\Factories\ServiceFactory;
use Quill\Factories\ModelFactory;

class SettingController extends \App\Controllers\Web\Merchant\MerchantBaseController {

    function __construct($app = NULL) {

        parent::__construct($app);

        $this->core = CoreFactory::boot(array('View'));
        $this->models = ModelFactory::boot(array('User', 'UserPostageOption'));
    }

    public function account() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_user'])) {

            $rules = [
                'regex' => [['first_name', "@^([a-zA-Z.\-/' ])+$@"], ['last_name', "@^([a-zA-Z.\-/' ])+$@"]],
                'email' => [['email']],
                'numeric' => [['mobile_number']]
            ];

            $v = new \Quill\Validator($_POST, array('first_name', 'last_name', 'email', 'mobile_number', 'title', 'date_of_birth', 'country', 'accept_promotional_mails', 'accept_thirdparty_mails', 'mobile_number_prefix'));
            $v->rules($rules);

            if ($v->validate()) {

                $user = $v->sanatized();

                $user['accept_promotional_mails'] = (!empty($user['accept_promotional_mails'])) ? $user['accept_promotional_mails'] : 0;
                $user['accept_thirdparty_mails'] = (!empty($user['accept_thirdparty_mails'])) ? $user['accept_thirdparty_mails'] : 0;

                $user['id'] = $this->app->user['id'];

                if (isset($user['date_of_birth'])) {

                    $user['date_of_birth'] = date('Y-m-d', strtotime($user['date_of_birth']));
                }

                if ($user = $this->models->user->save($user)) {

                    $message['type'] = 'success';
                    $message['text'] = 'Profile details updated successfully.';
                }
            } else {

                $message['type'] = 'error';
                $message['errors'] = $v->errors();
            }

            $data['message'] = $message;
        }

        $data['title'] = 'Account Settings';
        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['countries'] = load_config_one('countries');

        $data['user'] = $this->models->user->getById($this->app->user['id']);

        $this->core->view->make('web/merchant/setting/account.php', $data);
    }

    public function postage() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['countries'] = load_config_one('countries');
        $data['currencies'] = load_config_one('currencies');
        $data['continents'] = load_config_one('continents');
        $data['title'] = 'Postage Templates';
        $data['postage_options'] = $this->models->userPostageOption->getAllByUserId($this->app->user['id']);

        $this->core->view->make('web/merchant/setting/postage.php', $data);
    }

    public function postageOptionListView() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['countries'] = load_config_one('countries');
        $data['currencies'] = load_config_one('currencies');
        $data['continents'] = load_config_one('continents');
        $data['postage_options'] = $this->models->userPostageOption->getAllByUserId($this->app->user['id']);

        $this->core->view->make('web/merchant/components/postage-option-list.php', $data);
    }

}
