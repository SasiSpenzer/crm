@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <h2 class="label-info"></h2>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Agent Activity Report
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <h5>Email</h5>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <input type="hidden" id="user_email" name="user_email" value="{{$user_email}}">
                            <input class="form-control"  id ="search_email"  placeholder = 'Search By Email' value="{{$user_email != ''?$user_email : null}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-2 col-sm-2 col-md-2">
                        <button type="submit" class="btn btn-default btn-warning" id="list-ads" >Show Agent Activity</button>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2">
                        <button type="button" class="btn btn-default btn-danger" id="reset-activity" >Reset</button>
                    </div>
                </div>
                <h4></h4>
                <!--  -->
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="list-ads-by-email-div">
                        <div class="alert" id="flash_message" style="display:none"></div>
                        <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="agent-activity-table">
                            <thead>
                                <tr>
                                    <th colspan="8" style="text-align: center !important;">{{ $header }}</th>
                                    <th></th>
                                    <th colspan="6" style="text-align: center !important;">{{ $month_q1 }}</th>
                                    <th></th>
                                    <th colspan="6" style="text-align: center !important;">{{ $month_q2 }}</th>
                                    <th></th>
                                    <th colspan="6" style="text-align: center !important;">{{ $month_q3 }}</th>
                                </tr>
                                <tr>

                                    <th>UID</th>
                                    <th>First Name</th>
                                    <th>Surname</th>
                                    <th>Email</th>
                                    <th>Category</th>
                                    <th>AM</th>
                                    <th>Expiry</th>
                                    <th>Payment Exp Date</th>
                                    <th>[ {{ $month_q1 }} REPORT ]</th>
                                    <th>Posts</th>
                                    <th>Edits</th>
                                    <th>Boosts</th>
                                    <th>Online Payments</th>
                                    <th>Views</th>
                                    <th>Leads</th>
                                    <th>[ {{ $month_q2 }} REPORT ]</th>
                                    <th>Posts</th>
                                    <th>Edits</th>
                                    <th>Boosts</th>
                                    <th>Online Payments</th>
                                    <th>Views</th>
                                    <th>Leads</th>
                                    <th>[ {{ $month_q3 }} REPORT ]</th>
                                    <th>Posts</th>
                                    <th>Edits</th>
                                    <th>Boosts</th>
                                    <th>Online Payments</th>
                                    <th>Views</th>
                                    <th>Leads</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<style>
    /*#month2 {
        background: #5cb85c !important;
        background-color: #5cb85c !important;
    }*/
</style>
<script type="text/javascript" src="{{ URL::asset('/js/app/member/agent-activity.js') }}"></script>
@endsection
