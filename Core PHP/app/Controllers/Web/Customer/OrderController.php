<?php

namespace App\Controllers\Web\Customer;

use Quill\Factories\CoreFactory;
use Quill\Factories\RepositoryFactory;
use Quill\Factories\ModelFactory;

class OrderController extends \App\Controllers\Web\Customer\CustomerBaseController {

    function __construct($app = NULL) {

        parent::__construct($app);

        $this->core = CoreFactory::boot(array('View'));
        $this->models = ModelFactory::boot(array('OrderSuborder'));
        $this->repositories = RepositoryFactory::boot(array('OrderRepository'));
    }

    public function index() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['data']['user']['id'] = $this->app->user['id'];
        $data['user'] = $this->app->user;
        $data['app'] = $this->app->config();
        $offset = $this->request->get('page');
        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));
        $orders = $this->repositories->orderRepository->getOrderList($this->app->user['id'], $filter, $order, $offset, 10);

        $data['orders'] = $orders;
        $data['total_orders'] = $this->models->orderSuborder->getAllCountByUserId($this->app->user['id'], $filter);
        $data['page'] = 0;
        $data['meta'] = array('title' => 'Orders - Customer Dashboard | Tagzie.com', 'page-name' => 'customer-orders');
        $data['currencies'] = load_config_one('currencies');
        $this->core->view->make('web/customer/order.php', $data);
    }

    public function getOrderList() {
        $data['page'] = $this->request->get('page');
        $data['app'] = $this->app->config();
        $data['user'] = $this->app->user;
        $offset = ($this->request->get('page') - 1);

        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));
        $totalOrders = 0;

        $status = '';
//        if ($this->request->get('status') == 'completed') {
//
//            $orders = $this->repositories->orderRepository->getOrderList($this->app->user['id'], $filter, $order, $offset, 10, 'completed');
//            $totalOrders = $this->models->orderSuborder->getAllCountByUserId($this->app->user['id'], $filter, 'completed');
//        } elseif ($this->request->get('status') == 'in_progress') {
//
//            $orders = $this->repositories->orderRepository->getOrderList($this->app->user['id'], $filter, $order, $offset, 10, 'in_progress');
//            $totalOrders = $this->models->orderSuborder->getAllCountByUserId($this->app->user['id'], $filter, 'in_progress');
//        } elseif ($this->request->get('status') == 'tagged') {
//            $status = 'tagged';
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
            $status = 'tagged';
            $orders = $this->repositories->orderRepository->getTaggedList($this->app->user['id'], $filter, $order, $offset);
        } else {
            $orders = $this->repositories->orderRepository->getOrderList($this->app->user['id'], $filter, $order, $offset, 10, $this->request->get('status'));
            $totalOrders = $this->models->orderSuborder->getAllCountByUserId($this->app->user['id'], $filter, $this->request->get('status'));
        }

        $data['orders'] = $orders;
        $data['status'] = $status;

        $data['total_orders'] = $totalOrders;

        $data['currencies'] = load_config_one('currencies');
        $this->core->view->make('web/customer/components/order_list.php', $data);
    }

}
