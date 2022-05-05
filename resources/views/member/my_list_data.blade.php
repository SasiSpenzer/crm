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
                    {{$full_name}}
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        @include('widgets.am.total_customer')
                        @include('widgets.am.mem_target')
                        @include('widgets.am.today_updated')
                        @include('widgets.am.month_updated')
                    </div>
                    <!--div class="row">
                        @ include('widgets.am.deactivated_member')
                        @ include('widgets.am.expired_member')
                        @ include('widgets.am.expired_week')
                        @ include('widgets.am.lose_member')
                    </div>
                    <div class="row">
                        @ include('widgets.am.rev_target')
                        @ include('widgets.am.mem_potential')
                        @ include('widgets.am.rev_achieved')
                    </div-->

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Customer Data
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">
                                    <div class="tab-pane fade in active" id="home-pills">
                                        <div class="alert" id="flash_message" style="display:none"></div>
                                        <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="customer-table">
                                            <thead>
                                                <th>Full Name</th>
                                                <th>Category</th>
                                                <th>AM</th>
                                                <th>Membership Status</th>
                                                <th>Payment Exp. Date</th>
                                                <th>Status</th>
                                                <th>Pending Amount</th>
                                                <th>Latest Comments</th>
                                                <th>Last Updated</th>
                                                <th>Expiry</th>
                                                <th>Leads</th>
                                                <th>Views</th>
                                                <th>No. Of Ads</th>
                                                <th>Column ID</th>
                                                <th>Email Address</th>
                                                <th>Edit</th>
                                            </thead>
                                        </table>
                                    </div>
                                    <!--@ include('member.edit')-->
                                    @include('member.details_modal')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{ URL::asset('/js/app/dashboard/my_list_data.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('/js/app/payment/expired.js') }}"></script>
    <style>
        tr.expired {
            background-color: #f19292 !important;
        }
        tr.deactivated {
            background-color: #e9944a !important;
        }
        tr.month_expired {
            background-color: #f8bac5 !important;
        }
        tr.two_week {
            background-color: #ddeb6d !important;
        }
    </style>
@endsection