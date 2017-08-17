<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title><?= !empty($meta['title']) ? $meta['title'] : ''; ?> </title>
        <?php if (!empty($meta['description'])): ?><meta name="description" content="<?= $meta['description']; ?>"><?php endif; ?>
        <?php if (!empty($meta['keywords'])): ?><meta name="keywords" content="<?= $meta['keywords']; ?>"><?php endif; ?>
        <link href="<?= $app['base_assets_url']; ?>css/bootstrap.min.css" rel="stylesheet">
        <link href="<?= $app['base_assets_url']; ?>css/home-style.css" rel="stylesheet">
        <link href="<?= $app['base_assets_url']; ?>css/style.css" rel="stylesheet">
        <link rel="icon" href="<?= $app['base_assets_url']; ?>images/favicon.ico">
        <link href="<?= $app['base_assets_url']; ?>css/dev.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Ubuntu:regular,bold&subset=Latin">
        <script src="<?= $app['base_assets_url']; ?>js/jssor.slider-21.1.6.min.js" type="text/javascript"></script>
        <link href="<?= $app['base_assets_url']; ?>toastr-master/build/toastr.min.css" rel="stylesheet">

        <!-- Google Analytic Code Start Here-->
        <script>
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

            ga('create', 'UA-90284014-1', 'auto');
            ga('send', 'pageview');

        </script>
        <!-- Google Analytic Code Start Here-->

        <script type="text/javascript">

<?php if (isset($data)): ?>
                var Data = <?php echo json_encode($data); ?>;
<?php endif; ?>

        </script>
        <script src="<?= $app['base_assets_url']; ?>js/routes.js?<?= time(); ?>" type="text/javascript"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="<?= $app['base_assets_url']; ?>toastr-master/build/toastr.min.js" type="text/javascript"></script>
        <script src="<?= $app['base_assets_url']; ?>js/helpers.js?<?= time(); ?>" type="text/javascript"></script>
        <script src="<?= $app['base_assets_url']; ?>Parsley.js-2.4.4/dist/parsley.min.js?<?= time(); ?>" type="text/javascript"></script>        
        <style type="text/css">
            body {
                font-family: 'Ubuntu', sans-serif;
            }
        </style>
    </head>

    <body role="document" id="<?= (!empty($meta['page-name'])) ? $meta['page-name'] : ''; ?>">
        <div class="wrapper webtemp">
              <div class="header_fix_top_margin">
            <div class="header_bar heder_fix">
                <div class="container">
                    <div class="row">

                        <div class="col-md-2">
                            <a href="<?= $app['base_url']; ?>"><img src="<?= $app['base_assets_url']; ?>images/shdow_instpas.png" class="img-responsive hederlog" alt=""></a>
                        </div>
                        <div class="col-md-10 navigation navi_hover">
                            <ul class="nav nav-pills">
                                <?php //if (!empty($meta['page']['name']) && $meta['page']['name'] == 'home'): ?>
                                <li class="<?= find_active_url('about') ? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>about">About Tagzie</a></li>
                                <li class="<?= find_active_url('buying') ? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>buying">Buying</a></li>
                                <li class="<?= find_active_url('selling') ? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>selling">Selling</a></li>
                                <li class="<?= find_active_url('faq') ? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>faq">FAQs</a></li>
                                <li class="<?= find_active_url('how-it-works') ? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>how-it-works">How It Works </a></li>
                                <li class="<?= find_active_url('contact') ? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>contact">Contact Tagzie</a></li>
                                <li class="pull-right"><?php if (!empty($user) && $user['is_active']): ?> <a href="<?= $app['base_url']; ?>account/logout">Log Out</a><?php endif; ?></li>
                                <li class="pull-right"><a href="<?= $app['base_url']; ?>account/customer">My Account</a></li>


                                <?php //else: ?>
<!--                                    <li><a href="<?= $app['base_url']; ?>legal/terms">Terms and Conditions</a></li>
    <li><a href="<?= $app['base_url']; ?>legal/refund">Refund Policy</a></li>
    <li><a href="<?= $app['base_url']; ?>legal/privacy">Privacy Policy </a></li>-->
                                <?php //endif; ?>
                            </ul>
                            <div class="menu_repondv ">
                                <nav class="navbar navbar-default navbar-fixed-top">
                                    <div class="navbar-header">
                                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                                            <span class="sr-only">Toggle navigation</span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                        </button>
                                        <a href="<?= $app['base_url']; ?>"><img src="<?= $app['base_assets_url']; ?>images/shdow_instpas.png" class="img-responsive hederlog1" alt=""></a>
                                    </div>
                                    <div id="navbar" class="navbar-collapse collapse">
                                        <ul class="nav navbar-nav">
                                            <?php //if (!empty($meta['page']['name']) && $meta['page']['name'] == 'home'): ?>
                                            <li class="<?= find_active_url('about') ? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>about">About Tagzie</a></li>
                                            <li class="<?= find_active_url('buying') ? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>buying">Buying</a></li>
                                            <li class="<?= find_active_url('selling') ? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>selling">Selling</a></li>
                                            <li class="<?= find_active_url('faq') ? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>faq">FAQs</a></li>
                                            <li class="<?= find_active_url('how-it-works') ? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>how-it-works">How It Works </a></li>
                                            <li class="<?= find_active_url('contact') ? 'active' : '' ?>"><a href="<?= $app['base_url']; ?>contact">Contact Tagzie</a></li>
                                            <li><a href="<?= $app['base_url']; ?>account/customer">My Account</a></li>
                                            <li><?php if (!empty($user) && $user['is_active']): ?> <a href="<?= $app['base_url']; ?>account/logout">Log Out</a><?php endif; ?></li>


                                            <?php //else: ?>
<!--                                                <li><a href="<?= $app['base_url']; ?>legal/terms">Terms and Conditions</a></li>
                    <li><a href="<?= $app['base_url']; ?>legal/refund">Refund Policy</a></li>
                    <li><a href="<?= $app['base_url']; ?>legal/privacy">Privacy Policy </a></li>
                    <li><a href="<?= $app['base_url']; ?>legal/cookie">Cookie</a></li>-->
                                            <?php //endif; ?>
                                        </ul>
                                    </div>
                                    <!--/.nav-collapse -->
                                </nav>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div class="secure_pin secure_pin1">
                <h6><span><img alt="" class="img-resaponsive" src="<?= $app['base_assets_url']; ?>images/super_lock.png">Social commerce powered by Tagzie  &nbsp;&nbsp;&nbsp;&nbsp;|</span>Buy and sell over your favorite social networks</h6>
            </div>
            </div>