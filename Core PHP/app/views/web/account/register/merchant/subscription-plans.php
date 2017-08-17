<?php include_once $this->getPart('/web/common/header.php'); ?>
<div class="secure_pin">
    <h6><img src="<?= $app['base_assets_url']; ?>images/super_lock.png" class="img-resaponsive" alt="">Tagzie is in ULTRA SECURE MODE</h6>  
</div>
<section id="subscriptionForm">

    <header>
        <div class="container">
            <div class="subscript">
                <div class="col-xs-12">
                    <h6>Tagzie Subscription</h1>
                </div>
            </div>
        </div>
    </header>

    <div class="subscript_outter">
        <div class="container">
            <?php foreach ($subscriptionPackages as $package): ?>
                <div class="subscript_box">
                    <div class="row">
                        <div class="col-md-8">
                            <h4><?= $package['name']; ?> Seller</h4>
                            <h5>UK &amp; EU transaction fee: <span> <?= $package['eu_transaction_fee']; ?>%</span></h5>                            
                            <h5>Transaction fee:<span> <?= $package['transaction_fee']; ?>%</span></h5>
                            <h5>Funds transferred: <span><?= $package['payment_threshold']; ?> days</span></h5>
                            <div class="subscript_price">
                                <p>Cost: <?php echo $price = ($package['discount_enabled']) ? '<del>£' . number_format($package['rate']) . '</del> £' . number_format($package['discounted_rate']) : '£' . number_format($package['rate']); ?> / month</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <img src="<?= $app['base_assets_url']; ?>images/<?= $package['image_name']; ?>" class="img-responsive plan-icon" alt="">
                        </div>
                    </div>    
                    <div class="row">
                        <div class="col-md-12">
                            <a href="<?= $app['base_url']; ?>account/merchant/signup/choose-plan/<?= $package['id']; ?>" class="showmore_button detail_style <?= (!empty($user['merchant_subscription_package_id']) && $package['id'] == $user['merchant_subscription_package_id']) ? 'disabled' : ''; ?>">
                                <?php if (!empty($user['merchant_subscription_package_id']) && $package['id'] == $user['merchant_subscription_package_id']): ?>Selected Plan <?php else: ?>Show More Details<?php endif; ?>
                            </a>
                        </div>                        
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</section>
<?php include_once $this->getPart('/web/common/footer.php'); ?>