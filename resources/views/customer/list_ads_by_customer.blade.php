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
                Activate / De-activate Ads
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <h5>Email</h5>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <input type="hidden" id="user_email" name="user_email" value="{{$user_email}}">
                                <input type="hidden" id="max_ads_count" name="max_ads_count" value="{{$max_ad_count}}">
                                <input class="form-control"  id ="search_email"  placeholder = 'Search By Email' value="{{$user_email != ''?$user_email : null}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <button type="submit" class="btn btn-default btn-warning" id="list-ads" >Show Ads </button>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <button type="submit" class="btn btn-default btn-warning" id="save-ads" >Save All </button>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <button type="submit" class="btn btn-default btn-warning" id="boost-ads" >Boost All </button>
                        </div>
                    </div>
                <h4></h4>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="alert" id="flash_message" style="display:none"></div>
                        <div class="form-group" id="list-ads-by-email-div">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="list-ads-by-email">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="hidden" name="total_ad_count" id="total_ad_count" value="0">
                                            <input type="hidden" name="used_ad_count" id="used_ad_count" value="0">
                                            <div class="checkbox"><input type="checkbox"  id="select-all">All</div>
                                        </th>
                                        <th>Heading</th>
                                        <th>Type</th>
                                        <th>Ad ID</th>
                                        <th>Views</th>
                                        <th>Leads</th>
                                        <th>Page</th>
                                        <th>Price</th>
                                        <th>Priority</th>
                                        <th>Last B 30D</th>
                                        <th>Updated</th>
                                        <th>Posted</th>
                                        <th></th>

                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<style>
    .yellow-class,
    .yellow-class:hover {
        background-color: lightyellow !important;
    }

</style>
<!-- /.row -->
<script type="text/javascript" src="{{ URL::asset('/js/app/customer/app.customer.ads.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/app/customer/range.js') }}"></script>
@endsection