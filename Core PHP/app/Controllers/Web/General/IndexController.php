<?php

namespace App\Controllers\Web\General;

use Quill\Factories\CoreFactory;
use Quill\Factories\ModelFactory;

class IndexController extends \App\Controllers\Web\PublicBaseController {

    function __construct($app = NULL) {

        parent::__construct($app);

        $this->core = CoreFactory::boot(array('View'));
        $this->models = ModelFactory::boot(array('SubscriptionPackage', 'Media', 'MediaVariant', 'AdminUser'));
    }

    public function index() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $offset = $this->request->get('page');
        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));
        $data['products'] = $this->models->media->getAllPlatinum($filter, $order, $offset = '', 30);
        foreach ($data['products'] as $index => $product) {

            $data['products'][$index]['prices'] = $this->models->mediaVariant->getPricesByMediaId($product['id']);
        }
        $data['currencies'] = load_config_one('currencies');
        $data['meta'] = array('title' => 'Tagzie: Supercharged Social Commerce', 'page-name' => 'home');
        $this->core->view->make('web/general/index.php', $data);
    }

    public function newHome() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $offset = $this->request->get('page');
        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));
        $data['products'] = $this->models->media->getAllActive($filter, $order, $offset = '', 30);
        foreach ($data['products'] as $index => $prices) {
            $id = $prices['id'];
            $data['products'][$index]['prices'] = $this->models->mediaVariant->getPricesByMediaId($id);
        }
        $data['currencies'] = load_config_one('currencies');
        $data['meta'] = array('title' => 'Tagzie: Supercharged Social Commerce');
        $data['meta']['page'] = array('name' => 'home');
        $this->core->view->make('web/general/new-home.php', $data);
    }

    public function getProducts() {
        $data['page'] = $this->request->get('page');
        $data['app'] = $this->app->config();
        $offset = ($this->request->get('page') - 1);

        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));

        $products = $this->models->media->getAllActive($filter, $order, $offset, 30);

        $data['products'] = $products;
        foreach ($data['products'] as $index => $prices) {
            $id = $prices['id'];
            $data['products'][$index]['prices'] = $this->models->mediaVariant->getPricesByMediaId($id);
        }
        $data['currencies'] = load_config_one('currencies');
        $this->core->view->make('web/general/components/product_list.php', $data);
    }

    public function terms() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['meta'] = array('title' => 'Terms & Conditions | Tagzie: Supercharged Social Commerce');
        $this->core->view->make('web/general/terms.php', $data);
    }

    public function error() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['meta'] = array('title' => 'Error');
        $this->core->view->make('web/general/error.php', $data);
    }

    public function privacy() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['meta'] = array('title' => 'Privacy Policy | Tagzie: Supercharged Social Commerce');
        $this->core->view->make('web/general/privacy.php', $data);
    }

    public function cookie() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['meta'] = array('title' => 'Cookie | Tagzie: Supercharged Social Commerce');
        $this->core->view->make('web/general/cookie.php', $data);
    }

    public function refund() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['meta'] = array('title' => 'Refund Policy | Tagzie: Supercharged Social Commerce');
        $this->core->view->make('web/general/refund.php', $data);
    }

    public function about() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['meta'] = array('title' => 'About | Tagzie: Supercharged Social Commerce');
        $this->core->view->make('web/general/about.php', $data);
    }

    public function buying() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['meta'] = array('title' => 'Buying | Tagzie: Supercharged Social Commerce');
        $this->core->view->make('web/general/buying.php', $data);
    }

    public function selling() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['subscriptionPackages'] = $this->models->subscriptionPackage->getAllPublic();
        $data['meta'] = array('title' => 'Selling | Tagzie: Supercharged Social Commerce');
        $this->core->view->make('web/general/selling.php', $data);
    }

    public function faq() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['meta'] = array('title' => 'FAQs | Tagzie: Supercharged Social Commerce');
        $this->core->view->make('web/general/faq.php', $data);
    }

    public function contact() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['meta'] = array('title' => 'Contact | Tagzie: Supercharged Social Commerce');
        $this->core->view->make('web/general/contact.php', $data);
    }

    public function howItWorks() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['meta'] = array('title' => 'How It Works | Tagzie: Supercharged Social Commerce');
        $this->core->view->make('web/general/how-it-works.php', $data);
    }

    public function privacySecurity() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $this->core->view->make('web/general/privacy-security.php', $data);
    }

    public function customerFaq() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $this->core->view->make('web/general/customer-faq.php', $data);
    }

    public function merchantFaq() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $this->core->view->make('web/general/merchant-faq.php', $data);
    }

    public function staticFaq() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $this->core->view->make('web/general/static/faq.php', $data);
    }

    public function staticCustomerTerms() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $this->core->view->make('web/general/static/customer-terms-content.php', $data);
    }

    public function staticMerchantTerms() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $this->core->view->make('web/general/static/merchant-terms-content.php', $data);
    }

    public function staticPrivacy() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $this->core->view->make('web/general/static/privacy-content.php', $data);
    }

    public function staticCookie() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $this->core->view->make('web/general/static/cookie-content.php', $data);
    }

    public function staticRefund() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $this->core->view->make('web/general/static/refund-content.php', $data);
    }

    public function staticPrivacySecurity() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $this->core->view->make('web/general/static/privacy-security-content.php', $data);
    }

    public function staticAbout() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $this->core->view->make('web/general/static/about-content-mobile.php', $data);
    }

    public function login() {
        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['data']['instagram_connect_url'] = $this->app->config('instagram_connect_url');
        $data['app'] = $this->app->config();
        $data['meta'] = array('title' => 'Login | Tagzie.com');
        $this->core->view->make('web/account/login.php', $data);
    }

    public function merchantSignup() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['subscriptionPackages'] = $this->models->subscriptionPackage->getAllPublic();
        $data['meta'] = array('title' => 'Merchant Subscriptions Packages | Tagzie.com');

        if ($this->app->user['merchant_deactivate'] == 1) {

            throw new \Quill\Exceptions\BaseException('Access to your merchant account has been restricted, please contact tagzie support for further details.', array(), 403);
        }

        $this->core->view->make('web/account/register/merchant/subscription-plans.php', $data);
    }

    public function merchantSignupChoosePlan($id) {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['data']['instagram_connect_url'] = $this->app->config('instagram_connect_url');
        $data['app'] = $this->app->config();
        $data['package'] = $this->models->subscriptionPackage->getById($id);
        $data['meta'] = array('title' => $data['package']['name'] . ' Package Features | Tagzie.com');
        
        if ($this->app->user['merchant_deactivate'] == 1) {

            throw new \Quill\Exceptions\BaseException('Access to your merchant account has been restricted, please contact tagzie support for further details.', array(), 403);
        }
        
        if (empty($data['package'])) {

            $this->app->slim->notFound();
        }

        $this->core->view->make('web/account/register/merchant/plan-details.php', $data);
    }

    /*
     * Superadmin login
     */

    public function adminLogin() {

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['title'] = 'Admin | Login';

        if (isset($_POST['method']) && $_POST['method'] == 'admin_login') {

            $user_details = $this->models->adminUser->getUserDetailsByUsername($_POST['username']);
            /*
             * Verify password with database
             */
            $verify_password = password_verify($_POST['password'], $user_details['password']);
            if (!empty($user_details) && $verify_password) {
                $_SESSION['user_data']['user_id'] = $user_details['user_id'];
                $_SESSION['user_data']['user_name'] = $user_details['firstname'] . ' ' . $user_details['lastname'];
                $_SESSION['user_data']['email'] = $user_details['email'];
                $this->app->slim->redirect($this->app->config('base_url') . 'admin/dashboard/');
            } else {
                $_SESSION['error_msg'] = 'Invalid username or password!';
            }
        }

        $this->core->view->make('web/admin/index.php', $data);
    }

    public function logout() {

        if (!empty($_COOKIE['token'])) {

            setcookie('token', '', time() - 3600, $this->app->config('path'), $this->app->config('domain'), false, true);
        }

        $this->slim->redirect($this->app->config('base_url') . 'account/login/');
    }

}
