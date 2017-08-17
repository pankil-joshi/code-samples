<!-- mark as dispatch Modal start -->
<div class="modal fade mark_margin" id="cancel-requested-form" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content modal_inner">

            <form id="cancel_requested">
                <button type="button" class="close modal_close" data-dismiss="modal">&times;</button>
                <div class="modal-header modal_main">
                    <h4 class="modal-size">Please confirm if you wish to Accept or Decline this customers request for cancellation.</h4>
                </div>
                <div class="decline_section" style="display: none;">
                    <div class="modal-header all-data"  style="padding-top: 0px;">
                        <div class="pro_form">
                            <textarea class="form-control" rows="4" style="margin-top: 5px;" placeholder="Enter a reason for declining this request" required id="decline_reason" name="decline_reason" data-parsley-required-message="Please enter the reason for decline."></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-header all-data" style="padding-top: 0px;">
                    <div class="row back_btn">
                        <input type="hidden" name="action_request" id="action_request" value="accept_cancellation" />
                        <div class="col-xs-6">
                            <button class=" btn btn-default btn_proced cancel_order" type="submit" style="width:100%;">Accept</button>
                        </div>
                        <div class="col-xs-6">
                            <button class=" btn btn-default btn_proced decline" type="submit" style="width:100%;">Decline</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
