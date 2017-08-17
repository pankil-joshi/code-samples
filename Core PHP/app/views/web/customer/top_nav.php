<div class="secure_pin">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-4 mai_demain">
                    <div class="demai_img">
                        <a href="<?= $app['base_url']; ?>account/customer"><img src="<?= $user['instagram_profile_picture']; ?>" class="img-responsive user-instagram-profile-picture" alt=""></a> 
                    </div>
                    <div class="demain_txt">
                        <h6>Welcome back, <span class="user-first-name"><?= $user['first_name']; ?></span></h6>
                        <?php if (!empty($awaiting_feedback)): ?>
                            <p class="notification">
                                You have <span>items awaiting feedback</span>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-8 desbordnave">
                    <ul class="nav nav-pills">
                        <?php if ($user['is_merchant']): ?>
                            <li class="<?= find_active_url('account/merchant') ? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>account/merchant"><img src="<?= $app['base_assets_url']; ?>images/home_navi.png" class="img-responsive" alt="" style="height: 32px;
                                                                                                                                                           width: 40px;">Merchant Dashboard</a></li>
                                                                                                                                                       <?php endif; ?>                        
                        <li class="<?= find_active_url('account/customer') ? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>account/customer"><img src="<?= $app['base_assets_url']; ?>images/dasheboard.png" class="img-responsive" alt="">Customer Dashboard</a></li>
                        <li class="<?= find_active_url('account/customer/orders') ? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>account/customer/orders"><img src="<?= $app['base_assets_url']; ?>images/order.png" class="img-responsive" alt="">Orders</a></li>
                        <li class="<?= find_active_url('account/customer/disputes') ? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>account/customer/disputes"><img src="<?= $app['base_assets_url']; ?>images/disepute.png" class="img-responsive" alt="">Disputes</a></li>
                        <li class="<?= find_active_url('account/customer/addresses') ? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>account/customer/addresses"><img src="<?= $app['base_assets_url']; ?>images/adrees_book.png" class="img-responsive" alt="">Address Book</a></li>
                        <li class="<?= find_active_url('account/customer/wallet') ? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>account/customer/wallet"><img src="<?= $app['base_assets_url']; ?>images/wallet.png" class="img-responsive" alt="">Wallet</a></li>
                        <li class="<?= find_active_url('account/customer/settings') ? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>account/customer/settings"><img src="<?= $app['base_assets_url']; ?>images/seting_new.png" class="img-responsive" alt="">Settings</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div> 