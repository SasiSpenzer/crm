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
        @if(isset($flash_message))
            <div class="alert alert-danger" id="flash_message">
                {{$flash_message}}
            </div>
        @endif
        <div class="panel panel-default">
            <div class="panel-heading">
                Dashboard
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    @if(Auth::user()->admin_level >= 3)
                    @include('widgets.total_users')
                    @include('widgets.total_members')
                    @include('widgets.revenue')
                    @endif
                </div>
                <div class="row">
                    @include('widgets.target_my')
                    @include('widgets.target_group')
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                User Activities
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="row" id="widget-data">
                                    @include('widgets.user_total_data')
                                    @include('widgets.pvt_seller_data')
                                    @include('widgets.unassigned_data')
                                    @include('widgets.online_data')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @include('widgets.today')
                    @include('widgets.last_7_days')
                </div>
                <!-- Adding Source Table By Sasi Spenzer 2021-06-08 -->
{{--                <div class="row">--}}
{{--                    @include('widgets.source')--}}
{{--                </div>--}}
                <!-- end Source Table By Sasi Spenzer 2021-06-08 -->
                <!-- /.row  chart and table not necessary 21st Feb 2020 -->
                <!--div class="row">
                    @ include('widgets.chart_and_table')
                </div-->
                <!-- /.row -->
            </div>
        </div>
    </div>
</div>
<style>
    .list-group-item.primary-item {
        /*background-color: #79b4e7 !important;*/
        border: 1px solid #99ccf8 !important;
    }
    .list-group-item.green-item {
        /*background-color: #a0ef86 !important;*/
        border: 1px solid #55df0e !important;
    }
    .list-group-item.yellow-item {
        /*background-color: #efcd9d !important;*/
        border: 1px solid #c8be40 !important;
    }
    .list-group-item.red-item {
        /*background-color: #e77575 !important;*/
        border: 1px solid #e55570 !important;
    }
    .high-font-weight{
        font-weight: 900 !important;
    }
    .user-panel-body{
        margin-top: -15px !important;
        margin-left: -1px !important;
    }
    .user-panel{
        height: 190px!important;
        border-color: #fefeff;
    }
    .border-non-radius {
        border-top-left-radius: 0 !important;
        border-top-right-radius: 0 !important;
    }
    .list-group-item:last-child {
        margin-bottom: 0;
        border-bottom-right-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
    }
    .border-color-non {
        border-color: #ffffff !important;
    }
</style>
<!-- Flot Charts JavaScript -->
<script src="{{ URL::asset('/vendor/flot/excanvas.min.js') }}"></script>
<script src="{{ URL::asset('/vendor/flot/jquery.flot.js') }}"></script>
<script src="{{ URL::asset('/vendor/flot/jquery.flot.pie.js') }}"></script>
<script src="{{ URL::asset('/vendor/flot-tooltip/jquery.flot.tooltip.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/app/dashboard/app.dashboard.dashboard.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/app/dashboard/user_dashboard.js?v=13') }}"></script>
@endsection