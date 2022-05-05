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
                Active Advertisement Reports
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <ul class="nav nav-pills" style="margin-bottom: 5px;">
                    <li class="active">
                        <a data-toggle="tab" href="#home-pills">All (Default)</a> <!-- Home -->
                    </li>
                    <li>
                        <a id="btn_un_conected" href="#un-connected" data-toggle="tab">Unassigned</a>
                    </li>
                    <li>
                        <a id="btn-followups" href="#followups" data-toggle="tab">Not contacted</a>
                    </li>
                    <li>
                        <a id="btn-two-left" href="#twoleft" data-toggle="tab">Follow-up</a>
                    </li>
                    <li>
                        <a id="btn-free" href="#free" data-toggle="tab">Free</a>
                    </li>
                    <li>
                        <a id="btn-lowleads" href="#lowleads" data-toggle="tab">Low Leads</a>
                    </li>


                </ul>

                <!--  -->
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="home-pills">
                        <div class="alert" id="flash_message" style="display:none"></div>
                        <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="users-table">
                            <thead>
                            <tr>
                                <th>Name</th>
{{--                                <th>Expired Date</th>--}}
                                <!--<th>Amount</th>-->
                                <th>Upgrade type</th>
                                <th>AM</th>
                                <th>Status</th>
                                <th>Latest Comments</th>
                                <th>Last update date</th>
                                <th>Payment exp date</th>
                                <th>Email</th>
                                <th>Tel</th>
                                <th>Add type</th>
                                <th>Property type</th>
                                <th>Duration</th>
                                <th></th>
                            </tr>
                            </thead>
                        </table>
                        <!--@ include('member.edit')-->

                    </div>

                    <div class="tab-pane fade" id="un-connected">
                        <div class="alert" id="flash_message" style="display:none"></div>
                        <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="users-table-un-connected">
                            <thead>
                            <tr>
                                <th>Name</th>
{{--                                <th>Expired Date</th>--}}
                                <!--<th>Amount</th>-->
                                <th>Upgrade type</th>
                                <th>AM</th>
                                <th>Status</th>
                                <th>Latest Comments</th>
                                <th>Last update date</th>
                                <th>Payment exp date</th>
                                <th>Email</th>
                                <th>Tel</th>
                                <th>Add type</th>
                                <th>Property type</th>
                                <th>Duration</th>
                                <th></th>
                            </tr>
                            </thead>
                        </table>
                        <!--@ include('member.edit')-->

                    </div>
                    <div class="tab-pane fade" id="followups">
                        <div class="alert" id="flash_message" style="display:none"></div>
                        <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="users-table-follow">
                            <thead>
                            <tr>
                                <th>Name</th>
{{--                                <th>Expired Date</th>--}}
                                <!--<th>Amount</th>-->
                                <th>Upgrade type</th>
                                <th>AM</th>
                                <th>Status</th>
                                <th>Latest Comments</th>
                                <th>Last update date</th>
                                <th>Payment exp date</th>
                                <th>Email</th>
                                <th>Tel</th>
                                <th>Add type</th>
                                <th>Property type</th>
                                <th>Duration</th>
                                <th></th>
                            </tr>
                            </thead>
                        </table>
                        <!--@ include('member.edit')-->

                    </div>
                    <div class="tab-pane fade" id="twoleft">
                        <div class="alert" id="flash_message" style="display:none"></div>
                        <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="users-table-two-left">
                            <thead>
                            <tr>
                                <th>Name</th>
{{--                                <th>Expired Date</th>--}}
                                <!--<th>Amount</th>-->
                                <th>Upgrade type</th>
                                <th>AM</th>
                                <th>Status</th>
                                <th>Latest Comments</th>
                                <th>Last update date</th>
                                <th>Payment exp date</th>
                                <th>Email</th>
                                <th>Tel</th>
                                <th>Add type</th>
                                <th>Property type</th>
                                <th>Duration</th>
                                <th></th>
                            </tr>
                            </thead>
                        </table>
                        <!--@ include('member.edit')-->

                    </div>

                    <div class="tab-pane fade" id="free">
                        <div class="alert" id="flash_message" style="display:none"></div>
                        <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="users-table-free">
                            <thead>
                            <tr>
                                <th>Name</th>
                            {{--                                <th>Expired Date</th>--}}
                            <!--<th>Amount</th>-->
                                <th>Upgrade type</th>
                                <th>AM</th>
                                <th>Status</th>
                                <th>Latest Comments</th>
                                <th>Last update date</th>
                                <th>Payment exp date</th>
                                <th>Email</th>
                                <th>Tel</th>
                                <th>Add type</th>
                                <th>Property type</th>
                                <th>Duration</th>
                                <th></th>
                            </tr>
                            </thead>
                        </table>
                        <!--@ include('member.edit')-->

                    </div>
                    <div class="tab-pane fade" id="lowleads">
                        <div class="alert" id="flash_message" style="display:none"></div>
                        <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="users-low-leads">
                            <thead>
                            <tr>
                                <th>Name</th>
                            {{--                                <th>Expired Date</th>--}}
                            <!--<th>Amount</th>-->
                                <th>Upgrade type</th>
                                <th>AM</th>
                                <th>Status</th>
                                <th>Latest Comments</th>
                                <th>Last update date</th>
                                <th>Payment exp date</th>
                                <th>Email</th>
                                <th>Tel</th>
                                <th>Add type</th>
                                <th>Property type</th>
                                <th>Duration</th>
                                <th></th>
                            </tr>
                            </thead>
                        </table>
                        <!--@ include('member.edit')-->

                    </div>
                    @include('member.details_modal')
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
    .green-class,
    .green-class:hover {
        background-color: #ddeb6d !important;
    }

</style>

<script type="text/javascript" src="{{ URL::asset('/js/app/ads/single-ads-active.js?v=0.3') }}"></script>
@endsection
