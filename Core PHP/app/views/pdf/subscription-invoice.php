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
                    <span style="display: inline-block; color: rgb(0, 0, 0);font-size: 40px;font-weight: bold;">Tagzie Limited</span>
                    <p style="color: rgb(64, 64, 64);
                       font-size: 12px;line-height: 16px;">Company number 09956906. <br>VAT registration number is <?= $app['vat_number']; ?>. <br>Registered in England and
                        Wales. Registered office: 32 Thistlebank, East Leake,
                        Loughborough, LE12 6RS, United Kingdom
                    </p></div><div style="display: inline-block;width: 50%;background-color:#FF6A00;"><div style="padding: 15px 0;"><ul style="list-style: outside none none;margin-left: 15px;">
                            <li style=" align-items: center;
                                color: rgb(255, 255, 255);
                                font-weight: bold;
                                width: 100%;
                                margin-bottom: 10px;"><img style="display: inline-block;vertical-align: middle;
                                   width: 35px;" src="<?= $app['base_assets_url']; ?>images/mobile.jpg" class="img-responsive" alt=""><span style="margin-left: 10px;">+44 115 718 0117</span></li>
                            <li style=" align-items: center;
                                color: rgb(255, 255, 255);
                                font-weight: bold;
                                width: 100%;
                                margin-bottom: 10px;"><img style="display: inline-block;vertical-align: middle;
                                   width: 35px;" src="<?= $app['base_assets_url']; ?>images/wit-message.jpg" class="img-responsive" alt=""><span style="margin-left: 10px;">support@tagzie.com</span></li>
                        </ul></div>
                </div>
            </div>
            <div>
                <div style="width: 100%;">
                    Subscription Invoice / <?= convert_datetime($invoice['created_at'], $merchant['timezone'], 'dS F Y'); ?>
                </div>
            </div>
            <div style="margin: 40px 0;">
                <div style="width:50%;display: inline-block;vertical-align: top;">
                    <div style="padding-right:10px">
                        <div style="margin:0; font-weight: bold;font-size:14px;background-color:#FF6A00;padding: 13px 14px;color: rgb(255, 255, 255);">Invoice No: #TGZ<?= $invoice['id']; ?></div>
                        <div style="width:100%;margin:40px 0 0 0">
                            <h2 style="width:100%;font-weight:bold;font-size:14px;color:#000">Invoice to</h2>
                            <ul style="list-style: outside none none;margin:20px 0 0 20px;line-height: 16px;">
                                <li style="width:100%;font-size:12px;"><?= $merchant['merchant_legal_entity_business_name']; ?></li>
                                <li style="width:100%;font-size:12px;"><?= $merchant['merchant_legal_entity_address_line1']; ?>, <?= $merchant['merchant_legal_entity_address_city']; ?></li>
                                <li style="width:100%;font-size:12px;"><?= (!empty($merchant['merchant_legal_entity_business_state'])) ? $merchant['merchant_legal_entity_address_state'] . ', ' : ''; ?><?= $merchant['merchant_legal_entity_address_postal_code']; ?></li>
                                <li style="width:100%;font-size:12px;"><?= $countries[$merchant['merchant_business_country']]['name']; ?></li>
                            </ul>
                        </div> 
                    </div>
                </div><div style="width:50%;display: inline-block;">
                    <div>
                        <div style="background-color:#7E7E7E;display: inline-block;margin: 0;width:50%;color:#fff;padding: 13px 0px;text-align: center;font-weight:bold;font-size:14px;">Date: <?= convert_datetime($invoice['created_at'], $merchant['timezone'], 'd/m/Y'); ?></div><div style="background-color:#7E7E7E;margin:0;display: inline-block;width:50%;color:#fff; padding: 13px 0px;text-align: center;font-weight:bold;font-size:14px;">Status: Paid</div>

                    </div>
                    <div  style="margin:40px 0 0 0">

                    </div>
                </div>
            </div>

            <div style="font-weight: bold;font-size:14px;color: rgb(255, 255, 255);">
                <div style="width:80%;display: inline-block;">
                    <div style="padding-right: 10px">
                        <div style="background-color:#7E7E7E;">
                            <div style="margin:0 0% 0 0;padding: 13px 14px;"> <span style="color:#7E7E7E;">(Tax)</span><br />Tagzie Subscription</div>
                        </div>
                    </div>
                </div><div style="width:20%;display: inline-block;text-align:center;">
                    <div style="background-color:#7E7E7E;">
                        <div style="margin: 0; width:100%;padding: 13px 0px;"><span style="color:#7E7E7E;">(Tax)</span><br />Total</div>
                    </div>
                </div>      
            </div>
            <?php $counter = 1;
            foreach ($invoice['items'] as $item): ?>
                <div style="font-size:14px;color:#000">
                    <div style="text-align:center;">
                        <div style="background-color:<?php if ($counter % 2 == 0): ?> #fff<?php else: ?>#f1f1f1<?php endif; ?>;">
                            <div style="display: inline-block;margin:0 0% 0 0;padding: 13px 0px;width:80%;vertical-align: middle;"><div style="text-align: left;padding: 0 14px"><span style="font-weight:bold;"><?= $item['subscription_package_name']; ?></span><br><span style="font-weight:normal !important;"><?= convert_datetime(gmdate('Y-m-d h:i:s', $item['current_period_start']), $merchant['timezone'], 'd/m/Y'); ?> - <?= convert_datetime(gmdate('Y-m-d h:i:s', $item['current_period_end']), $merchant['timezone'], 'd/m/Y'); ?></span></div></div><div style="display: inline-block;margin: 0; width:20%;padding: 13px 0px;"><?= $currencies['GBP']; ?><?= getMoney($item['amount']); ?></div>
                        </div>
                    </div>      
                </div>
    <?php $counter++;
endforeach; ?>    
            <div style="font-size:14px;color: #fff;font-weight:bold;">
                <div style="text-align:center;">
                    <div style="background-color:#ccc;">
                        <div style="display: inline-block;margin: 0px 0 0px 0;padding: 13px 0px;width:80%;vertical-align: middle;text-align:left;"><span style="margin-left: 14px">Discount:</span></div><div style="display: inline-block;margin: 0; width:20%;padding: 13px 0px;background-color:#ccc"><?= $currencies['GBP']; ?><?= (!empty($invoice['discount']))? $invoice['discount'] : 0.00; ?></div>
                    </div>
                </div>      
            </div> 
            <div style="font-size:14px;color: #fff;font-weight:bold;">
                <div style="text-align:center;">
                    <div style="background-color:#ccc;">
                        <div style="display: inline-block;margin: 0px 0 0px 0;padding: 13px 0px;width:80%;vertical-align: middle;text-align:left;"><span style="margin-left: 14px">Tax:</span></div><div style="display: inline-block;margin: 0; width:20%;padding: 13px 0px;background-color:#ccc"><?= $currencies['GBP']; ?><?= number_format((int) $invoice['tax'], 2); ?></div>
                    </div>
                </div>      
            </div> 
            <div style="font-size:14px;color: #fff;font-weight:bold;">
                <div style="text-align:center;">
                    <div style="background-color:#FF6A00;">
                        <div style="display: inline-block;margin: 0px 0 0px 0;padding: 13px 0px;width:80%;vertical-align: middle;text-align:left;"><span style="margin-left: 14px">Total:</span></div><div style="display: inline-block;margin: 0; width:20%;padding: 13px 0px;background-color:#FF6A00"><?= $currencies['GBP']; ?><?= $invoice['amount']; ?></div>
                    </div>
                </div>      
            </div>           
            <hr>
            <div style="padding:0 14px">
                <div style="font-size:20px;margin:10px 0 20px 0;font-weight:bold"> What is this invoice?</div>
                <p style="margin-bottom:20px">This invoice is for your monthly subscription as a seller on Tagzie.</p> 
                <p style="margin-bottom:20px">Your subscription renews automatically each month to prevent service interruption. If you wish to cancel your Tagzie seller subscription, please visit your Tagzie App, Go to Settings > Application Settings > Deactivate Account. You will still be able to access your account for reference purposes but you will be unable to post any new items.</p>
            </div>
            <hr>
            <div style="padding:0 14px"><div style="display:inline-block;width:50%;text-align: left"><span>Generated at <?= convert_datetime($invoice['created_at'], $merchant['timezone'], 'H:i'); ?> on <?= convert_datetime($invoice['created_at'], $merchant['timezone'], 'd/m/Y'); ?> via Tagzie Invoice</span></div><div style="text-align: right;display:inline-block;width:50%;"><span>TGZ<?= $invoice['id']; ?></span></div></div>
        </div>
    </body>
</html>
