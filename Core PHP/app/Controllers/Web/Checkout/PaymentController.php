<?php

namespace App\Controllers\Web\Checkout;

use Quill\Factories\CoreFactory;
use Quill\Factories\RepositoryFactory;
use Quill\Factories\ModelFactory;
use Quill\Factories\ServiceFactory;

class PaymentController extends \App\Controllers\Web\AccountBaseController {

    function __construct($app = NULL) {

        parent::__construct($app);

        $this->core = CoreFactory::boot(array('View'));
        $this->repositories = RepositoryFactory::boot(array('MediaRepository'));
        $this->models = ModelFactory::boot(array('User', 'UserAddress', 'UserStripeCard', 'OrderSuborder', 'OrderItem', 'MerchantDetails'));
        $this->services = ServiceFactory::boot(array('Stripe'));
    }

    public function index() {
        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();

        $media = $this->repositories->mediaRepository->get($_POST['media_id']);

        if (!empty($media['id'])) {

            $_SESSION['cart']['variant_ids'] = $_POST['variant_ids'];
            $_SESSION['cart']['media_id'] = $_POST['media_id'];
            $_SESSION['cart']['quantities'] = $_POST['quantities'];
            $_SESSION['cart']['postage_option'] = $_POST['postage_option'];
            $_SESSION['cart']['delivery_address'] = $_POST['delivery_address'];
            
            $media['postage_options'] = $this->repositories->mediaRepository->getAllPostageOptionsByMediaId($_POST['media_id']);
            $data['delivery_address'] = $this->models->userAddress->getById($_POST['delivery_address'], $this->app->user['id']);
            $stripeCustomerId = $this->models->user->getStripeCustomerId($this->app->user['id']);
            $data['cards'] = $this->models->userStripeCard->getAllbyUserId($this->app->user['id']);
            $data['media'] = $media;
            $data['user'] = $this->models->user->getById($this->app->user['id']);
            $config = load_config_one('stripe');
            $data['stripe_publishable_key'] = $config['stripe_publishable_key'];
            $data['meta'] = array('title' => 'Secure Payment | Tagzie.com');            
            $this->core->view->make('web/checkout/payment.php', $data);
        } else {
            $this->app->slim->notFound();
        }
    }

    public function success($id) {
        
         $order = $this->models->orderSuborder->getById($id, $this->app->user['id']);

        if (!empty($order['id'])) {
        $invoice['suborder'] = $order;

            if(!$invoice['suborder']) 
                $this->app->slim->notFound();
        
            $data['data']['base_url'] = $this->app->config('base_url');
            $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
            $order['items'] = $this->models->orderItem->getBySuborderId($order['id']);
            $order['merchant'] = $this->models->merchantDetails->getByUserId($order['merchant_id']);
            $media = $this->repositories->mediaRepository->get($order['media_id']);
            $data['countries'] = load_config_one('countries');
            $data['currencies'] = load_config_one('currencies');
            $data['app'] = $this->app->config();
            $data['order'] = $order;
            $data['media'] = $media;
            $data['meta'] = array('title' => 'Payment Successful | Tagzie.com');            
            $this->core->view->make('web/checkout/success.php', $data);
        } else {
            
            $this->app->slim->notFound();
        }
    }    
}
