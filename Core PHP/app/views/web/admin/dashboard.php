<?php
include_once $this->getPart('/web/admin/common/header.php');
include_once $this->getPart('/web/admin/common/sidebar.php');
?>
<div class="col-md-8 col-sm-10 col-xs-10">
    <!--    <div class="col-md-12">
            <div class="istapd">    
                <h1>InstaPaid Visitors</h1>
                <img src="<?= $app['base_assets_url']; ?>images/graps.png" class="img-responsive" alt="">
            </div>
        </div>-->
    <!--    <div class="col-md-12">
            <div class="istapd">
                <h1>Platform Transactions</h1>
                <img src="<?= $app['base_assets_url']; ?>images/graps_two.png" class="img-responsive" alt="">
            </div>
        </div>-->
    <div class="col-md-6">
        <div class="seller">
            <h1>Top 10 Sellers </h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Instagram Username</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($top_merchants)):
                        foreach ($top_merchants as $top_merchant) :
                            ?>
                            <tr>
                                <td><?= $top_merchant['id']; ?></td>
                                <td><?= $top_merchant['instagram_username']; ?></td>
                            </tr>
                        <?php endforeach;
                    else:
                        ?> 
                        <tr>
                            <td colspan="2" style="
                                text-align: center;
                                ">No sellers found!</td>
                        </tr> 
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div> 
    <div class="col-md-6">
        <div class="seller">
            <h1>Top 10 Items</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Item</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($top_items)) :
                        foreach ($top_items as $top_item) :
                            ?>
                            <tr>
                                <td><?= $top_item['media_id']; ?></td>
                                <td><?= $top_item['media_title']; ?></td>
                            </tr>
                        <?php endforeach;
                    else:
                        ?>
                        <tr>
                            <td colspan="2" style="
                                text-align: center;
                                ">No items found!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>    
</div>
<div class="col-md-3 col-sm-10 col-xs-10 pull-right right_aside_fixed claider_aline_main">
    <div class="right_bar">
        <h1><div id="selected_range" class="datepicker">Duration: Last 30 days (<?= date('d/m/y', strtotime(date('Y-m-d') . ' -1 months')); ?> - <?= date('d/m/y'); ?>) <a class="pull-right"><span class="glyphicon glyphicon-triangle-bottom"></span></a></div></h1>
        <input type="hidden" id="date-range-start" value="<?= date('Y-m-d', strtotime(date('Y-m-d') . ' -1 months')); ?>"><input type="hidden" id="date-range-end" value="<?= date('Y-m-d'); ?>">

    </div>
    <div id="dashboard_details" class="right_bar">
<?php include_once $this->getPart('/web/admin/components/dashboard_details.php'); ?>
    </div>
</div>

<script>

    var replaceOrderList = function (html, callback) {
        $('#dashboard_details').html(html).hide().fadeIn('slow');

        if (typeof callback == 'function') {
            callback();
        }

    }
    $(document).ready(function () {
        var data = {};

        if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

            data.filter = 'custom';
            data.start_date = $('#date-range-start').val();
            data.end_date = $('#date-range-end').val();


            $.when(getAdminDashboardDetails(data)).then(function (html) {

                replaceOrderList(html);

            });
        }
        /*
         * Datepicker
         */
        $('.datepicker').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY'
            },
            startDate: '<?= gmdate('d/m/Y'); ?>'
        }, function (start, end, label) {
            $('#date-range-start').val(start.format('YYYY-MM-DD'));
            $('#date-range-end').val(end.format('YYYY-MM-DD'));
            $('#selected_range').html('Duration: (' + start.format('DD/MM/YY') + ' - ' + end.format('DD/MM/YY') + ')')
            var data = {};

            if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

                data.filter = 'custom';
                data.start_date = $('#date-range-start').val();
                data.end_date = $('#date-range-end').val();
            }

            $.when(getAdminDashboardDetails(data)).then(function (html) {

                replaceOrderList(html);

            });
        });

    });
</script>    
<?php include_once $this->getPart('/web/admin/common/footer.php'); ?>