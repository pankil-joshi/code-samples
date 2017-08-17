<?php include_once $this->getPart('/web/common/header.php'); ?>
<?php include_once $this->getPart('/web/customer/top_nav.php'); ?>
<div class="desbord_body check_outstp">
    <div class="container">
        <div class="row">
            <div class="col-md-12 lastest_marg">
                <div class="col-md-12">
                    <div class="yor_active" style="border:0">
                        <div class="order-active">
                            <div class="col-md-4 col-sm-5">
                                <h6>Your Tagzie Wallet</h6>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <ul class="orderdetil">
                                    <li>Securely store your payment details for a rapid checkout process when shopping on Tagzie.</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-12 " style="padding:0 ">
                            <?php if (!empty($cards)): $counter = 1;
                                foreach ($cards as $card): ?>
                                        <?php if ($counter == 1): $counter = 1; ?>
                                        <div class="border_left_box">
        <?php endif; ?>
                                        <div class="col-md-3 card-box-container">
                                            <div class="row">
                                                <div class="">
                                                    <div class="wallt">
                                                        <div class="card-details-box">
                                                            <ul class="wllet_join">
                                                                <li><?= $card['brand']; ?></li>
                                                                <li>**** **** **** <?= $card['last4']; ?></li>
                                                                <li>Expires: <?= $card['exp_month']; ?>/<?= $card['exp_year']; ?></li>
                                                            </ul>

                                                            <ul class="wllet_join">
                                                                <li>Name: <?= $card['name']; ?></li>
                                                            </ul>

                                                            <ul class="wllet_join">
                                                                <?php if (!empty($card['address_line1'])): ?><li><?= $card['address_line1']; ?> </li><?php endif; ?>
                                                                <?php if (!empty($card['address_line2'])): ?><li><?= $card['address_line2']; ?></li><?php endif; ?>
                                                                <?php if (!empty($card['address_city']) || !empty($card['address_state'])): ?><li><?= (!empty($card['address_city'])) ? $card['address_city'] . ', ' : ''; ?> <?= (!empty($card['address_state'])) ? $card['address_state'] : ''; ?></li><?php endif; ?>
        <?php if (!empty($card['address_zip']) || !empty($card['address_country'])): ?><li><?= (!empty($card['address_zip'])) ? $card['address_zip'] . ', ' : ''; ?> <?= (!empty($card['address_country'])) ? $card['address_country'] : ''; ?> </li><?php endif; ?>
                                                            </ul>
                                                        </div>
                                                        <a href="#" class="defult_adres <?php if ($card['is_default'] == 1): ?>active<?php endif; ?> set-default" data-id="<?= $card['stripe_card_id']; ?>"><span class="glyphicon glyphicon-star"></span>Default Card</a>
                                                        <a href="#" class="defult_adres delete-card" data-id="<?= $card['stripe_card_id']; ?>"><span class="glyphicon glyphicon-remove-sign"></span>Delete Card</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

        <?php if ($counter == 4): $counter = 1; ?>

                                        </div> 
                                    <?php endif; ?>
                                    <?php $counter++;
                                endforeach; ?>
                                        <?php else: ?>
                            <p style="text-align: center"><img src="<?= $app['base_assets_url']; ?>images/no-data.png" alt="No data"> Please <a class="primary-orange" onclick="javascript:void()" href="#" data-toggle="modal" data-target="#add-card-modal">add payment source</a> to your account to allow you to make seamless purchases.</p>
<?php endif; ?>
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

                                $('#selectedCard').html(cardList);
                                hideLoader('#card-box');
                                $('#add-card-modal').modal('hide');
                                $('#complete-signup-modal').modal('show');
                                $('#selectedCard').trigger('change');
                                window.location.reload();
                            });

                            $('#add-card-form input').val('');
                            $('#add-card-form select').val('');
                            $('#addCard').prop('disabled', false);
                        } else {
                            hideLoader('#card-box');
                            toastr.error(response.data.errors.message);
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
        $('.delete-card').click(function () {

            var element = $(this);
            var data = {};
            data.body = {card_id: $(this).data('id')};
            var confirmDelete = confirm('Are you sure, you want to delete this card?');

            if (confirmDelete) {

                $.when(deleteCard(data)).then(function (data) {
                    hideLoader('body');

                    if (data.meta.success) {

                        element.closest('.card-box-container').remove();
                        toastr.success('Card deleted successfully.');
                    } else {

                        toastr.error(data.data.errors.message);
                    }

                });
            }

        });

        $('.set-default').click(function () {

            var element = $(this);
            var data = {};
            data.body = {card_id: $(this).data('id')};
            var confirmDefault = confirm('Are you sure, you want to set this as your default card?');

            if (confirmDefault) {

                $.when(setDefaultCard(data)).then(function (data) {
                    hideLoader('body');

                    if (data.meta.success) {

                        $('.set-default').each(function () {

                            if ($(this).hasClass('active')) {

                                $(this).removeClass('active');
                            }
                        });

                        element.addClass('active');
                        toastr.success('Default card changed.');
                    } else {

                        toastr.error(data.data.errors.message);
                    }

                });
            }

        });
    });
</script>
<?php include_once $this->getPart('/web/common/footer.php'); ?>