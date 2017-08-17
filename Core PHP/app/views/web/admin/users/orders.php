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
                            <div class="col-md-8">
                                <h6>Orders of <?= $user['instagram_username']; ?></h6>
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
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Date</th>
                                            <th>Product Image</th>
                                            <th>Product Title</th>
                                            <th>Merchant Username</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orders">
                                        <?php include_once $this->getPart('/web/admin/components/orders_list_view.php'); ?>
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
<?php include_once $this->getPart('/web/admin/components/cancel_order_form.php'); ?>
<script>

    var replaceOrderList = function (html, callback) {

        if ($('#orders .order-row').length > 0) {

            $('#orders').html(html).hide().fadeIn('slow');
        } else {

            $('#orders').html(html).hide().fadeIn('slow');
        }

        if (typeof callback == 'function') {
            callback();
        }

    }
    
    var refreshOrderList;
    $(document).ready(function () {

        $('body').on('click', '.pagination-container > li > a', function (e) {
            e.preventDefault();

            var data = {page: $(this).data('page'), user_id: '<?= $user['id']; ?>'};
            $.when(getOrdersListViewByAdmin(data)).then(function (html) {

                replaceOrderList(html);
            });

        });

        $('#datepicker').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            },
            startDate: '<?= date('Y-m-d'); ?>'
        }, function (start, end, label) {
            $('#date-range-start').val(start.format('YYYY-MM-DD'));
            $('#date-range-end').val(end.format('YYYY-MM-DD'));

            var data = {page: $(this).data('page'), order: $('#sort').val(), user_type: user_type};
            if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

                data.filter = 'custom';
                data.start_date = $('#date-range-start').val();
                data.end_date = $('#date-range-end').val();
            }
            $.when(getOrdersListViewByAdmin(data)).then(function (html) {
                //console.log(html);
                replaceOrderList(html);

            });
        });
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
        $('#sort').change(function () {
            var data = {order: $(this).val(), user_type: user_type};
            if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

                data.filter = 'custom';
                data.start_date = $('#date-range-start').val();
                data.end_date = $('#date-range-end').val();
            }
            $.when(getOrdersListViewByAdmin(data)).then(function (html) {
                //console.log(html);
                replaceOrderList(html);

            });
        });
    $('#orders').on('click', '.cancel', function () {

        $("#cancel-order-form input").val('');
        $("#cancel-order-form").data('id', $(this).data('id'));
        $("#cancel-order-form").modal('show');

    });
    $("#cancel-order").on('submit', function (e) {
        
        e.preventDefault();
        var form = $(this);

        form.parsley().validate();

        if (form.parsley().isValid()) {
            
            var data = {};
            var formData = $('#cancel-order').serializeObject();

            data.body = formData;
            data.order_id = $('#cancel-order-form').data('id');
            
                $.when(cancelOrderByAdmin(data)).then(function (response) {
                    if (response.meta.success == true) {
                        toastr.success('Order cancelled successfully.');
                        data = {user_id: <?= $user['id']; ?>};
                        $.when(getOrdersListViewByAdmin(data)).then(function (html) {
                            //console.log(html);
                            replaceOrderList(html);

                        });
                        
                        $("#cancel-order-form").modal('hide');
                        hideLoader('#cancel-order-form .modal-content');
                    }

                });
            }
        });
    });
</script>
<?php include_once $this->getPart('/web/admin/common/footer.php'); ?>
