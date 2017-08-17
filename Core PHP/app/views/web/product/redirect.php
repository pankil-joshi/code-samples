<?php include_once $this->getPart('/web/common/header.php'); ?>
<style>
    .width_dialog{
        width:100%;
        float: left;
        margin: 0;
        padding: 0 !important;
        height: 100%;
    }
    .width_dialog .modal-dialog{
        width:100%;
        float: left;
        margin: 0;
        padding: 0;
        height: 100%;
    } 
    .width_dialog .modal-content{
        width:100%;
        float: left;
        margin: 0;
        padding: 0;
        height: 100%;
    }
    .redirect-footer a {
        float: left;
        margin: 0 0 9px !important;
        padding: 9px 0;
        width: 100%;
    }
    .redirect-footer{
        position: absolute;
        bottom: 0;
        width: 100%;
        float: left;
        position: fixed;
        width: 100%;
        background: #fff;
        z-index: 1;
    }
    .redirect-footer .orange {
        background: rgb(255, 108, 0) !important;
        border: 0 none;
        color: rgb(255, 255, 255);
        float: left;
        margin: 0;
        width: 100%;
    }
    .redirect-footer .grey {
        background: rgb(160, 160, 160) none repeat scroll 0 0;
        border: 0 none;
        color: rgb(255, 255, 255);
    }
    body{
        
        overflow: hidden;
    }
</style>

<div class="redirect-footer produt_show" style="background-image: url(<?= $media['image_standard_resolution']; ?>);">
    <h1><?= $media['title']; ?></h1>
    <h6>Price: <?= $media['prices']['min_price'] == $media['prices']['max_price'] ? $currencies[$media['base_currency_code']] . $media['prices']['min_price'] : $currencies[$media['base_currency_code']] . $media['prices']['min_price'] . " - " . $currencies[$media['base_currency_code']] . $media['prices']['max_price']; ?>   
</h6>
    <p>@<?= $media['instagram_username']; ?></p>
    <a href="<?php if(getOS() == 'Android'): ?>intent://displayProduct.html#<?= $id; ?>#Intent;scheme=ipaid;package=<?= $app['android_package_name']; ?>;end<?php elseif(getOS() == 'iOS'): ?>ipaid://displayProduct.html#<?= $id; ?><?php endif; ?>" class="btn btn-default orange" >Open in App</a>
    <div class="dont_app">Don't have Tagzie app? <a href="<?php if(getOS() == 'Android'): ?>https://play.google.com/store/apps/details?id=com.Tagzie<?php elseif(getOS() == 'iOS'): ?>https://itunes.apple.com/us/app/tagzie/id1173808443<?php endif; ?>">Click here</a></div>
</div>
<?php include_once $this->getPart('/web/common/footer.php'); ?>