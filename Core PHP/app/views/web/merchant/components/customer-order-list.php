<?php if (empty($users)): ?>
    <p class="text-center"><img src="<?= $app['base_assets_url']; ?>images/no-data.png" alt="No data"> You've not yet received any orders.</p>
<?php endif; ?>
<?php
$key = 0;

foreach ($alphabets as $range) {

    $count = count($user_last_name_initial);

    if ($count != $key) {

        if ($user_last_name_initial[$key] == $range) {
            ?>

            <table class="table table-hover table-responsive cutomer_form_detail order-row">
                <thead>
                    <tr>
                        <th><span><?php echo $range; ?></span></th>
                        <th>email</th>
                        <th>telephone</th>
                        <th>spend</th>
                        <th>last order</th>
                        <th></th>    
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($users as $user) {
                        $user_spend = 0;
                        $user_last_order = '';
                        $user_payment_currency_code = '';
                        foreach ($orders as $n_order) {
                            if ($user['id'] == $n_order['user_id']) {
                                $user_spend += $n_order['total'];
                                $user_last_order = $n_order['created_at'];
                                $user_payment_currency_code = $n_order['payment_currency_code'];
                            }
                        }

                        $user_last_name = $user['last_name'];
                        if (ucfirst($user_last_name[0]) == $range) {
                            ?>
                            <tr class="active_cutomer main-tr order_customer_main_show">
                                <td><?php echo ucfirst($user['last_name']) . ", " . ucfirst($user['first_name']); ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td><?php echo $user['mobile_number_prefix'] . " " . $user['mobile_number']; ?></td>
                                <td><?php
                                    if ($user_payment_currency_code != '') {
                                        echo $currencies[$user_payment_currency_code];
                                    }
                                    ?><?php echo getMoney($user_spend); ?></td>
                                <td><?php echo gmdate('d/m/y', strtotime($user_last_order)); ?></td>
                                <td><a href="javascript:void(0);"><img src="<?= $app['base_assets_url']; ?>images/adding_cutomer.png" class="adng_gif get-orders" user-id="<?php echo $user['id']; ?>" alt=""></a></td>    
                            </tr>
                            <?php
                            foreach ($orders as $order) {
                                if ($user['id'] == $order['user_id']) {
                                    ?>
                                    <tr class="active_cutomer new_orders<?php echo $user['id']; ?>" hidden data-order-id="<?= $order['id']; ?>">
                                        <td colspan="6">
                                            <?php if ($order['status'] == 'requested_cancellation'): ?>
                                                <div class="cancel_request_alert">
                                                    <div class="date_detal">
                                                        <h1 style="color:red; font-size: 15px;"><b>ACTION - Customer has requested to cancel order</b></h1>
                                                    </div>
                                                </div> 
                                            <?php endif; ?>
                                            <div class="col-md-2">
                                                <span class="corloe">#<?php echo $order['id']; ?></span><?php
                                                if ($order['payment_currency_code'] != '') {
                                                    echo $currencies[$order['payment_currency_code']];
                                                }
                                                ?><?php
                                                if ($order['total']) {
                                                    echo $order['total'];
                                                }
                                                ?>  &nbsp;&nbsp;    <?php echo gmdate('d/m/y', strtotime($order['created_at'])); ?></span>

                                            </div>
                                            <div class="status-box">
                                                <div <?php if ($order['status'] == 'requested_cancellation'): ?>data-toggle="tooltip" data-placement="left" title="Please action cancellation request before proceeding"<?php endif; ?> class="paking_detal <?php if ($order['status'] == 'in_progress' || $order['status'] == 'declined'): ?>mark-dispatch<?php elseif ($order['status'] == 'requested_cancellation'): ?>pending-cancellation<?php else: ?>fulldis<?php endif; ?>" data-id="<?= $order['id']; ?>">
                                                    <?php if ($order['status'] == 'in_progress' || $order['status'] == 'requested_cancellation' || $order['status'] == 'declined'): ?><img src="<?= $app['base_assets_url']; ?>images/make_despachbox.png" class="make_box" alt=""><?php else: ?><img src="<?= $app['base_assets_url']; ?>images/gry_makepch.png" class="make_box" alt=""><?php endif; ?>
                                                    <div class="table_row"><div class="table_cell">
                                                            <?php if ($order['status'] == 'in_progress' || $order['status'] == 'requested_cancellation' || $order['status'] == 'declined'): ?><span>Mark as Dispatched</span><?php else: ?><span><?= $order['status']; ?></span><?php endif; ?>
                                                        </div></div>
                                                </div> 
                                            </div>
                                            <a href="<?= $app['base_api_mobile_url']; ?>orders/<?= $order['id']; ?>/packaging/" class="packaging-documents" data-id="<?php echo $order['id']; ?>"> 
                                                <div class="paking_detal">
                                                    <img alt="" class="make_box" src="<?= $app['base_assets_url']; ?>images/packing_doc.png">
                                                    <div class="table_row"><div class="table_cell"><span>Packaging Documents</span></div></div>
                                                </div>
                                            </a>
                                            <div class="paking_detal message-button" data-order_id="<?= $order['id']; ?>" data-id="<?= (!empty($order['thread_id'])) ? $order['thread_id'] : ''; ?>" data-second_user_id="<?= (!empty($order['user_id'])) ? $order['user_id'] : ''; ?>">
                                                <img alt="" class="make_box" src="<?= $app['base_assets_url']; ?>images/message_custom.png">
                                                <div class="table_row"><div class="table_cell"><span>Message Customer</span></div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 cancel-box cancel_oder">
                                                <div class="paking_detal <?php if ($order['status'] != 'completed' && $order['status'] != 'cancelled' && $order['status'] != 'declined' && $order['status'] != 'refunded' && $order['status'] != 'partially_refunded' && $order['status'] != 'requested_cancellation'): ?>cancel-order<?php elseif ($order['status'] == 'requested_cancellation'): ?>cancel-requested<?php else: ?>fulldis<?php endif; ?>" data-id="<?php echo $order['id']; ?>">

                                                    <?php if ($order['status'] != 'completed' && $order['status'] != 'cancelled' && $order['status'] != 'declined' && $order['status'] != 'refunded' && $order['status'] != 'partially_refunded'): ?><img src="<?= $app['base_assets_url']; ?>images/cancel_requd.png" class="make_box" alt=""><?php else: ?><img src="<?= $app['base_assets_url']; ?>images/cancel_requd_grey.png" class="make_box" alt=""><?php endif; ?>
                                                    <div class="table_row"><div class="table_cell">
                                                            <span><?php if ($order['status'] == 'in_progress'): ?>Cancel Order<?php elseif ($order['status'] == 'requested_cancellation'): ?>Action Cancellation<?php elseif ($order['status'] == 'cancelled'): ?>Cancelled<?php elseif ($order['status'] == 'declined'): ?>Cancellation Declined<?php else: ?>Refund Order<?php endif; ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>        
                                    </tr>

                                    <?php
                                }
                            }
                        }
                    }
                    ?>

                </tbody>
            </table>
            <?php
            $key++;
        }
    }
}
?>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>