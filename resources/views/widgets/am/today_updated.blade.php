<div class="col-lg-4 col-md-4">
    <div class="panel panel-yellow user-panel">
        <div class="panel-heading" style="margin-left: -1px !important;">
            <div class="row">
                <div class="col-12 text-center high-font-weight">
                    Updated
                </div>
            </div>
        </div>
        <div class="panel-body data-panel user-panel-body" style="margin-bottom: -35px !important;">
            <div class="list-group row" style="font-size: 24px !important; margin-top: -15px !important;">
                <div class="list-group-item primary-item col-md-6 col-sm-6 border-non-radius">
                    <div class="text-center high-font-weight border-non-radius" id="total_invoiced">
                        {{$activity_count}}
                    </div>
                    <div class="text-center" style="font-size: 15px !important;">
                        Today
                    </div>
                </div>
                <div class="list-group-item primary-item col-md-6 col-sm-6 border-non-radius">
                    <div class="text-center high-font-weight border-non-radius" id="total_collected">
                        {{$last_week_activity_count}}
                    </div>
                    <div class="text-center" style="font-size: 15px !important;">
                        Last 7
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>