<?php include_once $this->getPart('/web/common/header.php'); ?> 
<?php include_once $this->getPart('/web/checkout/sub_header.php'); ?>
<div class="check_outstp">
    <div class="container">
        <form method="POST" action="<?= $data['base_url']; ?>checkout/payment" id="payment-form">
            <input type="hidden" name="media_id" value="<?= $_SESSION['cart']['media_id']; ?>" id="media-id">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-5">
                        <div class="chack_demain">
                            <div class="row">    
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                    <img src="<?= $media['image_thumbnail']; ?>" class="img-responsive" alt="">
                                </div>

                                <div class="col-md-9 col-sm-9 col-xs-9">
                                    <h6><?= $media['title']; ?></h6>
                                    <p>from <span>@<?= $media['instagram_username']; ?></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7" id="variants">
                        <div class="information_detail">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <h6 style="margin-left: 15px;"><?php if ($media['variants'][0]['is_default'] == 0): ?>Variant: <?php else: ?>Price: <?php endif; ?></h6>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <h6>Quantity:</h6>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12 text-right">    
                                <h6>Cost:</h6>
                            </div>
                        </div>
                        <div class="items">
                            <?php
                            if ($media['variants'][0]['is_default'] == 0):
                                $items = (!empty($_SESSION['cart']['variant_ids'])) ? $_SESSION['cart']['variant_ids'] : array($media['variants'][0]['id']);
                                $counter = 1;
                                foreach ($items as $index => $item):
                                    foreach ($media['variants'] as $variant) {

                                        if ($variant['id'] == $item) {

                                            $variant = $variant;

                                            break;
                                        }
                                    }
                                    ?>
                                    <div class="col-md-12 information_detail variant-row">
                                        <div class="col-md-6 col-sm-6 col-xs-6 variant-label">
                                            <span class="variant-label-span">
                                                <span class="variant-label-text"><?= $variant['label']; ?></span> <span class="variant-price"><?= $currencies[$media['base_currency_code']]; ?><?= ($media['tax']['inclusive']) ? getExclusiveAmount($variant['price'], $media['tax']['rate']) : $variant['price']; ?></span>
                                                <input type="hidden" class="variant-selector" readonly="readonly" name="variant_ids[]" value="<?= $item; ?>" data-variantid="<?= $item; ?>" data-stockquantity="<?= $variant['stock_quantity']; ?>" data-price="<?= ($media['tax']['inclusive']) ? getExclusiveAmount($variant['price'], $media['tax']['rate']) : $variant['price']; ?>">
                                                <span class="glyphicon glyphicon-minus-sign remove-variant primary-oragnge" style="font-size: 21px"></span></span>
                                            </span>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-6">
                                            <div class="variant-quantity-container">
                                                <select class="variant-quantity selectpicker" name="quantities[]">
                                                    <?php for ($i = 1; $i <= $variant['stock_quantity']; $i++): ?>
                                                        <option value="<?= $i; ?>" <?= (!empty($_SESSION['cart']['quantities'][$index]) && $_SESSION['cart']['quantities'][$index] == $i) ? 'selected' : ''; ?>><?= $i; ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-3 col-xs-12" style="padding-right:0">
                                            <div class="text-right">   

                                                <span class="currency_code"><?= $media['base_currency_code']; ?></span><span class="row-total">0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $counter++;
                                endforeach;
                                ?>
                            </div>
                            <div class="col-md-12 information_detail add-variant-row">
                                <div class="dropdown variant-list" style="display: inline-block;">
                                    <span class="dropdown-toggle" type="button" data-toggle="dropdown">
                                        <span class=" glyphicon glyphicon-plus-sign add-variant primary-oragnge"></span>Add variant</span>
                                    <ul class="dropdown-menu">
                                        <?php foreach ($media['variants'] as $variant): ?>

                                            <li><a data-variantid="<?= $variant['id']; ?>" data-currencycode="<?= $media['base_currency_code']; ?>" data-stockquantity="<?= $variant['stock_quantity']; ?>" data-price="<?= ($media['tax']['inclusive']) ? getExclusiveAmount($variant['price'], $media['tax']['rate']) : $variant['price']; ?>" class="variant-item <?= (!$variant['stock_quantity'] > 0) ? 'disabled' : ''; ?>" ><?= $variant['label']; ?> <?= $currencies[$media['base_currency_code']]; ?><?= ($media['tax']['inclusive']) ? getExclusiveAmount($variant['price'], $media['tax']['rate']) : $variant['price']; ?> <?= (!$variant['stock_quantity'] > 0) ? '- Out of Stock' : ''; ?></a></li>

                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <?php
                        else:
                            ?>
                            <div class="information_detail variant-row">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <span class="currency_code"><?= $currencies[$media['base_currency_code']]; ?><?= ($media['tax']['inclusive']) ? getExclusiveAmount($media['variants'][0]['price'], $media['tax']['rate']) : $media['variants'][0]['price']; ?>
                                            <input class="variant-selector" type="hidden" name="variant_ids[]" value="<?= $media['variants'][0]['id']; ?>" data-stockquantity="<?= $media['variants'][0]['stock_quantity']; ?>" data-price="<?= ($media['tax']['inclusive']) ? getExclusiveAmount($media['variants'][0]['price'], $media['tax']['rate']) : $media['variants'][0]['price']; ?>">
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-6">
                                    <div class="variant-quantity-container">

                                        <select class="variant-quantity selectpicker" name="quantities[]">
                                            <?php for ($i = 1; $i <= $media['variants'][0]['stock_quantity']; $i++): ?>

                                                <option value="<?= $i; ?>" <?= (!empty($_SESSION['cart']['quantities']) && $_SESSION['cart']['quantities'][0] == $i) ? 'selected' : ''; ?>><?= $i; ?></option>

                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-3 col-xs-12">

                                    <div class="text-right">   
                                        <span class="currency_code"><?= $media['base_currency_code']; ?></span><span class="row-total">0</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endif; ?>


                </div>
                <div class="col-md-12 col-sm-12"><div class="row"><hr></div></div>
            </div>

            <div class="col-md-5"></div>
            <div class="col-md-7 subtotal">
                <div class="col-md-8 col-sm-8 col-xs-8"><h6>Subtotal:</h6></div>
                <div class="col-md-4 col-sm-4 col-xs-4"> <div class="text-right">  <span class="currency_code"><?= $media['base_currency_code']; ?></span><span id="sub-total">0</span></div></div>
                <div class="col-md-12 col-sm-12 col-xs-12" style="padding:0"><hr></div> 

                <div class="col-md-2 col-sm-8 col-xs-8"><h6 class="top_space">Address:</h6></div>
                <div class="col-md-10 col-sm-8 col-xs-8"><div class="row">
                        <div class="webcheck1">
                            <select name="delivery_address" class="selectpicker" id="delivery-address">
                                <?php foreach ($delivery_addresses as $delivery_address): ?>
                                    <option value="<?= $delivery_address['id']; ?>" <?= (isset($_SESSION['cart']['delivery_address']) && $_SESSION['cart']['delivery_address'] == $delivery_address['id'] || !isset($_SESSION['cart']['delivery_address']) && $delivery_address['is_delivery_address'] == 1) ? 'selected' : ''; ?>>
                                        <?= _s($delivery_address['first_name']); ?> <?= _s($delivery_address['last_name']); ?>,
                                        <?= _s($delivery_address['line_1']); ?> <?= _s($delivery_address['line_2']); ?>,
                                        <?= _s($delivery_address['city']); ?> <?= _s($delivery_address['state']); ?>,
                                        <?= _s($delivery_address['zip_code']); ?> <?= _s($countries[$delivery_address['country']]['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select><span><a href="#" title="Add Address" data-target="#address-form" data-toggle="modal"><span class="glyphicon glyphicon-plus-sign add-address primary-oragnge"></span></a></span>
                        </div>
                    </div>
                </div>                    
                <div class="col-md-12 col-sm-12 col-xs-12" style="padding:0"><hr></div> 
                <div class="col-md-2 col-sm-8 col-xs-8"><h6 class="top_space">Shipping:</h6></div>
                <div class="col-md-6 col-sm-8 col-xs-8" style="padding-left:0"><h6>
                        <select id="postage-selector" class="selectpicker" name="postage_option">
                            <?php foreach ($media['postage_options'] as $postage_option): ?>
                                <option value="<?= $postage_option['id']; ?>" data-postageoptionid="<?= $postage_option['id']; ?>" data-pastagerate="<?= $postage_option['rate']; ?>" <?= (isset($_SESSION['cart']['postage_option']) && $_SESSION['cart']['postage_option'] == $postage_option['id']) ? 'selected' : ''; ?> ><?= $postage_option['label']; ?>, <?= $postage_option['duration']; ?> est. <?= convert_datetime(gmdate('d/m/Y', strtotime('+ ' . $postage_option['duration'] . ' UTC')), $user['timezone'], 'd/m/Y'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </h6></div>
                <div class="col-md-4 col-sm-4 col-xs-4"><h5 class="top_space"><span class="currency_code"><?= $media['base_currency_code']; ?></span><span id="postage-rate">0</span></h5></div>
                <?php if (!empty($media['merchant']['tax_enabled'])): ?>
                    <div class="col-md-12 col-sm-12 col-xs-12" style="padding:0"><hr></div> 
                    <div class="col-md-8 col-sm-8 col-xs-8"><h6>Tax:</h6></div>
                    <div class="col-md-4 col-sm-4 col-xs-4"><h5><span class="currency_code"><?= $media['base_currency_code']; ?></span><span id="tax" data-tax-rate="0">0</span></h5></div> 
                <?php endif; ?>                        
                <div class="col-md-12 col-sm-12 col-xs-12" style="padding:0"><hr></div> 
                <div class="col-md-8 col-sm-8 col-xs-8"><h6>Total:</h6></div>
                <div class="col-md-4 col-sm-4 col-xs-4"><h5><span class="currency_code"><?= $media['base_currency_code']; ?></span><span id="total">0</span></h5></div>
                <div class="col-md-12 col-sm-12 col-xs-12" style="padding:0"><hr></div> 
                <div class="col-md-12">
                    <input type="hidden" name="total" value="" id="totalInput">
                    <button class="sure_pyment" id="payment-button">   
                        <div class="col-md-2 col-sm-2 col-xs-4"><img src="<?= $data['base_assets_url']; ?>images/creadit_card.png" class="img-responsive" alt=""> </div>
                        <div class="col-md-10 col-sm-10 col-xs-8 text-center" type="submit"><h5>PROCEED TO SECURE PAYMENT</h5></div>
                    </button>
                </div>
            </div>    

            <div class="col-md-12">
                <div class="col-md-5">
                    <div class="chack_demain">
                        <div class="row">    
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <img alt="" class="img-responsive" src="<?= $media['user_instagram_profile_picture']; ?>">
                            </div>

                            <div class="col-md-9 col-sm-9 col-xs-9">
                                <h6>@<?= $media['instagram_username']; ?></h6>
                                <p>Seller rating:                                    
                                    <?php for ($i = 0; $i < 5; $i++): ?>
                                        <span class="glyphicon <?php if ($i < $media['seller_rating']): ?>glyphicon-star<?php else: ?>glyphicon-star-empty<?php endif; ?>"></span>
                                    <?php endfor; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <div class="caller_sociel">
                                <div class="col-md-4 col-sm-4 col-xs-12"><a href="<?= (!empty($media['merchant']['business_telephone_prefix']) && !empty($media['merchant']['legal_entity_phone_number'])) ? 'tel:' . $media['merchant']['business_telephone_prefix'] . $media['merchant']['legal_entity_phone_number'] : '#'; ?>"><div class="bak_color"><span class=""><img src="<?= $data['base_assets_url']; ?>images/iphone.png" class="img-responsive" alt=""> </span><p>Call Seller</p></div></a>  </div>
                                <div class="col-md-4 col-sm-4 col-xs-12"><?php if (!empty($user['is_active'])): ?><a href="#"  class="message-button" data-type="enquiry" data-product_id="<?= $media['id']; ?>" data-id="" data-second_user_id="<?= $media['user_id']; ?>"><div class="bak_color"><span class="magr_img"><img src="<?= $data['base_assets_url']; ?>images/mesage.png" class="img-responsive" alt=""></span><p>Ask A
                                                    Question</p></div></a><?php endif; ?></div>
                                <div class="col-md-4 col-sm-4 col-xs-12"><?php if (!empty($user)): ?><a href="#" data-toggle="modal" data-target="#report-product"><div class="bak_color"><span class=""><img src="<?= $data['base_assets_url']; ?>images/shape_wht.png" class="img-responsive" alt=""></span><p>Report
                                                    Seller</p> </div></a><?php endif; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</form>
</div>  
</div> 
<?php
$addressModalType = 'Add';
include_once $this->getPart('/web/components/address_form.php'); // Add new address modal.  
?>
<?php include_once $this->getPart('/web/components/report-product.php'); ?>
<?php include_once $this->getPart('/web/components/message.php'); ?>
<?php include_once $this->getPart('/web/components/new_thread.php'); ?>
<script>

    var Variants = <?= json_encode($media['variants']); ?>;
    var selectedVariants = [];
    var Countries = <?= json_encode($countries); ?>;

    /*
     * Calculate cart total.
     */
    var calculateTotal = function () {

        var tax = ($('#tax').data('tax-rate')) ? $('#tax').data('tax-rate') : 0;
        tax = parseFloat($('#sub-total').text()) * parseFloat(tax);
        var total = parseFloat($('#sub-total').text()) + parseFloat($('#postage-rate').text()) + tax;
        $('#totalInput').val(parseFloat(total).toFixed(2));
        $('#tax').text(tax.toFixed(2));
        $('#total').text(parseFloat(total).toFixed(2));
    };

    $(document).ready(function () {

        $('body').on('click', '.remove-variant', function () {

            $(this).closest('.variant-row').remove();

            if ($('.variant-row').length == 0) {
                $('#sub-total, #postage-rate, #tax, #total').text('0.00');
            }

            $('select.variant-quantity').trigger('change');
        });

        $('body').on('click', '.variant-item', function () {

            var selectedVariant = $(this);

            if ($('input[data-variantid="' + selectedVariant.data('variantid') + '"]').length > 0) {

                var selectedQuantity = parseInt($('input[data-variantid="' + selectedVariant.data('variantid') + '"]').closest('.variant-row').find('select.variant-quantity').val());
                var last_option_val = parseInt($('input[data-variantid="' + selectedVariant.data('variantid') + '"]').closest('.variant-row').find('select.variant-quantity option').last().val());
                if (selectedQuantity < last_option_val) {
                    $('input[data-variantid="' + selectedVariant.data('variantid') + '"]').closest('.variant-row').find('select.variant-quantity').val(selectedQuantity + 1).selectpicker().trigger('change');
                }
            } else {

                $(Variants).each(function (i, item) {

                    if (item.id == selectedVariant.data('variantid')) {
                        var stockQuantity = selectedVariant.data('stockquantity');
                        var quantitySelector = '';
                        var rowTotal = (item.price * ((selectedVariant.data('stockquantity') <= 0) ? 0 : 1));
                        for (var i = 1; i <= stockQuantity; i++) {

                            quantitySelector += '<option value"' + i + '">' + i + '</option>';

                        }
                        var outOfStock = (item.stock_quantity <= 0) ? ' - Out of Stock' : '';
                        var variantRow = '<div class="col-md-12 information_detail variant-row">'

                                + '<div class="col-md-6 col-sm-6 col-xs-6 variant-label">'

                                + '<span class="variant-label-span"><span class="variant-label-text">'
                                + item.label + '</span> <span class="variant-price"><?= $currencies[$media['base_currency_code']]; ?> ' + selectedVariant.data('price') + outOfStock
                                + '</span><input type="hidden" class="variant-selector" readonly="readonly" name="variant_ids[]" value="' + item.id + '" data-variantid="' + item.id + '" data-price="' + selectedVariant.data('price') + '">'
                                + '<span class="glyphicon glyphicon-minus-sign remove-variant primary-oragnge" style="font-size: 21px"></span></span>'
                                + '</span>'
                                + '</div>'

                                + '<div class="col-md-3 col-sm-3 col-xs-6">'

                                + '<div class="variant-quantity-container">'
                                + '<select class="variant-quantity selectpicker" name="quantities[]">'
                                + quantitySelector
                                + '</select>'
                                + '</div>'

                                + '</div>'

                                + '<div class="col-md-3 col-sm-3 col-xs-12" style="padding-right:0">'

                                + '<div class="text-right">'
                                + '<span class="currency_code"> <?= $currencies[$media['base_currency_code']]; ?> </span><span class="row-total">' + rowTotal + '</span>'
                                + '</div>'

                                + '</div>'

                                + '</div> ';

                        var currentRow = $('#variants .items').append(variantRow).find('select.variant-quantity');
                        currentRow.selectpicker();
                        currentRow.trigger('change');
                        return false;
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
                $('#new-thread').data('product_id', $(this).data('product_id'));
                $('#new-thread').data('second_user_id', $(this).data('second_user_id'));
                $('#new-thread .modal-title').text($(this).data('type'));
                $('#new-thread').modal('show');
            }

        });
        /*
         * Checkout to payment page
         */

        $('#payment-button').click(function (e) {
            e.preventDefault();
            if (!$('#delivery-address').val()) {
                toastr.warning('Please select or add a delivery address.');
            } else if (!$('#postage-selector').val()) {
                toastr.warning('Merchant do not ship to the delivery address you provided.');
            } else if ($('input[name="variant_ids[]"]').length == 0) {
                toastr.warning('You need to select at least one item.');
            } else {
                $('#payment-form').submit();
            }
        });

        /*
         * Add new address.
         */
        $("#save-address").on('submit', function (e) {
            e.preventDefault();
            var form = $(this);

            form.parsley().validate();

            if (form.parsley().isValid()) {
                var data = {};
                data.body = $('#save-address').serializeObject()

                $.when(saveAddress(data)).then(function (data) {

                    if (data.meta.success) {

                        var address = data.data.address;

                        var delivery_address = '<option selected value="' + address.id + '">'
                                + _e(address.first_name) + ' ' + _e(address.last_name) + ', '
                                + _e(address.line_1) + ', ' + _e(address.line_2) + ', '
                                + _e(address.city) + ', ' + _e(address.state) + ', '
                                + _e(address.zip_code) + ', ' + _e(Countries[address.country].name)
                                + '</option>';
                        $('select[name="delivery_address"]').append(delivery_address).selectpicker('refresh');
                        $('select#delivery-address').trigger('change');
                        $("#address-form").modal('hide');
                        hideLoader('#address-form .modal-content');
                        $('#save-address').find('input, select').val('');
                        toastr.success('Address added successfully!');
                    }

                });

            }
        });


        replaceCurrencyCode(); // Replace currency code with symbol.

        /*
         * Functions to run run on change of variant quantity.
         */
        $('#variants').on('change', 'select.variant-quantity', function () {

            var selectedQuantity = ($(this).find(':selected').val() == null) ? 0 : parseFloat($(this).find(':selected').val());

            var rowTotal = parseFloat($(this).closest('.variant-row').find('.variant-selector').data('price')) * selectedQuantity;
            var subTotal = 0;
            $(this).closest('.variant-row').find('.row-total').text(parseFloat(rowTotal).toFixed(2));
            $('.variant-row').each(function () {
                subTotal += parseFloat($(this).find('.row-total').text());

            });
            $('#sub-total').text(parseFloat(subTotal).toFixed(2));
            calculateTotal();
        });

        /*
         * Functions to perform on change of postage option.
         */
        $('select#postage-selector').change(function () {

            if (parseFloat($('#sub-total').text()) == '') {

                $('#postage-rate').text(0);
            } else {

                $('#postage-rate').text($(this).find(':selected').data('pastagerate'));
            }

            calculateTotal();
        });


        /*
         * Functions to perform on change of delivery address.
         */
        $('select#delivery-address').change(function () {

            var selectedAddressId = $(this).val();
            var mediaId = $('#media-id').val();

            var data = {};
            data.media_id = mediaId;
            data.body = {address_id: selectedAddressId};

            $.when(getPostageOptionsTax(data)).then(function (data) {

                if (data.meta.success) {

                    var postageOptions = data.data.postageOptions;
                    var taxRate = data.data.taxRate;

                    var postageOptionsHtml = '';

                    $(postageOptions).each(function (index, item) {

                        var numberOfDaysToAdd = item.duration.split(' ');
                        var estimateDate = moment().add(numberOfDaysToAdd[0], 'days').format('DD/MM/YYYY');
                        var selected = (<?= (isset($_SESSION['cart']['postage_option'])) ? $_SESSION['cart']['postage_option'] : 0; ?> == item.id) ? 'selected' : '';
                        postageOptionsHtml += '<option value="' + item.id + '" data-postageoptionid="' + item.id + '" data-pastagerate="' + item.rate + '" ' + selected + '>' + item.label + ', ' + item.duration + ' est. ' + estimateDate + '</option>';
                    });


                    $('#postage-selector').html(postageOptionsHtml).selectpicker('refresh');
                    $('#tax').data('tax-rate', taxRate);
                    calculateTotal();
                }
            });

        });

        $('select.variant-quantity').trigger('change');
        $('select#postage-selector').trigger('change');
        $('select#delivery-address').trigger('change');
    });
</script>
<?php include_once $this->getPart('/web/common/footer.php'); ?>    
