<?php

namespace App\Controllers\Web\Product;

use Quill\Factories\CoreFactory;
use Quill\Factories\RepositoryFactory;
use Quill\Factories\ModelFactory;

class IndexController extends \App\Controllers\Web\PublicBaseController {

    function __construct($app = NULL) {

        parent::__construct($app);

        $this->core = CoreFactory::boot(array('View'));
        $this->models = ModelFactory::boot(array('Media'));
        $this->repositories = RepositoryFactory::boot(array('MediaRepository'));
    }

    public function index($id) {


        $media = $this->repositories->mediaRepository->get($id);
        
        if(getOS() == 'Android' || getOS() == 'iOS') {
            
            $this->app->slim->redirect($this->app->config('base_url') . 'r/' . $media['uid'], 301);
        }
        if ($media) {
            
            if(is_numeric($id)) {

                $this->app->slim->redirect($this->app->config('base_url') . $media['instagram_username'] . '/' . $media['path'], 301);
            }            

            if ($this->request->get('destination') == 'customer_view') {

                $this->models->media->addView($id);
            }            

            foreach ($media['attributes'] as $row) {

                $attributes[$row['code']] = $row['attribute_value'];
            }

            $media['attributes'] = $attributes;
            $media['postage_option_rates'] = $this->repositories->mediaRepository->getPostageOptionRatesByMediaId($media['id']);
            $media['postage_options'] = $this->repositories->mediaRepository->getAllPostageOptionsByMediaId($media['id']);
            $data['data']['base_url'] = $this->app->config('base_url');
            $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
            $data['app'] = $this->app->config();
            $data['media'] = $media;
            $data['page'] = array('name' => 'product');
            $data['currencies'] = load_config_one('currencies');
            $data['meta'] = array('title' => "Buy {$media['title']} from {$media['merchant']['merchant_legal_entity_business_name']} (@{$media['instagram_username']}) | Shop with Tagzie", 'description' => $media['description']);

            $this->core->view->make('web/product/index.php', $data);
        } else {
            
            $this->app->slim->notFound();
        }
    }

}
