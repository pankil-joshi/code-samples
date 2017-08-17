<?php
include_once $this->getPart('/web/admin/common/header.php');
include_once $this->getPart('/web/admin/common/sidebar.php');
?>
<div class="col-md-11 col-sm-10 col-xs-10 right_brd">
    <div class="desbord_body check_outstp">
        <div class="row">
            <div class="col-md-12 lastest_marg">
                <div class="col-md-12">
                    <div class="yor_active" style="border:0">
                        <div class="order-active">
                            <div class="col-md-12">
                                <h6>Devices of <?= $user['instagram_username']; ?></h6>
                            </div>
                            <!--                            <div class="col-md-3">
                                                            <div class="lable-date">
                            
                                                                <label>Sort:</label>
                                                                <div class="select-style date-order">
                                                                    <select id="sort">
                                                                        <option value="desc">Date (Latest - Oldest)</option>
                                                                        <option value="asc">Date (Oldest - Latest)</option>
                                                                    </select>
                                                                </div>   
                            
                                                            </div>
                                                        </div>-->
                            <!--                            <div class="col-md-6">
                                                            <ul class="orderdetil" id="filter-nav">
                                                                <li data-filter="none" class="filter"><a href="#"><span class="active"></span></a>All</li>    
                                                                <li data-filter="completed" class="filter"><a href="#"><span class="deactive"></span></a>Completed</li>
                                                                <li data-filter="in_progress" class="filter"><a href="#"><span class="deactive"></span></a>Processing</li>
                                                                <li data-filter="tagged" class="filter"><a href="#"><span class="deactive"></span></a>Tagged</li>
                                                                <li>
                            
                                                                    <a href="#" id="datepicker" data-date="<?= date('m/d/Y'); ?>"  style="display: inline-block;"><img src="<?= $app['base_assets_url']; ?>images/calder.png" class="img-responsive" alt=""></a>
                                                                    <input type="hidden" id="date-range-start"><input type="hidden" id="date-range-end">
                                                                </li>
                                                            </ul>
                                                        </div>-->
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="row">
                                <table class="table user_lists devices_list">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Updated at</th>
                                            <th>Platform</th>
                                            <th>Version</th>
                                            <th>Model</th>
                                            <th>Manufacturer</th>
                                            <th>Notification ID</th>
                                            <th>Logged In</th>
                                        </tr>
                                    </thead>
                                    <tbody id="devices">
                                        <?php include_once $this->getPart('/web/admin/components/devices_list_view.php'); ?>
                                    </tbody>
                                </table>        
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    var replaceDeviceList = function (html, callback) {

        if ($('#devices .device-row').length > 0) {

            $('#devices').html(html).hide().fadeIn('slow');
        } else {

            $('#devices').html(html).hide().fadeIn('slow');
        }

        if (typeof callback == 'function') {
            callback();
        }

    }

    $(document).ready(function () {

        $('body').on('click', '.pagination-container > li > a', function (e) {
            e.preventDefault();

            var data = {page: $(this).data('page'), order: $('#sort').val(), user_id: <?= $user['id']; ?>};
            if ($('#date-range-start').length > 0 && $('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

                data.filter = 'custom';
                data.start_date = $('#date-range-start').val();
                data.end_date = $('#date-range-end').val();
            }

            $.when(getUserDevicsListViewByAdmin(data)).then(function (html) {

                replaceDeviceList(html);
            });

        });

//        $('#datepicker').daterangepicker({
//            locale: {
//                format: 'YYYY-MM-DD'
//            },
//            startDate: '<?= date('Y-m-d'); ?>'
//        }, function (start, end, label) {
//            $('#date-range-start').val(start.format('YYYY-MM-DD'));
//            $('#date-range-end').val(end.format('YYYY-MM-DD'));
//
//            var data = {page: $(this).data('page'), order: $('#sort').val(), user_type: user_type};
//            if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {
//
//                data.filter = 'custom';
//                data.start_date = $('#date-range-start').val();
//                data.end_date = $('#date-range-end').val();
//            }
//            $.when(getOrdersListViewByAdmin(data)).then(function (html) {
//                //console.log(html);
//                replaceOrderList(html);
//
//            });
//        });
//        $('#filter-nav').on('click', 'li.filter', function (e) {
//            e.preventDefault();
//
//            if ($(this).find('span').hasClass('deactive')) {
//
//                $('#filter-nav a > span').removeClass('active').addClass('deactive');
//                $(this).find('span').addClass('active');
//            }
//
//            var data = {order: $('#sort').val()};
//            if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {
//
//                data.filter = 'custom';
//                data.start_date = $('#date-range-start').val();
//                data.end_date = $('#date-range-end').val();
//            }
//            $.when(getUserList(data)).then(function (html) {
//
//                replaceOrderList(html, initializeBarRating);
//
//            });
//        });
//        $('#sort').change(function () {
//            var data = {order: $(this).val(), user_type: user_type};
//            if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {
//
//                data.filter = 'custom';
//                data.start_date = $('#date-range-start').val();
//                data.end_date = $('#date-range-end').val();
//            }
//            $.when(getOrdersListViewByAdmin(data)).then(function (html) {
//                //console.log(html);
//                replaceOrderList(html);
//
//            });
//        });
    });
</script>
<?php include_once $this->getPart('/web/admin/common/footer.php'); ?>
