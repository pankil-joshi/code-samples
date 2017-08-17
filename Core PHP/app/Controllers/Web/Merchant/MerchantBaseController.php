<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controllers\Web\Merchant;

use Quill\Factories\ModelFactory;

/**
 * Description of MerchantBaseController
 *
 * @author harinder
 */
class MerchantBaseController extends \App\Controllers\Web\AccountBaseController {

    protected $merchant;

    function __construct($app = NULL) {
        parent::__construct($app);

        if ($this->app->user['is_merchant'] == 0) {

            $this->app->slim->redirect($this->app->config('base_url') . 'account/merchant/signup');
            exit();
        }
        if ($this->app->user['merchant_deactivate'] == 1) {

            throw new \Quill\Exceptions\BaseException('Access to your merchant account has been restricted, please contact tagzie support for further details.', array(), 403);
        }
        $this->models = ModelFactory::boot(array(
                    'MerchantDetails'
        ));

        $this->merchant = $this->models->merchantDetails->getByUserId($this->app->user['id']);
    }

}
