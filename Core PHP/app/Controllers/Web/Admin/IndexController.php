<?php

namespace App\Controllers\Web\Admin;

use Quill\Factories\CoreFactory;
//use Quill\Factories\ServiceFactory;
use Quill\Factories\ModelFactory;

class IndexController extends  \App\Controllers\Web\Admin\AdminBaseController{
    
    function __construct($app = NULL) {

        parent::__construct($app);

        $this->core = CoreFactory::boot(array('View'));
        $this->models = ModelFactory::boot(array('AdminUser','User','Invoice','OrderSuborder','OrderItem','MerchantLedger'));
    }
    
    public function logout() {
        session_destroy();
        $this->slim->redirect($this->app->config('base_url') . 'admin/login/');
    }
    
    public function adminDashboard() {
        
        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['title'] = 'Admin | Dashboard';
        
        /*
         * Get top ten merchants
         */
        $data['top_merchants'] = $this->models->orderSuborder->getTopTenMerchants();
        
        /*
         * Get top ten items
         */
        $data['top_items'] = $this->models->orderItem->getTopTenItems();
        
        $this->core->view->make('web/admin/dashboard.php', $data); 

    }
    
    public function getAdminDashboardDetails() {
        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $filter = array('start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));
        
        
        /*
         * Get merchant subscription total
         */
        $merchants_revenue = $this->models->invoice->getMerchantsSubscriptionTotalByDates($filter);

        /*
         * All Transections 
         */
        $transections = $this->models->merchantLedger->getAllTransectionsTotal($filter);
        
        /*
         * All Commissions
         */
        $commissions = $this->models->merchantLedger->getAllCommissionsTotal($filter);
        
        /*
         * All refunded
         */
        $refunded = $this->models->merchantLedger->getAllRefundedTotal($filter);        
 
        $data['users'] = $this->models->user->countAll($filter);
        $data['merchants'] = $this->models->user->countAllMerchants($filter);
        $data['merchants_revenue'] = $merchants_revenue;
        $data['transections'] = $transections;
        $data['commissions'] = $commissions;
        $data['refunded'] = $refunded;
        $data['currencies'] = load_config_one('currencies');

        $this->core->view->make('web/admin/components/dashboard_details.php', $data); 
    }
    
}


