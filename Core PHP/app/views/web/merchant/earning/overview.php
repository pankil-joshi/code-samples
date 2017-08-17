<?php
include_once $this->getPart('/web/merchant/common/header.php');
include_once $this->getPart('/web/merchant/common/sidebar.php');
?>
<div class="col-md-11 col-sm-10 col-xs-10 pull-right">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="order-active" style="padding:0">
                <div class="col-md-9 col-sm-12 col-xs-12">
                    <h6>Earnings Overview</h6>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                </div>
                <div class="col-md-1 col-sm-6 col-xs-12">
                    <a href="#" id="datepicker"><img alt="" class="img-responsive cldr_sumry" src="<?= $app['base_assets_url']; ?>images/calder.png"></a>
                    <input type="hidden" id="date-range-start"><input type="hidden" id="date-range-end">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-11 col-sm-10 col-xs-10 pull-right">
    <div class="col-md-12 col-sm-12">
        <div style="width:800px; position: relative;margin: 0 auto;">
            <canvas id="earningGraph" width="800" height="400"></canvas>
        </div>
    </div>
    <div class="top_earning">
        <div class="col-md-4 col-sm-12">
            <div class="oder-pdtung earning_diagram">
                <h1><?= $currencies[$merchant['business_currency']]; ?><?= number_format($awaiting_withdrawl['amount'], 2); ?></h1>
                <p>awaiting
                    <br> withdrawal</p>
            </div>
        </div>
        <div class="col-md-7 col-sm-12 pull-right">
            <div class="row">
                <div class="recent_earnig">
                    <h3>Your Recent Earnings</h3>
                    <ul>
                        <li>This month you have earned:<span><?= $currencies[$merchant['business_currency']]; ?><?= number_format($earnings_this_month, 2); ?></span></li>
                        <li>This time last month you had earned:<span><?= $currencies[$merchant['business_currency']]; ?><?= number_format($earnings_last_month, 2); ?></span></li>
                        <li>This month you have refunded:<span><?= $currencies[$merchant['business_currency']]; ?><?= number_format($refunds, 2); ?></span></li>
                        <li>Transaction Fee’s this month:<span><?= $currencies[$merchant['business_currency']]; ?><?= number_format($transaction_fee, 2); ?></span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--    <div class="col-md-12">
            <div class="clnder_detil">
                <div class="col-md-7 col-sm-6">
                    <ul>
                        <li><a href="<?= $app['base_url'] . 'account/merchant/earnings/overview'; ?><?= (!empty($_GET['date']) && $_GET['date'] < strtotime(date('d/m/Y'))) ? '?date=' . ($_GET['date'] + 86400) : '#'; ?>" style="<?= (!empty($_GET['date']) && $_GET['date'] < strtotime(date('d/m/Y'))) ? '' : 'color:#ffae82' ?>" <?= (!empty($_GET['date']) && $_GET['date'] < strtotime(date('d/m/Y'))) ? '' : 'disabled' ?>><span class="glyphicon glyphicon-triangle-right"></span></a> </li>
                        <li><?= (!empty($_GET['date']) && $_GET['date']) ? date('d/m/Y', $_GET['date']) : date('d/m/Y'); ?></li>
                        <li><a href="#" data-time><span class="glyphicon glyphicon-triangle-left"></span></a> </li>
                    </ul>
                </div>
                <div class="col-md-5 col-sm-6">
                    <img src="<?= $app['base_assets_url']; ?>images/calder.png" class="clir_dtl" alt="">
                </div>
            </div>
        </div>-->
</div>
<?php include_once $this->getPart('/web/merchant/common/footer.php'); ?>
<script>
<?php
foreach ($earnings_fourteen_days as $earnings_fourteen_day) {

    $dates[] = $earnings_fourteen_day['date'];
    $amounts[] = $earnings_fourteen_day['amount'];
}

$max = max($amounts);
$step_size = strlen(round($max, 0));

$step_size = ($max > 999) ? '25' . str_repeat('0', ($step_size - 3)) : 25;
?>
    var data = {
        labels: <?= json_encode($dates); ?>,
        datasets: [
            {
                data: <?= json_encode($amounts); ?>,
                backgroundColor: 'rgb(255, 90, 0)',
                label: 'Earnings in ' + decodeEntities('<?= $currencies[$merchant['business_currency']]; ?>')
            }
        ]
    };

    var options = {
        legend: {
            display: false
        },
        scales: {
            responsive: false,
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

    $(document).ready(function () {

        $('#datepicker').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY'
            },
            startDate: '<?= date('d/m/Y'); ?>',
            dateLimit: {
                "days": 14
            }
        }, function (start, end, label) {
            $('#date-range-start').val(start.format('YYYY-MM-DD'));
            $('#date-range-end').val(end.format('YYYY-MM-DD'));

            var data = {};

            if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

                data.filter = 'custom';
                data.start_date = $('#date-range-start').val();
                data.end_date = $('#date-range-end').val();
            }

            $.when(getMerchantEarnings(data)).then(function (response) {
                
                var labels = [];
                var datasets = [];
                
                $(response.data.earnings_fourteen_days).each(function(i, item){
                    
                    labels.push(item.date);
                    datasets.push(item.amount);
                });
                
                myBarChart.config.data.labels = labels;
                myBarChart.config.data.datasets[0].data = datasets;
                myBarChart.update();

            });
        });
    });
</script>