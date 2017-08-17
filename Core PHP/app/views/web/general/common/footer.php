</div>
<div class="secure_pin">
    <h6><span><img alt="" class="img-resaponsive" src="<?= $app['base_assets_url']; ?>images/super_lock.png">Social commerce powered by Tagzie &nbsp;&nbsp;&nbsp;&nbsp;|</span>Buy and sell over your favorite social networks</h6>
</div>
<div class="header_bar">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-2">
                    <a href="#"><img src="<?= $app['base_assets_url']; ?>images/shdow_instpas.png" class="img-responsive hederlog" alt=""></a>
                </div>
                <div class="col-md-10 navigation navigato1">
                    <ul class="nav nav-pills">
                        <?php //if (!empty($meta['page']['name']) && $meta['page']['name'] == 'home'): ?>
                        <li><a href="<?= $app['base_url']; ?>about">About Tagzie</a></li>
                        <li><a href="<?= $app['base_url']; ?>buying">Buying</a></li>
                        <li><a href="<?= $app['base_url']; ?>selling">Selling</a></li>
                        <li><a href="<?= $app['base_url']; ?>faq">FAQs</a></li>
                        <li><a href="<?= $app['base_url']; ?>how-it-works">How It Works</a></li>
                        <li><a href="<?= $app['base_url']; ?>contact">Contact Tagzie</a></li>
                        <?php //else: ?>
                        <li><a href="<?= $app['base_url']; ?>legal/terms">Terms and Conditions</a></li>
                        <li><a href="<?= $app['base_url']; ?>legal/refund">Refund Policy</a></li>
                        <li><a href="<?= $app['base_url']; ?>legal/privacy">Privacy Policy </a></li>
                        <li><a href="<?= $app['base_url']; ?>legal/cookie">Cookie</a></li>
                        <li><a href="<?= $app['base_url']; ?>account/customer">My Account</a></li>
                        <?php //endif; ?>
                    </ul>
                </div>
                <div class="col-md-2">

                </div>
                <div class="col-md-12">
                    <p>Patent pending.</p>

                    <p>Disclaimer: Tagzie is a social commerce platform that allows sellers and businesses to sell over Instagram and other social networks. Tagzie is not affiliated with Instagram or any other social network in any way and is a separate service altogether. Whilst Tagzie closely moderates items listed on its marketplace, Tagzie accepts no responsibility for the products listed or outcomes of your purchase.</p>

                    <p>&copy; Tagzie Limited 2016. Company registered in England and Wales (Company No: 09956906). Registered address: 32 Thistlebank, East Leake, Loughborough, LE12 6RS, United Kingdom.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= $app['base_assets_url']; ?>js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?= $app['base_assets_url']; ?>js/bootstrap.min.js"></script>
<script src="<?= $app['base_assets_url']; ?>js/jquery.viewport.mini.js"></script>

<script type="text/javascript">

    // Validate subscription form and subscribe to our list
    $('body').on('click', '#subscribe', function () {
        $('#message_block').html('');
    });

    $("#subscribe_form").validate({
        rules: {
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            first_name: {
                required: "Please enter first name"
            },
            last_name: {
                required: "Please enter last name"
            },
            email: {
                required: "Please enter email",
                email: "Please enter valid email address"
            }
        },
        submitHandler: function (form) {
            $("#subscribe").attr('disabled', true);
            $('#message_block').html('');
            $.ajax({
                method: 'POST',
                data: $('#subscribe_form').serializeArray(),
                url: 'include/subscribe.php',
                cache: false,
                beforeSend: function () {
                    $('.loader').show();
                },
                success: function (data) {
                    $('.loader').hide();
                    if ($.trim(data) == "success") {
                        $('#message_block').html('<span style="color:green">You have subscribed successfully.</span>').show();
                        $('#subscribe_form')[0].reset();
                        setTimeout(function () {
                            $('#message_block').html('').hide();
                        }, 10000);
                    } else {
                        $('#message_block').html('<span style="color:red">' + data + '</span>').show();
                    }
                    $("#subscribe").attr('disabled', false);
                }
            });
            5
        }
    });

</script>
</body>
</html>