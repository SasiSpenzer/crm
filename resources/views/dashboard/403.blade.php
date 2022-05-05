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
                <div class="row" style="text-align: center;">
                    <h4>Access Denied !</h4>
                    
                </div>
                
                
                
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

@endsection