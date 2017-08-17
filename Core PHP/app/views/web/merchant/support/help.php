<?php
include_once $this->getPart('/web/merchant/common/header.php');
include_once $this->getPart('/web/merchant/common/sidebar.php');
?>
<div class="col-md-11 col-sm-10 col-xs-10 pull-right">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="desbord_body check_outstp">
            <div class="row">
                <div class="lastest_marg">
                    <div class="yor_active" style="border:0">
                        <div class="order-active">
                            <div class="col-md-4 col-sm-5 col-xs-12">
                                <h6>Help & Support</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="adneadd">
                        <p>If you have any questions or need assistance with Tagzie, please do not hesitate to contact us. Our Support Team are ready to help resolve your query.</p>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-md-8 col-sm-12">
                            <div class="help_cont">
                                <ul>
                                    <li><span><a href="<?= $app['base_url']; ?>contact">Click here to log a support ticket (recommended)</a></span></li>
                                    <li>Call Tagzie on: <span>0800 086 9337 </span> (UK Freephone) / <span> +44 115 718 0117 </span> (Intl)</li>
                                    <li> Email Tagzie: <span><a href=mailto:support@tagzie.com>support@tagzie.com</a></span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once $this->getPart('/web/merchant/common/footer.php'); ?>