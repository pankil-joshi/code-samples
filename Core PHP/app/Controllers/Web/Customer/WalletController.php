<?php

namespace App\Controllers\Web\Customer;

use Quill\Factories\CoreFactory;
use Quill\Factories\RepositoryFactory;
use Quill\Factories\ServiceFactory;

use Quill\Factories\ModelFactory;

class WalletController extends \App\Controllers\Web\Customer\CustomerBaseController {

    function __construct($app = NULL) {

        parent::__construct($app);
        
        $this->models = ModelFactory::boot(array('User', 'UserStripeCard'));
        $this->core = CoreFactory::boot(array('View'));
        $this->services = ServiceFactory::boot(array('Stripe'));
    }
    
    public function index() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['data']['user']['id'] = $this->app->user['id'];
        $stripeCustomerId = $this->models->user->getStripeCustomerId($this->app->user['id']);
        $data['cards'] = $this->models->userStripeCard->getAllbyUserId($this->app->user['id']);
        $data['meta'] = array('title' => 'Wallet - Customer Dashboard | Tagzie.com', 'page-name' => 'customer-wallet');
        $config = load_config_one('stripe');
        $data['stripe_publishable_key'] = $config['stripe_publishable_key'];        
        $this->core->view->make('web/customer/wallet.php', $data);

    }    
}
