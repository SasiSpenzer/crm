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
                Add Invoiced
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="invoice_div">
                        <iframe src="{{$invoice_url}}" style="width: 100% !important;height: 600px !important;">
                            Your browser isn't compatible
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection