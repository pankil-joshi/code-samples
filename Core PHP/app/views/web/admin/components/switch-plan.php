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
<div class="modal fade main_thride" id="switch-plan-modal" role="dialog" data-user-id="<?= $user['id']; ?>">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Change Subscription</h4>
            </div>
            <div class="modal-body">
                <form id="switch-plan-form" data-parsley-validate>
                <select name="planid" class="form-control">
                    <?php foreach($subscription_plans as $package):?>
                    <option value="<?= $package['id']; ?>" <?= ($package['id'] == $user['merchant_subscription_package_id'])? 'selected' : '';?>><?= $package['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <div>
                    <button type="submit" class="submit_form switch-plan-button" style="width:100%;" >Switch</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--modal end-->
