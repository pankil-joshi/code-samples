<?php
include_once $this->getPart('/web/merchant/common/header.php');
include_once $this->getPart('/web/merchant/common/sidebar.php');
?>
<div class="col-md-11 col-sm-10 col-xs-10 pull-right">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="order-active customr_order">
                <div class="col-md-9 col-sm-12 col-xs-12">
                    <h6>Orders by Customer</h6>
                </div>
                <div class="col-md-3 col-sm-7 col-xs-12 pull-right">
                    <div class="lable-date">

                        <label>Sort:</label>
                        <div class="select-style date-order">
                            <select id="sort">
                                <option value="asc">A to Z</option>
                                <option value="desc">Z to A</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="col-md-11 col-sm-10 col-xs-10 pull-right customer-orders">
    <div class="col-md-12" id="list_orders">
        <?php include_once $this->getPart('/web/merchant/components/customer-order-list.php'); ?>
    </div>
</div>
<?php include_once $this->getPart('/web/merchant/components/dispatch_form.php'); ?>
<?php include_once $this->getPart('/web/merchant/components/cancel_order_form.php'); ?>
<?php include_once $this->getPart('/web/merchant/components/cancel_requested_form.php'); ?>
<?php include_once $this->getPart('/web/components/message.php'); ?>
<?php include_once $this->getPart('/web/components/new_thread.php'); ?>
<script>
    $('#list_orders').on('click', '.get-orders', function () {
        var user_id = $(this).attr('user-id');
        if ($(this).attr('src') == '<?= $app['base_assets_url']; ?>images/adding_cutomer.png') {
            $(this).attr('src', '<?= $app['base_assets_url']; ?>images/minimize.png');
        } else {

            $(this).attr('src', '<?= $app['base_assets_url']; ?>images/adding_cutomer.png');
        }
        $(this).parents('tbody').find('.new_orders' + user_id).each(function () {
            $(this).toggle();
        });

    });
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

    var order_id = '';

    $(document).ready(function () {

        $('#sort').change(function () {

            var data = {status: $('#filter-nav .active').closest('li.filter').data('filter'), order: $(this).val()};

            if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

                data.filter = 'custom';
                data.start_date = $('#date-range-start').val();
                data.end_date = $('#date-range-end').val();
            }
            $.when(getMerchantCustomerOrderView(data)).then(function (html) {

                replaceOrderList(html);

            });
        });

        $('#list_orders').on('click', '.cancel-order', function () {

            $("#cancel-order-form input").val('');
            $("#cancel-order-form").data('id', $(this).data('id'));
            $("#cancel-order-form").modal('show');

        });
        $('#list_orders').on('click', '.cancel-requested', function () {

            $("#cancel-requested-form").data('id', $(this).data('id'));
            $("#cancel-requested-form").modal('show');

        });
        $('body').on('keyup', 'input[name="carrier_number"]', function () {

            if ($(this).val() != '') {

                $('input[name="carrier_tracking_url"]').closest('.pro_form').show();
            } else {

                $('input[name="carrier_tracking_url"]').closest('.pro_form').hide();
            }
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
                        var status = $('div[data-order-id="' + $("#cancel-order-form").data('id') + '"]').find('.cancel-box .table_cell span').text() == 'Cancel' ? 'Cancelled' : 'Refunded';
                        $('*[data-order-id="' + $("#cancel-order-form").data('id') + '"]').find('.status-box').removeClass('mark-dispatch');
                        $('*[data-order-id="' + $("#cancel-order-form").data('id') + '"]').find('.status-box').addClass('fulldis');
                        $('*[data-order-id="' + $("#cancel-order-form").data('id') + '"]').find('.status-box .table_cell span').text(status);
                        $('*[data-order-id="' + $("#cancel-order-form").data('id') + '"]').find('.cancel-box  .paking_detal').removeClass('cancel-order');
                        $('*[data-order-id="' + $("#cancel-order-form").data('id') + '"]').find('.cancel-box  .paking_detal').addClass('fulldis');
                        var greyImg = $('*[data-order-id="' + $('#cancel-order-form').data('id') + '"]').find('.cancel-box img').attr('src');
                        greyImg = greyImg.replace('cancel_requd', 'cancel_requd_grey');
                        $('*[data-order-id="' + $('#cancel-order-form').data('id') + '"]').find('.cancel-box img').attr('src', greyImg);
                        var greyImg = $('*[data-order-id="' + $('#cancel-order-form').data('id') + '"]').find('.status-box img').attr('src');
                        greyImg = greyImg.replace('make_despachbox', 'gry_makepch');
                        $('*[data-order-id="' + $('#cancel-order-form').data('id') + '"]').find('.status-box img').attr('src', greyImg);
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
                        $('*[data-order-id="' + $("#dispatch-form").data('id') + '"]').find('.status-box').removeClass('mark-dispatch');
                        $('*[data-order-id="' + $("#dispatch-form").data('id') + '"]').find('.status-box').addClass('fulldis');
                        $('*[data-order-id="' + $("#dispatch-form").data('id') + '"]').find('.status-box .table_cell span').text('Shipped');
                        $('*[data-order-id="' + $("#dispatch-form").data('id') + '"]').find('.cancel-order .table_cell span').text('Refund');
                        var greyImg = $('*[data-order-id="' + $("#dispatch-form").data('id') + '"]').find('.status-box img').attr('src');
                        greyImg = greyImg.replace('make_despachbox', 'gry_makepch');
                        $('*[data-order-id="' + $("#dispatch-form").data('id') + '"]').find('.status-box img').attr('src', greyImg);
                        toastr.success('Order marked as dispatched successfully!');
                        hideLoader('#dispatch-form .modal-content');
                    }

                });

            }
        });
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

            var data = {status: $('#filter-nav .active').closest('li.filter').data('filter'), page: $(this).data('page'), order: $('#sort').val()};

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
                        $('tr[data-order-id="' + $("#cancel-requested-form").data('id') + '"]').find('.status-box .paking_detal').removeAttr('data-original-title');
                        $('tr[data-order-id="' + $("#cancel-requested-form").data('id') + '"]').find('.status-box .paking_detal').removeClass('pending-cancellation');

                        $('.cancel_request_alert').remove();
                        if ($("#action_request").val() == 'accept_cancellation') {
                            var greyImg = $('tr[data-order-id="' + $('#cancel-requested-form').data('id') + '"]').find('.status-box img').attr('src');
                            greyImg = greyImg.replace('make_despachbox', 'gry_makepch');

                            $('tr[data-order-id="' + $('#cancel-requested-form').data('id') + '"]').find('.status-box img').attr('src', greyImg);

                            $('tr[data-order-id="' + $("#cancel-requested-form").data('id') + '"]').find('.status-box .paking_detal').addClass('fulldis');
                            $('tr[data-order-id="' + $("#cancel-requested-form").data('id') + '"]').find('.status-box .table_cell span').text('Cancelled');

                        } else {
                            $('tr[data-order-id="' + $("#cancel-requested-form").data('id') + '"]').find('.status-box .paking_detal').addClass('mark-dispatch');
                        }

                        $('tr[data-order-id="' + $("#cancel-requested-form").data('id') + '"]').find('.cancel-box .table_cell span').text(status);
                        $('tr[data-order-id="' + $("#cancel-requested-form").data('id') + '"]').find('.cancel-box .paking_detal').removeClass('cancel-requested');
                        $('tr[data-order-id="' + $("#cancel-requested-form").data('id') + '"]').find('.cancel-box .paking_detal').addClass('fulldis');

                        var greyImg = $('tr[data-order-id="' + $('#cancel-requested-form').data('id') + '"]').find('.cancel-box img').attr('src');
                        greyImg = greyImg.replace('cancel_requd', 'cancel_requd_grey');
                        $('tr[data-order-id="' + $('#cancel-requested-form').data('id') + '"]').find('.cancel-box img').attr('src', greyImg);

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