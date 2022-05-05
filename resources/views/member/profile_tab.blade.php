<div class="tab-pane fade hide" id="profile">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well well-sm">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12" id="user_manage">
                        <form role="form" enctype="multipart/form-data" id="membershipFormData" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="membership_full_name">Name</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" disabled readonly name="membership_uid" type="hidden" id="membership_uid"/>
                                    <input class="form-control" disabled readonly id="membership_full_name" type="text" name="membership_full_name"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="membership_email">Email</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" disabled readonly id="membership_email" type="email" name="membership_email"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="company_name">Company Name</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" id="old_company_name" name="old_company_name" type="hidden"/>
                                    <input class="form-control" id="company_name" name="company_name" type="text"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="membership_contact_num">Contact Number</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" id="old_membership_contact_num" name="old_membership_contact_num" type="hidden"/>
                                    <input class="form-control" id="membership_contact_num" name="membership_contact_num" type="tel" pattern="[0-9]{10}" placeholder="0111111111"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="linkin_profile">LinkIn Profile</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" id="old_linkin_profile" name="old_linkin_profile" type="hidden"/>
                                    <input class="form-control" id="linkin_profile" name="linkin_profile" type="text"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="user_image">Member Image</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" id="old_user_image" name="old_user_image" type="hidden"/>
                                    <input type="file" class="form-control" name="user_image" id="user_image"/>
                                    <p id="selected_image_data"></p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="phone_restrictions">Auto Boost</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" id="is_active_auto_boost" name="old_is_active_auto_boost" type="hidden"/>
                                    <input type = "radio" name = "is_active_auto_boost" id = "is_active_auto_boost_yes" value = "Y"/>
                                    <label for = "is_active_auto_boost" style="margin-right: 15px;">Yes</label>
                                    <input type = "radio" name = "is_active_auto_boost" id = "is_active_auto_boost_no" value = "N" />
                                    <label for = "is_active_auto_boost">No</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="phone_restrictions">Auto Boost for New Ads</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" id="auto_boost_for_new_ads" name="old_auto_boost_for_new_ads" type="hidden"/>
                                    <input type = "radio" name = "auto_boost_for_new_ads" id = "auto_boost_for_new_ads_yes" value = "Y"/>
                                    <label for = "auto_boost_for_new_ads" style="margin-right: 15px;">Yes</label>
                                    <input type = "radio" name = "auto_boost_for_new_ads" id = "auto_boost_for_new_ads_no" value = "N" />
                                    <label for = "auto_boost_for_new_ads">No</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="user_image">Latest Comment</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" id="old_latest_comment" name="old_latest_comment" type="hidden"/>
                                    <p id="latest_comment"></p>
                                </div>
                            </div>

                            <!-- Split button -->
                            <div class="form-group row modal-footer">
                                <div class="col-md-3"></div>
                                <div class="col-md-9 text-right">
                                    <button type="submit" class="btn btn-warning btn-lg" style="border-radius:10px;" id="member_save" data-uid="">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>