<?php

namespace App\Controllers\Web\Customer;

use Quill\Factories\CoreFactory;
use Quill\Factories\ModelFactory;

class AddressController extends \App\Controllers\Web\Customer\CustomerBaseController {

    function __construct($app = NULL) {

        parent::__construct($app);

        $this->core = CoreFactory::boot(array('View'));
        $this->models = ModelFactory::boot(array('UserAddress'));
        
    }
    
    public function index() {
        
        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['data']['user']['id'] = $this->app->user['id'];
        $data['app'] = $this->app->config();
        $data['countries'] = load_config_one('countries');
        $data['addresses'] = $this->models->userAddress->getAllByUserId($this->app->user['id']);  
        $data['meta'] = array('title' => 'Address Book - Customer Dashboard | Tagzie.com',  'page-name' => 'customer-addressbook');
        $this->core->view->make('web/customer/address.php', $data);
        
    }    
    
}
