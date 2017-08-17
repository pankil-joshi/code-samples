<div class="col-md-1 col-sm-2 col-xs-2" style="padding:0">
    <div class="navi_menu">
        <ul class="main_ul machent">
            <li class="<?= substr($_SERVER['REQUEST_URI'], -strlen('account/merchant')) == 'account/merchant'? 'active' : '' ?>">
                <a href="<?= $app['base_url']; ?>account/merchant"><img src="<?= $app['base_assets_url']; ?>images/home.png" class="img-responsive" alt=""></a> 
                    <!--<ul class="inner_ul">
                        <li class="<?= substr($_SERVER['REQUEST_URI'], -strlen('account/merchant')) == 'account/merchant'? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>account/merchant"><img src="<?= $app['base_assets_url']; ?>images/dasheboard.png" class="img-responsive" alt="" title="Dashboard"><label>Dashboard</label></a></li>
                        <li><a href="#"><img src="<?= $app['base_assets_url']; ?>images/carrer_up.png" class="img-responsive" alt=""></a></li>
                    </ul>-->
            </li>
            <li class="<?= strpos($_SERVER['REQUEST_URI'], 'account/merchant/orders') !==false || strpos($_SERVER['REQUEST_URI'], 'account/merchant/products') !==false? 'active' : '' ?>"><a><img src="<?= $app['base_assets_url']; ?>images/box.png" class="img-responsive" alt=""></a>
                <ul class="inner_ul">
                    <li class="<?= substr($_SERVER['REQUEST_URI'], -strlen('account/merchant/orders/overview')) == 'account/merchant/orders/overview'? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>account/merchant/orders/overview"><img src="<?= $app['base_assets_url']; ?>images/shopping_whit.png" class="img-responsive" alt="" title="Orders"><label>Orders</label></a></li>
                    <li class="<?= substr($_SERVER['REQUEST_URI'], -strlen('account/merchant/orders/customers')) == 'account/merchant/orders/customers'? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>account/merchant/orders/customers"><img src="<?= $app['base_assets_url']; ?>images/customer_icon.png" class="img-responsive" alt="" title="Customers"><label>Customers</label></a></li>
                    <li class="<?= substr($_SERVER['REQUEST_URI'], -strlen('account/merchant/products')) == 'account/merchant/products'? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>account/merchant/products"><img src="<?= $app['base_assets_url']; ?>images/t-shirt.png" class="img-responsive" alt="" title="Products"><label>Products</label></a></li>
                </ul>
            </li>

            <li class="<?= strpos($_SERVER['REQUEST_URI'], 'account/merchant/messages') !==false? 'active' : '' ?>"><a><img src="<?= $app['base_assets_url']; ?>images/message.png" class="img-responsive" alt=""></a>
                <ul class="inner_ul">
                    <li class="<?= substr($_SERVER['REQUEST_URI'], -strlen('account/merchant/messages/enquiries')) == 'account/merchant/messages/enquiries'? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>account/merchant/messages/enquiries"><img src="<?= $app['base_assets_url']; ?>images/messge_equte.png" class="img-responsive" alt="" title="Enquiries"><label>Enquiries</label></a></li>
                    <li class="<?= substr($_SERVER['REQUEST_URI'], -strlen('account/merchant/messages/disputes')) == 'account/merchant/messages/disputes'? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>account/merchant/messages/disputes"><img src="<?= $app['base_assets_url']; ?>images/message_dispute.png" class="img-responsive" alt="" title="Disputes"><label>Disputes</label></a></li>

                </ul>
            </li>    

            <li class="<?= strpos($_SERVER['REQUEST_URI'], 'account/merchant/earnings') !==false? 'active' : '' ?>"><a><img src="<?= $app['base_assets_url']; ?>images/doler.png" class="img-responsive" alt=""></a>
                <ul class="inner_ul">
                    <li class="<?= substr($_SERVER['REQUEST_URI'], -strlen('account/merchant/earnings/overview')) == 'account/merchant/earnings/overview'? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>account/merchant/earnings/overview"><img src="<?= $app['base_assets_url']; ?>images/earning_overview.png" class="img-responsive" alt="" title="Earnings"><label>Earnings</label></a></li>
                    <li class="<?= substr($_SERVER['REQUEST_URI'], -strlen('account/merchant/earnings/settlements')) == 'account/merchant/earnings/settlements'? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>account/merchant/earnings/settlements"><img src="<?= $app['base_assets_url']; ?>images/tagzie_invoice.png" class="img-responsive" alt="" title="Invoices"><label>Invoices</label></a></li>
                </ul>
            </li>

            <li class="<?= strpos($_SERVER['REQUEST_URI'], 'account/merchant/settings') !==false? 'active' : '' ?>"><a><img src="<?= $app['base_assets_url']; ?>images/setting-gears.png" class="img-responsive" alt=""></a>
                <ul class="inner_ul">
                    <li class="<?= substr($_SERVER['REQUEST_URI'], -strlen('account/merchant/settings/account')) == 'account/merchant/settings/account'? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>account/merchant/settings/account"><img src="<?= $app['base_assets_url']; ?>images/account_setting.png" class="img-responsive" alt="" title="Account Settings"><label>Account Settings</label></a></li>
                    <li class="<?= substr($_SERVER['REQUEST_URI'], -strlen('account/merchant/settings/postage')) == 'account/merchant/settings/postage'? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>account/merchant/settings/postage"><img src="<?= $app['base_assets_url']; ?>images/postage_template.png" class="img-responsive" alt="" title="Postage Templates"><label>Postage Templates</label></a></li>
                </ul>
            </li>        

            <li class="<?= strpos($_SERVER['REQUEST_URI'], 'account/merchant/support') !==false? 'active' : '' ?>"><img src="<?= $app['base_assets_url']; ?>images/team-spports.png" class="img-responsive" alt="">
                <ul class="inner_ul">
                    <li class="<?= substr($_SERVER['REQUEST_URI'], -strlen('account/merchant/support/help')) == 'account/merchant/support/help'? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>account/merchant/support/help"><img src="<?= $app['base_assets_url']; ?>images/help_supprt.png" class="img-responsive" alt="" title="Help & Support"><label>Help & Support</label></a></li>
                    <li><a href="<?= $app['base_url']; ?>contact"><img src="<?= $app['base_assets_url']; ?>images/light_support.png" class="img-responsive" alt="" title="Feedback">
                       <label>Feedback</label></a></li>
                    <li><a href="<?= $app['base_url']; ?>faq"><img src="<?= $app['base_assets_url']; ?>images/wifi_support.png" class="img-responsive" alt="" title="FAQ">
                        <label>FAQ</label></a></li>
                </ul>
            </li>        

        </ul>
    </div>    
</div>