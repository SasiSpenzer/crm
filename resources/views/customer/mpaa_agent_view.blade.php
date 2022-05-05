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
                    <a href="{{url('/member/mpaa/agents')}}">
                        <span class="pull-left"><i class="fa fa-arrow-circle-left fa-2x"></i></span>
                    </a>
                    <label style="margin-left: 10px;font-size: 20px;">Sell fast Ads view report - {{$agent_name}}</label>


                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <input type="hidden" id="agent-id" name="agent-id" value="{{$id}}">
                    <!--  -->
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="home-pills">
                            <div class="alert" id="flash_message" style="display:none">
                            </div>
                            <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="paa-agent-data-table">
                                <thead>
                                <tr>
                                    <th>Ad Type</th>
                                    <th>Posted day count</th>
                                    <th>Edit Count</th>
                                    <th>Last Edit Date</th>
                                    <th>URL</th>
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
    @include('customer.mpaa_agent_action_model')
    <style>
        .high {
            background-color: #FFEBCD !important;
        }
    </style>

    <script type="text/javascript" src="{{ URL::asset('/js/app/customer/mpaa_agent_data.js') }}"></script>
@endsection