<div class="col-lg-6 col-md-6">
    <div class="panel panel-green user-panel">
        <div class="panel-heading" style="margin-left: -1px !important;">
            <div class="row">
                <div class="col-12 text-center high-font-weight">
                    Memberships
                </div>
            </div>
        </div>
        <div class="panel-body data-panel user-panel-body" style="margin-bottom: -35px !important;">
            <div class="list-group row" style="font-size: 24px !important; margin-top: -15px !important;">
                <div class="list-group-item primary-item col-md-4 col-sm-4 border-non-radius">
                    <div id="current_member_count" class="text-center high-font-weight border-non-radius">
                        {{$current_mem_count}}
                    </div>
                    <div class="text-center" style="font-size: 15px !important;">
                        Current
                    </div>
                </div>
                <div class="list-group-item primary-item col-md-4 col-sm-4 border-non-radius">
                    <div class="text-center high-font-weight border-non-radius" id="converted_member_count">
                        {{$conv_mem_count}}
                    </div>
                    <div class="text-center" style="font-size: 15px !important;">
                        Converted
                    </div>
                </div>
                <div class="list-group-item primary-item col-md-4 col-sm-4 border-non-radius">
                    <div class="text-center high-font-weight border-non-radius" id="target_member_count">
                        {{$mem_target}}
                    </div>
                    <div class="text-center" style="font-size: 15px !important;">
                        Target
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>