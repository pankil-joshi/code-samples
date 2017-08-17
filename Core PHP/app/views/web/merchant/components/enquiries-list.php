<?php
$counter = 1;
if(!empty($enquiries)):
foreach ($enquiries as $message):
    ?>
    <div class="enquiry-row <?php if ($counter % 2 == 0): ?>deactive_show<?php else: ?>active_show<?php endif; ?> cenrt_object">
        <div style="padding:0px;" class="col-md-2 col-sm-12 col-xs-12">
            <div class="date_detal date_detal1 messge_equrdt">
                <div class="col-md-5">
                    <h1><?= date('M', strtotime($message['sent_at'] . ' UTC')); ?><br><strong><?= date('j', strtotime($message['sent_at'] . ' UTC')); ?></strong><br><span><?= date('H:i', strtotime($message['sent_at'] . ' UTC')); ?></span></h1>
                </div>
                <div class="col-md-7 text-center">
                    <img alt="" class="img-responsive" src="<?= $message['image_thumbnail']; ?>">
                </div>

            </div>
        </div>

        <div class="col-md-9 col-sm-12 col-xs-12 order_manage dispute-text oder_overviw" style="padding-left:0">
            <div style="padding:0" class="col-md-2">
                <div class="air_max mesg_equr">
                    <h1>#Q<?= $message['thread_id']; ?></h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-10">
                <div class="date_detal set_detail_equr">
                    <h1>Type: <?= $message['type']; ?></h1>
                </div>
            </div>
            <div class="col-md-4">
                <div class="air_max mesg_equr">
                    <h1><?= $message['second_user']['first_name']; ?> <?= $message['second_user']['last_name']; ?>, <?= $countries[$message['second_user']['country']]['name']; ?></h1>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="bagwel">
                        <p><span><?= $message['sender_first_name']; ?> : </span> <?= $message['text']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-1 pull-right message-button" data-order_id="<?= $message['order_id']; ?>" data-id="<?= (!empty($message['thread_id'])) ? $message['thread_id'] : ''; ?>" data-second_user_id="<?= (!empty($message['first_user_id'])) ? $message['first_user_id'] : ''; ?>">
            <img src="<?= $app['base_assets_url']; ?>images/message_customer.png" class="mesg_cutomer" alt="">
        </div>
    </div>
<?php
endforeach;
$counter++;
else: ?>
    <p class="text-center"><img src="<?= $app['base_assets_url']; ?>images/no-data.png" alt="No data"> You've not yet received any customer enquiries.</p>
<?php endif; ?>