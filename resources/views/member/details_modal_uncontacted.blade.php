<!-- Modal start-->
<div class="modal fade" id="membershipModalunfollow" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="membershipModalContent">
            <div class="modal-header">
                <button type="button" class="close" aria-label="Close" onclick="closeMembershipModalUncon()"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="myModalLabel">Membership Manage</h5>
            </div>
            <div class="modal-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" id="tabs">
                    <li class="active">
                        <a href="#home" data-toggle="tab" onclick="view_summary()">Summary</a>
                    </li>
                    <li>
                        <a href="#details" data-toggle="tab" onclick="view_details()">Details</a>
                    </li>
                    <li>
                        <a href="#profile" data-toggle="tab" onclick="view_profile()">Profile</a>
                    </li>
                    <li>
                        <a href="#stats" data-toggle="tab" onclick="view_stats()">Stats</a>
                    </li>
                    <li>
                        <a href="#activity" data-toggle="tab" onclick="view_activity()">Activity</a>
                    </li>
                    <!--li id="payment_tab" class="hidden"-->
                    <li id="payment_tab">
                        <a href="#payments" data-toggle="tab" onclick="view_payments()">Payments</a>
                    </li>
                    <li>
                        <a href="#billings" data-toggle="tab" onclick="view_billings()">Billings</a>
                    </li>
                    <li>
                        <a href="#addons" data-toggle="tab" onclick="view_addons()">Add-Ons</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">

                    <div class="alert alert-block hide" id="alert-block">
                        <button type="button" class="close"  onclick="close_alert()">×</button>
                        <strong id="alert-block-data"></strong>
                    </div>
                    <div class="alert alert-block hide" id="approval-alert-block">
                        <button type="button" class="close" onclick="close_approval_alert()">×</button>
                        <strong id="approval-alert-block-data"></strong>
                    </div>
                    <div class="alert alert-block hide" id="google-sheet-alert-block">
                        <button type="button" class="close" onclick="close_google_data_alert()">×</button>
                        <strong id="google-sheet-alert-block-data"></strong>
                    </div>

                    @include('member.summary_tab')
                    @include('member.details_tab')
                    @include('member.profile_tab')
                    @include('member.stats_tab')
                    @include('member.activity_tab')
                    @include('member.payments_tab')
                    @include('member.billings_tab')
                    @include('member.addons_tab')
                    <div class="tab-pane fade hide" id="messages">
                        <h4>Messages Tab</h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                    </div>
                    <div class="tab-pane fade hide" id="settings">
                        <h4>Settings Tab</h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .nav-tabs>li.active>a {
        background-color: #f5f5f5 !important;
        border: 1px solid #e3e3e3 !important;
    }
    #membership_action {
        margin-left: 0 !important;
    }
    #member_status {
        margin-left: 20px !important;
    }
</style>
<script type="text/javascript" src="{{ URL::asset('/js/app/modal/member-data.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/app/customer/range.js') }}"></script>
<!-- Modal close-->