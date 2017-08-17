<?php

/*
 * Whitelist routes, which don't need to be authenticated.
 */
$app->slim->add(new App\Middlewares\AuthenticationMiddleware(array(
    '/api/mobile/users/instagramConnect/',
    '/api/mobile/media/instagramCallback/',
    '/api/mobile/devices/',
    '/api/mobile/general/info',
    '/api/mobile/subscriptions/createStripePlans',
    '/api/mobile/support/contact'
)));

/*
 * Website home page.  
 */
$app->slim->get('/', function () use ($app) {

    $home = new App\Controllers\Web\General\IndexController($app);
    $home->index();
});
/*
 * Website home page.  
 */
$app->slim->get('/error', function () use ($app) {

    $home = new App\Controllers\Web\General\IndexController($app);
    $home->error();
});
/*
 * Website home page.  
 */
$app->slim->get('/blog/', function () use ($app) {
    $appConfig = load_config_one('url');
    $app->slim->redirect($appConfig['base_url']);
});
/*
 * Website home page.  
 */

$app->slim->get('/:instagramUsername/:id', function ($instagramUsername, $id) use ($app) {
    $database = new \Quill\Database();

    $database->setTableName('media');
    $where = array('instagram_username' => $instagramUsername);
    $user = $database->select('id')->where($where)->andOrWhere(array('id' => $id, 'path' => $id))->one();

    if (!empty($user)) {
        $product = new App\Controllers\Web\Product\IndexController($app);
        $product->index($id);
    } else {

        $app->slim->pass();
    }
});
/*
 * Website home page.  
 */
//$app->slim->get('/new-home/', function () use ($app) {
//
//    $home = new App\Controllers\Web\General\IndexController($app);
//    $home->newHome();
//});
/*
 * Website home page.  
 */
$app->slim->get('/get-products-view/', function () use ($app) {

    $home = new App\Controllers\Web\General\IndexController($app);
    $home->getProducts();
});
/*
 * Launch url redirect.  
 */
$app->slim->get('/launch/', function () use ($app) {
    $appConfig = load_config_one('url');
    $app->slim->redirect($appConfig['launch_event_url']);
});

/*
 * About page.
 */
$app->slim->get('/about/', function () use ($app) {

    $home = new App\Controllers\Web\General\IndexController($app);
    $home->about();
});

/*
 * About page.
 */
$app->slim->get('/buying/', function () use ($app) {

    $home = new App\Controllers\Web\General\IndexController($app);
    $home->buying();
});

/*
 * About page.
 */
$app->slim->get('/selling/', function () use ($app) {

    $home = new App\Controllers\Web\General\IndexController($app);
    $home->selling();
});

/*
 * About page.
 */
$app->slim->get('/faq/', function () use ($app) {

    $home = new App\Controllers\Web\General\IndexController($app);
    $home->faq();
});

/*
 * About page.
 */
$app->slim->get('/contact/', function () use ($app) {

    $home = new App\Controllers\Web\General\IndexController($app);
    $home->contact();
});

/*
 * About page.
 */
$app->slim->get('/how-it-works/', function () use ($app) {

    $home = new App\Controllers\Web\General\IndexController($app);
    $home->howItWorks();
});

/*
 * Admin pages group.
 */
$app->slim->group('/admin/', function () use ($app) {
    /*
     * Superadmin login page
     */
    $app->slim->map('login/', function () use ($app) {

        $home = new App\Controllers\Web\General\IndexController($app);
        $home->adminLogin();
    })->via('GET', 'POST');

    /*
     * Logout
     */
    $app->slim->map('logout/', function () use ($app) {

        $home = new App\Controllers\Web\Admin\IndexController($app);
        $home->logout();
    })->via('GET', 'POST');

    /*
     * Route to check country for stripe transaction 
     */

    $app->slim->get('countrySpecs/:country/', function ($country) use ($app) {

        $merchant = new App\Controllers\Api\Mobile\MerchantController($app);
        $merchant->getRequiredFields($country);
    });

    /*
     * Superadmin dashboard page
     */
    $app->slim->get('dashboard/', function () use ($app) {

        $home = new App\Controllers\Web\Admin\IndexController($app);
        $home->adminDashboard();
    });

    $app->slim->group('merchant/', function () use ($app) {
        
        $app->slim->post('', function () use ($app) {

            $merchant = new App\Controllers\Api\Mobile\AdminUserController($app);
            $merchant->updateMerchant();
        });
    });

    $app->slim->group('users/', function () use ($app) {

        /*
         * Superadmin users list
         */
        $app->slim->get('exportToExcel/', function () use ($app) {

            $home = new App\Controllers\Web\Admin\UserController($app);
            $home->exportToExcel();
        });
        /*
         * Dashboard user list view
         */
        $app->slim->map(':id/orders_list_view/', function ($id) use ($app) {

            $home = new App\Controllers\Web\Admin\UserController($app);
            $home->getOrdersListView($id);
        })->via('GET', 'POST');

        /*
         * Dashboard user list view
         */
        $app->slim->map(':id/user_devices_list_view/', function ($id) use ($app) {

            $home = new App\Controllers\Web\Admin\UserController($app);
            $home->getDevicesListView($id);
        })->via('GET', 'POST');

        /*
         * Superadmin users list
         */
        $app->slim->get('', function () use ($app) {

            $home = new App\Controllers\Web\Admin\UserController($app);
            $home->getUsersList();
        });

        $app->slim->group(':id/', function () use ($app) {

            /*
             * Edit user
             */
            $app->slim->map('edit/', function ($id) use ($app) {

                $home = new App\Controllers\Web\Admin\UserController($app);
                $home->editUserDetails($id);
            })->via('GET', 'POST');

            /*
             * Superadmin users list
             */
            $app->slim->get('orders/', function ($id) use ($app) {

                $home = new App\Controllers\Web\Admin\UserController($app);
                $home->getOrders($id);
            });

            /*
             * Superadmin users list
             */
            $app->slim->get('devices/', function ($id) use ($app) {

                $home = new App\Controllers\Web\Admin\UserController($app);
                $home->getDevices($id);
            });
        });
    });

    /*
     * Dashboard details view
     */
    $app->slim->map('dashboard_details_view/', function () use ($app) {

        $home = new App\Controllers\Web\Admin\IndexController($app);
        $home->getAdminDashboardDetails();
    })->via('GET', 'POST');

    /*
     * Dashboard user list view
     */
    $app->slim->map('users_list_view/', function () use ($app) {

        $home = new App\Controllers\Web\Admin\UserController($app);
        $home->getUsersListView();
    })->via('GET', 'POST');

    /*
     * Delete user by id
     */
    $app->slim->map('delete_user/:id', function ($id) use ($app) {

        $home = new App\Controllers\Api\Mobile\AdminUserController($app);
        $home->deleteUser($id);
    })->via('GET', 'POST');

    /*
     * Update user by id
     */
    $app->slim->map('update_user/', function () use ($app) {

        $home = new App\Controllers\Api\Mobile\AdminUserController($app);
        $home->updateUser();
    })->via('GET', 'POST');

    /*
     * Update user by id
     */
    $app->slim->map('cancel_order/:id', function ($id) use ($app) {

        $home = new App\Controllers\Api\Mobile\AdminUserController($app);
        $home->cancelOrder($id);
    })->via('GET', 'POST');

    /*
     * Update user by id
     */
    $app->slim->map('changeSubscription/:id', function ($id) use ($app) {

        $home = new App\Controllers\Api\Mobile\AdminUserController($app);
        $home->changeSubscription($id);
    })->via('GET', 'POST');
});
/*
 * Faq pages group.
 */
$app->slim->group('/faq/', function () use ($app) {

    /*
     * Customer faq page.
     */
    $app->slim->get('customer/', function () use ($app) {

        $home = new App\Controllers\Web\General\IndexController($app);
        $home->customerFaq();
    });

    /*
     * Merchant faq page.
     */
    $app->slim->get('merchant/', function () use ($app) {

        $home = new App\Controllers\Web\General\IndexController($app);
        $home->merchantFaq();
    });
});
/*
 * Privacy & security page for mobile.
 */
$app->slim->get('/privacy-security/', function () use ($app) {

    $home = new App\Controllers\Web\General\IndexController($app);
    $home->privacySecurity();
});
/*
 * Static pages group without header and footer. 
 */
$app->slim->group('/static/', function () use ($app) {

    /*
     * Root level test controller 
     */
    $app->slim->get('privacy-security/', function () use ($app) {

        $home = new App\Controllers\Web\General\IndexController($app);
        $home->staticPrivacySecurity();
    });

    /*
     * Static faq page for mobile.
     */
    $app->slim->get('faq/', function () use ($app) {

        $home = new App\Controllers\Web\General\IndexController($app);
        $home->staticFaq();
    });

    /*
     * Static about page for mobile.
     */
    $app->slim->get('about/', function () use ($app) {

        $home = new App\Controllers\Web\General\IndexController($app);
        $home->staticAbout();
    });

    /*
     * Legal pages group.
     */
    $app->slim->group('legal/', function () use ($app) {

        /*
         * Customer terms page.
         */
        $app->slim->get('customer-terms/', function () use ($app) {

            $test = new App\Controllers\Web\General\IndexController($app);
            $test->staticCustomerTerms();
        });

        /*
         * Merchant terms page.
         */
        $app->slim->get('merchant-terms/', function () use ($app) {

            $test = new App\Controllers\Web\General\IndexController($app);
            $test->staticMerchantTerms();
        });

        /*
         * Privacy policy page. 
         */
        $app->slim->get('privacy/', function () use ($app) {

            $test = new App\Controllers\Web\General\IndexController($app);
            $test->staticPrivacy();
        });

        /*
         * Cookie information page.
         */
        $app->slim->get('cookie/', function () use ($app) {

            $test = new App\Controllers\Web\General\IndexController($app);
            $test->staticCookie();
        });
        /*
         * Refund information page.
         */

        $app->slim->get('refund/', function () use ($app) {

            $test = new App\Controllers\Web\General\IndexController($app);
            $test->staticRefund();
        });
    });
});

/*
 * Static pages with header and footer.
 */
$app->slim->group('/legal/', function () use ($app) {

    /*
     * Terms and conditions page.
     */
    $app->slim->get('terms/', function () use ($app) {

        $test = new App\Controllers\Web\General\IndexController($app);
        $test->terms();
    });

    /*
     * Privacy policy page. 
     */
    $app->slim->get('privacy/', function () use ($app) {

        $test = new App\Controllers\Web\General\IndexController($app);
        $test->privacy();
    });

    /*
     * Cookie information page.
     */
    $app->slim->get('cookie/', function () use ($app) {

        $test = new App\Controllers\Web\General\IndexController($app);
        $test->cookie();
    });

    /*
     * Refund information page.
     */
    $app->slim->get('refund/', function () use ($app) {

        $test = new App\Controllers\Web\General\IndexController($app);
        $test->refund();
    });
});

/*
 * Page for testing.
 */
$app->slim->get('/test/', function () use ($app) {

    $test = new App\Controllers\TestController($app);
    $test->index();
});

/*
 * Client error logging.
 */
$app->slim->post('/client/log/', function () use ($app) {

    $client = new App\Controllers\ClientController($app);
    $client->saveLog();
});

/*
 * Account pages group.
 */
$app->slim->group('/account/', function () use ($app) {

    /*
     * Guest sign up page.
     */
    $app->slim->POST('guest/', function () use ($app) {

        $account = new App\Controllers\Web\Guest\IndexController($app);
        $account->index();
    });

    /*
     * Login page.
     */
    $app->slim->get('login/', function () use ($app) {

        $account = new App\Controllers\Web\General\IndexController($app);
        $account->login();
    });

    /*
     * Logout url.
     */
    $app->slim->get('logout/', function () use ($app) {

        $account = new App\Controllers\Web\General\IndexController($app);
        $account->logout();
    });

    /*
     * Merchant pages group.
     */
    $app->slim->group('merchant/', function () use ($app) {

        /*
         * Merchant dashboard page.
         */
        $app->slim->get('', function () use ($app) {

            $merchant = new App\Controllers\Web\Merchant\IndexController($app);
            $merchant->index();
        });

        /*
         * Merchant order pages group.
         */
        $app->slim->group('orders/', function () use ($app) {

            /*
             * Merchant orders overview page.
             */
            $app->slim->get('overview/', function () use ($app) {

                $merchant = new App\Controllers\Web\Merchant\OrderController($app);
                $merchant->overview();
            });

            /*
             * Merchant orders by customer page.
             */
            $app->slim->get('customers/', function () use ($app) {

                $merchant = new App\Controllers\Web\Merchant\OrderController($app);
                $merchant->customer();
            });

            /*
             * Merchant orders list view to load from ajax.
             */
            $app->slim->get('overviewView/', function () use ($app) {

                $merchant = new App\Controllers\Web\Merchant\OrderController($app);
                $merchant->getOrdersByStatus();
            });

            /*
             * Merchant orders list view by customer to load from ajax.
             */
            $app->slim->get('customerOrdersView/', function () use ($app) {

                $merchant = new App\Controllers\Web\Merchant\OrderController($app);
                $merchant->customerOrdersView();
            });
        });

        /**
         * Merchant messages pages group.
         */
        $app->slim->group('messages/', function () use ($app) {

            /**
             * Merchant enquiries pages.
             */
            $app->slim->get('enquiries/', function () use ($app) {

                $merchant = new App\Controllers\Web\Merchant\MessageController($app);
                $merchant->enquiries();
            });

            /**
             * Merchant enquiries pages.
             */
            $app->slim->get('enquiriesView/', function () use ($app) {

                $merchant = new App\Controllers\Web\Merchant\MessageController($app);
                $merchant->enquiriesView();
            });

            /**
             * Merchant disputes pages.
             */
            $app->slim->get('disputes/', function () use ($app) {

                $merchant = new App\Controllers\Web\Merchant\MessageController($app);
                $merchant->disputes();
            });
            /**
             * Merchant disputes pages.
             */
            $app->slim->get('disputesView/', function () use ($app) {

                $merchant = new App\Controllers\Web\Merchant\MessageController($app);
                $merchant->disputesView();
            });
        });

        /**
         * Merchant product pages group.
         */
        $app->slim->group('products/', function () use ($app) {

            /**
             * Merchant products list page.
             */
            $app->slim->get('', function () use ($app) {

                $merchant = new App\Controllers\Web\Merchant\ProductController($app);
                $merchant->index();
            });

            /**
             * Merchant postage templates page.
             */
            $app->slim->get('productListView/', function () use ($app) {

                $merchant = new App\Controllers\Web\Merchant\ProductController($app);
                $merchant->productListView();
            });
        });

        /**
         * Merchant earnings pages group.
         */
        $app->slim->group('earnings/', function () use ($app) {

            /**
             * Merchant earnings overview page.
             */
            $app->slim->get('overview/', function () use ($app) {

                $merchant = new App\Controllers\Web\Merchant\EarningController($app);
                $merchant->overview();
            });

            /**
             * Merchant settlements page.
             */
            $app->slim->get('settlements/', function () use ($app) {

                $merchant = new App\Controllers\Web\Merchant\EarningController($app);
                $merchant->settlements();
            });

            /**
             * Merchant settlements page.
             */
            $app->slim->get('invoicesView/', function () use ($app) {

                $merchant = new App\Controllers\Web\Merchant\EarningController($app);
                $merchant->invoicesView();
            });
        });

        /**
         * Merchant setting pages group.
         */
        $app->slim->group('settings/', function () use ($app) {

            /**
             * Merchant account setting page.
             */
            $app->slim->map('account/', function () use ($app) {

                $merchant = new App\Controllers\Web\Merchant\SettingController($app);
                $merchant->account();
            })->via('GET', 'POST');

            /**
             * Merchant postage templates page.
             */
            $app->slim->get('postage/', function () use ($app) {

                $merchant = new App\Controllers\Web\Merchant\SettingController($app);
                $merchant->postage();
            });

            /**
             * Merchant postage templates page.
             */
            $app->slim->get('postageOptionListView/', function () use ($app) {

                $merchant = new App\Controllers\Web\Merchant\SettingController($app);
                $merchant->postageOptionListView();
            });
        });

        /**
         * Merchant support pages group.
         */
        $app->slim->group('support/', function () use ($app) {

            /**
             * Merchant help page.
             */
            $app->slim->get('help/', function () use ($app) {

                $merchant = new App\Controllers\Web\Merchant\SupportController($app);
                $merchant->help();
            });
        });

        /**
         * Merchant signup pages group.
         */
        $app->slim->group('signup/', function () use ($app) {

            /**
             * Merchant singup index page.
             */
            $app->slim->get('', function () use ($app) {

                $merchant = new App\Controllers\Web\General\IndexController($app);
                $merchant->merchantSignup();
            });

            /**
             * Merchant signup choose plan page.
             */
            $app->slim->get('choose-plan/:id', function ($id) use ($app) {

                $merchant = new App\Controllers\Web\General\IndexController($app);
                $merchant->merchantSignupChoosePlan($id);
            });

            /**
             * Merchant singup submit details page.
             */
            $app->slim->get('details/:id', function ($id) use ($app) {

                $merchant = new App\Controllers\Web\Customer\IndexController($app);
                $merchant->merchantSignupDetails($id);
            });
        });
    });

    /**
     * Customer pages group.
     */
    $app->slim->group('customer/', function () use ($app) {

        /*
         * Customer dashboard page.
         */
        $app->slim->get('', function () use ($app) {

            $customer = new App\Controllers\Web\Customer\IndexController($app);
            $customer->index();
        });

        /*
         * Customer sign up page.
         */
        $app->slim->map('signup/', function () use ($app) {

            $customer = new App\Controllers\Web\AccountController($app);
            $customer->customerSignup();
        })->via('GET', 'POST');

        /*
         * Customer orders page
         */
        $app->slim->get('orders/', function () use ($app) {

            $customer = new App\Controllers\Web\Customer\OrderController($app);
            $customer->index();
        });

        /*
         * Customer orders list view to load via ajax.
         */
        $app->slim->get('ordersView/', function () use ($app) {

            $customer = new App\Controllers\Web\Customer\OrderController($app);
            $customer->getOrderList();
        });

        /*
         * Customer orders list view to load via ajax on dashboard.
         */
        $app->slim->get('ordersViewDashboard/', function () use ($app) {

            $customer = new App\Controllers\Web\Customer\IndexController($app);
            $customer->getOrderList();
        });

        /*
         * Customer wallet page. 
         */
        $app->slim->get('wallet/', function () use ($app) {

            $customer = new App\Controllers\Web\Customer\WalletController($app);
            $customer->index();
        });

        /*
         * Customer disputes page
         */
        $app->slim->get('disputes/', function () use ($app) {

            $customer = new App\Controllers\Web\Customer\DisputeController($app);
            $customer->index();
        });

        /*
         * Customer dispute list view to load via ajax.
         */
        $app->slim->get('disputesView/', function () use ($app) {

            $customer = new App\Controllers\Web\Customer\DisputeController($app);
            $customer->getDisputeList();
        });

        /*
         * Customer settings page.
         */
        $app->slim->map('settings/', function () use ($app) {

            $customer = new App\Controllers\Web\Customer\SettingController($app);
            $customer->index();
        })->via('GET', 'POST');

        /*
         * Customer addresses page. 
         */
        $app->slim->get('addresses/', function () use ($app) {

            $customer = new App\Controllers\Web\Customer\AddressController($app);
            $customer->index();
        });
    });
});
//
//$app->slim->group('/product/', function () use ($app) {
//
//    $app->slim->get(':id', function ($id) use ($app) {
//
//        $product = new App\Controllers\Web\Product\IndexController($app);
//        $product->index($id);
//    });
//});

$app->slim->group('/checkout/', function () use ($app) {

    $app->slim->map('', function () use ($app) {

        $checkout = new App\Controllers\Web\Checkout\IndexController($app);
        $checkout->index();
    })->via('GET', 'POST');

    $app->slim->post('payment/', function () use ($app) {

        $checkout = new App\Controllers\Web\Checkout\PaymentController($app);
        $checkout->index();
    });

    $app->slim->get('success/:orderId', function ($orderId) use ($app) {

        $checkout = new App\Controllers\Web\Checkout\PaymentController($app);
        $checkout->success($orderId);
    });
});

/*
 * Url redirection to Quill product page. It can be in mobile app or web app 
 */

$app->slim->get('/r/:id', function ($id) use ($app) {

    $urlRedirect = new App\Controllers\UrlRedirect\IndexController($app);
    $urlRedirect->redirect($id);
});

/*
 * Route group for API methods.
 */

$app->slim->group('/api/', function () use ($app) {

    /*
     * Payment options route group. 
     */

    $app->slim->group('payment/', function () use ($app) {

        /*
         * Mobile payment options route group. 
         */

        $app->slim->group('stripe/', function () use ($app) {

            /*
             * Route to create customer and or add card to customer. 
             */

            $app->slim->map('addCard/', function () use ($app) {

                $stripe = new App\Controllers\Api\Payment\StripeController($app);
                $stripe->addCard();
            })->via('GET', 'POST');

            /*
             * Route to list cards of a customer. 
             */

            $app->slim->get('listCard/', function () use ($app) {

                $stripe = new App\Controllers\Api\Payment\StripeController($app);
                $stripe->listCard();
            });

            /*
             * Route to list cards of a customer. 
             */

            $app->slim->post('setDefaultCard/', function () use ($app) {

                $stripe = new App\Controllers\Api\Payment\StripeController($app);
                $stripe->setCustomerDefaultCard();
            });

            /*
             * Route to list cards of a customer. 
             */

            $app->slim->get('getDefaultCard/', function () use ($app) {

                $stripe = new App\Controllers\Api\Payment\StripeController($app);
                $stripe->getCustomerDefaultCard();
            });

            /*
             * Route to list cards of a customer. 
             */

            $app->slim->post('deleteCard/', function () use ($app) {

                $stripe = new App\Controllers\Api\Payment\StripeController($app);
                $stripe->deleteCard();
            });
        });
    });

    /*
     * Route group for Mobile App API methods.
     */

    $app->slim->group('mobile/', function () use ($app) {

        $app->slim->group('general/', function () use ($app) {

            /*
             * Save data of subscription packages.
             */

            $app->slim->post('contact', function () use ($app) {

                $general = new App\Controllers\Api\Mobile\GeneralController($app);
                $general->contactSupport();
            });

            /*
             * Save data of subscription packages.
             */

            $app->slim->get('info', function () use ($app) {

                $general = new App\Controllers\Api\Mobile\GeneralController($app);
                $general->info();
            });
        });

        $app->slim->group('support/', function () use ($app) {

            /*
             * Save data of subscription packages.
             */

            $app->slim->post('ticket/', function () use ($app) {

                $support = new App\Controllers\Api\Mobile\SupportController($app);
                $support->createTicket();
            });

            /*
             * Save data of subscription packages.
             */

            $app->slim->post('contact/', function () use ($app) {

                $support = new App\Controllers\Api\Mobile\SupportController($app);
                $support->contactSupport();
            });
        });

        /*
         * Route group to add/get subscription packages.
         */

        $app->slim->group('subscriptions/', function () use ($app) {

            /*
             * Save data of subscription packages.
             */

            $app->slim->post('', function () use ($app) {

                $subscription = new App\Controllers\Api\Mobile\SubscriptionController($app);
                $subscription->savePackage();
            });

            /*
             * Save data of subscription packages.
             */

            $app->slim->get('', function () use ($app) {

                $subscription = new App\Controllers\Api\Mobile\SubscriptionController($app);
                $subscription->listSubscriptions();
            });

            /*
             * Save data of subscription packages.
             */

            $app->slim->get('createStripePlans', function () use ($app) {

                $subscription = new App\Controllers\Api\Mobile\SubscriptionController($app);
                $subscription->createStripeSubscriptionPlans();
            });
        });

        /*
         * Route group to add/get device info.
         */

        $app->slim->group('devices/', function () use ($app) {

            /*
             * Save data of device.
             */

            $app->slim->post('', function () use ($app) {

                $device = new App\Controllers\Api\Mobile\UserController($app);
                $device->saveDevice();
            });
        });

        /*
         * Route group for user methods. 
         */

        $app->slim->group('users/', function () use ($app) {

            /*
             * Route to get user info. 
             */

            $app->slim->get('', function () use ($app) {

                $user = new App\Controllers\Api\Mobile\UserController($app);
                $user->getUser();
            });

            /*
             * Route to update user info. 
             */

            $app->slim->post('', function () use ($app) {

                $user = new App\Controllers\Api\Mobile\UserController($app);
                $user->saveUser();
            });

            /*
             * Route to deactivate customer. 
             */

            $app->slim->get('deactivate', function () use ($app) {

                $user = new App\Controllers\Api\Mobile\UserController($app);
                $user->deactivateCustomer();
            });
            
            /*
             * Route to deactivate customer. 
             */

            $app->slim->get('deactivateCustomer', function () use ($app) {

                $user = new App\Controllers\Api\Mobile\UserController($app);
                $user->deactivateCustomer();
            });

            /*
             * Route to deactivate customer. 
             */

            $app->slim->get('deactivateMerchant', function () use ($app) {

                $user = new App\Controllers\Api\Mobile\UserController($app);
                $user->deactivateMerchant();
            });            

            /*
             * Route to add postage option. 
             */

            $app->slim->get('postageOptions/:id', function ($id) use ($app) {

                $user = new App\Controllers\Api\Mobile\UserController($app);
                $user->getPostageOption($id);
            });

            /*
             * Route to add postage option. 
             */

            $app->slim->post('postageOptions/:id', function ($id) use ($app) {

                $user = new App\Controllers\Api\Mobile\UserController($app);
                $user->savePostageOption($id);
            });

            /*
             * Route to add postage option. 
             */

            $app->slim->delete('postageOptions/:id', function ($id) use ($app) {

                $user = new App\Controllers\Api\Mobile\UserController($app);
                $user->deletePostageOption($id);
            });

            /*
             * Route to add postage option. 
             */

            $app->slim->get('postageOptions/', function () use ($app) {

                $user = new App\Controllers\Api\Mobile\UserController($app);
                $user->listPostageOptions();
            });
            /*
             * Route to add postage option. 
             */

            $app->slim->post('postageOptions/', function () use ($app) {

                $user = new App\Controllers\Api\Mobile\UserController($app);
                $user->savePostageOption();
            });

            /*
             * Route group for user tag methods. 
             */

            $app->slim->group('tags/', function () use ($app) {

                /*
                 * Route to get list of tags, linked to user authenticated. 
                 */

                $app->slim->get('', function () use ($app) {

                    $tag = new App\Controllers\Api\Mobile\UserController($app);
                    $tag->listTags();
                });

                /*
                 * Route to create new tag, linked to user authenticated. 
                 */

                $app->slim->post('', function () use ($app) {

                    $tag = new App\Controllers\Api\Mobile\UserController($app);
                    $tag->saveTag();
                });

                /*
                 * Route to delete tag, linked to user authenticated. 
                 */

                $app->slim->delete(':id/', function ($id) use ($app) {

                    $tag = new App\Controllers\Api\Mobile\UserController($app);
                    $tag->deleteTag($id);
                });
            });

            /*
             * Route group for user mention methods. 
             */

            $app->slim->group('mentions/', function () use ($app) {

                /*
                 * Route to get list of tags, linked to user authenticated. 
                 */

                $app->slim->get('', function () use ($app) {

                    $mention = new App\Controllers\Api\Mobile\UserController($app);
                    $mention->listMentions();
                });

                /*
                 * Route to create new tag, linked to user authenticated. 
                 */

                $app->slim->post('', function () use ($app) {

                    $mention = new App\Controllers\Api\Mobile\UserController($app);
                    $mention->saveMention();
                });

                /*
                 * Route to delete tag, linked to user authenticated. 
                 */

                $app->slim->delete(':id/', function ($id) use ($app) {

                    $mention = new App\Controllers\Api\Mobile\UserController($app);
                    $mention->deleteMention($id);
                });
            });

            /*
             * Route group for user addresses. 
             */

            $app->slim->group('messages/', function () use ($app) {


                /*
                 * Route to get user info. 
                 */

                $app->slim->get(':id/read/', function ($id) use ($app) {

                    $message = new App\Controllers\Api\Mobile\MessageController($app);
                    $message->markRead($id);
                });


                /*
                 * Route to get user info. 
                 */

                $app->slim->get('recent/', function () use ($app) {

                    $message = new App\Controllers\Api\Mobile\MessageController($app);
                    $message->getAllRecent();
                });

                /*
                 * Route to get user info. 
                 */

                $app->slim->post('thread/', function () use ($app) {

                    $message = new App\Controllers\Api\Mobile\MessageController($app);
                    $message->postMessage();
                });

                /*
                 * Route to get user info. 
                 */

                $app->slim->get('thread/:id', function ($id) use ($app) {

                    $message = new App\Controllers\Api\Mobile\MessageController($app);
                    $message->getThread($id);
                });

                /*
                 * Route to get user info. 
                 */

                $app->slim->post('thread/:id', function ($id) use ($app) {

                    $message = new App\Controllers\Api\Mobile\MessageController($app);
                    $message->postMessage($id);
                });
            });

            /*
             * Route to register and authenticate user using instagram credentials. 
             */

            $app->slim->get('instagramConnect/', function () use ($app) {

                $user = new App\Controllers\Api\Mobile\UserController($app);
                $user->instagramConnect();
            });

            /*
             * Route group for user addresses. 
             */

            $app->slim->group('addresses/', function () use ($app) {

                /*
                 * Route to get user addresses. 
                 */

                $app->slim->get('', function () use ($app) {

                    $user = new App\Controllers\Api\Mobile\UserController($app);
                    $user->listAddresses();
                });

                /*
                 * Route to get user address. 
                 */

                $app->slim->get(':id', function ($id) use ($app) {

                    $user = new App\Controllers\Api\Mobile\UserController($app);
                    $user->getAddress($id);
                });

                /*
                 * Route to get user address. 
                 */

                $app->slim->get(':id/default/', function ($id) use ($app) {

                    $user = new App\Controllers\Api\Mobile\UserController($app);
                    $user->setDefaultAddress($id);
                });

                /*
                 * Route to create user address. 
                 */

                $app->slim->post('', function () use ($app) {

                    $user = new App\Controllers\Api\Mobile\UserController($app);
                    $user->saveAddress();
                });

                /*
                 * Route to update user address. 
                 */

                $app->slim->post(':id', function ($id) use ($app) {

                    $user = new App\Controllers\Api\Mobile\UserController($app);
                    $user->saveAddress($id);
                });

                /*
                 * Route to update user address. 
                 */

                $app->slim->delete(':id', function ($id) use ($app) {

                    $user = new App\Controllers\Api\Mobile\UserController($app);
                    $user->deleteAddress($id);
                });
            });

            /*
             * Route to get user info. 
             */

            $app->slim->get(':id/', function ($id) use ($app) {

                $user = new App\Controllers\Api\Mobile\UserController($app);
                $user->getUser($id);
            });
        });


        /*
         * Route group for product methods. 
         */

        $app->slim->group('media/', function () use ($app) {

            /*
             * Instagram callback route to listen posted data by instagram on new updates done by authenticated users of this app. 
             */

            $app->slim->map('instagramCallback/', function () use ($app) {

                $media = new App\Controllers\Api\Mobile\MediaController($app);
                $media->instagramCallback();
            })->via('GET', 'POST');

            /*
             * Route to get list of all product categories. 
             */

            $app->slim->get('categories/', function () use ($app) {

                $media = new App\Controllers\Api\Mobile\MediaController($app);
                $media->listMediaCategories();
            });

            /*
             * Route to get list of all product categories. 
             */

            $app->slim->get('tagged/', function () use ($app) {

                $media = new App\Controllers\Api\Mobile\MediaController($app);
                $media->listTagged();
            });

            /*
             * Route to get list of all product types. 
             */

            $app->slim->get('types/', function () use ($app) {

                $media = new App\Controllers\Api\Mobile\MediaController($app);
                $media->listMediaTypes();
            });

            /*
             * Route to get list of all product attributes. 
             */

            $app->slim->get('attributes/', function () use ($app) {

                $media = new App\Controllers\Api\Mobile\MediaController($app);
                $media->listMediaAttributes();
            });

            /*
             * Route to get list of products, of user authenticated. 
             */

            $app->slim->get('', function () use ($app) {

                $media = new App\Controllers\Api\Mobile\MediaController($app);
                $media->listMedia();
            });

            /*
             * Route to create new product, linked to user authenticated. 
             */

            $app->slim->post('', function () use ($app) {

                $media = new App\Controllers\Api\Mobile\MediaController($app);
                $media->saveMedia();
            });

            /*
             * Route group for product using id. 
             */

            $app->slim->group(':id/', function () use ($app) {

                /*
                 * Route to get product by product "id", linked to user authenticated. 
                 */

                $app->slim->get('', function ($id) use ($app) {

                    $media = new App\Controllers\Api\Mobile\MediaController($app);
                    $media->getMedia($id);
                });

                /*
                 * Route to update product by product "id", linked to user authenticated. 
                 */

                $app->slim->post('', function ($id) use ($app) {

                    $media = new App\Controllers\Api\Mobile\MediaController($app);
                    $media->saveMedia($id);
                });

                /*
                 * Route to delete product by product "id", linked to user authenticated. 
                 */

                $app->slim->delete('', function ($id) use ($app) {

                    $media = new App\Controllers\Api\Mobile\MediaController($app);
                    $media->delete($id);
                });

                /*
                 * Route to update list variants by product "id", linked to user authenticated. 
                 */

                $app->slim->get('variants/', function ($id) use ($app) {

                    $media = new App\Controllers\Api\Mobile\MediaController($app);
                    $media->listVariants($id);
                });

                /*
                 * Route to update list variants by product "id", linked to user authenticated. 
                 */

                $app->slim->post('report/', function ($id) use ($app) {

                    $media = new App\Controllers\Api\Mobile\MediaController($app);
                    $media->report($id);
                });

                /*
                 * Route to update list variants by product "id", linked to user authenticated. 
                 */

                $app->slim->post('postageOptions/', function ($id) use ($app) {

                    $media = new App\Controllers\Api\Mobile\MediaController($app);
                    $media->listPostageOptionsByMediaId($id);
                });

                /*
                 * Route to update list variants by product "id", linked to user authenticated. 
                 */

                $app->slim->post('tax/', function ($id) use ($app) {

                    $media = new App\Controllers\Api\Mobile\MediaController($app);
                    $media->getTaxRateByMediaId($id);
                });
            });
        });

        /*
         * Route group for order methods. 
         */

        $app->slim->group('orders/', function () use ($app) {

            /*
             * Route to get an order, linked to user authenticated. 
             */

            $app->slim->get('', function ( ) use ($app) {

                $order = new App\Controllers\Api\Mobile\OrderController($app);
                $order->listOrders();
            });

            /*
             * Route to cretae a new order, linked to user authenticated. 
             */

            $app->slim->post('', function () use ($app) {

                $order = new App\Controllers\Api\Mobile\OrderController($app);
                $order->saveOrder();
            });

            /*
             * Route to cretae a new order, linked to user authenticated. 
             */

            $app->slim->get('activity/', function () use ($app) {

                $order = new App\Controllers\Api\Mobile\OrderController($app);
                $order->getActivity();
            });

            /*
             * Route to get an order, linked to user authenticated. 
             */

            $app->slim->get(':id/', function ($id) use ($app) {

                $order = new App\Controllers\Api\Mobile\OrderController($app);
                $order->getOrder($id);
            });

            /*
             * Route to update list variants by product "id", linked to user authenticated. 
             */

            $app->slim->post(':id/rating/', function ($id) use ($app) {

                $order = new App\Controllers\Api\Mobile\OrderController($app);
                $order->saveRating($id);
            });

            /*
             * Route to update list variants by product "id", linked to user authenticated. 
             */

            $app->slim->get(':id/invoice/', function ($id) use ($app) {

                $order = new App\Controllers\Api\Mobile\OrderController($app);
                $order->getInvoice($id);
            });

            /*
             * Route to update list variants by product "id", linked to user authenticated. 
             */

            $app->slim->get(':id/packaging/', function ($id) use ($app) {

                $order = new App\Controllers\Api\Mobile\OrderController($app);
                $order->getPackagingDocuments($id);
            });

            /*
             * Route to get an order, linked to user authenticated. 
             */

            $app->slim->post(':id/cancel/', function ($id) use ($app) {

                $order = new App\Controllers\Api\Mobile\OrderController($app);
                $order->cancel($id);
            });
        });

        /*
         * Route group for order methods. 
         */

        $app->slim->group('merchant/', function () use ($app) {

            /*
             * Merchant dashboard page.
             */
            $app->slim->post('changeSubscription/', function () use ($app) {

                $merchant = new App\Controllers\Api\Mobile\MerchantController($app);
                $merchant->changeSubscription();
            });

            /*
             * Route group payments. 
             */

            $app->slim->group('payments/', function () use ($app) {

                /*
                 * Route to get payment invoice. 
                 */

                $app->slim->get(':id/invoice/', function ($id) use ($app) {

                    $merchant = new App\Controllers\Api\Mobile\MerchantController($app);
                    $merchant->getInvoice($id);
                });
            });
            /*
             * Route to get sale orders list, linked to user authenticated. 
             */

            $app->slim->post('', function () use ($app) {

                $merchant = new App\Controllers\Api\Mobile\MerchantController($app);
                $merchant->saveMerchant();
            });

            /*
             * Route to get sale orders list, linked to user authenticated. 
             */

            $app->slim->post('uploadDocument', function () use ($app) {

                $merchant = new App\Controllers\Api\Mobile\MerchantController($app);
                $merchant->uploadFile();
            });

            /*
             * Route to get sale orders list, linked to user authenticated. 
             */

            $app->slim->post('uploadDocumentAdditionalOwners/:ownerIndex', function ($ownerIndex) use ($app) {

                $merchant = new App\Controllers\Api\Mobile\MerchantController($app);
                $merchant->uploadFileAdditionalOwners($ownerIndex);
            });

            /*
             * Route to get sale orders list, linked to user authenticated. 
             */

            $app->slim->get('', function () use ($app) {

                $merchant = new App\Controllers\Api\Mobile\MerchantController($app);
                $merchant->getMerchant();
            });

            /*
             * Route to get sale orders list, linked to user authenticated. 
             */

            $app->slim->get('countrySpecs/:country/', function ($country) use ($app) {

                $merchant = new App\Controllers\Api\Mobile\MerchantController($app);
                $merchant->getRequiredFields($country);
            });

            /*
             * Route to get sale orders list, linked to user authenticated. 
             */

            $app->slim->get('orders/', function () use ($app) {

                $merchant = new App\Controllers\Api\Mobile\MerchantController($app);
                $merchant->listOrders();
            });

            /*
             * Route to get sale orders list, linked to user authenticated. 
             */

            $app->slim->get('balance/', function () use ($app) {

                $merchant = new App\Controllers\Api\Mobile\MerchantController($app);
                $merchant->getStripeBalance();
            });

            /*
             * Route to get an order, linked to user authenticated. 
             */

            $app->slim->post('orders/:id/ship', function ($id) use ($app) {

                $order = new App\Controllers\Api\Mobile\OrderController($app);
                $order->ship($id);
            });

            /*
             * Route to get an order, linked to user authenticated. 
             */

            $app->slim->post('orders/:id/cancel/', function ($id) use ($app) {

                $order = new App\Controllers\Api\Mobile\MerchantController($app);
                $order->cancel($id);
            });

            /*
             * Route group for order methods. 
             */

            $app->slim->group('reports/', function () use ($app) {

                /*
                 * Route to get popular products, linked to user authenticated. 
                 */

                $app->slim->get('earnings/', function () use ($app) {

                    $merchant = new App\Controllers\Api\Mobile\MerchantController($app);
                    $merchant->getEarnings();
                });

                /*
                 * Route to get popular products, linked to user authenticated. 
                 */

                $app->slim->get('earningsBetweenDates/', function () use ($app) {

                    $merchant = new App\Controllers\Api\Mobile\MerchantController($app);
                    $merchant->getEarningsBetweenDates();
                });

                /*
                 * Route to get popular products, linked to user authenticated. 
                 */

                $app->slim->get('popular/', function () use ($app) {

                    $merchant = new App\Controllers\Api\Mobile\MerchantController($app);
                    $merchant->listPopular();
                });

                /*
                 * Route to get popular products, linked to user authenticated. 
                 */

                $app->slim->get('sales/', function () use ($app) {

                    $merchant = new App\Controllers\Api\Mobile\MerchantController($app);
                    $merchant->salesByDateRange();
                });
            });
        });
    });
});
