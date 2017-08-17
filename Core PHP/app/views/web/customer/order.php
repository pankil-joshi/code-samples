<?php include_once $this->getPart('/web/common/header.php'); ?>
<?php include_once $this->getPart('/web/customer/top_nav.php'); ?>
<div class="desbord_body check_outstp">
    <div class="container">
        <div class="row">
            <div class="col-md-12 lastest_marg">
                <div class="col-md-12">
                    <div class="yor_active" style="border:0">
                        <div class="order-active">
                            <div class="col-md-3">
                                <h6>Your Activity</h6>
                            </div>

                            <div class="col-md-3">
                                <div class="lable-date">
                                    <label>Sort:</label>
                                    <div class="select-style date-order">
                                        <select id="sort">
                                            <option value="desc">Latest First</option>
                                            <option value="asc">Oldest First</option>
                                        </select>
                                    </div>   
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="lable-date">
                                    <label>Filter By:</label>
                                    <div class="select-style date-order">
                                        <select id="filter-by">
                                            <option value="none">All</option>
                                            <option value="completed">Completed</option>
                                            <option value="in_progress">Awaiting Dispatch</option>
                                            <option value="shipped">Shipped</option>
                                            <option value="cancelled">Cancelled</option>
                                            <option value="returned">Returned</option>
                                            <option value="disputed ">Disputed</option>
                                            <option value="tagged">Tagged</option>
                                            <option value="requested_cancellation">Pending Cancellation</option>
                                            <option value="declined">Cancellation Declined</option>
                                        </select>
                                    </div>   
                                </div>
                                <!--                                <ul class="orderdetil" id="filter-nav">
                                                                    <li data-filter="none" class="filter"><a href="#"><span class="active"></span></a>All</li>    
                                                                    <li data-filter="completed" class="filter"><a href="#"><span class="deactive"></span></a>Completed</li>
                                                                    <li data-filter="in_progress" class="filter"><a href="#"><span class="deactive"></span></a>Processing</li>
                                                                    <li data-filter="tagged" class="filter"><a href="#"><span class="deactive"></span></a>Tagged</li>
                                                                    <li>
                                
                                                                        <a href="#" id="datepicker" data-date="<?= date('m/d/Y'); ?>"  style="display: inline-block;"><img src="<?= $app['base_assets_url']; ?>images/calder.png" class="img-responsive" alt=""></a>
                                                                        <input type="hidden" id="date-range-start"><input type="hidden" id="date-range-end">
                                                                    </li>
                                                                </ul>-->
                            </div>

                            <div class="col-md-1">
                                <a href="#" id="datepicker" data-date="<?= date('m/d/Y'); ?>" class="caleneder-srh" ><img src="<?= $app['base_assets_url']; ?>images/calder.png" class="img-responsive" alt=""></a>
                                <input type="hidden" id="date-range-start"><input type="hidden" id="date-range-end">
                            </div>

                        </div>
                        <div class="col-md-12 col-sm-12" id="orders">
                            <div class="row">
                                <?php include_once $this->getPart('/web/customer/components/order_list.php'); ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once $this->getPart('/web/components/message.php'); ?>
<?php include_once $this->getPart('/web/components/new_thread.php'); ?>
<?php include_once $this->getPart('/web/customer/components/cancel_order_form.php'); ?>
<script>
    var rateOrder = function (value, text, event) {

        var orderid = this.$elem.data('orderid');

        var data = {};
        data.order_id = orderid;
        data.body = {rating: value};

        $.when(postOrderRating(data)).then(function (data) {

            if (data.meta.success) {
                var data = {status: $('#filter-by').val(), page: $('#order-pagination a.active').data('page'), order: $('#sort').val()};

                if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

                    data.filter = 'custom';
                    data.start_date = $('#date-range-start').val();
                    data.end_date = $('#date-range-end').val();
                }

                $.when(getOrderView(data)).then(function (html) {

                    replaceOrderList(html, initializeBarRating);

                });
            }

        });
    }
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
    var activeSendButton;
    $(document).ready(function () {

        $('body').on('click', '.message-button', function (e) {
            e.preventDefault();
            activeSendButton = $(this);
            if ($(this).data('id')) {
                var data = {thread_id: $(this).data('id'), second_user_id: $(this).data('second_user_id')};
                refreshThread(data);
            } else {
                $('#new-thread .type').val('');
                $('#new-thread .message-text').val('');
                $('#new-thread').data('order_id', $(this).data('order_id'));
                $('#new-thread').data('product_id', $(this).data('product_id'));
                $('#new-thread').data('second_user_id', $(this).data('second_user_id'));
                var dispute = ($(this).data('type') == 'dispute') ? 1 : 0;
                $('#new-thread').data('dispute', dispute);
                $('#new-thread .modal-title').text($(this).data('type'));
                $('#new-thread').modal('show');
            }

        });

        $('body').on('click', '.pagination-container > li > a', function (e) {
            e.preventDefault();

            var data = {status: $('#filter-by').val(), page: $(this).data('page'), order: $('#sort').val()};
            if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

                data.filter = 'custom';
                data.start_date = $('#date-range-start').val();
                data.end_date = $('#date-range-end').val();
            }
            $.when(getOrderView(data)).then(function (html) {

                replaceOrderList(html, initializeBarRating);

            });

        });

        $('#datepicker').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY'
            },
            startDate: '<?= date('d/m/Y'); ?>'
        }, function (start, end, label) {
            $('#date-range-start').val(start.format('YYYY-MM-DD'));
            $('#date-range-end').val(end.format('YYYY-MM-DD'));

            var data = {status: $('#filter-by').val(), page: $(this).data('page'), order: $('#sort').val()};
            if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

                data.filter = 'custom';
                data.start_date = $('#date-range-start').val();
                data.end_date = $('#date-range-end').val();
            }
            $.when(getOrderView(data)).then(function (html) {

                replaceOrderList(html, initializeBarRating);

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
//            var data = {status: $(this).closest('li.filter').data('filter'), order: $('#sort').val()};
//            if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {
//
//                data.filter = 'custom';
//                data.start_date = $('#date-range-start').val();
//                data.end_date = $('#date-range-end').val();
//            }
//            $.when(getOrderView(data)).then(function (html) {
//
//                replaceOrderList(html, initializeBarRating);
//
//            });
//        });

        $('#filter-by').change(function (e) {
            e.preventDefault();
            var data = {status: $(this).val(), order: $('#sort').val()};
            if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {
                alert();
                data.filter = 'custom';
                data.start_date = $('#date-range-start').val();
                data.end_date = $('#date-range-end').val();
            }
            $.when(getOrderView(data)).then(function (html) {

                replaceOrderList(html, initializeBarRating);

            });
        });

        $('#sort').change(function () {

            var data = {status: $('#filter-by').val(), order: $(this).val()};
            if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

                data.filter = 'custom';
                data.start_date = $('#date-range-start').val();
                data.end_date = $('#date-range-end').val();
            }
            $.when(getOrderView(data)).then(function (html) {

                replaceOrderList(html, initializeBarRating);

            });
        });
        initializeBarRating();

        $('#orders').on('click', '.cancel-order-button.enabled', function () {

            $("#cancel-order-form textarea").val(' ');
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

                $.when(cancelOrderByCustomer(data)).then(function (response) {

                    if (response.meta.success == true) {
                        toastr.success(response.data.message);
                        var data = {status: $('#filter-by').val(), page: $('#order-pagination .active').data('page'), order: $('#sort').val()};
                        if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

                            data.filter = 'custom';
                            data.start_date = $('#date-range-start').val();
                            data.end_date = $('#date-range-end').val();
                        }
                        $.when(getOrderView(data)).then(function (html) {

                            replaceOrderList(html, initializeBarRating);

                        });

                        $("#cancel-order-form").modal('hide');
                        hideLoader('#cancel-order-form .modal-content');
                    }

                });
            }
        });
    });
</script>
<?php include_once $this->getPart('/web/common/footer.php'); ?>