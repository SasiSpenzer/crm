<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 class="modal-title" id="myModalLabel">Update Membership</h5>
      </div>
      <div class="modal-body">
        <form role="form">
            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
            <p><b>Member ID -  </b>  <input style="border: none" name="uid" type="text" id="memship_uid"> </p>

            <div class="form-group">
                <label>Name</label>
                <input class="form-control" disabled id ="memship_fullname">
            </div>
            <div class="form-group">
                <label>Membership Type</label>
                <select class="form-control" id="memship_type">
                @if(count(Config::get('membership.type')))>0)
                    @foreach(Config::get('membership.type') as $type)
                    <option value="{{$type}}">{{$type}}</option>
                    @endforeach
                @else
                    <option value="1">--</option>
                @endif
                </select>
            </div>
            <div class="form-group">
                <label>Membership Category</label>
                <select class="form-control" id="memship_cat">

                <?php
                    $categoies = \App\Package::all()->pluck('package_name')->toArray();
                ?>
                @if(count($categoies)>0)
                    @foreach($categoies as $category)
                    <option value="{{$category}}">{{$category}}</option>
                    @endforeach
                @else
                    <option value="1">--</option>
                @endif
                </select>

                <label id="change-amount" style="cursor: pointer;" class="text-warning">Change Package Amount</label>
            </div>
            <div id="div-package-amount" class="form-group hidden">
                <label class="text-info">Package Amount</label>
                <input @if(Auth::user()->admin_level <= 2) readonly @endif class="form-control" id ="package-amount">
            </div>
            
            <div class="form-group">
                <label>Membership Payment</label>
                <select class="form-control" id="memship_payment">
                @if(count(Config::get('membership.payment')))>0)
                    @foreach(Config::get('membership.payment') as $payment)
                    <option value="{{$payment}}">{{$payment}}</option>
                    @endforeach
                @else
                    <option value="1">--</option>
                @endif
                </select>
                <spen id="showRate" style="color: blue;display: none;font-weight: bold;margin-left: 5px;"></spen>
            </div>

            <div class="form-group">
                <label>Membership/Trial Duration</label>
                <select class="form-control" id="memship_duration">
                @if(count(Config::get('membership.duration')))>0)
                    @foreach(Config::get('membership.duration') as $duration)
                    <option value="{{$duration}}">{{$duration}}</option>
                    @endforeach
                @else
                    <option value="1">--</option>
                @endif
                </select>
            </div>

            @if(Auth::user()->admin_level > 2)
                <div class="form-group">
                    <label>Payment expiry date</label>
                    <!-- <input class="form-control" id ="payment_expiry_date" placeholder="YYYY-MM-DD"> -->
    		        <input class="form-control" id ="memship_call_date_time" placeholder="YYYY-MM-DD">
                   <!--  <div class="input-append date form_datetime" data-date="2012-12-21">
                        <input size="16" type="text" value="" readonly id ="memship_call_date_time" class="form-control">
                        <span class="add-on"><i class="icon-remove"></i></span>
                        <span class="add-on"><i class="icon-th"></i></span>
                    </div> -->
                </div>
            @else
                <div class="form-group">
                    <label>Payment expiry date</label>
                    <input class="form-control" disabled="true" id ="memship_call_date_time" placeholder="YYYY-MM-DD">
                </div>
            @endif

            <div class="form-group">
                <label>Membership expiry Date</label>
                <input class="form-control" id ="memship_expiry"  placeholder="YYYY-MM-DD">
            </div>
            <div class="form-group">
                <label>Company name</label>
                <input class="form-control" id ="memship_company">
            </div>
            <div class="form-group">
                <label>Number of Active ads</label>
                <input class="form-control" disabled id ="memship_active_ads">
            </div>
            <div class="form-group">
                <label>Number of leads</label>
                <input class="form-control" disabled id ="memship_leads">
            </div>
            <div class="form-group">
                <label>Mobile Numbers</label>
                <textarea class="form-control" rows="1" id="memship_mobile_nos"></textarea>
            </div>
            @if(Auth::user()->admin_level > 2)
            <div class="form-group" id="div-am-data">
                <label>AM</label>
                <select class="form-control" id="memship_am">
                    <option value="">--</option>
                <?php $ams = \App\User::select('username')->whereIn('department_id', [1])
                        ->get()->pluck('username')->toArray();
                ?>
                @if(count($ams))>0)
                    @foreach($ams as $am)
                    <option value="{{$am}}">{{ ucfirst(trans($am))}}</option>
                    @endforeach
                @else
                    <option value="">--</option>
                @endif

                </select>
            </div>
            @else
                <div class="form-group" id="div-am">
                    <label>AM</label>
                    <input class="form-control" disabled="true" id ="memship_am1">
                </div>
                <div class="form-group hidden" id="div-am-data">
                    <label>AM</label>
                    <select class="form-control" id="memship_am">
                        <option value="">--</option>
                        <?php $ams = \App\User::select('username')->whereIn('department_id', [1])
                            ->get()->pluck('username')->toArray();
                        ?>
                        @if(count($ams))>0)
                        @foreach($ams as $am)
                            <option value="{{$am}}">{{ ucfirst(trans($am))}}</option>
                        @endforeach
                        @else
                            <option value="">--</option>
                        @endif

                    </select>
                </div>
            @endif

            <div class="form-group" id="div-status">
                <label>Status</label>
                <select class="form-control" id="memship_status">
                    <option value="">--</option>
                    <?php
                    $status = \App\PaymentStatus::select('payment_status_id', 'payment_status')->whereIn('is_enable', [1])->get();
                    ?>
                    @if(count($status))>0)
                    @foreach($status as $sta)
                        <option value="{{$sta['payment_status_id']}}">{{ ucfirst(trans($sta['payment_status']))}}</option>
                    @endforeach
                    @else
                        <option value="">--</option>
                    @endif

                </select>
            </div>

             <!-- <div class="form-group">
                <label>Remarks</label>
                <textarea class="form-control" rows="3" id="memship_remarks"></textarea>
            </div> -->

            <button type="submit" class="btn btn-default btn-primary" id="memship_submit" style="float: right;">Save </button>


            <div style="clear: both;"></div>
        </form>
      </div>
      <div class="modal-footer">
            <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <button type="submit" class="btn btn-default btn-warning" id="log" data-toggle="modal"  data-target="#logModal">+ Log Activity</button>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <button type="submit" class="btn btn-default btn-warning" id="sales" data-toggle="modal"  data-target="#salesModal">Add-on Sales </button>
                        </div>
             </div>

      </div>
      <div id="details" style="display:none;margin-left: 20px;margin-bottom: 20px;"></div>
    </div>
  </div>
</div>

<script type="text/javascript">
    @if(Auth::user()->admin_level > 2)
        change_am = true;
    @else
        change_am = false;
    @endif

    log_form_reset = true; //bacause of modal close when the datetimepicker was changed

    $(document).ready(function(){

        $('#Reminder').click(function(){
            if($('#Reminder').is(':checked')){
                $('#div-datetimepicker').removeClass('hidden');
            }
            else{
                $('#txt-reminder').val('');
                $('#div-datetimepicker').addClass('hidden');
            }
        });

        $("#memship_cat,#memship_payment").change(function () {

            let user_package = $("#memship_cat").val();
            let payment_typepp = $("#memship_payment").children("option:selected").val();
            let _token = $("#csrf-token").val();

            $.ajax({
                type:'GET',
                url:'/su/LPW-Admin/public/get-package-price',
                data:{'user_package' : user_package,'payment_type':payment_typepp,'duration':1,'_token': _token},
                success:function(data) {
                        $("#showRate").show();
                        $("#showRate").html(data.ad_rates);

                }
            });
        });

        $('#change-amount').click(function(){
            if($('#div-package-amount').hasClass('hidden')){
                $('#div-package-amount').removeClass('hidden');
            }
            else{
                $('#package-amount').val('');
                $('#div-package-amount').addClass('hidden');
            }
        });

        $('#logModal').on('hide.bs.modal', function () {
            if (log_form_reset) {
                $("#log_form").trigger("reset");
                $('#div-datetimepicker').addClass('hidden');
            }
        });

        /*$('#memship_payment').change(function(){
            $('#memship_duration').val(null);
            if($('#memship_payment').val() == 'Quarterly'){
                $('#memship_duration option[value="6 months"]').addClass('hidden');
                $('#memship_duration option[value="1 year"]').addClass('hidden');
            }
            else{
                $('#memship_duration option[value="6 months"]').removeClass('hidden');
                $('#memship_duration option[value="1 year"]').removeClass('hidden');
            }
        });*/

        $('#memship_submit').click(function(){
            let membership_type = $('#memship_type').val();
            if(membership_type == null){
                alert('Please select, Membership Type.');
                return false;
            }
            if(membership_type == 'Member' && $('#memship_cat').val() == null){
                alert('Please select, Membership Category.');
                return false;
            }
            if(membership_type == 'Member' && $('#memship_payment').val() == null){
                alert('Please select, Membership Payment.');
                return false;
            }
            if(membership_type == 'Member' && $('#memship_duration').val() == null){
                alert('Please select, Membership/Trial Duration.');
                return false;
            }

            var category = $('#memship_cat').find(':selected').val();

            if(membership_type == 'Member' && $('#memship_call_date_time').val() == '' && $("#memship_call_date_time").prop('disabled') != true && (category != 'Free' && category != 'Trial')){
                alert('Please enter, Payment expiry date.');
                return false;
            }

            @if(Auth::user()->admin_level == 1 || Auth::user()->admin_level == 3 || Auth::user()->admin_level == 4)
                if(membership_type == 'Member' && $('#memship_expiry').val() == '' && (category != 'Free' && category != 'Trial')){
                    alert('Please enter, Membership expiry Date.');
                    return false;
                }
            @endif

            //to validate previously entered date. some time enterd date have like this, Quataly -> 1 year (can't be happend)
            if($('#memship_payment').val() == 'Quarterly'){
                if($('#memship_duration').val() != '1 month' && $('#memship_duration').val() != '3 months'){
                    $('#memship_payment').change();
                    alert("Please check 'Membership/Trial Duration'. You can't select '1 month' or '3 months' for Quarterly payment.");
                    return false;
                }
            }
        });

        $('#myModal').on('show.bs.modal', function () {
          //$('#memship_payment').change();
        });

        /*$('#logModal').on('hidden.bs.modal', function () {
            //$("#myModal").show();
        });*/

    });
</script>

<style type="text/css">
    .modal { overflow: auto !important; }
</style>

@include('member.log')
@include('member.sales')

<script type="text/javascript" src="{{ URL::asset('/js/app/modal/edit.js?v=0.1') }}"></script>