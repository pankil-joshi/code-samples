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
                       font-size: 12px;line-height: 16px;">Company number 09956906. Registered in England and
Wales. Registered office: 32 Thistlebank, East Leake,
Loughborough, LE12 6RS, United Kingdom
</p></div><div style="display: inline-block;width: 50%;background-color:#FF6A00;"><div style="padding: 15px 0;"><ul style="list-style: outside none none;margin-left: 15px;">
                            <li style=" align-items: center;
                                color: rgb(255, 255, 255);
                                font-weight: bold;
                                width: 100%;
                                margin-bottom: 10px;"><img style="display: inline-block;vertical-align: middle;
                                   width: 35px;" src="<?= $app['base_assets_url']; ?>images/mobile.jpg" class="img-responsive" alt=""><span style="margin-left: 10px;">+44 1509 559 788</span></li>
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
                    Settlement Invoice / <?= convert_datetime($invoice['created_at'], $merchant['timezone'], 'dS F Y'); ?>
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
                                <li style="width:100%;font-size:12px;"><?= (!empty($merchant['merchant_legal_entity_business_state']))? $merchant['merchant_legal_entity_address_state'] . ', ': ''; ?><?= $merchant['merchant_legal_entity_address_postal_code']; ?></li>
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
                            <div style="margin:0 0% 0 0;padding: 13px 14px;"> <span style="color:#7E7E7E;">(Tax)</span><br />Tagzie Transaction Commissions</div>
                        </div>
                    </div>
                </div><div style="width:20%;display: inline-block;text-align:center;">
                    <div style="background-color:#7E7E7E;">
                        <div style="margin: 0; width:100%;padding: 13px 0px;"><span style="color:#7E7E7E;">(Tax)</span><br />Total</div>
                    </div>
                </div>      
            </div>
            <?php $counter = 1; foreach(unserialize($invoice['items']) as $item): ?>
            <div style="font-size:14px;color:#000">
                <div style="text-align:center;">
                    <div style="background-color:<?php if($counter%2==0): ?> #fff<?php else: ?>#f1f1f1<?php endif; ?>;">
                        <div style="display: inline-block;margin:0 0% 0 0;padding: 13px 0px;width:80%;vertical-align: middle;"><div style="text-align: left;padding: 0 14px"><span style="font-weight:bold;">#TGZ<?= $item['order_id']; ?></span><br><span style="font-weight:normal"><?= $item['transaction_fee_rate']; ?>% of order total <?= $currencies[$merchant['merchant_business_currency']]; ?><?= $item['order_total']; ?> [You receive <?= $currencies[$merchant['merchant_business_currency']]; ?><?= $item['recieved_amount']; ?>]</span></div></div><div style="display: inline-block;margin: 0; width:20%;padding: 13px 0px;"><?= $currencies[$merchant['merchant_business_currency']]; ?><?= $item['transaction_fee']; ?></div>
                    </div>
                </div>      
            </div>
            <?php $counter++; endforeach;?>            
            <div style="font-size:14px;color: #fff;font-weight:bold;">
                <div style="text-align:center;">
                    <div style="background-color:#FF6A00;">
                        <div style="display: inline-block;margin: 0px 0 0px 0;padding: 13px 0px;width:80%;vertical-align: middle;text-align:left;"><span style="margin-left: 14px">Total:</span></div><div style="display: inline-block;margin: 0; width:20%;padding: 13px 0px;background-color:#FF6A00"><?= $currencies[$merchant['merchant_business_currency']]; ?><?= $invoice['amount']; ?></div>
                    </div>
                </div>      
            </div>
            <div style="font-size:14px;color: #848386;font-weight:bold;">
                <div style="text-align:center;">
                    <div style="background-color:#fee5d2;">
                        <div style="display: inline-block;margin:0 0% 0 0;padding: 13px 0px;width:80%;vertical-align: middle;text-align:left;"><span style="margin-left: 14px;font-style:italic">You receive:</span></div><div style="display: inline-block;margin: 0; width:20%;padding: 13px 0px;background-color:#fee5d2"><?= $currencies[$merchant['merchant_business_currency']]; ?><?= $invoice['recieved_amount']; ?></div>
                    </div>
                </div>      
            </div>            
            <hr>
            <div style="padding:0 14px">
                <div style="font-size:20px;margin:10px 0 20px 0;font-weight:bold"> What is this invoice?</div>
                <p style="margin-bottom:20px">This invoice is a summary of your transaction settlement payments that have been collected from your orders. These have been automatically paid to Tagzie and the remaining balance has been withdrawn to your account. Please retain this invoice for your records.</p> 
                <p style="margin-bottom:20px">As per the Tagzie Terms of Use which you have accepted, any refunds requested within the first 14 days of the customer receiving the order (considered the cooling off period) shall be accepted by yourself providing the condition of the respective item meets our refund eligibility guidelines. If the item type belongs to our non-refundable list, then the customer has wavered their cooling off period at the time of purchase and you are not obliged to refund. In any case, we advise you to put the customer first in everything you do and try to be as flexible and accommodating to refunds or disputes beyond the first mandatory 14 days.</p>
                <p>Should you refund a customer, Tagzie will refund the respective transaction fee relating to the refund amount at the point of processing the refund.</p>
            </div>
            <hr>
            <div style="padding:0 14px"><div style="display:inline-block;width:50%;text-align: left"><span>Generated at <?= convert_datetime($invoice['created_at'], $merchant['timezone'], 'H:i'); ?> on <?= convert_datetime($invoice['created_at'], $merchant['timezone'], 'd/m/Y'); ?> via Tagzie Invoice</span></div><div style="text-align: right;display:inline-block;width:50%;"><span>TGZ<?= $invoice['id']; ?></span></div></div>
        </div>
    </body>
</html>
