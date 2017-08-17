<?php include_once $this->getPart('/web/common/header.php'); ?> 
<?php include_once $this->getPart('/web/checkout/sub_header.php'); ?>


<div class="check_outstp">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-5">
                    <div class="chack_demain edit_ordr">
                        <div class="row">    
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <img src="<?= $media['image_thumbnail']; ?>" class="img-responsive" alt="">
                            </div>

                            <div class="col-md-9 col-sm-9 col-xs-9">
                                <h6><?= $media['title']; ?></h6>
                                <p>from <span>@<?= $media['instagram_username']; ?></span></p>
                                <h4><a href="<?= $app['base_url']; ?>checkout/?media_id=<?= $media['id']; ?>" >EDIT ORDER</a></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 information_detail">
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <h6>Delivery Address</h6>
                        <h4><?= $delivery_address['first_name']; ?> <?= $delivery_address['last_name']; ?><br>
                            <?= $delivery_address['line_1']; ?>, <?= $delivery_address['city']; ?>...</h4>
                    </div>

                    <div class="col-md-4 col-sm-1 col-xs-12">
                        <div class="right_bar1">    
                            <h6>Order Total:</h6>
                            <h4><span class="currency_code"><?= $media['base_currency_code']; ?></span><?= $_POST['total']; ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12"><hr></div>
            </div>
            <div class="col-md-12">


                <div class="col-md-7 subtotal">
                    <div class="col-md-12">
                        <div class="iner_wlat">    
                            <img src="<?= $app['base_assets_url']; ?>images/wallet_orange.png" class="wlt_img" alt="">
                            <h1><strong>My Wallet</strong></h1> 
                            <p>Select payment source:</p>
                            <div class="">    
                                <div class=" date-order">

                                    <select class="selectpicker" id="card">
                                        <?php if (!empty($cards)): foreach ($cards as $card): ?>
                                                <option value="<?= $card['stripe_card_id']; ?>" <?= ($card['is_default'] == 1) ? 'selected' : ''; ?>>“<?= $card['brand']; ?>”  |  **** **** **** <?= $card['last4']; ?></option>
                                                <?php
                                            endforeach;
                                        else:
                                            ?>
                                            <option disabled="disabled">No cards exist!</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <p>To confirm your order and make payment, please click 
                                below to complete this purchase.</p>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <a href="#" id="processOrder"><div class="sure_pyment sure_pyment1">   
                                <div class="col-md-2 col-sm-2 col-xs-4"><img src="<?= $app['base_assets_url']; ?>images/creadit_card.png" class="img-responsive" alt=""> </div>
                                <div class="col-md-10 col-sm-10 col-xs-8"><div class="row"><h5>CONFIRM PURCHASE</h5></div> </div>
                            </div></a> 
                    </div>
                </div> 


                <div class="col-md-5" id="card-box">
                    <div class="discont new_setd">
                        <form data-parsley-validate id="add_card" autocomplete="nofill">
                            <h6>Add New Card</h6>
                            <input type="text" placeholder="name on card" class="input_tc_pro" id="card-name" required data-parsley-required-message="Please enter name on card">
                            <input type="text" placeholder="card number" class="input_tc_pro" id="card-number" required data-parsley-required-message="Please enter card number">
                            <div class="col-md-4">
                                <div class="row">
                                    <!--<input type="text" placeholder="exp mm" class="input_tc_pro" id="card-expiry-month">-->
                                    <select id="card-expiry-month" class="input_tc_pro" required data-parsley-required-message="Please select expiry month">
                                        <option value="">Choose...</option>
                                        <?php foreach (range(01, 12) as $value): ?>
                                            <option value="<?= $value; ?>"><?= $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <!--<input type="text" placeholder="exp yy" class="input_tc_pro" id="card-expiry-year" required>-->
                                    <select id="card-expiry-year" class="input_tc_pro" required data-parsley-required-message="Please select expiry year">
                                        <option value="">Choose...</option>
                                        <?php foreach (range(date('Y'), date('Y', strtotime('+ 15 years'))) as $value): ?>
                                            <option value="<?= $value; ?>"><?= $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>                        
                            <div class="col-md-4" style="padding-right: 0">
                                <input id="cvc" type="password" autocomplete="new-password" placeholder="cvc" class="input_tc_pro" required data-parsley-required-message="Please enter cvc code">
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <!--                                <div class="check_bx">
                                                                        <label class="control control--checkbox">
                                                                            <input type="checkbox"/>
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                        <span>Save card</span>
                                                                    </div>-->
                                </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="row">
                                    <button type="submit" class="next_ch addCard" href="#" id="addCard">Next</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


            </div>

        </div>
    </div>  
</div>  

<?php include_once $this->getPart('/web/components/bespoke_alert.php'); ?>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script>

    $(document).ready(function () {

        var is_bespoke_flag = "<?= !empty($media['is_refundable_disabled']) ? 1 : 0; ?>";

        $("#bespoke_agreement").on('submit', function (e) {
            e.preventDefault();
            var form = $(this);

            form.parsley().validate();

            if (form.parsley().isValid()) {
                is_bespoke_flag = 0;
                $('#bespoke_modal').modal('hide');
                $('#processOrder').trigger('click');
            }
        });

        $("#add_card").on('submit', function (e) {
            e.preventDefault();
            var form = $(this);

            form.parsley().validate();

            if (form.parsley().isValid()) {
                requestToken();

            }
        });
        replaceCurrencyCode();
//            $('.addCard').click(function (e) {
//                e.preventDefault();
//                requestToken();
//
//            });


        $('#processOrder').click(function (e) {
            e.preventDefault();

            if (is_bespoke_flag == 1) {
                $('#bespoke_modal').modal('show');
                return false;
            }

            showLoader('body');
            var order = {};

            order.postage_option_id = <?= $_POST['postage_option']; ?>;
            order.delivery_address_id = <?= $_POST['delivery_address']; ?>;
<?php
foreach ($_POST['variant_ids'] as $index => $id) {
    $item['media_id'] = $_POST['media_id'];
    $item['variant_id'] = $id;
    $item['quantity'] = $_POST['quantities'][$index];
    $items[] = $item;
}
?>
            order.items = <?= json_encode($items); ?>;
            order.payment_option = {
                id: 'Stripe',
                data: {card_id: $('#card').val()}
            };
            order.comment_id = '<?= !empty($_SESSION['cart']['comment_id']) ? $_SESSION['cart']['comment_id'] : ''; ?>';


            var data = {};
            data.body = order;

            $.when(createOrder(data)).then(function (data) {
                if (data.meta.success) {
                    window.location = '<?= $app['base_url'] . 'checkout/success/' ?>' + data.data.order.id;
                } else {

                    hideLoader('body');
                }

            });
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
                        $.when(getUserCardList()).then(function (data) {
                            var cardList = '';
                            var cards = data.data.cards;
                            $.each(cards, function (index, item) {
                                cardList += '<option value="' + item.stripe_card_id + '">“' + item.brand + '”  |  **** **** **** ' + item.last4 + '</option>';
                            });

                            $('#card').html(cardList).selectpicker('refresh');
                            hideLoader('#card-box');
                            toastr.success('Card added successfully!');
                        });

                        $('#add_card input').val('');
                        $('#add_card select').val('');

                    } else {

                        hideLoader('#card-box');
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

            console.log('sentAfter');


        }

    }
</script>
<?php include_once $this->getPart('/web/common/footer.php'); ?>            