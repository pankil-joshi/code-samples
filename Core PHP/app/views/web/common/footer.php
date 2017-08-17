</div>
</div>
<footer <?= (!empty($meta['page-name']) && $meta['page-name'] == 'product-redirect')? 'style="display:none"' : ''; ?>>
    <div class="secure_pin">
        <h6><span>Social commerce powered by Tagzie &nbsp;&nbsp;&nbsp;&nbsp;|</span>Buy and sell over your favorite social networks</h6>  
    </div> 
    <div class="header_bar">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-2">
                        <a href="#"><img alt="" class="img-responsive foot_logo" src="<?= $app['base_assets_url']; ?>images/shdow_instpas.png"></a> 
                    </div>
                    <div class="col-md-10 navigation navigato1">
                        <ul class="nav nav-pills">
                            <li><a href="<?= $app['base_url']; ?>about">About Tagzie</a></li>
                            <li><a href="<?= $app['base_url']; ?>buying">Buying</a></li>
                            <li><a href="<?= $app['base_url']; ?>selling">Selling</a></li>
                            <li><a href="<?= $app['base_url']; ?>faq">FAQs</a></li>
                            <li><a href="<?= $app['base_url']; ?>how-it-works">How It Works</a></li>
                            <li><a href="<?= $app['base_url']; ?>contact">Contact Tagzie</a></li>
                            <li><a href="<?= $app['base_url']; ?>legal/terms">Terms and Conditions</a></li>
                            <li><a href="<?= $app['base_url']; ?>legal/refund">Refund Policy</a></li>
                            <li><a href="<?= $app['base_url']; ?>legal/privacy">Privacy Policy </a></li>
                            <li><a href="<?= $app['base_url']; ?>legal/cookie">Cookie</a></li>
                            <li><a href="<?= $app['base_url']; ?>account/customer">My Account</a></li>
                        </ul>
                    </div>
                    
                    <div class="col-md-12">
                        <p>Patent pending.</p>

                        <p>Disclaimer: Tagzie is a social commerce platform that allows sellers and businesses to sell over Instagram and other social networks. Tagzie is not affiliated with Instagram or any other social network in any way and is a separate service altogether. Whilst Tagzie closely moderates items listed on its marketplace, Tagzie accepts no responsibility for the products listed or outcomes of your purchase.</p>

                        <p>&copy; Tagzie Limited 2016. Company registered in England and Wales (Company No: 09956906). Registered address: 32 Thistlebank, East Leake, Loughborough, LE12 6RS, United Kingdom.</p>
                    </div>
                </div>  
            </div>
        </div>
    </div>    
</footer>  
<script src="<?= $app['base_assets_url']; ?>js/bootstrap.min.js"></script>
<script src="<?= $app['base_assets_url']; ?>js/jquery.barrating.js" type="text/javascript"></script>
<script src="<?= $app['base_assets_url']; ?>bootstrap-select/dist/js/bootstrap-select.min.js" type="text/javascript"></script>
<script src="<?= $app['base_assets_url']; ?>js/daterangepicker.js" type="text/javascript"></script>
<script src="<?= $app['base_assets_url']; ?>js/app.js" type="text/javascript"></script>
<?php if(!empty($meta['page-name']) && $meta['page-name'] == 'product'): ?>
<script type="application/ld+json">
//{ "@context": "http://schema.org",
//  "@type": "Product",
//  "name": "<?php //$media['title']; ?>",
//  "aggregateRating":
//    {"@type": "AggregateRating",
//     "ratingValue": "##RATING##"
//    }
//}
<?php endif; ?>
</script>
</body>
</html>
