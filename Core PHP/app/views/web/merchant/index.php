<?php
include_once $this->getPart('/web/merchant/common/header.php');
include_once $this->getPart('/web/merchant/common/sidebar.php');
?>
<div class="col-md-3 col-sm-10 col-xs-10">
    <div class="row">
        <div class="col-md-12" style="">
            <div class="oder-pdtung">
                <h1><?php echo $pending_order_count['count']; ?></h1>
                <p><a href="<?= $app['base_url']; ?>account/merchant/orders/overview">orders <br>pending</a></p>
            </div>
        </div>
        <div class="col-md-12 hr-line"><div class="col-md-12 hr-line"><hr></div></div>
        <div class="col-md-12">
            <div class="oder-pdtung">
                <h1><?= $currencies[$merchant['business_currency']]; ?><?php echo number_format($awaiting_withdrawal['amount'], 2); ?></h1>
                <p><a href="<?= $app['base_url']; ?>account/merchant/earnings/overview">awaiting<br> withdrawal</a></p>
            </div>
        </div>
        <div class="col-md-12 hr-line"><div class="col-md-12 hr-line"><hr></div></div>
        <div class="col-md-12">
            <div class="oder-pdtung">
                <h1><?= $open_inquiries_count; ?></h1>
                <p><a href="<?= $app['base_url']; ?>account/merchant/messages/enquiries">questions<br>awaiting reply</a></p>
            </div>
        </div>
        <div class="col-md-12 hr-line"><div class="col-md-12 hr-line"><hr></div></div>
        <div class="col-md-12">
            <div class="oder-pdtung">
                <h1><?= $open_dispute_count; ?></h1>
                <p><a href="<?= $app['base_url']; ?>account/merchant/messages/disputes">pending <br>order dispute</a></p>
            </div>
        </div>
    </div>
    <div class="col-md-12 hr-line"><div class="col-md-12 hr-line"><hr></div></div>

</div>
<div class="col-md-8 col-sm-10 col-xs-10 pull-right">

    <div class="fixing-hit"> 
        <div class="col-md-12 headg"><h2>Recent Orders</h2></div>
        <?php
        //print_r($pending_orders);
        foreach ($pending_orders as $pending_order) {
            ?>
            <div class="col-md-12 awiting">
                <div class="row">
                    <div class="col-md-1"><div class="row"><h1><img src="<?php echo $pending_order['items'][0]['media_thumbnail_image']; ?>" class="img-responsive" alt=""></h1></div></div>
                    <div class="col-md-2"><div class="row"><div class="contnt-txt"><h2>#<?php echo $pending_order['id']; ?><br><?php
                                    if ($pending_order['base_currency_code'] != '') {
                                        echo $currencies[$pending_order['base_currency_code']];
                                    }
                                    ?><?php
                                    if ($pending_order['total']) {
                                        echo $pending_order['total'];
                                    }
                                    ?></h2></div></div></div>
                    <div class="col-md-8"><div class="row"><div class="pragph-detal"><p><?php
                                    if ($pending_order['user_first_name'] != '' && $pending_order['user_last_name'] != '') {
                                        echo $pending_order['user_first_name'] . " " . $pending_order['user_last_name'] . ',';
                                    }
                                    ?> <?php
                                    if ($pending_order['delivery_address']['line_1'] != '') {
                                        echo $pending_order['delivery_address']['line_1'];
                                    }
                                    ?><?php
                                    if ($pending_order['delivery_address']['line_2'] != '') {
                                        echo ", " . $pending_order['delivery_address']['line_2'];
                                    }
                                    ?><?php
                                    if ($pending_order['delivery_address']['line_3'] != '') {
                                        echo ", " . $pending_order['delivery_address']['line_3'];
                                    }
                                    ?><?php
                                    if ($pending_order['delivery_address']['city'] != '') {
                                        echo ", " . $pending_order['delivery_address']['city'];
                                    }
                                    ?>
                                    <?php
                                    if ($pending_order['delivery_address']['zip_code'] != '') {
                                        echo ", " . $pending_order['delivery_address']['zip_code'];
                                    }
                                    ?>          
                                    <?php
                                    if ($pending_order['delivery_address']['state'] != '') {
                                        echo ", " . $pending_order['delivery_address']['state'];
                                    }
                                    ?><?php
                                    if ($pending_order['delivery_address']['country'] != '') {
                                        echo ", " . $countries[$pending_order['delivery_address']['country']]['name'];
                                    }
                                    ?><br><?php
                                    if ($pending_order['items'][0]['media_title'] != '') {
                                        echo $pending_order['items'][0]['media_title'];
                                    }
                                    ?><?php
                                    if ($pending_order['postage_option_label'] != '') {
                                        echo "(" . $pending_order['postage_option_label'] . ")";
                                    }
                                    ?></p></div></div></div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="col-md-12"> <a href="<?= $app['base_url']; ?>account/merchant/orders/overview" class="mor_red">View Orders</a></div>
    <div class="col-md-12 hr-line" style="padding:0"><hr></div>





    <div class="">        
        <div class="col-md-12 awiting" style="padding: 0;">

            <div class="fixing-hit">
                <div class="col-md-12 headg"><h2>Earnings Report</h2></div>
                <div class="col-md-12"><div class="row" style="overflow: hidden"><div class="col-md-12">
                            <canvas id="earningGraph" height="50"></canvas>
                        </div></div>
                </div>
            </div>   
        </div>
        <div class="col-md-12"> <a href="<?= $app['base_url']; ?>account/merchant/earnings/overview" class="mor_red">View Earnings</a></div>
        <div class="col-md-12 hr-line" style="padding:0"><hr></div>
    </div>



    <div class="fixing-hit" style="overflow: hidden;">
        <div class="col-md-12 headg"><h2>Recent Queries</h2></div>
        <?php foreach ($open_inquiries as $inquiry) { ?>
            <div class="col-md-12 awiting">
                <div class="row">
                    <div class="fixing-hit">
                        <div class="col-md-2"><div class="contnt-txt"><h2><a href="<?= $app['base_url']; ?>account/merchant/messages/enquiries"><?= "Q#" . $inquiry['id']; ?></a></h2></div></div>
                        <div class="col-md-10"><div class="row"><div class="pragph-detal"><p><?= ucfirst($inquiry['type']); ?> Enquiry: '<?= $inquiry['media_title']; ?>' <br /><?= $inquiry['text']; ?><span><?= $inquiry['sender_first_name'][0] . '. ' . $inquiry['sender_last_name'] . ', ' . $countries[$inquiry['sender_country']]['name']; ?></span></p></div></div></div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <!--div class="col-md-12 awiting">
            <div class="row">
                <div class="col-md-2"><div class="row"><div class="contnt-txt"><h2>#Q112364</h2></div></div></div>
                <div class="col-md-10"><div class="row"><div class="pragph-detal"><p>Shipping & Delivery Enquiry: Money Clothing ‘Super Club Crew’...
                                Hi, do you ship to Turkey? We have no stockists of your brand, so I have to order online. I look forward to hearing back from you.<span>T. Bag, Instanbul, Turkey</span></p></div></div></div>
            </div>
        </div>  
        <div class="col-md-12 awiting">
            <div class="row">
                <div class="col-md-2"><div class="row"><div class="contnt-txt"><h2>#Q112364 </h2></div></div></div>
                <div class="col-md-10"><div class="row"><div class="pragph-detal"><p>General Product Enquiry: Money Clothing ‘Emoji’ Sweatshirt
                                Do you have these in any other colour but black?<span>M, Mathers, Detroit, USA</span></p></div></div></div>
            </div>
        </div-->  


    </div>
    <div class="col-md-12"> <a href="<?= $app['base_url']; ?>account/merchant/messages/enquiries" class="mor_red">View Messages</a></div>
    <div class="col-md-12 hr-line" style="padding:0 0 0 0px"><hr></div>






    <div class="fixing-hit" style="overflow: hidden;">      
        <div class="col-md-12 awiting">
            <div class="col-md-12 headg"><h2>Recent Disputes</h2></div>
            <?php foreach ($open_disputes as $dispute): ?>

                <div class="fixing-hit">
                    <div class="col-md-2"><div class="contnt-txt"><h2><a href="<?= $app['base_url']; ?>account/merchant/messages/disputes">#D<?= $dispute['id']; ?></a></h2></div></div>
                    <div class="col-md-10"><div class="row"><div class="pragph-detal"><p>Category: <?= ucfirst($dispute['type']); ?><br>
                                    Order ref: TGZ#<?= $dispute['order_id']; ?>  (<?= convert_datetime($dispute['created_at'], $user['timezone'], 'd/m/Y'); ?>)<br>
                                    Est. delivery: <?= convert_datetime(gmdate('Y-m-d H:i:s', strtotime($dispute['created_at'] . ' UTC + ' . ($dispute['expected_dispatch_time'] + $dispute['expected_transit_time']) . ' days')), $user['timezone'], 'd/m/Y'); ?>
                                </p><p>Message: <?= $dispute['text']; ?></p></div></div></div>
                </div>
            <?php endforeach; ?>
        </div>  

    </div>
    <div class="col-md-12"> <a href="<?= $app['base_url']; ?>account/merchant/messages/disputes" class="mor_red">View Disputes</a></div>
    <div class="col-md-12 hr-line" style="padding:0 0 0 0px"><hr></div>



    <?php include_once $this->getPart('/web/merchant/common/footer.php'); ?>
    <script>
<?php
foreach ($earnings_fourteen_days as $earnings_fourteen_day) {

    $dates[] = gmdate('j M', strtotime($earnings_fourteen_day['date'] . ' UTC'));
    $amounts[] = $earnings_fourteen_day['amount'];
}

$max = max($amounts);
$step_size = strlen(round($max, 0));

$step_size = ($max > 999)? '25'.str_repeat('0', ($step_size-3)) : 25;
?>
        var data = {
            labels: <?= json_encode($dates); ?>,
            datasets: [
                {
                    data: <?= json_encode($amounts); ?>,
                    backgroundColor: 'rgb(255, 90, 0)',
                    label: 'Earnings in ' + decodeEntities('<?= $currencies[$merchant['business_currency']]; ?>'),
                }
            ]
        };
        var options = {
            legend: {
                display: false
            },
            responsive: true,
            scales: {
                xAxes: [{
                        display: true,
                        gridLines: {
                            display: false
                        },
                    }],
                yAxes: [{
                        display: true,
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            stepSize: <?= $step_size; ?>,
                            beginAtZero: true,
                            callback: function (value, index, values) {
                                return decodeEntities('<?= $currencies[$merchant['business_currency']]; ?>') + abbreviate_number(value, 0);
                            }
                        }
                    }]
            }
        };
        var ctx = document.getElementById("earningGraph");
        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options
        });
    </script>