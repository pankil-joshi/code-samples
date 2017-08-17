<?php
include_once $this->getPart('/web/merchant/common/header.php');
include_once $this->getPart('/web/merchant/common/sidebar.php');
?>
<div class="col-md-11 col-sm-10 col-xs-10 pull-right">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="order-active">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h6>Dispute Resolution</h6>
                </div>
                <div class="col-md-3 col-sm-7 col-xs-12">
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
                <div class="col-md-9 col-sm-12 col-xs-12">
                    <ul class="orderdetil" id="filter-nav">
                        <li class="filter" data-filter="none"><a href="javascript:void(0);"><span class="active change-status"></span></a>All</li>
                        <li class="filter" data-filter="open"><a href="javascript:void(0);"><span class="deactive change-status"></span></a>Open</li>
                        <li class="filter" data-filter="close"><a href="javascript:void(0);"><span class="deactive change-status"></span></a>Closed</li>
                        <li>
                            <a href="#" id="datepicker"><img alt="" class="img-responsive" src="<?= $app['base_assets_url']; ?>images/calder.png"></a>
                            <input type="hidden" id="date-range-start"><input type="hidden" id="date-range-end">
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-11 col-sm-10 col-xs-10 pull-right">
    <div class="col-md-12" id="disputes">
        <?php include_once $this->getPart('/web/merchant/components/disputes-list.php'); ?>
    </div>
    <?php include_once $this->getPart('/web/components/message.php'); ?>
    <script>

        $(document).ready(function () {

            $(document).on('click', '.message-button', function (e) {
                e.preventDefault();

                var data = {thread_id: $(this).data('id'), second_user_id: $(this).data('second_user_id')};
                refreshThread(data);

            });

            var replaceDisputeList = function (html, callback) {
                if ($('#disputes .dispute-row').length > 0) {

                    $('#disputes').html(html).hide().fadeIn('slow');
                } else {

                    $('#disputes').html(html).hide().fadeIn('slow');
                }

                if (typeof callback == 'function') {
                    callback();
                }

            }

            $('#filter-nav').on('click', 'li.filter', function (e) {
                e.preventDefault();

                if ($(this).find('span').hasClass('deactive')) {

                    $('#filter-nav a > span').removeClass('active').addClass('deactive');
                    $(this).find('span').addClass('active');
                }

                var data = {status: $(this).closest('li.filter').data('filter'), order: $('#sort').val()};
                if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

                    data.filter = 'custom';
                    data.start_date = $('#date-range-start').val();
                    data.end_date = $('#date-range-end').val();
                }
                $.when(getMerchantDisputesView(data)).then(function (html) {

                    replaceDisputeList(html);

                });
            });
            $('#sort').change(function () {

                var data = {status: $('#filter-nav .active').closest('li.filter').data('filter'), order: $(this).val()};

                if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

                    data.filter = 'custom';
                    data.start_date = $('#date-range-start').val();
                    data.end_date = $('#date-range-end').val();
                }
                $.when(getMerchantDisputesView(data)).then(function (html) {

                    replaceDisputeList(html);

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

                var data = {status: $('#filter-nav .active').closest('li.filter').data('filter'), page: $(this).data('page'), order: $('#sort').val()};

                if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

                    data.filter = 'custom';
                    data.start_date = $('#date-range-start').val();
                    data.end_date = $('#date-range-end').val();
                }

                $.when(getMerchantDisputesView(data)).then(function (html) {

                    replaceDisputeList(html);

                });
            });
        });
    </script>
    <?php include_once $this->getPart('/web/merchant/common/footer.php'); ?>