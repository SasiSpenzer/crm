<div class="col-lg-6 col-md-6">
    <div class="panel panel-primary user-panel">
        <div class="panel-heading" style="margin-left: -1px !important;">
            <div class="row">
                <div class="col-12 text-center high-font-weight">
                    Revenue
                </div>
            </div>
        </div>
        <div class="panel-body data-panel user-panel-body" style="margin-bottom: -35px !important;">
            <div class="list-group row" style="font-size: 24px !important; margin-top: -15px !important;">
                <div class="list-group-item primary-item col-md-4 col-sm-4 border-non-radius">
                    <div id="total_users_data" class="text-center high-font-weight border-non-radius">
                        <span style="font-size: 20px !important;">{{$achieved_value}}</span>
                        <span style="font-size: 16px !important;">({{$achieved_percentage}}%)</span>
                    </div>
                    <div class="text-center" style="font-size: 15px !important;">
                        Achieved
                    </div>
                </div>
                <div class="list-group-item primary-item col-md-4 col-sm-4 border-non-radius">
                    <div class="text-center high-font-weight border-non-radius" id="total_invoiced">
                        {{$rev_target}}
                    </div>
                    <div class="text-center" style="font-size: 15px !important;">
                        Target
                    </div>
                </div>
                <div class="list-group-item primary-item col-md-4 col-sm-4 border-non-radius">
                    <div class="text-center high-font-weight border-non-radius" id="total_collected">{{$potential_value}}</div>
                    <div class="text-center" style="font-size: 15px !important;">
                        Potential
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>