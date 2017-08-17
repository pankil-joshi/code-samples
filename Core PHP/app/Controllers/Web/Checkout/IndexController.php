<?php

namespace App\Controllers\Web\Checkout;

use Quill\Factories\CoreFactory;
use Quill\Factories\RepositoryFactory;
use Quill\Factories\ModelFactory;

class IndexController extends \App\Controllers\Web\PublicBaseController {

    function __construct($app = NULL) {

        parent::__construct($app);

        $this->core = CoreFactory::boot(array('View'));
        $this->repositories = RepositoryFactory::boot(array('MediaRepository'));
        $this->models = ModelFactory::boot(array('UserAddress', 'MerchantDetails', 'User'));
    }

    public function index() {

        $_SESSION['cart']['comment_id'] = (!empty($this->request->get('comment_id'))) ? $this->request->get('comment_id') : '';

        if(!empty($this->request->get('media_id'))) {
                if(isset($_SESSION['cart']['media_id']) && $_SESSION['cart']['media_id'] != $this->request->get('media_id')) {
                    
                    $_SESSION['cart'] = null;
                }            
            $_SESSION['cart']['media_id'] = $this->request->get('media_id');
        } elseif (!empty ($_SESSION['cart']['media_id'])) {
            
            $_SESSION['cart']['media_id'] = $_SESSION['cart']['media_id'];
        } else {
            
            $this->app->slim->notFound();
        }

        if (empty($this->app->user['id'])) {

            $this->app->slim->redirect($this->app->config('base_url') . 'account/login?redirect=checkout');
        } elseif ($this->app->user['is_active'] != 1 && empty ($_SESSION['guest'])) {
            
            $this->app->slim->redirect($this->app->config('base_url') . 'account/customer/signup?redirect=checkout');
        }

        $media = $this->repositories->mediaRepository->get($_SESSION['cart']['media_id']);

        if (!empty($media['id'])) {
            foreach ($media['attributes'] as $row) {

                $attributes[$row['code']] = $row['attribute_value'];
            }
            
            if(!empty($this->request->get('variant_id')) && ((isset($_SESSION['cart']['variant_ids']) && !in_array($this->request->get('variant_id'), $_SESSION['cart']['variant_ids'])) || !isset($_SESSION['cart']['variant_ids'])) ){

                foreach ($media['variants'] as $variant){
                    if($variant['id'] == $this->request->get('variant_id')) {
                        $_SESSION['cart']['variant_ids'][] = $this->request->get('variant_id');
                    }
                }
                
            }
            $media['merchant'] = $this->models->merchantDetails->getByUserId($media['user_id']);
            $media['attributes'] = $attributes;
            $media['postage_option_rates'] = $this->repositories->mediaRepository->getPostageOptionRatesByMediaId($_SESSION['cart']['media_id']);
            $media['postage_options'] = $this->repositories->mediaRepository->getAllPostageOptionsByMediaId($_SESSION['cart']['media_id']);
            $data['countries'] = load_config_one('countries');
            $data['currencies'] = load_config_one('currencies');
            $data['delivery_addresses'] = $this->models->userAddress->getAllByUserId($this->app->user['id']);
            $data['data']['base_url'] = $this->app->config('base_url');
            $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
            $data['app'] = $this->app->config();
            $data['user'] = $this->models->user->getById($this->app->user['id']);
            $data['media'] = $media;
            $data['meta'] = array('title' => 'Checkout | Tagzie.com');            
            $this->core->view->make('web/checkout/index.php', $data);
        } else {
            $this->app->slim->notFound();
        }
    }
}
