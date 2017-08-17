<div class="col-md-1 col-sm-2 col-xs-2"style="padding:0">
    <div class="navi_menu">
        <ul class="main_ul">
            <li class="<?= strpos($_SERVER['REQUEST_URI'], 'admin/dashboard') !== false ? 'active' : ''; ?>">
                <a href="<?= $app['base_url'] ?>admin/dashboard/"><img src="<?= $app['base_assets_url']; ?>images/home.png" class="img-responsive" alt=""></a>
<!--                <ul class="inner_ul">
                    <li class="active"><a href="#"><img src="<?= $app['base_assets_url']; ?>images/carrer_up.png" class="img-responsive" alt=""></a></li>
                    <li><a href="#"><img src="<?= $app['base_assets_url']; ?>images/landecape.png" class="img-responsive" alt=""></a></li>
                    <li><a href="#"><img src="<?= $app['base_assets_url']; ?>images/message.png" class="img-responsive" alt=""></a></li>
                    <li><a href="#"><img src="<?= $app['base_assets_url']; ?>images/setting.png" class="img-responsive" alt=""></a></li>
                </ul>-->
            </li>
            <li class="<?= strpos($_SERVER['REQUEST_URI'], 'admin/users/') !== false || strpos($_SERVER['REQUEST_URI'], 'admin/users/edit') !== false ? 'active' : ''; ?>">
                <a href="<?= $app['base_url'] ?>admin/users/"><img src="<?= $app['base_assets_url']; ?>images/grop.png" class="img-responsive" alt=""></a>
<!--                <ul class="inner_ul">
                    <li class="active"><a href="#"><img src="<?= $app['base_assets_url']; ?>images/carrer_up.png" class="img-responsive" alt=""></a></li>
                    <li><a href="#"><img src="<?= $app['base_assets_url']; ?>images/landecape.png" class="img-responsive" alt=""></a></li>
                    <li><a href="#"><img src="<?= $app['base_assets_url']; ?>images/message.png" class="img-responsive" alt=""></a></li>


                </ul>-->
            </li>
<!--            <li class="<?= strpos($_SERVER['REQUEST_URI'], 'admin/users/merchants') !== false ? 'active' : ''; ?>">
                <a href="<?= $app['base_url'] ?>admin/orders/merchants"><img src="<?= $app['base_assets_url']; ?>images/man.png" class="img-responsive" alt=""></a>
                <ul class="inner_ul">
                    <li class="active"><a href="#"><img src="<?= $app['base_assets_url']; ?>images/carrer_up.png" class="img-responsive" alt=""></a></li>
                    <li><a href="#"><img src="<?= $app['base_assets_url']; ?>images/landecape.png" class="img-responsive" alt=""></a></li>

                    <li><a href="#"><img src="<?= $app['base_assets_url']; ?>images/setting.png" class="img-responsive" alt=""></a></li>

                </ul>
            </li>
            <li><img src="<?= $app['base_assets_url']; ?>images/error.png" class="img-responsive" alt="">
                <ul class="inner_ul">

                    <li class="active"><a href="#"><img src="<?= $app['base_assets_url']; ?>images/carrer_up.png" class="img-responsive" alt=""></a></li>

                    <li><a href="#"><img src="<?= $app['base_assets_url']; ?>images/message.png" class="img-responsive" alt=""></a></li>
                    <li><a href="#"><img src="<?= $app['base_assets_url']; ?>images/setting.png" class="img-responsive" alt=""></a></li>
                </ul>
            </li>-->
        </ul>
    </div>    
</div>