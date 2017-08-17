<?php include_once $this->getPart('/web/common/header.php'); ?>
<!-- Modal -->
<div class="modal fade width_dialog" id="merchant-success-modal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <!--<div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Modal Header</h4>
            </div>-->
            <div class="modal-body">
                <p>Merchant account created successfully.</p>
            </div>
            <div class="modal-footer">
                <a href="intent://dashboard.html#Intent;scheme=ipaid;package=com.prologic.ip;end" type="button" class="btn btn-default orange" >Back to App</a>
                <a href="<?= $app['base_url']; ?>account/merchant" type="button" class="btn btn-default grey" >Open merchant dashboard</a>
            </div>
        </div>

    </div>
</div>

<div class="secure_pin">
    <h6><img src="<?= $app['base_assets_url']; ?>images/super_lock.png" class="img-resaponsive" alt="">Tagzie is in ULTRA SECURE MODE</h6>  
</div>
<div id="merchantFields">

    <div class="container customer-registration-fields">
        <?php if (!empty($message)): ?>
            <div class="message col-md-12 col-sm-12 col-xs-12">
                <div class="<?php if ($message['type'] == 'success'): ?>success<?php elseif ($message['type'] == 'error'): ?>error<?php endif; ?>">
                    <?php if ($message['type'] == 'success'): ?>
                        <?= $message['text']; ?>
                    <?php elseif ($message['type'] == 'error'): ?>
                        <ul>
                            <?php foreach ($message['errors'] as $errors): ?>
                                <?php foreach ($errors as $error): ?>
                                    <li><?= $error; ?></li>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>        
        <form method="POST" id="customer-registration-form" data-parsley-validate>
            <div class="row tab"  id="business-details" >
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="showing_form">
                        <div class="col-md-4">
                            <label>Title:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <select class="form-control" name="title" style="padding: 9px" required data-parsley-required-message="Please choose title">
                                    <option value="">Choose title</option>
                                    <option value="Mr">Mr.</option>
                                    <option value="Miss">Miss</option>
                                    <option value="Mrs">Mrs.</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="showing_form">
                        <div class="col-md-4">
                            <label>First Name:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input type="text" class="form-control" name="first_name" required data-parsley-required-message="Please enter first name" value="<?= $user['first_name']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="showing_form">
                        <div class="col-md-4">
                            <label>Last Name:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input type="text" class="form-control" value="" name="last_name" required data-parsley-required-message="Please enter last name">
                            </div>
                        </div>
                    </div>
<!--                    <div class="showing_form">
                        <div class="col-md-4">
                            <label>Date of Birth:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input type="text" class="form-control" value="" name="date_of_birth" required data-parsley-required-message="Please enter date of birth">
                            </div>
                        </div>
                    </div>                    -->
                    <div class="showing_form">
                        <div class="col-md-4">
                            <label>Age:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input type="number" class="form-control" name="age" data-parsley-required-message="Please enter age" max="100" min="13">
                            </div>
                        </div>
                    </div>  
                    <div class="showing_form">
                        <div class="col-md-4">
                            <label>Email Address:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input type="email" class="form-control" value="" name="email" required data-parsley-required-message="Please enter email" data-parsley-type-message="Email must be valid">
                            </div>
                        </div>
                    </div>
                    <div class="showing_form">
                        <div class="col-md-4">
                            <label>Country:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <select class="form-control" name="country" style="padding: 9px" required data-parsley-required-message="Please select a country">
                                    <option value="">Select country</option>
                                    <?php foreach ($countries as $code => $country): ?>
                                        <option data-dial-code="<?= $country['dial_code']; ?>" value="<?= $code; ?>" ><?= $country['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>                                
                            </div>
                        </div>
                    </div>
                    <div class="showing_form">
                        <div class="col-md-4">
                            <label>Mobile Number:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-3 col-xs-4" style="padding:0">
                                    <input class="form-control smail_sinp" name="mobile_number_prefix" required data-parsley-required-message="Please select a mobile number prefix" value="Dial code" readonly>

<!--                                    <select class="form-control" name="mobile_number_prefix" style="padding: 9px" required data-parsley-required-message="Please select a mobile number prefix">
    <option value="">Prefix</option>
                                    <?php
                                    $dial_codes = array();
                                    foreach ($countries as $country) {

                                        if (!in_array($country['dial_code'], $dial_codes))
                                            $dial_codes[] = $country['dial_code'];
                                    }

                                    asort($dial_codes);
                                    ?>
                                    <?php foreach ($dial_codes as $dial_code): ?>
                    <option value="<?= $dial_code; ?>"><?= $dial_code; ?></option>
                                    <?php endforeach; ?>
</select>-->
                                </div>
                                <div style="padding:0" class="col-md-9 col-xs-8">
                                    <input type="number" class="form-control lefr_borfd" data-parsley-type="digits" name="mobile_number" required data-parsley-required-message="Please enter mobile number">
                                </div>

                            </div>
                        </div>
                    </div>
                    <div>                   
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="check_bx">
                                <label class="control control--checkbox">
    <input type="checkbox" id="test1" name="terms_accepted" value="1" >
     <div class="control__indicator"></div>
                            </label>
                                <span>I accept the <a href="<?= $app['base_url']; ?>legal/terms#buyer_terms"><span class="orange underline">Term & Conditions</span></a></span>
                            </div>
                        </div>            
                    </div>
                    <div class="showing_form">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="proceed_btn">
                                    <input type="hidden" name="submit-form" value="1">
                                    <button data-tab="business-address" type="submit" value="true"> Proceed  </button>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>          
            </div>
        </form>
    </div>
    <?php include_once $this->getPart('/web/components/add-card-form.php'); ?>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <!--<script type="text/javascript" src="<?= $app['base_assets_url']; ?>js/moment-timezone-with-data-2010-2020.min.js"></script>-->
    <script>

        $(document).ready(function () {

//            $('input[name="timezone"]').val(moment.tz.guess());
            $('select[name="country"]').change(function () {

                $('input[name="mobile_number_prefix"]').val($(this).find(':selected').data('dial-code'));
            });
            for (var i = 0; i <= 12; i++) {

                if (i < 10) {

                    i = "0" + i;

                }
                if (i == 0) {

                    $("#card-expiry-month").append('<option value="">Choose...</option>');

                } else {

                    $("#card-expiry-month").append('<option value="' + i + '">' + i + '</option>');
                }

            }
            var d = new Date();
            var n = d.getFullYear();
            for (var i = n - 1; i <= d.getFullYear() + 14; i++) {

                if (i == n - 1) {

                    $("#card-expiry-year").append('<option value="">Choose...</option>');

                } else {

                    $("#card-expiry-year").append('<option value="' + i + '">' + i + '</option>');
                }
            }
            $("#add-card-form").on('submit', function (e) {
                e.preventDefault();
                var form = $(this);

                form.parsley().validate();

                if (form.parsley().isValid()) {
                    requestToken();

                }
            });
            $('#add-card-modal .close').click(function () {

                $('#add-card-modal').modal('hide');
                $('#complete-signup-modal').modal('show');
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
                                        cardList += '<option value="' + item.id + '">“' + item.brand + '”  |  **** **** **** ' + item.last4 + '</option>';
                                    });

                                    $('#selectedCard').html(cardList);
                                    hideLoader('#card-box');
                                    $('#add-card-modal').modal('hide');
                                    $('#complete-signup-modal').modal('show');
                                });

                                $('#add-card-form input').val('');
                                $('#add-card-form select').val('');

                            } else {
                                hideLoader('#card-box');
                                toastr.error(response.data.errors.message);
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

            $('button[type="submit"]').click(function (e) {
                e.preventDefault();

                $('#customer-registration-form').parsley().validate();

                if ($('#customer-registration-form').parsley().isValid()) {

                    if (!$('input[name="terms_accepted"]').is(':checked')) {

                        toastr.error('You must accept terms & conditions');
                    } else {

                        $('#customer-registration-form').submit();
                    }
                }

            });


            $('#add-card').click(function () {
                $('#complete-signup-modal').modal('hide');
                $('#add-card-modal').modal('show');
            });

            $('input[name="date_of_birth"]').mask("00-00-0000");

        });
    </script>
    
    <?php include_once $this->getPart('/web/common/footer.php'); ?>