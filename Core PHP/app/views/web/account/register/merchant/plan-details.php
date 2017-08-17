<?php include_once $this->getPart('/web/common/header.php'); ?>
<div class="secure_pin">
    <h6><img src="<?= $app['base_assets_url']; ?>images/super_lock.png" class="img-resaponsive" alt="">Tagzie is in ULTRA SECURE MODE</h6>  
</div>
<section class="platinum_frame" >
    <!--<div class="platinum_bg platinum_mem" style="background-image: url(<?= $app['base_assets_url']; ?>images/<?= $package['background_image']; ?>)"></div>-->
    <div class="container">
        <div class="subscript_popup">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="platinium_box">
                            <h1><?= $package['name']; ?> Membership</h1> 
                            <?= $package['description']; ?>
                        </div>
                        <div class="membership_details">  
                            <ul class="members_list">
                                <li>UK &amp; EU transaction fee: <?= $package['eu_transaction_fee']; ?>%*</li>
                                <li>Transaction fee: <?= $package['transaction_fee']; ?>%*</li>
                                <li>Funds desposited: <?= $package['payment_threshold']; ?> days</li>
                                <li>Verified badge: <?= ($package['verified_badge']) ? 'Yes <img class="platinum_icon" src="' . $app['base_assets_url'] . 'images/member_check.png">' : 'No'; ?></li>
                                <li>Top seller badge: <?= ($package['top_seller_badge']) ? 'Eligible <img class="platinum_icon" src="' . $app['base_assets_url'] . 'images/member_trophy.png">' : 'Ineligible'; ?></li>
                                <li>Priority support <?php if ($package['support_phone']): ?><img class="platinum_icon" src="<?= $app['base_assets_url']; ?>images/member_call.png"><?php endif; ?><?php if ($package['support_email']): ?><img class="platinum_icon"  src="<?= $app['base_assets_url']; ?>images/member_email.png"><?php endif; ?><?php if ($package['support_chat']): ?><img class="platinum_icon" src="<?= $app['base_assets_url']; ?>images/member_chat.png"><?php endif; ?></li>
                                <?php if ($package['new_features_access']): ?>
                                    <li>First to access new features</li>
                                <?php endif; ?>
                            </ul>
                            <p class="pre_text">* + 20p transaction fee</p>
                        </div>
                    </div>
                </div>
                <a href="<?= (empty($user['merchant_subscription_package_id'])) ? $app['base_url'] . 'account/merchant/signup/details/' . $package['id'] : '#'; ?>" class="showmore_button Platinum_button <?= (!empty($user['merchant_subscription_package_id']) && $package['id'] != $user['merchant_subscription_package_id']) ? 'disabled' : ''; ?>" <?php if (!empty($user['merchant_subscription_package_id']) && $package['id'] != $user['merchant_subscription_package_id']): ?>data-toggle="modal" data-target="#change-subscription-modal"<?php endif; ?>> 
                    <?php if (!empty($user['merchant_subscription_package_id']) && $package['id'] == $user['merchant_subscription_package_id']): ?>Selected Plan <?php else: ?>Subscribe to Tagzie <?= $package['name']; ?><br>
                        <span>£<?= $package['rate']; ?> / month</span><?php endif; ?>

                </a>
            </div>
        </div>
    </div>
</section>

    <div class="col-md-12">
        <div class="modal fade" id="change-subscription-modal" role="dialog">
            <div class="modal-dialog model_width model_responsive">

                <!-- Modal content-->
                <div class="modal-content message-modal-body">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                        <h4 class="modal-title">Change Subscription Plan</h4>
                    </div>
                    <div class="modal-body review_style">
                        <div id="" class="">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="subscription_pack">
                                        <h4>Subscription package:</h4>
                                    </div>
                                    <span class="selected-package"><?= $package['name']; ?></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="subscription_pack">
                                        <h4> Price:</h4>

                                    </div>
                                    <span class="symbol_plan">£<span class="selected-subscription-price"><?= $package['rate']; ?> / month</span></span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="subscribe_btn">
                                    <form id="change-subscription-form" autocomplete="off">
                                        <input type="hidden" value="<?= $package['id']; ?>" name="planid">
                                        <button type="button" id="change-plan"> Subscribe  </button>
                                    </form>
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
  
<div class="modal fade width_dialog" id="merchant-success-modal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
                <h4 class="modal-title">Plan Changed Successful</h4>
            </div>
            <div class="modal-body">
                <p>Thank you for joining the Tagzie Marketplace and helping to shape Tagzie’s Social Commerce Revolution!</p>
                <p>We’ve just sent you an email confirming your subscription level, including some useful links and information to help you get started.</p>
                <p>You can manage your merchant account entirely from the Tagzie App, or if you’re using a computer/laptop you can view your Merchant Dashboard via: <a href="https://www.tagzie.com/account/merchant" class="primary-orange">tagzie.com/account/merchant</a></p>
                <p>You may be required to upload an identification document to verify your account. You will see a notification about this when you next load the app. We will email you with a checklist of what information is required.</p>
            </div>
            <div class="modal-footer">
                <a href="<?php if (getOS() == 'Android'): ?>intent://dashboard.html#Intent;scheme=ipaid;package=<?= $app['android_package_name']; ?>;end<?php elseif (getOS() == 'iOS'): ?>ipaid://dashboard.html<?php endif; ?>" class="btn btn-default orange" >Back to App</a>

            </div>
        </div>

    </div>
</div>
<script>     

$(document).ready(function () {
        $('#change-plan').click(function () {

            var data = {};
            var formData = $('#change-subscription-form').serializeObject();

            data.body = formData;

            $.when(changeSubscriptionPlan(data)).then(function (data) {
                hideLoader('#change-subscription-modal .modal-content');
                if (data.meta.success) {
<?php if (getOS() == 'Android' || getOS() == 'iOS'): ?>
                        $('#change-subscription-modal').modal('hide');
                        $('#merchant-success-modal').modal('show');
<?php else: ?>
                        window.location.href = '<?= $app['base_url']; ?>account/merchant';
<?php endif; ?>

                }
            });
        });    
});
</script>
<?php include_once $this->getPart('/web/common/footer.php'); ?>
