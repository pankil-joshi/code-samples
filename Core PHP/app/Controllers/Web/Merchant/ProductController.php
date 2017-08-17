<?php

namespace App\Controllers\Web\Merchant;

use Quill\Factories\CoreFactory;
//use Quill\Factories\ServiceFactory;
use Quill\Factories\ModelFactory;
use Quill\Factories\RepositoryFactory;

class ProductController extends \App\Controllers\Web\Merchant\MerchantBaseController {

    function __construct($app = NULL) {

        parent::__construct($app);

        $this->core = CoreFactory::boot(array('View'));

        $this->models = ModelFactory::boot(array('User', 'Media'));
        
        $this->repositories = RepositoryFactory::boot(array('MediaRepository'));
    }

    public function index() {

        $data['data']['base_url'] = $this->app->config('base_url');

        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');

        $data['app'] = $this->app->config();

        $data['user'] = $this->models->user->getById($this->app->user['id']);
        $data['currencies'] = load_config_one('currencies');
        $data['title'] = 'Products';
        
        $offset = $this->request->get('page');

        $search = $this->request->get('search');
        $status = $this->request->get('status');

        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));

        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));

        $_media = $this->models->media->getAllByUserId($this->app->user['id'], $filter, $order, $offset, 50, $status, $search);
        $media = array();
        foreach ($_media as $index => $row) {
	    
            $media[] = $this->repositories->mediaRepository->get($row['id']);
 
        }        
        
        $data['media'] = $media;
        
        $this->core->view->make('web/merchant/product/index.php', $data);
    }
    
    public function productListView() {

        $data['data']['base_url'] = $this->app->config('base_url');

        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');

        $data['app'] = $this->app->config();

        $data['user'] = $this->models->user->getById($this->app->user['id']);
        $data['currencies'] = load_config_one('currencies');

        
        $offset = ($this->request->get('page') - 1);

        $search = $this->request->get('search');
        $status = $this->request->get('status');
        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));

        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));

        $_media = $this->models->media->getAllByUserId($this->app->user['id'], $filter, $order, $offset, 50, $status, $search);
        $media = array();

        foreach ($_media as $index => $row) {
	    
            $media[] = $this->repositories->mediaRepository->get($row['id']);
 
        }        
        
        $data['media'] = $media;
        
        $this->core->view->make('/web/merchant/components/product-list.php', $data);
    }    

}
