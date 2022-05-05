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
                    Change ad offer type
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <!--  -->
                    <!-- Tab panes -->
                    <div class="tab-content">
                        @if(session('msg'))
                            <div class="alert alert-success" id="flash_message">{{ session('msg') }}</div>
                        @endif
                        @if(session('error_msg'))
                            <div class="alert alert-danger" id="flash_message">{{ session('error_msg') }}</div>
                        @endif
                        @if(isset($admin_level) && $admin_level < 3 && !session('error_msg'))
                            <div class="alert alert-danger" id="flash_message">You haven't permission to change ads offer type!</div>
                        @endif

                        <div class="tab-pane fade in active">
                            <div class="alert" id="flash_message" style="display:none">{{session('msg')}}</div>

                            <form action="{{env('APP_URL')}}/ads/offer/type" method="post" class="form-inline">
                                {{ csrf_field() }}
                                <input type="text" class="form-control" style="width: 50%" name="ad_id" placeholder="Type ad id here">
                                @if(isset($admin_level) && $admin_level > 2)
                                    <input class="btn btn-primary btn-sm" type="submit" name="submit" value="Search">
                                @elseif(isset($admin_level) && $admin_level < 3)
                                    <input class="btn btn-primary btn-sm" type="button" name="submit" value="Search" disabled>
                                @else
                                    <input class="btn btn-primary btn-sm" type="submit" name="submit" value="Search">
                                @endif
                            </form>

                        </div>
                    </div>

                    @if(isset($s_ad))
                        <hr>

                        <div class="tab-content">
                            <div class="tab-pane fade in active">

                                <div class="col-dm-12">
                                    <div class="row">
                                        <div class="col-md-6 well">
                                            <div class="form-group">
                                                <label><u>Ad ID:</u> {{ $s_ad->ad_id }}</label>
                                            </div>

                                            <div class="form-group">
                                                <label><u>Ad Heading:</u> {{ $s_ad->heading }}</label>
                                            </div>

                                            <div class="form-group">
                                                <label><u>Property Type:</u> {{ $s_ad->propty_type }}</label>
                                            </div>

                                            <div class="form-group">
                                                <label><u>Owner Name:</u> {{ $s_ad->firstname }} {{ $s_ad->surname }}</label>
                                            </div>

                                            <div class="form-group">
                                                <label><u>Owner Email:</u> {{ $s_ad->Uemail }}</label>
                                            </div>

                                            &nbsp;<br>&nbsp;<br>&nbsp;
                                        </div>

                                        <div class="col-md-6 well">
                                            <form action="{{env('APP_URL')}}/ads/offer/type" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="s_ad_id" value="{{ $s_ad->ad_id }}">
                                                <input type="hidden" name="uid" value="{{ $s_ad->UID }}">
                                                <div class="form-group">
                                                    <label for="paid" style="width: 100px;">Old Ad Type:</label>
                                                    <input type="text" name="old_type" id="old_type" readonly value="{{ $s_ad->type }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="paid" style="width: 100px;">Ads Type:</label>
                                                    <select class="form-control" id="ads_type" name="ads_type" required autofocus>
                                                        <option value=""> Select new ads type </option>
                                                        @foreach ($s_ad->ads_type as $key => $value)
                                                            @if(isset($value->type) && $value->type != '' && $value->type != null)
                                                                <option value="{{ $value->type }}"> {{ $value->type }} </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <input class="btn btn-danger" type="submit" name="submit" value="Change ads type">
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endif

                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->

@endsection