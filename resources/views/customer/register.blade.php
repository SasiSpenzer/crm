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
                            <!-- Changed for S size -->
                            <div style="width: 90%!important;" class="register-form">
                                <div class="form">
                                    <div class="note">
                                        <p>Please Enter Your Customer Details Below</p>
                                    </div>

                                    <form action="{{ action('CustomerController@getCustomerRegisterData') }}" onSubmit="return validateData(this);" method="post" id="customer_reg_form">
                                        <div class="form-content">
                                            <div class="row">
                                                <div class="col-md-12">

                                                    <?php  if($error != '') {  ?>
                                                    <div class="alert" id="flash_message" style="color: red;">
                                                        <?php  if($error != ''){ echo $error ; }  ?>
                                                    </div>
                                                        <?php  }  ?>
                                                        <?php  if(isset($_GET['success'])) {  ?>
                                                        <div class="alert-success" id="flash_message" style="color: green;height:25px;">
                                                            <?php  if($_GET['success'] == '1'){ echo "User Added Succesfully !" ; }  ?>
                                                        </div>
                                                        <br>
                                                        <?php  }  ?>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Customer First name *" value=""/>
                                                        <span></span>
                                                        <span id="first_name_error" style="display: none;color: red;margin-left: 14px;">Please Enter First Name</span>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Customer Surname *" value=""/>
                                                        <span></span>
                                                        <span id="last_name_error" style="display: none;color: red;margin-left: 14px;">Please Enter Surname</span>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="email"  id="email" placeholder="Customer Email" value=""/>
                                                        <span style="margin-top: 3px;margin-left: 10px;">
                                                            <?php if(isset($email)) echo 'Suggestion (if no email) - '. $email ; ?>
                                                        </span>
                                                        <span id="email_error" style="display: none;color: red;margin-left: 14px;">Please Enter Valid Customer Email</span>
                                                    </div>

                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="telephone" id="telephone" placeholder="Customer Telephone" value=""/>
                                                        <span></span>
                                                        <span id="telephone_error" style="display: none;color: red;margin-left: 14px;">Please Enter Valid Customer Contact Number</span>
                                                    </div>
                                                    <div class="form-group">
                                                        <select class="form-control" name="source" id="source">
                                                            <option value="0">Please Select Source</option>
                                                            <option value="Prospects">Prospects</option>
                                                            <option value="Inbound_call">Inbound Call</option>
                                                            <option value="Outbound_call">Outbound call</option>
                                                            <option value="Chat_Email">Chat/Email</option>
                                                            <option value="Newspaper">Newspaper</option>
                                                            <option value="Ikman_List">Ikman List</option>
                                                            <option value="other_website">Other Website</option>
                                                            <option value="fb">FB</option>
                                                            <option value="other">Other</option>
                                                            <option value="agents">Agents - Other sites</option>
                                                        </select>

                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="link" id="link" placeholder="Customer Link" value=""/>
                                                    </div>
                                                    <div class="form-group">
                                                        <span style="font-weight: bold;margin-bottom: 10px;">Type of Customer</span>

                                                        <label style="display:block;margin-top: 10px;">
                                                            <input checked name="agent" type="radio" value="O">
                                                            Owner
                                                            &nbsp;&nbsp;&nbsp; <input name="agent" type="radio" value="A">
                                                            Agent
                                                            &nbsp;&nbsp;&nbsp; <input name="agent" type="radio" value="D">
                                                            Developer
                                                            &nbsp;&nbsp;&nbsp; <input name="agent" type="radio" value="B">
                                                            Buyer
                                                        </label>
                                                    </div>
                                                    </div>

                                                </div>
    {{--                                            <div class="col-md-">--}}
    {{--                                                <div class="form-group">--}}
    {{--                                                    <input type="text" class="form-control" placeholder=" *" value=""/>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="form-group">--}}
    {{--                                                    <input type="text" class="form-control" placeholder="Confirm Password *" value=""/>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
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