<style>
.main_thride .modal-content {
  float: left;
  width: 100%;
}
.main_thride {
  float: left;
  width: 100%;
}
.thred_popup select {
  border: 1px solid rgb(208, 208, 208);
  border-radius: 0;
  float: left;
  height: 39px;
  margin-bottom: 11px;
  width: 100%;
}
.thride_textarea textarea {
  border: 1px solid rgb(208, 208, 208);
  border-radius: 0;
  float: left;
  margin-bottom: 12px;
  width: 100%;
}
    .send-new-message {
  background-color: rgb(255, 108, 0);
  border: medium none;
  color: rgb(255, 255, 255);
  font-size: 16px;
  padding: 10px 27px;
        float: right;
}
.send-new-message {
  background-color: rgb(255, 108, 0) !important;
  background-image: none;
  border: medium none;
  border-radius: 0;
  color: rgb(255, 255, 255);
  float: right;
  font-size: 16px;
  margin: 0 0 10px;
  padding: 10px 27px;
  width: auto;
}
.modal-title {
    
    text-transform: capitalize;
}
</style>


<!-- Modal -->
<div class="modal fade main_thride" id="new-thread" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Message</h4>
            </div>
            <div class="modal-body">

                <div class="thred_popup">

                    <select class="form-control type">
                        <option value="">Select Type...</option>
                        <option value="General">General</option>
                        <option value="Shipping">Shipping</option>
                        <option value="Technical">Technical</option>
                    </select>
                   <!-- <span><i class="fa fa-sort-desc fa-fw"></i></span>-->

                </div>
                <div class="thride_textarea">
                    <!--<label for="exampleInput1">State:</label>-->
                    <textarea class="form-control message-text" rows="5" placeholer="Message"></textarea>
                </div>
                <div>
                    <button type="button" class="btn btn-default col-xs-12 send-new-message">Send</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--modal end-->
