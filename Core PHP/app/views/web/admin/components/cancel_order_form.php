<!-- mark as dispatch Modal start -->
<div class="modal fade mark_margin cancel_oder_popup" id="cancel-order-form" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content modal_inner">
            <form id="cancel-order" data-parsley-validate>
                <button type="button" class="close modal_close" data-dismiss="modal">&times;</button>
                <div class="modal-header modal_main">
                    <h4 class="modal-size">Please provide the reason for order cancellation.</h4>
                </div>
                <div class="modal-header all-data">
                    <div class="col-xs-12 pro_form">
                        <textarea class="form-control" placeholder="e.g. Not in stock." required name="reason" data-parsley-required-message="Please enter the reason for Cancellation."></textarea>
                    </div>
                    <div class="col-xs-12 pro_form">
                        <input class="form-control" placeholder="Refund amount." name="amount" type="number">
                    </div>                    
                    <div class="col-xs-12 back_btn">
                        <button class=" col-xs-12 btn btn-default btn_proced dispatchButton" type="submit">Cancel order</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>