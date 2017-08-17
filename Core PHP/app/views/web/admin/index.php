<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="../../favicon.ico">

        <title> <?= !empty($title) ? $title : ''; ?></title>
        <link href="<?= $app['base_assets_url']; ?>css/bootstrap.min.css" rel="stylesheet">
        <link href="<?= $app['base_assets_url']; ?>css/bootstrap-theme.min.css" rel="stylesheet">
        <link href="<?= $app['base_assets_url']; ?>css/admin_style.css?<?= strtotime(date('Y-m-d')); ?>" rel="stylesheet">
        <link href="<?= $app['base_assets_url']; ?>css/ie10-viewport-bug-workaround.css" rel="stylesheet">
        <link href="<?= $app['base_assets_url']; ?>theme.css" rel="stylesheet">
        <script src="<?= $app['base_assets_url']; ?>js/ie-emulation-modes-warning.js"></script>
        <script type="text/javascript">
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

        <script src="<?= $app['base_assets_url']; ?>js/jquery.min.js"></script>
        <script src="<?= $app['base_assets_url']; ?>js/jquery.validate.min.js"></script>
        <script src="<?= $app['base_assets_url']; ?>js/bootstrap.min.js"></script>
        <script src="<?= $app['base_assets_url']; ?>js/docs.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="<?= $app['base_assets_url']; ?>js/ie10-viewport-bug-workaround.js"></script>
    </head>
    <body role="document">

        <div class="login_main">
            <div class="vertical_align_table ">
                <div class="vertical_align_table_inner">
                    <form action="" id="admin_login_form" method="POST">
                        <div class="login_inner">
                            <h1><span class="glyphicon glyphicon-user"></span>Superadmin Login</h1>
                            <div id="error_msg"><?php if (isset($_SESSION['error_msg'])) {
                                echo $_SESSION['error_msg'];
                                unset($_SESSION['error_msg']);
                            } ?></div>
                            <div class="inner_inp">
                                <span class="glyphicon glyphicon-user"></span>    
                                <input type="text" class="input_login" alt="" name="username" placeholder="Username">
                            </div>  
                            <div class="inner_inp">
                                <span class="glyphicon glyphicon-lock"></span>      
                                <input type="password" class="input_login" alt="" name="password" placeholder="Password">
                            </div>
                            <input type="hidden" name="method" value="admin_login">
                            <button type="submit" class="login_btn">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> 
    </body>
</html>

<script>
            /*
             * Validate login form
             */
            $('#admin_login_form').validate({
                rules: {
                    username: {required: true},
                    password: {required: true}
                },
                messages: {
                    username: {required: "Username is required."},
                    password: {required: "Password is required."}
                }
            });

            /*
             * Hide error message timeout
             */
            $(document).ready(function () {
                setInterval(function () {
                    $('#error_msg').hide(); // show next div
                }, 10000); // do this every 10 seconds 
            });
</script>
