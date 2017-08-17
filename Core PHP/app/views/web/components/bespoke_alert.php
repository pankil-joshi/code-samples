<div class="modal fade mark_margin make_payment" id="bespoke_modal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content modal_inner" style="display: inline-block;">
            <form id="bespoke_agreement" data-parsley-validate>
                <div class="modal-header">
                    <button type="button" class="close modal_close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-size">Bespoke Item</h4>
                </div>
                <div class="modal-body">
                    <div class="col-xs-12">
                        <h4 class="modal-size">This is a bespoke / made-to-order item. Once your order has been placed, you will be unable to cancel or receive a refund unless there is an issue with the item.</h4>
                        <div class="check_bx">
                            <label class="control control--checkbox">
                                <input type="checkbox" id="agree_term" data-parsley-required-message="Please confirm agree with these terms" required="" name="agree_term" >
                                <div class="control__indicator"></div>
                            </label>
                            <span>I agree with these terms</span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="proceed_btn text-center">
                                <button class="mar_rigt disabe_btn make_payment_proceed" type="submit" value="true">Make Payment</button>
                                <button type="button" class="" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#agree_term').on('click', function(){
           if ($(this).is(':checked')) {
               $('.make_payment_proceed').removeClass('disabe_btn');
           } else {
               $('.make_payment_proceed').addClass('disabe_btn');
           } 
        });
    });
</script>