<?php include_once $this->getPart('/web/general/common/header.php'); ?>



<style>
    /* jssor slider bullet navigator skin 05 css */
    /*
    .jssorb05 div           (normal)
    .jssorb05 div:hover     (normal mouseover)
    .jssorb05 .av           (active)
    .jssorb05 .av:hover     (active mouseover)
    .jssorb05 .dn           (mousedown)
    */
    .jssorb05 {
        position: absolute;
    }
    .jssorb05 div, .jssorb05 div:hover, .jssorb05 .av {
        position: absolute;
        /* size of bullet elment */
        width: 16px;
        height: 16px;
        overflow: hidden;
        cursor: pointer;
    }
    .jssorb05 div { background-position: -7px -7px; }
    .jssorb05 div:hover, .jssorb05 .av:hover { background-position: -37px -7px; }
    .jssorb05 .av { background-position: -67px -7px; }
    .jssorb05 .dn, .jssorb05 .dn:hover { background-position: -97px -7px; }

    /* jssor slider arrow navigator skin 22 css */
    /*
    .jssora22l                  (normal)
    .jssora22r                  (normal)
    .jssora22l:hover            (normal mouseover)
    .jssora22r:hover            (normal mouseover)
    .jssora22l.jssora22ldn      (mousedown)
    .jssora22r.jssora22rdn      (mousedown)
    .jssora22l.jssora22lds      (disabled)
    .jssora22r.jssora22rds      (disabled)
    */
    .jssora22l, .jssora22r {
        display: block;
        position: absolute;
        /* size of arrow element */
        width: 40px;
        height: 58px;
        cursor: pointer;

        overflow: hidden;
    }
    .jssora22l { background-position: -10px -31px; }
    .jssora22r { background-position: -70px -31px; }
    .jssora22l:hover { background-position: -130px -31px; }
    .jssora22r:hover { background-position: -190px -31px; }
    .jssora22l.jssora22ldn { background-position: -250px -31px; }
    .jssora22r.jssora22rdn { background-position: -310px -31px; }
    .jssora22l.jssora22lds { background-position: -10px -31px; opacity: .3; pointer-events: none; }
    .jssora22r.jssora22rds { background-position: -70px -31px; opacity: .3; pointer-events: none; }
</style>

<div class="slider_box" style="margin-top:96px;">
    <div id="jssor_1" style="position: relative; margin: 0 auto; top: 0px; left: 0px; width: 1336px; height: 620px; visibility: hidden;">

        <div data-u="slides" style="cursor: default; position: relative; top: 0px; left: 0px; width: 1336px; height: 620px; ">

            <div data-b="0" >
                <img data-u="image" src="<?= $app['base_assets_url']; ?>images/slider.jpg" />
                <div class="banner_heading" data-u="caption" data-t="1" style="position: absolute; top: 100px; left: 240px; text-align: left;">Shop Securely Straight <br>
                    from Instagram & <br>
                    Beyond With Tagzie</div>





                <div style="position: absolute; bottom: 120px; left: 150px;">
                    <div  data-b="0" data-u="caption">
                  <table style="position: absolute;  left: -23px; top:23px" width="100" border="0" cellpadding="2" cellspacing="0" title="Click to Verify - This site chose Symantec SSL for secure e-commerce and confidential communications.">
<tr>
<td width="135" align="center" valign="top"><script type="text/javascript" src="https://seal.websecurity.norton.com/getseal?host_name=www.tagzie.com&amp;size=L&amp;use_flash=NO&amp;use_transparent=NO&amp;lang=en"></script><br />
</td>
</tr>
</table>
          
                    <img  class="bullets_bottom_slider1"  style="position: absolute;  left: 160px; width:450px; height: 80px; top:20px" src="<?= $app['base_assets_url']; ?>images/slider1_bottom.png" />
                          </div>
                </div>
            </div>
            <div data-b="0" style="display: none;">
                <img data-u="image" src="<?= $app['base_assets_url']; ?>images/slider5.jpg" />
                <div class="banner_heading" data-u="caption" data-t="1" style="position: absolute; top: 100px; left: 240px;  text-align: left;">Welcome to Tagzie:   <br>
                    Supercharged  <br>
                    Social Commerce  <br>
                </div>

                <div style="position: absolute; bottom: 110px; left: 110px;">
                    <img data-u="caption" data-b="0" class="bullets_bottom"  style="position: absolute;  left: 0px;  width: 755px; height: 67px;" src="<?= $app['base_assets_url']; ?>images/slider5_bottom.png" />
                </div>
            </div>
            
             <div data-b="0"  style="display: none;">
                <img data-u="image" src="<?= $app['base_assets_url']; ?>images/slider4.jpg" />
                <div class="banner_heading" data-u="caption" data-t="1" style="position: absolute; top: 100px; left: 240px;   text-align: left;">Easily Add, Post &   <br>
                    Manage Your  <br>
            Product Inventory 
                </div>

                <div style="position: absolute; bottom: 110px; left: 110px;">
                    <img data-u="caption" data-b="0" class="bullets_bottom"  style="position: absolute;  left: 0px; width: 755px; height: 67px;" src="<?= $app['base_assets_url']; ?>images/slider4_bottom.png" />
                </div>
            </div>

            <div data-b="0"  style="display: none;">
                <img data-u="image" src="<?= $app['base_assets_url']; ?>images/slider3.jpg" />

                <div class="banner_heading"  data-u="caption" data-t="1" style="position: absolute; top: 100px; left: 240px;  text-align: left;">Robust Sales   <br>
                  Manager Helping   <br>
                   You Manage Global <br>
                  Sales & Enquiries  
                </div>

      <div style="position: absolute; bottom: 110px; left: 110px;">
                    <img data-u="caption" data-b="0" class="bullets_bottom"  style="position: absolute;  left: 0px; width: 755px; height: 67px;" src="<?= $app['base_assets_url']; ?>images/slider3_bottom.png" />
                </div>
            </div>

            <div data-b="0"  style="display: none;">
                <img data-u="image" src="<?= $app['base_assets_url']; ?>images/slider2.jpg" />
                <div class="banner_heading" data-u="caption" data-t="1" style="position: absolute; top: 100px; left: 240px; text-align: left;">Visual Reports To  <br>
                    Help You Keep <br>
                    Track Of Sales <br>
                Performance
                </div>
                <div style="position: absolute; bottom: 110px; left: 110px;">
                    <img data-u="caption" data-b="0"  class="bullets_bottom"  style="position: absolute;  left: 0px; width: 755px; height: 67px;" src="<?= $app['base_assets_url']; ?>images/slider2_bottom.png" />
                </div>
            </div>

        </div>

        <div class="app_icons_download" style="position: absolute; top: 300px; left: 200px; width: 90px; height: 104px;">
            <a href="<?= $app['android_play_url']; ?>" target="_blank"><img data-u="caption" data-t="12" style="position: absolute; top: 22px; left: 270px; width: 137px; height: 45px;" src="<?= $app['base_assets_url']; ?>images/app_story.png" /></a>
            <a href="<?= $app['ios_appstore_url']; ?>" target="_blank"><img data-u="caption" data-t="12" style="position: absolute; top: 22px; left: 430px; width: 128px; height: 45px;" src="<?= $app['base_assets_url']; ?>images/black_aapstor.png" /></a>

        </div>

        <div data-u="navigator" class="jssorb05" style="bottom:16px;right:16px;" data-autocenter="1">
            <!-- bullet navigator item prototype -->
            <div data-u="prototype" style="width:16px;height:16px;"></div>
        </div>

    </div>



    <div class="mobile_main_box">
        <img  src="<?= $app['base_assets_url']; ?>images/socil_commers_mobile1.png" />
    </div>



    <div id="jssor_2" style="position: relative; margin: 0 auto; top: 0px; left: 0px; width: 267px; height: 541px; ">

        <div data-u="slides" style="cursor: default; position: relative; top: 0px; left: 0px; width: 267px; height: 541px; ">

            <div data-b="0"  idle="5000" >

                <img data-u="image" class="phone_slider_main" src="<?= $app['base_assets_url']; ?>images/mobile_slide1.png" />



                <div data-u="caption"  style="position: absolute; left:88px; top:350px; width: 100px; height: 100px;">

                    <img data-u="caption" data-t="16" style="position: absolute;  width: 45px; height: 45px;" src="<?= $app['base_assets_url']; ?>images/orange_load.gif" />



                </div>

            </div>

            <div data-b="0" idle="3000"   style="display: none;">
                <img data-u="image" class="phone_slider_main" src="<?= $app['base_assets_url']; ?>images/mobile_slide2.png" />


                <div data-u="caption" data-t="11" data-to="0% 100%" style="position: absolute; left:3px; top:159px;  width: 410px; height: 630px; overflow: hidden;">
                    <div style="position: absolute;  width: 312px; height: 377px; overflow: hidden; ">
                        <img data-u="caption" data-t="4" style="position: absolute; width: 212px; height: 731px;" src="<?= $app['base_assets_url']; ?>images/scroll3.jpg" />
                    </div>

                  <!--  <img data-u="caption" data-t="5" style="position: absolute; top: 222px; left: 74px; width: 108px; height: 108px;" src="<?= $app['base_assets_url']; ?>images/circle-hollow.png" />
                    <img data-u="caption" data-t="6" style="position: absolute; top: 231px; left: 83px; width: 90px; height: 90px;" src="<?= $app['base_assets_url']; ?>images/circle-solid.png" />
                    <img data-u="caption" data-t="7" style="position: absolute; top: 300px; left: 235px; width: 63px; height: 77px;" src="<?= $app['base_assets_url']; ?>images/c-finger-pointing.png" />
                    <img data-u="caption" data-t="8" style="position: absolute; top: 204px; right: 300px; width: 20px; height: 75px;" src="<?= $app['base_assets_url']; ?>images/swipe-arrow.png" />-->
                </div>


            </div>

            <div data-b="0" idle="8000"  >

                <img data-u="image" class="phone_slider_main" src="<?= $app['base_assets_url']; ?>images/mobile_slide3.png" />




            </div>


            <div data-b="0" idle="8000">

                <img data-u="image" class="phone_slider_main" src="<?= $app['base_assets_url']; ?>images/mobile_slide4.png" />




            </div>


            <div data-b="0" idle="8500">

                <img data-u="image" class="phone_slider_main" src="<?= $app['base_assets_url']; ?>images/mobile_slide5.png" />




            </div>



        </div>



    </div>


</div>



<div class="image_effect" id="products">

    <?php include_once $this->getPart('/web/general/components/product_list.php'); ?>

</div>
<?php if(count($products) > 30): ?>
<div class="view_more_box"><a href="#" id="load-more-products" data-page="1">View More</a></div>
<?php endif; ?>
<div class="check_outstp">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-4 information_detail col-md-push-8">
                    <img class="img-responsive bysell" src="<?= $app['base_assets_url']; ?>images/bysell_tagzie.jpg" alt="">
                </div>
                <div class="col-md-8 col-md-pull-4">
                    <div class="chack_demain edit_ordr">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <h1>Buy & Sell on Instagram and Beyond</h1>
                                <p>Welcome to Tagzie, an innovative and secure way of buying and selling on Instagram</p>
                                <p>Simply <span>download our app,</span>  register using your Instagram account, then shop directly from your newsfeed by simply replying <span>#tagzie</span>  in the comments on a Tagzie enabled post.</p>
                                <p>We have countless sellers joining the Tagzie revolution each and every day!</p>
                            </div>
                        </div>
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


            <div class="col-md-12">
                <div class="col-md-4 information_detail">
                    <img class="img-responsive bysell flot_seuty" src="<?= $app['base_assets_url']; ?>images/security_img.jpg" alt="">
                </div>
                <div class="col-md-8">
                    <div class="chack_demain edit_ordr">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                               <h1>Serious Security</h1>
                                <p>Tagzie takes security and your privacy very seriously.</p>
                                <p>Payments are processed by our industry partners using the latest, safest technology.</p>
                                <P>None of your payment details are shared with the seller - they are not even shared with
us. They are stored directly in the ultra secure vault of our payment processing partner, Tagzie doesn't even get to see them!</P>
                                <p><span class="shop_tagzie"><a href="<?= $app['base_url']; ?>buying">Shop with confidence on Tagzie.</a></span></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>

<script type="text/javascript">
    jssor_1_slider_init = function () {





        var jssor_1_SlideshowTransitions = [
            {$Duration: 1500, $Opacity: 2}
        ];



        var jssor_1_SlideoTransitions = [
            [{b: -1, d: 1, o: -1}, {b: 0, d: 1200, y: 300, o: 1, e: {y: 24, o: 6}}, {b: 5600, d: 800, x: -200, o: -1, e: {y: 5}}],
            [{b: -1, d: 1, o: -1}, {b: 400, d: 800, x: 200, o: 1, e: {x: 27, o: 6}}, {b: 5600, d: 800, x: -200, o: -1, e: {x: 5}}],
            [{b: -1, d: 1, o: -1}, {b: 400, d: 800, x: -200, o: 1, e: {x: 27, o: 6}}, {b: 5600, d: 800, x: 200, o: -1, e: {x: 5}}],
            [{b: -1, d: 1, o: -1}, {b: 1600, d: 600, x: 200, y: -200, o: 1}, {b: 5600, d: 800, o: -1}],
            [{b: 4600, d: 760, y: -204}],
            [{b: -1, d: 1, sX: -1, sY: -1}, {b: 3400, d: 400, sX: 1, sY: 1}, {b: 3800, d: 300, o: -1, sX: 0.1, sY: 0.1}],
            [{b: -1, d: 1, sX: -1, sY: -1}, {b: 3520, d: 400, sX: 1, sY: 1}, {b: 3920, d: 300, o: -1, sX: 0.1, sY: 0.1}],
            [{b: -1, d: 1, o: -1}, {b: 2200, d: 1200, x: -135, y: -24, o: 1, e: {x: 7, y: 7}}, {b: 4600, d: 640, y: -130}],
            [{b: -1, d: 1, o: -1}, {b: 4600, d: 240, y: -75, o: 1, e: {y: 1}}, {b: 4840, d: 480, y: -150, e: {y: 1}}, {b: 5320, d: 240, y: -75, o: -1, e: {y: 1}}],
            [{b: 2800, d: 600, y: 70, sX: -0.5, sY: -0.5, e: {y: 5}}, {b: 6000, d: 600, y: 50, r: -10}, {b: 7000, d: 400, o: -1, rX: 10, rY: -10}],
            [{b: 0, d: 600, x: -742, sX: 4, sY: 4, e: {x: 6}}, {b: 900, d: 600, sX: -4, sY: -4}],
            [{b: -1, d: 1, o: -1}, {b: 400, d: 500, o: 1, e: {o: 5}}],
            [{b: -1, d: 1, o: -1, r: -180}, {b: 1500, d: 500, o: 1, r: 180, e: {r: 27}}],
            [{b: -1, d: 1, o: -1, r: 180}, {b: 2000, d: 500, o: 1, r: -180, e: {r: 27}}],
            [{b: 2800, d: 600, y: -270, e: {y: 6}}],
            [{b: 6000, d: 600, y: -100, r: -10, e: {y: 6}}, {b: 7000, d: 400, o: -1, rX: -10, rY: 10}],
            [{b: -1, d: 1, sX: -1, sY: -1}, {b: 3400, d: 400, sX: 1.33, sY: 1.33, e: {sX: 7, sY: 7}}, {b: 3800, d: 200, sX: -0.33, sY: -0.33, e: {sX: 16, sY: 16}}],
            [{b: -1, d: 1, o: -1}, {b: 3400, d: 600, o: 1}, {b: 4000, d: 1000, r: 360, e: {r: 1}}],
            [{b: -1, d: 1, o: -1}, {b: 3400, d: 600, y: -70, o: 1, e: {y: 27}}],
            [{b: -1, d: 1, sX: -1, sY: -1}, {b: 3700, d: 400, sX: 1.33, sY: 1.33, e: {sX: 7, sY: 7}}, {b: 4100, d: 200, sX: -0.33, sY: -0.33, e: {sX: 16, sY: 16}}],
            [{b: -1, d: 1, o: -1}, {b: 3700, d: 600, o: 1}, {b: 4300, d: 1000, r: 360}],
            [{b: -1, d: 1, o: -1}, {b: 3700, d: 600, x: -150, o: 1, e: {x: 27}}],
            [{b: -1, d: 1, sX: -1, sY: -1}, {b: 4000, d: 400, sX: 1.33, sY: 1.33, e: {sX: 7, sY: 7}}, {b: 4400, d: 200, sX: -0.33, sY: -0.33, e: {sX: 16, sY: 16}}],
            [{b: -1, d: 1, o: -1}, {b: 4000, d: 600, o: 1}, {b: 4600, d: 1000, r: 360}],
            [{b: -1, d: 1, o: -1}, {b: 4000, d: 600, x: 150, o: 1, e: {x: 27}}],
            [{b: 9300, d: 600, o: -1, r: 540, sX: -0.5, sY: -0.5, e: {r: 5}}],
            [{b: -1, d: 1, o: -1, sX: 2, sY: 2}, {b: 6880, d: 20, o: 1}, {b: 6900, d: 300, sX: -2.08, sY: -2.08, e: {sX: 27, sY: 27}}, {b: 7200, d: 240, sX: 0.08, sY: 0.08}],
            [{b: -1, d: 1, o: -1, sX: 5, sY: 5}, {b: 6300, d: 600, o: 1, sX: -5, sY: -5}],
            [{b: -1, d: 1, o: -1}, {b: 7200, d: 440, o: 1}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 7420, d: 20, o: 1}, {b: 7440, d: 200, r: 180, sX: 0.4, sY: 0.4}, {b: 7640, d: 200, r: 180, sX: 0.5, sY: 0.5}],
            [{b: -1, d: 1, o: -1, r: -60, sX: -0.9, sY: -0.9}, {b: 7620, d: 20, o: 1}, {b: 7640, d: 300, r: 60, sX: 1.1, sY: 1.1}, {b: 7940, d: 160, sX: -0.2, sY: -0.2}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 7920, d: 20, o: 1}, {b: 7940, d: 300, sX: 1.4, sY: 1.4}, {b: 8240, d: 160, sX: -0.5, sY: -0.5}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 7620, d: 20, o: 1}, {b: 7640, d: 200, r: 180, sX: 0.4, sY: 0.4}, {b: 7840, d: 200, r: 180, sX: 0.5, sY: 0.5}],
            [{b: -1, d: 1, o: -1, r: -60, sX: -0.9, sY: -0.9}, {b: 7820, d: 20, o: 1}, {b: 7840, d: 300, r: 60, sX: 1.1, sY: 1.1}, {b: 8140, d: 160, sX: -0.2, sY: -0.2}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 8120, d: 20, o: 1}, {b: 8140, d: 300, sX: 1.4, sY: 1.4}, {b: 8440, d: 160, sX: -0.5, sY: -0.5}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 7820, d: 20, o: 1}, {b: 7840, d: 200, r: 180, sX: 0.4, sY: 0.4}, {b: 8040, d: 200, r: 180, sX: 0.5, sY: 0.5}],
            [{b: -1, d: 1, o: -1, r: -60, sX: -0.9, sY: -0.9}, {b: 8020, d: 20, o: 1}, {b: 8040, d: 300, r: 60, sX: 1.1, sY: 1.1}, {b: 8340, d: 160, sX: -0.2, sY: -0.2}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 8320, d: 20, o: 1}, {b: 8340, d: 300, sX: 1.4, sY: 1.4}, {b: 8640, d: 160, sX: -0.5, sY: -0.5}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 8020, d: 20, o: 1}, {b: 8040, d: 200, r: 180, sX: 0.4, sY: 0.4}, {b: 8240, d: 200, r: 180, sX: 0.5, sY: 0.5}],
            [{b: -1, d: 1, o: -1, r: -60, sX: -0.9, sY: -0.9}, {b: 8220, d: 20, o: 1}, {b: 8240, d: 300, r: 60, sX: 1.1, sY: 1.1}, {b: 8540, d: 160, sX: -0.2, sY: -0.2}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 8520, d: 20, o: 1}, {b: 8540, d: 300, sX: 1.4, sY: 1.4}, {b: 8840, d: 160, sX: -0.5, sY: -0.5}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 8220, d: 20, o: 1}, {b: 8240, d: 200, r: 180, sX: 0.4, sY: 0.4}, {b: 8440, d: 200, r: 180, sX: 0.5, sY: 0.5}],
            [{b: -1, d: 1, o: -1, r: -60, sX: -0.9, sY: -0.9}, {b: 8420, d: 20, o: 1}, {b: 8440, d: 300, r: 60, sX: 1.1, sY: 1.1}, {b: 8740, d: 160, sX: -0.2, sY: -0.2}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 8720, d: 20, o: 1}, {b: 8740, d: 300, sX: 1.4, sY: 1.4}, {b: 9040, d: 160, sX: -0.5, sY: -0.5}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 8220, d: 20, o: 1}, {b: 8240, d: 200, r: 180, sX: 0.4, sY: 0.4}, {b: 8440, d: 200, r: 180, sX: 0.5, sY: 0.5}],
            [{b: -1, d: 1, o: -1, r: -60, sX: -0.9, sY: -0.9}, {b: 8420, d: 20, o: 1}, {b: 8440, d: 300, r: 60, sX: 1.1, sY: 1.1}, {b: 8740, d: 160, sX: -0.2, sY: -0.2}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 8720, d: 20, o: 1}, {b: 8740, d: 300, sX: 1.4, sY: 1.4}, {b: 9040, d: 160, sX: -0.5, sY: -0.5}],
            [{b: -1, d: 1, sX: -0.2, sY: -0.2}, {b: 0, d: 400, y: 330}, {b: 900, d: 400, y: 50, rX: 80}, {b: 21000, d: 1000, y: -95, rX: -80, sX: 0.2, sY: 0.2, e: {y: 16, rX: 16, sX: 16, sY: 16}}, {b: 23000, d: 900, y: 25, o: -1, rX: 60}],
            [{b: -1, d: 1, o: -0.5}, {b: 900, d: 400, o: -0.5}],
            [{b: -1, d: 1, sX: -0.2, sY: -0.2}, {b: 1700, d: 400, y: 310}, {b: 2600, d: 400, y: 50, rX: 80}, {b: 20000, d: 1000, y: 20}, {b: 21000, d: 1000, y: -95, rX: -80, sX: 0.2, sY: 0.2, e: {y: 16, rX: 16, sX: 16, sY: 16}}, {b: 23000, d: 900, y: 25, o: -1, rX: 60}],
            [{b: 20000, d: 1000, o: -1}],
            [{b: -1, d: 1, o: -0.5}, {b: 2600, d: 400, o: -0.5}],
            [{b: -1, d: 1, sX: -0.2, sY: -0.2}, {b: 3400, d: 400, y: 290}, {b: 5100, d: 400, y: 50, rX: 80}, {b: 20000, d: 1000, y: 40}, {b: 21000, d: 1000, y: -95, rX: -80, sX: 0.2, sY: 0.2, e: {y: 16, rX: 16, sX: 16, sY: 16}}, {b: 23000, d: 900, y: 25, o: -1, rX: 60}],
            [{b: 20000, d: 1000, o: -1}],
            [{b: -1, d: 1, c: {t: -280}}, {b: 3880, d: 20, c: {t: 50.40}}, {b: 3980, d: 20, c: {t: 33.60}}, {b: 4080, d: 20, c: {t: 30.80}}, {b: 4180, d: 20, c: {t: 30.80}}, {b: 4280, d: 20, c: {t: 33.60}}, {b: 4380, d: 20, c: {t: 22.40}}, {b: 4480, d: 20, c: {t: 28.00}}, {b: 4580, d: 20, c: {t: 50.40}}],
            [{b: -1, d: 1, o: -0.5}, {b: 5100, d: 400, o: -0.5}],
            [{b: -1, d: 1, sX: -0.2, sY: -0.2}, {b: 6000, d: 400, y: 270}, {b: 6900, d: 400, y: 50, rX: 40}, {b: 15000, d: 500, rX: 40}, {b: 20000, d: 1000, y: 60}, {b: 21000, d: 1000, y: -95, rX: -80, sX: 0.2, sY: 0.2, e: {y: 16, rX: 16, sX: 16, sY: 16}}, {b: 23000, d: 900, y: 25, o: -1, rX: 60}],
            [{b: 20000, d: 1000, o: -1}],
            [{b: 6900, d: 400, o: -0.2}, {b: 15000, d: 500, o: -0.8}],
            [{b: -1, d: 1, o: -0.5}, {b: 15000, d: 500, o: -0.5}],
            [{b: -1, d: 1, sX: -0.2, sY: -0.2}, {b: 7800, d: 400, y: 270}, {b: 8700, d: 400, y: 50, rX: 40}, {b: 15000, d: 500, rX: 40}, {b: 20000, d: 1000, y: 60}, {b: 21000, d: 1000, y: -95, rX: -80, sX: 0.2, sY: 0.2, e: {y: 16, rX: 16, sX: 16, sY: 16}}, {b: 23000, d: 900, y: 25, o: -1, rX: 60}],
            [{b: 8700, d: 400, o: -1}],
            [{b: -1, d: 1, o: -0.5}, {b: 8700, d: 400, o: -0.5}],
            [{b: -1, d: 1, sX: -0.2, sY: -0.2}, {b: 9600, d: 400, y: 270}, {b: 10500, d: 400, y: 50, rX: 40}, {b: 15000, d: 500, rX: 40}, {b: 20000, d: 1000, y: 60}, {b: 21000, d: 1000, y: -95, rX: -80, sX: 0.2, sY: 0.2, e: {y: 16, rX: 16, sX: 16, sY: 16}}, {b: 23000, d: 900, y: 25, o: -1, rX: 60}],
            [{b: 10500, d: 400, o: -1}],
            [{b: -1, d: 1, o: -0.5}, {b: 10500, d: 400, o: -0.5}],
            [{b: -1, d: 1, sX: -0.2, sY: -0.2}, {b: 11400, d: 400, y: 270}, {b: 12300, d: 400, y: 50, rX: 40}, {b: 15000, d: 500, rX: 40}, {b: 20000, d: 1000, y: 60}, {b: 21000, d: 1000, y: -95, rX: -80, sX: 0.2, sY: 0.2, e: {y: 16, rX: 16, sX: 16, sY: 16}}, {b: 23000, d: 900, y: 25, o: -1, rX: 60}],
            [{b: 12300, d: 400, o: -1}],
            [{b: -1, d: 1, o: -0.5}, {b: 12300, d: 400, o: -0.5}],
            [{b: -1, d: 1, sX: -0.2, sY: -0.2}, {b: 13200, d: 400, y: 270}, {b: 14100, d: 400, y: 50, rX: 40}, {b: 15000, d: 500, rX: 40}, {b: 20000, d: 1000, y: 60}, {b: 21000, d: 1000, y: -95, rX: -80, sX: 0.2, sY: 0.2, e: {y: 16, rX: 16, sX: 16, sY: 16}}, {b: 23000, d: 900, y: 25, o: -1, rX: 60}],
            [{b: 14100, d: 400, o: -1}],
            [{b: -1, d: 1, o: -0.5}, {b: 14100, d: 400, o: -0.5}],
            [{b: -1, d: 1, sX: -0.2, sY: -0.2}, {b: 16000, d: 400, y: 270}, {b: 19100, d: 400, y: 30, rX: 80}, {b: 20000, d: 1000, y: 80}, {b: 21000, d: 1000, y: -95, rX: -80, sX: 0.2, sY: 0.2, e: {y: 16, rX: 16, sX: 16, sY: 16}}, {b: 23000, d: 900, y: 25, o: -1, rX: 60}],
            [{b: 20000, d: 1000, o: -1}],
            [{b: -1, d: 1, o: -0.5}, {b: 19100, d: 400, o: -0.5}],
            [{b: -1, d: 1, o: -1}, {b: 16400, d: 300, o: 1}, {b: 16700, d: 500, x: -238}],
            [{b: -1, d: 1, o: -1}, {b: 16400, d: 300, o: 1}, {b: 16700, d: 500, x: 238}],
            [{b: -1, d: 1, o: -1}, {b: 17000, d: 400, y: 200, o: 1, e: {y: 2, o: 6}}, {b: 17400, d: 300, y: -28, e: {y: 3}}, {b: 17700, d: 300, y: 28, e: {y: 2}}],
            [{b: -1, d: 1, o: -1}, {b: 17200, d: 400, y: 200, o: 1, e: {y: 2, o: 6}}, {b: 17600, d: 300, y: -28, e: {y: 3}}, {b: 17900, d: 300, y: 28, e: {y: 2}}],
            [{b: -1, d: 1, o: -1}, {b: 17400, d: 400, y: 200, o: 1, e: {y: 2, o: 6}}, {b: 17800, d: 300, y: -28, e: {y: 3}}, {b: 18100, d: 300, y: 28, e: {y: 2}}],
            [{b: -1, d: 1, o: -1}, {b: 17600, d: 400, y: 200, o: 1, e: {y: 2, o: 6}}, {b: 18000, d: 300, y: -28, e: {y: 3}}, {b: 18300, d: 300, y: 28, e: {y: 2}}]



        ];

        var jssor_1_options = {
            $AutoPlay: true,
            $ArrowKeyNavigation: false,
            $AutoPlayInterval: 1500,
            $SlideDuration: 1500,
            $PauseOnHover: 0,
            $DragOrientation: 0,
            $SlideEasing: $Jease$.$OutQuint,
            $SlideshowOptions: {
                $Class: $JssorSlideshowRunner$,
                $Transitions: jssor_1_SlideshowTransitions,
                $TransitionsOrder: 1
            },
            $CaptionSliderOptions: {
                $Class: $JssorCaptionSlideo$,
                $Transitions: jssor_1_SlideoTransitions,
                $Breaks: [
                    [{d: 2000, b: 5600, t: 2}],
                    [{d: 2000, b: 9300, t: 2}],
                    [{d: 2000, b: 23000}]
                ]
            },
            $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$
            },
            $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$
            }
        };


        var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

        /*responsive code begin*/
        /*you can remove responsive code if you don't want the slider scales while window resizing*/
        function ScaleSlider() {
            var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
            if (refSize) {
                refSize = Math.min(refSize, 1920);
                jssor_1_slider.$ScaleWidth(refSize);
            } else {
                window.setTimeout(ScaleSlider, 30);
            }
        }
        ScaleSlider();
        $Jssor$.$AddEvent(window, "load", ScaleSlider);
        $Jssor$.$AddEvent(window, "resize", ScaleSlider);
        $Jssor$.$AddEvent(window, "orientationchange", ScaleSlider);
        /*responsive code end*/
    };
</script>

<script type="text/javascript">
    jssor_2_slider_init = function () {

        var jssor_2_SlideshowTransitions = [
            {$Duration: 1500, $Opacity: 2}
        ];

        var jssor_2_SlideoTransitions = [
            [{b: -1, d: 1, o: -1}, {b: 0, d: 1200, y: 300, o: 1, e: {y: 24, o: 6}}, {b: 5600, d: 800, x: -200, o: -1, e: {y: 5}}],
            [{b: -1, d: 1, o: -1}, {b: 400, d: 800, x: 200, o: 1, e: {x: 27, o: 6}}, {b: 5600, d: 800, x: -200, o: -1, e: {x: 5}}],
            [{b: -1, d: 1, o: -1}, {b: 400, d: 800, x: -200, o: 1, e: {x: 27, o: 6}}, {b: 5600, d: 800, x: 200, o: -1, e: {x: 5}}],
            [{b: -1, d: 1, o: -1}, {b: 1600, d: 600, x: 200, y: -200, o: 1}, {b: 5600, d: 800, o: -1}],
            [{b: 4600, d: 760, y: -204}],
            [{b: -1, d: 1, sX: -1, sY: -1}, {b: 3400, d: 400, sX: 1, sY: 1}, {b: 3800, d: 300, o: -1, sX: 0.1, sY: 0.1}],
            [{b: -1, d: 1, sX: -1, sY: -1}, {b: 3520, d: 400, sX: 1, sY: 1}, {b: 3920, d: 300, o: -1, sX: 0.1, sY: 0.1}],
            [{b: -1, d: 1, o: -1}, {b: 2200, d: 1200, x: -135, y: -24, o: 1, e: {x: 7, y: 7}}, {b: 4600, d: 640, y: -130}],
            [{b: -1, d: 1, o: -1}, {b: 4600, d: 240, y: -75, o: 1, e: {y: 1}}, {b: 4840, d: 480, y: -150, e: {y: 1}}, {b: 5320, d: 240, y: -75, o: -1, e: {y: 1}}],
            [{b: 2800, d: 600, y: 70, sX: -0.5, sY: -0.5, e: {y: 5}}, {b: 6000, d: 600, y: 50, r: -10}, {b: 7000, d: 400, o: -1, rX: 10, rY: -10}],
            [{b: 0, d: 600, x: -742, sX: 4, sY: 4, e: {x: 6}}, {b: 900, d: 600, sX: -4, sY: -4}],
            [{b: -1, d: 1, o: -1}, {b: 400, d: 500, o: 1, e: {o: 5}}],
            [{b: -1, d: 1, o: -1, r: -180}, {b: 1500, d: 500, o: 1, r: 180, e: {r: 27}}],
            [{b: -1, d: 1, o: -1, r: 180}, {b: 2000, d: 500, o: 1, r: -180, e: {r: 27}}],
            [{b: 2800, d: 600, y: -270, e: {y: 6}}],
            [{b: 6000, d: 600, y: -100, r: -10, e: {y: 6}}, {b: 7000, d: 400, o: -1, rX: -10, rY: 10}],
            [{b: -1, d: 1, sX: -1, sY: -1}, {b: 3400, d: 400, sX: 1.33, sY: 1.33, e: {sX: 7, sY: 7}}, {b: 3800, d: 200, sX: -0.33, sY: -0.33, e: {sX: 16, sY: 16}}],
            [{b: -1, d: 1, o: -1}, {b: 3400, d: 600, o: 1}, {b: 4000, d: 1000, r: 360, e: {r: 1}}],
            [{b: -1, d: 1, o: -1}, {b: 3400, d: 600, y: -70, o: 1, e: {y: 27}}],
            [{b: -1, d: 1, sX: -1, sY: -1}, {b: 3700, d: 400, sX: 1.33, sY: 1.33, e: {sX: 7, sY: 7}}, {b: 4100, d: 200, sX: -0.33, sY: -0.33, e: {sX: 16, sY: 16}}],
            [{b: -1, d: 1, o: -1}, {b: 3700, d: 600, o: 1}, {b: 4300, d: 1000, r: 360}],
            [{b: -1, d: 1, o: -1}, {b: 3700, d: 600, x: -150, o: 1, e: {x: 27}}],
            [{b: -1, d: 1, sX: -1, sY: -1}, {b: 4000, d: 400, sX: 1.33, sY: 1.33, e: {sX: 7, sY: 7}}, {b: 4400, d: 200, sX: -0.33, sY: -0.33, e: {sX: 16, sY: 16}}],
            [{b: -1, d: 1, o: -1}, {b: 4000, d: 600, o: 1}, {b: 4600, d: 1000, r: 360}],
            [{b: -1, d: 1, o: -1}, {b: 4000, d: 600, x: 150, o: 1, e: {x: 27}}],
            [{b: 9300, d: 600, o: -1, r: 540, sX: -0.5, sY: -0.5, e: {r: 5}}],
            [{b: -1, d: 1, o: -1, sX: 2, sY: 2}, {b: 6880, d: 20, o: 1}, {b: 6900, d: 300, sX: -2.08, sY: -2.08, e: {sX: 27, sY: 27}}, {b: 7200, d: 240, sX: 0.08, sY: 0.08}],
            [{b: -1, d: 1, o: -1, sX: 5, sY: 5}, {b: 6300, d: 600, o: 1, sX: -5, sY: -5}],
            [{b: -1, d: 1, o: -1}, {b: 7200, d: 440, o: 1}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 7420, d: 20, o: 1}, {b: 7440, d: 200, r: 180, sX: 0.4, sY: 0.4}, {b: 7640, d: 200, r: 180, sX: 0.5, sY: 0.5}],
            [{b: -1, d: 1, o: -1, r: -60, sX: -0.9, sY: -0.9}, {b: 7620, d: 20, o: 1}, {b: 7640, d: 300, r: 60, sX: 1.1, sY: 1.1}, {b: 7940, d: 160, sX: -0.2, sY: -0.2}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 7920, d: 20, o: 1}, {b: 7940, d: 300, sX: 1.4, sY: 1.4}, {b: 8240, d: 160, sX: -0.5, sY: -0.5}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 7620, d: 20, o: 1}, {b: 7640, d: 200, r: 180, sX: 0.4, sY: 0.4}, {b: 7840, d: 200, r: 180, sX: 0.5, sY: 0.5}],
            [{b: -1, d: 1, o: -1, r: -60, sX: -0.9, sY: -0.9}, {b: 7820, d: 20, o: 1}, {b: 7840, d: 300, r: 60, sX: 1.1, sY: 1.1}, {b: 8140, d: 160, sX: -0.2, sY: -0.2}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 8120, d: 20, o: 1}, {b: 8140, d: 300, sX: 1.4, sY: 1.4}, {b: 8440, d: 160, sX: -0.5, sY: -0.5}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 7820, d: 20, o: 1}, {b: 7840, d: 200, r: 180, sX: 0.4, sY: 0.4}, {b: 8040, d: 200, r: 180, sX: 0.5, sY: 0.5}],
            [{b: -1, d: 1, o: -1, r: -60, sX: -0.9, sY: -0.9}, {b: 8020, d: 20, o: 1}, {b: 8040, d: 300, r: 60, sX: 1.1, sY: 1.1}, {b: 8340, d: 160, sX: -0.2, sY: -0.2}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 8320, d: 20, o: 1}, {b: 8340, d: 300, sX: 1.4, sY: 1.4}, {b: 8640, d: 160, sX: -0.5, sY: -0.5}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 8020, d: 20, o: 1}, {b: 8040, d: 200, r: 180, sX: 0.4, sY: 0.4}, {b: 8240, d: 200, r: 180, sX: 0.5, sY: 0.5}],
            [{b: -1, d: 1, o: -1, r: -60, sX: -0.9, sY: -0.9}, {b: 8220, d: 20, o: 1}, {b: 8240, d: 300, r: 60, sX: 1.1, sY: 1.1}, {b: 8540, d: 160, sX: -0.2, sY: -0.2}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 8520, d: 20, o: 1}, {b: 8540, d: 300, sX: 1.4, sY: 1.4}, {b: 8840, d: 160, sX: -0.5, sY: -0.5}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 8220, d: 20, o: 1}, {b: 8240, d: 200, r: 180, sX: 0.4, sY: 0.4}, {b: 8440, d: 200, r: 180, sX: 0.5, sY: 0.5}],
            [{b: -1, d: 1, o: -1, r: -60, sX: -0.9, sY: -0.9}, {b: 8420, d: 20, o: 1}, {b: 8440, d: 300, r: 60, sX: 1.1, sY: 1.1}, {b: 8740, d: 160, sX: -0.2, sY: -0.2}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 8720, d: 20, o: 1}, {b: 8740, d: 300, sX: 1.4, sY: 1.4}, {b: 9040, d: 160, sX: -0.5, sY: -0.5}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 8220, d: 20, o: 1}, {b: 8240, d: 200, r: 180, sX: 0.4, sY: 0.4}, {b: 8440, d: 200, r: 180, sX: 0.5, sY: 0.5}],
            [{b: -1, d: 1, o: -1, r: -60, sX: -0.9, sY: -0.9}, {b: 8420, d: 20, o: 1}, {b: 8440, d: 300, r: 60, sX: 1.1, sY: 1.1}, {b: 8740, d: 160, sX: -0.2, sY: -0.2}],
            [{b: -1, d: 1, o: -1, sX: -0.9, sY: -0.9}, {b: 8720, d: 20, o: 1}, {b: 8740, d: 300, sX: 1.4, sY: 1.4}, {b: 9040, d: 160, sX: -0.5, sY: -0.5}],
            [{b: -1, d: 1, sX: -0.2, sY: -0.2}, {b: 0, d: 400, y: 330}, {b: 900, d: 400, y: 50, rX: 80}, {b: 21000, d: 1000, y: -95, rX: -80, sX: 0.2, sY: 0.2, e: {y: 16, rX: 16, sX: 16, sY: 16}}, {b: 23000, d: 900, y: 25, o: -1, rX: 60}],
            [{b: -1, d: 1, o: -0.5}, {b: 900, d: 400, o: -0.5}],
            [{b: -1, d: 1, sX: -0.2, sY: -0.2}, {b: 1700, d: 400, y: 310}, {b: 2600, d: 400, y: 50, rX: 80}, {b: 20000, d: 1000, y: 20}, {b: 21000, d: 1000, y: -95, rX: -80, sX: 0.2, sY: 0.2, e: {y: 16, rX: 16, sX: 16, sY: 16}}, {b: 23000, d: 900, y: 25, o: -1, rX: 60}],
            [{b: 20000, d: 1000, o: -1}],
            [{b: -1, d: 1, o: -0.5}, {b: 2600, d: 400, o: -0.5}],
            [{b: -1, d: 1, sX: -0.2, sY: -0.2}, {b: 3400, d: 400, y: 290}, {b: 5100, d: 400, y: 50, rX: 80}, {b: 20000, d: 1000, y: 40}, {b: 21000, d: 1000, y: -95, rX: -80, sX: 0.2, sY: 0.2, e: {y: 16, rX: 16, sX: 16, sY: 16}}, {b: 23000, d: 900, y: 25, o: -1, rX: 60}],
            [{b: 20000, d: 1000, o: -1}],
            [{b: -1, d: 1, c: {t: -280}}, {b: 3880, d: 20, c: {t: 50.40}}, {b: 3980, d: 20, c: {t: 33.60}}, {b: 4080, d: 20, c: {t: 30.80}}, {b: 4180, d: 20, c: {t: 30.80}}, {b: 4280, d: 20, c: {t: 33.60}}, {b: 4380, d: 20, c: {t: 22.40}}, {b: 4480, d: 20, c: {t: 28.00}}, {b: 4580, d: 20, c: {t: 50.40}}],
            [{b: -1, d: 1, o: -0.5}, {b: 5100, d: 400, o: -0.5}],
            [{b: -1, d: 1, sX: -0.2, sY: -0.2}, {b: 6000, d: 400, y: 270}, {b: 6900, d: 400, y: 50, rX: 40}, {b: 15000, d: 500, rX: 40}, {b: 20000, d: 1000, y: 60}, {b: 21000, d: 1000, y: -95, rX: -80, sX: 0.2, sY: 0.2, e: {y: 16, rX: 16, sX: 16, sY: 16}}, {b: 23000, d: 900, y: 25, o: -1, rX: 60}],
            [{b: 20000, d: 1000, o: -1}],
            [{b: 6900, d: 400, o: -0.2}, {b: 15000, d: 500, o: -0.8}],
            [{b: -1, d: 1, o: -0.5}, {b: 15000, d: 500, o: -0.5}],
            [{b: -1, d: 1, sX: -0.2, sY: -0.2}, {b: 7800, d: 400, y: 270}, {b: 8700, d: 400, y: 50, rX: 40}, {b: 15000, d: 500, rX: 40}, {b: 20000, d: 1000, y: 60}, {b: 21000, d: 1000, y: -95, rX: -80, sX: 0.2, sY: 0.2, e: {y: 16, rX: 16, sX: 16, sY: 16}}, {b: 23000, d: 900, y: 25, o: -1, rX: 60}],
            [{b: 8700, d: 400, o: -1}],
            [{b: -1, d: 1, o: -0.5}, {b: 8700, d: 400, o: -0.5}],
            [{b: -1, d: 1, sX: -0.2, sY: -0.2}, {b: 9600, d: 400, y: 270}, {b: 10500, d: 400, y: 50, rX: 40}, {b: 15000, d: 500, rX: 40}, {b: 20000, d: 1000, y: 60}, {b: 21000, d: 1000, y: -95, rX: -80, sX: 0.2, sY: 0.2, e: {y: 16, rX: 16, sX: 16, sY: 16}}, {b: 23000, d: 900, y: 25, o: -1, rX: 60}],
            [{b: 10500, d: 400, o: -1}],
            [{b: -1, d: 1, o: -0.5}, {b: 10500, d: 400, o: -0.5}],
            [{b: -1, d: 1, sX: -0.2, sY: -0.2}, {b: 11400, d: 400, y: 270}, {b: 12300, d: 400, y: 50, rX: 40}, {b: 15000, d: 500, rX: 40}, {b: 20000, d: 1000, y: 60}, {b: 21000, d: 1000, y: -95, rX: -80, sX: 0.2, sY: 0.2, e: {y: 16, rX: 16, sX: 16, sY: 16}}, {b: 23000, d: 900, y: 25, o: -1, rX: 60}],
            [{b: 12300, d: 400, o: -1}],
            [{b: -1, d: 1, o: -0.5}, {b: 12300, d: 400, o: -0.5}],
            [{b: -1, d: 1, sX: -0.2, sY: -0.2}, {b: 13200, d: 400, y: 270}, {b: 14100, d: 400, y: 50, rX: 40}, {b: 15000, d: 500, rX: 40}, {b: 20000, d: 1000, y: 60}, {b: 21000, d: 1000, y: -95, rX: -80, sX: 0.2, sY: 0.2, e: {y: 16, rX: 16, sX: 16, sY: 16}}, {b: 23000, d: 900, y: 25, o: -1, rX: 60}],
            [{b: 14100, d: 400, o: -1}],
            [{b: -1, d: 1, o: -0.5}, {b: 14100, d: 400, o: -0.5}],
            [{b: -1, d: 1, sX: -0.2, sY: -0.2}, {b: 16000, d: 400, y: 270}, {b: 19100, d: 400, y: 30, rX: 80}, {b: 20000, d: 1000, y: 80}, {b: 21000, d: 1000, y: -95, rX: -80, sX: 0.2, sY: 0.2, e: {y: 16, rX: 16, sX: 16, sY: 16}}, {b: 23000, d: 900, y: 25, o: -1, rX: 60}],
            [{b: 20000, d: 1000, o: -1}],
            [{b: -1, d: 1, o: -0.5}, {b: 19100, d: 400, o: -0.5}],
            [{b: -1, d: 1, o: -1}, {b: 16400, d: 300, o: 1}, {b: 16700, d: 500, x: -238}],
            [{b: -1, d: 1, o: -1}, {b: 16400, d: 300, o: 1}, {b: 16700, d: 500, x: 238}],
            [{b: -1, d: 1, o: -1}, {b: 17000, d: 400, y: 200, o: 1, e: {y: 2, o: 6}}, {b: 17400, d: 300, y: -28, e: {y: 3}}, {b: 17700, d: 300, y: 28, e: {y: 2}}],
            [{b: -1, d: 1, o: -1}, {b: 17200, d: 400, y: 200, o: 1, e: {y: 2, o: 6}}, {b: 17600, d: 300, y: -28, e: {y: 3}}, {b: 17900, d: 300, y: 28, e: {y: 2}}],
            [{b: -1, d: 1, o: -1}, {b: 17400, d: 400, y: 200, o: 1, e: {y: 2, o: 6}}, {b: 17800, d: 300, y: -28, e: {y: 3}}, {b: 18100, d: 300, y: 28, e: {y: 2}}],
            [{b: -1, d: 1, o: -1}, {b: 17600, d: 400, y: 200, o: 1, e: {y: 2, o: 6}}, {b: 18000, d: 300, y: -28, e: {y: 3}}, {b: 18300, d: 300, y: 28, e: {y: 2}}]


        ];

        var jssor_2_options = {
            $ArrowKeyNavigation: false,
            $AutoPlay: true,
            $AutoPlayInterval: 1500,
            $SlideDuration: 1500,
            $PauseOnHover: 0,
            $DragOrientation: 0,
            $SlideshowOptions: {
                $Class: $JssorSlideshowRunner$,
                $Transitions: jssor_2_SlideshowTransitions,
                $TransitionsOrder: 1
            },
            $CaptionSliderOptions: {
                $Class: $JssorCaptionSlideo$,
                $Transitions: jssor_2_SlideoTransitions
            },
            $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$
            },
            $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$
            }
        };

        var jssor_2_slider = new $JssorSlider$("jssor_2", jssor_2_options);

        /*responsive code begin*/
        /*you can remove responsive code if you don't want the slider scales while window resizing*/
        function ScaleSlider() {
            var refSize = jssor_2_slider.$Elmt.parentNode.clientWidth;
            if (refSize) {
                refSize = Math.min(refSize, 270);
                jssor_2_slider.$ScaleWidth(refSize);
            } else {
                window.setTimeout(ScaleSlider, 30);
            }
        }
        ScaleSlider();
        $Jssor$.$AddEvent(window, "load", ScaleSlider);
        $Jssor$.$AddEvent(window, "resize", ScaleSlider);
        $Jssor$.$AddEvent(window, "orientationchange", ScaleSlider);
        /*responsive code end*/
    };
</script>


<script type="text/javascript">jssor_2_slider_init();</script>

<script type="text/javascript">jssor_1_slider_init();</script>
<?php include_once $this->getPart('/web/general/common/footer.php'); ?>
<script>
    $(document).ready(function () {

        $('#load-more-products').click(function (e) {
            e.preventDefault();
            var data = {page: $(this).data('page')};

            $.when(getProductsView(data)).then(function (html) {
                
                if(html != ''){
                    $('#load-more-products').data('page', (parseInt($('#load-more-products').data('page'))+1));
                    $('#products').append(html);
                }
                                    hideLoader('#products');
            });
        });
    });
</script>