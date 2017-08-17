<?php include_once $this->getPart('/web/common/header.php'); ?>
<?php include_once $this->getPart('/web/customer/top_nav.php'); ?>
<div class="desbord_body check_outstp">
    <div class="container dashbaord">
        <div class="row">
            <div class="col-md-12 lastest_marg">
                <div class="row row-eq-height remove-row-margin">
                    <div class="col-md-5">
                        <?php // include_once $this->getPart('/web/customer/components/blog.php'); ?>
                        <div class="latest_ins">
                            <h6><a href="<?= $app['base_url']; ?>account/customer/wallet">Your Tagzie Wallet</a></h6>
                            <?php if (empty($cards)): ?>
                                <p style="width: 100%;font-size: inherit;" class="text-center"><img src="<?= $app['base_assets_url']; ?>images/no-data.png" alt="No data"> Please <a onclick="javascript:void()" href="#" data-toggle="modal" data-target="#add-card-modal" class="primary-orange">add payment source</a> to your account to allow you to make seamless purchases.</p>
                            <?php endif; ?>                              
                            <div id="cards" class="col-md-12">                              
                                <?php foreach ($expiring_cards as $card): ?>
                                    <div class="col-sm-6 col-xs-6 col-md-12 visa_latest card-row">
                                        <div class="col-xs-12 col-md-2" style="padding:0"><img src="<?= $app['base_assets_url']; ?>images/<?= $card['brand']; ?>.png" class="ncpss" alt=""></div>
                                        <div class="col-md-10"><p> <?= $card['brand']; ?> <br>
                                                Card ending   <?= $card['last4']; ?>  <br>
                                                Expired  <?= $card['exp_month']; ?> /  <?= $card['exp_year']; ?>  </p></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="yor_active">
                            <div class="row">
                                <div class="col-md-3">
                                    <h6><a href="<?= $app['base_url']; ?>account/customer/orders" style="color: #FF6C00;">Your Activity</a></h6>
                                    <!--                                                        <ul id="filter-nav">
                                                                                                <li data-filter="none"><a href="#"><span class="active"></span></a>All</li>    
                                                                                                <li data-filter="completed"><a href="#"><span class="deactive"></span></a>Completed</li>
                                                                                                <li data-filter="in_progress"><a href="#"><span class="deactive"></span></a>Processing</li> 
                                                                                                <li data-filter="tagged"><a href="#"><span class="deactive"></span></a>Tagged</li>
                                                                                                <li><a href="#"><img src="<?= $app['base_assets_url']; ?>images/calder.png" class="img-responsive" alt=""></a></li>
                                                                                            </ul>-->
                                </div>
                                
                                <div class="col-md-8">
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
                                </div>
                                
                                <div class="col-md-1">
<!--                                    <a href="#" id="datepicker" data-date="<?= date('m/d/Y'); ?>" class="caleneder-srh" ><img src="<?= $app['base_assets_url']; ?>images/calder.png" class="img-responsive" alt=""></a>
                                    <input type="hidden" id="date-range-start"><input type="hidden" id="date-range-end">-->
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" id="orders" style="padding:0 ">
                            <?php include_once $this->getPart('/web/customer/components/order_list_dashboard.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once $this->getPart('/web/components/add-card-form.php'); ?>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script>

                                $(document).ready(function () {

                                    $("#add-card-form").on('submit', function (e) {
                                        e.preventDefault();
                                        var form = $(this);
                                        form.parsley().validate();
                                        if (form.parsley().isValid()) {
                                            requestToken();
                                        }
                                    });
                                    $('#add-card-modal .close').click(function () {
                                        $('#add-card-modal').find('input, select').val('');
                                        $('#add-card-modal').modal('hide');
                                    });
                                });
                                Stripe.setPublishableKey("<?= $stripe_publishable_key; ?>");
                                function stripeResponseHandler(status, response) {

                                    if (status == 200) {

                                        var formData = new FormData();
                                        formData.append("stripeToken", response.id);
                                        var xmlHttp = new XMLHttpRequest();
                                        xmlHttp.onreadystatechange = function () {

                                            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                                                var response = JSON.parse(xmlHttp.responseText);
                                                if (response.meta.success) {

                                                    hideLoader('#card-box');
                                                    window.location.href = '<?= $app['base_url']; ?>account/customer/wallet';
                                                } else {

                                                    $('#addCard').prop('disabled', false);
                                                }

                                            }

                                        }

                                        xmlHttp.open("post", "<?= $app['base_api_url']; ?>payment/stripe/addCard");
                                        xmlHttp.setRequestHeader("token", '<?= $_COOKIE['token']; ?>');
                                        xmlHttp.send(formData);
                                    } else {
                                        hideLoader('#card-box');
                                        if (response.error.message) {

                                            toastr.error(response.error.message);
                                        } else {

                                            toastr.error('Some unexpected error occured!');
                                        }

                                        $('#addCard').prop('disabled', false);
                                    }

                                }

                                function requestToken() {

                                    $('#addCard').prop('disabled', true);
                                    if (!Stripe.card.validateCardNumber(document.getElementById("card-number").value)) {

                                        toastr.warning('Card number is not valid!');
                                        $('#addCard').prop('disabled', false);
                                    } else if (!Stripe.card.validateExpiry(document.getElementById("card-expiry-month").value, document.getElementById("card-expiry-year").value)) {

                                        toastr.warning('Expiry date is not valid!');
                                        $('#addCard').prop('disabled', false);
                                    } else if (!Stripe.card.validateCVC(document.getElementById("cvc").value)) {

                                        toastr.warning('CVC is not valid!');
                                        $('#addCard').prop('disabled', false);
                                    } else {
                                        showLoader('#card-box');
                                        Stripe.card.createToken({
                                            number: document.getElementById("card-number").value,
                                            cvc: document.getElementById("cvc").value,
                                            exp_month: document.getElementById("card-expiry-month").value,
                                            exp_year: document.getElementById("card-expiry-year").value,
                                            name: document.getElementById("card-name").value,
                                        }, stripeResponseHandler);
                                    }
                                }


                                var rateOrder = function (value, text, event) {

                                    var orderid = this.$elem.data('orderid');
                                    var data = {};
                                    data.order_id = orderid;
                                    data.body = {rating: value};
                                    $.when(postOrderRating(data)).then(function (data) {

                                        if (data.meta.success) {
                                            var data = {status: $('#filter-by').val(), page: $('#order-pagination a.active').data('page')};
                                            $.when(getOrderViewDashboard(data)).then(function (html) {

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

                                $(document).ready(function () {

                                    $('body').on('click', '.pagination-container > li > a', function (e) {
                                        e.preventDefault();
                                        var data = {status: $('#filter-by').val(), page: $(this).data('page')};
                                        $.when(getOrderViewDashboard(data)).then(function (html) {

                                            replaceOrderList(html, initializeBarRating);
                                        });
                                    });
//                                    $('#filter-nav').on('click', 'li', function (e) {
//                                        e.preventDefault();
//
//                                        if ($(this).find('span').hasClass('deactive')) {
//
//                                            $('#filter-nav a > span').removeClass('active').addClass('deactive');
//                                            $(this).find('span').addClass('active');
//                                        }
//
//                                        var data = {status: $(this).closest('li').data('filter')};
//
//                                        $.when(getOrderViewDashboard(data)).then(function (html) {
//
//                                            replaceOrderList(html, initializeBarRating);
//
//                                        });
//                                    });

                                    $('#filter-by').change(function (e) {
                                        e.preventDefault();
                                        var data = {status: $(this).val()};
                                        $.when(getOrderViewDashboard(data)).then(function (html) {

                                            replaceOrderList(html, initializeBarRating);
                                        });
                                    });
                                    initializeBarRating();
                                });



</script>
<?php include_once $this->getPart('/web/common/footer.php'); ?>