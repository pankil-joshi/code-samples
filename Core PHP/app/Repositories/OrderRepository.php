<?php

namespace App\Repositories;

use Quill\Factories\ModelFactory;
use Quill\Exceptions\BaseException;

class OrderRepository {

    private $models = array();

    public function __construct() {

        ModelFactory::setNamespace('\\App\\Models\\');

        $this->models = ModelFactory::boot(array(
                    'Order',
                    'OrderSuborder',
                    'OrderItem',
                    'Comment',
                    'Media',
                    'MediaVariant',
                    'MessagesThreadDetails'
        ));
    }

    public function getOrderList($userId, $filter, $order, $offset, $limit = 10, $status = '') {

        $orders = $this->models->orderSuborder->getAllByUserId($userId, $filter, $order, $offset, $limit, $status);

        foreach ($orders as $orderIndex => $order) {

            $history = explode(':::', $order['order_history']);

            $orderHistory = array();

            foreach ($history as $row) {

                $orderHistory[] = unserialize($row);
            }

            $orders[$orderIndex]['order_history'] = $orderHistory;
            $orders[$orderIndex]['tracking_details'] = unserialize($order['tracking_details']);
            $orders[$orderIndex]['items'] = $this->models->orderItem->getBySuborderId($order['id'], $userId);
            $orders[$orderIndex]['thread_id'] = $this->models->messagesThreadDetails->getThreadIdByOrderId($order['id'], $userId, $order['merchant_id']);
            $orders[$orderIndex]['dispute_id'] = $this->models->messagesThreadDetails->getDisputeIdByOrderId($order['id'], $userId, $order['merchant_id']);

            foreach ($orders[$orderIndex]['items'] as $itemIndex => $item) {

                $orders[$orderIndex]['items'][$itemIndex]['options'] = unserialize($item['options']);
            }
        }

        return $orders;
    }

    public function getTaggedList($userId, $filter, $order, $offset) {

        $taggedProducts = $this->models->media->getTaggedByUserId($userId, $filter, $order, $offset);

        foreach ($taggedProducts as $index => $taggedProduct) {

            $prices = $this->models->mediaVariant->getPricesByMediaId($taggedProduct['id']);
            $taggedProducts[$index]['thread_id'] = $this->models->messagesThreadDetails->getThreadIdByProductId($taggedProduct['id'], $userId);
            $taggedProducts[$index]['min_price'] = $prices['min_price'];
            $taggedProducts[$index]['max_price'] = $prices['max_price'];
        }

        return $taggedProducts;
    }

    public function getActivitList($userId, $filter, $order, $offset) {
        
        $orders = $this->getOrderList($userId, $filter, $order, $offset);
        $tagged = $this->getTaggedList($userId, $filter, $order, $offset);
        $activity = array_merge($orders, $tagged);

        $date = array();
        foreach ($activity as $key => $row) {
            $date[$key] = strtotime((isset($row['items'])) ? $row['created_at'] : $row['tagged_at'] . ' UTC');
        }
        
        array_multisort($date, SORT_DESC, $activity);
        
        return $activity;
    }

}
