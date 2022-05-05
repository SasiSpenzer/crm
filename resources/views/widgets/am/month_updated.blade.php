<div class="col-lg-8 col-md-8">
    <div class="panel panel-red user-panel">
        <div class="panel-heading" style="margin-left: -1px !important;">
            <div class="row">
                <div class="col-12 text-center high-font-weight">
                    Expiry
                </div>
            </div>
        </div>
        <div class="panel-body data-panel user-panel-body" style="margin-bottom: -35px !important;">
            <div class="list-group row" style="font-size: 24px !important; margin-top: -15px !important;">
                <div class="list-group-item primary-item col-md-3 col-sm-3 border-non-radius">
                    <div id="total_users_data" class="text-center high-font-weight border-non-radius">
                        {{$expired_count}}
                    </div>
                    <div class="text-center" style="font-size: 15px !important;">
                        Expired
                    </div>
                </div>
                <div class="list-group-item primary-item col-md-3 col-sm-3 border-non-radius">
                    <div id="total_users_data" class="text-center high-font-weight border-non-radius">
                        {{$graced_count}}
                    </div>
                    <div class="text-center" style="font-size: 15px !important;">
                        Grace Period
                    </div>
                </div>
                <div class="list-group-item primary-item col-md-3 col-sm-3 border-non-radius">
                    <div class="text-center high-font-weight border-non-radius" id="total_invoiced">
                        {{$will_expire_count}}
                    </div>
                    <div class="text-center" style="font-size: 15px !important;">
                        Exp. in 7 days
                    </div>
                </div>
                <div class="list-group-item primary-item col-md-3 col-sm-3 border-non-radius">
                    <div class="text-center high-font-weight border-non-radius" id="total_collected">
                        <span style="font-size: 20px !important;">{{$lose_count}}</span>
                        <span style="font-size: 16px !important;">({{$likely_lose_percentage}}%)</span>
                    </div>
                    <div class="text-center" style="font-size: 15px !important;">
                        Likely to Lose
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>