<?php

namespace App\Controllers\UrlRedirect;

use App\Services\Uid as Uid;
use Quill\Factories\CoreFactory;
use Quill\Factories\ModelFactory;
use Quill\Factories\RepositoryFactory;

class IndexController extends \App\Controllers\BaseController {

    private $uId;

    function __construct($app) {

        parent::__construct($app);

        $this->core = CoreFactory::boot(array('View'));
        $this->models = ModelFactory::boot(array('Media', 'MediaVariant'));

        $this->uId = new Uid();
    }

    public function redirect($uid) {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['id'] = $this->uId->decode($uid)[0];
        $media = $this->models->media->getById($data['id']);
        $data['url'] = $this->app->config('base_url') . $media['instagram_username'] . '/' . $media['path'];
        $data['media'] = $media;
        $data['currencies'] = load_config_one('currencies');
        

        $data['media']['prices'] = $this->models->mediaVariant->getPricesByMediaId($data['id']);
        
        $data['meta'] = array('title' => "Buy {$media['title']} from @{$media['instagram_username']} | Shop with Tagzie", 'page-name' => 'product-redirect');
        if (empty($media) || $media['is_deleted'] || empty($media['is_active'])) {

            $this->app->slim->notFound();
        }

        if (getOS() == 'Android' || getOS() == 'iOS') {

            $this->core->view->make('web/product/redirect.php', $data);
        } else {

            $this->app->slim->redirect($data['url'], 301);
        }
    }

}
