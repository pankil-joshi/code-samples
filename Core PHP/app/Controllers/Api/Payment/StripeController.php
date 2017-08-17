<?php

namespace App\Controllers\Api\Payment;

use App\Controllers\Api\ApiBaseController as ApiBaseController;
use Quill\Factories\CoreFactory;
use Quill\Factories\ServiceFactory;
use Quill\Factories\ModelFactory;
use Quill\Exceptions\BaseException;

class StripeController extends ApiBaseController {

    /**
     * Constructor
     * @param object contains app core.
     */
    function __construct($app = NULL) {

        // Call to parent class constructer.
        parent::__construct($app);

        // Instantiate models.
        $this->models = ModelFactory::boot(array('User', 'UserAddress', 'UserStripeCard', 'MerchantDetails', 'Invoice', 'MerchantLedger'));

        // Instantiate services.
        $this->services = ServiceFactory::boot(array('Stripe', 'EmailNotification'));

        $this->app->config(load_config_one('emailTemplates'));
        $this->app->config(load_config_one('url'));

        // Instantiate core classes.
        $this->core = CoreFactory::boot(array('Response', 'View'));
    }

    public function listCard() {

        $cards = $this->models->userStripeCard->getAllbyUserId($this->app->user['id']);

        if (!empty($cards)) {

            $data['cards'] = $cards;

            echo $response = $this->core->response->json($data, FALSE);
        } else {

            throw new BaseException('No cards exist!');
        }
    }

    public function addCard() {

        $config = load_config_one('stripe');

        if ($this->request->isPost() && !empty($this->request->post('stripeToken'))) {

            $token = $this->request->post('stripeToken');

            $stripeCustomerId = $this->models->user->getStripeCustomerId($this->app->user['id']);

            if (empty($stripeCustomerId)) {

                try {

                    $customer = $this->services->stripe->createCustomer($token, $this->app->user['id']);

                    $user['id'] = $this->app->user['id'];
                    $user['stripe_customer_id'] = $customer->id;
                    $stripeCustomerId = $customer->id;

                    $this->models->user->save($user);

                    $card = array();
                    $card['stripe_card_id'] = $customer->sources->data[0]->id;
                    $card['last4'] = $customer->sources->data[0]->last4;
                    $card['brand'] = $customer->sources->data[0]->brand;
                    $card['country'] = $customer->sources->data[0]->country;
                    $card['address_city'] = $customer->sources->data[0]->address_city;
                    $card['address_country'] = $customer->sources->data[0]->address_country;
                    $card['address_line1'] = $customer->sources->data[0]->address_line1;
                    $card['address_line2'] = $customer->sources->data[0]->address_line2;
                    $card['address_state'] = $customer->sources->data[0]->address_state;
                    $card['address_zip'] = $customer->sources->data[0]->address_zip;
                    $card['name'] = $customer->sources->data[0]->name;
                    $card['exp_month'] = $customer->sources->data[0]->exp_month;
                    $card['exp_year'] = $customer->sources->data[0]->exp_year;
                    $card['fingerprint'] = $customer->sources->data[0]->fingerprint;
                    $card['user_id'] = $this->app->user['id'];
                    $card['is_default'] = 1;
                    $this->models->userStripeCard->save($card);

                    $data = array();
                    $data['message'] = 'Card added successfully!';

                    echo $response = $this->core->response->json($data, FALSE);
                } catch (\Exception $e) {

                    if ($e->getMessage() == 'Your card does not support this type of purchase.') {

                        $errorMessage = "Your card does not support this subscription purchase due to one of the following reasons:<br> 

                        - Card may require a PIN to be entered which we currently do not support<br> 
                        - Cross-border restrictions; if your card was not issued in your business's country<br> 
                        - Corporate/FSA card usage may be restricted to certain categories like Travel or Healthcare<br> 

                        Please try again using an alternative card.";
                    } else {

                        $errorMessage = $e->getMessage();
                    }
                    throw new BaseException($errorMessage);
                }
            } else {

                try {

                    $cardObject = $this->services->stripe->addCard($token, $stripeCustomerId);

                    $card = array();
                    $card['stripe_card_id'] = $cardObject->id;
                    $card['last4'] = $cardObject->last4;
                    $card['brand'] = $cardObject->brand;
                    $card['country'] = $cardObject->country;
                    $card['address_city'] = $cardObject->address_city;
                    $card['address_country'] = $cardObject->address_country;
                    $card['address_line1'] = $cardObject->address_line1;
                    $card['address_line2'] = $cardObject->address_line2;
                    $card['address_state'] = $cardObject->address_state;
                    $card['address_zip'] = $cardObject->address_zip;
                    $card['name'] = $cardObject->name;
                    $card['exp_month'] = $cardObject->exp_month;
                    $card['exp_year'] = $cardObject->exp_year;
                    $card['fingerprint'] = $cardObject->fingerprint;
                    $card['user_id'] = $this->app->user['id'];
                    $this->models->userStripeCard->save($card);

                    $data = array();
                    $data['message'] = 'Card added successfully!';

                    echo $response = $this->core->response->json($data, FALSE);
                } catch (\Exception $e) {

                    if ($e->getMessage() == 'Your card does not support this type of purchase.') {

                        $errorMessage = "Your card does not support this subscription purchase due to one of the following reasons:<br> 

                        - Card may require a PIN to be entered which we currently do not support<br> 
                        - Cross-border restrictions; if your card was not issued in your business's country<br> 
                        - Corporate/FSA card usage may be restricted to certain categories like Travel or Healthcare<br> 

                        Please try again using an alternative card.";
                    } else {

                        $errorMessage = $e->getMessage();
                    }
                    throw new BaseException($errorMessage);
                }
            }
        } else {

            $data['stripe_publishable_key'] = $config['stripe_publishable_key'];
            $data['user_default_billing_address'] = $this->models->userAddress->getDefaultBillingByUserId($this->app->user['id']);
            $data['user'] = $this->models->user->getFullNameById($this->app->user['id']);
            $data['base_api_url'] = $this->app->config('base_api_url');

            $this->core->view->make('payment/addCardStripe.php', $data);
        }
    }

    public function setCustomerDefaultCard() {

        $request = $this->jsonRequest;

        $stripeCustomerId = $this->models->user->getStripeCustomerId($this->app->user['id']);

        if (!empty($stripeCustomerId)) {

            try {

                $this->services->stripe->setCustomerDefaultCard($stripeCustomerId, $request['card_id']);
                $card['user_id'] = $this->app->user['id'];
                $card['is_default'] = 0;
                $this->models->userStripeCard->unsetDefault($card);
                $card = array();
                $card['stripe_card_id'] = $request['card_id'];
                $card['is_default'] = 1;
                $this->models->userStripeCard->saveByCardId($card);

                $data['messgae'] = 'Updated successfully!';

                echo $response = $this->core->response->json($data, FALSE);
            } catch (\Exception $ex) {

                throw new BaseException('Error in updating account: ' . $ex->getMessage());
            }
        } else {

            throw new BaseException('No cards exist!');
        }
    }

    public function getCustomerDefaultCard() {

        $request = $this->jsonRequest;

        $stripeCustomerId = $this->models->user->getStripeCustomerId($this->app->user['id']);

        if (!empty($stripeCustomerId)) {

            try {

                $customer = $this->services->stripe->getCustomer($stripeCustomerId);

                $data = array();
                $data['card']['card_id'] = $customer['default_source'];

                echo $response = $this->core->response->json($data, FALSE);
            } catch (\Exception $ex) {

                throw new BaseException('Error in getting customer details: ' . $ex->getMessage());
            }
        } else {

            throw new BaseException('No cards exist!');
        }
    }

    public function deleteCard() {

        $request = $this->jsonRequest;

        $stripeCustomerId = $this->models->user->getStripeCustomerId($this->app->user['id']);

        if (!empty($stripeCustomerId)) {

            try {

                $customer = $this->services->stripe->deleteCard($stripeCustomerId, $request['card_id']);

                $this->models->userStripeCard->deleteByCardId($request['card_id']);
                $data = array();
                $data['card']['card_id'] = $customer['default_source'];

                echo $response = $this->core->response->json($data, FALSE);
            } catch (\Exception $ex) {

                throw new BaseException('Error in deleting card: ' . $ex->getMessage());
            }
        } else {

            throw new BaseException('No cards exist!');
        }
    }

    public function connectCallback() {

        // Retrieve the request's body and parse it as JSON
        $input = @file_get_contents("php://input");
        $logData = array();
        $logData['response'] = json_decode($input);

        $this->app->globalLogger->log('info', 'Data recieved from stripe connect callback.', 'global', $logData);

        if (!empty($input)) {

            $event_json = json_decode($input);

            if ($event_json->type == 'account.updated' || $event_json->type == 'transfer.paid') {
                $account = $event_json->data->object;
                $merchantDetails = $this->models->merchantDetails->getByStripeAccountId($account->id);

                if (!empty($merchantDetails['user_id'])) {

                    if ($event_json->type == 'account.updated') {

                        $merchant = array();
                        $merchant['user_id'] = $merchantDetails['user_id'];
                        $merchant['stripe_charges_enabled'] = $account->charges_enabled;
                        $merchant['stripe_transfers_enabled'] = $account->transfers_enabled;
                        $merchant['stripe_transfers_disabled_reason'] = $account->verification->disabled_reason;
                        $merchant['stripe_fields_needed'] = implode(',', $account->verification->fields_needed);
                        $merchant['stripe_fields_needed_due_by'] = $account->verification->due_by;
                        $merchant['stripe_legal_entity_verification_details'] = $account->legal_entity->verification->details;
                        $merchant['stripe_legal_entity_verification_status'] = $account->legal_entity->verification->status;

                        $this->models->merchantDetails->save($merchant);
                    } elseif ($event_json->type == 'transfer.paid') {

                        $entry = array();
                        $entry['amount'] = ($account->amount / 100);
                        $entry['flow'] = 'out';
                        $entry['description'] = 'Withdrawal to bank.';
                        $entry['type_code'] = 'withdrawal';
                        $entry['currency_code'] = $merchantDetails['merchant_business_currency'];
                        $entry['user_id'] = $merchantDetails['user_id'];

                        $this->models->merchantLedger->save($entry);
                    } elseif ($event_json->type == 'transfer.failed') {

                        $entry = array();
                        $entry['amount'] = ($account->amount / 100);
                        $entry['flow'] = 'in';
                        $entry['description'] = 'Withdrawal to bank.';
                        $entry['type_code'] = 'withdrawal_reversed';
                        $entry['currency_code'] = $merchantDetails['merchant_business_currency'];
                        $entry['user_id'] = $merchantDetails['user_id'];

                        $this->models->merchantLedger->save($entry);
                    }
                } else {
                    echo 'Stripe Account doesn\'t exist';
                    http_response_code(500);
                }
            }
        }
    }

    public function callback() {

        // Retrieve the request's body and parse it as JSON
        $input = @file_get_contents("php://input");
        $logData = array();
        $logData['response'] = json_decode($input);

        $this->app->globalLogger->log('info', 'Data recieved from stripe account callback.', 'global', $logData);

        if (!empty($input)) {

            $event_json = json_decode($input);

            if (!empty($event_json->type) && $event_json->type == 'invoice.created' || $event_json->type == 'invoice.updated' || $event_json->type == 'invoice.payment_failed' || $event_json->type == 'invoice.payment_succeeded' || $event_json->type == 'customer.subscription.updated') {

                $merchant = $this->models->user->getByStripeSubscriptionId((!empty($event_json->data->object->subscription)) ? $event_json->data->object->subscription : $event_json->data->object->id);

                if (empty($merchant)) {

                    throw new BaseException('Subscription not found.');
                }
                if ($event_json->type == 'invoice.created') {

                    $stripeInvoice = $event_json->data->object;
                    $item = array();

                    foreach ($stripeInvoice->lines->data as $lineItem) {

                        $item['subscription_package_name'] = $lineItem->plan->name;
                        $item['current_period_start'] = $lineItem->period->start;
                        $item['current_period_end'] = $lineItem->period->end;
                        $item['amount'] = ($lineItem->amount / 100);
                        $items[] = $item;
                    }

                    $invoice['items'] = serialize($items);
                    $invoice['amount'] = ($stripeInvoice->total / 100);
                    $invoice['subtotal'] = ($stripeInvoice->subtotal / 100);
                    $invoice['stripe_invoice_id'] = $stripeInvoice->id;
                    $invoice['stripe_event'] = $input;
                    $invoice['discount'] = (!empty($stripeInvoice->discount->coupon->amount_off)) ? ($stripeInvoice->discount->coupon->amount_off / 100) : 0;
                    $invoice['type'] = 'subscription';
                    $invoice['status'] = 'unpaid';
                    $invoice['user_id'] = $merchant['id'];
                    $invoice['tax'] = ((int) $stripeInvoice->tax / 100);

                    $invoice = $this->models->invoice->save($invoice);
                } elseif ($event_json->type == 'invoice.payment_succeeded') {

                    $stripeInvoice = $event_json->data->object;

                    $invoice = array();
                    $invoice['stripe_invoice_id'] = $stripeInvoice->id;
                    $invoice['status'] = 'paid';
                    $invoice['attempt_count'] = $stripeInvoice->attempt_count;
                    $invoice['next_payment_attempt'] = $stripeInvoice->next_payment_attempt;

                    $invoice = $this->models->invoice->updateByInvoiceId($invoice);


                    if (!empty($invoice)) {

                        $invoice['items'] = unserialize($invoice['items']);

                        if (file_exists($this->app->config('tagzie_invoices_directory') . '/TGZ-' . $invoice['id'] . '.pdf')) {

                            unlink($this->app->config('tagzie_invoices_directory') . '/TGZ-' . $invoice['id'] . '.pdf');
                        }

                        $data['invoice'] = $invoice;
                        $data['merchant'] = $merchant;
                        $data['countries'] = load_config_one('countries');
                        $data['currencies'] = load_config_one('currencies');

                        $app = load_config_one('app', array('path'));
                        $url = load_config_one('url');
                        $data['app'] = array_merge($app, $url);
                        $dompdf = new \Dompdf\Dompdf();
                        $dompdf->set_option('isHtml5ParserEnabled', true);
                        $dompdf->set_option('isRemoteEnabled', true);
                        $dompdf->set_option('defaultFont', 'Ubuntu');
                        $dompdf->loadHtml($this->core->view->make('pdf/subscription-invoice.php', $data, false));
                        $dompdf->render();
                        $pdf = $dompdf->output();
                        file_put_contents($this->app->config('tagzie_invoices_directory') . '/TGZ-' . $invoice['id'] . '.pdf', $pdf);

                        if ($stripeInvoice->total > 0) {
                            $app = array(
                                'base_assets_url' => $this->app->config('base_assets_url'),
                                'app_title' => $this->app->config('app_title'),
                                'master_hashtag' => $this->app->config('master_hashtag'),
                                'feedback_email' => $this->app->config('feedback_email'),
                                'sales_email' => $this->app->config('sales_email'),
                                'base_url' => $this->app->config('base_url'),
                                'support_email' => $this->app->config('support_email'),
                                'instagram_account_url' => $this->app->config('instagram_account_url'),
                                'twitter_account_url' => $this->app->config('twitter_account_url'),
                                'support_phone_uk' => $this->app->config('support_phone_uk'),
                                'support_phone_int' => $this->app->config('support_phone_int')
                            );
                            $attachments = array();
                            $attachments[] = array('path' => $this->app->config('tagzie_invoices_directory') . '/TGZ-' . $invoice['id'] . '.pdf', 'name' => 'TGZ-' . $invoice['id'] . '.pdf');
                            $this->services->emailNotification->sendMail(array('email' => $merchant['merchant_business_email'], 'name' => $merchant['first_name'] . ' ' . $merchant['last_name']), $this->app->config('subscription_payment_success'), $this->core->view->make('email/subscription-payment.php', array('user' => $merchant, 'app' => $app, 'invoice' => $invoice, 'currencies' => load_config_one('currencies')), false), $attachments);
                        }
                    } else {

                        echo 'Invoice doesn\'t exist';

                        http_response_code(500);
                    }
                } elseif ($event_json->type == 'invoice.payment_failed') {

                    $stripeInvoice = $event_json->data->object;

                    $invoice = array();
                    $invoice['stripe_invoice_id'] = $stripeInvoice->id;
                    $invoice['status'] = 'unpaid';
                    $invoice['attempt_count'] = $stripeInvoice->attempt_count;
                    $invoice['next_payment_attempt'] = $stripeInvoice->next_payment_attempt;

                    $invoice = $this->models->invoice->updateByInvoiceId($invoice);
                    if (!empty($invoice)) {
                        $invoice['items'] = unserialize($invoice['items']);

                        if (file_exists($this->app->config('tagzie_invoices_directory') . '/TGZ-' . $invoice['id'] . '.pdf')) {

                            unlink($this->app->config('tagzie_invoices_directory') . '/TGZ-' . $invoice['id'] . '.pdf');
                        }

                        $data['invoice'] = $invoice;
                        $data['merchant'] = $merchant;
                        $data['countries'] = load_config_one('countries');
                        $data['currencies'] = load_config_one('currencies');

                        $app = load_config_one('app', array('path'));
                        $url = load_config_one('url');
                        $data['app'] = array_merge($app, $url);
                        $dompdf = new \Dompdf\Dompdf();
                        $dompdf->set_option('isHtml5ParserEnabled', true);
                        $dompdf->set_option('isRemoteEnabled', true);
                        $dompdf->set_option('defaultFont', 'Ubuntu');
                        $dompdf->loadHtml($this->core->view->make('pdf/subscription-invoice.php', $data, false));
                        $dompdf->render();
                        $pdf = $dompdf->output();
                        file_put_contents($this->app->config('tagzie_invoices_directory') . '/TGZ-' . $invoice['id'] . '.pdf', $pdf);

                        if ($stripeInvoice->total > 0) {
                            $app = array(
                                'base_assets_url' => $this->app->config('base_assets_url'),
                                'app_title' => $this->app->config('app_title'),
                                'master_hashtag' => $this->app->config('master_hashtag'),
                                'feedback_email' => $this->app->config('feedback_email'),
                                'sales_email' => $this->app->config('sales_email'),
                                'base_url' => $this->app->config('base_url'),
                                'support_email' => $this->app->config('support_email'),
                                'instagram_account_url' => $this->app->config('instagram_account_url'),
                                'twitter_account_url' => $this->app->config('twitter_account_url'),
                                'support_phone_uk' => $this->app->config('support_phone_uk'),
                                'support_phone_int' => $this->app->config('support_phone_int')
                            );
                            $attachments = array();
                            $attachments[] = array('path' => $this->app->config('tagzie_invoices_directory') . '/TGZ-' . $invoice['id'] . '.pdf', 'name' => 'TGZ-' . $invoice['id'] . '.pdf');
                            $this->services->emailNotification->sendMail(array('email' => $merchant['merchant_business_email'], 'name' => $merchant['first_name'] . ' ' . $merchant['last_name']), $this->app->config('subscription_payment_failed'), $this->core->view->make('email/subscription-payment.php', array('user' => $merchant, 'app' => $app, 'invoice' => $invoice, 'currencies' => load_config_one('currencies')), false), $attachments);
                        }
                    } else {

                        echo 'Invoice doesn\'t exist';

                        http_response_code(500);
                    }
                } elseif ($event_json->type == 'customer.subscription.updated') {

                    $subscription = $event_json->data->object;

                    $merchant = array();
                    $merchant['stripe_subscription_id'] = $subscription->id;
                    $merchant['current_period_start'] = $subscription->current_period_start;
                    $merchant['current_period_end'] = $subscription->current_period_end;
                    $merchant['subscription_package_id'] = $subscription->plan->id;
                    $merchant['subscription_status'] = ($subscription->status == 'active' || $subscription->status == 'trialing') ? 1 : 0;
                    $merchant['stripe_subscription_status'] = $subscription->status;

                    $this->models->merchantDetails->save($merchant);
                }
            }
        }
    }

}
