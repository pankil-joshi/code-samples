<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services;

use Quill\Factories\ServiceFactory;
use Quill\Exceptions\BaseException;
/**
 * Description of FInancialTransaction
 *
 * @author harinder
 */
class Transaction {

//    public $mode, $attributes, $amount, $currency_code, $user_id, $status;


    public function __construct() {

        // Instantiate models.
        $this->financialTransaction = new \App\Models\FinancialTransaction();
        
        // Instantiate services.
        $this->services = ServiceFactory::boot(array('Stripe'));
    }

    public function newOrder() {

        $transaction['type_code'] = 'new_order';
        $transaction['mode'] = $this->mode;
        $transaction['attributes'] = $this->attributes;
        $transaction['amount'] = $this->amount;
        $transaction['flow'] = 'in';
        $transaction['currency_code'] = $this->currency_code;
        $transaction['user_id'] = $this->user_id;
        $transaction['status'] = 'pending';
        $transaction['user_type'] = 'customer';

        return $this->financialTransaction->save($transaction);
    }

    public function orderCancelled() {

        $transaction['type_code'] = 'order_cancelled';
        $transaction['mode'] = $this->mode;
        $transaction['attributes'] = $this->attributes;
        $transaction['amount'] = $this->amount;
        $transaction['flow'] = 'out';
        $transaction['currency_code'] = $this->currency_code;
        $transaction['user_id'] = $this->user_id;
        $transaction['status'] = 'pending';
        $transaction['user_type'] = 'customer';

        return $this->financialTransaction->save($transaction);
    }

    public function completed($id, $referenceKeyName, $referenceId, $response) {

        $transaction['id'] = $id;
        $transaction['status'] = 'completed';
        $transaction[$referenceKeyName] = $referenceId;
        $transaction['stripe_response'] = serialize($response->__toArray(true));

        return $this->financialTransaction->save($transaction);
    }

    public function failed($id) {

        $transaction['id'] = $id;
        $transaction['status'] = 'failed';

        return $this->financialTransaction->save($transaction);
    }

    public function refund($id, $amount = 0) {

        $transaction = $this->financialTransaction->getById($id);
        $this->mode = $transaction['mode'];
        $this->amount = $transaction['amount'];
        $this->currency_code = $transaction['currency_code'];
        $this->attributes = serialize(array(
            'transaction_id' => $transaction['id'],
            'stripe' => array(
                'charge_id' => $transaction['stripe_charge_id']
            )
        ));

        $this->user_id = $transaction['user_id'];

        $transactionId = $this->orderCancelled();

        try {

            $refund = $this->services->stripe->refund(
                    $transaction['stripe_charge_id'], $amount
            );

            if (strcmp($refund->status, 'succeeded') === 0) {

                $this->completed($transactionId['id'], 'stripe_refund_id', $refund->id, $refund);
            }
            return $transactionId;
        } catch (\Exception $ex) {
            $this->failed($transactionId['id']);
            throw new BaseException('Error in processing refund: ' . $ex->getMessage());
        }
    }

}
