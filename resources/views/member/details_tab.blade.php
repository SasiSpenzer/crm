<div class="tab-pane fade hide" id="details">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well well-sm">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12" id="user_manage">
                        <form role="form" enctype="multipart/form-data" id="membershipDetailsForm" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="membership_type">User Type</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" id="old_user_type" name="old_user_type" type="hidden"/>
                                    <select class="form-control" id="user_type" name="user_type">
                                        <option value="">--</option>
                                        @if(count(Config::get('membership.user_type'))>0)
                                            @foreach(Config::get('membership.user_type') as $index => $type)
                                                <option value="{{$index}}">{{$type}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="membership_type">Membership Status</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" disabled readonly name="membership_details_uid" type="hidden" id="membership_details_uid"/>
                                    <input class="form-control" disabled readonly name="membership_pending_approval_count" type="hidden" id="membership_pending_approval_count" value="0"/>
                                    <input class="form-control" id="old_membership_type" name="old_membership_type" type="hidden"/>
                                    <select class="form-control" id="membership_type" name="membership_type">
                                        <option value="">--</option>
                                        @if(count(Config::get('membership.type'))>0)
                                            @foreach(Config::get('membership.type') as $type)
                                                <option value="{{$type}}">{{$type}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="membership_category">Membership Category</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" id="old_membership_category" name="old_membership_category" type="hidden"/>
                                    <select class="form-control" id="membership_category" name="membership_category">
                                        <option value="">--</option>
                                        <?php
                                        $categoies = \App\Package::all()->pluck('package_name')->toArray();
                                        ?>
                                        @if(count($categoies)>0)
                                            @foreach($categoies as $category)
                                                <option value="{{$category}}">{{$category}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="membership_payment">Membership Payment</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" id="old_membership_payment" name="old_membership_payment" type="hidden"/>
                                    <select class="form-control" id="membership_payment" name="membership_payment">
                                        <option value="">--</option>
                                        @if(count(Config::get('membership.payment'))>0)
                                            @foreach(Config::get('membership.payment') as $payment_id=>$payment)
                                                <option value="{{$payment_id}}">{{$payment}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="membership_duration">Membership/Trial Duration</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" id="old_membership_duration" name="old_membership_duration" type="hidden"/>
                                    <select class="form-control" id="membership_duration" name="membership_duration">
                                        <option value="">--</option>
                                        @if(count(Config::get('membership.duration'))>0)
                                            @foreach(Config::get('membership.duration') as $duration)
                                                <option value="{{$duration}}">{{$duration}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="package_amount">Package Amount</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" id="old_package_amount" name="old_package_amount" type="hidden"/>
                                    <input class="form-control" id="package_amount" name="package_amount" type="number" value="0.00" placeholder="10000.00" min="0.00" step="0.01" required/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="package_amount">Pending Amount</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" id="old_pending_amount" name="old_pending_amount" type="hidden"/>
                                    <input class="form-control" id="pending_amount" name="pending_amount" type="number" value="0.00" placeholder="10000.00" min="0.00" step="0.01" required/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="membership_call_date">Payment Expiry Date</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" id="admin_level" name="admin_level" type="hidden"/>
                                    <input class="form-control" id="old_membership_call_date" name="old_membership_call_date" type="hidden"/>
                                    <div id="membership_call_date_div">
                                        <input class="form-control" id="membership_call_date" name="membership_call_date" type="text"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="membership_expiry_date">Membership Expiry Date</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" id="old_membership_expiry_date" name="old_membership_expiry_date" type="hidden"/>
                                    <div id="membership_expiry_date_div">
                                        <input class="form-control" id="membership_expiry_date" name="membership_expiry_date" type="text"/>
                                    </div>
                                </div>
                            </div>
                            <!--div class="form-group row">
                                <div class="col-md-4">
                                    <label for="membership_active_add_count">Number of Active ads</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" id="old_membership_active_add_count" name="old_membership_active_add_count" type="hidden"/>
                                    <input class="form-control" readonly id="membership_active_add_count" name="membership_active_add_count" type="number" placeholder="0"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="membership_leads_count">Number Of Leads</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" id="old_membership_leads_count" name="old_membership_leads_count" type="hidden"/>
                                    <input class="form-control" readonly id="membership_leads_count" name="membership_leads_count" type="number" placeholder="0"/>
                                </div>
                            </div-->
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="membership_am">AM</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" id="old_membership_am" name="old_membership_am" type="hidden"/>
                                    <select class="form-control" id="membership_am" name="membership_am">
                                        <option value="">--</option>
                                        <?php $ams = \App\User::select('username')->whereIn('department_id', [1])
                                            ->get()->pluck('username')->toArray();
                                        ?>
                                        @if(count($ams))>0)
                                            @foreach($ams as $am)
                                                <option value="{{$am}}">{{ ucfirst(trans($am))}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="phone_restrictions">Phone Restrictions</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" id="old_phone_restrictions" name="old_phone_restrictions" type="hidden"/>
                                    <input type = "radio" name = "phone_restrictions" id = "phone_restrictions_yes" value = "1"/>
                                    <label for = "phone_restrictions" style="margin-right: 15px;">Yes</label>
                                    <input type = "radio" name = "phone_restrictions" id = "phone_restrictions_no" value = "0" />
                                    <label for = "phone_restrictions">No</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="membership_comment">Comments to Approver</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea class="form-control" rows="3" placeholder="Put Comment" id="membership_comment" name="membership_comment"></textarea>
                                </div>
                            </div>
                            <!-- Split button -->
                            <div class="form-group row modal-footer">
                                <div class="col-md-3"></div>
                                <div class="col-md-9 text-center">
                                    <button type="button" class="btn btn-warning btn-lg" style="border-radius:10px;" id="member_generate_url" data-uid="">Generate Url</button>
                                    <button type="submit" class="btn btn-primary btn-lg" style="border-radius:10px;" id="member_details_save" data-uid="">Submit</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>