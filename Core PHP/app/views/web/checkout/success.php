<?php include_once $this->getPart('/web/common/header.php'); ?> 
<?php include_once $this->getPart('/web/checkout/sub_header.php'); ?>
<div class="check_outstp">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-12">
                    <div class="chack_demain edit_ordr">
                        <div class="row">    
                            <div class="col-md-1 col-sm-3 col-xs-3">
                                <img src="<?= $order['items'][0]['media_thumbnail_image']; ?>" class="img-responsive" alt="">
                            </div>

                            <div class="col-md-11 col-sm-9 col-xs-9 ordr_comp">
                                <h6>Order Complete</h6>
                                <?php $order; ?>
                                <p><span>Ref: #<?= $order['id']; ?></span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12"><hr></div>
            </div>
            <div class="col-md-12">

                <div class="col-md-5">
                    <div class="discont new_setd">
                        <h6>Order Summary</h6>
                        <p>Reference: #<?= $order['id']; ?></p>
                        <p>Order total: <?= $currencies[$order['base_currency_code']] . $order['total']; ?></p>
                        <p>Estimated delivery date: <?= convert_datetime(gmdate('d-m-Y H:i:s', strtotime('+' . ($order['expected_dispatch_time'] + $order['expected_transit_time']) . ' days UTC')), $user['timezone'], 'd/m/Y'); ?></p>
                        <p>Address:                          
                            <?php $deliveryAddress = unserialize($order['delivery_address']); ?>
                            <?= $deliveryAddress['first_name']; ?>  <?= $deliveryAddress['last_name']; ?>
                            <?= $deliveryAddress['line_1']; ?>
                            <?= $deliveryAddress['city']; ?>, <?= $deliveryAddress['zip_code']; ?>
                            <?= $countries[$deliveryAddress['country']]['name']; ?>
                        </p>
                        <a class="invoce_vw" href="<?= $app['base_api_mobile_url']; ?>orders/<?= $order['id']; ?>/invoice/">VIEW INVOICE</a>
                    </div>
                </div>
                <div class="col-md-7 subtotal">
                    <div class="col-md-12">
                        <div class="iner_wlat"> 
                            <p class="iwagtant">Thank you for placing an order from  <span>@<?= $order['items'][0]['user_instagram_username']; ?>.</span></p>
                            <p class="iwagtant">You will be kept updated on the progress of your order 
                                by the seller through Tagzie.</p>    
                            <h1><strong>What Happens Next?</strong></h1> 
                            <p>Once you’ve received the order and you’re happy with it, 
                                we would appreciate it if you could leave feedback for the 
                                seller on Tagzie to help other potential customers.</p>
                            <p><?php if (!empty($media['is_refundable_disabled'])): ?>
                                    This is a bespoke / made-to-order item. Once your order has been placed, you will be unable to cancel or receive a refund unless there is an issue with the item
                                <?php else: ?>
                                    Refunds: You have a cooling off period of 14 days from the 
                                    date of receiving the order to request a refund from the 
                                    seller.
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>    
            </div>

        </div>
    </div>  
</div>
<?php include_once $this->getPart('/web/common/footer.php'); ?>