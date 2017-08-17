<?php
include_once $this->getPart('/web/admin/common/header.php');
include_once $this->getPart('/web/admin/common/sidebar.php');
?>
<div class="col-md-11 col-sm-10 col-xs-10 right_brd">
    <div class="desbord_body check_outstp">
        <div class="row">
            <div class="col-md-12 lastest_marg">
                <div class="col-md-12">
                    <div class="yor_active" style="border:0">
                        <div class="order-active">
                            <div class="row">
                                <div class="col-md-3">
                                    <h6>Users</h6>
                                </div>
                                <div class="col-md-3">
                                    <div class="lable-date">

                                        <label>Sort:</label>
                                        <div class="select-style date-order">
                                            <select id="sort">
                                                <option value="desc">Latest First</option>
                                                <option value="asc">Oldest First</option>
                                            </select>
                                        </div>   

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <ul class="pull-right" id="filter-nav">
    <!--                                    <li data-filter="none" class="filter"><a href="#"><span class="active"></span></a>All</li>    
                                        <li data-filter="completed" class="filter"><a href="#"><span class="deactive"></span></a>Completed</li>
                                        <li data-filter="in_progress" class="filter"><a href="#"><span class="deactive"></span></a>Processing</li>
                                        <li data-filter="tagged" class="filter"><a href="#"><span class="deactive"></span></a>Tagged</li>-->
                                        <li>

                                            <a href="#" id="datepicker" data-date="<?= gmdate('d/m/Y'); ?>"  style="display: inline-block;"><img src="<?= $app['base_assets_url']; ?>images/calder.png" class="img-responsive" alt=""></a>
                                            <input type="hidden" id="date-range-start"><input type="hidden" id="date-range-end">
                                        </li>
                                    </ul>
                                    <a class="showmore_button" style="float:right !important; margin:15px 0px !important;" href="<?= $app['base_url']; ?>admin/users/exportToExcel" >Export to Excel</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="row">
                                <table class="table user_lists user_table_show">
                                    <thead class="instagram_user">
                                        <tr>
                                            <th>ID</th>
                                            <th>Instagram Username</th>
                                            <th>User Type</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Mobile Number</th>
                                            <th>Country/Currency Code</th>
                                            <th>Deactivated</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="users" class="td_instagram_table">
                                        <?php include_once $this->getPart('/web/admin/components/users_list_view.php'); ?>
                                    </tbody>
                                </table>  

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    var replaceOrderList = function (html, callback) {

        if ($('#users .user-row').length > 0) {

            $('#users').html(html).hide().fadeIn('slow');
        } else {

            $('#users').html(html).hide().fadeIn('slow');
        }

        if (typeof callback == 'function') {
            callback();
        }

    }
    var activeSendButton;
    var user_type = "<?= isset($user_type) ? $user_type : ''; ?>";
    $(document).ready(function () {

        $('body').on('click', '.pagination-container > li > a', function (e) {
            e.preventDefault();

            var data = {page: $(this).data('page'), order: $('#sort').val(), user_type: user_type};
            if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

                data.filter = 'custom';
                data.start_date = $('#date-range-start').val();
                data.end_date = $('#date-range-end').val();
            }
            $.when(getUserList(data)).then(function (html) {

                replaceOrderList(html);

            });

        });

        $('#datepicker').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY'
            },
            startDate: '<?= date('d/m/Y'); ?>'
        }, function (start, end, label) {
            $('#date-range-start').val(start.format('YYYY-MM-DD'));
            $('#date-range-end').val(end.format('YYYY-MM-DD'));

            var data = {page: $(this).data('page'), order: $('#sort').val(), user_type: user_type};
            if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

                data.filter = 'custom';
                data.start_date = $('#date-range-start').val();
                data.end_date = $('#date-range-end').val();
            }
            $.when(getUserList(data)).then(function (html) {
                //console.log(html);
                replaceOrderList(html);

            });
        });
//        $('#filter-nav').on('click', 'li.filter', function (e) {
//            e.preventDefault();
//
//            if ($(this).find('span').hasClass('deactive')) {
//
//                $('#filter-nav a > span').removeClass('active').addClass('deactive');
//                $(this).find('span').addClass('active');
//            }
//
//            var data = {order: $('#sort').val()};
//            if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {
//
//                data.filter = 'custom';
//                data.start_date = $('#date-range-start').val();
//                data.end_date = $('#date-range-end').val();
//            }
//            $.when(getUserList(data)).then(function (html) {
//
//                replaceOrderList(html, initializeBarRating);
//
//            });
//        });
        $('#sort').change(function () {
            var data = {order: $(this).val(), user_type: user_type};
            if ($('#date-range-start').val() != '' && $('#date-range-end').val() != '') {

                data.filter = 'custom';
                data.start_date = $('#date-range-start').val();
                data.end_date = $('#date-range-end').val();
            }
            $.when(getUserList(data)).then(function (html) {
                //console.log(html);
                replaceOrderList(html);

            });
        });

        $('body').on('click', '.delete_user', function () {
            var me = $(this);
            var data = {id: $(this).data('id')};
            var user = $(this).data('user');
            if (user == 'merchant')
                var confirm_this = confirm('On deleting, user suscription will be cancelled. Are you sure you want to delete this user.');
            else
                var confirm_this = confirm('Are you sure you want to delete this user.');

            if (confirm_this == true) {
                $.when(deleteUserById(data)).then(function (response) {
                    if (response.meta.success == true) {
                        me.parents('tr').remove();
                        toastr.success('User deleted successfully.');
                    } else {
                        //toastr.error('Some error occured in deleting user, Please try again later.');
                    }
                    //replaceOrderList(html, initializeBarRating);
                });
            }
        });

        $('body').on('click', '.update_status', function () {
            var me = $(this);
            var data = {id: $(this).data('id'), status: $(this).data('action')};
            $.when(updateUserById(data)).then(function (response) {
                //console.log(response);
                if (response.meta.success == true) {
                    if (response.data.status == '1') {
                        me.parents('td').prev('td').html('Yes');
                        me.data('action', '0');
                        me.html('Activate');
                    } else {
                        me.parents('td').prev('td').html('No');
                        me.data('action', '1');
                        me.html('Deactivate');
                    }
                    toastr.success('Status updated successfully.');
                } else {
                    //toastr.error('Some error occured in updating status, Please try again later.');
                }
                //replaceOrderList(html, initializeBarRating);
            });
        });
    });
</script>
<?php include_once $this->getPart('/web/admin/common/footer.php'); ?>
