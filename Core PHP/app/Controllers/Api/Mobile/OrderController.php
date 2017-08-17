<?php

namespace App\Controllers\Api\Mobile;

use App\Controllers\Api\Mobile\MobileBaseController as MobileBaseController;
use Quill\Factories\CoreFactory;
use Quill\Factories\ServiceFactory;
use Quill\Factories\ModelFactory;
use Quill\Factories\RepositoryFactory;
use Quill\Exceptions\BaseException;

class OrderController extends MobileBaseController {

    /**
     * Constructor
     * @param object contains app core.
     */
    function __construct($app = NULL) {

        // Call to parent class constructer.
        parent::__construct($app);

        // Instantiate models.
        $this->models = ModelFactory::boot(array(
                    'User',
                    'Media',
                    'Tag',
                    'Device',
                    'UserAddress',
                    'Order',
                    'OrderSuborder',
                    'OrderItem',
                    'MediaVariant',
                    'MediaPostageOptions',
                    'ItemTax',
                    'Promotion',
                    'MediaStockItem',
                    'MerchantLedger',
                    'MediaVariantOption',
                    'OrderItem',
                    'MerchantDetails',
                    'MediaTax',
                    'Comment',
                    'OrderRating',
                    'SubscriptionPackage',
                    'MessagesThreadDetails',
                    'MediaAttributeValue',
                    'CurrencyRate',
                    'UserStripeCard'
        ));

        // Instantiate core classes.
        $this->core = CoreFactory::boot(array('Response', 'Http', 'View'));

        // Instantiate services.
        $this->services = ServiceFactory::boot(array('Jwt', 'Stripe', 'Transaction', 'EmailNotification'));

        $this->app->config(load_config_one('emailTemplates'));

        $this->repositories = RepositoryFactory::boot(array('OrderRepository', 'MessageRepository'));
    }

    /**
     * Create order, add order items and try to process payment. 
     * If payment successfull then set order status to "in_progress" and, deduct ordered quantity from the available stock. 
     * On payment failure return json error.
     * 
     * @throws Exception
     */
    public function saveOrder() {


        $_order = $this->jsonRequest;

        $orderTotal = 0;
        $orderSubtotal = 0;
        $orderDiscount = 0;
        $orderTax = 0;
        $itemBasetotal = 0;
        $orderPostage = 0;
        $this->items = array();
        $this->suborderItems = array();

        $this->set('userId', $this->app->user['id']);
        $this->set('postageOptionId', $_order['postage_option_id']);
        $this->set('paymentOption', $_order['payment_option']);

        if (!empty($_order['comment_id'])) {

            $this->set('commentId', $_order['comment_id']);
        } else {

            $this->set('commentId', null);
        }
        $this->customer = $this->models->user->getById($this->app->user['id']);
        $this->deliveryAddress = $this->models->userAddress->getById($_order['delivery_address_id'], $this->app->user['id']);

        /*
         * Find out multiple entries of same media_id, varient_id and merge it.
         */
        foreach ($_order['items'] as $item) {

            $key = $item['media_id'] . '-' . $item['variant_id'];

            if (array_key_exists($key, $this->items)) {

                $this->items[$key]['quantity'] += $item['quantity'];
            } else {

                $this->items[$key] = $item;
            }
        }

        foreach ($this->items as $item) {

            $this->suborders[$this->models->mediaVariant->getMerchantId($item['variant_id'])]['items'][] = $item;
            $this->merchantDetails = (empty($this->merchantDetails)) ? $this->models->user->getById($this->models->mediaVariant->getMerchantId($item['variant_id'])) : $this->merchantDetails;
        }

        foreach ($this->suborders as $suborderIndex => $suborder) {

            $subOrderSubtotal = 0;
            $subOrderDiscount = 0;
            $subOrderTax = 0;
            $subOrderTotal = 0;

            foreach ($suborder['items'] as $itemIndex => $item) {

                /*
                 * Check if item is still in stock, if yes then prepare and set different order variables.
                 */
                if ($this->models->mediaVariant->getQuantity($item['variant_id']) >= $item['quantity'] && $this->models->media->getAvailability($item['media_id'])) {

                    if ($this->models->mediaVariant->getMerchantId($item['variant_id']) == $this->app->user['id']) {

                        throw new BaseException('You can not purchase your own product!');
                    }

                    $this->suborders[$suborderIndex]['items'][$itemIndex]['merchant_id'] = $this->models->mediaVariant->getMerchantId($item['variant_id']);

                    $getPrice = ($this->models->mediaTax->getByMediaId($item['media_id'])['inclusive']) ? getExclusiveAmount($this->models->mediaVariant->getPrice($item['variant_id']), $this->models->mediaTax->getRateByMediaId($item['media_id'])) : $this->models->mediaVariant->getPrice($item['variant_id']);

                    $itemBasetotal = $this->getMoney(( $getPrice * $item['quantity']));

                    $this->set('itemBasetotal', $itemBasetotal);

                    $subOrderSubtotal += $itemBasetotal;

                    $this->suborders[$suborderIndex]['items'][$itemIndex]['row_basetotal'] = $itemBasetotal;
                    $this->suborders[$suborderIndex]['items'][$itemIndex]['price'] = $getPrice;
                    $this->suborders[$suborderIndex]['items'][$itemIndex]['row_discount'] = $this->getItemDiscount($item['variant_id']);

                    $subOrderDiscount += $this->getItemDiscount($item['variant_id']);

                    $this->suborders[$suborderIndex]['items'][$itemIndex]['row_tax_rate'] = $this->getItemTaxRate($item['variant_id'], $item['media_id']);

                    $this->suborders[$suborderIndex]['items'][$itemIndex]['row_tax'] = $this->getItemTaxAfterDiscount($item['variant_id'], $item['media_id']);

                    $this->suborders[$suborderIndex]['items'][$itemIndex]['row_tax_inclusive'] = $this->models->mediaTax->getByMediaId($item['media_id'])['inclusive'];
                    $subOrderTax += $this->getItemTaxAfterDiscount($item['variant_id'], $item['media_id']);

                    $this->suborders[$suborderIndex]['items'][$itemIndex]['row_total'] = $this->getItemTotalAfterTax($item['variant_id'], $item['media_id']);

                    $subOrderTotal += $this->getItemTotalAfterTax($item['variant_id'], $item['media_id']);

                    $this->mediaId = $item['media_id'];
                } else {

                    throw new BaseException('One or more items not available anymore!');
                }
            }

            $postageOptions = $this->models->mediaPostageOptions->getAllIdsByMediaIdUserAddress($this->mediaId, $this->deliveryAddress);

            if (in_array($this->get('postageOptionId'), $postageOptions)) {

                $orderPostage = $this->models->mediaPostageOptions->getRate($this->get('postageOptionId'));
            } else {

                throw new BaseException('Invalid postage option selected.');
            }


            $this->suborders[$suborderIndex]['order_subtotal'] = $subOrderSubtotal;
            $this->suborders[$suborderIndex]['order_total'] = $subOrderTotal + $orderPostage;
            $this->suborders[$suborderIndex]['order_tax'] = $subOrderTax;
            $this->suborders[$suborderIndex]['order_discount'] = $subOrderDiscount;
            $this->suborders[$suborderIndex]['order_postage_option_id'] = $this->get('postageOptionId');
            $this->suborders[$suborderIndex]['order_postage_option_label'] = $this->getOrderPostageOptionLabel();
            $this->suborders[$suborderIndex]['order_postage'] = $orderPostage;
            $this->suborders[$suborderIndex]['delivery_address'] = serialize($this->deliveryAddress);
            $this->suborders[$suborderIndex]['expected_transit_time'] = $this->models->mediaPostageOptions->getDuration($this->get('postageOptionId'));
            $this->suborders[$suborderIndex]['expected_dispatch_time'] = $this->models->mediaAttributeValue->getByAttributeIdMediaId(1, $this->mediaId)['value'] . ' ' . $this->models->mediaAttributeValue->getByAttributeIdMediaId(2, $this->mediaId)['value'];

            $orderTotal += $subOrderTotal + $orderPostage;
        }

        $this->set('orderTotal', $orderTotal);

        $this->createOrder();
        $this->createSuborder();
        $this->processPayment();
    }

    /**
     * Calculate tax on item after applying discount.
     * 
     * @param type int
     * @return decimal
     */
    public function getItemTaxAfterDiscount($variantId, $mediaId) {

        $taxRate = 0;
        if (!empty($this->merchantDetails['merchant_legal_entity_business_tax_id']) && ($this->merchantDetails['merchant_taxable_countries'] == '*' || strpos($this->merchantDetails['merchant_taxable_countries'], $this->deliveryAddress['country']) !== false)) {

            $taxRate = $this->models->mediaTax->getRateByMediaId($mediaId);
        }

        return $this->getMoney(( $this->getItemTotalAfterDiscount($variantId) * $taxRate));
    }

    /**
     * Calculate tax on item after applying discount.
     * 
     * @param type int
     * @return decimal
     */
    public function getItemTaxRate($variantId, $mediaId) {

        $taxRate = 0;
        if (!empty($this->merchantDetails['merchant_legal_entity_business_tax_id']) && ($this->merchantDetails['merchant_taxable_countries'] == '*' || strpos($this->merchantDetails['merchant_taxable_countries'], $this->deliveryAddress['country']) !== false)) {

            $taxRate = $this->models->mediaTax->getRateByMediaId($mediaId);
        }
        return $taxRate;
    }

    /**
     * Calculate item discount.
     * 
     * @param type int
     * @return decimal
     */
    public function getItemDiscount($variantId) {

        return $this->getMoney(( $this->get('itemBasetotal') * $this->models->promotion->getRate($variantId)));
    }

    /**
     * Calculate item total after applying discount.
     * 
     * @param type int
     * @return decimal
     */
    public function getItemTotalAfterDiscount($variantId) {

        return ( $this->get('itemBasetotal') - $this->getItemDiscount($variantId) );
    }

    /**
     * Calculate item total after applying discount and, adding tax.
     * 
     * @param type int
     * @return decimal
     */
    public function getItemTotalAfterTax($variantId, $mediaId) {

        return ( $this->getItemTotalAfterDiscount($variantId) + $this->getItemTaxAfterDiscount($variantId, $mediaId) );
    }

    /**
     * Calculate item total after applying discount, adding tax and, adding postage.
     * 
     * @param type int
     * @return decimal
     */
    public function getItemTotalAfterPostage($variantId) {

        return ( $this->getItemTotalAfterTax($variantId) + $this->models->mediaPostageOptions->getRate($this->get('postageOptionId')) );
    }

    /**
     * Get postage option label.
     * 
     * @return string
     */
    public function getOrderPostageOptionLabel() {

        return $this->models->mediaPostageOptions->getLabel($this->get('postageOptionId'));
    }

    public function createSuborder() {

        $history = '';

        foreach ($this->suborders as $index => $_suborder) {

            $suborder['status'] = 'pending';
            $suborder['subtotal'] = $_suborder['order_subtotal'];
            $suborder['total'] = $_suborder['order_total'];
            $suborder['discount'] = $_suborder['order_discount'];
            $suborder['tax'] = $_suborder['order_tax'];
            $merchantDetail = $this->models->merchantDetails->getByUserId($index);
            //$suborder['tax_enabled'] = empty($this->merchantDetails['merchant_legal_entity_business_tax_id']) ? 0 : 1;
            $suborder['tax_enabled'] = $merchantDetail['tax_enabled'];
            $suborder['postage_option_id'] = $_suborder['order_postage_option_id'];
            $suborder['postage_option_label'] = $_suborder['order_postage_option_label'];
            $suborder['postage'] = $_suborder['order_postage'];
            $suborder['order_id'] = $this->get('orderId');
            $suborder['merchant_id'] = $index;
            $suborder['media_title'] = $this->models->media->getTitle($this->mediaId);
            $suborder['media_id'] = $this->mediaId;
            $suborder['delivery_address'] = $_suborder['delivery_address'];
            $suborder['expected_dispatch_time'] = $_suborder['expected_dispatch_time'];
            $suborder['expected_transit_time'] = $_suborder['expected_transit_time'];
            $suborder['base_currency_code'] = $this->merchantDetails['merchant_business_currency'];
            $suborder['merchant_currency_conversion_factor'] = $this->merchantDetails['merchant_currency_conversion_factor'];

            $history['datetime'] = gmdate('Y-m-d H:i:s');
            $history['status'] = $suborder['status'];
            $history['comment'] = 'Order created!';

            $history = serialize($history);

            $suborder['order_history'] = $history;

            $this->suborder = $this->models->orderSuborder->save($suborder);

            $this->set('suborderId', $this->suborder['id']);
            $this->addOrderItems($_suborder['items']);
        }
    }

    public function createOrder() {

        $order['user_id'] = $this->get('userId');
        $order['total'] = $this->get('orderTotal');
        $order['customer_currency_conversion_factor'] = $this->customer['customer_currency_conversion_factor'];
        $order['customer_currency_code'] = $this->customer['currency_code'];

        $order = $this->models->order->save($order);

        $this->set('orderId', $order['id']);
    }

    public function addOrderItems($suborderItems) {

        foreach ($suborderItems as $_item) {

            $item['suborder_id'] = $this->get('suborderId');
            $item['order_id'] = $this->get('orderId');
            $item['media_id'] = $_item['media_id'];
            $item['media_title'] = $this->models->media->getTitle($_item['media_id']);
            $item['media_thumbnail_image'] = $this->models->media->getThumbnailImage($_item['media_id']);
            $item['variant_id'] = (!empty($_item['variant_id'])) ? $_item['variant_id'] : '';

            $mediaVariantOptions = $this->models->mediaVariantOption->getAllByMediaId($_item['media_id'], $_item['variant_id']);

            $variantLabel = array();
            $mediaVariant = array();

            foreach ($mediaVariantOptions as $mediaVariantOption) {

                $variantOption['id'] = $mediaVariantOption['id'];
                $variantOption['label'] = $mediaVariantOption['label'];
                $variantOption['value_id'] = $mediaVariantOption['option_value_id'];
                $variantOption['value'] = $mediaVariantOption['option_value_value'];

                $variantLabel[] = $mediaVariantOption['option_value_value'];
                $mediaVariant['options'][] = $variantOption;
            }

            $mediaVariant['label'] = implode(' / ', $variantLabel);

            $item['options'] = serialize($mediaVariant);
            $item['ordered_quantity'] = $_item['quantity'];
            $item['billed_quantity'] = $item['ordered_quantity'];
            $item['price'] = $_item['price'];
            $item['row_discount'] = $this->getItemDiscount($_item['variant_id']);
            $item['row_tax'] = $_item['row_tax'];
            $item['row_tax_rate'] = $_item['row_tax_rate'];
            $item['row_tax_inclusive'] = $_item['row_tax_inclusive'];
            $item['row_basetotal'] = $_item['row_basetotal'];
            $item['row_total'] = $_item['row_total'];
            $item['merchant_id'] = $_item['merchant_id'];

            $this->suborder['items'][] = $this->models->orderItem->save($item);
        }
    }

    private function processPayment() {

        $paymentOption = $this->get('paymentOption');

        if (strcmp($paymentOption['id'], 'Stripe') === 0) {

            if (empty($this->models->user->getStripeCustomerId($this->get('userId')))) {

                throw new BaseException('Please add a card for payment.');
            }

            $this->services->transaction->mode = 'Stripe';
            $this->services->transaction->amount = $this->get('orderTotal');
            $this->services->transaction->currency_code = $this->merchantDetails['merchant_business_currency'];
            $this->services->transaction->attributes = serialize(array(
                'order_id' => $this->get('orderId'),
                'stripe' => array(
                    'customer_id' => $this->models->user->getStripeCustomerId($this->get('userId')),
                    'card_id' => $paymentOption['data']['card_id']
                )
            ));
            $this->services->transaction->user_id = $this->get('userId');

            $this->set('transaction', $this->services->transaction->newOrder());

            $tansaction = $this->get('transaction');

            $transactionAttributes = unserialize($tansaction['attributes']);
            $stripeEuCountries = load_config_one('stripeEuCountries');
            $this->userCard = $this->models->userStripeCard->getByCardId($paymentOption['data']['card_id']);

            if (in_array($this->userCard['country'], $stripeEuCountries)) {

                $transactionFee = $this->models->subscriptionPackage->getByUserId($this->merchantDetails['id'])['eu_transaction_fee'];
            } else {

                $transactionFee = $this->models->subscriptionPackage->getByUserId($this->merchantDetails['id'])['transaction_fee'];
            }

            if ($this->merchantDetails['merchant_business_currency'] != 'GBP') {

                $this->additionalFee = (0.20 / $this->models->currencyRate->getByCurrencyCode('GBP') * $this->merchantDetails['merchant_currency_conversion_factor']);
                $this->additionalFee = $this->getMoney(($this->additionalFee + ($this->additionalFee * 0.02)));
            } else {

                $this->additionalFee = 0.20;
            }

            try {

                $charge = $this->services->stripe->charge(
                        $tansaction['amount'], $tansaction['currency_code'], $transactionAttributes['stripe']['customer_id'], $transactionAttributes['stripe']['card_id'], '', $this->merchantDetails['merchant_stripe_account_id'], ($this->getMoney(($this->get('orderTotal')) * ($transactionFee) / 100) + $this->additionalFee)
                );
            } catch (\Exception $ex) {
                $this->services->transaction->failed($tansaction['id']);
                throw new BaseException('Error in payment processing: ' . $ex->getMessage());
            }
            if (strcmp($charge->status, 'succeeded') === 0) {

                $this->services->transaction->completed($tansaction['id'], 'stripe_charge_id', $charge->id, $charge);
                $this->processOrder();
            }
        } else {

            throw new BaseException('Invalid payment option.');
        }
    }

    private function processOrder() {
        $stripeEuCountries = load_config_one('stripeEuCountries');
        $merchantEntries = array();

        foreach ($this->suborders as $index => $suborder) {

            foreach ($suborder['items'] as $item) {

                $this->models->mediaStockItem->decrement($item['media_id'], $item['variant_id'], $item['quantity']);
            }

            $entry['amount'] = $this->getMoney(($suborder['order_total']));
            $entry['flow'] = 'in';
            $entry['description'] = 'New order.';
            $entry['type_code'] = 'new_order';
            $entry['currency_code'] = $this->merchantDetails['merchant_business_currency'];
            $entry['user_id'] = $index;
            $entry['order_id'] = $this->get('suborderId');
            $entry['transaction_id'] = $this->get('transaction')['id'];

            $this->models->merchantLedger->save($entry);
            if (in_array($this->userCard['country'], $stripeEuCountries)) {

                $transactionFee = $this->models->subscriptionPackage->getByUserId($index)['eu_transaction_fee'];
            } else {

                $transactionFee = $this->models->subscriptionPackage->getByUserId($index)['transaction_fee'];
            }

            $entry['amount'] = $this->getMoney(($suborder['order_total']) * ($transactionFee) / 100);
            $entry['flow'] = 'out';
            $entry['description'] = 'Transaction fee.';
            $entry['type_code'] = 'transaction_fee';
            $entry['currency_code'] = $this->merchantDetails['merchant_business_currency'];
            $entry['transaction_fee_rate'] = $transactionFee;
            $entry['user_id'] = $index;
            $entry['order_id'] = $this->get('suborderId');
            $entry['transaction_id'] = $this->get('transaction')['id'];

            $this->models->merchantLedger->save($entry);

            $entry['amount'] = $this->additionalFee;
            $entry['flow'] = 'out';
            $entry['description'] = 'Additional fee.';
            $entry['type_code'] = 'additional_fee';
            $entry['currency_code'] = $this->merchantDetails['merchant_business_currency'];
            $entry['transaction_fee_rate'] = '';
            $entry['user_id'] = $index;
            $entry['order_id'] = $this->get('suborderId');
            $entry['transaction_id'] = $this->get('transaction')['id'];

            $this->models->merchantLedger->save($entry);

            $merchant = $this->models->user->getById($index);

            $app = array(
                'base_assets_url' => $this->app->config('base_assets_url'),
                'domain' => $this->app->config('domain'),
                'base_url' => $this->app->config('base_url'),
                'app_title' => $this->app->config('app_title'),
                'master_hashtag' => $this->app->config('master_hashtag'),
                'feedback_email' => $this->app->config('feedback_email'),
                'sales_email' => $this->app->config('sales_email'),
                'support_email' => $this->app->config('support_email'),
                'instagram_account_url' => $this->app->config('instagram_account_url'),
                'twitter_account_url' => $this->app->config('twitter_account_url'),
                'support_phone_uk' => $this->app->config('support_phone_uk'),
                'support_phone_int' => $this->app->config('support_phone_int')
            );
            $user = $this->models->user->getById($this->app->user['id']);
            $orderArray['suborder'] = $this->suborder;
            $orderArray['merchant'] = $this->models->merchantDetails->getByUserId($this->merchantDetails['id']);
            $orderArray['delivery_address'] = $this->deliveryAddress;

            $attachments = $this->getInvoice($this->get('suborderId'), 'getPdfFile');
            $this->services->emailNotification->sendMail(array('email' => $user['email'], 'name' => $user['first_name'] . ' ' . $user['last_name']), sprintf($this->app->config('customer_order_confirmation_subject'), '#TGZ' . $orderArray['suborder']['id']), $this->core->view->make('email/order-confirmation.php', array('user' => $user, 'app' => $app, 'order' => $orderArray, 'countries' => load_config_one('countries'), 'currencies' => load_config_one('currencies')), false), $attachments);

            if ($merchant['notify_push_merchant_new_order']) {

                $message = array('title' => 'Sales Manager', 'message' => 'You have recieved a new order.', 'extra' => array('link' => 'ipaid://salesManager.html', 'image' => $this->models->media->getThumbnailImage($this->mediaId)));
                $pushNotification = new \App\Services\PushNotification();
                $pushNotification->sendToUserDevices($index, $message);
            }
        }


        $transaction = $this->get('transaction');

        $order['id'] = $this->get('orderId');
        $order['total_paid'] = $transaction['amount'];
        $order['payment_currency_code'] = $transaction['currency_code'];
        $order['payment_transaction_id'] = $transaction['id'];
        $order['payment_status'] = 'fully_paid';

        $this->models->order->save($order);

        $order = $this->models->orderSuborder->updateByOrderId(array('status' => 'in_progress', 'payment_status' => 'paid', 'order_id' => $this->get('orderId')));

        $this->addHistory($this->get('orderId'), 'Order sent to the merchant for processing!', 'in_progress');

        if ($order) {

            if (!empty($this->get('commentId'))) {

                $comment['comment_id'] = $this->get('commentId');
                $comment['order_completed'] = 1;
                $comment['order_id'] = $this->get('orderId');

                $this->models->comment->updateByInstagramCommentId($comment);
            }

            $data['order'] = $order;
            unset($_SESSION['cart']);
            $this->core->response->json($data);
        }
    }

    public function ship($id) {

        $trackingDetails = $this->jsonRequest;

        $suborder = array('status' => 'shipped', 'id' => $id, 'shipped_at' => gmdate('Y-m-d H:i:s'));

        $suborder = $this->models->orderSuborder->updateByMerchantId($suborder, $this->app->user['id']);

        if (!empty($suborder)) {

            $suborder = array('id' => $id, 'tracking_details' => serialize($trackingDetails));

            $suborder = $this->models->orderSuborder->updateByMerchantId($suborder, $this->app->user['id']);

            $this->addHistoryToSubOrder($id, 'Order shipped by the merchant!', 'shipped');

            $_suborder = $this->models->orderSuborder->getById($suborder['order_id']);

            $app = array(
                'base_assets_url' => $this->app->config('base_assets_url'),
                'domain' => $this->app->config('domain'),
                'base_url' => $this->app->config('base_url'),
                'app_title' => $this->app->config('app_title'),
                'master_hashtag' => $this->app->config('master_hashtag'),
                'feedback_email' => $this->app->config('feedback_email'),
                'sales_email' => $this->app->config('sales_email'),
                'support_email' => $this->app->config('support_email'),
                'instagram_account_url' => $this->app->config('instagram_account_url'),
                'twitter_account_url' => $this->app->config('twitter_account_url'),
                'support_phone_uk' => $this->app->config('support_phone_uk'),
                'support_phone_int' => $this->app->config('support_phone_int')
            );

            $user = $this->models->user->getById($_suborder['user_id']);

            $orderArray = $_suborder;
            $orderArray['tracking_details'] = unserialize($_suborder['tracking_details']);
            $orderArray['merchant'] = $this->models->merchantDetails->getByUserId($this->app->user['id']);
            $orderArray['delivery_address'] = unserialize($_suborder['delivery_address']);

            $this->services->emailNotification->sendMail(array('email' => $user['email'], 'name' => $user['first_name'] . ' ' . $user['last_name']), sprintf($this->app->config('customer_order_dispatch_subject'), '#TGZ' . $orderArray['id']), $this->core->view->make('email/order-dispatch.php', array('user' => $user, 'app' => $app, 'order' => $orderArray, 'countries' => load_config_one('countries')), false));


            if ($_suborder['user_notify_push_customer_order_status_change']) {

                $notification = array('title' => 'Orders', 'message' => 'Order #TGZ' . $id . ' is dispatched.', 'extra' => array('link' => 'ipaid://purchases.html', 'image' => $this->models->media->getThumbnailImage($suborder['media_id'])));
                $pushNotification = new \App\Services\PushNotification();
                $pushNotification->sendToUserDevices($_suborder['user_id'], $notification);
            }
            $data = array();
            $data['message'] = 'Order updated successfully!';
            $this->core->response->json($data);
        } else {

            throw new BaseException('Nothing to change!');
        }
    }

//    public function cancel($id) {
//
//        $request = $this->jsonRequest;
//
//        $_suborder = $this->models->orderSuborder->getById($id);
//        $order = $this->models->order->getById($id);
//
//        if ($_suborder['status'] == 'in_progress' || $_suborder['status'] == 'pending') {
//
//            $suborder = array('status' => 'cancelled', 'id' => $id, 'cancelled_at' => gmdate('Y-m-d H:i:s'));
//
//            $transactionEntryDescription = 'Order canceled.';
//            $transactionEntryType = 'cancel_order';
//            $tansection_amt = $this->models->merchantLedger->getByTransactionIdOrderAmount($order['payment_transaction_id'], $_suborder['merchant_id']);
//            $additionalFee = $this->models->merchantLedger->getAdditionalFee($order['payment_transaction_id'], $_suborder['merchant_id']);
//
//            if ($transection_id = $this->services->transaction->refund($order['payment_transaction_id'])) {
//
//                $suborder['refunded_at'] = gmdate('Y-m-d H:i:s');
//
//                $suborder['cancel_reason'] = (!empty($request['reason'])) ? $request['reason'] : '';
//
//                $suborder = $this->models->orderSuborder->updateByMerchantId($suborder, $_suborder['merchant_id']);
//
//                $newOrderEntry = $this->models->merchantLedger->getByTransactionIdNewOrder($order['payment_transaction_id'], $_suborder['merchant_id']);
//
//                $entry['amount'] = $this->getMoney(($newOrderEntry['amount']));
//                $entry['flow'] = 'out';
//                $entry['description'] = $transactionEntryDescription;
//                $entry['type_code'] = $transactionEntryType;
//                $entry['currency_code'] = $newOrderEntry['currency_code'];
//                $entry['user_id'] = $_suborder['merchant_id'];
//                $entry['order_id'] = $id;
//                $entry['transaction_id'] = $transection_id['id'];
//                $this->models->merchantLedger->save($entry);
//
//                $entry['amount'] = $this->getMoney(($tansection_amt['amount']));
//                $entry['flow'] = 'in';
//                $entry['description'] = 'Transaction fee returned';
//                $entry['type_code'] = 'transaction_fee_returned';
//                $entry['currency_code'] = $newOrderEntry['currency_code'];
//                $entry['user_id'] = $_suborder['merchant_id'];
//                $entry['order_id'] = $id;
//                $entry['transaction_id'] = $transection_id['id'];
//                $this->models->merchantLedger->save($entry);
//
//                $entry['amount'] = $this->getMoney(($additionalFee));
//                $entry['flow'] = 'in';
//                $entry['description'] = 'Additional fee returned';
//                $entry['type_code'] = 'additional_fee_returned';
//                $entry['currency_code'] = $newOrderEntry['currency_code'];
//                $entry['user_id'] = $_suborder['merchant_id'];
//                $entry['order_id'] = $id;
//                $entry['transaction_id'] = $transection_id['id'];
//                $this->models->merchantLedger->save($entry);
//
//                $verb = 'cancelled';
//
//                $orderItems = $this->models->orderItem->getBySuborderId($id);
//
//                foreach ($orderItems as $item) {
//
//                    $this->models->mediaStockItem->increment($item['media_id'], $item['variant_id'], $item['ordered_quantity']);
//                }
//
//                if (file_exists($this->app->config('invoices_directory') . '/TZ-' . $id . '.pdf')) {
//
//                    unlink($this->app->config('invoices_directory') . '/TZ-' . $id . '.pdf');
//                }
//            }
//        } elseif ($_suborder['status'] == 'shipped') {
//
//            $suborder = array('status' => 'returned', 'id' => $id, 'returned_at' => gmdate('Y-m-d H:i:s'));
//
//            $suborder = $this->models->orderSuborder->updateByMerchantId($suborder, $_suborder['merchant_id']);
//
//            $verb = 'returned';
//        }
//        $message = 'Order #' . $id . ' was ' . $verb . ' because of the following reason: ' . $request['reason'];
//
//        $this->repositories->messageRepository->postMessage($this->app->user['id'], array('order_id' => $id, 'text' => $message, 'recipient_id' => $_suborder['merchant_id']));
//
//        if (!empty($suborder)) {
//
//            if ($_suborder['status'] == 'in_progress' || $_suborder['status'] == 'pending') {
//
//                $this->addHistoryToSubOrder($id, 'Order cancelled', 'cancelled');
//            } elseif ($_suborder['status'] == 'shipped') {
//
//                $this->addHistoryToSubOrder($id, 'Order returned', 'returned');
//            }
//
//            $data['message'] = 'Order updated successfully!';
//
//            $this->core->response->json($data);
//        } else {
//
//            throw new BaseException('Nothing to change!');
//        }
//    }

    public function cancel($id) {

        $request = $this->jsonRequest;

        $_suborder = $this->models->orderSuborder->getById($id);
        $order = $this->models->order->getById($id);

        if ($_suborder['status'] == 'in_progress' || $_suborder['status'] == 'pending') {

            $suborder = array('status' => 'cancelled', 'id' => $id, 'cancelled_at' => gmdate('Y-m-d H:i:s'));

//            $transactionEntryDescription = 'Order canceled.';
//            $transactionEntryType = 'cancel_order';
//            $tansection_amt = $this->models->merchantLedger->getByTransactionIdOrderAmount($order['payment_transaction_id'], $_suborder['merchant_id']);
//            $additionalFee = $this->models->merchantLedger->getAdditionalFee($order['payment_transaction_id'], $_suborder['merchant_id']);
//
//            if ($transection_id = $this->services->transaction->refund($order['payment_transaction_id'])) {
//
//                $suborder['refunded_at'] = gmdate('Y-m-d H:i:s');
//
//                $suborder['cancel_reason'] = (!empty($request['reason'])) ? $request['reason'] : '';
//
//                $suborder = $this->models->orderSuborder->updateByMerchantId($suborder, $_suborder['merchant_id']);
//
//                $newOrderEntry = $this->models->merchantLedger->getByTransactionIdNewOrder($order['payment_transaction_id'], $_suborder['merchant_id']);
//
//                $entry['amount'] = $this->getMoney(($newOrderEntry['amount']));
//                $entry['flow'] = 'out';
//                $entry['description'] = $transactionEntryDescription;
//                $entry['type_code'] = $transactionEntryType;
//                $entry['currency_code'] = $newOrderEntry['currency_code'];
//                $entry['user_id'] = $_suborder['merchant_id'];
//                $entry['order_id'] = $id;
//                $entry['transaction_id'] = $transection_id['id'];
//                $this->models->merchantLedger->save($entry);
//
//                $entry['amount'] = $this->getMoney(($tansection_amt['amount']));
//                $entry['flow'] = 'in';
//                $entry['description'] = 'Transaction fee returned';
//                $entry['type_code'] = 'transaction_fee_returned';
//                $entry['currency_code'] = $newOrderEntry['currency_code'];
//                $entry['user_id'] = $_suborder['merchant_id'];
//                $entry['order_id'] = $id;
//                $entry['transaction_id'] = $transection_id['id'];
//                $this->models->merchantLedger->save($entry);
//
//                $entry['amount'] = $this->getMoney(($additionalFee));
//                $entry['flow'] = 'in';
//                $entry['description'] = 'Additional fee returned';
//                $entry['type_code'] = 'additional_fee_returned';
//                $entry['currency_code'] = $newOrderEntry['currency_code'];
//                $entry['user_id'] = $_suborder['merchant_id'];
//                $entry['order_id'] = $id;
//                $entry['transaction_id'] = $transection_id['id'];
//                $this->models->merchantLedger->save($entry);
//
//                $verb = 'cancelled';
//
//                $orderItems = $this->models->orderItem->getBySuborderId($id);
//
//                foreach ($orderItems as $item) {
//
//                    $this->models->mediaStockItem->increment($item['media_id'], $item['variant_id'], $item['ordered_quantity']);
//                }
//
//                if (file_exists($this->app->config('invoices_directory') . '/TZ-' . $id . '.pdf')) {
//
//                    unlink($this->app->config('invoices_directory') . '/TZ-' . $id . '.pdf');
//                }
//            }

            $verb = 'requested for cancellation';

            $suborder = array('status' => 'requested_cancellation', 'id' => $id, 'requested_cancellation_at' => gmdate('Y-m-d H:i:s'));
            $suborder['cancel_reason'] = (!empty($request['reason'])) ? $request['reason'] : '';

            $suborder = $this->models->orderSuborder->updateByMerchantId($suborder, $_suborder['merchant_id']);

            $message = array('title' => 'Sales Manager', 'message' => 'Your customer wishes to cancel order #TGZ' . $id, 'extra' => array('link' => 'ipaid://salesManager.html', 'requested_cancellation' => 1, 'image' => $this->models->media->getThumbnailImage($_suborder['media_id'])));
            $pushNotification = new \App\Services\PushNotification();
            $pushNotification->sendToUserDevices($_suborder['merchant_id'], $message);

            $app = array(
                'base_assets_url' => $this->app->config('base_assets_url'),
                'domain' => $this->app->config('domain'),
                'base_url' => $this->app->config('base_url'),
                'app_title' => $this->app->config('app_title'),
                'master_hashtag' => $this->app->config('master_hashtag'),
                'feedback_email' => $this->app->config('feedback_email'),
                'sales_email' => $this->app->config('sales_email'),
                'support_email' => $this->app->config('support_email'),
                'instagram_account_url' => $this->app->config('instagram_account_url'),
                'twitter_account_url' => $this->app->config('twitter_account_url'),
                'support_phone_uk' => $this->app->config('support_phone_uk'),
                'support_phone_int' => $this->app->config('support_phone_int')
            );
            $mechantDetail = $this->models->user->getById($_suborder['merchant_id']);

            $orderArray = array();
            $orderArray['id'] = $id;
            $orderArray['cancel_reason'] = $request['reason'];
            $orderArray['merchant'] = $mechantDetail;

            $customerDetail = $this->models->user->getById($this->app->user['id']);


            $this->services->emailNotification->sendMail(array('email' => $mechantDetail['email'], 'name' => $mechantDetail['first_name'] . ' ' . $mechantDetail['last_name']), sprintf($this->app->config('customer_order_cancellation_request_subject'), '#TGZ' . $id), $this->core->view->make('email/order-cancellation-request.php', array('user' => $customerDetail, 'order' => $orderArray, 'app' => $app), false));
        } elseif ($_suborder['status'] == 'shipped') {

            $suborder = array('status' => 'returned', 'id' => $id, 'returned_at' => gmdate('Y-m-d H:i:s'));

            $suborder = $this->models->orderSuborder->updateByMerchantId($suborder, $_suborder['merchant_id']);

            $verb = 'returned';
        }
        $message = 'Order #TGZ' . $id . ' has been ' . $verb . ' because of the following reason: ' . $request['reason'];

        $this->repositories->messageRepository->postMessage($this->app->user['id'], array('order_id' => $id, 'text' => $message, 'recipient_id' => $_suborder['merchant_id']));

        if (!empty($suborder)) {

            if ($_suborder['status'] == 'in_progress' || $_suborder['status'] == 'pending') {

                //$this->addHistoryToSubOrder($id, 'Order cancelled', 'cancelled');
                $this->addHistoryToSubOrder($id, 'Order cancel requested', 'requested_cancellation');
                $data['message'] = 'Order cancellation request sent to merchant successfully!';
            } elseif ($_suborder['status'] == 'shipped') {

                $this->addHistoryToSubOrder($id, 'Order returned', 'returned');
                $data['message'] = 'Order updated successfully!';
            }

            //$data['message'] = 'Order updated successfully!';

            $this->core->response->json($data);
        } else {

            throw new BaseException('Nothing to change!');
        }
    }

    public function getMoney($amount) {

        return round($amount, 2);
    }

    private function addHistory($orderId, $comment, $status) {

        $history = '';
        $history['datetime'] = gmdate('Y-m-d H:i:s');
        $history['status'] = $status;
        $history['comment'] = $comment;

        $history = serialize($history);

        $this->models->orderSuborder->saveHistoryByOrderId($orderId, $history);
    }

    private function addHistoryToSubOrder($subOrderId, $comment, $status) {

        $history = '';
        $history['datetime'] = gmdate('Y-m-d H:i:s');
        $history['status'] = $status;
        $history['comment'] = $comment;

        $history = serialize($history);

        $this->models->orderSuborder->saveHistory($subOrderId, $history);
    }

    public function listOrders() {

        $this->userLogger->log('info', 'User requested to get list of orders.', $this->app->user['id']);

        $request = $this->jsonRequest;

        $offset = $this->request->get('page');
        $status = $this->request->get('status');
        $search = $this->request->get('search');
        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));
        $orders = $this->models->orderSuborder->getAllByUserId($this->app->user['id'], $filter, $order, $offset, 50, $status, $search);

        foreach ($orders as $orderIndex => $order) {

            $history = explode(':::', $order['order_history']);

            $orderHistory = array();

            foreach ($history as $row) {

                $orderHistory[] = unserialize($row);
            }

            $orders[$orderIndex]['order_history'] = $orderHistory;
            $orders[$orderIndex]['tracking_details'] = unserialize($order['tracking_details']);
            $orders[$orderIndex]['items'] = $this->models->orderItem->getBySuborderId($order['id'], $this->app->user['id']);
            $orders[$orderIndex]['thread_id'] = $this->models->messagesThreadDetails->getThreadIdByOrderId($order['id'], $this->app->user['id'], $order['merchant_id']);
            $orders[$orderIndex]['dispute_id'] = $this->models->messagesThreadDetails->getDisputeIdByOrderId($order['id'], $this->app->user['id'], $order['merchant_id']);
            foreach ($orders[$orderIndex]['items'] as $itemIndex => $item) {

                $orders[$orderIndex]['items'][$itemIndex]['options'] = unserialize($item['options']);
            }
        }
        if ($orders) {

            $data['orders'] = $orders;

            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
        } else {

            $this->userLogger->log('error', 'Resource not found', $this->app->user['id']);

            throw new BaseException('Resource not found');
        }
    }

    public function saveRating($id) {

        $request = $this->jsonRequest;

        $rating = array();
        $rating['user_id'] = $this->app->user['id'];
        $rating['suborder_id'] = $id;
        $rating['rating'] = $request['rating'];

        $rating = $this->models->orderRating->save($rating);

        if ($rating) {

            $data['message'] = 'Order rated successfully!';

            echo $response = $this->core->response->json($data, FALSE);
        }
    }

    public function getInvoice($id, $getPdfFile = '') {

        $invoice = array();
        $invoice['suborder'] = $this->models->orderSuborder->getById($id, $this->app->user['id']);

        if (!$invoice['suborder'])
            $this->app->slim->notFound();

        if (!file_exists($this->app->config('invoices_directory') . '/TZ-' . $id . '.pdf')) {

            $invoice['suborder']['items'] = $this->models->orderItem->getBySuborderId($invoice['suborder']['id']);
            $invoice['merchant'] = $this->models->merchantDetails->getByUserId($invoice['suborder']['merchant_id']);
            $data['invoice'] = $invoice;
            $data['user'] = $this->models->user->getById($this->app->user['id']);
            $data['countries'] = load_config_one('countries');
            $data['currencies'] = load_config_one('currencies');
            $data['app'] = $this->app->config();
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->set_option('isHtml5ParserEnabled', true);
            $dompdf->set_option('isRemoteEnabled', true);
            $dompdf->set_option('defaultFont', 'Ubuntu');
            $dompdf->loadHtml($this->core->view->make('pdf/invoice.php', $data, false));
            $dompdf->render();
            $pdf = $dompdf->output();
            file_put_contents($this->app->config('invoices_directory') . '/TZ-' . $id . '.pdf', $pdf);
        }

//        $response['order']['invoice_url'] = $this->app->config('base_invoices_url') . 'TZ-' . $id . '.pdf';
        $file = $this->app->config('invoices_directory') . '/TZ-' . $id . '.pdf';

        if (empty($getPdfFile)) {
            output_file($file);
        } else {
            $attachments = array();
            $attachments[] = array('path' => $this->app->config('invoices_directory') . '/TZ-' . $id . '.pdf', 'name' => 'TZ-' . $id . '.pdf');
            return $attachments;
        }

//        echo $response = $this->core->response->json($response, FALSE);
    }

    public function getActivity() {

        $offset = $this->request->get('page');
        $order = array('order' => $this->request->get('order'), 'order_by' => $this->request->get('orderBy'));
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));
        $activity = $this->repositories->orderRepository->getActivitList($this->app->user['id'], $filter, $order, $offset);

        if (!empty($activity)) {

            $data['activity'] = $activity;

            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
        } else {

            $this->userLogger->log('error', 'Resource not found', $this->app->user['id']);

            throw new BaseException('Resource not found');
        }
    }

    public function getPackagingDocuments($id) {

        $invoice = array();
        $invoice['suborder'] = $this->models->orderSuborder->getById($id, $this->app->user['id'], 'merchant');

        if (!$invoice['suborder'])
            $this->app->slim->notFound();

        if (!file_exists($this->app->config('packaging_directory') . '/TZ-' . $id . '.pdf')) {

            $invoice['suborder']['items'] = $this->models->orderItem->getBySuborderId($invoice['suborder']['id']);
            $invoice['merchant'] = $this->models->merchantDetails->getByUserId($invoice['suborder']['merchant_id']);
            $data['invoice'] = $invoice;
            $data['user'] = $this->models->user->getById($this->app->user['id']);
            $data['countries'] = load_config_one('countries');
            $data['currencies'] = load_config_one('currencies');
            $data['app'] = $this->app->config();
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->set_option('isHtml5ParserEnabled', true);
            $dompdf->set_option('isRemoteEnabled', true);
            $dompdf->set_option('defaultFont', 'Ubuntu');
            $dompdf->loadHtml($this->core->view->make('pdf/packaging.php', $data, false));
            $dompdf->render();
            $pdf = $dompdf->output();
            file_put_contents($this->app->config('packaging_directory') . '/TZ-' . $id . '.pdf', $pdf);
        }

//        $response['order']['packaging_url'] = $this->app->config('base_packaging_url') . 'TZ-' . $id . '.pdf';
        $file = $this->app->config('packaging_directory') . '/TZ-' . $id . '.pdf';

        output_file($file);
//        echo $response = $this->core->response->json($response, FALSE);
    }

    public function markComplete() {

        return $this->models->orderSuborder->complete($this->app->config('order_hold_period'));
    }

}
