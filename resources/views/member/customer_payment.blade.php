@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Customer Payment Details
                </div>
                <div class="panel-body">
                    <div class="col-sm-12 col-md-12 col-lg-12" id="">
                        <div class="row" style="margin-top: 150px">
                            <div class="col"></div>
                            <div class="col-md-6" >
                                <center>
                                    <figure class="figure">
                                        <img src="{{ env('APP_URL').'/dist/img/logo1.png' }}" class="figure-img img-fluid rounded" width="235px" alt="logo">
                                        <figcaption class="figure-caption"></figcaption>
                                    </figure>
                                </center>
                                <hr>
                                <form method="post" action="">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Invoice No :</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" name="" required="" >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Customer Name :</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="" >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Price :</label>
                                        <div class="col-sm-8">
                                            <input type="number" step=".1" min="0" value="0.00" class="form-control" name="" >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Action :</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="" >
                                        </div>
                                    </div>
                                    <div class="form-group form-control-sm">
                                        <button type="submit" name="cus_payment" id="cus_payment"  class="btn btn-outline-success btn-block">payment</button>
                                    </div>
                                </form>

                            </div>
                            <div class="col"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
