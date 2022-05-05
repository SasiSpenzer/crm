@extends('layouts.app')
@section('content')
    <div class="row">
        <!--div class="col-lg-12">
            <h2 class="label-info"></h2>
        </div-->
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="{!! url('/dashboard') !!}"> Dashboard</a> >
                    <label>Sell fast listings view report</label>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <!--  -->
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 row">
                                <div class="form-group col-xs-4 col-sm-4 col-md-4">
                                    <input class="form-control"  id ="start_date" name ="start_date"  placeholder = 'Start Date' value="" type="text">
                                </div>
                                <div class="form-group col-xs-4 col-sm-4 col-md-4">
                                    <input class="form-control"  id ="end_date" name ="end_date"  placeholder = 'End Date' value="" type="text">
                                </div>
                                <div class="col-xs-2 col-sm-2 col-md-2 right">
                                    <a id="add_date_range" class="btn btn-default btn-warning" href="#search-data-div" data-toggle="tab">
                                        Search
                                    </a>
                                </div>
                                <div class="col-xs-2 col-sm-2 col-md-2 right">
                                    <button type="button" class="btn btn-default btn-danger" id="clear_date_range" >Reset</button>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade in active" id="home-pills">
                            <div class="alert" id="flash_message" style="display:none"></div>
                            <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="paa-agents-table">
                                <thead>
                                    <tr>
                                        <th colspan="2"></th>
                                        <th colspan="2" style="text-align: center !important;">Week</th>
                                        <th colspan="2" style="text-align: center !important;">Month</th>
                                        <th colspan="2" style="text-align: center !important;">Last Month</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th>m-PAA Agent</th>
                                        <th>AM</th>
                                        <th>Ads Count</th>
                                        <th>Revenue</th>
                                        <th>Ads Count</th>
                                        <th>Revenue</th>
                                        <th>Ads Count</th>
                                        <th>Revenue</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th colspan="2" style="text-align:center">(Total)</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="tab-pane fade" id="search-data-div">
                            <div class="alert" id="flash_message" style="display:none"></div>
                            <table width="100%" class="table table-striped table-bordered table-hover" id="search-data-tbl">
                                <thead>
                                <tr>
                                    <th>PAA Agent</th>
                                    <th>AM</th>
                                    <th>Ads Count</th>
                                    <th>Revenue</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th colspan="2" style="text-align:center;">(Total)</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </tfoot>
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


    <script type="text/javascript" src="{{ URL::asset('/js/app/customer/mpaa_agents_list.js') }}"></script>
@endsection