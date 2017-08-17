<!--<script type="text/javascript" src="https://js.stripe.com/v2/"></script>-->
<form id="cc-details">
    <div class="col-xs-12 pro_form">
        <!--<label for="exampleInput1">Name on Card:</label>-->
        <input id="card-name" type="text" class="form-control frid_boeder" placeholder="Name on Card">
    </div>

    <div class="col-xs-12 pro_form pading_top">
        <!--<label for="exampleInput1">Card number:</label>-->
        <input id="card-number" type="number" class="form-control frid_boeder" placeholder="Card Number">
    </div>

    <div class="col-xs-12 pro_form pading_top">
        <div class="col-xs-12 mobileField">
            <!--<label for="exampleInput1">Expiry Date:</label>-->
        </div>
        <div class="col-xs-6 mobileField expiry-date">
            <select class="form-control frid_boeder choice" id="card-expiry-month" style="border-right:0; margin-top:-1px"></select>
            <span class="input-group-addonx expiry-date-select" style=""><i class="fa fa-sort-desc fa-fw"></i></span>
        </div>
        <script>
            onCardExpiryMonth();

        </script>
        <!--<div class="col-xs-2 " style="font-size: 27px;padding: 0px 0px 0px 0px; text-align:center">&nbsp;&nbsp;/</div>-->
        <div class="col-xs-6 mobileField expiry-date">
            <select class="form-control frid_boeder choice" id="card-expiry-year" style="margin-top:-1px"></select>
            <span class="input-group-addonx expiry-date-select" style=""><i class="fa fa-sort-desc fa-fw"></i></span>
        </div>
        <script>
            onCardExpiryYear();

        </script>
    </div>

    <div class="col-xs-12 pro_form pading_top" style="margin-top:-5px">
        <!--<label for="exampleInput1">CSV/CVV/CVC:</label>-->
        <input id="cvc" type="password" class="form-control last_boder" placeholder="CSV / CVV / CVC">
    </div>

    <!--new-->
    <div id="address-area">
        <div class="col-xs-12 pro_form termsconditions">
            <label class="control control--checkbox">Use Default Address as Billing Address
                <input id="isDefaultAsBillingAddress" type="checkbox" onchange="onDefaultBillingAddress();" value="">
                <div class="control__indicator"></div>
            </label>
        </div>

        <script>
            onDefaultBillingAddress();

        </script>
        <div id="addressArea">
            <div class="col-xs-12 pro_form">
                <!--<label for="exampleInput1">Billing Address Line 1:</label>-->
                <input id="address-line1" type="text" class="form-control frid_boeder" placeholder="Address Line 1">
            </div>

            <div class="col-xs-12 pro_form pading_top">
                <!--  <label for="exampleInput1">Billing Address Line 2:</label>-->
                <input id="address-line2" type="text" class="form-control frid_boeder" placeholder="Address Line 2">
            </div>

            <div class="col-xs-12 pro_form pading_top">
                <!--<label for="exampleInput1">City:</label>-->
                <input class="form-control frid_boeder" id="address-city" placeholder="Town / City">
            </div>

            <div class="col-xs-12 pro_form pading_top">
                <!--<label for="exampleInput1">State:</label>-->
                <input class="form-control frid_boeder" id="address-state" placeholder="Region / State">
            </div>

            <div class="col-xs-12 pro_form pading_top">
                <!--<label for="exampleInput1">Zip code:</label>-->
                <input class="form-control frid_boeder" id="address-zip" placeholder="Postcode / Zip Code">
            </div>


            <div class="col-xs-12 pro_form pading_top">
                <!--<label for="exampleInput1">Country:</label>-->
                <select class="form-control signupAppendCountry last_boder choice" id="country"></select>
                <span class="input-group-addonx2 set_card_span" style=""><i class="fa fa-sort-desc fa-fw"></i></span>
            </div>
        </div>
        <script>
            onAppendCountries();
            $(".choice").change(function () {

                if ($(this).val() == "") {
                    $(this).addClass("empty");
                } else {
                    $(this).removeClass("empty");
                }
            });
            $(".choice").change();

        </script>
    </div>

</form>

<input type="hidden" id="response">
<div class="col-xs-12 back_btn active-focus">
    <button class="col-xs-5 btn btn-default btn_proced bck_btn cancel_btn cancelAddress" type="button" onclick="onClickPaymentListShow();">CANCEL</button>
    <button class=" col-xs-5 btn btn-default btn_proced" type="button" id="add" onclick="requestToken();">ADD CARD</button>
</div>

<script>
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

                        document.getElementById('response').value = 'success';
                        window.location.hash = 'success';

                    } else {
                        window.location.hash = 'error';
                        alert(response.data.errors.message);
                        $('#showLoader').hide();
                        document.getElementById('add').disabled = false;
                    }

                }

            }

            xmlHttp.open("post", "<?= $base_api_url; ?>payment/stripe/addCard");
            xmlHttp.setRequestHeader("token", token);
            xmlHttp.send(formData);

        } else {

            if (response.error.message) {

                onToast(response.error.message);

            } else {

                onToast('Some unexpected error occured!');

            }

            document.getElementById('add').disabled = false;
            $('#showLoader').hide();
        }

    }

    function requestToken() {

        document.getElementById('add').disabled = true;

        if (!Stripe.card.validateCardNumber(document.getElementById("card-number").value)) {

            onToast('Card number is not valid!');

            document.getElementById('add').disabled = false;

        }
        if (document.getElementById("card-name").value == '') {

            onToast('Name on card can not be empty!');

            document.getElementById('add').disabled = false;

        } else if (!Stripe.card.validateExpiry(document.getElementById("card-expiry-month").value, document.getElementById("card-expiry-year").value)) {

            onToast('Expiry date is not valid!');

            document.getElementById('add').disabled = false;

        } else if (!Stripe.card.validateCVC(document.getElementById("cvc").value)) {

            onToast('CVC is not valid!');

            document.getElementById('add').disabled = false;

        } else {

            $('#showLoader').show();
            onPaymentSuccessAddCheck('shbcs');

            Stripe.card.createToken({
                number: document.getElementById("card-number").value,
                cvc: document.getElementById("cvc").value,
                exp_month: document.getElementById("card-expiry-month").value,
                exp_year: document.getElementById("card-expiry-year").value,
                name: document.getElementById("card-name").value,
                address_line1: document.getElementById("address-line1").value,
                address_line2: document.getElementById("address-line2").value,
                address_state: document.getElementById("address-state").value,
                address_city: document.getElementById("address-city").value,
                address_zip: document.getElementById("address-zip").value,
                address_country: document.getElementById("country").value,
            }, stripeResponseHandler);

            console.log('sentAfter');


        }

    }

    $("span").on('click', null, function () {

        var parentId = $(this).parent().find('select, input').attr('id');

        try {
            var e = document.createEvent('MouseEvents');
            e.initMouseEvent('mousedown');
            $(this).parent().find('select')[0].dispatchEvent(e);
        } catch (e) {
            console.log(e);
        }

    });
</script>        
