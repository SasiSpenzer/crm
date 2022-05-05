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
            <div class="panel-heading" style="color: red;">
                Reset Customer Password
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <!--  -->
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="alert alert-success hidden" id="flash_message"></div>
                    <div class="alert alert-danger hidden" id="flash_error_message"></div>

                    <div class="tab-pane fade in active">
                        <div class="alert" id="flash_message" style="display:none">{{session('msg')}}</div>
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label for="customer_name">Customer Email Address </label>
                                </div>
                                <div class="col-md-5">
                                    <input class="form-control" id="customer_email" name="customer_email" type="email" value="" placeholder="Type customer's email address. ex: customer_name@gmail.com" autocomplete="off" required/>
                                </div>
                                <div class="col-md-3">
                                    <button id="reset_btn" class="btn btn-lg btn-danger">Reset Password</button>
                                </div>
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
<!-- /.row -->

@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#reset_btn').click(function () {
                $("#reset_btn"). attr("disabled", true);
                let customer_email = $("#customer_email").val();
                $.ajax({
                    url: '/su/LPW-Admin/public/password/reset-confirm',
                    /*url: '/password/reset-confirm',*/
                    type: 'post',
                    cache: false,
                    data: {
                        _token: "{{ csrf_token() }}", customer_email: customer_email
                    },
                    success: function (data) {
                        if (data == 1) {
                            $("#flash_message").html("Customer password reset successfully!");
                            $("#flash_message").removeClass("hidden");
                            $("#flash_error_message").addClass("hidden");
                        } else {
                            $("#flash_error_message").html(data);
                            $("#flash_message").addClass("hidden");
                            $("#flash_error_message").removeClass("hidden");
                        }
                        $("#reset_btn"). attr("disabled", false);
                    },
                    error: function () {
                        $("#flash_error_message").html("Something went wrong. Please, try again later!");
                        $("#flash_message").addClass("hidden");
                        $("#flash_error_message").removeClass("hidden");
                        $("#reset_btn"). attr("disabled", false);
                    }
                });
            });
        });
    </script>
@endsection