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
                    Archive Customer Report
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <!--  -->
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="home-pills">
                            <p>
                            <div class="alert" id="flash_message" style="display:none">
                            </div>
                            <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="customers-archive-table">
                                <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Ad ID</th>
                                    <th>Heading</th>
                                    <th>Email Address</th>
                                    <th>Tel. Number</th>
                                    <th>Deleted Date</th>
                                </tr>
                                </thead>
                            </table>
                            </p>
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


    <script type="text/javascript" src="{{ URL::asset('/js/app/customer/customer_archive_list.js') }}"></script>
@endsection