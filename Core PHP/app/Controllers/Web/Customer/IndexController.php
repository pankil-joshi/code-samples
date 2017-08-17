<?php

namespace App\Controllers\Web\Customer;

use Quill\Factories\CoreFactory;
use Quill\Factories\RepositoryFactory;
use Quill\Factories\ModelFactory;
use Quill\Factories\ServiceFactory;

class IndexController extends \App\Controllers\Web\Customer\CustomerBaseController {

    function __construct($app = NULL) {

        parent::__construct($app);

        $this->core = CoreFactory::boot(array('View'));
        $this->models = ModelFactory::boot(array('OrderSuborder', 'SubscriptionPackage', 'UserStripeCard', 'User', 'MediaCategories'));
        $this->services = ServiceFactory::boot(array('Wordpress'));
        $this->repositories = RepositoryFactory::boot(array('OrderRepository'));
    }

    public function index() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['data']['user']['id'] = $this->app->user['id'];
        $data['app'] = $this->app->config();
        $offset = $this->request->get('page');
        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));
        $orders = $this->repositories->orderRepository->getOrderList($this->app->user['id'], $filter, $order, $offset, 10);

        $data['orders'] = $orders;
        $data['expiring_cards'] = $this->models->userStripeCard->getExpiredCards($this->app->user['id']);
        $data['cards'] = $this->models->userStripeCard->getAllbyUserId($this->app->user['id']);
//        $this->services->wordpress->loadBlogHeader();
        $data['total_orders'] = $this->models->orderSuborder->getAllCountByUserId($this->app->user['id'], $filter);
        $data['page'] = 0;
        $data['meta'] = array('title' => 'Customer Dashboard | Tagzie.com', 'page-name' => 'customer-dashboard');
        $data['currencies'] = load_config_one('currencies');
        $config = load_config_one('stripe');
        $data['stripe_publishable_key'] = $config['stripe_publishable_key'];
        $this->core->view->make('web/customer/index.php', $data);
    }

    public function getOrderList() {
        $data['page'] = $this->request->get('page');
        $data['app'] = $this->app->config();
        $offset = ($this->request->get('page') - 1);

        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));
        $totalOrders = 0;
//        if ($this->request->get('status') == 'completed') {
//
//            $orders = $this->repositories->orderRepository->getOrderList($this->app->user['id'], $filter, $order, $offset, 10, 'completed');
//            $totalOrders = $this->models->orderSuborder->getAllCountByUserId($this->app->user['id'], $filter, 'completed');
//        } elseif ($this->request->get('status') == 'in_progress') {
//
//            $orders = $this->repositories->orderRepository->getOrderList($this->app->user['id'], $filter, $order, $offset, 10, 'in_progress');
//            $totalOrders = $this->models->orderSuborder->getAllCountByUserId($this->app->user['id'], $filter, 'in_progress');
//        } elseif ($this->request->get('status') == 'tagged') {
//
//            $orders = $this->repositories->orderRepository->getTaggedList($this->app->user['id'], $filter, $order, $offset);
//        } else {
//
//            $orders = $this->repositories->orderRepository->getOrderList($this->app->user['id'], $filter, $order, $offset, 10);
//            $totalOrders = $this->models->orderSuborder->getAllCountByUserId($this->app->user['id'], $filter);
//        }

        if ($this->request->get('status') == 'none') {

            $orders = $this->repositories->orderRepository->getOrderList($this->app->user['id'], $filter, $order, $offset, 10);
            $totalOrders = $this->models->orderSuborder->getAllCountByUserId($this->app->user['id'], $filter);
        } elseif ($this->request->get('status') == 'tagged') {

            $orders = $this->repositories->orderRepository->getTaggedList($this->app->user['id'], $filter, $order, $offset);
        } else {

            $orders = $this->repositories->orderRepository->getOrderList($this->app->user['id'], $filter, $order, $offset, 10, $this->request->get('status'));
            $totalOrders = $this->models->orderSuborder->getAllCountByUserId($this->app->user['id'], $filter, $this->request->get('status'));
        }

        $data['orders'] = $orders;

        $data['total_orders'] = $totalOrders;

        $data['currencies'] = load_config_one('currencies');
        $this->core->view->make('web/customer/components/order_list_dashboard.php', $data);
    }

    public function merchantSignupDetails($id) {

        $data['meta'] = array('title' => 'Merchant Account Details | Tagzie.com');
        if ($this->app->user['is_merchant']) {

            $this->slim->redirect($this->app->config('base_url') . 'account/merchant/');
        }

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['data']['instagram_connect_url'] = $this->app->config('instagram_connect_url');
        $data['app'] = $this->app->config();
        $data['package'] = $this->models->subscriptionPackage->getById($id);
        $data['subscriptionPackages'] = $this->models->subscriptionPackage->getAllPublic();
        $data['cards'] = $this->models->userStripeCard->getAllbyUserId($this->app->user['id']);
        if (empty($data['package'])) {

            $this->app->slim->notFound();
        }
        $config = load_config_one('stripe');
        $data['stripe_publishable_key'] = $config['stripe_publishable_key'];
        $data['parent_categories'] = $this->models->mediaCategories->getAllParent();

        $this->core->view->make('web/account/register/merchant/business-details.php', $data);
    }

}
