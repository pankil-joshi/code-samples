<?php

namespace App\Controllers\Web\Customer;

use Quill\Factories\CoreFactory;
use Quill\Factories\RepositoryFactory;
use Quill\Factories\ModelFactory;

class DisputeController extends \App\Controllers\Web\Customer\CustomerBaseController  {

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

    public function index() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['data']['user']['id'] = $this->app->user['id'];
        $data['user'] = $this->app->user;
        $data['app'] = $this->app->config();

        $disputes = $this->models->message->getCustomerRecentDisputesByUserId($this->app->user['id']);

        foreach ($disputes as $index => $thread) {

            if ($this->app->user['id'] == $thread['sender_id']) {

                $user = $this->models->user->getById($thread['recipient_id']);
            } else {

                $user = $this->models->user->getById($thread['sender_id']);
            }
            $disputes[$index]['second_user'] = $user;
        }

        $data['disputes'] = $disputes;
        $data['meta'] = array('title' => 'Disputes - Customer Dashboard | Tagzie.com', 'page-name' => 'customer-disputes');
        $this->core->view->make('web/customer/dispute.php', $data);
    }

    public function getDisputeList() {
        $data['page'] = $this->request->get('page');
        $data['app'] = $this->app->config();
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['data']['user']['id'] = $this->app->user['id'];
        $offset = ($this->request->get('page') - 1);

        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));
        $totalDisputes = 0;
        if ($this->request->get('status') == 'open') {

            $disputes = $this->models->message->getCustomerRecentDisputesByUserId($this->app->user['id'],  array('filter' => $filter, 'order' => $order, 'status' => 'open'));
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

            $disputes = $this->models->message->getCustomerRecentDisputesByUserId($this->app->user['id'],  array('filter' => $filter, 'order' => $order, 'status' => 'close'));
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

            $disputes = $this->models->message->getCustomerRecentDisputesByUserId($this->app->user['id'], array('filter' => $filter, 'order' => $order));
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
        $this->core->view->make('/web/customer/components/dispute_list.php', $data);
    }

}
