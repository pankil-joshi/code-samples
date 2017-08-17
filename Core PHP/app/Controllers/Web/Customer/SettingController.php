<?php

namespace App\Controllers\Web\Customer;

use Quill\Factories\CoreFactory;
use Quill\Factories\ModelFactory;

class SettingController extends \App\Controllers\Web\Customer\CustomerBaseController {

    function __construct($app = NULL) {

        parent::__construct($app);

        $this->core = CoreFactory::boot(array('View'));
        $this->models = ModelFactory::boot(array('User'));
    }

    public function index() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_user'])) {

            $rules = [
                'required' => [['first_name'], ['last_name'], ['email'], ['mobile_number'], ['title'], ['age'], ['country'], ['mobile_number_prefix']],
                'alpha' => [['title'], ['country']],
                'email' => [['email']],
                'numeric' => [['mobile_number'], ['age']],
                'accepted' => [['accept_promotional_mails'], ['accept_thirdparty_mails']],
                'regex' => [['first_name', "@^([a-zA-Z.\-/' ])+$@"], ['last_name', "@^([a-zA-Z.\-/' ])+$@"]]
            ];

            $v = new \Quill\Validator($_POST, array('first_name', 'last_name', 'email', 'mobile_number', 'title', 'age', 'country', 'accept_promotional_mails', 'accept_thirdparty_mails', 'mobile_number_prefix'));
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

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['data']['user'] = $this->app->user;
        $data['app'] = $this->app->config();
        $data['countries'] = load_config_one('countries');
        $data['meta'] = array('title' => 'Account Settings - Customer Dashboard | Tagzie.com', 'page-name' => 'customer-settings');
        $data['user'] = $this->models->user->getById($this->app->user['id']);

        $this->core->view->make('web/customer/setting.php', $data);
    }

}
