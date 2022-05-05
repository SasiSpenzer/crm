<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 class="modal-title" id="myModalLabel">Update Payment</h5>
      </div>
      <div class="modal-body">
        <form role="form">
            <input type="hidden" id="memship_uid" value="">

            <div class="form-group">
                <label>Payment expiry date</label>
                <!-- <input class="form-control" id ="payment_expiry_date" placeholder="YYYY-MM-DD"> -->
                <input class="form-control" id ="payment_expiry_date" placeholder="YYYY-MM-DD">
               <!--  <div class="input-append date form_datetime" data-date="2012-12-21">
                    <input size="16" type="text" value="" readonly id ="memship_call_date_time" class="form-control">
                    <span class="add-on"><i class="icon-remove"></i></span>
                    <span class="add-on"><i class="icon-th"></i></span>
                </div> -->
            </div>

            <div class="form-group">
                <label>Amount</label>
                <input class="form-control" id ="amount" placeholder="0.00">
            </div>

            <div class="form-group">
                <label>Membership Expire Date: <span class="text-info" id="memship_expiry"></span> </label>
            </div>

             <!-- <div class="form-group">
                <label>Remarks</label>
                <textarea class="form-control" rows="3" id="memship_remarks"></textarea>
            </div> -->

            <button type="submit" class="btn btn-default btn-primary" id="memship_submit" style="float: right;">Save </button>


            <div style="clear: both;"></div>
        </form>
      </div>

      <div id="details" style="display:none;margin-left: 20px;margin-bottom: 20px;"></div>
    </div>
  </div>
</div>

