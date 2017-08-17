<?php include_once $this->getPart('/web/common/header.php'); ?> 

<div class="secure_pin">
    <h6><img src="<?= $data['base_assets_url']; ?>images/shield.png" class="img-responsive img_shild" alt="">Tagzie is in ultra-secure mode. Shop with confidence.</h6>  
</div>

<div class="fles_sale check_outstp product_detail">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-6">
                    <img src="<?= $media['image_standard_resolution']; ?>" class="img-responsive" alt="">

                    <div class="chack_demain">
                        <div class="row">    
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <img src="<?= $media['user_instagram_profile_picture']; ?>" class="img-responsive" alt="">
                            </div>

                            <div class="col-md-9 col-sm-9 col-xs-9">
                                <h6>@<?= $media['instagram_username']; ?></h6>
                                <p>Seller rating:
                                    <?php for ($i = 0; $i < 5; $i++): ?>
                                        <span class="glyphicon <?php if ($i < $media['seller_rating']): ?>glyphicon-star<?php else: ?>glyphicon-star-empty<?php endif; ?>"></span>
                                    <?php endfor; ?>
                                </p>
                            </div>
                        </div>
                    </div>


                    <div class="chack_demain exprs_detail">
                        <div class="row">    
                            <ul>
                                <li>
                                    <img src="<?= $data['base_assets_url']; ?>images/hard_umg.png" class="img-responsive" alt="">
                                    <span><?= (int) $media['likes']; ?></span>
                                </li>

                                <?php if (!empty($media['is_refundable_disabled'])): ?>
                                    <li>
                                        <img src="<?= $data['base_assets_url']; ?>images/bespoke.png" style="width:33px;" class="img-responsive" alt="">
                                        <span>Bespoke Item</span>   
                                    </li>
                                <?php endif; ?>

                                <?php if ($media['express_delivery']): ?>
                                    <li>
                                        <img src="<?= $data['base_assets_url']; ?>images/car.png" class="img-responsive" alt="">
                                        <span>Express
                                            Delivery</span>    
                                    </li>

                                <?php endif; ?>
                                <?php if ($media['worldwide_shipping']): ?>
                                    <li>
                                        <img src="<?= $data['base_assets_url']; ?>images/goal.png" class="img-responsive" alt="">
                                        <span>Worldwide
                                            Shipping</span>    
                                    </li>
                                <?php endif; ?>
                                <?php if ($media['top_seller']): ?>
                                    <li>
                                        <img src="<?= $data['base_assets_url']; ?>images/seller.png" class="img-responsive" alt="">
                                        <span>Top
                                            Seller</span>    
                                    </li>
                                <?php endif; ?>
                        </div>
                    </div>
                    <div class="caller_sociel">
                        <div class="row"> 
                            <div class="col-md-4 col-sm-4 col-xs-12"><a href="<?= (!empty($media['merchant']['merchant_business_telephone_prefix']) && !empty($media['merchant']['merchant_legal_entity_phone_number'])) ? 'tel:' . $media['merchant']['merchant_business_telephone_prefix'] . $media['merchant']['merchant_legal_entity_phone_number'] : '#'; ?>"><div class="bak_color"><span class=""><img alt="" class="img-responsive" src="<?= $app['base_assets_url']; ?>images/iphone.png"> </span><p>Call<br> Seller</p></div></a>  </div>
                            <div class="col-md-4 col-sm-4 col-xs-12"><?php if (!empty($user['is_active'])): ?><a href="#" class="message-button" data-type="enquiry" data-product_id="<?= $media['id']; ?>" data-id="" data-second_user_id="<?= $media['user_id']; ?>"><div class="bak_color"><span class="magr_img"><img alt="" class="img-responsive" src="<?= $app['base_assets_url']; ?>images/mesage.png"></span><p>Ask A <br>Question</p></div></a><?php endif; ?></div>
                            <div class="col-md-4 col-sm-4 col-xs-12" ><?php if (!empty($user)): ?><a href="#" data-toggle="modal" data-target="#report-product"><div class="bak_color"><span class=""><img alt="" class="img-responsive" src="<?= $app['base_assets_url']; ?>images/shape_wht.png"></span><p>Report <br>Product</p> </div></a><?php endif; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-12">
                        <div class="checkout_demain">
                            <h1><?= $media['title']; ?></h1>
                            <p>
                                <?php
                                if ($media['variants'][0]['is_default'] == 0):
                                    $price = false;

                                    if ($media['min_price'] != $media['max_price']) {

                                        $price = true;
                                    }
                                    if ($price):
                                        ?>
                                        from <span class="currency_code"><?= $media['base_currency_code']; ?></span><?= ($media['tax']['inclusive']) ? getExclusiveAmount($media['min_price'], $media['tax']['rate']) : $media['min_price']; ?> <?php if (isset($user) && !empty(localCurrencyAmount($media['merchant_currency_conversion_factor'], $user['customer_currency_conversion_factor'], $media['min_price'])) && !empty($user['customer_currency_conversion_factor'])): ?>(<?= $currencies[$user['currency_code']]; ?><?= localCurrencyAmount($media['merchant_currency_conversion_factor'], $user['customer_currency_conversion_factor'], (($media['tax']['inclusive']) ? getExclusiveAmount($media['min_price'], $media['tax']['rate']) : $media['min_price'])); ?> <?= $user['currency_code']; ?>)<?php endif; ?> to <span class="currency_code"><?= $media['base_currency_code']; ?></span><?= ($media['tax']['inclusive']) ? getExclusiveAmount($media['max_price'], $media['tax']['rate']) : $media['max_price']; ?> <?php if (isset($user) && !empty(localCurrencyAmount($media['merchant_currency_conversion_factor'], $user['customer_currency_conversion_factor'], $media['min_price'])) && !empty($user['customer_currency_conversion_factor'])): ?>(<?= $currencies[$user['currency_code']]; ?><?= localCurrencyAmount($media['merchant_currency_conversion_factor'], $user['customer_currency_conversion_factor'], (($media['tax']['inclusive']) ? getExclusiveAmount($media['min_price'], $media['tax']['rate']) : $media['min_price'])); ?> <?= $user['currency_code']; ?>)<?php endif; ?>
                                    <?php else: ?>
                                        <span class="currency_code"><?= $media['base_currency_code']; ?></span><?= ($media['tax']['inclusive']) ? getExclusiveAmount($media['min_price'], $media['tax']['rate']) : $media['min_price']; ?> <?php if (isset($user) && !empty(localCurrencyAmount($media['merchant_currency_conversion_factor'], $user['customer_currency_conversion_factor'], $media['min_price'])) && !empty($user['customer_currency_conversion_factor'])): ?>(<?= $currencies[$user['currency_code']]; ?><?= localCurrencyAmount($media['merchant_currency_conversion_factor'], $user['customer_currency_conversion_factor'], (($media['tax']['inclusive']) ? getExclusiveAmount($media['min_price'], $media['tax']['rate']) : $media['min_price'])); ?> <?= $user['currency_code']; ?>)<?php endif; ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="currency_code"><?= $media['base_currency_code']; ?></span><?= ($media['tax']['inclusive']) ? getExclusiveAmount($media['min_price'], $media['tax']['rate']) : $media['min_price']; ?> <?php if (isset($user) && !empty(localCurrencyAmount($media['merchant_currency_conversion_factor'], $user['customer_currency_conversion_factor'], $media['min_price'])) && !empty($user['customer_currency_conversion_factor'])): ?>(<?= $currencies[$user['currency_code']]; ?><?= localCurrencyAmount($media['merchant_currency_conversion_factor'], $user['customer_currency_conversion_factor'], (($media['tax']['inclusive']) ? getExclusiveAmount($media['min_price'], $media['tax']['rate']) : $media['min_price'])); ?> <?= $user['currency_code']; ?>)<?php endif; ?>
                                <?php endif; ?>    
                            </p>

                        </div>
                    </div>
                    <div class="col-md-12">
                        <form action="<?= $app['base_url']; ?>checkout/" method="GET">
                            <input type="hidden" name="media_id" value="<?= $media['id']; ?>">                        
                            <div class="delver">
                                <div class="<?php if ($media['variants'][0]['is_default'] == 0): ?>col-md-6<?php else: ?>hidden<?php endif; ?>">
                                    <div class="product-variant-selector">
                                        <?php if ($media['variants'][0]['is_default'] == 0): ?>
                                            <select id="variant-selector" class="selectpicker" name="variant_id">
                                                <?php foreach ($media['variants'] as $variant): ?>

                                                    <option value="<?= $variant['id']; ?>" data-variantid="<?= $variant['id']; ?>" data-stockquantity="<?= $variant['stock_quantity']; ?>" data-currencycode="<?= $media['base_currency_code']; ?>"><?= $variant['label']; ?> {<?= $media['base_currency_code']; ?>}<?= ($media['tax']['inclusive']) ? getExclusiveAmount($variant['price'], $media['tax']['rate']) : $variant['price']; ?> <?php if (isset($user) && !empty(localCurrencyAmount($media['merchant_currency_conversion_factor'], $user['customer_currency_conversion_factor'], $variant['price'])) && !empty($user['customer_currency_conversion_factor'])): ?>(<?= $currencies[$user['currency_code']]; ?><?= localCurrencyAmount($media['merchant_currency_conversion_factor'], $user['customer_currency_conversion_factor'], (($media['tax']['inclusive']) ? getExclusiveAmount($media['min_price'], $media['tax']['rate']) : $variant['price'])); ?> <?= $user['currency_code']; ?>)<?php endif; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php else: ?>
                                            <input type="hidden" value="<?= $media['variants'][0]['id']; ?>" name="variant_id">
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-<?php if ($media['variants'][0]['is_default'] == 0): ?>6<?php else: ?>12<?php endif; ?> text-center">
                                    <button href="#" style="<?php if ($media['variants'][0]['is_default'] == 1): ?>width:65%;margin: 0 auto;float: none;padding: 10px 20px;<?php endif; ?>" <?= ($media['variants'][0]['stock_quantity'] == 0 || $media['is_available'] == 0) ? 'disabled' : ''; ?>><img src="<?= $data['base_assets_url']; ?>images/adding.gif" class="adig" alt=""><span class="buy-button-text"><?php if ($media['variants'][0]['stock_quantity'] > 0 && $media['is_available'] == 1): ?><img src="<?= $data['base_assets_url']; ?>images/cart-of-ecommerce.png" class="img-responsive img_shild" alt="">PURCHASE<?php elseif ($media['is_available'] == 0): ?>Not available<?php else: ?>Out of Stock<?php endif; ?></span> </button>
                                </div>
                                <p>Shipping from <?php if ($media['postage_option_rates']['min_rate'] > 0): ?><span class="currency_code"><?= $media['base_currency_code']; ?></span><?= $media['postage_option_rates']['min_rate']; ?> <?php if (isset($user) && !empty(localCurrencyAmount($media['merchant_currency_conversion_factor'], $user['customer_currency_conversion_factor'], $media['postage_option_rates']['min_rate'])) && !empty($user['customer_currency_conversion_factor'])): ?>(<?= $currencies[$user['currency_code']]; ?><?= localCurrencyAmount($media['merchant_currency_conversion_factor'], $user['customer_currency_conversion_factor'], $media['postage_option_rates']['min_rate']); ?> <?= $user['currency_code']; ?>)<?php endif; ?><?php else: ?>Free<?php endif; ?>. Delivery available within <?= $media['attributes']['dispatch_days']; ?> day<?= ($media['attributes']['dispatch_days'] > 1) ? 's' : ''; ?>.</p>
                            </div>
                        </form>            
                    </div>
                    <div class="col-md-12">
                        <div class="pair_prgh">
                            <?= $media['description']; ?>
                        </div>
                        <?php if (!empty($media['is_refundable_disabled'])): ?>
                            <div class="bespok_desc">Please note: This is a bespoke / made-to-order item. Once your order has been placed, you will be unable to cancel or receive a refund unless there is an issue with the item.</div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <div class="flaging">
                            <h2>Shipping</h2>
                            <?php foreach ($media['postage_options'] as $postage_option): ?>
                                <div class="marg_space">    
                                    <div class="col-md-6">
                                        <div class="row">
                    <!--                    <img src="<?= $data['base_assets_url']; ?>images/englant_fleg.png" class="img-responsive" alt="">-->
                                            <p><?= $postage_option['label']; ?></p>
                                        </div></div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <p><?php if ($postage_option['rate'] > 0): ?><span class="currency_code"><?= $postage_option['rate_currency']; ?></span><?= $postage_option['rate']; ?> <?php if (isset($user) && !empty(localCurrencyAmount($media['merchant_currency_conversion_factor'], $user['customer_currency_conversion_factor'], $postage_option['rate'])) && !empty($user['customer_currency_conversion_factor'])): ?><br>(<?= $currencies[$user['currency_code']]; ?><?= localCurrencyAmount($media['merchant_currency_conversion_factor'], $user['customer_currency_conversion_factor'], $postage_option['rate']); ?> <?= $user['currency_code']; ?>)<?php endif; ?><?php else: ?>Free<?php endif; ?></p>    
                                        </div>
                                    </div>
                                </div>   
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="flaging">
                            <h2>Refunds</h2>
                            <?php if (!empty($media['is_refundable_disabled'])): ?>
                                <p>This is a bespoke / made-to-order item. Once your order has been placed, you will be unable to cancel or receive a refund unless there is an issue with the item.</p>
                            <?php else: ?>
                                <p>This purchase is protected by our <a href="<?= $app['base_url']; ?>legal/refund"><span class="primary-oragnge underline">Effortless Refund Guarantee.</span></a> Request a refund and return within <?= $app['order_hold_period']; ?> days of receiving your order and we’ll expedite your refund.</p>    
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once $this->getPart('/web/components/report-product.php'); ?>
<?php include_once $this->getPart('/web/components/message.php'); ?>
<?php include_once $this->getPart('/web/components/new_thread.php'); ?>
<script>
    $(document).ready(function () {
        $('body').on('click', '.message-button', function (e) {
            e.preventDefault();
            activeSendButton = $(this);
            if ($(this).data('id')) {
                var data = {thread_id: $(this).data('id'), second_user_id: $(this).data('second_user_id')};
                refreshThread(data);
            } else {
                $('#new-thread .type').val('');
                $('#new-thread .message-text').val('');
                $('#new-thread').data('product_id', $(this).data('product_id'));
                $('#new-thread').data('second_user_id', $(this).data('second_user_id'));
                $('#new-thread .modal-title').text($(this).data('type'));
                $('#new-thread').modal('show');
            }

        });
        replaceCurrencyCode();

        $('#variant-selector').change(function () {

            var stockQuantity = $(this).find(':selected').data('stockquantity');

            var mediaIsAvailable = <?= $media['is_available'] ? 'true' : 'false'; ?>;

            if (stockQuantity > 0 && mediaIsAvailable) {
                $('.buy-button-text').parent('button').attr('disabled', false);
                $('.buy-button-text').html('<img src="<?= $data['base_assets_url']; ?>images/cart-of-ecommerce.png" class="img-responsive img_shild" alt=""> Purchase');

            } else if (!mediaIsAvailable) {

                $('.buy-button-text').parent('button').attr('disabled', true);
                $('.buy-button-text').text('Not available');

            } else {

                $('.buy-button-text').parent('button').attr('disabled', true);
                $('.buy-button-text').text('Out of Stock');

            }

        });
        $('#variant-selector').trigger('change');
    });
</script>
<?php include_once $this->getPart('/web/common/footer.php'); ?>    
