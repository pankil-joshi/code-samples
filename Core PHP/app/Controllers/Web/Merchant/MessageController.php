<?php

namespace App\Controllers\Web\Merchant;

use Quill\Factories\CoreFactory;
//use Quill\Factories\ServiceFactory;
use Quill\Factories\ModelFactory;

class MessageController extends \App\Controllers\Web\Merchant\MerchantBaseController {

    function __construct($app = NULL) {

        parent::__construct($app);

        $this->core = CoreFactory::boot(array('View'));
        $this->models = ModelFactory::boot(array(
                    'MessageRecent',
                    'Message',
                    'MessageRecipient',
                    'User'
        ));
    }

    public function enquiries() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['countries'] = load_config_one('countries');
        $data['title'] = 'Customer Enquiries';
        $data['data']['user']['id'] = $this->app->user['id'];
        $enquiries = $this->models->message->getMerchantRecentEnquiriesByUserId($this->app->user['id']);

        foreach ($enquiries as $index => $thread) {

            if ($this->app->user['id'] == $thread['sender_id']) {

                $user = $this->models->user->getById($thread['recipient_id']);
            } else {

                $user = $this->models->user->getById($thread['sender_id']);
            }
            $enquiries[$index]['second_user'] = $user;
        }

        $data['enquiries'] = $enquiries;

        $this->core->view->make('web/merchant/message/enquiries.php', $data);
    }

    public function enquiriesView() {
        $data['page'] = $this->request->get('page');
        $data['app'] = $this->app->config();
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['data']['user']['id'] = $this->app->user['id'];
        $offset = ($this->request->get('page') - 1);

        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));
        $totalEnquiries = 0;

        if ($this->request->get('status') == 'open') {

            $enquiries = $this->models->message->getMerchantRecentEnquiriesByUserId($this->app->user['id'], array('filter' => $filter, 'order' => $order, 'status' => 'open'));
            foreach ($enquiries as $index => $thread) {

                if ($this->app->user['id'] == $thread['sender_id']) {

                    $user = $this->models->user->getById($thread['recipient_id']);
                } else {

                    $user = $this->models->user->getById($thread['sender_id']);
                }
                $enquiries[$index]['second_user'] = $user;
            }
//            $totalDisputes = $this->models->orderSuborder->getAllCountByUserId($this->app->user['id'], $filter, 'completed');
        } elseif ($this->request->get('status') == 'close') {

            $enquiries = $this->models->message->getMerchantRecentEnquiriesByUserId($this->app->user['id'], array('filter' => $filter, 'order' => $order, 'status' => 'close'));
            foreach ($enquiries as $index => $thread) {

                if ($this->app->user['id'] == $thread['sender_id']) {

                    $user = $this->models->user->getById($thread['recipient_id']);
                } else {

                    $user = $this->models->user->getById($thread['sender_id']);
                }
                $enquiries[$index]['second_user'] = $user;
            }
//            $totalDisputes = $this->models->orderSuborder->getAllCountByUserId($this->app->user['id'], $filter, 'in_progress');
        } else {

            $enquiries = $this->models->message->getMerchantRecentEnquiriesByUserId($this->app->user['id'], array('filter' => $filter, 'order' => $order));
            foreach ($enquiries as $index => $thread) {

                if ($this->app->user['id'] == $thread['sender_id']) {

                    $user = $this->models->user->getById($thread['recipient_id']);
                } else {

                    $user = $this->models->user->getById($thread['sender_id']);
                }
                $enquiries[$index]['second_user'] = $user;
            }
//            $totalDisputes = $this->models->orderSuborder->getAllCountByUserId($this->app->user['id'], $filter);
        }

        $data['enquiries'] = $enquiries;

        $data['total_enquiries'] = $totalEnquiries;

        $data['currencies'] = load_config_one('currencies');
        $data['countries'] = load_config_one('countries');
        $this->core->view->make('/web/merchant/components/enquiries-list.php', $data);
    }

    public function disputes() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['countries'] = load_config_one('countries');
        $data['title'] = 'Dispute resolution';
        $data['data']['user']['id'] = $this->app->user['id'];
        $disputes = $this->models->message->getMerchantRecentDisputesByUserId($this->app->user['id'], 1, true);

        foreach ($disputes as $index => $thread) {

            if ($this->app->user['id'] == $thread['sender_id']) {

                $user = $this->models->user->getById($thread['recipient_id']);
            } else {

                $user = $this->models->user->getById($thread['sender_id']);
            }
            $disputes[$index]['second_user'] = $user;
        }

        $data['disputes'] = $disputes;
        $this->core->view->make('web/merchant/message/disputes.php', $data);
    }

    public function disputesView() {
        $data['page'] = $this->request->get('page');
        $data['app'] = $this->app->config();
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['data']['user']['id'] = $this->app->user['id'];
        $offset = ($this->request->get('page') - 1);

        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));
        $totalDisputes = 0;

        if ($this->request->get('status') == 'open') {

            $disputes = $this->models->message->getMerchantRecentDisputesByUserId($this->app->user['id'], array('filter' => $filter, 'order' => $order, 'status' => 'open'));
            foreach ($disputes as $index => $thread) {

                if ($this->app->user['id'] == $thread['sender_id']) {

                    $user = $this->models->user->getById($thread['recipient_id']);
                } else {

                    $user = $this->models->user->getById($thread['sender_id']);
                }
                $disputes[$index]['second_user'] = $user;
            }
//            $totalDisputes = $this->models->orderSuborder->getAllCountByUserId($this->app->user['id'], $filter, 'completed');
        } elseif ($this->request->get('status') == 'close') {

            $disputes = $this->models->message->getMerchantRecentDisputesByUserId($this->app->user['id'], array('filter' => $filter, 'order' => $order, 'status' => 'close'));
            foreach ($disputes as $index => $thread) {

                if ($this->app->user['id'] == $thread['sender_id']) {

                    $user = $this->models->user->getById($thread['recipient_id']);
                } else {

                    $user = $this->models->user->getById($thread['sender_id']);
                }
                $disputes[$index]['second_user'] = $user;
            }
//            $totalDisputes = $this->models->orderSuborder->getAllCountByUserId($this->app->user['id'], $filter, 'in_progress');
        } else {

            $disputes = $this->models->message->getMerchantRecentDisputesByUserId($this->app->user['id'], array('filter' => $filter, 'order' => $order));
            foreach ($disputes as $index => $thread) {

                if ($this->app->user['id'] == $thread['sender_id']) {

                    $user = $this->models->user->getById($thread['recipient_id']);
                } else {

                    $user = $this->models->user->getById($thread['sender_id']);
                }
                $disputes[$index]['second_user'] = $user;
            }
//            $totalDisputes = $this->models->orderSuborder->getAllCountByUserId($this->app->user['id'], $filter);
        }

        $data['disputes'] = $disputes;

        $data['total_disputes'] = $totalDisputes;

        $data['currencies'] = load_config_one('currencies');
        $data['countries'] = load_config_one('countries');

        $this->core->view->make('/web/merchant/components/disputes-list.php', $data);
    }

}
