<?php include_once $this->getPart('/web/common/header.php'); ?>
<!-- Modal -->
<div class="modal fade width_dialog" id="merchant-success-modal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
                <h4 class="modal-title">Merchant Registration Successful</h4>
            </div>
            <div class="modal-body">
                <p>Thank you for joining the Tagzie Marketplace and helping to shape Tagzie’s Social Commerce Revolution!</p>
                <p>We’ve just sent you an email confirming your subscription level, including some useful links and information to help you get started.</p>
                <p>You can manage your merchant account entirely from the Tagzie App, or if you’re using a computer/laptop you can view your Merchant Dashboard via: <a href="https://www.tagzie.com/account/merchant" class="primary-orange">tagzie.com/account/merchant</a></p>
                <p>You may be required to upload an identification document to verify your account. You will see a notification about this when you next load the app. We will email you with a checklist of what information is required.</p>
            </div>
            <div class="modal-footer">
                <a href="<?php if (getOS() == 'Android'): ?>intent://dashboard.html#Intent;scheme=ipaid;package=<?= $app['android_package_name']; ?>;end<?php elseif (getOS() == 'iOS'): ?>ipaid://dashboard.html<?php endif; ?>" type="button" class="btn btn-default orange" >Back to App</a>

            </div>
        </div>

    </div>
</div>

<div class="secure_pin">
    <h6><img src="<?= $app['base_assets_url']; ?>images/super_lock.png" class="img-resaponsive" alt="">Tagzie is in ULTRA SECURE MODE</h6>  
</div>
<div id="merchantFields">
    <!--tab-->
    <div class="container">
        <div class="col-xs-12">
            <div class="down_tab">
                <h1>Registration Progress</h1>
                <div id="exTab2" class="busnis_detil">
                    <ul class="nav nav-tabs tab_set_degn">
                        <li class="active " style="padding: 0;">
                            <a href="#" data-tab="business-details" class="tab-link">1</a>
                        </li>
                        <li class="" style="padding: 0;">
                            <a href="#" data-tab="business-address" class="tab-link">2</a>
                        </li>
                        <li class="" style="padding: 0;">
                            <a href="#" data-tab="personal-details" class="tab-link">3</a>
                        </li>
                        <li class="" style="padding:0;">
                            <a href="#" data-tab="bank-details" class="tab-link">4</a>
                        </li>                    
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="container merchant-registration-fields">
        <form id="merchant-registration-form" autocomplete="off">
            <div class="row tab"  id="business-details" data-parsley-validate>
                <div class="col-md-6 col-sm-12 col-xs-12">                  
                    <div class="col-md-12 buseind_detil"><h1>BUSINESS DETAILS</h1></div>
                    <div class="showing_form">
                        <div class="col-md-4">
                            <label>Instagram Username:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input type="text" class="form-control" value="@<?= $user['instagram_username']; ?>" readonly>
                            </div>
                        </div>
                    </div>                      
                    <div class="showing_form">
                        <div class="col-md-4">
                            <label>Business Name:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="businessName" required data-parsley-required-message="Please enter business name" name="legal_entity_business_name" type="text" class="form-control" placeholder="eg. Abc limited">
                            </div>
                        </div>
                    </div>
                    <div class="showing_form stripe-field" style="display: none">
                        <div class="col-md-4">
                            <label>Business Name(kana):</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="businessName" required data-parsley-required-message="Please enter business name" name="legal_entity_business_name_kana" type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="showing_form stripe-field" style="display: none">
                        <div class="col-md-4">
                            <label>Business Name(kanji):</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="businessName" required data-parsley-required-message="Please enter business name" name="legal_entity_business_name_kanji" type="text" class="form-control">
                            </div>
                        </div>
                    </div>                    
                    <div class="showing_form">
                        <div class="col-md-4">
                            <label>Select Entity:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <select class="form-control frid_boeder choice" id="companyStatus" required data-parsley-required-message="Select business entity type" name="legal_entity_type">
                                    <option value="">Select Entity...</option>
                                    <option value="Sole Trader">Sole Trader</option>
                                    <option value="Limited Company">Limited Company</option>
                                    <option value="PLC">PLC</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="showing_form">
                        <div class="col-md-4">
                            <label>Registration No.<span> (if applicable):</span></label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="companyRegistrationNumber" name="business_registration_number" type="text" class="form-control" placeholder="eg. REG-XXXXXX">
                            </div>
                        </div>
                    </div>
                    <div class="showing_form">
                        <div class="col-md-4">
                            <label>Email Address:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input type="email" id="businessEmail" required data-parsley-required-message="Please enter email" data-parsley-type-message="Email must be valid" name="business_email" type="text" class="form-control" placeholder="eg. example@abc.com"> </div>
                        </div>
                    </div>
                    <div class="showing_form">
                        <div class="col-md-4">
                            <label>Website:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input type="url" id="businessWebsite" data-parsley-type-message="Website URL must be valid" name="business_website" type="text" class="form-control" placeholder="eg. www.abc.com">
                            </div>
                        </div>
                    </div>
                    <div class="showing_form">
                        <div class="col-md-4">
                            <label>Business Category:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">

                                <select class="form-control choice" id="businessCategory" required data-parsley-required-message="Please select category." name="business_category">
                                    <option value="">Business Category</option>
                                    <?php foreach ($parent_categories as $category): ?>
                                        <option value="<?= $category['label']; ?>"><?= $category['label']; ?></option>
                                    <?php endforeach; ?>
                                </select></div>
                        </div>
                    </div>
                    <div class="showing_form">
                        <div class="col-md-4">
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
                        <div class="col-md-4">
                            <label>Business Currency:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input type="text"  id="businessCurrency" name="business_currency" class="form-control" readonly placeholder="eg. GBP">                                
                            </div>
                        </div>
                    </div>
                    <div class="showing_form tax_enabled_field" style="display: none">
                        <div class="col-md-4">
                            <label>Are you tax registered?</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <label class="radio font3"> 
                                    <input type="radio" name='tax_enabled' class="tax_enabled" value='1' />
                                    <span class="outer"><span class="inner"></span></span> Yes
                                </label>
                                <label class="radio font3"> 
                                    <input type="radio" name='tax_enabled' class="tax_enabled" value='0' checked="" />
                                    <span class="outer"><span class="inner"></span></span> No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="showing_form tax_field" style="display: none">
                        <div class="col-md-4">
                            <label>Tax ID:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="taxId" name="legal_entity_business_tax_id" data-parsley-required-message="Please enter tax ID" type="text" class="form-control" placeholder="eg. TIN-XXXXXX"></div>
                        </div>
                    </div>
                    <div class="showing_form">
                        <div class="col-md-4">
                            <label>Business Telephone:</label>
                        </div>
                        <div class="col-md-8">

                            <div class="row">
                                <div style="padding:0" class="col-md-3 col-sm-9 col-xs-3">
                                    <input id="dialCode" readonly style="padding: 9px" name="business_telephone_prefix" class="form-control" placeholder="eg. +44">
                                </div>
                                <div class="col-md-9 col-sm-9 col-xs-9" style="padding:0"><input type="text" required data-parsley-required-message="Please enter mobile number" data-parsley-type="digits" name="legal_entity_phone_number"  alt="" class="form-control" placeholder="eg. XXXXXXXXX"></div>
                            </div>
                        </div>
                    </div>
                    <div class="showing_form stripe-field" style="display: none">
                        <div class="col-md-4">
                            <label>Add additional owners:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <button class="btn btn-size col-xs-12" type="button"><i class="fa fa-plus plus_icon"></i> Add  </button></div>
                        </div>
                    </div>
                    <div class="showing_form">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="proceed_btn">
                                    <button class="tab-link" data-tab="business-address" data-effect="slide" type="button"> Proceed  </button>
                                </div>
                            </div>
                        </div>
                    </div>            
                </div>
            </div>
            <div class="row tab" id="business-address" style="display: none" data-parsley-validate>
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="col-md-12 buseind_detil"><h1>BUSINESS ADDRESS</h1></div>
                    <div class="showing_form stripe-field" style="display: none">
                        <div class="col-md-4">
                            <label>Address Line 1:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="businessAddressLine1" required data-parsley-required-message="Please enter address line 1" name="legal_entity_address_line1" type="text" class="form-control" placeholder="e.g. 2601/2 none-C">
                            </div>
                        </div>
                    </div>
                    <div class="showing_form stripe-field" style="display: none">
                        <div class="col-md-4">
                            <label>Town / City:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="businessAddressCity" required data-parsley-required-message="Please enter city" name="legal_entity_address_city" type="text" class="form-control" placeholder="e.g. Downtown Square"> </div>
                        </div>
                    </div>
                    <div class="showing_form stripe-field"  style="display: none">
                        <div class="col-md-4">
                            <label>Region / State:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row business-state">

                            </div>
                        </div>
                    </div>
                    <div class="showing_form stripe-field"  style="display: none">
                        <div class="col-md-4">
                            <label>Postcode / Zip Code:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="businessAddressPostcode" required data-parsley-required-message="Please enter zip code" name="legal_entity_address_postal_code" type="text" class="form-control" placeholder="e.g. 10014"> </div>
                        </div>
                    </div>
                    <div class="japan-fields stripe-field"  style="display: none">
                        <div class="col-xs-12">
                            <label><b><u>Business Address(KANA):</u></b></label>
                        </div>

                        <div class="showing_form">
                            <div class="col-md-4">
                                <label>Address Line 1:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <input id="kanaPersonalAddressLine1" data-parsley-required-message="Please enter address line 1" name="legal_entity_address_kana_line1" type="text" class="form-control" placeholder="e.g. 1-5-2"> </div>
                            </div>
                        </div>
                        <div class="showing_form">
                            <div class="col-md-4">
                                <label> City:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <input id="kanaPersonalAddressCity" data-parsley-required-message="Please enter city" name="legal_entity_address_kana_city" type="text" class="form-control" placeholder="e.g. Tokyo"> </div>
                            </div>
                        </div>
                        <div class="showing_form">
                            <div class="col-md-4">
                                <label>Region / State:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <input id="kanaPersonalAddressState" data-parsley-required-message="Please enter region/state" name="legal_entity_address_kana_state" type="text" class="form-control" placeholder="e.g. Japan">
                                </div>
                            </div>
                        </div>
                        <div class="showing_form">
                            <div class="col-md-4">
                                <label>Postcode / Zip Code:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <input id="kanaPersonalAddressPostcode" data-parsley-required-message="Please enter postcode/zip code" name="legal_entity_address_kana_postal_code" type="text" class="form-control" placeholder="e.g. 105-7123"></div>
                            </div>
                        </div>

                        <div class="showing_form">
                            <div class="col-md-4">
                                <label>Town:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <input id="kanaPersonalAddressTown" data-parsley-required-message="Please enter town" name="legal_entity_address_kana_town" type="text" class="form-control" placeholder="e.g. Karakura Town"></div>
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <label><b><u>Business Address(KANJI):</u></b></label>
                        </div>                     
                        <div class="showing_form">
                            <div class="col-md-4">
                                <label>Address Line 1:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <input id="kanjiPersonalAddressLine1" data-parsley-required-message="Please enter address line 1" name="legal_entity_address_kanji_line1" type="text" class="form-control" placeholder="e.g. 1-5-2"> </div>
                            </div>
                        </div>
                        <div class="showing_form">
                            <div class="col-md-4">
                                <label> City:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <input id="kanjiPersonalAddressCity" data-parsley-required-message="Please enter city" name="legal_entity_address_kanji_city" type="text" class="form-control" placeholder="e.g. Tokyo"></div>
                            </div>
                        </div>
                        <div class="showing_form">
                            <div class="col-md-4">
                                <label>Region / State:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <input id="kanjiPersonalAddressState" data-parsley-required-message="Please enter region/state" name="legal_entity_address_kanji_state" type="text" class="form-control" placeholder="e.g. Japan">
                                </div>
                            </div>
                        </div>
                        <div class="showing_form">
                            <div class="col-md-4">
                                <label>Postcode / Zip Code:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <input id="kanjiPersonalAddressPostcode" data-parsley-required-message="Please enter postcode/zip code" name="legal_entity_address_kanji_postal_code" type="text" class="form-control" placeholder="e.g. 105-7123"></div>
                            </div>
                        </div>

                        <div class="showing_form">
                            <div class="col-md-4">
                                <label>Town:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <input id="kanjiPersonalAddressTown" data-parsley-required-message="Please enter town"name="legal_entity_address_kanji_town" type="text" class="form-control" placeholder="e.g. Karakura Town"></div>
                            </div>
                        </div>
                    </div>                     
                    <div class="showing_form">                    
                        <div class="col-md-6 col-md-push-6">
                            <div class="row">
                                <div class="proceed_btn">
                                    <button class="col-xs-12 tab-link" data-tab="personal-details" data-effect="slide" type="button"> Proceed  </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-md-pull-6">
                            <div class="row">
                                <div class="proceed_btn">
                                    <button class="col-xs-12 tab-link" type="button" data-tab="business-details" data-validate="0"> Back  </button>
                                </div>
                            </div>
                        </div>  

                    </div>             
                </div>
            </div>
            <div class="row tab" id="personal-details" style="display: none" data-parsley-validate>

                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="col-md-12 buseind_detil"> <h1>PERSONAL DETAILS</h1></div>
                    <div class="showing_form stripe-field" style="display: none">
                        <div class="col-md-4">
                            <label>First name:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="firstName" required data-parsley-required-message="Please enter first name" name="legal_entity_first_name" type="text" class="form-control" placeholder="e.g. John"> </div>
                        </div>
                    </div>
                    <div class="showing_form stripe-field" style="display: none">
                        <div class="col-md-4">
                            <label>Last name:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="lastName" required data-parsley-required-message="Please enter last name" name="legal_entity_last_name" type="text" class="form-control" placeholder="e.g. Smith"></div>
                        </div>
                    </div>
                    <div class="showing_form stripe-field" style="display: none">
                        <div class="col-md-4">
                            <label>First name(kana):</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="firstName" required data-parsley-required-message="Please enter first name" name="legal_entity_kana_first_name" type="text" class="form-control" placeholder="e.g. John"> </div>
                        </div>
                    </div>
                    <div class="showing_form stripe-field" style="display: none">
                        <div class="col-md-4">
                            <label>Last name(kana):</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="lastName" required data-parsley-required-message="Please enter last name" name="legal_entity_kana_last_name" type="text" class="form-control" placeholder="e.g. Smith"></div>
                        </div>
                    </div>
                    <div class="showing_form stripe-field" style="display: none">
                        <div class="col-md-4">
                            <label>First name(kanji):</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="firstName" required data-parsley-required-message="Please enter first name" name="legal_entity_kanji_first_name" type="text" class="form-control" placeholder="e.g. John"> </div>
                        </div>
                    </div>
                    <div class="showing_form stripe-field" style="display: none">
                        <div class="col-md-4">
                            <label>Last name(kanji):</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="lastName" required data-parsley-required-message="Please enter last name" name="legal_entity_kanji_last_name" type="text" class="form-control" placeholder="e.g. Smith"></div>
                        </div>
                    </div>                      
                    <div class="showing_form stripe-field" style="display: none">
                        <div class="col-md-4">
                            <label>Address Line 1:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="residentialAddressLine1" required data-parsley-required-message="Please enter address line 1" name="legal_entity_personal_address_line1" type="text" class="form-control" placeholder="e.g. 2601/2 none-C"> </div>
                        </div>
                    </div>
                    <div class="showing_form stripe-field" style="display: none">
                        <div class="col-md-4">
                            <label>Town / City:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="residentialAddressCity" required data-parsley-required-message="Please enter city/town" name="legal_entity_personal_address_city" type="text" class="form-control" placeholder="e.g. Downtown Square"></div>
                        </div>
                    </div>
                    <div class="showing_form stripe-field"  style="display: none">
                        <div class="col-md-4">
                            <label>Region / State:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row personal-state">

                            </div>
                        </div>
                    </div>
                    <div class="showing_form stripe-field"  style="display: none">
                        <div class="col-md-4">
                            <label>Postcode / Zip Code:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="residentialAddressPostcode" required data-parsley-required-message="Please enter postcode/zip code" name="legal_entity_personal_address_postal_code" type="text" class="form-control" placeholder="e.g. 10014" > </div>
                        </div>
                    </div>
                    <div class="showing_form">
                        <div class="col-md-4">
                            <label>Date of Birth:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input type="tel" id="personalDateOfBirth" required data-parsley-required-message="Please enter date of birth" name="legal_entity_dob" type="text" class="form-control" placeholder="e.g. 01-01-1985"></div>
                        </div>
                    </div>

                    <div class="showing_form stripe-field"  style="display: none">
                        <div class="col-md-4">
                            <label>Gender:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <select id="gender" required data-parsley-required-message="Please select gender" name="legal_entity_gender" class="form-control">
                                    <option value="">Choose...</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="showing_form stripe-field"  style="display: none">
                        <div class="col-md-4">
                            <label>SSN (Last 4):</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="ssnNumber" data-parsley-required-message="Please enter ssn number" name="legal_entity_ssn_last_4" type="text" class="form-control" placeholder="e.g. ******1234"></div>
                        </div>
                    </div>
                    <div class="showing_form stripe-field"  style="display: none">
                        <div class="col-md-4">
                            <label>Personal ID number:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="personalId" data-parsley-required-message="Please enter personal ID" name="legal_entity_personal_id_number" type="text" class="form-control" placeholder="e.g. XXXXXXX"></div>
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
                                    <input id="kanaPersonalAddressLine1" data-parsley-required-message="Please enter address line 1" name="legal_entity_personal_address_kana_line1" type="text" class="form-control" placeholder="e.g. 1-5-2"> </div>
                            </div>
                        </div>
                        <div class="showing_form">
                            <div class="col-md-4">
                                <label> City:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <input id="kanaPersonalAddressCity" data-parsley-required-message="Please enter city" name="legal_entity_personal_address_kana_city" type="text" class="form-control" placeholder="e.g. Tokyo"> </div>
                            </div>
                        </div>
                        <div class="showing_form">
                            <div class="col-md-4">
                                <label>Region / State:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <input id="kanaPersonalAddressState" data-parsley-required-message="Please enter region/state" name="legal_entity_personal_address_kana_state" type="text" class="form-control" placeholder="e.g. Japan">
                                </div>
                            </div>
                        </div>
                        <div class="showing_form">
                            <div class="col-md-4">
                                <label>Postcode / Zip Code:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <input id="kanaPersonalAddressPostcode" data-parsley-required-message="Please enter postcode/zip code" name="legal_entity_personal_address_kana_postal_code" type="text" class="form-control" placeholder="e.g. 105-7123"></div>
                            </div>
                        </div>

                        <div class="showing_form">
                            <div class="col-md-4">
                                <label>Town:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <input id="kanaPersonalAddressTown" data-parsley-required-message="Please enter town" name="legal_entity_personal_address_kana_town" type="text" class="form-control" placeholder="e.g. Karakura Town"></div>
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
                                    <input id="kanjiPersonalAddressLine1" data-parsley-required-message="Please enter address line 1" name="legal_entity_personal_address_kanji_line1" type="text" class="form-control" placeholder="e.g. 1-5-2"> </div>
                            </div>
                        </div>
                        <div class="showing_form">
                            <div class="col-md-4">
                                <label> City:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <input id="kanjiPersonalAddressCity" data-parsley-required-message="Please enter city" name="legal_entity_personal_address_kanji_city" type="text" class="form-control" placeholder="e.g. Tokyo"></div>
                            </div>
                        </div>
                        <div class="showing_form">
                            <div class="col-md-4">
                                <label>Region / State:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <input id="kanjiPersonalAddressState" data-parsley-required-message="Please enter region/state" name="legal_entity_personal_address_kanji_state" type="text" class="form-control" placeholder="e.g. Japan">
                                </div>
                            </div>
                        </div>
                        <div class="showing_form">
                            <div class="col-md-4">
                                <label>Postcode / Zip Code:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <input id="kanjiPersonalAddressPostcode" data-parsley-required-message="Please enter postcode/zip code" name="legal_entity_personal_address_kanji_postal_code" type="text" class="form-control" placeholder="e.g. 105-7123"></div>
                            </div>
                        </div>

                        <div class="showing_form">
                            <div class="col-md-4">
                                <label>Town:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <input id="kanjiPersonalAddressTown" data-parsley-required-message="Please enter town"name="legal_entity_personal_address_kanji_town" type="text" class="form-control" placeholder="e.g. Karakura Town"></div>
                            </div>
                        </div>
                    </div>
                    <div class="showing_form">                  
                        <div class="col-md-6 col-md-push-6">
                            <div class="row">
                                <div class="proceed_btn">
                                    <button class="col-xs-12 tab-link" data-tab="bank-details" data-effect="slide" type="button"> Proceed  </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-md-pull-6">
                            <div class="row">
                                <div class="proceed_btn">
                                    <button class="col-xs-12 tab-link" type="button" data-tab="business-address" data-validate="0"> Back  </button>
                                </div>
                            </div>
                        </div>  

                    </div>              
                </div>    
            </div>

            <div class="row tab" id="bank-details" style="display: none" data-parsley-validate>
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="col-md-12 buseind_detil"> <h1>BANK DETAILS</h1></div>
                    <div class="showing_form">
                        <div class="col-md-4">
                            <label>Bank Name:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="businessBankName" name="bank_name" required data-parsley-required-message="Please enter bank name" type="text" class="form-control" placeholder="eg. HSBC">
                            </div>
                        </div>
                    </div>
                    <div class="showing_form">
                        <div class="col-md-4">
                            <select class="form-control" id="accountNumberType">
                                <option value="account_number">Account Number</option>
                                <option value="iban">IBAN</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="businessBankAccountNumber" name="bank_account_number" required data-parsley-required-message="Please enter your bank account number" type="number" class="form-control" placeholder="eg. XXXXXXXXX"> 
                                <input id="businessBankIBAN" name="bank_account_number" data-parsley-required-message="Please enter your IBAN number" type="text" class="form-control" placeholder="eg. GBXXXXXXXXX" disabled="disabled" style="display: none;"> 
                            </div>
                        </div>
                    </div>
                    <div class="showing_form">
                        <div class="col-md-4">
                            <label>Sort Code / Routing Number:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="businessSortCode" name="bank_sort_code" data-parsley-required-message="Please enter your bank sort code or routing number" type="text" class="form-control" required placeholder="eg. XXXX-XXXX"> </div>
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
                        <div class="col-md-4">
                            <label>Swift (optional):</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="swiftCode" name="bank_swift" type="text" class="form-control">  </div>
                        </div>
                    </div>

                    <div class="showing_form">
                        <div class="col-md-4">
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
                        <div class="col-md-4">
                            <label>Bank Currency:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input required data-parsley-required-message="Please select bank currency" id="bankCurrency" type="text" class="form-control" name="bank_currency" readonly placeholder="eg. GBP">
                            </div>
                        </div>
                    </div>
                    <div class="showing_form">                   
                        <div class="col-md-6 col-md-push-6">
                            <div class="row">
                                <div class="proceed_btn">
                                    <button class="col-xs-12" type="button" id="complete-signup"> Proceed  </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-md-pull-6">
                            <div class="row">
                                <div class="proceed_btn">
                                    <button class="col-xs-12 tab-link" type="button" data-tab="personal-details" data-validate="0"> Back  </button>
                                </div>
                            </div>
                        </div>  

                    </div>          
                </div>    
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="modal fade" id="complete-signup-modal" role="dialog">
                        <div class="modal-dialog model_width model_responsive">

                            <!-- Modal content-->
                            <div class="modal-content message-modal-body">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                                    <h4 class="modal-title">Complete Registration</h4>
                                </div>
                                <div class="modal-body review_style">
                                    <div class="panel-group" id="accordion">
                                        <!--                                        <div class="panel panel-default">
                                                                                    <div class="panel-heading selct_subs">
                                                                                        <h4 class="panel-title">
                                                                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse1" class="btn btn-info btn_style" id="">
                                                                                                Select Subscription</a>
                                                                                        </h4>
                                                                                    </div>
                                                                                    <div id="collapse1" class="panel-collapse collapse in" data-parsley-validate>
                                                                                        <div class="panel-body">
                                                                                            <div class="showing_form">
                                        
                                                                                                <div class="col-md-12">
                                                                                                    <div class="row">
                                                                                                        <select class="form-control" required id="selectedSubscription" name="subscription_package_id">
                                        <?php foreach ($subscriptionPackages as $subscriptionPackage): ?>
                                                                                                                                    <option data-name="<?= $subscriptionPackage['name']; ?>" data-price="<?= $subscriptionPackage['rate']; ?>" value="<?= $subscriptionPackage['id']; ?>" <?= $subscriptionPackage['id'] == $package['id'] ? 'selected' : ''; ?>><?= $subscriptionPackage['name']; ?> £<?= $subscriptionPackage['rate']; ?></option>
                                        <?php endforeach; ?>
                                                                                                        </select>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>                                
                                                                                        </div>
                                                                                    </div>
                                                                                </div>-->
                                        <div class="panel panel-default">
                                            <div class="panel-heading selct_subs">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse1" class="btn btn-info btn_style collapse1">
                                                        Select Card</a>
                                                </h4>
                                            </div>
                                            <div id="collapse1" class="panel-collapse collapse in" data-parsley-validate>
                                                <div class="panel-body">
                                                    <div class="showing_form">

                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="regi_select complete_reg">
                                                                    <input type="hidden" id="selectedSubscription" name="subscription_package_id" data-name="<?= $package['name']; ?>" data-price="<?= $package['rate']; ?>" value="<?= $package['id']; ?>">

                                                                    <select class="form-control" required id="selectedCard" data-parsley-required-message="Please add or select a card.">
                                                                        <?php if (!empty($cards)): foreach ($cards as $card): ?>
                                                                                <option value="<?= $card['stripe_card_id']; ?>" >“<?= $card['brand']; ?>”  |  ending with <?= $card['last4']; ?></option>
                                                                                <?php
                                                                            endforeach;
                                                                        else:
                                                                            ?>
                                                                            <option value="">No cards exist!</option>
                                                                        <?php endif; ?>
                                                                    </select>
                                                                    <a href="#" style="font-size: 24px" title="Add Card" id="add-card"><span class="glyphicon glyphicon-plus-sign add-address primary-oragnge"></span></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                             
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading selct_subs">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse2" class="btn btn-info btn_style">
                                                        Review</a>
                                                </h4>
                                            </div>
                                            <div id="collapse2" class="panel-collapse collapse">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="subscription_pack">
                                                            <h4>Subscription package:</h4>
                                                        </div>
                                                        <span class="selected-package"></span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="subscription_pack">
                                                            <h4> Price:</h4>

                                                        </div>
                                                        <span class="symbol_plan">£<span class="selected-subscription-price"></span></span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="subscription_pack">
                                                            <h4>  Card:</h4>
                                                        </div>
                                                        <span class="selected-card"></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="subscribe_btn">
                                                        <button type="button" id="subscribe"> Subscribe  </button>
                                                    </div>
                                                </div>                            
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
        </form>
        <?php include_once $this->getPart('/web/components/add-card-form.php'); ?>
    </div>
</div>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
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
                                    cardList += '<option value="' + item.stripe_card_id + '">“' + item.brand + '”  |  ending with ' + item.last4 + '</option>';
                                });

                                $('#selectedCard').html(cardList);
                                hideLoader('#card-box');
                                $('#add-card-modal').modal('hide');
                                $('#complete-signup-modal').modal('show');
                                $('#selectedCard').trigger('change');
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

        $('#add-card').click(function () {
            $('#complete-signup-modal').modal('hide');
            $('#add-card-modal').modal('show');
        });

        $('#personalDateOfBirth').mask("00-00-0000");
        $('#subscribe').click(function () {

            var data = {};
            var formData = $('#merchant-registration-form').serializeObject();
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
            formData.payment_option = {data: {card_id: $('#selectedCard').val()}, id: 'Stripe'}

            data.body = formData;

            $.when(saveMerchant(data)).then(function (data) {
                hideLoader('#complete-signup-modal .modal-content');
                if (data.meta.success) {
<?php if (getOS() == 'Android' || getOS() == 'iOS'): ?>
                        $('#complete-signup-modal').modal('hide');
                        $('#merchant-success-modal').modal('show');
<?php else: ?>
                        window.location.href = '<?= $app['base_url']; ?>account/merchant';
<?php endif; ?>

                }
            });
        });
        $('.selected-package').text($('#selectedSubscription').data('name'));
        $('.selected-subscription-price').text($('#selectedSubscription').data('price'));
        $('.selected-card').text($('#selectedCard').find(':selected').text());

        $('#selectedCard').change(function () {
            $('.selected-card').text($(this).find(':selected').text());

        });
        $('#accordion a[data-toggle]').on('click', function (e) {
            // Panel that is currently open
            $('#accordion div.in').parsley().validate();
            if (!$('#accordion div.in').parsley().isValid()) {
                e.stopPropagation();
            }
        });
        $('#complete-signup').click(function () {
            var currentTab = $('.tab:visible').attr('id');
            $('#' + currentTab).parsley().validate();

            if ($('#' + currentTab).parsley().isValid()) {

                $('#complete-signup-modal').modal('show');
            }
        });

        var countryOptions = '';
        $(stripeCountries).each(function (index, item) {
            var selected = item.code == 'GB' ? 'selected' : '';
            countryOptions += '<option value="' + item.code + '" data-currency-code="' + item.currency_code + '" data-dial-code="' + item.dial_code + '">' + item.name + '</options>';
        });

        $('#businessCountry').append(countryOptions);
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

                    var businessState = '<input id="residentialAddressState" required data-parsley-required-message="Please enter region/state" name="legal_entity_address_state" type="text" class="form-control" placeholder="e.g. New York">';
                    var personalState = '<input id="residentialAddressState" required data-parsley-required-message="Please enter region/state" name="legal_entity_personal_address_state" type="text" class="form-control" placeholder="e.g. New York">';
                }
                $('.business-state').html(businessState);
                $('.personal-state').html(personalState);

                $('#bankCountry').val($(this).val());
                $('#businessCurrency').val($(this).find(':selected').data('currency-code'));
                $('#bankCurrency').val($(this).find(':selected').data('currency-code'));
                $('#dialCode').val($(this).find(':selected').data('dial-code'));
                var data = {country_code: $(this).val()};

                $.when(getMerchantCountrySpecs(data)).then(function (data) {
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

                            $('.tax_enabled_field').hide();

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
                    } else {

                        toastr.error(data.data.errors.message);
                    }
                });
            }
        });

        $('body').on('click', '.tax_enabled', function () {
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

                if ($('#personalDateOfBirth').is(':visible')) {

                    if (moment($('#personalDateOfBirth').val(), 'DD-MM-YYYY', true).isValid()) {

                        $('.tab').hide();
                        $('#' + $(this).data('tab')).fadeIn();
//                $('#' + $(this).data('tab')).scrollView();
                        $('.nav-tabs').find('li').removeClass('active');
                        $('.nav-tabs *[data-tab="' + $(this).data('tab') + '"]').closest('li').addClass('active');
                        $(window).scrollTop(0);
                    } else {

                        toastr.error('Date of birth is incorrect, it should be in DD-MM-YYYY format.');
                    }

                } else if (!$('#personalDateOfBirth').is(':visible')) {

                    $('.tab').hide();
                    $('#' + $(this).data('tab')).fadeIn();
//                $('#' + $(this).data('tab')).scrollView();
                    $('.nav-tabs').find('li').removeClass('active');
                    $('.nav-tabs *[data-tab="' + $(this).data('tab') + '"]').closest('li').addClass('active');
                    $(window).scrollTop(0);
                }
            }
        });
    });
</script>
<?php include_once $this->getPart('/web/common/footer.php'); ?>