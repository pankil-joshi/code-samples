<?php
if (!empty($media)):
    $counter = 1;

    foreach ($media as $row):
        ?>        
        <div class="product-row <?php if ($counter % 2 == 0): ?>deactive_show<?php else: ?>active_show<?php endif; ?> cenrt_object" data-id="<?= $row['id']; ?>">
            <div style="padding:0px;" class="col-md-1 col-sm-12 col-xs-12">
                <div class="date_detal date_detal1 messge_equrdt">
                    <div class="col-md-12 text-center">
                        <img alt="" class="img-responsive" src="<?= $row['image_thumbnail']; ?>">
                    </div>

                </div>
            </div>
            <div class="col-md-10 col-sm-12 col-xs-12 order_manage dispute-text oder_overviw flex-center">
                <div class="col-md-3">
                    <div class="air_max mesg_equr span_gry">
                        <h1><input type="text" value="<?= $row['title']; ?>" style="
                                   background: none;
                                   border: 0;
                                   color: rgb(255, 90, 0);
                                   font-weight: bold;
                                   font-size: 18px;
                                   " name="title"></h1>
                        <span><?= date('d/m/y, h:i a', strtotime($row['created_at'])); ?></span>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="date_detal set_detail_equr">

                        <label class="switch">
                            <input type="checkbox" <?= ($row['is_available']) ? 'checked' : ''; ?>>
                            <div class="slider round is-available"></div>
                        </label>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <?php
                        $counter1 = 1;
                        foreach ($row['variants'] as $variant):
                            ?>

                            <div class="col-md-3">

                                <div class="air_max mesg_equr set_price_box">
                                    <?php if ($counter1 == 1): ?><?php if ($variant['is_default'] == 0): ?><label>Variants </label><?php endif; ?><?php endif; ?>
                                    <span><?= $variant['label']; ?></span>
                                </div>                      
                            </div>                    
                            <div class="col-md-3">
                                <div class="air_max mesg_equr set_price_box set_first_price_box">
                                    <?php if ($counter1 == 1): ?><div class="col-md-10 col-md-offset-2"><label>Price </label></div><?php endif; ?>
                                    <div class="input-group bot_marg">
                                        <div class="col-md-2 padding0">
                                            <span class="input-group-addon">
                                                <?= $currencies[$row['base_currency_code']]; ?>
                                            </span>
                                        </div>
                                        <div class="col-md-10 padding0">
                                            <input style="width: 100%;" type="text" class="inp_mang variant-price" data-id="<?= $variant['id']; ?>" value="<?= $variant['price']; ?>">
                                        </div>
                                    </div>                                        
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="air_max mesg_equr set_price_box">
                                    <?php if ($counter1 == 1): ?><label>Stock </label><?php endif; ?>
                                    <input type="text" class="inp_mang variant-quantity" style="width:100%" value="<?= $variant['quantity']; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="air_max mesg_equr set_price_box">
                                    <?php if ($counter1 == 1): ?><label>Stock Alert </label><?php endif; ?>
                                    <input type="text" class="inp_mang variant-stock-alert" style="width:100%" value="<?= $variant['min_stock_level']; ?>">
                                </div>
                            </div>                            
                            <div class="clearfix"></div>       
                            <?php
                            $counter1++;
                        endforeach;
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-1 pull-right">
                <a href="#" class="check_main_box update-product" title="Update"><i class="fa fa-check" aria-hidden="true"></i></a>
            </div>
        </div>
        <?php
        $counter++;
    endforeach;
else:
    ?>
    <p class="text-center"><img src="<?= $app['base_assets_url']; ?>images/no-data.png" alt="No data"> You don't have any products.</p>
<?php endif; ?>