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
                    Expired +2 (Hunters)
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="expired_hunter">
                            <div class="alert" id="flash_message" style="display:none">
                            </div>
                            <table width="100%" class="table table-striped table-bordered table-hover" id="tbl-expired-hunter">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>No. of ads</th>
                                        <th>Last updated date</th>
                                        <th>Membership category</th>
                                        <th>Payment Expiry</th>
                                        <th>AM</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                            @include('member.details_modal')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{ URL::asset('/js/app/payment/expired_hunter.js') }}"></script>
@endsection