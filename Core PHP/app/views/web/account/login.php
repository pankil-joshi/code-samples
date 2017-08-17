<?php include_once $this->getPart('/web/common/header.php'); ?>
<div class="secure_pin">
    <h6><img src="<?= $app['base_assets_url']; ?>images/super_lock.png" class="img-resaponsive" alt="">Tagzie is in ULTRA SECURE MODE</h6>  
</div> 
<div class="login_body check_outstp">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                
                    <?php if(isset($_GET['redirect']) && $_GET['redirect'] == 'checkout' && isset($_SESSION['cart'])): ?>
                <div class="col-md-6" style="border-right:1px #ccc solid;">
                    <div class="guest_checkout">
                        <h6><img src="<?= $app['base_assets_url']; ?>images/check_gust.png" class="img-resaponsive" alt=""><span>Checkout as Guest</span></h6>
                        <p>You can complete this order without creating an Tagzie account.</p>
                        <p>You will not benefit from account perks such as:</p>
                        <ul>
                            <li>Effortless purchasing</li>
                            <li> Order history and tracking</li>
                            <li>Direct messaging</li>
                        </ul>
                        <p>To checkout as a Guest, simply provide your email address<br> below to proceed.</p>
                        <div class="email_addrs">
                            <form action="<?= $app['base_url']; ?>account/guest" method="POST">
                                <input type="text" class="inp_text form-control" placeholder="Email address..." name="email">
                                <button type="submit"><img src="<?= $app['base_assets_url']; ?>images/email_img.png" class="img-resaponsive" alt=""></button>
                            </form>
                        </div>
                    </div>
                    </div>
                    <?php endif; ?>
                
                <div class="col-md-<?php if(isset($_GET['redirect']) && $_GET['redirect'] == 'checkout' && isset($_SESSION['cart'])): ?>6<?php else: ?>12<?php endif; ?>">
                    <div class="guest_checkout register <?php if(!isset($_SESSION['cart'])): ?>regis_only_sign<?php endif; ?>">

                        <div class="<?php if(!isset($_SESSION['cart'])): ?>cebtr<?php endif; ?> guest_checkout">

                        <div class="<?php if(!isset($_GET['redirect']) || $_GET['redirect'] != 'checkout' || !isset($_SESSION['cart'])): ?>cebtr<?php endif; ?>">

                        <h6><img src="<?= $app['base_assets_url']; ?>images/register_sign.png" class="img-resaponsive" alt=""><span>Register or Sign In</span></h6>
                        <p>If you wish to register for an Tagzie account, or have registered previously,<br> simply sign in via Instagram below.</p>
                        <a href="#" id="instagramConnect"><img src="<?= $app['base_assets_url']; ?>images/login_instragrm.png" class="img-responsive login_inst" alt=""></a>
                    </div>  
                       </div> 
                </div> 
            </div>
        </div>
    </div>  
</div>
<style>
    .regis_only_sign{
        width:  838px;
        margin: 0 auto;
        float: none;
    }
.cebtr {
    width: 100%;
    float: left;
}
.cebtr h6 {
    text-align: center;
    font-size: 15px;
}
.cebtr p {
    text-align: center;
}
.cebtr .login_inst {
    float: none;
    margin: 0 auto;
    padding: 0;
    width: 384px;
    text-align: center;
}
.guest_checkout .cebtr h6 img {
    float: none;
    margin: 0 17px 0 0;
    width: 47px;
    text-align: center;
}
    
</style>
<script>
    $(document).ready(function () {

        /* login page */
        $('#instagramConnect').on('click', null, function (e) {

            e.preventDefault();
            var windowObjectReference = window.open(Data.instagram_connect_url, 'instagramConnect');
            var interval = setInterval(function () {
                try {

                    var hash = windowObjectReference.location.hash;

                    if (hash != '') {

                        clearInterval(interval);
                        var type = hash.split('=');

                        if (type[0] == "#success") {
                            window.location.href = <?php if (!empty($_GET['redirect'])): ?> '<?= $app['base_url'] . $_GET['redirect']; ?>' <?php else: ?> Routes.customer_dashboard <?php endif; ?>;
                        } else if (type[0] == "#error") {

                            var error = hash.substr(7);
                            error = decodeURIComponent(type[1]);
                            toastr.error(replaceAll(error, '+', ' '));
                        }

                        windowObjectReference.close();
                    }
                } catch (evt) {
                }
            }, 50);
        });
    });
</script>
<?php include_once $this->getPart('/web/common/footer.php'); ?>