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
                                <h6>Postage Templates</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12">
                    <div class="adneadd postage_adding">
                        <p>Manage postage templates to help you publish product posts quickly.</p>
                        <button type="button" class="btn btn-size add-postage" data-toggle="modal" data-target="#add_address"><span class="glyphicon glyphicon-plus-sign"></span><div class="button_plus_txt">Add new postage option</div></button>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12"><div class="row"><hr></div></div>
                <div id="postage-options">
                    <?php include_once $this->getPart('/web/merchant/components/postage-option-list.php'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once $this->getPart('/web/merchant/components/postage_form.php'); ?>
<script>
    var continent_group = <?= json_encode($continents); ?>;

    $(document).ready(function () {

        $('#postage-form').find('select[name="geography"]').multiselect({});
        $('#postage-options').on('click', '.edit-postage', function () {

            var postageOptionIndex = $(this).data('index');

            $('#postage-form').find('input[name="label"]').val(PostageOptions[postageOptionIndex].label);
            $('#postage-form').find('input[name="rate"]').val(PostageOptions[postageOptionIndex].rate);
            $('#postage-form').find('input[name="duration"]').val(PostageOptions[postageOptionIndex].duration);
            var geography = PostageOptions[postageOptionIndex].geography.split(',');
            var geographyHtml = '';
            $(geography).each(function (index, item) {
                if (item = '*') {
                    item = 'Worldwide';
                }
                geographyHtml += '<option value="' + item + '" >' + item + '<option>';
            });
            //            $('#postage-form').find('select[name="geography"]').html(geographyHtml);

            multiSelectTree(continent_group, '#countryList', 'geography', geography);
            $('#postage-form').data('id', PostageOptions[postageOptionIndex].id);
            $('#postage-form').modal('show');
        });
        $('.add-postage').click(function () {

            $('#postage-form').find('input[name="label"]').val('');
            $('#postage-form').find('input[name="rate"]').val('');
            $('#postage-form').find('input[name="duration"]').val('');
            multiSelectTree(continent_group, '#countryList', 'geography');
            $('#postage-form').data('id', '');
            $('#postage-form').modal('show');
        });
        $("#save-postage").on('submit', function (e) {
            e.preventDefault();
            var form = $(this);
            form.parsley().validate();
            if (form.parsley().isValid() && $('input[name="geography"]').val() != '') {
                var data = {};
                var formData = $('#save-postage').serializeObject();

                data.body = formData;
                data.postage_option_id = $('#postage-form').data('id');
                $.when(savePostage(data)).then(function (data) {

                    if (data.meta.success) {

                        hideLoader('#postage-form .modal-content');

                        var postage = data.data.postage;
                        getPostageOptionList();
                        $("#postage-form").modal('hide');
                        //                        var addressHtml = '<li>' + _e(address.first_name) + ' ' + _e(address.last_name) + '</li>'
                        //                                + '<li>' + _e(address.line_1) + '</li>'
                        //                                + '<li>' + _e(address.line_2) + '</li>'
                        //                                + '<li>' + _e(address.state) + '</li>'
                        //                                + '<li>' + _e(address.city) + '</li>'
                        //                                + '<li>' + _e(address.zip_code) + '</li>'
                        //                                + '<li>' + _e(Countries[address.country].name) + '</li>';
                        //                        $('.address-box-' + address.id).find('ul').html(addressHtml);
                        toastr.success('Postage option saved successfully.');
                    }

                });
            } else if ($('input[name="geography"]').val() == '') {

                toastr.error('Please select geography');
            }
        });
        $('#postage-options').on('click', '.delete-postage', function () {

            var element = $(this);
            var data = {postage_option_id: $(this).data('id')};
            var confirmDelete = confirm('Are you sure, you want to delete this postage option?');
            if (confirmDelete) {

                $.when(deletePostage(data)).then(function (data) {
                    hideLoader('body');
                    if (data.meta.success) {

                        element.closest('.postage-option-box').remove();
                    } else {

                        toastr.error(data.data.errors.message);
                    }

                });
            }

        });

        var replacePostageOptionList = function (html, callback) {
            if ($('#postage-options .postage-option-row').length > 0) {

                $('#postage-options').html(html).hide().fadeIn('slow');
            } else {

                $('#postage-options').html(html).hide().fadeIn('slow');
            }

            if (typeof callback == 'function') {
                callback();
            }

        }
        
             function getPostageOptionList() {

                $.when(getMerchantPostageOptionListView()).then(function (html) {

                    replacePostageOptionList(html);

                });
            }      
    });
</script>
<?php include_once $this->getPart('/web/merchant/common/footer.php'); ?>