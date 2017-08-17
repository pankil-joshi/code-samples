<?php
include_once $this->getPart('/web/merchant/common/header.php');
include_once $this->getPart('/web/merchant/common/sidebar.php');
?>
<style>
    .update-product {

    }

</style>
<div class="col-md-11 col-sm-10 col-xs-10 pull-right">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="order-active">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h6>Product Manager</h6>
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
                        <li class="filter" data-filter="published"><a href="javascript:void(0);"><span class="deactive change-status"></span></a>Published</li>
                        <li class="filter" data-filter="disabled"><a href="javascript:void(0);"><span class="deactive change-status"></span></a>Disabled</li>
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
    <div class="col-md-12" id="products">
        <?php include_once $this->getPart('/web/merchant/components/product-list.php'); ?>
    </div>
    <script>
        function updateProductFunction(body, id) {
            var data = {};
            data.body = body;
            data.product_id = id;

            $.when(updateProduct(data)).then(function (data) {

                if (data.meta.success) {

                    toastr.success('Product updated successfully.')

                } else {

                    toastr.error(data.data.errors.message);
                }

            });
        }

        $('body').on('click', '.is-available', function () {

            var body = {};

            if ($(this).parent('.switch').find('input').is(':checked')) {
                body.is_available = 0;
            } else {
                body.is_available = 1;
            }

            updateProductFunction(body, $(this).closest('.product-row').data('id'));
        });
        $('body').on('click', '.update-product', function () {
            var clickedRow = $(this);
            var body = {};
            body.variants = [];
            body.title = $(this).closest('.product-row').find('input[name="title"]').val();
            $(this).closest('.product-row').find('.variant-price').each(function (index) {

                var variant = {};

                variant.id = $(this).data('id');
                variant.price = $(this).val();
                variant.quantity = clickedRow.closest('.product-row').find('.variant-quantity').eq(index).val();
                variant.min_stock_level = clickedRow.closest('.product-row').find('.variant-stock-alert').eq(index).val();
                body.variants.push(variant);

            });


            updateProductFunction(body, $(this).closest('.product-row').data('id'));
        });
        var replaceProductList = function (html, callback) {
            if ($('#products .product-row').length > 0) {

                $('#products').html(html).hide().fadeIn('slow');
            } else {

                $('#products').html(html).hide().fadeIn('slow');
            }

            if (typeof callback == 'function') {
                callback();
            }

        }
        $(document).ready(function () {

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
                $.when(getMerchantProductsView(data)).then(function (html) {

                    replaceProductList(html);

                });
            });
            $('#sort').change(function () {

                var data = {status: $('#filter-nav .active').closest('li.filter').data('filter'), order: $(this).val()};

                if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

                    data.filter = 'custom';
                    data.start_date = $('#date-range-start').val();
                    data.end_date = $('#date-range-end').val();
                }
                $.when(getMerchantProductsView(data)).then(function (html) {

                    replaceProductList(html);

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

                $.when(getMerchantProductsView(data)).then(function (html) {

                    replaceProductList(html);

                });
            });
        });
    </script>
    <?php include_once $this->getPart('/web/merchant/common/footer.php'); ?>