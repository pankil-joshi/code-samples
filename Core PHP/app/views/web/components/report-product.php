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

</style>


<!-- Modal -->
<div class="modal fade main_thride" id="report-product" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Report this product to Tagzie</h4>
            </div>
            <div class="modal-body">
                <form id="report-product-form" data-parsley-validate>
                    <select name="type" class="form-control">
                    <option value="offensive">This post is offensive.</option>
                    <option value="pornographic">This post is pornographic.</option>
                    <option value="non_relevant">This type of product shouldn't be on Tagzie.</option>
                    <option value="fraudulent_seller">I suspect this is a fraudulent seller.</option>
                </select>
                <input type="hidden" name="media_id" value="<?= $media['id']; ?>">
                <div>
                    <button type="submit" class="btn btn-default col-xs-12 orange-button report-product-button" >Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--modal end-->
