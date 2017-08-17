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
                                <h6>Your Address Book</h6>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <ul class="orderdetil">
                                    <li>manage your common addresses for a seamless shopping experience with Tagzie</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-12 " style="padding:0 " id="addresses">
                            <?php
                            if (!empty($addresses)): $counter = 1;
                                foreach ($addresses as $address):
                                    ?>
                                    <div class="col-md-3 address-box-container address-box-<?= $address['id']; ?>">
                                        <div class="row">
                                            <div class="">
                                                <div class="wallt">
                                                    <ul class="wllet_join">
                                                        <li><?= $address['first_name']; ?>  <?= $address['last_name']; ?> </li>
                                                        <?php if (!empty($address['line_1'])): ?><li><?= $address['line_1']; ?></li><?php endif; ?>
        <?php if (!empty($address['line_1'])): ?><li><?= $address['line_2']; ?></li><?php endif; ?>
                                                        <li><?= $address['state']; ?></li>
                                                        <li><?= $address['city']; ?></li>
                                                        <li><?= $address['zip_code']; ?></li>
                                                        <li><?= $countries[$address['country']]['name']; ?></li>
                                                    </ul>
                                                    <a href="#" class="defult_adres set-default <?php if ($address['is_delivery_address'] == 1): ?>active<?php endif; ?>"  data-id="<?= $address['id']; ?>"><span class="glyphicon glyphicon-star"></span>Default Address</a>
                                                    <a href="#" class="defult_adres edit-address" data-id="<?= $address['id']; ?>"><span class="glyphicon glyphicon-pencil"></span>Edit Address</a>
                                                    <a href="#" class="defult_adres delete-address" data-id="<?= $address['id']; ?>"><span class="glyphicon glyphicon-remove-sign"></span>Delete Address</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($counter == 4): $counter = 1; ?>
                                        <div class="clearfix"></div>
                                    <?php endif; ?>          
                                <?php endforeach; ?>
                                <?php else: ?>
                                        <p style="text-align: center" class="color_chag_a"><img src="<?= $app['base_assets_url']; ?>images/no-data.png" alt="No data"> There are no addresses in your addressbook, please <a href="#" class="add-address primary-orange">add an address</a>.</p>
<?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$addressModalType = 'Add';
include_once $this->getPart('/web/components/address_form.php');
?>
<script>
    var Countries = <?= json_encode($countries); ?>;

    $(document).ready(function () {

        /*
         * Save address.
         */
        $("#save-address").on('submit', function (e) {
            e.preventDefault();
            var form = $(this);

            form.parsley().validate();

            if (form.parsley().isValid()) {
                var data = {};
                data.body = $('#save-address').serializeObject();
                data.address_id = $('#address-form').data('id');

                $.when(saveAddress(data)).then(function (data) {

                    if (data.meta.success) {

                        var address = data.data.address;

                        $("#address-form").modal('hide');

                        if ($('.address-box-' + address.id).length > 0) {
                            var addressHtml = '<li>' + _e(address.first_name) + ' ' + _e(address.last_name) + '</li>'
                                    + '<li>' + _e(address.line_1) + '</li>'
                                    + '<li>' + _e(address.line_2) + '</li>'
                                    + '<li>' + _e(address.state) + '</li>'
                                    + '<li>' + _e(address.city) + '</li>'
                                    + '<li>' + _e(address.zip_code) + '</li>'
                                    + '<li>' + _e(Countries[address.country].name) + '</li>';
                            $('.address-box-' + address.id).find('ul').html(addressHtml);
                        } else {
                            var deliveryAddress = (address.is_delivery_address == 1)? 'active' : '';
                            var addressHtml = '<div class="col-md-3 address-box-container address-box-' + address.id + '">'
                                    + '<div class="row">'
                                    + '<div class="">'
                                    + '<div class="wallt"> <ul class="wllet_join">'
                                    + '<li>' + _e(address.first_name) + ' ' + _e(address.last_name) + '</li>'
                                    + '<li>' + _e(address.line_1) + '</li>'
                                    + '<li>' + _e(address.line_2) + '</li>'
                                    + '<li>' + _e(address.state) + '</li>'
                                    + '<li>' + _e(address.city) + '</li>'
                                    + '<li>' + _e(address.zip_code) + '</li>'
                                    + '<li>' + _e(Countries[address.country].name) + '</li></ul>'
                                    + '<a href="#" class="defult_adres set-default ' + deliveryAddress + '"  data-id="'+ address.id +'"><span class="glyphicon glyphicon-star"></span>Default Address</a>'
                                    + '<a href="#" class="defult_adres edit-address" data-id="'+ address.id +'"><span class="glyphicon glyphicon-pencil"></span>Edit Address</a>'
                                    + '<a href="#" class="defult_adres delete-address" data-id="'+ address.id +'"><span class="glyphicon glyphicon-remove-sign"></span>Delete Address</a>'
                                    + '</div></div></div></div>';   
                            if ($('.address-box-container').length > 0) {

                                $('#addresses').append(addressHtml);
                            } else {

                                $('#addresses').html(addressHtml);
                            }

                        }

                        toastr.success('Address saved successfully.');
                    } else {

                        toastr.error(data.data.errors.message);
                    }

                });

            }
        });
        $('#addresses').on('click', '.add-address', function (e) {
            e.preventDefault();

            $('#address-form').data('id', '');
            $('#address-form').modal('show');
        });
        $('#addresses').on('click', '.edit-address', function (e) {
            e.preventDefault();
            
            var data = {address_id: $(this).data('id')};
            $('#address-form').find('.modal-title').text('Edit Address');
            $.when(getAddress(data)).then(function (data) {
                hideLoader('body');

                if (data.meta.success) {

                    var address = data.data.address;

                    $('#address-form').find('input[name="first_name"]').val(address.first_name);
                    $('#address-form').find('input[name="last_name"]').val(address.last_name);
                    $('#address-form').find('input[name="line_1"]').val(address.line_1);
                    $('#address-form').find('input[name="line_2"]').val(address.line_2);
                    $('#address-form').find('input[name="city"]').val(address.city);
                    $('#address-form').find('input[name="state"]').val(address.state);
                    $('#address-form').find('input[name="zip_code"]').val(address.zip_code);
                    $('#address-form').find('select[name="country"]').val(address.country);
                    $('#address-form').find('select[name="mobile_number_prefix"]').val(address.mobile_number_prefix);
                    $('#address-form').find('input[name="mobile_number"]').val(address.mobile_number);
                    $('#address-form').data('id', address.id);
                    $('#address-form').modal('show');
                } else {

                    toastr.error(data.data.errors.message);
                }
            });
        });

        $('#addresses').on('click', '.delete-address', function (e) {
            e.preventDefault();
            
            var element = $(this);
            var data = {address_id: $(this).data('id')};
            var confirmDelete = confirm('Are you sure, you want to delete this address?');

            if (confirmDelete) {

                $.when(deleteAddress(data)).then(function (data) {
                    hideLoader('body');

                    if (data.meta.success) {

                        element.closest('.address-box-container').remove();
                        if($('.address-box-container').length == 0) {
                            $('#addresses').html('<p style="text-align: center"><img src="' + Data.base_assets_url + 'images/no-data.png" alt="No data"> There are no addresses in your addressbook, please <a href="#" class="add-address primary-orange" onclick="javascript:void(0)">add an address</a>.</p>');
                        }
                        toastr.success('Address deleted successfully.');
                        $('#address-form').find('input, select').val('');
                    } else {

                        toastr.error(data.data.errors.message);
                    }
                    
                });
            }

        });

        $('#addresses').on('click', '.set-default', function (e) {
            e.preventDefault();
            
            var element = $(this);
            var data = {address_id: $(this).data('id')};
            var confirmDefault = confirm('Are you sure, you want to set this as your default address?');

            if (confirmDefault) {

                $.when(setDefaultAddress(data)).then(function (data) {
                    hideLoader('body');

                    if (data.meta.success) {

                        $('.set-default').each(function () {

                            if ($(this).hasClass('active')) {

                                $(this).removeClass('active');
                            }
                        });

                        element.addClass('active');
                        toastr.success('Default address changed.');
                    } else {

                        toastr.error(data.data.errors.message);
                    }

                });
            }

        });
    });
</script>
<?php include_once $this->getPart('/web/common/footer.php'); ?>