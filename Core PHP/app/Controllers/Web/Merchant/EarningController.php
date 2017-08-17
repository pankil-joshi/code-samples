<?php

namespace App\Controllers\Web\Merchant;

use Quill\Factories\CoreFactory;
//use Quill\Factories\ServiceFactory;
use Quill\Factories\ModelFactory;

class EarningController extends \App\Controllers\Web\Merchant\MerchantBaseController {

    function __construct($app = NULL) {

        parent::__construct($app);

        $this->core = CoreFactory::boot(array('View'));
        $this->models = ModelFactory::boot(array(
                    'MerchantLedger',
                    'SubscriptionPackage',
                    'Invoice'
        ));
    }

    public function overview() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['merchant'] = $this->merchant;
        $data['currencies'] = load_config_one('currencies');

        $this->app->user['timezone'] = get_timezone_offset_from_name($this->app->user['timezone']);

        for ($i = 14; $i > 0; $i--) {

            $earning['date'] = gmdate('j M', strtotime('- ' . ($i - 1) . ' days UTC'));
            $earning['amount'] = $this->models->merchantLedger->getEarningsBetweenDates($this->app->user, array('start_date' => gmdate('Y-m-d', strtotime('- ' . ($i - 1) . ' days UTC'))))['amount'];
            $earnings_fourteen_days[] = $earning;
        }
        $data['title'] = 'Earnings Overview';
        $data['awaiting_withdrawl'] = $this->models->merchantLedger->getAwaitingWithdrawl($this->app->user['id'], $this->models->subscriptionPackage->getThresholdByUserId($this->app->user['id']));
        $data['earnings_this_month'] = $this->models->merchantLedger->getEarningsThisMonthByUserId($this->app->user['id']);
        $data['earnings_last_month'] = $this->models->merchantLedger->getEarningsLastMonthByUserId($this->app->user['id']);
        $data['refunds'] = $this->models->merchantLedger->getRefundsThisMonthByUserId($this->app->user['id']);
        $data['transaction_fee'] = $this->models->merchantLedger->getTransactionFeeThisMonthByUserId($this->app->user['id']);
        $data['earnings_fourteen_days'] = $earnings_fourteen_days;

        $this->core->view->make('web/merchant/earning/overview.php', $data);
    }

    public function settlements() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['merchant'] = $this->merchant;
        $data['currencies'] = load_config_one('currencies');
        $data['title'] = 'Tagzie Invoices';

        $settlements = $this->models->invoice->getSettlementInvoicesByUserId($this->app->user['id']);

        foreach ($settlements as $index => $settlement) {

//            $settlements[$index]['entries'] = $this->models->invoice->getAllByCreatedDate($this->app->user['id'], $settlement['created_at']);
        }

        $data['settlements'] = $settlements;

        $this->core->view->make('web/merchant/earning/settlements.php', $data);
    }

    public function invoicesView() {
        $data['page'] = $this->request->get('page');
        $type = $this->request->get('type');
        $data['app'] = $this->app->config();
        $data['merchant'] = $this->merchant;
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['data']['user']['id'] = $this->app->user['id'];
        $offset = ($this->request->get('page') - 1);

        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));
        $totalInvoices = 0;

        $invoices = $this->models->invoice->getSettlementInvoicesByUserId($this->app->user['id'], $filter, $order, $offset, 20, $type);

        $data['settlements'] = $invoices;

        $data['total_invoices'] = $totalInvoices;

        $data['currencies'] = load_config_one('currencies');
        $data['countries'] = load_config_one('countries');
        $this->core->view->make('/web/merchant/components/invoices-list.php', $data);
    }

}
