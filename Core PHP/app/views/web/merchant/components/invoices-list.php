<?php
if (!empty($settlements)):
    foreach ($settlements as $settlement):
        ?>
        <tr class="active_cutomer setme_from invoice-row">
            <td class="td_set_width">
                <a href="<?= $app['base_api_mobile_url']; ?>merchant/payments/<?= $settlement['id']; ?>/invoice/"><img src="<?= $app['base_assets_url']; ?>images/adding_cutomer.png" class="adng_gif" alt=""></a>
            </td>
            <td>#TGZ<?= $settlement['id']; ?></td>
            <td><?= date('d/m/Y', strtotime($settlement['created_at'])); ?></td>
            <td><?= $currencies[$merchant['business_currency']]; ?><?= number_format($settlement['amount'], 2); ?></td>
            <td><?= $settlement['number_of_orders']; ?></td>
            <td><?= $settlement['type']; ?></td>
        </tr>
    <?php endforeach;
else: ?>
    <tr><td colspan="6"><p class="text-center"><img src="<?= $app['base_assets_url']; ?>images/no-data.png" alt="No data"> You don't have any invoices.</p></td></tr>
<?php endif; ?>