<div class="col-md-12" style="padding:0">
    <?php
    if (!empty($orders)):
        $counter = 1;
        $statuses = array(
            'in_progress' => array('text' => 'Awaiting Dispatch', 'color' => '#843B0A'),
            'completed' => array('text' => 'Completed', 'color' => '#385623'),
            'requested_cancellation' => array('text' => 'Pending Cancellation', 'color' => 'red'),
            'declined' => array('text' => 'Cancellation Declined', 'color' => 'red'),
            'cancelled' => array('text' => 'Cancelled', 'color' => 'red'),
            'refunded' => array('text' => 'Refunded', 'color' => 'red'),
            'returned' => array('text' => 'Returned', 'color' => 'red'),
            'shipped' => array('text' => 'Dispatched', 'color' => '#385623')
        );
        foreach ($orders as $order):
            ?>
            <div class="order-row  <?php if ($counter % 2 == 0): ?>deactive_show<?php else: ?>active_show<?php endif; ?>">
                <div class="col-md-<?= empty($status) ? 3 : 4; ?> col-sm-12" style="padding:0px;">
                    <div class="date_detal">
                        <div class="col-md-3">    
                            <h1><?= convert_datetime((isset($order['items'])) ? $order['created_at'] : $order['tagged_at'], $user['timezone'], 'M'); ?><br><?= convert_datetime((isset($order['items'])) ? $order['created_at'] : $order['tagged_at'], $user['timezone'], 'd'); ?></h1>
                        </div>
                        <div class="col-md-3 text-center">
                            <?php
                            if (isset($order['items'])) {
                                $image = $order['items'][0]['media_deleted'] == 0 ? $order['items'][0]['media_thumbnail_image'] : $app['base_assets_url'] . 'images/image_deleted.jpg';
                            } else {
                                $image = $order['media_deleted'] == 0 ? $order['image_thumbnail'] : $app['base_assets_url'] . 'images/image_deleted.jpg';
                            }
                            ?>
                            <img src="<?= $image; ?>" class="img-responsive product_img_list" alt="">
                        </div>
                        <div class="col-md-6" style="padding:0">
                            <div class="air_max">
                                <h1><?= (isset($order['items'])) ? $order['items'][0]['media_title'] : $order['title']; ?></h1>
                                <p>from @<?= (isset($order['items'])) ? $order['items'][0]['user_instagram_username'] : $order['instagram_username']; ?></p>
                                <?php if (isset($order['items'])): ?><p>Status: <span style="color:<?= $statuses[$order['status']]['color']; ?>"><?= $statuses[$order['status']]['text']; ?></span></p><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-9 col-sm-12 order_manage pull-right">
                    <div class="row date_detal">
                        <div class="col-md-<?= empty($status) ? 8 : 6; ?>">    
                            <div class="col-md-<?= empty($status) ? 4 : 6; ?> col-sm-12">
                                <div class="date_detal message-button" data-type="enquiry" <?php if (isset($order['items'])): ?>data-order_id="<?= $order['id']; ?>"<?php else: ?>data-product_id="<?= $order['id']; ?>"<?php endif; ?> data-id="<?= (!empty($order['thread_id'])) ? $order['thread_id'] : ''; ?>" data-second_user_id="<?= (isset($order['items'])) ? $order['merchant_id'] : $order['user_id']; ?>" >
                                    <div class="col-md-4 text-center" style="padding-right:10px">
                                        <img src="<?= $app['base_assets_url']; ?>images/message_blue.png" class="img-responsive" alt="">
                                    </div>
                                    <div class="col-md-8" style="padding-left:0">    
                                        <h1>Message<br> Seller</h1>
                                    </div>
                                </div>
                            </div>
                            <?php if (isset($order['items'])): ?>
                                <div class="col-md-<?= empty($status) ? 4 : 6; ?> col-sm-12">
                                    <div class="date_detal message-button" data-type="dispute" data-order_id="<?= $order['id']; ?>" data-id="<?= (!empty($order['dispute_id'])) ? $order['dispute_id'] : ''; ?>" data-second_user_id="<?= (!empty($order['merchant_id'])) ? $order['merchant_id'] : ''; ?>">
                                        <div class="col-md-4 text-center" style="padding-right:10px">
                                            <img src="<?= $app['base_assets_url']; ?>images/disepute_iorg.png" class="img-responsive" alt="">
                                        </div>
                                        <div class="col-md-8" style="padding-left:0">    
                                            <h1>Problem<br>with order</h1>
                                        </div>
                                    </div>
                                </div>   
                            <?php endif; ?>

                            <div class="col-md-<?= empty($status) ? 4 : 6; ?> col-sm-12">
                                <div class="date_detal">
                                    <?php if (!isset($order['items'])): ?>
                                        <div class="col-md-4 text-center" style="padding-right:10px">
                                            <a target="_blank" href="https://www.instagram.com/<?= (isset($order['items'])) ? $order['items'][0]['user_instagram_username'] : $order['instagram_username']; ?>"><img src="<?= $app['base_assets_url']; ?>images/seeller-ista_inblue.png" class="img-responsive" alt=""></a>
                                        </div>
                                        <div class="col-md-8 " style="padding-left:0">    
                                            <a target="_blank" href="https://www.instagram.com/<?= (isset($order['items'])) ? $order['items'][0]['user_instagram_username'] : $order['instagram_username']; ?>"><h1>Sellers<br>Instagram</h1></a>
                                        </div>
                                    <?php else: ?>
                                        <div class="cancel-order-button <?= (empty($order['is_refundable_disabled']) && ($order['status'] == 'in_progress' || $order['status'] == 'shipped') || (!empty($order['is_refundable_disabled']) && $order['status'] == 'shipped')) ? 'enabled' : 'disabled' ?>" data-id="<?= $order['id']; ?>">
                                            <div class="col-md-4 text-center" style="padding-right:10px">
                                                <img src="<?= $app['base_assets_url']; ?>images/<?= (empty($order['is_refundable_disabled']) && ($order['status'] == 'in_progress' || $order['status'] == 'shipped') || (!empty($order['is_refundable_disabled']) && $order['status'] == 'shipped')) ? 'cancel_requd' : 'cancel_requd_grey'; ?>.png" class="img-responsive" alt="">
                                            </div>
                                            <div class="col-md-8 " style="padding-left:0">
                                                <?php if (!empty($order['is_refundable_disabled'])): ?>
                                                    <h1><?= ($order['status'] == 'shipped') ? 'Return<br>Order' : 'Refunds<br>Ineligible'; ?></h1>
                                                <?php elseif ($order['status'] == 'requested_cancellation'): ?>
                                                    <h1>Pending<br>Cancellation</h1>
                                                <?php elseif ($order['status'] == 'declined'): ?>
                                                    <h1>Cancellation<br>Declined</h1>
                                                <?php else: ?>
                                                    <h1><?= ($order['status'] == 'shipped') ? 'Return' : 'Cancel'; ?><br>Order</h1>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-<?= empty($status) ? 4 : 6; ?>" style="display: flex;align-items: center;">    
                            <div class="col-md-6 col-sm-12" style="padding:0">
                                <div class="star_span set_order_list">
                                    <?php if (isset($order['items'])): ?>
                                        <?php if (empty($order['rating'])): ?><h5>Awaiting feedback</h5><?php endif; ?>
                                        <select class="order-rating" data-rating="<?= $order['rating']; ?>" data-orderid="<?= $order['id']; ?>">
                                            <option value="0" style="display:none;"></option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    <?php else: ?>
                                        <span>Order not completed</span>
                                    <?php endif; ?>
                                </div>    

                            </div>
                            <div class="col-md-<?= empty($status) ? 6 : 4; ?> col-sm-12 text-center pdf_icon">
                                <a title="<?= (isset($order['items'])) ? 'View Invoice' : 'Click to Checkout' ?>" href="<?php if (isset($order['items'])): ?><?= $app['base_api_mobile_url']; ?>orders/<?= $order['id']; ?>/invoice/<?php else: ?><?= $app['base_url']; ?>checkout/?media_id=<?= $order['id']; ?>&comment_id=<?= $order['comment_id']; ?><?php endif; ?>" ><img src="<?= $app['base_assets_url']; ?>images/<?= (isset($order['items'])) ? 'price_oder' : 'shopping' ?>.png" class="img-responsive price_oder_tag " alt="" <?php if (isset($order['comment_id'])): ?>style="width:30px"<?php endif; ?>></a>
                                <span><?= $currencies[$order['base_currency_code']]; ?><?php
                                    if (isset($order['items'])): echo $order['total'];
                                    else: echo ($order['min_price'] == $order['max_price']) ? $order['max_price'] : $order['min_price'] . ' - ' . $order['max_price'];
                                    endif;
                                    ?></span>
                                <br />                         
                                <span style="font-size: 12px;">
                                    <?php if (isset($order['items'])): ?>
                                        <?php if (!empty(localCurrencyAmount($order['merchant_currency_conversion_factor'], $order['customer_currency_conversion_factor'], $order['total'])) && !empty($order['customer_currency_conversion_factor'])): ?>
                                            (approax. <?= $currencies[$order['customer_currency_code']]; ?><?= localCurrencyAmount($order['merchant_currency_conversion_factor'], $order['customer_currency_conversion_factor'], $order['total']); ?> <?= $order['customer_currency_code']; ?>)<sup>*</sup>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if (!empty(localCurrencyAmount($order['merchant_currency_conversion_factor'], $order['customer_currency_conversion_factor'], $order['min_price'])) && !empty($order['customer_currency_conversion_factor'])): ?>
                                            (approax. <?= $currencies[$order['customer_currency_code']]; ?><?= localCurrencyAmount($order['merchant_currency_conversion_factor'], $order['customer_currency_conversion_factor'], $order['min_price']); ?><?php if ($order['min_price'] != $order['max_price']): ?> - <?= $currencies[$order['customer_currency_code']]; ?><?= localCurrencyAmount($order['merchant_currency_conversion_factor'], $order['customer_currency_conversion_factor'], $order['max_price']); ?><?= $order['customer_currency_code']; ?><?php endif; ?>)<sup>*</sup>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            $counter++;
        endforeach;
        ?>
    </div>
    <div class="col-md-12 col-sm-12">
        <div class="col-md-12 pull-right">
            <div id="order-pagination">
                <?= pagination($total_orders, 10, $page); ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <p class="text-center"><img src="<?= $app['base_assets_url']; ?>images/no-data.png" alt="No data"> You've not yet made any orders.</p>
<?php endif; ?>
