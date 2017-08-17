<?php

namespace App\Controllers\Web\Merchant;

use Quill\Factories\CoreFactory;
//use Quill\Factories\ServiceFactory;
use Quill\Factories\ModelFactory;

class SupportController extends \App\Controllers\Web\Merchant\MerchantBaseController {

    function __construct($app = NULL) {

        parent::__construct($app);

        $this->core = CoreFactory::boot(array('View'));
        $this->models = ModelFactory::boot(array(

        ));
    }

    public function help() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['countries'] = load_config_one('countries');
        $data['title'] = 'Help & Support';
        $this->core->view->make('web/merchant/support/help.php', $data);
    }

}
