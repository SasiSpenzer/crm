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
                    Customer Register
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <!--  -->
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="home-pills">

                            <div class="container register-form">
                                <div class="form">
                                    <div class="note">
                                        <p>Please Upload Your CSV File</p>
                                    </div>

                                    <form action="{{ action('CustomerController@uploadCustomerData') }}" enctype="multipart/form-data"  method="post" id="customer_reg_form">
                                        <div class="form-content">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php
                                                    if(isset($error_list[0])){?>
                                                        <?php if($error_list[0] != 'List Imported Successfully!') {?>
                                                        <div class="alert alert-danger" role="alert">
                                                            Data Updated Without Following Emails. These emails are Already Exists in the System.
                                                            <?php foreach ($error_list as $each){
                                                                echo "<li>$each</li>";
                                                              }?>
                                                        </div>
                                                        <?php }else { ?>
                                                        <div class="alert alert-success" role="alert">
                                                            <?php echo $error_list[0]; ?>
                                                        </div>
                                                        <?php  } ?>
                                                    <?php } ?>
                                                    <div class="form-group">
                                                        <input type="file" class="form-control" name="customer_file" id="customer_file" placeholder="Customer First name *" value=""/>
                                                        <span></span>
                                                        <span id="first_name_error" style="display: none;color: red;margin-left: 14px;">Please Enter First Name</span>
                                                    </div>

                                            </div>
                                            <button type="submit" class="btnSubmit">Submit</button>
                                        </div>
                                    <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                                    </form>

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

<style>
    .note
    {
        text-align: center;
        height: 80px;
        background: -webkit-linear-gradient(left, #ca4a1f, #8811c5);
        color: #fff;
        font-weight: bold;
        line-height: 80px;
    }
    .form-content
    {
        padding: 5%;
        border: 1px solid #ced4da;
        margin-bottom: 2%;
    }
    .form-control{
        border-radius:1.5rem;
    }
    .btnSubmit
    {
        border:none;
        border-radius:1.5rem;
        padding: 1%;
        width: 20%;
        cursor: pointer;
        background: #0062cc;
        color: #fff;
    }
</style>
    <script type="text/javascript" src="{{ URL::asset('/js/app/customer/register_validate.js') }}"></script>
@endsection