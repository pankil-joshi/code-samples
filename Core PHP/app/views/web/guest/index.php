<?php include_once $this->getPart('/web/common/header.php'); ?>
<div class="secure_pin">
    <h6><img src="<?= $app['base_assets_url']; ?>images/super_lock.png" class="img-resaponsive" alt="">Tagzie is in ULTRA SECURE MODE</h6>  
</div> 
<div class="desbord_body check_outstp">
    <div class="container">
        <div class="row">
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
            <div class="col-md-12 lastest_marg">
                <form method="POST" id="guest-signup" data-parsley-validate> 
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="yor_active" style="border:0">
                            <div class="order-active">
                                <div class="col-md-4 col-sm-5 col-xs-12">
                                    <h6>Personal Details</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="showing_form">
                            <div class="col-md-3">
                                <label>Title:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <select class="inpttest" name="title" style="padding: 9px" required data-parsley-required-message="Please choose title">
                                        <option value="">Choose title</option>
                                        <option value="Mr">Mr.</option>
                                        <option value="Miss">Miss</option>
                                        <option value="Mrs">Mrs.</option>
                                    </select>
                                </div>
                            </div>
                        </div>                    
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
                                <label>Email
                                    <br> Address:
                                </label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <input type="email" class="inpttest" value="<?= $_POST['email']; ?>" name="email" required data-parsley-required-message="Please enter email" data-parsley-type-message="Email must be valid">
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
                                <label>Mobile Number:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-3" style="padding:0">
<!--                                        <select class="inpttest" name="mobile_number_prefix" style="padding: 9px" required data-parsley-required-message="Please select a mobile number prefix">
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
                                                    <option value="<?= $dial_code; ?>" >Dial code</option>
                                        <?php endforeach; ?>
                                        </select>-->
                                        <input class="inpttest" name="mobile_number_prefix" required data-parsley-required-message="Please select a mobile number prefix" value="Dial code" readonly>
                                    </div>
                                    <div style="padding:0" class="col-md-9"><input type="text" class="inpttest"  data-parsley-type="digits" name="mobile_number" required data-parsley-required-message="Please enter mobile number"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12"></div>
                    <!--                <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="yor_active" style="border:0">
                                            <div class="order-active">
                                                <div class="col-md-4 col-sm-5">
                                                    <h6>Security</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="showing_form">
                                            <div class="col-md-3">
                                                <label>Current
                                                    <br> Password:
                                                </label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <input type="text" class="inpttest" alt="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="showing_form">
                                            <div class="col-md-3">
                                                <label>New
                                                    <br> Password:
                                                </label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <input type="text" class="inpttest" alt="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="showing_form">
                                            <div class="col-md-3">
                                                <label>Repeat
                                                    <br> Password:
                                                </label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <input type="text" class="inpttest" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12"></div>-->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="yor_active" style="border:0">
                            <div class="order-active">
                                <div class="col-md-4 col-sm-5">
                                    <h6>Delivery Address</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="showing_form">
                                <div class="col-md-3">
                                    <label>First name:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <input type="text" class="inpttest" value="" name="address_first_name" required data-parsley-required-message="Please enter first name">
                                    </div>
                                </div>
                            </div>
                            <div class="showing_form">
                                <div class="col-md-3">
                                    <label>Last name:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <input type="text" class="inpttest" value="" name="address_last_name" required data-parsley-required-message="Please enter last name">
                                    </div>
                                </div>
                            </div>                            
                            <div class="showing_form">
                                <div class="col-md-3">
                                    <label>Line 1:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <input type="text" class="inpttest" value="" name="address_line_1" required data-parsley-required-message="Please enter address line 1">
                                    </div>
                                </div>
                            </div>
                            <div class="showing_form">
                                <div class="col-md-3">
                                    <label>Line 2:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <input type="text" class="inpttest" value="" name="address_line_2">
                                    </div>
                                </div>
                            </div>
                            <div class="showing_form">
                                <div class="col-md-3">
                                    <label>City:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <input type="text" class="inpttest" value="" name="address_city" required data-parsley-required-message="Please enter city">
                                    </div>
                                </div>
                            </div>
                            <div class="showing_form">
                                <div class="col-md-3">
                                    <label>Zip Code/Post Code:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <input type="text" class="inpttest" value="" name="address_zip_code" required data-parsley-required-message="Please enter zip code">
                                    </div>
                                </div>
                            </div>
                            <div class="showing_form">
                                <div class="col-md-3">
                                    <label>State:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <input type="text" class="inpttest" value="" name="address_state" >
                                    </div>
                                </div>
                            </div>
                            <div class="showing_form">
                                <div class="col-md-3">
                                    <label>Country:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <select class="inpttest" name="address_country" style="padding: 9px" required data-parsley-required-message="Please select a country">
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
                                    <label>Mobile Number:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-3" style="padding:0">
<!--                                            <select class="inpttest" name="address_mobile_number_prefix" style="padding: 9px" required data-parsley-required-message="Please select a mobile number prefix">
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
                                            <input class="inpttest" name="address_mobile_number_prefix" required data-parsley-required-message="Please select a mobile number prefix" value="Dial code" readonly>
                                        </div>
                                        <div style="padding:0" class="col-md-9"><input type="text" data-parsley-type="digits" class="inpttest"  value="" name="address_mobile_number" required data-parsley-required-message="Please enter mobile number"></div>
                                    </div>
                                </div>
                            </div>
                            <div>                   
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="check_bx">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" name="terms_accepted" value="1">
                                            <div class="control__indicator"></div>
                                        </label>
                                        <span>I accept the <a href="<?= $app['base_url']; ?>legal/terms#buyer_terms"><span class="orange underline">Term & Conditions</span></a></span>
                                    </div>
                                </div>            
                            </div>                            
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12"></div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="submit_in">
                                <input type="hidden" name="submit-form" value="1">
                                <button type="submit" class="submit_form" name="save_user">Submit</button>
                            </div>  
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<!--<script type="text/javascript" src="<?= $app['base_assets_url']; ?>js/moment-timezone-with-data-2010-2020.min.js"></script>-->
<script>

    $(document).ready(function () {
//        $('input[name="timezone"]').val(moment.tz.guess());
        $('button[type="submit"]').click(function (e) {
            e.preventDefault();

            $('#guest-signup').parsley().validate();

            if ($('#guest-signup').parsley().isValid()) {

                if (!$('input[name="terms_accepted"]').is(':checked')) {

                    toastr.error('You must accept terms & conditions');
                } else {

                    $('#guest-signup').submit();
                }
            }

        });
        $('select[name="address_country"]').change(function () {

            $('input[name="address_mobile_number_prefix"]').val($(this).find(':selected').data('dial-code'));
        });

        $('select[name="country"]').change(function () {

            $('input[name="mobile_number_prefix"]').val($(this).find(':selected').data('dial-code'));
        });
    });

</script>
<?php include_once $this->getPart('/web/common/footer.php'); ?>