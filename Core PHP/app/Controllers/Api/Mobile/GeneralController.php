<?php

namespace App\Controllers\Api\Mobile;

use App\Controllers\Api\Mobile\MobileBaseController as MobileBaseController;
use Quill\Factories\CoreFactory;
use Quill\Factories\ServiceFactory;
use Quill\Factories\ModelFactory;

/**
 * General Controller, contains all general methods.
 * 
 * @package App\Controllers\Api\Mobile
 * @author Pankil Joshi <pankil@prologictechnologies.in>
 * @version 1.0
 * @uses Quill\Factories\CoreFactory
 * @uses Quill\Factories\ServiceFactory
 * @uses Quill\Factories\ModelFactory
 * @uses Quill\Exceptions\BaseException
 * @uses Quill\Exceptions\ValidationException;
 * @extend App\Controllers\Api\Mobile\MobileBaseController
 */
class GeneralController extends MobileBaseController {

    function __construct($app = NULL) {

        parent::__construct($app);

        /**
         * Required model classes instantiated.
         */
        $this->models = ModelFactory::boot(array(
                    'User',
                    'CurrencyRate'
        ));

        /**
         * Required core classes instantiated.
         */
        $this->core = CoreFactory::boot(array('Response', 'View', 'Http'));

        /**
         * Required services classes instantiated.
         */
        $this->services = ServiceFactory::boot(array('EmailNotification'));

        $this->app->config(load_config_one('emailTemplates')); //Load email template configuration variable.
    }

    /**
     * @api {post} /api/mobile/general/contact Contact support.
     * @apiName ContactSupport
     * @apiGroup api/mobile/general
     * @apiDescription Use this api to contact support.
     * @apiParamExample {json} Request-Example:
     *     {
     *       "subject": "Some text",
     *       "message": "Some text"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *  {
     *      "meta": {
     *      "success": true,
     *      "code": 200
     *    },
     *    "data": {
     *      "message": "Mail sent successfully.",
     *    }
     *  }
     */
    public function contactSupport() {

        $logData = array();
        $logData['post'] = $this->jsonRequest;

        $this->userLogger->log('info', 'User requested to contact support.', $this->app->user['id'], $logData);

        $request = $this->jsonRequest;

        $rules = [
            'required' => [['message']]
        ];

        $v = new \Quill\Validator($request, array(
            'message'
        ));
        $v->rules($rules);

        if ($v->validate()) {

            $message = $v->sanatized();

            $user = $this->models->user->getById($this->app->user['id']);

            $mail = $this->services->emailNotification->sendMail(array('email' => $this->app->config('feedback_email'), 'name' => $this->app->config('app_title'), 'reply_to_email' => $user['email'], 'reply_to_name' => $user['first_name'] . ' ' . $user['last_name']), sprintf($this->app->config('support_email_subject'), $user['instagram_username'], gmdate('Y-m-d H:i:s z')), $this->core->view->make('email/contact-support.php', array('user' => $user, 'data' => $message), false));

            if ($mail) {

                $data['message'] = 'Mail sent successfully.';

                echo $response = $this->core->response->json($data, FALSE);
            }
        }
    }

    /**
     * @api {get} /api/mobile/general/info App info.
     * @apiName AppInfo
     * @apiGroup api/mobile/general
     * @apiDescription Use this api endpoint to get general info about the application.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *  {
     *      "meta": {
     *      "success": true,
     *      "code": 200
     *    },
     *    "data": {
     *      "app_title": "Tagzie",
     *      "feedback_email": "feedback@tagzie.com",
     *      "support_email": "support@tagzie.com",
     *      "sales_email": "sales@tagzie.com",
     *      "support_phone_1": null,
     *      "support_phone_2": null,
     *      "office_address": ""
     *    }
     *  }
     */
    public function info() {

        $data['app_title'] = $this->app->config('app_title');
        $data['feedback_email'] = $this->app->config('feedback_email');
        $data['support_email'] = $this->app->config('support_email');
        $data['sales_email'] = $this->app->config('sales_email');
        $data['support_phone_1'] = $this->app->config('support_phone_1');
        $data['support_phone_2'] = $this->app->config('support_phone_2');
        $data['office_address'] = $this->app->config('office_address');
        $data['customer_tutorial'] = $this->app->config('base_assets_url') . 'resources/videos/android-tagzie-for-customers.mp4';
        $data['merchant_tutorial'] = $this->app->config('base_assets_url') . 'resources/videos/android-tagzie-for-sellers.mp4';
        $data['ios_customer_tutorial'] = $this->app->config('base_assets_url') . 'resources/videos/ios-tagzie-for-customers.mp4';
        $data['ios_merchant_tutorial'] = $this->app->config('base_assets_url') . 'resources/videos/ios-tagzie-for-sellers.mp4';
        echo $response = $this->core->response->json($data, FALSE);
    }
    
    public function updateCurrencies() {
        $urlConfig = load_config_one('url');
        $openexchangerates = $this->core->http->get($urlConfig['open_exchange_rate_url']);

        $openexchangerates = json_decode($openexchangerates, true);
        
        foreach ($openexchangerates['rates'] as $currencyCode => $rate) {
            
            $currency['currency_code'] = $currencyCode;
            $currency['conversion_rate_usd_base'] = $rate;
            
            $this->models->currencyRate->updateRate($currency);
        }
    }    

}
