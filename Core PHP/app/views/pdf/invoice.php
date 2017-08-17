<?php $paymentStatus = array('paid' => 'Paid', 'unpaid' => 'Unpaid');
?>
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
                    <span style="display: inline-block; color: rgb(0, 0, 0);font-size: 40px;font-weight: bold;"><?= $invoice['merchant']['legal_entity_business_name']; ?></span>
                    <p style="color: rgb(64, 64, 64);
                       font-size: 12px;line-height: 16px;"><?php if (!empty($invoice['merchant']['business_registration_number'])): ?>Company number <?= $invoice['merchant']['business_registration_number']; ?>. Registered in <?= $countries[$invoice['merchant']['business_country']]['name']; ?>. <br><?php endif; ?><?php if (!empty($invoice['merchant']['tax_enabled'])): ?>TAX ID <?= $invoice['merchant']['legal_entity_business_tax_id']; ?>.<?php endif; ?></p></div><div style="display: inline-block;width: 50%;background-color:#FF6A00;"><div style="padding: 15px 0;"><ul style="list-style: outside none none;margin-left: 15px;">
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
                                   width: 35px;" src="<?= $app['base_assets_url']; ?>images/home.png" class="img-responsive" alt=""><span style="display: inline-block;margin-left: 10px;"><?= (!empty($invoice['merchant']['legal_entity_address_line1'])) ? $invoice['merchant']['legal_entity_address_line1'] . ',<br />' : '' ?>
                                    <?= (!empty($invoice['merchant']['legal_entity_address_city'])) ? $invoice['merchant']['legal_entity_address_city'] . ',<br />' : '' ?> <?= (!empty($invoice['merchant']['legal_entity_address_postal_code'])) ? $invoice['merchant']['legal_entity_address_postal_code'] . ',<br />' : '' ?> <?= (!empty($invoice['merchant']['business_country'])) ? $countries[$invoice['merchant']['business_country']]['name'] : '' ?></span></li>
                        </ul></div>
                </div>
            </div>

            <div style="margin: 40px 0;">
                <div style="width:50%;display: inline-block;vertical-align: top;">
                    <div style="padding-right:10px">
                        <div style="margin:0; font-weight: bold;font-size:14px;background-color:#FF6A00;padding: 13px 14px;color: rgb(255, 255, 255);">Order Reference: #TGZ<?= $invoice['suborder']['id']; ?></div>
                        <div style="width:100%;margin:20px 0 0 0">
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
                        <div style="background-color:#7E7E7E;display: inline-block;margin: 0;width:50%;color:#fff;padding: 13px 0px;text-align: center;font-weight:bold;font-size:14px;">Date: <?= convert_datetime($invoice['suborder']['created_at'], $user['timezone'], 'd/m/Y'); ?></div><div style="background-color:#7E7E7E;margin:0;display: inline-block;width:50%;color:#fff; padding: 13px 0px;text-align: center;font-weight:bold;font-size:14px;">Status: <?= ($invoice['suborder']['status'] != 'cancelled' && $invoice['suborder']['status'] != 'refunded' && $invoice['suborder']['status'] != 'returned') ? $paymentStatus[$invoice['suborder']['payment_status']] : ucfirst($invoice['suborder']['status']) ?></div>

                    </div>
                    <div  style="margin:20px 0 0 0">
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
                <div style="width:50%;display: inline-block;">
                    <div style="padding-right: 10px">
                        <div style="background-color:#7E7E7E;">
                            <div style="margin:0 0% 0 0;padding: 13px 14px;">Item Description<br /><span style="color:#7E7E7E;">(Tax)</span></div>
                        </div>
                    </div>
                </div><div style="width:50%;display: inline-block;text-align:center;">
                    <div style="background-color:#7E7E7E;">
                        <div style="display: inline-block;margin: 0; width:25%;padding: 13px 0px;">Quantity</div><div style="display: inline-block;margin: 0;width:25%;padding: 13px 0px;"><?php if (!empty($invoice['merchant']['tax_enabled'])): ?>Tax %<?php endif; ?></div><div style="display: inline-block;margin: 0;width:25%;padding: 13px 0px;">Unit Price</div><div style="display: inline-block;margin: 0; width:25%;padding: 13px 0px;">Total <br /><?php if (empty($invoice['merchant']['tax_enabled'])): ?><span style="color:#7E7E7E;"><?php endif; ?>(excl. Tax)<?php if (empty($invoice['merchant']['tax_enabled'])): ?></span><?php endif; ?></div>
                    </div>
                </div>      
            </div>
            <?php
            $counter = 1;
            foreach ($invoice['suborder']['items'] as $item):
                ?>
                <div style="font-size:14px;color:#000">
                    <div style="text-align:center;">
                        <div style="background-color:<?php if ($counter % 2 == 0): ?> #fff<?php else: ?>#f1f1f1<?php endif; ?>;">
                            <div style="display: inline-block;margin:0 0% 0 0;padding: 13px 0px;width:50%;vertical-align: middle;"><div style="text-align: left;padding: 0 14px"><span style="font-weight:bold;"><?= $item['media_title']; ?></span><br><span style="font-weight:normal"><?= unserialize($item['options'])['label']; ?></span></div></div><div style="display: inline-block;margin: 0; width:12.5%;padding: 13px 0px;"><?= (int) $item['billed_quantity']; ?></div><div style="display: inline-block;margin: 0;width:12.5%;padding: 13px 0px;"><?php if (!empty($invoice['merchant']['tax_enabled'])): ?><?= ($item['row_tax_rate'] * 100); ?> %<?php endif; ?></div><div style="display: inline-block;margin: 0;width:12.5%;padding: 13px 0px;"><?= $currencies[$invoice['suborder']['base_currency_code']] . $item['price']; ?></div><div style="display: inline-block;margin: 0; width:12.5%;padding: 13px 0px;"><?= $currencies[$invoice['suborder']['base_currency_code']]; ?> <?= $item['row_basetotal']; ?></div>
                        </div>
                    </div>      
                </div>
                <?php
                $counter++;
            endforeach;
            ?>   
            <div style="font-size:14px;color: #000;font-weight:bold;">
                <div style="text-align:center;">
                    <div style="background-color:#fff;">
                        <div style="display: inline-block;margin:0 0% 0 0;padding: 13px 0px;width:50%;vertical-align: middle;"></div><div style="display: inline-block;margin: 0; width:37.5%;padding: 13px 0px;text-align: right;background-color:#fff4ec">Subtotal:</div><div style="display: inline-block;margin: 0; width:12.5%;padding: 13px 0px;background-color:#fff4ec"><?= $currencies[$invoice['suborder']['base_currency_code']]; ?> <?= $invoice['suborder']['subtotal']; ?></div>
                    </div>
                </div>      
            </div> 
            <?php if (!empty($invoice['merchant']['tax_enabled'])): ?>
                <div style="font-size:14px;color: #000;font-weight:bold;">
                    <div style="text-align:center;">
                        <div style="background-color:#fff;">
                            <div style="display: inline-block;margin:0 0% 0 0;padding: 13px 0px;width:50%;vertical-align: middle;"></div><div style="display: inline-block;margin: 0; width:37.5%;padding: 13px 0px;text-align: right;background-color:#fff4ec">Tax:</div><div style="display: inline-block;margin: 0; width:12.5%;padding: 13px 0px;background-color:#fff4ec"><?= $currencies[$invoice['suborder']['base_currency_code']] . $invoice['suborder']['tax']; ?></div>
                        </div>
                    </div>      
                </div>
            <?php endif; ?>
            <div style="font-size:14px;color: #000;font-weight:bold;">
                <div style="text-align:center;">
                    <div style="background-color:#fff;">
                        <div style="display: inline-block;margin:0 0% 0 0;padding: 13px 0px;width:50%;vertical-align: middle;"></div><div style="display: inline-block;margin: 0; width:37.5%;padding: 13px 0px;text-align: right;background-color:#fff4ec">Standard Delivery:</div><div style="display: inline-block;margin: 0; width:12.5%;padding: 13px 0px;background-color:#fff4ec"><?= $currencies[$invoice['suborder']['base_currency_code']] . $invoice['suborder']['postage']; ?></div>
                    </div>
                </div>      
            </div>            
            <div style="font-size:14px;color: #fff;font-weight:bold;">
                <div style="text-align:center;">
                    <div style="background-color:#fff;">
                        <div style="display: inline-block;margin:0 0% 0 0;padding: 13px 0px;width:50%;vertical-align: middle;"></div><div style="display: inline-block;margin: 0; width:37.5%;padding: 13px 0px;text-align: right;background-color:#FF6A00">Order Total:</div><div style="display: inline-block;margin: 0; width:12.5%;padding: 13px 0px;background-color:#FF6A00"><?= $currencies[$invoice['suborder']['base_currency_code']] . $invoice['suborder']['total']; ?> </div>
                    </div>
                </div>      
            </div>
            <?php if (!empty(localCurrencyAmount($invoice['suborder']['merchant_currency_conversion_factor'], $invoice['suborder']['customer_currency_conversion_factor'], $invoice['suborder']['total'])) && !empty($user['customer_currency_conversion_factor'])): ?>
                <div style="font-size:14px;color: #fff;font-weight:bold;">
                    <div style="text-align:center;">
                        <div style="background-color:#fff;">
                            <div style="display: inline-block;margin:0 0% 0 0;padding: 13px 0px;width:50%;vertical-align: middle;"></div><div style="display: inline-block;margin: 0; width:37.5%;padding: 13px 0px;text-align: right;background-color:#FF6A00">Order Total in <?= $invoice['suborder']['customer_currency_code']; ?>:</div><div style="display: inline-block;margin: 0; width:12.5%;padding: 12.5px 0px;background-color:#FF6A00">(<?= $currencies[$invoice['suborder']['customer_currency_code']]; ?><?= localCurrencyAmount($invoice['suborder']['merchant_currency_conversion_factor'], $invoice['suborder']['customer_currency_conversion_factor'], $invoice['suborder']['total']); ?>)<sup>*</sup></div>
                        </div>
                    </div>      
                </div>
                <p style="font-size:14px;color:#000;font-weight: normal"><sup>*</sup>Approximate value</p>
            <?php endif; ?>
            <hr>
            <div style="padding:0 14px">
                <div style="font-size:20px;margin:10px 0 20px 0;font-weight:bold"> Terms & Conditions</div>
                <p style="margin-bottom:20px">Payments are accepted by Tagzie Limited. Company number 09956906. Registered in England and Wales. Registered address: 32 Thistlebank, East Leake, Loughborough, LE12 6RS, UK.</p> 
                <p>Orders are protected by the Tagzie Effortless Refund Policy. You have <?= $app['order_hold_period']; ?> days from the day your order has been delivered to instigate a refund with the merchant and Tagzie will intervene to expedite the process. You may be entitled to a refund beyond this cooling off period. All orders must be returned in the original packaging in an unused condition to be eligible for a refund.</p>

            </div>
            <hr>
            <div style="padding:0 14px"><div style="display:inline-block;width:50%;text-align: left"><span>Ordered at <?= convert_datetime($invoice['suborder']['created_at'], $user['timezone'], 'H:i') ?> on <?= convert_datetime($invoice['suborder']['created_at'], $user['timezone'], 'd/m/Y') ?> via Tagzie Invoice</span></div><div style="text-align: right;display:inline-block;width:50%;"><span>TGZ<?= $invoice['suborder']['id']; ?></span></div></div>
        </div>
    </body>
</html>
