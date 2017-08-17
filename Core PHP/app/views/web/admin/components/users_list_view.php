
<?php
$counter = 1;
foreach ($users as $user):

    if ($user['customer_deactivate'] == '1') {

        $status_val = 'Activate';
        $action = '0';
        $status = 'Yes';
    } else {

        $status_val = 'Deactivate';
        $action = '1';
        $status = 'No';
    }
    ?>
    <tr class="user-row btn_user_row_main">
        <td><?= $user['id']; ?></td>
        <td><?= $user['instagram_username']; ?></td>
        <td><?= (empty($user['instagram_username'])) ? 'Guest' : ''; ?><?= ($user['is_active'] == '1') ? 'Customer' : ''; ?><?= ($user['is_merchant'] == '1') ? '/Merchant' : ''; ?> <?= ($user['is_merchant'] == '1')? '(' . $user['subscription_packages_name'] . ')': ''; ?></td>
        <td><?= $user['first_name'] . ' ' . $user['last_name']; ?></td>
        <td><?= $user['email']; ?></td>
        <td><?= $user['mobile_number']; ?></td>
        <td><?= $user['country'] != '' ? $user['country_name'] . '/' . $user['currency_code'] : ''; ?></td>
        <td><?= $status; ?></td>
        <td><?= gmdate('d/m/y H:i:s', strtotime($user['created_at'] . ' UTC')); ?></td>
        <td>
            <a href="<?= $app['base_url']; ?>admin/users/<?= $user['id']; ?>/edit/"><button class="btn_main_style">Edit</button></a>
            <a href="<?= $app['base_url']; ?>admin/users/<?= $user['id']; ?>/orders/"><button class="btn_main_style">Orders</button></a>
            <a href="<?= $app['base_url']; ?>admin/users/<?= $user['id']; ?>/devices/"><button class="btn_main_style">Devices</button></a>
            <button data-id="<?= $user['id']; ?>" data-user="<?= $user['is_merchant'] == '1' ? 'merchant' : 'customer'; ?>" class="delete_user btn_main_style" style="background-color:rgb(176, 5, 5)">Delete</button>
            <button data-id="<?= $user['id']; ?>" class="update_status btn_main_style" data-action="<?= $action; ?>" class="update_status" style="background-color:rgb(176, 5, 5)"><?= $status_val; ?></button>
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
                <?= pagination($total_users, 10, $page); ?>
            </div>
        </div>
    </td>
</tr>