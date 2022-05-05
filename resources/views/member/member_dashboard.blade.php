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
                    Member Dashboard Report
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <!--  -->
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="home-pills">
                            <div class="alert" id="flash_message" style="display:none">
                            </div>
                            <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="member-dashboard-table">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Membership</th>
                                    <th>Highest Page Id</th>
                                    <th>% of ads with Images</th>
                                    <th>Views Status</th>
                                    <th>Leads Status</th>
                                    <th>Boosts Left</th>
                                    <th>No. Of Ads</th>
                                    <th>Payment Exp Date</th>
                                    <th>Member Since</th>
                                    <th>Membership Exp Date</th>
                                    <th>No. Of Upgraded Ads</th>
                                    <th>Email</th>
                                    <th>am</th>
                                    <th>User Type</th>
                                    <th>Action</th>
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
        .data-row-a {
            background-color: #fe9a9a !important;
        }
        .data-row-b {
            background-color: #fe7337 !important;
        }
        .data-row-c {
            background-color: #fffa85 !important;
        }
    </style>
    <script type="text/javascript" src="{{ URL::asset('/js/app/member/dashboard.js') }}"></script>
@endsection
