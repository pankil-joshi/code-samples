<div class="modal fade" tabindex="-1" role="dialog" id="address-form">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="save-address" data-parsley-validate>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><?= $addressModalType; ?> Address</h4>
                </div>
                <div class="">
                    <div class="col-md-12">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="showing_form">
                                <div class="col-md-3">
                                    <label>First name:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <input type="text" class="inpttest" value="" name="first_name" required data-parsley-required-message="Please enter first name">
                                    </div>
                                </div>
                            </div>
                            <div class="showing_form">
                                <div class="col-md-3">
                                    <label>Last name:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <input type="text" class="inpttest" value="" name="last_name" required data-parsley-required-message="Please enter last name">
                                    </div>
                                </div>
                            </div>                            
                            <div class="showing_form">
                                <div class="col-md-3">
                                    <label>Line 1:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <input type="text" class="inpttest" value="" name="line_1" required data-parsley-required-message="Please enter address line 1">
                                    </div>
                                </div>
                            </div>
                            <div class="showing_form">
                                <div class="col-md-3">
                                    <label>Line 2:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <input type="text" class="inpttest" value="" name="line_2">
                                    </div>
                                </div>
                            </div>
                            <div class="showing_form">
                                <div class="col-md-3">
                                    <label>Town/City:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <input type="text" class="inpttest" value="" name="city" required data-parsley-required-message="Please enter city/town">
                                    </div>
                                </div>
                            </div>
                            <div class="showing_form">
                                <div class="col-md-3">
                                    <label>Postcode/Zip Code:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <input type="text" class="inpttest" value="" name="zip_code" required data-parsley-required-message="Please enter zip code">
                                    </div>
                                </div>
                            </div>
                            <div class="showing_form">
                                <div class="col-md-3">
                                    <label>State:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <input type="text" class="inpttest" value="" name="state"  data-parsley-required-message="Please enter state">
                                    </div>
                                </div>
                            </div>
                            <div class="showing_form">
                                <div class="col-md-3">
                                    <label>Country:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <select class="inpttest" name="country" style="padding: 9px" required data-parsley-required-message="Please select a country">
                                            <option value="">Select country</option>
                                            <?php foreach ($countries as $code => $country): ?>
                                                <option data-dial-code="<?= $country['dial_code']; ?>" value="<?= $code; ?>" ><?= $country['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>                       
                            <div class="showing_form">
                                <div class="col-md-3">
                                    <label>Contact Number:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-3" style="padding:0">
                                            <input class="inpttest" name="mobile_number_prefix" required data-parsley-required-message="Please select a mobile number prefix" value="Dial code" readonly>
<!--                                            <select class="inpttest" name="mobile_number_prefix" style="padding: 9px" required data-parsley-required-message="Please select a mobile number prefix">
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
                                        <div style="padding:0" class="col-md-9"><input type="number" class="inpttest"  value="" name="mobile_number" required data-parsley-required-message="Please enter mobile number"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="submit_form" name="save_address" style="float:right">Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    $(document).ready(function () {

        $('select[name="country"]').change(function () {

            $('input[name="mobile_number_prefix"]').val($(this).find(':selected').data('dial-code'));
        });
    });
</script>