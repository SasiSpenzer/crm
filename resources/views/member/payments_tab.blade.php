<div class="tab-pane fade hide" id="payments">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well well-sm">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12" id="user_manage">
                        <form role="form" enctype="multipart/form-data" id="membershipDetailsForm" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <div class="col-md-5">
                                    <label for="pay_membership_type">Membership Type</label>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control" disabled readonly name="pay_uid" type="hidden" id="pay_uid"/>
                                    <input class="form-control" id="pay_membership_type" name="pay_membership_type" type="text" readonly/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-5">
                                    <label for="pay_duration">Duration</label>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control" id="pay_duration" name="pay_duration" type="text" readonly/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-5">
                                    <label for="pay_membership_exp_date">Membership Exp. Date</label>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control" id="pay_membership_exp_date" name="pay_membership_exp_date" type="text" readonly/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-5">
                                    <label for="pay_payment_exp_date">Payment Exp. Date</label>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control" id="pay_payment_exp_date" name="pay_payment_exp_date" type="text" readonly/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-5">
                                    <label for="pay_membership_amount">Membership Amount</label>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control" id="pay_membership_amount" name="pay_membership_amount" type="number" readonly/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-5">
                                    <label for="pay_pending_amount">Pending Amount</label>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control" id="pay_pending_amount" name="pay_pending_amount" type="number" readonly/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-5">
                                    <label for="pay_payment_total">Payment Total</label>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control" id="pay_payment_total" name="pay_payment_total" type="number" readonly/>
                                </div>
                            </div>
                            <!--div class="form-group row">
                                <div class="col-md-5">
                                    <label for="pay_membership_amount">Are there any due payments from client?</label>
                                </div>
                                <div class="col-md-7">
                                    <input type = "radio" name = "is_paid" id = "is_paid_yes" value = "1"/>
                                    <label for = "is_paid" style="margin-right: 15px;">Yes</label>
                                    <input type = "radio" name = "is_paid" id = "is_paid_no" value = "0" />
                                    <label for = "is_paid">No</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-5">
                                    <label for="pay_payment_amount">If yes, How much?</label>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control" id="pay_payment_amount" name="pay_payment_amount" type="number" step="0.01"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-5">
                                    <label for="pay_membership_amount">Has the customer made full payment?</label>
                                </div>
                                <div class="col-md-7">
                                    <input type = "radio" name = "is_full_paid" id = "is_paid_yes" value = "1"/>
                                    <label for = "is_full_paid" style="margin-right: 15px;">Yes</label>
                                    <input type = "radio" name = "is_full_paid" id = "is_paid_no" value = "0" />
                                    <label for = "is_full_paid">No</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-5">
                                    <label for="pay_membership_amount">Comment</label>
                                </div>
                                <div class="col-md-7">
                                    <textarea class="form-control" rows="3" placeholder="Put Comment" id="payment_msg" name="payment_msg"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-5">
                                    <label for="pay_btn">Action</label>
                                </div>
                                <div class="col-md-7">
                                    <button type="button" class="btn btn-warning" style="border-radius:10px;" id="pay_btn">Generate Url</button>
                                </div>
                            </div-->
                            <div class="form-group row">
                                <div class="col-md-5">
                                    <label for="pay_url">Payment Url</label>
                                </div>
                                <div class="col-md-6">
                                    <input class="form-control" id="pay_url" name="pay_url" type="text" readonly/>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-warning btn-sm" style="border-radius:10px;" id="pay_copy">
                                        <icon class="fa fa-copy"></icon>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
