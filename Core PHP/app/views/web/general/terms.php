<?php include_once $this->getPart('/web/general/common/header.php'); ?>

<div class="buy_terms_frame">
    <div class="terms_stripe">
        <img src="<?= $app['base_assets_url']; ?>images/save.png">
    </div>
    <div class="container" id="top_section">
        <div class="row">
            <div class="col-md-12">
                <ul class="terms_list">
                    <li><a href="#buyer_terms">Buyer Terms and Conditions</a></li>
                    <li><a href="#seller_terms">Seller Terms and Conditions</a></li>
                </ul>

                <?php include_once $this->getPart('/web/general/static/customer-terms-content.php'); ?>

            </div>
        </div>

    </div>
</div>
<div class="sell_terms_outer" id="seller_terms">
    <div class="buy_terms_frame">
        <div class="terms_stripe">
            <img src="<?= $app['base_assets_url']; ?>images/save.png">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <a class="back_top" href="#top_section">Back To Top</a>
                    <?php include_once $this->getPart('/web/general/static/merchant-terms-content.php'); ?>
                    <p>Image Attribution

                        Copyright: <a href='http://www.123rf.com/profile_foxaon' rel=”nofollow”>foxaon / 123RF Stock Photo</a>



                        Copyright: <a href='http://www.123rf.com/profile_everythingpossible' rel=”nofollow”>everythingpossible / 123RF Stock Photo</a>



                        Copyright: <a href='http://www.123rf.com/profile_wetzkaz' rel=”nofollow”>wetzkaz / 123RF Stock Photo</a>



                        Copyright: <a href='http://www.123rf.com/profile_fgnopporn' rel=”nofollow”>fgnopporn / 123RF Stock Photo</a>



                        Copyright: <a href='http://www.123rf.com/profile_dogfella' rel=”nofollow”>dogfella / 123RF Stock Photo</a>



                        Copyright: <a href='http://www.123rf.com/profile_leaf' rel=”nofollow”>leaf / 123RF Stock Photo</a>



                        Copyright: <a href='http://www.123rf.com/profile_antonioguillem' rel=”nofollow”>antonioguillem / 123RF Stock Photo</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once $this->getPart('/web/general/common/footer.php'); ?>
<script>
    $(document).ready(function() {
        $('a[href*="#"]:not([href="#"])').click(function () {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top
                    }, 1000);
                    return false;
                }
            }
        });
    });
</script>