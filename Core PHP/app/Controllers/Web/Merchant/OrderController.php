<?php

namespace App\Controllers\Web\Merchant;

use Quill\Factories\CoreFactory;
use Quill\Factories\ServiceFactory;
use Quill\Factories\ModelFactory;
use Quill\Factories\RepositoryFactory;

class OrderController extends \App\Controllers\Web\Merchant\MerchantBaseController{
    
    function __construct($app = NULL) {

        parent::__construct($app);
        
        $this->core = CoreFactory::boot(array('View'));
        $this->models = ModelFactory::boot(array('Order', 'User', 'OrderSuborder', 'OrderItem', 'MerchantLedger', 'MessagesThreadDetails'));
        $this->repositories = RepositoryFactory::boot(array('OrderRepository'));
        $this->services = ServiceFactory::boot(array('Jwt', 'Stripe', 'Transaction'));
    }    
    
    public function customer() {
        
        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['title'] = 'Customer Orders';
        
        $data['customer_orders'] = $this->models->orderSuborder->getCustomerOrdersByMerchantId($this->app->user['id']);

        //get users list from orders
        $users = array();
        $user_details = array();
        foreach($data['customer_orders'] as $order_customer) {
            if(!in_array($order_customer['user_id'], $users)) {
                $user_detail = $this->models->user->getById($order_customer['user_id']);
                if($user_detail != '') {
                    array_push($user_details,$user_detail);
                    array_push($users,$order_customer['user_id']);
                }
            }
        }
        $data['users'] = $user_details;

        //sorting last name in asc order
        $last_names = array();
        foreach ($user_details as $key => $row)
        {
            $last_names[$key] = $row['last_name'];
        }
        array_multisort($last_names, SORT_ASC, $user_details);
        
        $last_names;
        
        //range of last name initial in sorting array
        $user_last_name_initial = array();
        foreach($last_names as $last_name) {
            
            if(!in_array($last_name[0],$user_last_name_initial))
            {
                if($last_name[0] != '')
                {
                    array_push($user_last_name_initial,  ucfirst($last_name[0]));
                }
            }
            
        }
        $data['user_last_name_initial'] = $user_last_name_initial;
        
        
        //orders formatting
        $saleOrders = $data['customer_orders'];

        foreach ($saleOrders as $orderIndex => $order) {

            $history = explode(':::', $order['order_history']);

            $orderHistory = array();

            foreach ($history as $row) {

                $orderHistory[] = unserialize($row);
            }

            $saleOrders[$orderIndex]['order_history'] = $orderHistory;
            $saleOrders[$orderIndex]['tracking_details'] = unserialize($order['tracking_details']);
            $saleOrders[$orderIndex]['delivery_address'] = unserialize($order['delivery_address']);
            $saleOrders[$orderIndex]['items'] = $this->models->orderItem->getBySuborderId($order['id']);

            foreach ($saleOrders[$orderIndex]['items'] as $itemIndex => $item) {

                $saleOrders[$orderIndex]['items'][$itemIndex]['options'] = unserialize($item['options']);
            }
        }
        $data['orders'] = $saleOrders;
        $data['alphabets'] = range('A', 'Z');
        $data['currencies'] = load_config_one('currencies');
        $data['countries'] = load_config_one('countries'); 
        
        
        $this->core->view->make('web/merchant/order/customer.php', $data); 
        
    }
    
    public function customerOrdersView() {
        
        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        
        $data['customer_orders'] = $this->models->orderSuborder->getCustomerOrdersByMerchantId($this->app->user['id']);

        //get users list from orders
        $users = array();
        $user_details = array();
        foreach($data['customer_orders'] as $order_customer) {
            if(!in_array($order_customer['user_id'], $users)) {
                $user_detail = $this->models->user->getById($order_customer['user_id']);
                if($user_detail != '') {
                    array_push($user_details,$user_detail);
                    array_push($users,$order_customer['user_id']);
                }
            }
        }
        $data['users'] = $user_details;

        //sorting last name in asc order
        $last_names = array();
        foreach ($user_details as $key => $row)
        {
            $last_names[$key] = $row['last_name'];
        }
        
        $order = ($this->request->get('order') == 'desc')? SORT_DESC : SORT_ASC;
        
        array_multisort($last_names, $order, $user_details);
        
        $last_names;
        
        //range of last name initial in sorting array
        $user_last_name_initial = array();
        foreach($last_names as $last_name) {
            
            if(!in_array($last_name[0],$user_last_name_initial))
            {
                if($last_name[0] != '')
                {
                    array_push($user_last_name_initial,  ucfirst($last_name[0]));
                }
            }
            
        }
        $data['user_last_name_initial'] = $user_last_name_initial;
        
        
        //orders formatting
        $saleOrders = $data['customer_orders'];

        foreach ($saleOrders as $orderIndex => $order) {

            $history = explode(':::', $order['order_history']);

            $orderHistory = array();

            foreach ($history as $row) {

                $orderHistory[] = unserialize($row);
            }

            $saleOrders[$orderIndex]['order_history'] = $orderHistory;
            $saleOrders[$orderIndex]['tracking_details'] = unserialize($order['tracking_details']);
            $saleOrders[$orderIndex]['delivery_address'] = unserialize($order['delivery_address']);
            $saleOrders[$orderIndex]['items'] = $this->models->orderItem->getBySuborderId($order['id']);

            foreach ($saleOrders[$orderIndex]['items'] as $itemIndex => $item) {

                $saleOrders[$orderIndex]['items'][$itemIndex]['options'] = unserialize($item['options']);
            }
        }
        $data['orders'] = $saleOrders;
        $data['alphabets'] = ($this->request->get('order') == 'desc')? range('Z', 'A') : range('A', 'Z');
        $data['currencies'] = load_config_one('currencies');
        $data['countries'] = load_config_one('countries'); 
        
        
        $this->core->view->make('web/merchant/components/customer-order-list.php', $data); 
        
    }    
    
    public function overview() {
        
        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['data']['user']['id'] = $this->app->user['id'];
        $data['user'] = $this->app->user;
        $data['title'] = 'Orders';
        
        $offset = $this->request->get('page');
        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
	$search = $this->request->get('search');
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));
        $saleOrders = $this->models->orderSuborder->getAllByMerchantId($this->app->user['id'], $filter, $order, $offset, 50, '', $search);
        
        foreach ($saleOrders as $orderIndex => $order) {

            $history = explode(':::', $order['order_history']);

            $orderHistory = array();

            foreach ($history as $row) {

                $orderHistory[] = unserialize($row);
            }

            $saleOrders[$orderIndex]['order_history'] = $orderHistory;
            $saleOrders[$orderIndex]['tracking_details'] = unserialize($order['tracking_details']);
            $saleOrders[$orderIndex]['delivery_address'] = unserialize($order['delivery_address']);
            $saleOrders[$orderIndex]['items'] = $this->models->orderItem->getBySuborderId($order['id']);

            foreach ($saleOrders[$orderIndex]['items'] as $itemIndex => $item) {

                $saleOrders[$orderIndex]['items'][$itemIndex]['options'] = unserialize($item['options']);
            }
            $saleOrders[$orderIndex]['thread_id'] = $this->models->messagesThreadDetails->getThreadIdByOrderId($order['id'], $this->app->user['id'], $order['user_id']);

        }
        $data['currencies'] = load_config_one('currencies');
        $data['countries'] = load_config_one('countries'); 
        
        $data['orders'] = $saleOrders;
        $data['user_id'] = $this->app->user['id'];
        
        $this->core->view->make('web/merchant/order/overview.php', $data); 
        
    }    
    
    public function getOrdersByStatus() {
        
        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['user'] = $this->app->user;
        $offset = ($this->request->get('page') - 1);
        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));
        $status = $this->request->get('status');
        
        if($status == 'none') {

            $saleOrders = $this->models->orderSuborder->getAllByMerchantId($this->app->user['id'], $filter, $order, $offset);
        } else {

            $saleOrders = $this->models->orderSuborder->getAllByMerchantId($this->app->user['id'], $filter, $order, $offset, 10, $status);
        }

        foreach ($saleOrders as $orderIndex => $order) {

            $history = explode(':::', $order['order_history']);

            $orderHistory = array();

            foreach ($history as $row) {

                $orderHistory[] = unserialize($row);
            }

            $saleOrders[$orderIndex]['order_history'] = $orderHistory;
            $saleOrders[$orderIndex]['tracking_details'] = unserialize($order['tracking_details']);
            $saleOrders[$orderIndex]['delivery_address'] = unserialize($order['delivery_address']);
            $saleOrders[$orderIndex]['items'] = $this->models->orderItem->getBySuborderId($order['id']);
            $saleOrders[$orderIndex]['thread_id'] = $this->models->messagesThreadDetails->getThreadIdByOrderId($order['id'], $this->app->user['id'], $order['user_id']);

            foreach ($saleOrders[$orderIndex]['items'] as $itemIndex => $item) {

                $saleOrders[$orderIndex]['items'][$itemIndex]['options'] = unserialize($item['options']);
            }
        }
        $data['currencies'] = load_config_one('currencies');
        $data['countries'] = load_config_one('countries'); 
        
        $data['orders'] = $saleOrders;
        
        $this->core->view->make('web/merchant/components/order_list.php', $data); 
        
    }    
    
}


