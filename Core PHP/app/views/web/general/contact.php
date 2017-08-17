<?php include_once $this->getPart('/web/general/common/header.php'); ?>

<div class="main_about_page buying_page how_it_work">
    <div class="banner_about"><img src="<?= $app['base_assets_url']; ?>images/contact_bannner.jpg" /></div>




    <div class="container">
        <div class="main_about_middle_cont">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="bread_main_pedding">
                    <h1 style="margin-bottom:4">Contact Us</h1>
                    <div class="verb_txt buying_shop">
                        <p>We are happy to hear from you, whether you need support, advice or to find out how we can empower your brand, we've got you covered!</p>
                        <b>Please reach us using the form below:</b>
                    </div>
                </div>
            </div>

            <!--  <div class="posyon_icon">
                  <div class="col-md-6 col-sm-6 col-xs-6">
                      <hr>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-6">
                      <hr>
                  </div>
                  <img class="img-responsive" src="<?= $app['base_assets_url']; ?>images/grey_iconlogo.jpg" alt="">
              </div>
            -->

            <div class="col-md-12 text-center">
                <div class="contact_from form_frame">
                    <form id="contact-form" data-parsley-validate style="    float: left;
                          width: 100%;">
                          <?php if (!jwt_authentication()): ?>
                            <input type="text" name="name" required class="inp_check" placeholder="Your Name" data-parsley-required-message="Please enter full name.">        
                            <input type="email" name="email" required class="inp_check" placeholder="Your Email" data-parsley-required-message="Please enter email." data-parsley-type-message="Email must be valid."> 
                        <?php endif; ?>
                        <div class="select" >
                            <select name="topic_id" required data-parsley-required-message="Please select topic">
                                <option value="">Choose topic...</option>
                                <option value="2">Feedback</option>
                                <option value="1">General Inquiry</option>
                                <option value="10">Report a problem</option>
                                <option value="12">Support</option>
                                <option value="13">Sales</option>
                            </select>
                            <div class="select__arrow"></div>
                        </div>
                        <input type="text" name="subject" class="inp_check" placeholder="Subject" required data-parsley-required-message="Please enter subject"> 
                        <textarea name="message" class="inp_check" rows="10" placeholder="Enter Your Message" required data-parsley-required-message="Please enter message."></textarea>
                        <button type="submit" class="sub_btn">SUBMIT</button>  
                    </form>
                </div>
            </div>



            <div class="posyon_icon">
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <hr>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <hr>
                </div>
                <img class="img-responsive" src="<?= $app['base_assets_url']; ?>images/grey_iconlogo.jpg" alt="">
            </div>
        </div>
    </div>
</div>
<script>
    /*
     * Add new address.
     */
    $("#contact-form").on('submit', function (e) {
        e.preventDefault();
        var form = $(this);

        form.parsley().validate();

        if (form.parsley().isValid()) {
            var data = {};
            data.body = $('#contact-form').serializeObject()

            $.when(contactSupport(data)).then(function (response) {

                if (response.meta.success) {

                    hideLoader('#contact-form');
                    $('#contact-form').html('<p style="color: green;font-weight: 700; ">Thank you for contacting, you will hear from us shortly. Reference #'+response.data.ticket+'</p>');
                }

            });

        }
    });
</script>
<?php include_once $this->getPart('/web/general/common/footer.php'); ?>