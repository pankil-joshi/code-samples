<!-- mark as dispatch Modal start -->
<div class="modal fade mark_margin markDispatch makeing_dispach" id="dispatch-form" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content modal_inner">
            <form id="save-order" data-parsley-validate>
                <button type="button" class="close modal_close" data-dismiss="modal">&times;</button>
                <div class="modal-header modal_main">
                    <h4 class="modal-size">Dispatch detail </h4>
                </div>
                <div class="modal-header all-data">
                    <div class="col-xs-12 pro_form">
                        <label for="exampleInput1">Courier Name : </label>
                        <input type="text" class="form-control validateTextOnly" placeholder="e.g. Blue Dart" required name="carrier_name" data-parsley-required-message="Please enter courier name">
                    </div>
                    <div class="col-xs-12 pro_form">
                        <label for="exampleInput1">Courier Tracking Reference (optional) : </label>
                        <input type="text" class="form-control" placeholder="e.g. 684593217" name="carrier_number" >
                    </div>
                    <div class="col-xs-12 pro_form" style="display: none">
                        <label for="exampleInput1">Courier Tracking URL (optional) : </label>
                        <input type="url" class="form-control" placeholder="e.g. www.trackyourproduct.com" name="carrier_tracking_url" data-parsley-type-message="Must be a vaid URL">
                    </div>

                    <div class="col-xs-12 back_btn">
                        <button class=" col-xs-12 btn btn-default btn_proced dispatchButton" type="submit">DISPATCH</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>