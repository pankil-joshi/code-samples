<html lang="en">
    <head>
        <title>Invoice</title>   

        <style>
            /* http://meyerweb.com/eric/tools/css/reset/ 
   v2.0 | 20110126
   License: none (public domain)
            */

            html, body, div, span, applet, object, iframe,
            h1, h2, h3, h4, h5, h6, p, blockquote, pre,
            a, abbr, acronym, address, big, cite, code,
            del, dfn, em, img, ins, kbd, q, s, samp,
            small, strike, strong, sub, sup, tt, var,
            b, u, i, center,
            dl, dt, dd, ol, ul, li,
            fieldset, form, label, legend,
            table, caption, tbody, tfoot, thead, tr, th, td,
            article, aside, canvas, details, embed, 
            figure, figcaption, footer, header, hgroup, 
            menu, nav, output, ruby, section, summary,
            time, mark, audio, video {
                margin: 0;
                padding: 0;
                border: 0;
            }
            /* HTML5 display-role reset for older browsers */
            article, aside, details, figcaption, figure, 
            footer, header, hgroup, menu, nav, section {
                display: block;
            }
            body {
                line-height: 1;
            }
            ol, ul {
                list-style: none;
            }
            blockquote, q {
                quotes: none;
            }
            blockquote:before, blockquote:after,
            q:before, q:after {
                content: '';
                content: none;
            }
            table {
                border-collapse: collapse;
                border-spacing: 0;
            }
            @font-face {
                font-family: 'Ubuntu';
                font-style: normal;
                font-weight: normal;
                src: url(<?= $app['base_assets_url']; ?>/fonts/Ubuntu-R.ttf) format('truetype');
            }
            @font-face {
                font-family: "Ubuntu";
                src: url(<?= $app['base_assets_url']; ?>/fonts/Ubuntu-B.ttf) format('truetype');
                font-weight: bold;
            }
            @font-face {
                font-family: "Ubuntu";
                src: url(<?= $app['base_assets_url']; ?>/fonts/Ubuntu-B.ttf) format('truetype');
                font-style: italic;
            }
        </style>
    </head>
    <body>
        <div style="padding: 50px">
            <div>
                <div style="display: inline-block; width: 50%;vertical-align: top;">
                    <span style="display: inline-block; color: rgb(0, 0, 0);font-size: 40px;font-weight: bold;"><?= $invoice['merchant']['legal_entity_business_name'];?></span>
                    <p style="color: rgb(64, 64, 64);
                       font-size: 12px;line-height: 16px;"><?php if(!empty($invoice['merchant']['business_registration_number'])): ?>Company number <?= $invoice['merchant']['business_registration_number'];?>. Registered in <?= $countries[$invoice['merchant']['business_country']]['name']; ?>. <br><?php endif; ?><?php if(!empty($invoice['merchant']['legal_entity_business_tax_id'])):?>TAX ID <?= $invoice['merchant']['legal_entity_business_tax_id']; ?>.<?php endif; ?></p></div><div style="display: inline-block;width: 50%;background-color:#FF6A00;"><div style="padding: 15px 0;"><ul style="list-style: outside none none;margin-left: 15px;">
                            <li style=" align-items: center;
                                color: rgb(255, 255, 255);
                                font-weight: bold;
                                width: 100%;
                                margin-bottom: 10px;
                                "><img style="display: inline-block;vertical-align: middle;
                                   width: 35px;" src="<?= $app['base_assets_url']; ?>images/seeller-ista.jpg" class="img-responsive" alt=""><span style="margin-left: 10px;">@<?= $invoice['merchant']['user_instagram_username']; ?></span></li>
                            <li style=" align-items: center;
                                color: rgb(255, 255, 255);
                                font-weight: bold;
                                width: 100%;
                                margin-bottom: 10px;"><img style="display: inline-block;vertical-align: middle;
                                   width: 35px;" src="<?= $app['base_assets_url']; ?>images/mobile.jpg" class="img-responsive" alt=""><span style="margin-left: 10px;"><?= $invoice['merchant']['business_telephone_prefix']; ?> <?= $invoice['merchant']['legal_entity_phone_number']; ?></span></li>
                            <li style=" align-items: center;
                                color: rgb(255, 255, 255);
                                font-weight: bold;
                                width: 100%;
                                margin-bottom: 10px;"><img style="display: inline-block;vertical-align: middle;
                                   width: 35px;" src="<?= $app['base_assets_url']; ?>images/wit-message.jpg" class="img-responsive" alt=""><span style="margin-left: 10px;"><?= $invoice['merchant']['business_email']; ?></span></li>
                            <li style=" align-items: center;
                                color: rgb(255, 255, 255);
                                font-weight: bold;
                                width: 100%;
                                margin-bottom: 10px;"><img style="display: inline-block;vertical-align: middle;
                                   width: 35px;" src="<?= $app['base_assets_url']; ?>images/home.png" class="img-responsive" alt=""><span style="display: inline-block;margin-left: 10px;"><?= (!empty($invoice['merchant']['legal_entity_address_line1']))? $invoice['merchant']['legal_entity_address_line1'] . ',<br />' : ''  ?>
                                    <?= (!empty($invoice['merchant']['legal_entity_address_city']))? $invoice['merchant']['legal_entity_address_city'] . ',<br />' : ''  ?> <?= (!empty($invoice['merchant']['legal_entity_address_postal_code']))? $invoice['merchant']['legal_entity_address_postal_code'] . ',<br />' : ''  ?> <?= (!empty($invoice['merchant']['business_country']))? $countries[$invoice['merchant']['business_country']]['name'] : ''  ?></span></li>
                        </ul></div>
                </div>
            </div>

            <div style="margin: 40px 0;">
                <div style="width:50%;display: inline-block;vertical-align: top;">
                    <div style="padding-right:10px">
                        <div style="margin:0; font-weight: bold;font-size:14px;background-color:#FF6A00;padding: 13px 14px;color: rgb(255, 255, 255);">Order Reference: #TZ<?= $invoice['suborder']['id']; ?></div>
                        <div style="width:100%;margin:40px 0 0 0">
                            <h2 style="width:100%;font-weight:bold;font-size:14px;color:#000">Billing Address</h2>
                            <ul style="list-style: outside none none;margin:20px 0 0 20px;line-height: 16px;">
                                <?php $deliveryAddress = unserialize($invoice['suborder']['delivery_address']); ?>
                                <li style="width:100%;font-size:12px;"><?= $deliveryAddress['first_name']; ?>  <?= $deliveryAddress['last_name']; ?></li>
                                <li style="width:100%;font-size:12px;"><?= $deliveryAddress['line_1']; ?></li>
                                <li style="width:100%;font-size:12px;"><?= $deliveryAddress['city']; ?>, <?= $deliveryAddress['zip_code']; ?></li>
                                <li style="width:100%;font-size:12px;"><?= $countries[$deliveryAddress['country']]['name']; ?></li>
                            </ul>
                        </div> 
                    </div>
                </div><div style="width:50%;display: inline-block;">
                    <div>
                        <div style="background-color:#7E7E7E;display: inline-block;margin: 0;width:50%;color:#fff;padding: 13px 0px;text-align: center;font-weight:bold;font-size:14px;">Order Date: <?= convert_datetime($invoice['suborder']['created_at'], $user['timezone'], 'd/m/Y') ?></div><div style="background-color:#7E7E7E;margin:0;display: inline-block;width:50%;color:#fff; padding: 13px 0px;text-align: center;font-weight:bold;font-size:14px;">Packing Date: <?= convert_datetime(gmdate('Y-m-d H:i:s'), $user['timezone'], 'd/m/Y')  ?></div>

                    </div>
                    <div  style="margin:40px 0 0 0">
                        <h2 style="width:100%;font-weight:bold;font-size:14px;color:#000">Delivery Address</h2>
                        <ul style="list-style: outside none none;margin:20px 0 0 20px;line-height: 16px;">
                                <?php $deliveryAddress = unserialize($invoice['suborder']['delivery_address']); ?>
                                <li style="width:100%;font-size:12px;"><?= $deliveryAddress['first_name']; ?>  <?= $deliveryAddress['last_name']; ?></li>
                                <li style="width:100%;font-size:12px;"><?= $deliveryAddress['line_1']; ?></li>
                                <li style="width:100%;font-size:12px;"><?= $deliveryAddress['city']; ?>, <?= $deliveryAddress['zip_code']; ?></li>
                                <li style="width:100%;font-size:12px;"><?= $countries[$deliveryAddress['country']]['name']; ?></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div style="font-weight: bold;font-size:14px;color: rgb(255, 255, 255);">
                <div style="width:80%;display: inline-block;">
                    <div style="padding-right: 10px">
                        <div style="background-color:#7E7E7E;">
                            <div style="margin:0 0% 0 0;padding: 13px 14px;">Item Description</div>
                        </div>
                    </div>
                </div><div style="width:20%;display: inline-block;text-align:center;">
                    <div style="background-color:#7E7E7E;">
                        <div style="display: inline-block;margin: 0; width:100%;padding: 13px 0px;">Quantity</div>
                    </div>
                </div>      
            </div>
            <?php $counter = 1; $total_items = 0; foreach($invoice['suborder']['items'] as $item): ?>
            <div style="font-size:14px;color:#000">
                <div style="text-align:center;">
                    <div style="background-color:<?php if($counter%2==0): ?> #fff<?php else: ?>#f1f1f1<?php endif; ?>;">
                        <div style="display: inline-block;margin:0 0% 0 0;padding: 13px 0px;width:80%;vertical-align: middle;"><div style="text-align: left;padding: 0 14px"><span style="font-weight:bold;"><?= $item['media_title']; ?></span><br><span style="font-weight:normal"><?= unserialize($item['options'])['label']; ?></span></div></div><div style="display: inline-block;margin: 0; width:20%;padding: 13px 0px;"><?= (int)$item['billed_quantity']; ?> <?php $total_items += $item['billed_quantity']; ?></div>
                    </div>
                </div>      
            </div>
            <?php $counter++; endforeach;?>
            <div style="font-size:14px;color: #fff;font-weight:bold;">
                <div style="text-align:center;">
                    <div style="background-color:#fff;">
                        <div style="display: inline-block;margin:0 0% 0 0;padding: 13px 0px;width:50%;vertical-align: middle;"></div><div style="display: inline-block;margin: 0; width:30%;padding: 13px 0px;text-align: right;background-color:#FF6A00">Total Quantity:</div><div style="display: inline-block;margin: 0; width:20%;padding: 13px 0px;background-color:#FF6A00"><?= $total_items; ?></div>
                    </div>
                </div>      
            </div>
            <hr>
            <div style="padding:0 14px">
                <div style="font-size:20px;margin:10px 0 20px 0;font-weight:bold"> Terms & Conditions</div>
                <p style="margin-bottom:20px">Payments are accepted by Tagzie Limited. Company number 09956906. Registered in England and Wales. Registered address: 32 Thistlebank, East Leake, Loughborough, LE12 6RS, UK.</p> 
                <p>Orders are protected by the Tagzie Effortless Refund Policy. Your customer has 7 days from the day of receiving this order to instigate a refund with you and Tagzie will intervene to expedite the process for both parties.</p>

            </div>
            <hr>
            <div style="padding:0 14px"><div style="display:inline-block;width:50%;text-align: left"><span>Printed at <?= convert_datetime(gmdate('Y-m-d H:i:s'), $user['timezone'], 'H:i'); ?> on <?= convert_datetime(gmdate('Y-m-d H:i:s'), $user['timezone'], 'd/m/Y'); ?> via Tagzie Packaging Slip</span></div><div style="text-align: right;display:inline-block;width:50%;"><span>TGZ<?= $invoice['suborder']['id']; ?></span></div></div>
        </div>
    </body>
</html>
