<?php
if (!empty($orders)):
    $counter = 1;

    foreach ($orders as $key => $order) :
        ?>

        <div class="<?php if ($counter % 2 == 0): ?>deactive_show<?php else: ?>active_show<?php endif; ?> order-row" data-order-id="<?= $order['id']; ?>">
            <div style="padding:0px;" class="col-md-3 col-sm-12 col-xs-12">
                <div class="date_detal date_detal1">
                    <div class="col-md-4">    
                        <h1><?php echo convert_datetime($order['created_at'], $user['timezone'], 'M'); ?> <br><strong><?php echo convert_datetime($order['created_at'], $user['timezone'], 'd'); ?></strong><br><span><?php echo convert_datetime($order['created_at'], $user['timezone'], 'H:i'); ?></span></h1>
                    </div>
                    <div class="col-md-6 text-center">
                        <img alt="" class="img-responsive" src="<?= $order['items'][0]['media_deleted'] == 0 ? $order['items'][0]['media_thumbnail_image'] : $app['base_assets_url'] . 'images/image_deleted.jpg'; ?>">
                    </div>

                </div>
            </div>

            <div class="col-md-9 col-sm-12 col-xs-12 order_manage pull-right dispute-text oder_overviw">
                <div style="padding:0" class="col-md-2">
                    <div class="air_max">
                        <h1>#<?= $order['id']; ?></h1>
                        <h1><?php
                            if ($order['base_currency_code'] != '') {
                                echo $currencies[$order['base_currency_code']];
                            }
                            ?><?php
                            if ($order['total']) {
                                echo $order['total'];
                            }
                            ?></h1>
                    </div>
                </div>
                <div class="col-md-10 col-sm-10">
                    <div class="date_detal">
                        <h1><?php
                            if ($order['items'][0]['media_title'] != '') {
                                echo $order['items'][0]['media_title'] . ', ';
                            }
                            ?><?php
                            if ($order['postage_option_label'] != '') {
                                echo "(" . $order['postage_option_label'] . ")";
                            }
                            ?>
                            <br /><?php
                            if ($order['user_first_name'] != '' && $order['user_last_name'] != '') {
                                echo $order['user_first_name'] . " " . $order['user_last_name'] . ',';
                            }
                            ?> <?php
                            if ($order['delivery_address']['line_1'] != '') {
                                echo $order['delivery_address']['line_1'];
                            }
                            ?><?php
                            if ($order['delivery_address']['line_2'] != '') {
                                echo " " . $order['delivery_address']['line_2'];
                            }
                            ?><?php
                            if ($order['delivery_address']['line_3'] != '') {
                                echo " " . $order['delivery_address']['line_3'];
                            }
                            ?><?php
                            if ($order['delivery_address']['city'] != '') {
                                echo ", " . $order['delivery_address']['city'];
                            }
                            ?><?php
                            if ($order['delivery_address']['state'] != '') {
                                echo ", " . $order['delivery_address']['state'];
                            }
                            ?><?php
                            if ($order['delivery_address']['country'] != '') {
                                echo ", " . $countries[$order['delivery_address']['country']]['name'];
                            }
                            ?></h1>
                    </div>
                </div>
                <?php if ($order['status'] == 'requested_cancellation'): ?>
                 <div class="cancel_request_alert">
                    <div class="date_detal">
                        <h1 style="color:red; font-size: 15px;"><b>ACTION - Customer has requested to cancel order</b></h1>
                    </div>
                </div> 
                 <?php endif;?>
                <div class="col-md-12">
                    <div class="row">
                        <div class="row">
                            <div class="col-md-3 status-box">
                                <div <?php if ($order['status'] == 'requested_cancellation'): ?>data-toggle="tooltip" data-placement="left" title="Please action cancellation request before proceeding"<?php endif; ?> class="paking_detal <?php if ($order['status'] == 'in_progress' || $order['status'] == 'declined'): ?>mark-dispatch<?php elseif ($order['status'] == 'requested_cancellation'): ?>pending-cancellation<?php else: ?>fulldis<?php endif; ?>" data-id="<?= $order['id']; ?>">
                                    <?php if ($order['status'] == 'in_progress' || $order['status'] == 'requested_cancellation' || $order['status'] == 'declined'): ?><img src="<?= $app['base_assets_url']; ?>images/make_despachbox.png" class="make_box" alt=""><?php else: ?><img src="<?= $app['base_assets_url']; ?>images/gry_makepch.png" class="make_box" alt=""><?php endif; ?>
                                    <div class="table_row"><div class="table_cell">
                                            <?php if ($order['status'] == 'in_progress' || $order['status'] == 'requested_cancellation' || $order['status'] == 'declined'): ?><span>Mark as Dispatched</span><?php else: ?><span><?= $order['status']; ?></span><?php endif; ?>
                                        </div></div>
                                </div>    
                            </div>
                            <div class="col-md-3">
                                <a href="<?= $app['base_api_mobile_url']; ?>orders/<?= $order['id']; ?>/packaging/" class="packaging-documents" data-id="<?php echo $order['id']; ?>"> 
                                    <span class="paking_detal">
                                        <img src="<?= $app['base_assets_url']; ?>images/packing_doc.png" class="make_box" alt="">
                                        <div class="table_row"><div class="table_cell">
                                                <span>Packaging Documents</span>
                                            </div></div>
                                    </span> 
                                </a>
                            </div>  

                            <div class="col-md-3">
                                <div class="paking_detal message-button" data-order_id="<?= $order['id']; ?>" data-id="<?= (!empty($order['thread_id'])) ? $order['thread_id'] : ''; ?>" data-second_user_id="<?= (!empty($order['user_id'])) ? $order['user_id'] : ''; ?>">
                                    <img src="<?= $app['base_assets_url']; ?>images/message_custom.png" class="make_box" alt="">
                                    <div class="table_row"><div class="table_cell">
                                            <span>Message Customer</span>
                                        </div></div>
                                </div>    
                            </div>  
                            <div class="col-md-3 cancel-box">
                                <div class="paking_detal <?php if ($order['status'] != 'completed' && $order['status'] != 'cancelled' && $order['status'] != 'declined' && $order['status'] != 'refunded' && $order['status'] != 'partially_refunded' && $order['status'] != 'requested_cancellation'): ?>cancel-order<?php elseif ($order['status'] == 'requested_cancellation'):?>cancel-requested<?php else: ?>fulldis<?php endif; ?>" data-id="<?php echo $order['id']; ?>">

                                    <?php if ($order['status'] != 'completed' && $order['status'] != 'cancelled' && $order['status'] != 'declined' && $order['status'] != 'refunded' && $order['status'] != 'partially_refunded'): ?><img src="<?= $app['base_assets_url']; ?>images/cancel_requd.png" class="make_box" alt=""><?php else: ?><img src="<?= $app['base_assets_url']; ?>images/cancel_requd_grey.png" class="make_box" alt=""><?php endif; ?>
                                    <div class="table_row"><div class="table_cell">
                                            <span><?php if ($order['status'] == 'in_progress'): ?>Cancel Order<?php elseif ($order['status'] == 'requested_cancellation'): ?>Action Cancellation<?php elseif($order['status'] == 'cancelled'): ?>Cancelled<?php elseif($order['status'] == 'declined'): ?>Cancellation Declined<?php else: ?>Refund Order<?php endif; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $counter++;
    endforeach;
else:
    ?>
    <p class="text-center"><img src="<?= $app['base_assets_url']; ?>images/no-data.png" alt="No data"> You don't have any orders to display.</p>
    <?php endif; ?>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>