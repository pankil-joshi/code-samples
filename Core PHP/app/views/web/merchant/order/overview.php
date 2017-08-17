<?php
include_once $this->getPart('/web/merchant/common/header.php');
include_once $this->getPart('/web/merchant/common/sidebar.php');
?>

<div class="col-md-11 col-sm-10 col-xs-10 pull-right">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="order-active">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h6>Orders</h6>
                </div>
                <div class="col-md-5 col-sm-7 col-xs-12">
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

                <div class="col-md-6 col-sm-7 col-xs-12">
                    <div class="lable-date">
                        <label>Filter By:</label>
                        <div class="select-style date-order">
                            <select id="filter-by">
                                <option value="none">All</option>
                                <option value="in_progress">Pending</option>
                                <option value="shipped">Dispatched</option>
                                <option value="returned">Disputes</option>
                                <option value="cancelled">Cancelled/Refunded</option>
                                <option value="requested_cancellation">Pending Cancellation</option>
                                <option value="declined">Cancellation Declined</option>
                            </select>
                        </div>   
                    </div>
                </div>

                <div class="col-md-1 col-sm-7 col-xs-12">
                    <a id="datepicker" href="#"><img alt="" class="img-responsive" src="<?= $app['base_assets_url']; ?>images/calder.png"></a>
                    <input type="hidden" id="date-range-start"><input type="hidden" id="date-range-end">
                </div>

                <!--                <div class="col-md-9 col-sm-12 col-xs-12">
                                    <ul class="orderdetil" id="filter-nav">
                                        <li class="filter" data-filter="none"><a href="javascript:void(0);"><span class="active change-status"></span></a>All</li>
                                        <li class="filter" data-filter="in_progress"><a href="javascript:void(0);"><span class="deactive change-status"></span></a>Pending</li>
                                        <li class="filter" data-filter="shipped"><a href="javascript:void(0);"><span class="deactive change-status"></span></a>Dispatched</li>
                                        <li class="filter" data-filter="returned"><a href="javascript:void(0);"><span class="deactive change-status"></span></a>Disputes </li>
                                        <li class="filter" data-filter="cancelled"><a href="javascript:void(0);"><span class="deactive change-status"></span></a>Cancelled/Refunded</li>
                                        <li class="filter" data-filter="requested_cancellation"><a href="javascript:void(0);"><span class="deactive change-status"></span></a>Pending Cancellation</li>
                                        <li>
                                            <a id="datepicker" href="#"><img alt="" class="img-responsive" src="<?= $app['base_assets_url']; ?>images/calder.png"></a>
                                            <input type="hidden" id="date-range-start"><input type="hidden" id="date-range-end">
                                        </li>
                                    </ul>
                                </div>-->
            </div>
        </div>
    </div>
</div>
<div class="col-md-11 col-sm-10 col-xs-10 pull-right">
    <div class="col-md-12" id="list_orders">
        <?php include_once $this->getPart('/web/merchant/components/order_list.php'); ?>
    </div>
</div>
</div>

</div>

<?php include_once $this->getPart('/web/merchant/components/dispatch_form.php'); ?>
<?php include_once $this->getPart('/web/merchant/components/cancel_order_form.php'); ?>
<?php include_once $this->getPart('/web/merchant/components/cancel_requested_form.php'); ?>
<?php include_once $this->getPart('/web/components/message.php'); ?>
<?php include_once $this->getPart('/web/components/new_thread.php'); ?>

<script>
    var replaceOrderList = function (html, callback) {
        if ($('#list_orders .order-row').length > 0) {

            $('#list_orders').html(html).hide().fadeIn('slow');
        } else {

            $('#list_orders').html(html).hide().fadeIn('slow');
        }

        if (typeof callback == 'function') {
            callback();
        }

    }
//    $('#filter-nav').on('click', 'li.filter', function (e) {
//        e.preventDefault();
//
//        if ($(this).find('span').hasClass('deactive')) {
//
//            $('#filter-nav a > span').removeClass('active').addClass('deactive');
//            $(this).find('span').addClass('active');
//        }
//
//        var data = {status: $(this).closest('li.filter').data('filter'), order: $('#sort').val()};
//        if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {
//
//            data.filter = 'custom';
//            data.start_date = $('#date-range-start').val();
//            data.end_date = $('#date-range-end').val();
//        }
//        $.when(getMerchantOrderView(data)).then(function (html) {
//
//            replaceOrderList(html);
//
//        });
//    });

    $('#filter-by').change(function (e) {
        e.preventDefault();
        var data = {status: $(this).val(), order: $('#sort').val()};
        if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

            data.filter = 'custom';
            data.start_date = $('#date-range-start').val();
            data.end_date = $('#date-range-end').val();
        }
        $.when(getMerchantOrderView(data)).then(function (html) {

            replaceOrderList(html);

        });
    });
    $('#sort').change(function () {

        var data = {status: $('#filter-by').val(), order: $(this).val()};

        if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

            data.filter = 'custom';
            data.start_date = $('#date-range-start').val();
            data.end_date = $('#date-range-end').val();
        }
        $.when(getMerchantOrderView(data)).then(function (html) {

            replaceOrderList(html);

        });
    });

    var order_id = '';
    $('#list_orders').on('click', '.cancel-order', function () {

        $("#cancel-order-form input").val('');
        $("#cancel-order-form").data('id', $(this).data('id'));
        $("#cancel-order-form").modal('show');

    });
    $('#list_orders').on('click', '.cancel-requested', function () {

        $("#cancel-requested-form").data('id', $(this).data('id'));
        $("#cancel-requested-form").modal('show');

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

            $.when(cancelOrder(data)).then(function (data) {

                if (data.meta.success) {

                    $("#cancel-order-form").modal('hide');
                    var status = $('div[data-order-id="' + $("#cancel-order-form").data('id') + '"]').find('.cancel-box .table_cell span').text() == 'Cancel Order' ? 'Cancelled' : 'Refunded';
                    $('div[data-order-id="' + $("#cancel-order-form").data('id') + '"]').find('.status-box .paking_detal').removeClass('mark-dispatch');
                    $('div[data-order-id="' + $("#cancel-order-form").data('id') + '"]').find('.status-box .paking_detal').addClass('fulldis');
                    $('div[data-order-id="' + $("#cancel-order-form").data('id') + '"]').find('.status-box .table_cell span').text(status);
                    $('div[data-order-id="' + $("#cancel-order-form").data('id') + '"]').find('.cancel-box .paking_detal').removeClass('cancel-order');
                    $('div[data-order-id="' + $("#cancel-order-form").data('id') + '"]').find('.cancel-box .paking_detal').addClass('fulldis');
                    var greyImg = $('div[data-order-id="' + $('#cancel-order-form').data('id') + '"]').find('.cancel-box img').attr('src');
                    greyImg = greyImg.replace('cancel_requd', 'cancel_requd_grey');
                    $('div[data-order-id="' + $('#cancel-order-form').data('id') + '"]').find('.cancel-box img').attr('src', greyImg);
                    var greyImg = $('div[data-order-id="' + $('#cancel-order-form').data('id') + '"]').find('.status-box img').attr('src');
                    greyImg = greyImg.replace('make_despachbox', 'gry_makepch');
                    $('div[data-order-id="' + $('#cancel-order-form').data('id') + '"]').find('.status-box img').attr('src', greyImg);
                    toastr.success('Order cancelled successfully!');
                    hideLoader('#cancel-order-form .modal-content');
                }
            });

        }
    });
    $('#list_orders').on('click', '.mark-dispatch', function () {

        $("#dispatch-form input").val('');
        $("#dispatch-form").data('id', $(this).data('id'));
        $("#dispatch-form").modal('show');

    });
    $('body').on('keyup', 'input[name="carrier_number"]', function () {

        if ($(this).val() != '') {

            $('input[name="carrier_tracking_url"]').closest('.pro_form').show();
        } else {

            $('input[name="carrier_tracking_url"]').closest('.pro_form').hide();
        }
    });
    $("#save-order").on('submit', function (e) {
        e.preventDefault();
        var form = $(this);

        form.parsley().validate();

        if (form.parsley().isValid()) {
            var data = {};
            var formData = $('#save-order').serializeObject();

            data.body = formData;
            data.order_id = $('#dispatch-form').data('id');

            $.when(shipOrder(data)).then(function (data) {

                if (data.meta.success) {

                    $("#dispatch-form").modal('hide');
                    $('div[data-order-id="' + $("#dispatch-form").data('id') + '"]').find('.status-box .paking_detal').removeClass('mark-dispatch');
                    $('div[data-order-id="' + $("#dispatch-form").data('id') + '"]').find('.status-box .paking_detal').addClass('fulldis');
                    $('div[data-order-id="' + $("#dispatch-form").data('id') + '"]').find('.status-box .table_cell span').text('Shipped');
                    $('div[data-order-id="' + $("#dispatch-form").data('id') + '"]').find('.cancel-order .table_cell span').text('Refund Order');
                    var greyImg = $('div[data-order-id="' + $("#dispatch-form").data('id') + '"]').find('.status-box img').attr('src');
                    greyImg = greyImg.replace('make_despachbox', 'gry_makepch');
                    $('div[data-order-id="' + $("#dispatch-form").data('id') + '"]').find('.status-box img').attr('src', greyImg);
                    toastr.success('Order marked as dispatched successfully!');
                    hideLoader('#dispatch-form .modal-content');
                }
            });

        }
    });
    $('.paking_detal').on('click', function () {

        order_id = $(this).data('id');
    });

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
                $('#new-thread').data('second_user_id', $(this).data('second_user_id'));
                var dispute = ($(this).data('type') == 'dispute') ? 1 : 0;
                $('#new-thread').data('dispute', dispute);
                $('#new-thread .modal-title').text($(this).data('type'));
                $('#new-thread').modal('show');
            }

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

            $.when(getMerchantOrderView(data)).then(function (html) {

                replaceOrderList(html);

            });
        });

        $('body').on('click', '.decline', function () {

            $("#action_request").val('decline_cancellation');
            $('#decline_reason').attr('required', true);
            $(".decline_section").show();
        });

        $('body').on('click', '.cancel_order', function () {

            $("#action_request").val('accept_cancellation');
            $("#decline_reason").removeAttr('required');
            $(".decline_section").hide();
        });

        $("#cancel_requested").on('submit', function (e) {
            e.preventDefault();
            var form = $(this);

            form.parsley().validate();

            if (form.parsley().isValid()) {
                var data = {};
                var formData = $('#cancel_requested').serializeObject();

                data.body = formData;
                data.order_id = $('#cancel-requested-form').data('id');
                console.log(JSON.stringify(data));

                $.when(cancelDeclineOrder(data)).then(function (data) {

                    if (data.meta.success) {

                        $("#cancel-requested-form").modal('hide');
                        var status = $("#action_request").val() == 'accept_cancellation' ? 'Refund Order' : 'Cancellation Declined';
                        $('div[data-order-id="' + $("#cancel-requested-form").data('id') + '"]').find('.status-box .paking_detal').removeAttr('data-original-title');
                        $('div[data-order-id="' + $("#cancel-requested-form").data('id') + '"]').find('.status-box .paking_detal').removeClass('pending-cancellation');

                        $('.cancel_request_alert').remove();
                        if ($("#action_request").val() == 'accept_cancellation') {
                            var greyImg = $('div[data-order-id="' + $('#cancel-requested-form').data('id') + '"]').find('.status-box img').attr('src');
                            greyImg = greyImg.replace('make_despachbox', 'gry_makepch');

                            $('div[data-order-id="' + $('#cancel-requested-form').data('id') + '"]').find('.status-box img').attr('src', greyImg);

                            $('div[data-order-id="' + $("#cancel-requested-form").data('id') + '"]').find('.status-box .paking_detal').addClass('fulldis');
                            $('div[data-order-id="' + $("#cancel-requested-form").data('id') + '"]').find('.status-box .table_cell span').text('Cancelled');

                        } else {
                            $('div[data-order-id="' + $("#cancel-requested-form").data('id') + '"]').find('.status-box .paking_detal').addClass('mark-dispatch');
                        }

                        $('div[data-order-id="' + $("#cancel-requested-form").data('id') + '"]').find('.cancel-box .table_cell span').text(status);
                        $('div[data-order-id="' + $("#cancel-requested-form").data('id') + '"]').find('.cancel-box .paking_detal').removeClass('cancel-requested');
                        $('div[data-order-id="' + $("#cancel-requested-form").data('id') + '"]').find('.cancel-box .paking_detal').addClass('fulldis');

                        var greyImg = $('div[data-order-id="' + $('#cancel-requested-form').data('id') + '"]').find('.cancel-box img').attr('src');
                        greyImg = greyImg.replace('cancel_requd', 'cancel_requd_grey');
                        $('div[data-order-id="' + $('#cancel-requested-form').data('id') + '"]').find('.cancel-box img').attr('src', greyImg);

                        if ($("#action_request").val() == 'accept_cancellation')
                            toastr.success('Order cancelled successfully!');
                        else
                            toastr.success('Order declined successfully!');
                        hideLoader('#cancel-requested-form .modal-content');
                    }
                });
            }
        });
    });

</script>
<?php include_once $this->getPart('/web/merchant/common/footer.php'); ?>