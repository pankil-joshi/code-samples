<?php

namespace App\Controllers\Web\Merchant;

use Quill\Factories\CoreFactory;
//use Quill\Factories\ServiceFactory;
use Quill\Factories\ModelFactory;

class IndexController extends  \App\Controllers\Web\Merchant\MerchantBaseController{
    
    function __construct($app = NULL) {

        parent::__construct($app);
        
        $this->core = CoreFactory::boot(array('View'));
        $this->models = ModelFactory::boot(array('Order', 'OrderSuborder', 'OrderItem', 'MerchantLedger', 'MessageRecipient', 'SubscriptionPackage', 'Message'));
    }    
    
    public function index() {
        
        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        
        //pending orders
        $offset = $this->request->get('page');
        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));
        $saleOrders = $this->models->orderSuborder->getAllByMerchantId($this->app->user['id'], $filter, $order, $offset, 2, 'in_progress');
        
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
        $data['currencies'] = load_config_one('currencies');
        $data['countries'] = load_config_one('countries'); 
        
        $data['pending_orders'] = $saleOrders;
        
        //pending orders count
        $pending_order_count = $this->models->orderSuborder->getPendingOrdersByMerchantId($this->app->user['id']);
        
        $data['pending_order_count'] = $pending_order_count;
        
        $data['awaiting_withdrawal'] = $this->models->merchantLedger->getAwaitingWithdrawl($this->app->user['id'], $this->models->subscriptionPackage->getThresholdByUserId($this->app->user['id']));
        $data['merchant'] = $this->merchant;
        $data['currencies'] = load_config_one('currencies');
        
        $data['unread_replies'] = $this->models->messageRecipient->getUnreadReplies($this->app->user['id']);
        $data['open_dispute_count'] = (int)$this->models->message->getDisputeCount($this->app->user['id'], 'open');
        $data['open_disputes'] = $this->models->message->getRecentDisputesByUserId($this->app->user['id'], 'open');
        $data['open_inquiries_count'] = (int)$this->models->message->getInquiryCount($this->app->user['id'], 'open');
        $data['open_inquiries'] = $this->models->message->getRecentInquiriesByUserId($this->app->user['id'], 'open');        
        $data['unread_replies_detail'] = $this->models->messageRecipient->getUnreadRepliesWithDetail($this->app->user['id']); 

        $this->app->user['timezone'] = get_timezone_offset_from_name($this->app->user['timezone']);        
        
        for($i = 14; $i>0; $i--){
            $earning['date'] = gmdate('Y-m-d', strtotime('- '. ($i -1) .' days UTC'));
            $earning['amount'] = $this->models->merchantLedger->getEarningsBetweenDates($this->app->user, array('start_date' => gmdate('Y-m-d', strtotime('- '. ($i -1) .' days UTC'))))['amount'];
            $earnings_fourteen_days[] = $earning;
            
        }
        $data['earnings_fourteen_days'] = $earnings_fourteen_days;
        $data['title'] = 'Dashboard';
        $this->core->view->make('web/merchant/index.php', $data); 

    }
    
}


