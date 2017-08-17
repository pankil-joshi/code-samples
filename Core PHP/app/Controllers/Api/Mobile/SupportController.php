<?php

namespace App\Controllers\Api\Mobile;

use App\Controllers\Api\Mobile\MobileBaseController as MobileBaseController;
use Quill\Factories\CoreFactory;
use Quill\Factories\ServiceFactory;
use Quill\Factories\ModelFactory;
use Quill\Exceptions\BaseException;
use Quill\Exceptions\ValidationException;

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
class SupportController extends MobileBaseController {

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
        $this->services = ServiceFactory::boot(array('EmailNotification', 'OsTickets'));

        $this->app->config(load_config_one('emailTemplates')); //Load email template configuration variable.
    }

    /**
     * @api {post} /api/mobile/support/contact Contact support.
     * @apiName ContactSupport
     * @apiGroup api/mobile/support
     * @apiDescription Use this api to contact support.
     * @apiParamExample {json} Request-Example:
     *     {
     *       "name": "Jhon Murphy",
     *       "email": "test@example.com",
     *       "topic_id" : "1",
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
     *      "message": "Message sent successfully.",
     *    }
     *  }
     */
    public function contactSupport() {

        $logData = array();
        $logData['post'] = $this->jsonRequest;

        $this->userLogger->log('info', 'User requested to contact support.', $this->app->user['id'], $logData);

        $request = $this->jsonRequest;

        if ($auth = jwt_authentication()) {

            $user = $this->models->user->getById($auth['user']['id']);

            $request['name'] = $user['first_name'] . ' ' . $user['last_name'];
            $request['email'] = $user['email'];
        }

        $rules = [
            'required' => [['message'], ['subject'], ['name'], ['email']]
        ];

        $v = new \Quill\Validator($request, array(
            'name',
            'email',
            'topic_id',
            'subject',
            'message'
        ));
        $v->rules($rules);

        if ($v->validate()) {

            $message = $v->sanatized();

            if (empty($message['topic_id'])) {

                $message['topic_id'] = 2;
            }

            $this->services->osTickets->name = $message['name'];
            $this->services->osTickets->email = $message['email'];
            $this->services->osTickets->subject = $message['subject'];
            $this->services->osTickets->message = $message['message'];
            $this->services->osTickets->topicId = $message['topic_id'];

            if (!isset($user) || !$user['is_merchant']) {

                $this->services->osTickets->slaId = '2';
            } else {

                $this->services->osTickets->slaId = $user['subscription_packages_support_level_id'];
            }

            $ticket = $this->services->osTickets->createTicket();

            if ($ticket) {

                $data['ticket'] = $ticket;

                echo $response = $this->core->response->json($data, FALSE);
            }
        }
    }

    /**
     * @api {post} /api/mobile/support/ticket Create a ticket.
     * @apiName CreateTicket
     * @apiGroup api/mobile/support
     * @apiDescription Use this api to create a support ticket.
     * @apiParamExample {json} Request-Example:
     *     {
     *       "message": "Some text",
     *       "subject": "Some text"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *  {
     *      "meta": {
     *      "success": true,
     *      "code": 200
     *    },
     *    "data": {
     *      "message": "Ticket created successfully.",
     *    }
     *  }
     */
    public function createTicket() {

        $logData = array();
        $logData['post'] = $this->jsonRequest;

        $this->userLogger->log('info', 'User requested to create support ticket.', $this->app->user['id'], $logData);

        $request = $this->jsonRequest;

        $rules = [
            'required' => [['message'], ['subject']]
        ];

        $v = new \Quill\Validator($request, array(
            'message',
            'subject'
        ));
        $v->rules($rules);

        if ($v->validate()) {

            $message = $v->sanatized();

            $user = $this->models->user->getById($this->app->user['id']);

            $this->services->osTickets->name = $user['first_name'] . ' ' . $user['last_name'];
            $this->services->osTickets->email = $user['email'];
            $this->services->osTickets->subject = $message['subject'];
            $this->services->osTickets->message = $message['message'];
            $this->services->osTickets->topicId = '12';
            if (!$user['is_merchant']) {

                $this->services->osTickets->slaId = '2';
            } else {

                $this->services->osTickets->slaId = $user['subscription_packages_support_level_id'];
            }
            if (!$user['is_merchant']) {

                $this->services->osTickets->slaId = '2';
            } else {

                $this->services->osTickets->slaId = $user['subscription_packages_support_level_id'];
            }

            $ticket = $this->services->osTickets->createTicket();

            if ($ticket) {

                $data['ticket'] = $ticket;

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
        $data['customer_tutorial'] = $this->app->config('base_assets_url') . 'resources\videos\tagzie-for-customers.mp4';
        $data['merchant_tutorial'] = $this->app->config('base_assets_url') . 'resources\videos\tagzie-for-sellers.mp4';

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
