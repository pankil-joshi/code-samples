<?php
include_once $this->getPart('/web/admin/common/header.php');
include_once $this->getPart('/web/admin/common/sidebar.php');
?>
<div class="col-md-11 col-sm-10 col-xs-10 right_brd">
    <div class="desbord_body check_outstp admin_user_main_show">
        <div class="">
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
                <form method="POST" id="edit_customer_from"> 
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="yor_active" style="border:0">
                            <div class="order-active">
                                <div class="col-md-4 col-sm-5 col-xs-12">
                                    <h6>Edit User</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="col-md-6 col-sm-6 col-xs-12">

                        </div>
                        <div class="showing_form">
                            <div class="col-md-3">
                                <label>Title:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <select class="inpttest" name="title" style="padding: 9px">
                                        <option value="Mr" <?= ($user['title'] == 'Mr') ? 'selected' : ''; ?>>Mr.</option>
                                        <option value="Miss" <?= ($user['title'] == 'Miss') ? 'selected' : ''; ?>>Miss</option>
                                        <option value="Mrs" <?= ($user['title'] == 'Mrs') ? 'selected' : ''; ?>>Mrs.</option>
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
                                    <input type="text" class="inpttest" alt="" value="<?= $user['first_name']; ?>" name="first_name" required data-parsley-required-message="Please enter first name">
                                </div>
                            </div>
                        </div>
                        <div class="showing_form">
                            <div class="col-md-3">
                                <label>Last name:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <input type="text" class="inpttest" alt="" value="<?= $user['last_name']; ?>" name="last_name" required data-parsley-required-message="Please enter last name">
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
                                    <input type="email" class="inpttest" alt="" value="<?= $user['email']; ?>" name="email" required data-parsley-required-message="Please enter email" data-parsley-type-message="Email must be valid">
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
                                        <?php foreach ($countries as $code => $country): ?>
                                            <option data-dial-code="<?= $country['dial_code']; ?>" value="<?= $code; ?>" <?= ($user['country'] == $code) ? 'selected' : ''; ?>><?= $country['name']; ?></option>
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
    <!--                                    <select class="inpttest" name="mobile_number_prefix" style="padding: 9px" required data-parsley-required-message="Please select a mobile number prefix">
                                        <?php
                                        $dial_codes = array();
                                        foreach ($countries as $country) {

                                            if (!in_array($country['dial_code'], $dial_codes))
                                                $dial_codes[] = $country['dial_code'];
                                        }

                                        asort($dial_codes);
                                        ?>
                                        <?php foreach ($dial_codes as $dial_code): ?>
                                                                                    <option value="<?= $dial_code; ?>" <?= ($user['mobile_number_prefix'] == $dial_code) ? 'selected' : ''; ?>><?= $dial_code; ?></option>
                                        <?php endforeach; ?>
                                        </select>-->
                                        <input class="inpttest" name="mobile_number_prefix" value="<?= $user['mobile_number_prefix']; ?>" required data-parsley-required-message="Please select a mobile number prefix" value="Dial code" readonly>

                                    </div>
                                    <div style="padding:0" class="col-md-9"><input type="text" class="inpttest" alt="" value="<?= $user['mobile_number']; ?>" name="mobile_number" required data-parsley-required-message="Please enter mobile number"></div>
                                </div>
                            </div>
                        </div>
                        <div class="showing_form">
                            <div class="col-md-3">
                                <label>Age:
                                </label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <input type="number" class="inpttest" alt="" value="<?= $user['age']; ?>" name="age" data-parsley-required-message="Please enter age">
                                </div>
                            </div>
                        </div>                        
                        <!--                        <div class="showing_form">
                                                    <div class="col-md-3">
                                                        <label>Date of
                                                            <br> Birth:
                                                        </label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="row">
                                                            <input type="text" data-provide="datepicker" class="inpttest" alt="" value="<?= $user['date_of_birth']; ?>" name="date_of_birth" data-parsley-required-message="Please select birthdate">
                                                        </div>
                                                    </div>
                                                </div>-->
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="showing_form">
                            <div class="col-md-6">
                                <label>Accept Promotional Mails:
                                </label>
                            </div>
                            <div class="col-md-5">
                                <div class="row">
                                    <input type="radio" class="" alt="" value="1" name="accept_promotional_mails" <?= ($user['accept_promotional_mails']) ? 'checked' : ''; ?>> Yes &nbsp;
                                    <input type="radio" class="" alt="" value="0" name="accept_promotional_mails" <?= (!$user['accept_promotional_mails']) ? 'checked' : ''; ?>> No
                                </div>
                            </div>
                        </div>
                        <div class="showing_form">
                            <div class="col-md-6">
                                <label>Accept Thirdparty Mails:
                                </label>
                            </div>
                            <div class="col-md-5">
                                <div class="row">
                                    <input type="radio" class="" alt="" value="1" name="accept_thirdparty_mails" <?= ($user['accept_thirdparty_mails']) ? 'checked' : ''; ?>> Yes &nbsp;
                                    <input type="radio" class="" alt="" value="0" name="accept_thirdparty_mails" <?= (!$user['accept_thirdparty_mails']) ? 'checked' : ''; ?>> No
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="submit_in">
                                <button type="submit" href="" class="submit_form" name="save_user">Update</button>
<!--                                <a type="button" href="<?= $app['base_url']; ?>admin/customers/" class="submit_form">Cancel</a>-->
                            </div>  
                        </div>
                    </div>
                </form>
                <?php if ($user['is_merchant']): ?>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="yor_active" style="border:0">
                            <div class="order-active">
                                <div class="col-md-4 col-sm-5 col-xs-12">
                                    <h6>Merchant Details</h6>
                                </div>
                            </div>
                        </div>
                    </div>                    
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="col-md-6 col-sm-6 col-xs-12">

                        </div>
                        <div class="showing_form">
                            <div class="col-md-3">
                                <label>Subscription Plan:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <input class="inpttest" alt="" value="<?= $user['subscription_packages_name']; ?>" readonly="readonly" id="change-subscription">
                                </div>
                            </div>
                        </div> 
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($user['is_merchant']): ?>  

                <div class="col-md-12">
                    <div class="col-xs-12">
                        <div class="down_tab">
                            <div id="exTab2" class="busnis_detil">
                                <ul class="nav nav-tabs tab_set_degn">
                                    <li class="active " style="padding: 0;">
                                        <a href="javascript:void;" data-tab="business-details" class="tab-link">1</a>
                                    </li>
                                    <li class="" style="padding: 0;">
                                        <a href="javascript:void;" data-tab="business-address" class="tab-link">2</a>
                                    </li>
                                    <li class="" style="padding: 0;">
                                        <a href="javascript:void;" data-tab="personal-details" class="tab-link">3</a>
                                    </li>
                                    <li class="" style="padding:0;">
                                        <a href="javascript:void;" data-tab="bank-details" class="tab-link">4</a>
                                    </li>                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 merchant-registration-fields">
                    <form id="merchant-registration-form" autocomplete="off">
                        <div class="row tab"  id="business-details" data-parsley-validate>
                            <div class="col-md-6 col-sm-12 col-xs-12 busness_inner_detail"> 
                                <div class="col-md-12 col-sm-12 col-xs-12" style="padding-right:0">                  
                                    <div class="col-md-12 buseind_detil">
                                        <h1>BUSINESS DETAILS</h1></div>
                                    <div class="showing_form">
                                        <div class="col-md-3">
                                            <label>Instagram Username:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input type="text" class="form-control" value="@<?= $user['instagram_username']; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>                      
                                    <div class="showing_form">
                                        <div class="col-md-3">
                                            <label>Business Name:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input id="businessName" required data-parsley-required-message="Please enter business name" name="legal_entity_business_name" type="text" value="<?= $merchant['legal_entity_business_name']; ?>" class="form-control" placeholder="eg. Abc limited">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="showing_form stripe-field" style="display: none">
                                        <div class="col-md-3">
                                            <label>Business Name(kana):</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input id="businessName" required data-parsley-required-message="Please enter business name" name="legal_entity_business_name_kana" type="text" value="<?= $merchant['legal_entity_business_name_kana']; ?>" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="showing_form stripe-field" style="display: none">
                                        <div class="col-md-3">
                                            <label>Business Name(kanji):</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input id="businessName" required data-parsley-required-message="Please enter business name" name="legal_entity_business_name_kanji" type="text" value="<?= $merchant['legal_entity_business_name_kanji']; ?>" class="form-control">
                                            </div>
                                        </div>
                                    </div>                    
                                    <div class="showing_form">
                                        <div class="col-md-3">
                                            <label>Select Entity:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <select class="form-control frid_boeder choice" id="companyStatus" required data-parsley-required-message="Select business entity type" name="legal_entity_type">
                                                    <option value="">Select Entity...</option>
                                                    <option <?= $merchant['business_status'] == 'Sole Trader' ? 'selected' : ''; ?> value="Sole Trader">Sole Trader</option>
                                                    <option <?= $merchant['business_status'] == 'Limited Company' ? 'selected' : ''; ?> value="Limited Company">Limited Company</option>
                                                    <option <?= $merchant['business_status'] == 'PLC' ? 'selected' : ''; ?> value="PLC">PLC</option>
                                                    <option <?= $merchant['business_status'] == 'Others' ? 'selected' : ''; ?> value="Others">Others</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="showing_form">
                                        <div class="col-md-3">
                                            <label>Registration No.<span> (if applicable):</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input id="companyRegistrationNumber" name="business_registration_number" type="text" value="<?= $merchant['business_registration_number']; ?>" class="form-control" placeholder="eg. REG-XXXXXX">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="showing_form">
                                        <div class="col-md-3">
                                            <label>Email Address:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input type="email" id="businessEmail" required data-parsley-required-message="Please enter email" data-parsley-type-message="Email must be valid" name="business_email" type="text" value="<?= $merchant['business_email']; ?>" class="form-control" placeholder="eg. example@abc.com"> </div>
                                        </div>
                                    </div>
                                    <div class="showing_form">
                                        <div class="col-md-3">
                                            <label>Website:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input type="url" id="businessWebsite" data-parsley-type-message="Website URL must be valid" name="business_website" type="text" value="<?= $merchant['business_website']; ?>" class="form-control" placeholder="eg. www.abc.com">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="showing_form">
                                        <div class="col-md-3">
                                            <label>Business Category:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">

                                                <select class="form-control choice" id="businessCategory" required data-parsley-required-message="Please select category." name="business_category">
                                                    <option value="">Business Category</option>
                                                    <?php foreach ($parent_categories as $category): ?>
                                                        <option <?= $merchant['business_category'] == $category['label'] ? 'selected' : ''; ?> value="<?= $category['label']; ?>"><?= $category['label']; ?></option>
                                                    <?php endforeach; ?>
                                                </select></div>
                                        </div>
                                    </div>
                                    <div class="showing_form">
                                        <div class="col-md-3">
                                            <label>Business Country:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <select required data-parsley-required-message="Please select a country" id="businessCountry" name="business_country" class="form-control choice">
                                                    <option value="">Choose country</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="showing_form">
                                        <div class="col-md-3">
                                            <label>Business Currency:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input type="text"  id="businessCurrency" name="business_currency" value="<?= $merchant['business_currency']; ?>" class="form-control" readonly placeholder="eg. GBP">                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="showing_form tax_enabled_field" style="display: none">
                                        <div class="col-md-3">
                                            <label>Are you tax registered?</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <label class="radio font3"> 
                                                    <input type="radio" name='tax_enabled' class="tax_enabled" value='1' <?= $merchant['tax_enabled'] == 1 ? 'checked' : ''; ?> />
                                                    <span class="outer"><span class="inner"></span></span> Yes
                                                </label>
                                                <label class="radio font3"> 
                                                    <input type="radio" name='tax_enabled' class="tax_enabled" value='0' <?= $merchant['tax_enabled'] == 0 ? 'checked' : ''; ?> />
                                                    <span class="outer"><span class="inner"></span></span> No
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="showing_form tax_field" style="display: <?= $merchant['tax_enabled'] == 1 ? 'show' : 'none'; ?>">
                                        <div class="col-md-3">
                                            <label>Tax ID:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input id="taxId" name="legal_entity_business_tax_id" data-parsley-required-message="Please enter tax ID" type="text" value="<?= $merchant['legal_entity_business_tax_id']; ?>" class="form-control" placeholder="eg. TIN-XXXXXX"></div>
                                        </div>
                                    </div>
                                    <div class="showing_form">
                                        <div class="col-md-3">
                                            <label>Business Telephone:</label>
                                        </div>
                                        <div class="col-md-8">

                                            <div class="row">
                                                <div style="padding:0" class="col-md-3 col-sm-9 col-xs-3">
                                                    <input id="dialCode" readonly style="padding: 9px" name="business_telephone_prefix" value="<?= $merchant['business_telephone_prefix']; ?>" class="form-control" placeholder="eg. +44">
                                                </div>
                                                <div class="col-md-9 col-sm-9 col-xs-9" style="padding:0"><input type="text" required data-parsley-required-message="Please enter mobile number" data-parsley-type="digits" name="legal_entity_phone_number" value="<?= $merchant['legal_entity_phone_number']; ?>"  alt="" class="form-control" placeholder="eg. XXXXXXXXX"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="showing_form stripe-field" style="display: none">
                                        <div class="col-md-3">
                                            <label>Add additional owners:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <button class="btn btn-size col-xs-12" type="button"><i class="fa fa-plus plus_icon"></i> Add  </button></div>
                                        </div>
                                    </div>
                                    <div class="showing_form">
                                        <div class="col-md-12"> <div class="col-md-12">
                                                <div class="row">
                                                    <div class="proceed_btn">
                                                        <button class="tab-link submit_form" data-tab="business-address" data-effect="slide" type="button"> Proceed  </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>            
                                </div>
                            </div>
                        </div>
                        <div class="row tab" id="business-address" style="display: none" data-parsley-validate>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="col-md-12 col-sm-12 col-xs-12" style="padding-right:0">
                                    <div class="col-md-12 buseind_detil"><h1>BUSINESS ADDRESS</h1></div>
                                    <div class="showing_form stripe-field" style="display: none">
                                        <div class="col-md-3">
                                            <label>Address Line 1:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input id="businessAddressLine1" required data-parsley-required-message="Please enter address line 1" name="legal_entity_address_line1" value="<?= $merchant['legal_entity_address_line1']; ?>" type="text" class="form-control" placeholder="e.g. 2601/2 none-C">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="showing_form stripe-field" style="display: none">
                                        <div class="col-md-3">
                                            <label>Town / City:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input id="businessAddressCity" required data-parsley-required-message="Please enter city" name="legal_entity_address_city" value="<?= $merchant['legal_entity_address_city']; ?>" type="text" class="form-control" placeholder="e.g. Downtown Square"> </div>
                                        </div>
                                    </div>
                                    <div class="showing_form stripe-field"  style="display: none">
                                        <div class="col-md-3">
                                            <label>Region / State:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row business-state">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="showing_form stripe-field"  style="display: none">
                                        <div class="col-md-3">
                                            <label>Postcode / Zip Code:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input id="businessAddressPostcode" required data-parsley-required-message="Please enter zip code" name="legal_entity_address_postal_code" value="<?= $merchant['legal_entity_address_postal_code']; ?>" type="text" class="form-control" placeholder="e.g. 10014"> </div>
                                        </div>
                                    </div>
                                    <div class="japan-fields stripe-field"  style="display: none">
                                        <div class="col-xs-12">
                                            <label><b><u>Business Address(KANA):</u></b></label>
                                        </div>

                                        <div class="showing_form">
                                            <div class="col-md-3">
                                                <label>Address Line 1:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <input id="kanaPersonalAddressLine1" data-parsley-required-message="Please enter address line 1" name="legal_entity_address_kana_line1" value="<?= $merchant['legal_entity_address_kana_line1']; ?>" type="text" class="form-control" placeholder="e.g. 1-5-2"> </div>
                                            </div>
                                        </div>
                                        <div class="showing_form">
                                            <div class="col-md-3">
                                                <label> City:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <input id="kanaPersonalAddressCity" data-parsley-required-message="Please enter city" name="legal_entity_address_kana_city" value="<?= $merchant['legal_entity_address_kana_city']; ?>" type="text" class="form-control" placeholder="e.g. Tokyo"> </div>
                                            </div>
                                        </div>
                                        <div class="showing_form">
                                            <div class="col-md-3">
                                                <label>Region / State:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <input id="kanaPersonalAddressState" data-parsley-required-message="Please enter region/state" name="legal_entity_address_kana_state" value="<?= $merchant['legal_entity_address_kana_state']; ?>" type="text" class="form-control" placeholder="e.g. Japan">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="showing_form">
                                            <div class="col-md-3">
                                                <label>Postcode / Zip Code:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <input id="kanaPersonalAddressPostcode" data-parsley-required-message="Please enter postcode/zip code" name="legal_entity_address_kana_postal_code" value="<?= $merchant['legal_entity_address_kana_postal_code']; ?>" type="text" class="form-control" placeholder="e.g. 105-7123"></div>
                                            </div>
                                        </div>

                                        <div class="showing_form">
                                            <div class="col-md-3">
                                                <label>Town:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <input id="kanaPersonalAddressTown" data-parsley-required-message="Please enter town" name="legal_entity_address_kana_town" value="<?= $merchant['legal_entity_address_kana_town']; ?>" type="text" class="form-control" placeholder="e.g. Karakura Town"></div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12">
                                            <label><b><u>Business Address(KANJI):</u></b></label>
                                        </div>                     
                                        <div class="showing_form">
                                            <div class="col-md-3">
                                                <label>Address Line 1:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <input id="kanjiPersonalAddressLine1" data-parsley-required-message="Please enter address line 1" name="legal_entity_address_kanji_line1" value="<?= $merchant['legal_entity_address_kanji_line1']; ?>" type="text" class="form-control" placeholder="e.g. 1-5-2"> </div>
                                            </div>
                                        </div>
                                        <div class="showing_form">
                                            <div class="col-md-3">
                                                <label> City:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <input id="kanjiPersonalAddressCity" data-parsley-required-message="Please enter city" name="legal_entity_address_kanji_city" value="<?= $merchant['legal_entity_address_kanji_city']; ?>" type="text" class="form-control" placeholder="e.g. Tokyo"></div>
                                            </div>
                                        </div>
                                        <div class="showing_form">
                                            <div class="col-md-3">
                                                <label>Region / State:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <input id="kanjiPersonalAddressState" data-parsley-required-message="Please enter region/state" name="legal_entity_address_kanji_state" value="<?= $merchant['legal_entity_address_kanji_state']; ?>" type="text" class="form-control" placeholder="e.g. Japan">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="showing_form">
                                            <div class="col-md-3">
                                                <label>Postcode / Zip Code:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <input id="kanjiPersonalAddressPostcode" data-parsley-required-message="Please enter postcode/zip code" name="legal_entity_address_kanji_postal_code" value="<?= $merchant['legal_entity_address_kanji_postal_code']; ?>" type="text" class="form-control" placeholder="e.g. 105-7123"></div>
                                            </div>
                                        </div>

                                        <div class="showing_form">
                                            <div class="col-md-3">
                                                <label>Town:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <input id="kanjiPersonalAddressTown" data-parsley-required-message="Please enter town"name="legal_entity_address_kanji_town" value="<?= $merchant['legal_entity_address_kanji_town']; ?>" type="text" class="form-control" placeholder="e.g. Karakura Town"></div>
                                            </div>
                                        </div>
                                    </div>                     
                                    <div class="showing_form">                    
                                        <div class="col-md-12">
                                            <div class="proceed_btn">
                                                <button class="col-xs-12 tab-link submit_form sapace_back_btn" type="button" data-tab="business-details" data-validate="0"> Back  </button>
                                            </div>
                                            <div class="proceed_btn">
                                                <button class="col-xs-12 tab-link submit_form" data-tab="personal-details" data-effect="slide" type="button"> Proceed  </button>
                                            </div>
                                        </div>
                                    </div>             
                                </div>
                            </div>
                        </div>
                        <div class="row tab" id="personal-details" style="display: none" data-parsley-validate>

                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="col-md-12 col-sm-12 col-xs-12" style="padding-right:0">
                                    <div class="col-md-12 buseind_detil"> <h1>PERSONAL DETAILS</h1></div>
                                    <div class="showing_form stripe-field" style="display: none">
                                        <div class="col-md-3">
                                            <label>First name:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input id="firstName" required data-parsley-required-message="Please enter first name" name="legal_entity_first_name" value="<?= $merchant['legal_entity_first_name']; ?>" type="text" class="form-control" placeholder="e.g. John"> </div>
                                        </div>
                                    </div>
                                    <div class="showing_form stripe-field" style="display: none">
                                        <div class="col-md-3">
                                            <label>Last name:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input id="lastName" required data-parsley-required-message="Please enter last name" name="legal_entity_last_name" value="<?= $merchant['legal_entity_last_name']; ?>" type="text" class="form-control" placeholder="e.g. Smith"></div>
                                        </div>
                                    </div>
                                    <div class="showing_form stripe-field" style="display: none">
                                        <div class="col-md-3">
                                            <label>First name(kana):</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input required data-parsley-required-message="Please enter first name" name="legal_entity_first_name_kana" value="<?= $merchant['legal_entity_first_name_kana']; ?>" type="text" class="form-control" placeholder="e.g. John"> </div>
                                        </div>
                                    </div>
                                    <div class="showing_form stripe-field" style="display: none">
                                        <div class="col-md-3">
                                            <label>Last name(kana):</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input required data-parsley-required-message="Please enter last name" name="legal_entity_last_name_kana" value="<?= $merchant['legal_entity_last_name_kana']; ?>" type="text" class="form-control" placeholder="e.g. Smith"></div>
                                        </div>
                                    </div>
                                    <div class="showing_form stripe-field" style="display: none">
                                        <div class="col-md-4">
                                            <label>First name(kanji):</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input required data-parsley-required-message="Please enter first name" name="legal_entity_first_name_kanji" value="<?= $merchant['legal_entity_first_name_kanji']; ?>" type="text" class="form-control" placeholder="e.g. John"> </div>
                                        </div>
                                    </div>
                                    <div class="showing_form stripe-field" style="display: none">
                                        <div class="col-md-3">
                                            <label>Last name(kanji):</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input id="lastName" required data-parsley-required-message="Please enter last name" name="legal_entity_last_name_kanji" value="<?= $merchant['legal_entity_last_name_kanji']; ?>" type="text" class="form-control" placeholder="e.g. Smith"></div>
                                        </div>
                                    </div>                      
                                    <div class="showing_form stripe-field" style="display: none">
                                        <div class="col-md-3">
                                            <label>Address Line 1:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input id="residentialAddressLine1" required data-parsley-required-message="Please enter address line 1" name="legal_entity_personal_address_line1" value="<?= $merchant['legal_entity_personal_address_line1']; ?>" type="text" class="form-control" placeholder="e.g. 2601/2 none-C"> </div>
                                        </div>
                                    </div>
                                    <div class="showing_form stripe-field" style="display: none">
                                        <div class="col-md-3">
                                            <label>Town / City:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input id="residentialAddressCity" required data-parsley-required-message="Please enter city/town" name="legal_entity_personal_address_city" value="<?= $merchant['legal_entity_personal_address_city']; ?>" type="text" class="form-control" placeholder="e.g. Downtown Square"></div>
                                        </div>
                                    </div>
                                    <div class="showing_form stripe-field"  style="display: none">
                                        <div class="col-md-3">
                                            <label>Region / State:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row personal-state">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="showing_form stripe-field"  style="display: none">
                                        <div class="col-md-3">
                                            <label>Postcode / Zip Code:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input id="residentialAddressPostcode" required data-parsley-required-message="Please enter postcode/zip code" name="legal_entity_personal_address_postal_code" value="<?= $merchant['legal_entity_personal_address_postal_code']; ?>" type="text" class="form-control" placeholder="e.g. 10014" > </div>
                                        </div>
                                    </div>
                                    <div class="showing_form">
                                        <div class="col-md-3">
                                            <label>Date of Birth:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input type="tel" id="personalDateOfBirth" required data-parsley-required-message="Please enter date of birth" name="legal_entity_dob" value="<?= $merchant['legal_entity_dob_day'] . '-' . $merchant['legal_entity_dob_month'] . '-' . $merchant['legal_entity_dob_year']; ?>" type="text" class="form-control" placeholder="e.g. 01-01-1985"></div>
                                        </div>
                                    </div>

                                    <div class="showing_form stripe-field"  style="display: none">
                                        <div class="col-md-3">
                                            <label>Gender:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <select id="gender" required data-parsley-required-message="Please select gender" name="legal_entity_gender" class="form-control">
                                                    <option value="">Choose...</option>
                                                    <option <?= $merchant['legal_entity_gender'] == 'male' ? 'selected' : ''; ?> value="male">Male</option>
                                                    <option <?= $merchant['legal_entity_gender'] == 'female' ? 'selected' : ''; ?> value="female">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="showing_form stripe-field"  style="display: none">
                                        <div class="col-md-3">
                                            <label>SSN (Last 4):</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input id="ssnNumber" data-parsley-required-message="Please enter ssn number" name="legal_entity_ssn_last_4" value="<?= $merchant['legal_entity_ssn_last_4']; ?>"  type="text" class="form-control" placeholder="e.g. ******1234"></div>
                                        </div>
                                    </div>
                                    <div class="showing_form stripe-field"  style="display: none">
                                        <div class="col-md-3">
                                            <label>Personal ID number:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input id="personalId" data-parsley-required-message="Please enter personal ID" name="legal_entity_personal_id_number" value="<?= $merchant['legal_entity_personal_id_number']; ?>"  type="text" class="form-control" placeholder="e.g. XXXXXXX"></div>
                                        </div>
                                    </div>
                                    <!-- Japanese Personal detail fields -->
                                    <div class="japan-fields stripe-field"  style="display: none">
                                        <div class="col-xs-12">
                                            <label><b><u>Personal Details(KANA):</u></b></label>
                                        </div>

                                        <div class="showing_form">
                                            <div class="col-md-4">
                                                <label>Address Line 1:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <input id="kanaPersonalAddressLine1" data-parsley-required-message="Please enter address line 1" name="legal_entity_personal_address_kana_line1" value="<?= $merchant['legal_entity_personal_address_kana_line1']; ?>" type="text" class="form-control" placeholder="e.g. 1-5-2"> </div>
                                            </div>
                                        </div>
                                        <div class="showing_form">
                                            <div class="col-md-4">
                                                <label> City:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <input id="kanaPersonalAddressCity" data-parsley-required-message="Please enter city" name="legal_entity_personal_address_kana_city" value="<?= $merchant['legal_entity_personal_address_kana_city']; ?>" type="text" class="form-control" placeholder="e.g. Tokyo"> </div>
                                            </div>
                                        </div>
                                        <div class="showing_form">
                                            <div class="col-md-4">
                                                <label>Region / State:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <input id="kanaPersonalAddressState" data-parsley-required-message="Please enter region/state" name="legal_entity_personal_address_kana_state" value="<?= $merchant['legal_entity_personal_address_kana_state']; ?>" type="text" class="form-control" placeholder="e.g. Japan">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="showing_form">
                                            <div class="col-md-4">
                                                <label>Postcode / Zip Code:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <input id="kanaPersonalAddressPostcode" data-parsley-required-message="Please enter postcode/zip code" name="legal_entity_personal_address_kana_postal_code" value="<?= $merchant['legal_entity_personal_address_kana_postal_code']; ?>" type="text" class="form-control" placeholder="e.g. 105-7123"></div>
                                            </div>
                                        </div>

                                        <div class="showing_form">
                                            <div class="col-md-4">
                                                <label>Town:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <input id="kanaPersonalAddressTown" data-parsley-required-message="Please enter town" name="legal_entity_personal_address_kana_town" value="<?= $merchant['legal_entity_personal_address_kana_town']; ?>" type="text" class="form-control" placeholder="e.g. Karakura Town"></div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12">
                                            <label><b><u>Personal Details(KANJI):</u></b></label>
                                        </div>                      
                                        <div class="showing_form">
                                            <div class="col-md-4">
                                                <label>Address Line 1:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <input id="kanjiPersonalAddressLine1" data-parsley-required-message="Please enter address line 1" name="legal_entity_personal_address_kanji_line1" value="<?= $merchant['legal_entity_personal_address_kanji_line1']; ?>" type="text" class="form-control" placeholder="e.g. 1-5-2"> </div>
                                            </div>
                                        </div>
                                        <div class="showing_form">
                                            <div class="col-md-4">
                                                <label> City:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <input id="kanjiPersonalAddressCity" data-parsley-required-message="Please enter city" name="legal_entity_personal_address_kanji_city" value="<?= $merchant['legal_entity_personal_address_kanji_city']; ?>" type="text" class="form-control" placeholder="e.g. Tokyo"></div>
                                            </div>
                                        </div>
                                        <div class="showing_form">
                                            <div class="col-md-4">
                                                <label>Region / State:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <input id="kanjiPersonalAddressState" data-parsley-required-message="Please enter region/state" name="legal_entity_personal_address_kanji_state" value="<?= $merchant['legal_entity_personal_address_kanji_state']; ?>" type="text" class="form-control" placeholder="e.g. Japan">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="showing_form">
                                            <div class="col-md-4">
                                                <label>Postcode / Zip Code:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <input id="kanjiPersonalAddressPostcode" data-parsley-required-message="Please enter postcode/zip code" name="legal_entity_personal_address_kanji_postal_code" value="<?= $merchant['legal_entity_personal_address_kanji_postal_code']; ?>" type="text" class="form-control" placeholder="e.g. 105-7123"></div>
                                            </div>
                                        </div>

                                        <div class="showing_form">
                                            <div class="col-md-4">
                                                <label>Town:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <input id="kanjiPersonalAddressTown" data-parsley-required-message="Please enter town"name="legal_entity_personal_address_kanji_town" value="<?= $merchant['legal_entity_personal_address_kanji_town']; ?>" type="text" class="form-control" placeholder="e.g. Karakura Town"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="showing_form">                  
                                        <div class="col-md-12">
                                            <div class="proceed_btn">
                                                <button class="col-xs-12 tab-link submit_form sapace_back_btn" type="button" data-tab="business-address" data-validate="0">Back</button>
                                            </div>
                                            <div class="proceed_btn">
                                                <button class="col-xs-12 tab-link submit_form" data-tab="bank-details" data-effect="slide" type="button">Proceed</button>
                                            </div>

                                        </div>
                                    </div>              
                                </div>    
                            </div>    
                        </div>

                        <div class="row tab" id="bank-details" style="display: none" data-parsley-validate>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="col-md-12 col-sm-12 col-xs-12" style="padding-right:0">
                                    <div class="col-md-12 buseind_detil"> <h1>BANK DETAILS</h1></div>
                                    <div class="showing_form">
                                        <div class="col-md-3">
                                            <label>Bank Name:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input id="businessBankName" name="bank_name" value="<?= $merchant['bank_name']; ?>" required data-parsley-required-message="Please enter bank name" type="text" class="form-control" placeholder="eg. HSBC">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="showing_form">
                                        <div class="col-md-3">
                                            <label>Account Number / IBAN:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="row">
                                                        <select class="form-control" id="accountNumberType">
                                                            <option <?= !empty($merchant['bank_sort_code']) ? 'selected' : ''; ?> value="account_number">Account Number</option>
                                                            <option <?= empty($merchant['bank_sort_code']) ? 'selected' : ''; ?> value="iban">IBAN</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-7" style="padding-right:0">
                                                    <input id="businessBankAccountNumber" name="bank_account_number" value="<?= $merchant['bank_account_number']; ?>" required data-parsley-required-message="Please enter your bank account number" type="number" class="form-control" placeholder="eg. XXXXXXXXX"> 
                                                    <input id="businessBankIBAN" name="bank_account_number" value="<?= $merchant['bank_account_number']; ?>" data-parsley-required-message="Please enter your IBAN number" type="text" class="form-control" placeholder="eg. GBXXXXXXXXX" disabled="disabled" style="display: none;"></div> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="showing_form">
                                        <div class="col-md-3">
                                            <label>Sort Code / Routing Number:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input id="businessSortCode" name="bank_sort_code" value="<?= $merchant['bank_sort_code']; ?>" data-parsley-required-message="Please enter your bank sort code or routing number" type="text" class="form-control" required placeholder="eg. XXXX-XXXX"> </div>
                                        </div>
                                    </div>                    
                                    <!--                    <div class="showing_form">
                                                            <div class="col-md-4">
                                                                <label>IBAN:</label>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <div class="row">
                                                                    <input id="ibanNumber" name="bank_iban" type="text" class="form-control"> </div>
                                                            </div>
                                                        </div>-->
                                    <div class="showing_form">
                                        <div class="col-md-3">
                                            <label>Swift (optional):</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input id="swiftCode" name="bank_swift" value="<?= $merchant['bank_swift']; ?>" type="text" class="form-control">  </div>
                                        </div>
                                    </div>

                                    <div class="showing_form">
                                        <div class="col-md-3">
                                            <label>Bank Country:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <select disabled required data-parsley-required-message="Please select a country" id="bankCountry" style="padding: 9px" name="bank_country" class="form-control" readonly>
                                                    <option value="">Choose country</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="showing_form">
                                        <div class="col-md-3">
                                            <label>Bank Currency:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <input required data-parsley-required-message="Please select bank currency" id="bankCurrency" type="text" class="form-control" name="bank_currency" value="<?= $merchant['bank_currency']; ?>" readonly placeholder="eg. GBP">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="showing_form">                   
                                        <div class="col-md-12">
                                            <div class="proceed_btn">
                                                <button class="col-xs-12 tab-link submit_form sapace_back_btn" type="button" data-tab="personal-details" data-validate="0"> Back  </button>
                                            </div>
                                            <div class="proceed_btn">
                                                <button class="col-xs-12 submit_form" type="button" id="update_details"> Update  </button>
                                            </div>

                                        </div>
                                    </div>          
                                </div>    
                            </div>    
                        </div>
                    </form>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include_once $this->getPart('/web/admin/components/switch-plan.php'); ?>
<?php include_once $this->getPart('/web/admin/common/footer.php'); ?>
<script>
    $(document).ready(function () {
        $('#edit_customer_from').parsley();
        $('.datepicker').datepicker();

        $('#change-subscription').click(function () {

            $('#switch-plan-modal').modal('show');
        });

        $('.switch-plan-button').click(function (e) {
            e.preventDefault();
            var data = {};
            var formData = $('#switch-plan-form').serializeObject();

            data.body = formData;
            data.user_id = $('#switch-plan-modal').data('user-id');

            $.when(changeSubscriptionPlanByAdmin(data)).then(function (data) {

                hideLoader('#switch-plan-modal .modal-content');
                if (data.meta.success) {

                    $('#change-subscription').val(data.data.subscription.plan_name);
                    $('#switch-plan-modal').modal('hide');
                    toastr.success('Plan changed successfully!');
                }
            });
        });
    });
</script>


<script src="<?= $app['base_assets_url']; ?>js/jquery.mask.js" type="text/javascript"></script>
<script>

    var stripeCountries = [
        {
            "name": "Austria",
            "dial_code": "+43",
            "code": "AT",
            "currency_code": "EUR",
        }, {
            "name": "Australia",
            "dial_code": "+61",
            "code": "AU",
            "currency_code": "AUD",
        }, {
            "name": "Belgium",
            "dial_code": "+32",
            "code": "BE",
            "currency_code": "EUR",
        }, {
            "name": "Canada",
            "dial_code": "+1",
            "code": "CA",
            "currency_code": "CAD",
        }, {
            "name": "Germany",
            "dial_code": "+49",
            "code": "DE",
            "currency_code": "EUR",
        }, {
            "name": "Denmark",
            "dial_code": "+45",
            "code": "DK",
            "currency_code": "DKK",
        }, {
            "name": "Spain",
            "dial_code": "+34",
            "code": "ES",
            "currency_code": "EUR",
        }, {
            "name": "Finland",
            "dial_code": "+358",
            "code": "FI",
            "currency_code": "EUR",
        }, {
            "name": "France",
            "dial_code": "+33",
            "code": "FR",
            "currency_code": "EUR",
        }, {
            "name": "United Kingdom",
            "dial_code": "+44",
            "code": "GB",
            "currency_code": "GBP",
        }, {
            "name": "Hong Kong",
            "dial_code": "+852",
            "code": "HK",
            "currency_code": "HKD",
        }, {
            "name": "Ireland",
            "dial_code": "+353",
            "code": "IE",
            "currency_code": "EUR",
        }, {
            "name": "Italy",
            "dial_code": "+39",
            "code": "IT",
            "currency_code": "EUR",
        }
//        , {
//            "name": "Japan",
//            "dial_code": "+81",
//            "code": "JP",
//            "currency_code": "JPY",
//        }
        ,
        {
            "name": "Luxembourg",
            "dial_code": "+352",
            "code": "LU",
            "currency_code": "EUR",
        }, {
            "name": "Netherlands",
            "dial_code": "+31",
            "code": "NL",
            "currency_code": "EUR",
        }, {
            "name": "Norway",
            "dial_code": "+47",
            "code": "NO",
            "currency_code": "NOK",
        }, {
            "name": "Portugal",
            "dial_code": "+351",
            "code": "PT",
            "currency_code": "EUR",
        }, {
            "name": "Sweden",
            "dial_code": "+46",
            "code": "SE",
            "currency_code": "SEK",
        }, {
            "name": "Singapore",
            "dial_code": "+65",
            "code": "SG",
            "currency_code": "SGD",
        }, {
            "name": "United States",
            "dial_code": "+1",
            "code": "US",
            "currency_code": "USD",
        }
    ];
    
    stripeCountries.sort(function (a, b) {
        return a.name.localeCompare(b.name);
    });

    $(document).ready(function () {

        $('#accountNumberType').change(function () {
            var currentTab = $('.tab:visible').attr('id');
            $('#' + currentTab).parsley().destroy();
            if ($(this).val() == 'iban') {
                $('#businessBankAccountNumber').prop('disabled', true).prop('required', false).hide();
                $('#businessBankIBAN').prop('disabled', false).prop('required', true).show();
                $('input[name="bank_sort_code"]').val('');
                $('input[name="bank_sort_code"]').prop('required', false);
                $('input[name="bank_sort_code"]').closest('.showing_form').hide();
            } else {
                $('#businessBankIBAN').prop('disabled', true).prop('required', false).hide();
                $('#businessBankAccountNumber').prop('disabled', false).prop('required', true).show();
                $('input[name="bank_sort_code"]').attr('required', true);
                $('input[name="bank_sort_code"]').closest('.showing_form').show();
            }
            $('#' + currentTab).parsley();

        });

        $('#personalDateOfBirth').mask("00-00-0000");
        $('#update_details').click(function () {

            var currentTab = $('.tab:visible').attr('id');
            $('#' + currentTab).parsley().validate();

            if ($('#' + currentTab).parsley().isValid()) {

                var data = {};
                var formData = $('#merchant-registration-form').serializeObject();
                formData.user_id = '<?= $merchant['user_id']; ?>';
                formData.bank_currency = $('#bankCurrency').val();
                formData.business_currency = $('#businessCurrency').val();
                formData.bank_country = $('#bankCountry').val();
                formData.business_telephone_prefix = $('#dialCode').val();
                formData.business_status = $('#companyStatus').val();
                var legalEntityDob = $('#personalDateOfBirth').val().split('-');
                formData.legal_entity_dob_day = legalEntityDob[0];
                formData.legal_entity_dob_month = legalEntityDob[1];
                formData.legal_entity_dob_year = legalEntityDob[2];
                if ($('#companyStatus').val() == "Sole Trader") {
                    legalEntityType = "individual";
                } else {
                    legalEntityType = "company";
                }
                formData.legal_entity_type = legalEntityType;

                data.body = formData;

                $.when(updateMerchant(data)).then(function (data) {
                    hideLoader('.merchant-registration-fields');
                    if (data.meta.success) {

                        toastr.success('Merchant details updated successfully');
                        $('a[data-tab="business-details"]').trigger('click');
                    }
                });
            }

        });

        var countryOptions = '';
        var merchant = '<?= $user['is_merchant']; ?>';

        $(stripeCountries).each(function (index, item) {

            if (merchant == 1 && item.code == '<?= $merchant['business_country']; ?>') {

                countryOptions = '<option selected value="' + item.code + '" data-currency-code="' + item.currency_code + '" data-dial-code="' + item.dial_code + '">' + item.name + '</options>';
                return;
            }
        });

        $('#businessCountry').append(countryOptions);
        $(window).load(function () {
            $('#businessCountry').trigger('change');
            $('#accountNumberType').trigger('change');
            $('.business-state  option[value="<?= $merchant['legal_entity_address_state']; ?>').prop("selected", true);
        })

        $('#bankCountry').append(countryOptions);
        $('#companyStatus').change(function () {
            $('#businessCountry').trigger('change');
        });
//        $('#bankCountry').change(function () {
//            if ($(this).val() != '') {
//
//                var bankCurrencyOption = '<option value="' + $(this).find(':selected').data('currency-code') + '">' + $(this).find(':selected').data('currency-code') + '</option>';
//                $('#bankCurrency').html(bankCurrencyOption);
//            }
//
//        });
        $('#businessCountry').change(function () {

            if ($(this).val() != '') {

                if ($(this).val() == 'CA') {

                    var businessState = '<select required data-parsley-required-message="Please select region/state" name="legal_entity_address_state" class="form-control">'
                            + '<option value="">Select State</option>'
                            + '<option value="AB">Alberta</option>'
                            + '<option value="BC">British Columbia</option>'
                            + '<option value="MB">Manitoba</option>'
                            + '<option value="NB">New Brunswick</option>'
                            + '<option value="NL">Newfoundland and Labrador</option>'
                            + '<option value="NT">Northwest Territories</option>'
                            + '<option value="NS">Nova Scotia</option>'
                            + '<option value="NU">Nunavut</option>'
                            + '<option value="ON">Ontario</option>'
                            + '<option value="PE">Prince Edward Island</option>'
                            + '<option value="QC">Quebec</option>'
                            + '<option value="SK">Saskatchewan</option>'
                            + '<option value="YT">Yukon</option>'
                            + '</select>';
                    var personalState = '<select required data-parsley-required-message="Please select region/state" name="legal_entity_personal_address_state" class="form-control">'
                            + '<option value="">Select State</option>'
                            + '<option value="AB">Alberta</option>'
                            + '<option value="BC">British Columbia</option>'
                            + '<option value="MB">Manitoba</option>'
                            + '<option value="NB">New Brunswick</option>'
                            + '<option value="NL">Newfoundland and Labrador</option>'
                            + '<option value="NT">Northwest Territories</option>'
                            + '<option value="NS">Nova Scotia</option>'
                            + '<option value="NU">Nunavut</option>'
                            + '<option value="ON">Ontario</option>'
                            + '<option value="PE">Prince Edward Island</option>'
                            + '<option value="QC">Quebec</option>'
                            + '<option value="SK">Saskatchewan</option>'
                            + '<option value="YT">Yukon</option>'
                            + '</select>';
                } else {

                    var businessState = '<input id="residentialAddressState" required data-parsley-required-message="Please enter region/state" name="legal_entity_address_state" type="text" value="<?= $merchant['legal_entity_address_state']; ?>" class="form-control" placeholder="e.g. New York">';
                    var personalState = '<input id="residentialAddressState" required data-parsley-required-message="Please enter region/state" name="legal_entity_personal_address_state" type="text" value="<?= $merchant['legal_entity_personal_address_state']; ?>" class="form-control" placeholder="e.g. New York">';
                }
                $('.business-state').html(businessState);
                $('.personal-state').html(personalState);

                $('#bankCountry').val($(this).val());
                $('#businessCurrency').val($(this).find(':selected').data('currency-code'));
                $('#bankCurrency').val($(this).find(':selected').data('currency-code'));
                $('#dialCode').val($(this).find(':selected').data('dial-code'));
                var data = {country_code: $(this).val()};

                $.when(getAdminMerchantCountrySpecs(data)).then(function (data) {
                    hideLoader('.merchant-registration-fields');
                    $('.stripe-field').hide();
                    if (data.meta.success) {
                        $('.stripe-field').find('input, select').removeAttr('required');
                        if ($("#companyStatus :selected").text() == "Sole Trader") {
                            $(data.data.countrySpecs.verification_fields.individual.minimum).each(function (i, item) {

                                var fieldName = item.replace(/\./g, '_');
                                $('*[name = "' + fieldName + '"]').attr('required', true);
                                $('*[name = "' + fieldName + '"]').closest('.stripe-field').show();

                            });


                            $(data.data.countrySpecs.verification_fields.individual.additional).each(function (i, item) {

                                var fieldName = item.replace(/\./g, '_');
                                $('*[name = "' + fieldName + '"]').attr('required', true);
                                $('*[name = "' + fieldName + '"]').closest('.stripe-field').show();

                            });

                        } else {

                            $(data.data.countrySpecs.verification_fields.company.minimum).each(function (i, item) {

                                var fieldName = item.replace(/\./g, '_');
                                $('*[name = "' + fieldName + '"]').attr('required', true);
                                $('*[name = "' + fieldName + '"]').closest('.stripe-field').show();

                            });

                            $(data.data.countrySpecs.verification_fields.company.additional).each(function (i, item) {

                                var fieldName = item.replace(/\./g, '_');
                                $('*[name = "' + fieldName + '"]').attr('required', true);
                                $('*[name = "' + fieldName + '"]').closest('.stripe-field').show();

                            });
                            
                            $('#taxId').removeAttr('required');
                            $('.tax_enabled_field').show();

                        }
                    }
                });
            }
        });
        
        $('body').on('click', '.tax_enabled', function(){
              if ($(this).val() == '1') {
                  $('#taxId').attr('required', true);
                  $('.tax_field').show();
              } else {
                  $('#taxId').removeAttr('required');
                  $('.tax_field').hide();
              }
        });

        $('body').on('click', '.tab-link', function () {
            var currentTab = $('.tab:visible').attr('id');
            $('#' + currentTab).parsley().validate();
//        if($(this).data('effect') == 'slide') {
//            
//            $('#' + $(this).data('tab')).slide();           
//        } else {
//            
//            $('#' + $(this).data('tab')).fadeIn();            
//        }

            if ($('#' + currentTab).parsley().isValid() || $(this).data('validate') == 0) {

                $('.tab').hide();
                $('#' + $(this).data('tab')).fadeIn();
//                $('#' + $(this).data('tab')).scrollView();
                $('.nav-tabs').find('li').removeClass('active');
                $('.nav-tabs *[data-tab="' + $(this).data('tab') + '"]').closest('li').addClass('active');
                //$(window).scrollTop(0);
            }
        });
    });
</script>