<div class="modal fade" id="add-card-modal" role="dialog">
    <div class="modal-dialog model_width model_responsive">
        <!-- Modal content-->
        <div class="modal-content message-modal-body">
            <div class="modal-header">
                <button type="button" class="close">&times;</button>
                <h4 class="modal-title">Add new card</h4> </div>
            <div class="col-md-12" id="card-box">
                <form data-parsley-validate id="add-card-form">
                    <div class="showing_form">
                        <div class="col-md-4">
                            <label>Name on card</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input type="text" placeholder="e.g. John Murphy" class="form-control" id="card-name" required data-parsley-required-message="Please enter name on card"> </div>
                        </div>
                    </div>
                    <div class="showing_form">
                        <div class="col-md-4">
                            <label>Card number</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input type="text" placeholder="e.g. XXXX-XXXX-XXXX-XXXX" class="form-control" id="card-number" required data-parsley-required-message="Please enter card number"> </div>
                        </div>
                    </div>
                    <div class="showing_form">
                        <div class="col-md-4">
                            <label>Expiry Month</label>
                        </div>
                        <div class="col-md-8 ">
                            <div class="row">
                                <!--<input type="text" placeholder="exp mm" class="input_tc_pro" id="card-expiry-month">-->

                                <select id="card-expiry-month" class="form-control" required data-parsley-required-message="Please select expiry month">
                                    <option value="">Choose...</option>
                                    <?php foreach (range(01, 12) as $value): ?>
                                        <option value="<?= $value; ?>"><?= $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="showing_form">
                            <div class="col-md-4">
                                <label>Expiry Year</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <!--<input type="text" placeholder="exp yy" class="input_tc_pro" id="card-expiry-year" required>-->
                                    <select id="card-expiry-year" class="form-control" required data-parsley-required-message="Please select expiry year">
                                        <option value="">Choose...</option>
                                        <?php foreach (range(date('Y'), date('Y', strtotime('+ 15 years'))) as $value): ?>
                                            <option value="<?= $value; ?>"><?= $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="showing_form">
                        <div class="col-md-4">
                            <label>CVV/CVC</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <input id="cvc" type="password" placeholder="cvc" class="form-control" required data-parsley-required-message="Please enter cvc code"> </div>
                        </div>
                    </div>
                    <div class="showing_form">
                        <div class="col-md-12">
                            <div class="row">
                                <button type="submit" class="next_ch addCard submit_form" href="#" id="addCard" style="float:right;margin-top:0;text-decoration:none">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer"> </div>
        </div>
    </div>
</div>