<div class="modal fade pstg_frm" tabindex="-1" role="dialog" id="postage-form">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="save-postage" data-parsley-validate data-parsley-excluded="input[type=checkbox]">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Postage Option</h4>
                </div>
                <div class="mrgn_bot">
                    <div class="col-md-12">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="showing_form">
                                <div class="col-md-3">
                                    <label>Postage Name:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <input type="text" class="inpttest" value="" name="label" required data-parsley-required-message="Please enter postage name">
                                    </div>
                                </div>
                            </div>
                            <div class="showing_form">
                                <div class="col-md-3">
                                    <label>Cost:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <input type="text" class="inpttest" value="" name="rate" required data-parsley-required-message="Please enter rate">
                                    </div>
                                </div>
                            </div>                            
                            <div class="showing_form">
                                <div class="col-md-3">
                                    <label>Estimated delivery days:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <input type="number" class="inpttest" value=""  name="duration" required data-parsley-required-message="Please enter estimated delivery days">
                                    </div>
                                </div>                               
                            </div>
                            <div class="showing_form">
                                <div class="col-md-3">
                                    <label>Destination:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
<div id="countryList"></div>

                                    </div>
                                </div>
                            </div>                          
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="submit_form sumit_bn" name="save-postage" style="float:right; border:none">Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->