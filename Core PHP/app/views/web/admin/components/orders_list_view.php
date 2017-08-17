
    <?php
    $counter = 1;

    foreach ($orders as $order):
        ?>
        <tr class="order-row ">
            <td><?= $order['id']; ?></td>
            <td><?= gmdate('d/m/y H:i:s', strtotime($order['created_at'] . ' UTC')); ?></td>
            <td><img height="80" src="<?= $order['items'][0]['media_thumbnail_image']; ?>"></td>            
            <td><?= $order['media_title']; ?></td>
            <td><?= $order['items'][0]['user_instagram_username']; ?></td>
            <td><?= $order['status']; ?></td>
            <td><span><?= $currencies[$order['base_currency_code']]; ?><?= $order['total']; ?></span></td>
            <td>
               <?php if($order['status'] != 'cancelled' && $order['status'] != 'refunded' && $order['status'] != 'partially_refunded' && $order['status'] != 'completed'): ?> <a href="#" data-id="<?= $order['id']; ?>" class="cancel new_cab">Cancel/Refund</a><?php endif; ?>
            </td>
        </tr>

        <?php
        $counter++;
    endforeach;
    ?>

 <tr class="user-row ">
     <td colspan="10">
    <div class="text-center">
        <div id="order-pagination">
        <?= pagination($total_orders, 10, $page); ?>
        </div>
    </div>
     </td>
</tr>