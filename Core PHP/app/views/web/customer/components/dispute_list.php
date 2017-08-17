<?php     if (!empty($disputes)): $counter = 1;

foreach ($disputes as $dispute): ?>
    <div class="<?php if ($counter % 2 == 0): ?>deactive_show<?php else: ?>active_show<?php endif; ?> dispute-row">
        <div class="col-md-4" style="padding:0px;">
            <div class="date_detal">
                <div class="col-md-5">    
                    <h1><?= convert_datetime($dispute['sent_at'], $user['timezone'], 'F'); ?><br><?= convert_datetime($dispute['sent_at'], $user['timezone'], 'd'); ?></h1>
                </div>
                <div class="col-md-3 text-center">
                    <img src="<?= $dispute['image_thumbnail']; ?>" class="img-responsive" alt="">
                </div>
                <div class="col-md-4" style="padding:0">
                    <div class="air_max">
                        <h1><?= $dispute['media_title']; ?></h1>
                        <p>from @<?= $dispute['merchant_instagram_username']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 col-sm-12 order_manage pull-right dispute-text">
            <div class="col-md-5 col-sm-5">
                <div class="date_detal">
                    <div class="col-md-12">    
                        <h1>Status: <?= $dispute['status']; ?></h1>
                        <h1>Outcome: None required</h1>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-6">
                <div class="date_detal">
                    <div class="col-md-12">    
                        <h1>@<?= $dispute['sender_instagram_username']; ?>:  <?= $dispute['text']; ?></h1>
                    </div>
                </div>
            </div>

            <div class="col-md-1 col-sm-1">
                <div class=" price_detail">
                    <a class="message-button" href="#" data-id="<?= $dispute['thread_id']; ?>" data-second_user_id="<?= $dispute['second_user']['id']; ?>"><img src="<?= $data['base_assets_url']; ?>images/cheat.png" class="img-responsive" alt=""></a>
                </div>
            </div>
        </div>
    </div>
    <?php $counter++;
endforeach; ?>
<?php else: ?>
    <p class="text-center"><img src="<?= $app['base_assets_url']; ?>images/no-data.png" alt="No data"> There are no disputes to show.</p>
<?php endif; ?>