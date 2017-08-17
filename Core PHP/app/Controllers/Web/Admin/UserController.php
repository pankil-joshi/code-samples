<?php

namespace App\Controllers\Web\Admin;

use Quill\Factories\CoreFactory;
//use Quill\Factories\ServiceFactory;
use Quill\Factories\ModelFactory;
use Quill\Factories\RepositoryFactory;

class UserController extends \App\Controllers\Web\Admin\AdminBaseController {

    function __construct($app = NULL) {

        parent::__construct($app);

        $this->core = CoreFactory::boot(array('View'));
        $this->models = ModelFactory::boot(array('AdminUser', 'User', 'OrderSuborder', 'SubscriptionPackage', 'Device', 'MediaCategories', 'MerchantDetails'));
        $this->repositories = RepositoryFactory::boot(array('OrderRepository'));
    }

    public function getUsersList() {
        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['title'] = 'Admin | Users list';

        $offset = $this->request->get('page');
        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
        $search = $this->request->get('search');
        if (isset($this->request->get)) {
            $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));
        } else {
            $filter = '';
        }
        $users = $this->models->user->getList($filter, $order, $offset, 10, $search);
        $data['total_users'] = $this->models->user->countAll($filter, $search);

        $data['users'] = $users;
        $data['page'] = 0;

        $this->core->view->make('web/admin/users/list.php', $data);
    }

    public function getUsersListView() {
        $data['page'] = $this->request->get('page');
        $data['app'] = $this->app->config();
        $data['user'] = $this->app->user;
        $offset = ($this->request->get('page') - 1);

        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));

        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));

        $users = $this->models->user->getList($filter, $order, $offset, 10);
        $data['total_users'] = $this->models->user->countAll($filter);
        $data['users'] = $users;
        $this->core->view->make('web/admin/components/users_list_view.php', $data);
    }

    public function editUserDetails($id) {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_user'])) {

            $rules = [
                'regex' => [['first_name', "@^([a-zA-Z.\-/' ])+$@"], ['last_name', "@^([a-zA-Z.\-/' ])+$@"]],
                'email' => [['email']],
                'numeric' => [['mobile_number'], ['age']]
            ];

            $v = new \Quill\Validator($_POST, array('first_name', 'last_name', 'email', 'mobile_number', 'title', 'date_of_birth', 'age', 'country', 'accept_promotional_mails', 'accept_thirdparty_mails', 'mobile_number_prefix'));
            $v->rules($rules);
            $v->rule('check_duplicate', 'email', 'users', 'id', $id)->message('Email already exists.')->label('Email');
            if ($v->validate()) {

                $user = $v->sanatized();

                $user['accept_promotional_mails'] = (!empty($user['accept_promotional_mails'])) ? $user['accept_promotional_mails'] : 0;
                $user['accept_thirdparty_mails'] = (!empty($user['accept_thirdparty_mails'])) ? $user['accept_thirdparty_mails'] : 0;

                $user['id'] = $id;

                if (isset($user['date_of_birth'])) {

                    $user['date_of_birth'] = date('Y-m-d', strtotime($user['date_of_birth']));
                }

                if ($user = $this->models->user->save($user)) {

                    $message['type'] = 'success';
                    $message['text'] = 'Profile details updated successfully.';
                }
            } else {

                $message['type'] = 'error';
                $message['errors'] = $v->errors();
            }

            $data['message'] = $message;
        }

        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['title'] = 'Admin | Edit User';

        $data['countries'] = load_config_one('countries');

        $data['user'] = $this->models->user->getById($id);
        $data['merchant'] = $this->models->merchantDetails->getByUserId($id);
        $data['subscription_plans'] = $this->models->subscriptionPackage->getAll();
        $data['parent_categories'] = $this->models->mediaCategories->getAllParent();

        $this->core->view->make('web/admin/users/edit.php', $data);
    }

    public function getOrders($userId) {
        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['title'] = 'Admin | User orders list';

        $offset = $this->request->get('page');
        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));
        $orders = $this->repositories->orderRepository->getOrderList($userId, $filter, $order, $offset, 10);

        $data['orders'] = $orders;
        $data['total_orders'] = $this->models->orderSuborder->getAllCountByUserId($userId, $filter);
        $data['page'] = 0;
        $data['meta'] = array('page-name' => 'customer-orders');
        $data['user'] = $this->models->user->getById($userId);
        $data['currencies'] = load_config_one('currencies');

        $this->core->view->make('web/admin/users/orders.php', $data);
    }

    public function getOrdersListView($userId) {
        $data['page'] = $this->request->get('page');
        $data['app'] = $this->app->config();
        $data['user'] = $this->app->user;
        $offset = ($this->request->get('page') - 1);

        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));
        $totalOrders = 0;
        if ($this->request->get('status') == 'completed') {

            $orders = $this->repositories->orderRepository->getOrderList($userId, $filter, $order, $offset, 10, 'completed');
            $totalOrders = $this->models->orderSuborder->getAllCountByUserId($userId, $filter, 'completed');
        } elseif ($this->request->get('status') == 'in_progress') {

            $orders = $this->repositories->orderRepository->getOrderList($userId, $filter, $order, $offset, 10, 'in_progress');
            $totalOrders = $this->models->orderSuborder->getAllCountByUserId($userId, $filter, 'in_progress');
        } elseif ($this->request->get('status') == 'tagged') {

            $orders = $this->repositories->orderRepository->getTaggedList($userId, $filter, $order, $offset);
        } else {

            $orders = $this->repositories->orderRepository->getOrderList($userId, $filter, $order, $offset, 10);
            $totalOrders = $this->models->orderSuborder->getAllCountByUserId($userId, $filter);
        }

        $data['orders'] = $orders;

        $data['total_orders'] = $totalOrders;

        $data['currencies'] = load_config_one('currencies');

        $this->core->view->make('web/admin/components/orders_list_view.php', $data);
    }

    public function getDevices($userId) {
        $data['data']['base_url'] = $this->app->config('base_url');
        $data['data']['base_assets_url'] = $this->app->config('base_assets_url');
        $data['app'] = $this->app->config();
        $data['title'] = 'Admin | User devices list';

        $offset = $this->request->get('page');
        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));
        $devices = $this->models->device->getAllByUserId($userId, $filter, $order, $offset, 10);

        $data['devices'] = $devices;
        $data['total_devices'] = $this->models->device->getAllCountByUserId($userId, $filter);
        $data['page'] = 0;
        $data['meta'] = array('page-name' => 'customer-devices');
        $data['user'] = $this->models->user->getById($userId);

        $this->core->view->make('web/admin/users/devices.php', $data);
    }

    public function getDevicesListView($userId) {
        $data['page'] = $this->request->get('page');
        $data['app'] = $this->app->config();

        $offset = ($this->request->get('page') - 1);
        ;

        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));

        $devices = $this->models->device->getAllByUserId($userId, $filter, $order, $offset, 10);

        $data['total_devices'] = $this->models->device->getAllCountByUserId($userId, $filter);

        $data['devices'] = $devices;

        $this->core->view->make('web/admin/components/devices_list_view.php', $data);
    }

    public function exportToExcel() {

        $users = $this->models->user->getList(null, array('order' => 'ASC'), null, 0);
        $countries = load_config_one('countries');


        $objPHPExcel = new \PHPExcel();

        // Set document properties

        $objPHPExcel->getProperties()->setCreator("Tagzie")
                ->setLastModifiedBy("Tagzie")
                ->setTitle("Tagzie users")
                ->setSubject("Tagzie users list")
                ->setDescription("Tagzie users list.")
                ->setKeywords("")
                ->setCategory("");

        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID')
                ->setCellValue('B1', 'Instagram Username')
                ->setCellValue('C1', 'User Type')
                ->setCellValue('D1', 'Name')
                ->setCellValue('E1', 'Email')
                ->setCellValue('F1', 'Mobile Number')
                ->setCellValue('G1', 'Country/Currency Code')
                ->setCellValue('H1', 'Created At (UTC)')
                ->setCellValue('I1', 'Deactivated');
        $column = 2;
        foreach ($users as $index => $user) {

            if ($user['customer_deactivate'] == '1') {

                $status = 'Yes';
            } else {

                $status = 'No';
            }

            $userType = (empty($user['instagram_username'])) ? 'Guest' : '';
            $userType .= ($user['is_active'] == '1') ? 'Customer' : '';
            $userType .= ($user['is_merchant'] == '1') ? '/Merchant' : '';
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $column, $user['id'])
                    ->setCellValue('B' . $column, $user['instagram_username'])
                    ->setCellValue('C' . $column, $userType)
                    ->setCellValue('D' . $column, $user['first_name'] . ' ' . $user['last_name'])
                    ->setCellValue('E' . $column, $user['email'])
                    ->setCellValue('F' . $column, $user['mobile_number'])
                    ->setCellValue('G' . $column, $countries[$user['country']]['name'] . '/' . $user['currency_code'])
                    ->setCellValue('H' . $column, $user['created_at'])
                    ->setCellValue('I' . $column, $status);

            $column++;
        }

        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');


        $objWriter->setPreCalculateFormulas(true);
//        $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
        ob_end_clean();

        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel');
        // It will be called file.xls
        header('Content-Disposition: attachment; filename="Tagzie_users_list.xlsx"');

        $objWriter->save('php://output');
        exit();
    }

}
