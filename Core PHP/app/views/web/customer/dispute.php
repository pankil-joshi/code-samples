<?php include_once $this->getPart('/web/common/header.php'); ?>
<?php include_once $this->getPart('/web/customer/top_nav.php'); ?>
<div class="desbord_body check_outstp">
    <div class="container">
        <div class="row">
            <div class="col-md-12 lastest_marg">
                <div class="col-md-12">
                    <div class="yor_active" style="border:0">
                        <div class="order-active">
                            <div class="col-md-3 col-sm-5">
                                <h6>Disputes</h6>
                            </div>
                            <div class="col-md-3 col-sm-7">
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
                            <div class="col-md-6 col-sm-12">
                                <ul class="orderdetil" id="filter-nav">
                                    <li data-filter="none" class="filter"><a href="#"><span class="active"></span></a>All</li>    
                                    <li data-filter="open" class="filter"><a href="#"><span class="deactive"></span></a>Open</li>  
                                    <li data-filter="close" class="filter"><a href="#"><span class="deactive"></span></a>Closed</li>
                                    <li >                                        <a href="#" id="datepicker" data-date="<?= date('m/d/Y'); ?>"  style="display: inline-block;"><img src="<?= $app['base_assets_url']; ?>images/calder.png" class="img-responsive" alt=""></a>
                                        <input type="hidden" id="date-range-start"><input type="hidden" id="date-range-end"></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-12 " style="padding:0 " id="disputes">

                            <?php include_once $this->getPart('/web/customer/components/dispute_list.php'); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once $this->getPart('/web/components/message.php'); ?>
<script>
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
    $(document).ready(function () {
        $('#datepicker').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY'
            },
            startDate: '<?= date('d/m/Y'); ?>'
        }, function (start, end, label) {
            $('#date-range-start').val(start.format('YYYY-MM-DD'));
            $('#date-range-end').val(end.format('YYYY-MM-DD'));

            var data = {status: $('#filter-nav .active').closest('li.filter').data('filter'), order: $('#sort').val()};
            if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

                data.filter = 'custom';
                data.start_date = $('#date-range-start').val();
                data.end_date = $('#date-range-end').val();
            }
            $.when(getDisputeView(data)).then(function (html) {

                replaceDisputeList(html);
            });
        });
        $(document).on('click', '.message-button', function (e) {
            e.preventDefault();

            var data = {thread_id: $(this).data('id'), second_user_id: $(this).data('second_user_id')};
            refreshThread(data);

        });
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
            $.when(getDisputeView(data)).then(function (html) {

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
            $.when(getDisputeView(data)).then(function (html) {

                replaceDisputeList(html);

            });
        });
    });

</script>
<?php include_once $this->getPart('/web/common/footer.php'); ?>