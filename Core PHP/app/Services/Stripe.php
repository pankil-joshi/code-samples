<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services;

use Quill\Factories\CoreFactory;
use Quill\Exceptions\BaseException;

/**
 * Description of Stripe
 *
 * @author harinder
 */
class Stripe {

    public function __construct() {

        $this->config = load_config_one('stripe');

        \Stripe\Stripe::setApiKey($this->config['stripe_secret_key']);

        // Instantiate core.
        $this->core = CoreFactory::boot(array('Http'));
    }

    public function listCards($stripeCustomerId) {

        $customer = \Stripe\Customer::retrieve($stripeCustomerId);

        $cardList = $customer->sources->all(array(
            "object" => "card"
        ));

        return $cardList;
    }

    public function addCard($token, $stripeCustomerId) {

        $customer = \Stripe\Customer::retrieve($stripeCustomerId);

        try {
            return $customer->sources->create(array('source' => $token));
        } catch (\Stripe\Error\Card $e) {
            // Since it's a decline, \Stripe\Error\Card will be caught
            throw new BaseException($e->getMessage());
        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
            throw new BaseException($e->getMessage());
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
            throw new BaseException($e->getMessage());
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            throw new BaseException($e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
            throw new BaseException($e->getMessage());
        } catch (\Stripe\Error\Base $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            throw new BaseException($e->getMessage());
        } catch (\Exception $e) {
            // Something else happened, completely unrelated to Stripe
            throw new BaseException($e->getMessage());
        }
    }

    public function createCustomer($token, $userId) {

        return \Stripe\Customer::create(array(
                    'source' => $token,
                    'metadata' => array(
                        'user_id' => $userId
                    )
                        )
        );
    }

    public function getCustomer($customerId) {

        return \Stripe\Customer::retrieve($customerId);
    }

    public function setCustomerDefaultCard($customerId, $cardId) {

        $customer = \Stripe\Customer::retrieve($customerId);

        $customer->default_source = $cardId;

        return $customer->save();
    }

    public function deleteCard($customerId, $cardId) {

        $customer = \Stripe\Customer::retrieve($customerId);

        return $customer->sources->retrieve($cardId)->delete();
    }

    public function charge($amount, $currency, $customerId, $cardId, $description = '', $stripeAccountId = '', $appliactionFee = 0) {

        $amountInHunderds = ($amount * 100);
        $appliactionFeeInHunderds = ($appliactionFee * 100);

        return \Stripe\Charge::create(array(
                    'amount' => $amountInHunderds,
                    'currency' => $currency,
                    'application_fee' => $appliactionFeeInHunderds,
                    'destination' => $stripeAccountId,
                    'customer' => $customerId,
                    'card' => $cardId
        ));
    }

    public function refund($stripeChargeId, $amount = 0) {

        $amountInHunderds = ($amount * 100);

        $refund = array(
            'charge' => $stripeChargeId,
            'refund_application_fee' => true,
            'reverse_transfer' => true
        );
        if ($amount > 0) {
            $refund['amount'] = $amountInHunderds;
        }
        return \Stripe\Refund::create($refund);
    }

    public function createPlan($amount, $currency, $interval, $name, $id) {

        $amountInHunderds = ($amount * 100);

        return \Stripe\Plan::create(array(
                    'amount' => $amountInHunderds,
                    'interval' => $interval,
                    'name' => $name,
                    'currency' => $currency,
                    'id' => $id)
        );
    }

    public function createSubscription($stripeCustomerId, $planId, $coupon, $tax_percent) {

        $subscription = array(
            'customer' => $stripeCustomerId,
            'plan' => $planId
        );

        if (!empty($coupon)) {

            $subscription['coupon'] = $coupon;
        }
        
        if (!empty($tax_percent)) {

            $subscription['tax_percent'] = $tax_percent;
        }

        return \Stripe\Subscription::create($subscription);
    }

    public function changeSubscriptionPlan($stripeSubscriptionId, $planId, $coupon) {

        $subscription = \Stripe\Subscription::retrieve($stripeSubscriptionId);
        $subscription->plan = $planId;
        
        if (!empty($coupon)) {

            $subscription->coupon = $coupon;
        }        
        
        return $subscription->save();
    }

    public function cancelSubscription($stripeSubscriptionId) {

        $subscription = \Stripe\Subscription::retrieve($stripeSubscriptionId);

        return $subscription->cancel();
    }

    public function createAccount($options) {

        return \Stripe\Account::create(array(
                    "managed" => true,
                    "country" => $options['country'],
                    "email" => $options['email']
        ));
    }

    public function getAccount($accountId) {

        return \Stripe\Account::retrieve($accountId);
    }

    public function getSubscription($subscriptionId) {

        return \Stripe\Subscription::retrieve($subscriptionId);
    }

    public function getBalance($accountId) {

        return \Stripe\Balance::retrieve(array('stripe_account' => $accountId));
    }

    public function getCountrySpecs($country) {

        return \Stripe\CountrySpec::retrieve($country);
    }
    
    public function getCharge($chargeId) {

        return \Stripe\Charge::retrieve($chargeId);
    }    

    public function uploadFile($file) {

        $file = $this->core->http->createCurlFile(realpath($file), mime_type($file), basename($file));

        $params = array('purpose' => 'identity_document', 'file' => $file);

        return $this->core->http->post('https://uploads.stripe.com/v1/files', $params, array("Content-Type:multipart/form-data"), FALSE, array(
                    'username' => $this->config['stripe_secret_key'],
                    'password' => ''));
    }

}
