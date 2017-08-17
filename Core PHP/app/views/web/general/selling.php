<?php include_once $this->getPart('/web/general/common/header.php'); ?>

<style>
    @media only screen and (max-width: 800px) {
        <?php
        $counter = 2;
        foreach ($subscriptionPackages as $package):
            ?>

            .selling_page #no-more-tables td:nth-child(<?= $counter; ?>):before { content: "<?= $package['name']; ?>"; padding-left: 5px; font-weight: normal; }

            <?php
            $counter++;
        endforeach;
        ?>
    }
</style>

<div class="main_about_page buying_page selling_page">
    <div class="banner_about"><img src="<?= $app['base_assets_url']; ?>images/selling_banner.jpg" /></div>




    <div class="container">
        <div class="main_about_middle_cont">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="bread_main_pedding">
                    <h1 style="margin-bottom: 0;">Join the Tagzie Revolution</h1>
                    <div class="verb_txt buying_shop new_li_join">
                        <ul>
                            <li>Begin selling on the Tagzie marketplace today; directly engage with your followers and turn them into customers!</li>
                            <li>We’ve caused a shift in the realms of social commerce, get on board the Tagzie cart with 100’s of other brands & businesses.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="posyon_icon">
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <hr>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <hr>
                </div>
                <img class="img-responsive" src="<?= $app['base_assets_url']; ?>images/grey_iconlogo.jpg" alt="">
            </div>



            <div class="col-md-4 col-sm-12 col-xs-12 information_detail">
                <img class="img-responsive bysell flot_seuty" src="<?= $app['base_assets_url']; ?>images/selling_power_mobile.png" alt="">
            </div>
            <div class="col-md-8 col-sm-12 col-xs-12">
                <div class="chack_demain edit_ordr">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="bread_main_pedding ">
                                <h1>Putting the power in your hands</h1>
                                <p>We’ve developed our Product Management & Inventory systems together with local businesses, catering for a broad range of business requirements and usage patterns.</p>
                                <p>- Dynamic inventory counts</p>
                                <p>- Stock level buffers to alert you to items with low stock</p>
                                <p>- Post-publishing features; edit any aspect of your products including: description and shipping options</p>
                                <p>- Enable flash sales at the press of a button</p>
                                <b>Many more features are soon to come, all at extremely competitive rates.</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="posyon_icon">
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <hr>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <hr>
                </div>
                <img class="img-responsive" src="<?= $app['base_assets_url']; ?>images/grey_iconlogo.jpg" alt="">
            </div>

        </div>

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <div class="power_socil">
                        <h1>The<br>
                            Power of<br>
                            Social Media</h1>
                        <h4>Turn your follows into customers</h4>
                        <h6>250k followers<br>
                            =<br>
                            250k POTENTIAL customers</h6>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12 col-xs-12">
                    <div class="power_socil">
                        <h1>Reach<br>
                            of<br>
                            Engagement</h1>

                        <ul>
                            <li><img class="img-responsive socil_icon" src="<?= $app['base_assets_url']; ?>images/instagrim.png" alt=""><span>Instagram</span><div class="borer_rage"><div class="back_range"></div></div> </li>    
                            <li><img class="img-responsive socil_icon" src="<?= $app['base_assets_url']; ?>images/facebook_icon.png" alt=""><span>Facebook</span><div class="borer_rage"><div class="back_range1"></div></div></li>    
                            <li><img class="img-responsive socil_icon" src="<?= $app['base_assets_url']; ?>images/twitter_icon.png" alt=""><span>twitter</span><div class="borer_rage"><div class="back_range2"></div></div></li>    
                        </ul>    

                    </div>
                </div>

                <div class="col-md-4 col-sm-12 col-xs-12">
                    <div class="power_socil retain">
                        <h1>Retain<br>
                            Your<br>
                            Rights</h1>
                        <h4>You keep the rights of your intellectual property you publish through tagzie onto Instagram</h4>
                        <span class="glyphicon glyphicon-ok-sign"></span>
                    </div>
                </div>

            </div>
        </div>
        <div class="posyon_icon">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <hr>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                <hr>
            </div>
            <img class="img-responsive" src="<?= $app['base_assets_url']; ?>images/grey_iconlogo.jpg" alt="">
        </div>

        <div class="col-md-4 col-md-push-8 col-sm-12 col-xs-12 information_detail">
            <img alt="" src="<?= $app['base_assets_url']; ?>images/selling_performance.png" class="img-responsive bysell flot_seuty">
        </div>

        <div class="col-md-8 col-md-pull-4 col-sm-12 col-xs-12">
            <div class="chack_demain edit_ordr">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="bread_main_pedding">
                            <h1>Keep track of your sales performance</h1>
                            <p>We’ve built the tools to help you analyse and keep on top of your sales performance :</p>
                            <p>- Monthly sales chart</p>
                            <p>- Conversion rates</p>
                            <p>- Per-product performance matrix</p>
                            <p>- More reporting functions coming very soon!</p>
                            <div class="my_order_main selling_performnc_main">
                                <img alt="" src="<?= $app['base_assets_url']; ?>images/performace_range_icon.png" class="img-responsive">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="posyon_icon">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <hr>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                <hr>
            </div>
            <img class="img-responsive" src="<?= $app['base_assets_url']; ?>images/grey_iconlogo.jpg" alt="">
        </div>



        <div class="col-md-4 col-sm-12 col-xs-12 information_detail">
            <img class="img-responsive bysell flot_seuty" src="<?= $app['base_assets_url']; ?>images/effecient_order_mobile.png" alt="">
        </div>
        <div class="col-md-8 col-sm-12 col-xs-12">
            <div class="chack_demain edit_ordr">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="bread_main_pedding respn_ef">
                            <h1>Efficient order handling</h1>
                            <p>We’ve simplified the order handling process to make your life easier, we’ve also integrated key features to help build the communication bridge between yourself and your customers.</p>
                            <p>- Advanced dispatch process</p>
                            <p>- Automatically generated Packaging Documents</p>
                            <p>- Integrated messaging functionality to customers</p>
                            <p>- Refund system</p>
                            <p>- Dispute resolution centre</p>
                            <div class="col-md-12 text-center shopping_out_boundaries">
                                <img alt="" src="<?= $app['base_assets_url']; ?>images/effecient_hes_icon.png" class="img-responsive">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="posyon_icon">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <hr>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                <hr>
            </div>
            <img class="img-responsive" src="<?= $app['base_assets_url']; ?>images/grey_iconlogo.jpg" alt="">
        </div>



        <div class="col-md-4 col-md-push-8 col-sm-12 col-xs-12 information_detail">
            <img alt="" src="<?= $app['base_assets_url']; ?>images/disposil_mobile.png" class="img-responsive bysell flot_seuty">
        </div>

        <div class="col-md-8 col-md-pull-4 col-sm-12 col-xs-12">
            <div class="chack_demain edit_ordr">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="bread_main_pedding table_li_row">
                            <h1>Sophisticated tools at your disposal</h1>

                            <p style="margin-bottom: 22px; margin-top: 12px;">Working together with leading brands to develop and provide you with powerful tools you need to strive & succeed:</p>
                            <p><img class="img-responsive postage_icon" src="<?= $app['base_assets_url']; ?>images/postage_icon.png" alt=""> <span>- Postage templates to help manage your shipping options</span></p>
                            <p><img class="img-responsive postage_icon" src="<?= $app['base_assets_url']; ?>images/tax_icon.png" alt=""><span>- Tax Compliance with your local governments regulations</span></p>
                            <p><img class="img-responsive postage_icon" src="<?= $app['base_assets_url']; ?>images/invoice_icon.png" alt=""><span>- Automated invoicing, displaying your key company information</span></p>
                            <p><img class="img-responsive postage_icon" src="<?= $app['base_assets_url']; ?>images/auto_catigaration_icon.png" alt=""><span>- Auto-categorisation on to the Tagzie Marketplace website, erradicating the stress & costs of maintaining your own online shop.</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="posyon_icon">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <hr>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                <hr>
            </div>
            <img class="img-responsive" src="<?= $app['base_assets_url']; ?>images/grey_iconlogo.jpg" alt="">
        </div>



        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-md-4 col-sm-12 col-xs-12"><img class="img-responsive gobel_word" src="<?= $app['base_assets_url']; ?>images/google_word.png" alt=""></div>
                <div class="col-md-8 col-sm-12 col-xs-12 main_suport_contry">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h1>Supported Countries</h1>
                        </div>
                        <div class="col-md-8 support_country col-sm-12 col-xs-12">
                            <ul>
                                <li><span><img class="" src="<?= $app['base_assets_url']; ?>images/australia.png" alt=""></span>Australia</li>
                                <li><span><img class="" src="<?= $app['base_assets_url']; ?>images/austria.png" alt=""></span>Austria</li>
                                <li><span><img class="" src="<?= $app['base_assets_url']; ?>images/belgium.png" alt=""></span>Belgium</li>
                                <li><span><img class="" src="<?= $app['base_assets_url']; ?>images/canada.png" alt=""></span>Canada</li>
                                <li><span><img class="" src="<?= $app['base_assets_url']; ?>images/denmark.png" alt=""></span>Denmark</li>
                                <li><span><img class="" src="<?= $app['base_assets_url']; ?>images/finland.png" alt=""></span>Finland</li>
                                <li><span><img class="" src="<?= $app['base_assets_url']; ?>images/france.png" alt=""></span>France</li>
                                <li><span><img class="" src="<?= $app['base_assets_url']; ?>images/germany.png" alt=""></span>Germany</li>
                            </ul>
                            <ul>
                                <li><span><img class="" src="<?= $app['base_assets_url']; ?>images/hong-kong.png" alt=""></span>Hong Kong</li>
                                <li><span><img class="" src="<?= $app['base_assets_url']; ?>images/ireland.png" alt=""></span>Ireland</li>
                                <li><span><img class="" src="<?= $app['base_assets_url']; ?>images/italy.png" alt=""></span>Italy</li>
                                <li><span><img class="" src="<?= $app['base_assets_url']; ?>images/luxembourg.png" alt=""></span>Luxembourg</li>
                                <li><span><img class="" src="<?= $app['base_assets_url']; ?>images/netherlands.png" alt=""></span>Netherlands</li>
                                <li><span><img class="" src="<?= $app['base_assets_url']; ?>images/norway.png" alt=""></span>Norway</li>
                                <li><span><img class="" src="<?= $app['base_assets_url']; ?>images/portugal.png" alt=""></span>Portugal</li>
                                <li><span><img class="" src="<?= $app['base_assets_url']; ?>images/singapore.png" alt=""></span>Singapore</li>    
                            </ul>
                            <ul>
                                <li><span><img class="" src="<?= $app['base_assets_url']; ?>images/spain.png" alt=""></span>Spain</li>
                                <li><span><img class="" src="<?= $app['base_assets_url']; ?>images/sweden.png" alt=""></span>Sweden</li>
                                <li><span><img class="" src="<?= $app['base_assets_url']; ?>images/switzerland.png" alt=""></span>Switzerland</li>
                                <li><span><img class="" src="<?= $app['base_assets_url']; ?>images/united-kingdom.png" alt=""></span>United Kingdom</li>
                                <li><span><img class="" src="<?= $app['base_assets_url']; ?>images/united-states.png" alt=""></span>United States</li>
                                <li style="display:none"><span><img class="" src="<?= $app['base_assets_url']; ?>images/japan.png" alt=""></span>Japan</li>    
                            </ul>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <p>We currently support these 21 countries for merchant enrollment, with more being supported very soon.</p>
                            <p>As a merchant, you can accept customers located from any country!</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div class="posyon_icon">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <hr>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                <hr>
            </div>
            <img class="img-responsive" src="<?= $app['base_assets_url']; ?>images/grey_iconlogo.jpg" alt="">
        </div>


        <div class="col-md-12 col-sm-12 col-xs-12 table_design"> 

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h1 class="text-center">
                            Pricing
                        </h1>

                    </div>
                    <div id="no-more-tables">
                        <img data-toggle="tooltip" data-placement="left" title="50% discount on uninterrupted Platinum subscription renewals, valid for 1 year" class="img-responsive discount_img" src="<?= $app['base_assets_url']; ?>images/discount.png" alt="">
                        <table class="col-md-12 col-sm-12 col-xs-12 table-bordered table-striped table-condensed cf">
                            <thead class="cf">

                                <tr>
                                    <th></th>
                                    <?php foreach ($subscriptionPackages as $package): ?>
                                        <th <?= ($package['rate'] == 0) ? '' : 'class="numeric"'; ?>><?= $package['name']; ?> <span><?php $price = ($package['discount_enabled']) ? '<del>£' . number_format($package['rate']) . '</del> £' . number_format($package['discounted_rate']) : '£' . number_format($package['rate']); ?> <?= ($package['rate'] == 0) ? 'FREE' : $price . '/month'; ?></span></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="mobile_price_tr">
                                    <td></td>
                                    <?php foreach ($subscriptionPackages as $package): ?>
                                        <td data-title=""><?php $price = ($package['discount_enabled']) ? '<del>£' . number_format($package['rate']) . '</del> £' . number_format($package['discounted_rate']) : '£' . number_format($package['rate']); ?> <?= ($package['rate'] == 0) ? 'FREE' : $price . '/month'; ?></td>
                                    <?php endforeach; ?>
                                </tr>
                                <tr>
                                    <td >UK/EU Transaction Fee</td>
                                    <?php foreach ($subscriptionPackages as $package): ?>
                                        <td data-title="UK/EU Transaction Fee"><?= $package['eu_transaction_fee']; ?>%</td>
                                    <?php endforeach; ?>
                                </tr>
                                <tr>
                                    <td >Non-UK/EU Transaction Fee</td>
                                    <?php foreach ($subscriptionPackages as $package): ?>
                                        <td data-title="Non-UK/EU Transaction Fee"><?= $package['transaction_fee']; ?>%</td>
                                    <?php endforeach; ?>
                                </tr>
                                <tr>
                                    <td >Fund Settlements Time</td>
                                    <?php foreach ($subscriptionPackages as $package): ?>
                                        <td data-title="Fund Settlements Time"><?= $package['payment_threshold']; ?> days</td>
                                    <?php endforeach; ?>
                                </tr>
                                <tr>
                                    <td>Verified Badges</td>
                                    <?php foreach ($subscriptionPackages as $package): ?>
                                        <td data-title="Verified Badges"><?= ($package['verified_badge']) ? 'Yes' : 'No'; ?></td>
                                    <?php endforeach; ?>

                                </tr>
                                <tr>
                                    <td>Top Seller Badge</td>
                                    <?php foreach ($subscriptionPackages as $package): ?>
                                        <td data-title="Top Seller Badge"><?= ($package['top_seller_badge']) ? 'Yes' : 'No'; ?></td>
                                    <?php endforeach; ?>      			
                                </tr>
                                <tr>
                                    <td>Support Level</td>
                                    <?php
                                    $support_levels = array(
                                        '2' => 'Basic',
                                        '3' => '48 Hour',
                                        '4' => '24 Hour',
                                        '5' => '12 Hour'
                                    );
                                    foreach ($subscriptionPackages as $package):
                                        ?>
                                        <td data-title="Support Level"><?= $support_levels[$package['support_level_id']]; ?></td>
                                    <?php endforeach; ?>       				
                                </tr>
                                <tr>
                                    <td>Dedicated Support Manager</td>
                                    <?php foreach ($subscriptionPackages as $package): ?>
                                        <td data-title="Dedicated Support Manager"><?= ($package['dedicated_support_manager']) ? 'Yes' : 'No'; ?></td>
                                    <?php endforeach; ?>


                                </tr>
                                <tr>
                                    <td class="td_style_main">
                                        * All fee’s are subject to a fixed charge of 20p on each transaction
                                    </td>
                                    <td colspan="4" class="back_lat">For more information contact us on <?= $app['sales_email']; ?> or <?= $app['support_phone_uk']; ?> (UK Freephone) / <?= $app['support_phone_int']; ?> (Intl)</td>
                                    <td class="for_respnsv_tg"><p>* All fee’s are subject to a fixed charge of 20p on each transaction</p></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="posyon_icon">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <hr>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                <hr>
            </div>
            <img class="img-responsive" src="<?= $app['base_assets_url']; ?>images/grey_iconlogo.jpg" alt="">
        </div>


    </div>
</div>
</div>

<?php include_once $this->getPart('/web/general/common/footer.php'); ?>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>