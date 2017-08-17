<?php
include_once $this->getPart('/web/merchant/common/header.php');
include_once $this->getPart('/web/merchant/common/sidebar.php');
?>
<div class="col-md-11 col-sm-10 col-xs-10 pull-right">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="order-active tagzir_invoc">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h6>Tagzie Invoices</h6>
                </div>
                <div class="col-md-3 col-sm-5 col-xs-12">
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
                <div class="col-md-9 col-sm-6 col-xs-12">
                    <ul class="orderdetil" id="filter-nav">
                        <li class="filter" data-filter="none"><a href="javascript:void(0);"><span class="active change-status"></span></a>All</li>
                        <li class="filter" data-filter="subscription"><a href="javascript:void(0);"><span class="deactive change-status"></span></a>Subscription</li>
                        <li class="filter" data-filter="settlement"><a href="javascript:void(0);"><span class="deactive change-status"></span></a>Settlement</li>
                        <li>
                            <a href="#" id="datepicker"><img alt="" class="img-responsive" src="<?= $app['base_assets_url']; ?>images/calder.png"></a>
                            <input type="hidden" id="date-range-start"><input type="hidden" id="date-range-end">
                        </li>
                    </ul>
                </div>

            </div>
        </div>
        <div class="col-md-12 col-sm-12 blow_cont">
            <p>Below you will find settlement invoices for all payments made to Tagzie including transaction fees and subscription renewals.</p>
        </div>
    </div>
</div>
<div class="col-md-11 col-sm-10 col-xs-10 pull-right">
    <div class="col-md-12">

        <table class="table table-hover table-responsive cutomer_form_detail tagzie_table">
            <thead>
                <tr>
                    <th></th>
                    <th>Reference</th>
                    <th>Date</th>
                    <th>Value</th>
                    <th>#</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody id="invoices">
                <?php include_once $this->getPart('/web/merchant/components/invoices-list.php'); ?>
            </tbody>
        </table>

    </div>
</div>
<script>

    $(document).ready(function () {

        var replaceInvoicesList = function (html, callback) {
            if ($('#invoices .invoice-row').length > 0) {

                $('#invoices').html(html).hide().fadeIn('slow');
            } else {

                $('#invoices').html(html).hide().fadeIn('slow');
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

            var data = {type: $(this).closest('li.filter').data('filter'), order: $('#sort').val()};
            if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

                data.filter = 'custom';
                data.start_date = $('#date-range-start').val();
                data.end_date = $('#date-range-end').val();
            }
            $.when(getMerchantInvoicesView(data)).then(function (html) {

                replaceInvoicesList(html);

            });
        });
        $('#sort').change(function () {

            var data = {type: $('#filter-nav .active').closest('li.filter').data('filter'), order: $(this).val()};

            if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

                data.filter = 'custom';
                data.start_date = $('#date-range-start').val();
                data.end_date = $('#date-range-end').val();
            }
            $.when(getMerchantInvoicesView(data)).then(function (html) {

                replaceInvoicesList(html);

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

            var data = {type: $('#filter-nav .active').closest('li.filter').data('filter'), page: $(this).data('page'), order: $('#sort').val()};

            if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

                data.filter = 'custom';
                data.start_date = $('#date-range-start').val();
                data.end_date = $('#date-range-end').val();
            }

            $.when(getMerchantInvoicesView(data)).then(function (html) {

                replaceInvoicesList(html);

            });
        });
    });
</script>
<?php include_once $this->getPart('/web/merchant/common/footer.php'); ?>