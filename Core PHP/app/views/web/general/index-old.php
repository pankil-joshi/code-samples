<?php include_once $this->getPart('/web/general/common/header.php'); ?>
<div class="fles_sale ">
    <div class="container">
        <div class="row">
            <div class="col-md-12 resolve_center">
                <div class="col-md-8 col-sm-8 col-xs-8">
                    <div class="resolve_solve">
                        <h1>A Revolution In <br>Social Commerce</h1>
                        <h3>Arriving November 2016</h3>
                        <h6>Subscribe Below To Be Kept Up To Date</h6>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <img src="<?= $app['base_assets_url']; ?>images/tagzie_mobile_slid.png" class="img-responsive addming_mobile" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="alunc_redy">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h1>Get ready for our launch...</h1>
                    <p>We are so excited to share with the world what we’ve been working on for the past 12 months. Tagzie will redefine social commerce and empower independant and established businesses with robust ecommerce tools and more. In essence, with Tagzie you can turn your followers into direct customers.</p>
                    <p>Subscribe to our newsletter below to be kept informed of our launch promotions, latest news and more.</p>
                </div>
                <form name="subscribe_form" id="subscribe_form" method="POST">
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input type="text" name="first_name" id="first_name" class="inp_txt_typ" placeholder="first name">
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input type="text" name="last_name" id="last_name" class="inp_txt_typ" placeholder="last name">
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input type="text" name="email" id="email" class="inp_txt_typ" placeholder="email">
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input type="hidden" name="method" value="subscribe_to_newsletter" /> 
                        <button type="submit" name="subscribe" id="subscribe" class="suscibe" type="button">SUBSCRIBE</button>
                    </div>
                </form>
                <div id="message_block"></div>
            </div>
        </div>
    </div>
</div>
<div class="get_femry">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h1>Get familiar with our policies...</h1>
                    <p>All our users must adhere to our policies. Amongst other things, this includes what can and cannot be sold on Tagzie.</p>
                </div>
                <div class="col-md-2 col-sm-3 col-xs-4">
                    <img src="<?= $app['base_assets_url']; ?>images/copy_notpad.png" class="doc_copu" alt="">
                </div>
                <div class="col-md-10 col-sm-9 col-xs-8">
                    <div class="term_ul">
                        <ul>
                            <li><a href="<?= $app['base_url']; ?>legal/terms">Terms and Conditions</a></li>
                            <li><a href="<?= $app['base_url']; ?>legal/refund">Refund Policy</a></li>
                            <li><a href="<?= $app['base_url']; ?>legal/privacy">Privacy Policy</a></li>
<!--                            <li><a href="">Complaints</a></li>-->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="get_femry get_mrfin" style="padding:0">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h1>Contact Tagzie...</h1>
                    <p>If you wish to contact Tagzie for any reason, please use any of the following to reach us:</p>
                </div>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <div class="term_ul">
                        <ul>
                            <li><span><img src="<?= $app['base_assets_url']; ?>images/mobile_img.png" class="doc_copu1" alt=""></span>+44 1509 559 788</li>
                            <li><span><img src="<?= $app['base_assets_url']; ?>images/message_home_icon.png" class="doc_copu1" alt=""></span><a href="">support@tagzie.com</a></li>
                            <li style="align-items: flex-start;"><span><img src="<?= $app['base_assets_url']; ?>images/map_location.png" class="doc_copu1" alt=""></span>32 Thistlebank <br>East Leake <br>Loughborough<br> LE12 6RS<br> United Kingdom</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once $this->getPart('/web/general/common/footer.php'); ?>