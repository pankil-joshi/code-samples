<?php include_once $this->getPart('/web/general/common/header.php'); ?>

<div class="main_about_page buying_page how_it_work">
    <div class="banner_about"><img src="<?= $app['base_assets_url']; ?>images/how_it_work_banner.jpg" /></div>
<!--    <div class="banner_about position_loader">
        <div class="loader-box">
                <div class="loader-static loader-2"></div>
            </div>
        <img src="<?= $app['base_assets_url']; ?>images/how_it_work_banner.jpg" />
    </div>-->




    <div class="container">
        <div class="main_about_middle_cont">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="bread_main_pedding">
                    <h1 style="margin-bottom:4">How does it all work?</h1>
                    <div class="verb_txt buying_shop">
                        <ul>
                            <li>As a customer, simply <span> reply "#tagzie"  </span>
                                on a Tagzie enabled Instagram post to begin checking out.</li>
                            <li>As a seller, using the Tagzie App enter your product details and publish onto your Instagram feed..</li>
                            <li>Watch the short clips below to see how simple it really is!</li>
                        </ul>
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



            <div class="col-md-4 col-sm-12 col-xs-12 information_detail">
                <a class="how_works_img_vid"  data-target="#customer_vid" data-toggle="modal"> <img class="img-responsive bysell flot_seuty" src="<?= $app['base_assets_url']; ?>images/customer_video_mobile.png" alt="">
                </a>




                <div class="modal fade in video_pop_up" tabindex="-1" role="dialog" id="customer_vid" >
                    <div class="modal-dialog" role="document">
                        <div class="table_row"><div class="table_cell">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                        <h4 class="modal-title">Customer Video</h4>
                                    </div>

                                    <div class="video_box">
                                        <video  controls>
                                            <source src="<?= $app['base_assets_url']; ?>resources/videos/ios-tagzie-for-customers.mp4" type="video/mp4">

                                            Your browser does not support the video tag.
                                        </video> 
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div></div>


            </div>
            <div class="col-md-8 col-sm-12 col-xs-12">
                <div class="chack_demain edit_ordr">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="bread_main_pedding ">
                                <h1 style="margin-bottom:0">Customer</h1>
                                <h4>How to purchase from Instagram</h4>
                                <ul>
                                
                                <li>- Find a Tagzie-enabled post</li>
                                    <li>- Either comment <span> "#tagzie" </span> on the post or follow the link to purchase on website
                                    </li>
                                    <li>- Wait for a checkout notification on your device</li>
                                    <li>- Swipe through the checkout screens, selecting your delivery address and payment method</li>
                                    <li></li>
                                </ul>
                                
                                <b>Online shopping couldn’t be simpler...</b>
                            </div>
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




        <div class="col-md-5 col-md-push-7 col-sm-12 col-xs-12 information_detail">
            <a class="how_works_img_vid"  data-target="#seller_vid" data-toggle="modal"><img alt="" src="<?= $app['base_assets_url']; ?>images/breand_video_mobile.png" class="img-responsive bysell flot_seuty"></a>

            <div class="modal fade in video_pop_up" tabindex="-1" role="dialog" id="seller_vid" >
                <div class="modal-dialog" role="document">
                    <div class="table_row"><div class="table_cell">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                    <h4 class="modal-title">Seller Video</h4>
                                </div>

                                <div class="video_box">
                                    <video  controls>
                                        <source src="<?= $app['base_assets_url']; ?>resources/videos/ios-tagzie-for-sellers.mp4" type="video/mp4">

                                        Your browser does not support the video tag.
                                    </video> 
                                </div>

                            </div>
                        </div>
                    </div>
                </div></div>
        </div>

        <div class="col-md-7 col-md-pull-5 col-sm-12 col-xs-12">
            <div class="chack_demain edit_ordr">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="bread_main_pedding">
                            <h1 style="margin-bottom:0">Brands</h1>
                            <h4>How to publish on Instagram</h4>
                            <ul>
                            <li>- Enter your product details</li>
                            <li>- Fill out your shipping and tax details (where applicable)</li>
                                <li>- Publish your product directly onto Instagram</li>                           
                            </ul>
                            <b>Sit back and wait for the sales to come in!</b>
                        </div>
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
</div>
</div>
<script>
    $(document).ready(function () {

        $("#customer_vid").on("hidden.bs.modal", function () {
            $("video").each(function () {
                this.pause()
            });
        });
        $("#seller_vid").on("hidden.bs.modal", function () {
            $("video").each(function () {
                this.pause()
            });
        });
    });
</script>
<?php include_once $this->getPart('/web/general/common/footer.php'); ?>
