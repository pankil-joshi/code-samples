<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="<?= $app['base_assets_url']; ?>images/favicon.ico">

        <title><?= !empty($title) ? $title : ''; ?></title>
        <link href="<?= $app['base_assets_url']; ?>css/bootstrap.min.css" rel="stylesheet">
        <link href="<?= $app['base_assets_url']; ?>css/bootstrap-theme.min.css" rel="stylesheet">
        <link href="<?= $app['base_assets_url']; ?>css/admin_style.css" rel="stylesheet">
<!--        <link href="<?= $app['base_assets_url']; ?>css/ie10-viewport-bug-workaround.css" rel="stylesheet">-->
<!--        <link href="<?= $app['base_assets_url']; ?>theme.css" rel="stylesheet">-->
        <link href="<?= $app['base_assets_url']; ?>css/daterangepicker.css" rel="stylesheet" type="text/css"/>
        <link href="<?= $app['base_assets_url']; ?>css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css"/>
         <link href="<?= $app['base_assets_url']; ?>css/dev.css" rel="stylesheet" type="text/css"/>
        <link href="<?= $app['base_assets_url']; ?>toastr-master/build/toastr.min.css" rel="stylesheet">
<!--        <script src="<?= $app['base_assets_url']; ?>js/ie-emulation-modes-warning.js"></script>-->
        
        <script type="text/javascript">
<?php if (isset($data)): ?>
    var Data = <?php echo json_encode($data); ?>;
<?php endif; ?>
            WebFontConfig = {
                google: {families: ['Ubuntu::latin']}
            };
            (function () {
                var wf = document.createElement('script');
                wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
                wf.type = 'text/javascript';
                wf.async = 'true';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(wf, s);
            })();</script>
        <script src="<?= $app['base_assets_url']; ?>js/moment.min.js" type="text/javascript"></script>
        <script src="<?= $app['base_assets_url']; ?>js/routes.js?<?= time(); ?>" type="text/javascript"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>          
        <script src="<?= $app['base_assets_url']; ?>toastr-master/build/toastr.min.js" type="text/javascript"></script>
        <script src="<?= $app['base_assets_url']; ?>resources/currency_symbols.js" type="text/javascript"></script>  
        <script src="<?= $app['base_assets_url']; ?>Parsley.js-2.4.4/dist/parsley.min.js?<?= time(); ?>" type="text/javascript"></script>
        <script src="<?= $app['base_assets_url']; ?>js/helpers.js?<?= time(); ?>" type="text/javascript"></script>
        <script src="<?= $app['base_assets_url']; ?>js/bootstrap-datepicker.min.js?<?= time(); ?>" type="text/javascript"></script>
        <script>
            $(document).ready(function () {
                toastr.options = {
                    "closeButton": true,
                    "positionClass": "toast-top-center"
                } 
            });
            $(document).ready(function () {

                $('.type-selecter').click(function (e) {
                    e.preventDefault();
                    $('.holder .type').text($(this).text());
                });
                $('.search').click(function (e) {
                    e.preventDefault();
                    var type = $('.holder .type').text();
                    var query = $('.query').val();
                    var queryString;
                    if (type == 'Users') {
                        queryString = 'users?search=' + query + '&type=' + type;
                    }

                    window.location.href = '<?= $app['base_url']; ?>admin/' + queryString;
                });
                $(document).on('keydown', '.query', function (ev) {
                    if (ev.which === 13) {
                        $('.search').trigger('click');
                    }
                });
            });            
        </script>    
    </head>
    <body role="document" id="<?= !empty($meta['page-name'])? $meta['page-name'] : ''; ?>">

        <div class="wrapper">

            <div class="col-md-12 col-sm-12 col-xs-12 border_bot">
                <div class="col-md-4 col-sm-4 col-sx-2">
                    <a href="<?= $app['base_url']; ?>admin/dashboard"><img src="<?= $app['base_assets_url']; ?>images/shdow_instpas.png" class="ins_logo" alt=""></a>   
                </div>
                <div class="col-md-4 col-sm-5 col-xs-5">
                    <div class="inpdes">
                        <input type="text" class="form-control inp_set query" placeholder="Search..." value="<?= (!empty($_GET['search'])) ? $_GET['search'] : ''; ?>">
                        <a href="#" class="search"> <span class="glyphicon glyphicon-search cold_icon"></span></a>

                        <div class="dropdown user">
                            <button class="btn btn-primary dropdown-toggle holder" type="button" data-toggle="dropdown"><span class="type"><?= (!empty($_GET['type'])) ? $_GET['type'] : 'Users'; ?></span>
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href="#" class="type-selecter">Users</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-" style="padding:0">
                    <a class="clos_icon" href="<?= $app['base_url']; ?>admin/logout/"><img src="<?= $app['base_assets_url']; ?>images/clos.png" class="" alt=""></a>
                </div>
            </div>

            <div class="col-md-12 col-sm-12 body_postion" style="padding:0">