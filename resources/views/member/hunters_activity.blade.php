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

                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
{{--                        @include('widgets.am.total_customer')--}}
{{--                        @include('widgets.am.mem_target')--}}
{{--                        @include('widgets.am.today_updated')--}}
{{--                        @include('widgets.am.month_updated')--}}
                    </div>
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Admin member activities
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="col-lg-12">
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <th>Am</th>
                                            <th></th>
                                            <?php foreach ($ams as $eachAM){?>
                                            <th><?php echo $eachAM->username; ?></th>
                                            <?php }  ?>
                                        </tr>
                                        <?php foreach ($resultsTable as $key=> $each_record){ ?>
                                        <tr>
                                            <td rowspan="3"><?php echo $key;  ?></td>
                                            <td>Today</td>
                                            <?php foreach ($each_record['Today'] as $ams_value){ ?>
                                            <td><?php echo $ams_value['count'].' ('.$ams_value['convert'].')'; ?></td>

                                            <?php }  ?>

                                        </tr>
                                        <tr>

                                            <td>Last 30 Days</td>
                                            <?php foreach ($each_record['Last_30_days'] as $ams_value){ ?>
                                            <td><?php echo $ams_value['count'].' ('.$ams_value['convert'].')'; ?></td>

                                            <?php }  ?>

                                        </tr>
                                        <tr style="background-color: lightyellow;">

                                            <td>Total Assigned</td>
                                            <?php foreach ($ams as $eachAM){?>

                                            <td><?php echo  $each_record[$eachAM->username]['total'] ; ?></td>
                                            <?php }?>



                                        </tr>
                                        <?php } ?>
                                    </table>
                                </div>

                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
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