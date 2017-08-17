
    <?php
    $counter = 1;

    foreach ($devices as $device):
        ?>
        <tr class="device-row ">
            <td><?= $device['id']; ?></td>
            <td><?= gmdate('d/m/y H:i:s', strtotime($device['updated_at'] . ' UTC')); ?></td>
            <td><?= $device['platform']; ?></td>            
            <td><?= $device['version']; ?></td>
            <td><?= $device['model']; ?></td>
            <td><?= $device['manufacturer']; ?></td>
            <td><?= $device['notification_id']; ?></td>
            <td>
               <?= ($device['logout'] == 1)? 'No' : 'Yes' ?>
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
        <?= pagination($total_devices, 10, $page); ?>
        </div>
    </div>
     </td>
</tr>