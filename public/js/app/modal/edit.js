$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //$("#payment_expiry_date").datetimepicker({
   $("#datetimepicker").datetimepicker({

        showOn: "button",
        showSecond: false,
        minView: 2,
        format: "yyyy-mm-dd",
        //dateFormat: "yy-mm-dd",
        showTimepicker: false,
        autoclose: true,
        todayBtn: true,
    }).on('change', function (e) {
        log_form_reset = false;
        setTimeout(disable_click, 100);
    });

    function disable_click(){
        log_form_reset = true;
    }

    $("#memship_call_date_time").datetimepicker({

        showOn: "button",
        showSecond: false,
        minView: 2,
        format: "yyyy-mm-dd",
        //dateFormat: "yy-mm-dd",
        showTimepicker: false,
        autoclose: true,
        todayBtn: true
    });

    $("#memship_expiry").datetimepicker({

        showOn: "button",
        showSecond: false,
        minView: 2,
        format: "yyyy-mm-dd",
        //dateFormat: "yy-mm-dd",
        showTimepicker: false,
        autoclose: true,
        todayBtn: true
    });

    $(document).on("click", ".btn-edit-memship", function(e) { //user click on edit button
        e.preventDefault();
        userId = $(this).attr("id");
        $.ajax({
            type: "POST",
            url: url + "/customer/details",
            data: {
                "uid": userId,
            },
        }).done(function(member) {
            if (member.am == null || member.am == "" || member.am == "Online"){
                $('#div-am').addClass('hidden');
                $('#div-am-data').removeClass('hidden');
            }
            $("#memship_fullname").val(member.firstname + " " + member.surname);
            $("#memship_uid").val(member.UID);
            if(member.payment_status != null && member.payment_status != ""){
                $("#memship_status").val(member.payment_status);
            }


            if (member.type != null && member.type != "") {
                $("#memship_type").val(member.type);
            } else {
                $("#memship_type").val("");
            }
            if (member.category != null && member.category != "") {
                $("#memship_cat").val(member.category);
            } else {
                $("#memship_cat").val("");
            }
            if (member.duration != null && member.duration != "") {
                $("#memship_duration").val(member.duration);
            } else {
                $("#memship_duration").val("");
            }
            if (member.payment != null && member.payment != "") {
                $("#memship_payment").val(member.payment);
            } else {
                $("#memship_payment").val("");
            }

            if (member.custom_amount == 1) {
                $("#package-amount").val(member.package_amount);
                $('#change-amount').addClass('hidden');
                $('#div-package-amount').removeClass('hidden');
            } else {
                $("#package-amount").val("");
                $('#change-amount').removeClass('hidden');
                $('#div-package-amount').addClass('hidden');
            }

            if (member.am != null && member.am != "") {
                //if(!change_am)
                $("#memship_am").val(member.am);
                $("#memship_am1").val(member.am);
            } /*else {
                $("#memship_am").val(member.username);
            }*/

            if (member.mobile_nos != null && member.mobile_nos != "") {
                $("#memship_mobile_nos").val(member.mobile_nos);
            } else {
                $("#memship_mobile_nos").val("");
            }
            if (member.company != null && member.company != "") {
                $("#memship_company").val(member.company);
            } else {
                $("#memship_company").val("");
            }
            if (member.active_ads != null && member.active_ads != "") {
                $("#memship_active_ads").val(member.active_ads);
            } else {
                $("#memship_active_ads").val("");
            }
            if (member.leads != null && member.leads != "") {
                $("#memship_leads").val(member.leads);
            } else {
                $("#memship_leads").val("");
            }
            if (member.call_date_time != null && member.call_date_time != "") {
                $("#memship_call_date_time").val(member.call_date_time);
            } else if(member.category == 'Single Ad' && member.payment_exp_date != null && member.payment_exp_date != "") {
                $("#memship_call_date_time").val(member.payment_exp_date);
            } else {
                $("#memship_call_date_time").val("");
            }
            $("#details").html("");
            if (member.remarks != null && member.remarks != "") {

                $("#details").css("display", "block");
                if (member.call_date_time != null && member.call_date_time != "") {
                    $("#details").html(member.call_date_time + " - Action Call - Outcome : " + member.remarks);
                }
                $("#details").html(" Remarks - " + member.remarks);
            } else {
                $("#details").html("");
            }
            if (member.memberAction != null && member.memberAction != "") {
                $("#details").css("display", "block");
                for (count = 0; count < member.memberAction.action.length; count++) {
                    if (member.memberAction.qty[count] == null && member.memberAction.value[count] == null) {
                        if (member.memberAction.reminder[count] != null)
                            reminder = ': ' + member.memberAction.reminder[count];
                        else
                            reminder = '';
                        $("#details").append("<br/>" + member.memberAction.date_time[count] + " - Action " + member.memberAction.action[count] + reminder + " - Outcome : " + member.memberAction.comments[count]);
                    } else {
                        $("#details").append("<br/>" + member.memberAction.date_time[count] + " - Add-on sales - " + member.memberAction.action[count] + " - Qty " + member.memberAction.qty[count]);
                    }
                }
            } else {
                // $("#memship_remarks").html("");
            }
            if (member.expiry != null && member.expiry != "") {
                $("#memship_expiry").val(member.expiry);
            } else {
                $("#memship_expiry").val("");
            }
        });

    });

    $(document).on("click", "#memship_submit", function(e) { //user click on submit button
        e.preventDefault();
        memship_uid = $("#memship_uid").val();
        memship_type = $("#memship_type").val();
        memship_cat = $("#memship_cat").val();
        memship_duration = $("#memship_duration").val();
        memship_payment = $("#memship_payment").val();
        if($("#memship_am").val() != undefined && $("#memship_am").val() != null && $("#memship_am").val() != ""){
            memship_am = $("#memship_am").val();
        } else if($("#memship_am1").val() != undefined && $("#memship_am1").val() != null && $("#memship_am1").val() != ""){
            memship_am = $("#memship_am1").val();
        } else {
            memship_am = "";
        }

        memship_mobile_nos = $("#memship_mobile_nos").val();
        memship_company = $("#memship_company").val();
        memship_active_ads = $("#memship_active_ads").val();
        memship_leads = $("#memship_leads").val();
        if ($("#memship_call_date_time").prop('disabled') != true) memship_call_date_time = $("#memship_call_date_time").val(); else memship_call_date_time = null;
        memship_remarks = $("#memship_remarks").val();
        memship_expiry = $("#memship_expiry").val();
        package_amount = $("#package-amount").val();
        memship_status = $("#memship_status").val();

        $('#div-package-amount').addClass('hidden');
        $("#package-amount").val('');

        $.ajax({
            type: "POST",
            url: url + "/member/save",
            //dataType: 'json',
            //headers: {'X-CSRF-Token': token},
            //data: 'fromDate='+from_date+'&toDate='+to_date+'&adults='+adults+'&kids='+kids+'&ageString='+age_string,
            data: {
                "uid": memship_uid,
                "type": memship_type,
                "category": memship_cat,
                "duration": memship_duration,
                "payment": memship_payment,
                "am": memship_am,
                "mobile_nos": memship_mobile_nos,
                "company": memship_company,
                "active_ads": memship_active_ads,
                "leads": memship_leads,
                "call_date_time": memship_call_date_time,
                "remarks": memship_remarks,
                "expiry": memship_expiry,
                "package_amount": package_amount,
                "memship_status": memship_status
            },
        }).done(function(success) {
            $('#myModal').modal('toggle');
            $("#flash_message").css("display", "block");
            if (success == "true") {
                $("#flash_message").html("Membership updated successfully");
                $("#flash_message").addClass("alert-success");
                $("#flash_message").show().delay(5000).fadeOut();
                //$('#users-table').DataTable().ajax.reload();
                user_member_details_table.draw("full-hold");
            } else {
                $("#flash_message").html("Sorry! ,Membership could not updated successfully");
                $("#flash_message").addClass("alert-danger");
                $("#flash_message").show().delay(5000).fadeOut();
            }

        });

    });

    $(document).on("click", "#log_submit", function(e) { //user click on submit button
        e.preventDefault();

        memship_uid = $("#memship_uid").val();
        log_comments = $("#log_comments").val();
        log_type = $(":checkbox:checked").attr("id");

        if($('#Reminder').is(':checked'))
            if($('#txt-reminder').val() == ''){
                alert('Select a date for the reminder.');
                return false;
            }
            else
                log_reminder = $('#txt-reminder').val();
        else
            log_reminder = '';

        $.ajax({
            type: "POST",
            url: url + "/log/save",
            data: {
                "memship_uid": memship_uid,
                "log_comments": log_comments,
                "log_type": log_type,
                "log_reminder": log_reminder,
            },
        }).done(function(data) {
            $('#logModal').modal('toggle');
            if (data != null) {
                $("#details").append("<br/>" + data.created_at + " - Action " + data.action + " - Outcome : " + data.comments);
            }

        });

    });

    $(document).on("click", "#sales_submit", function(e) { //user click on submit button
        e.preventDefault();

        memship_uid = $("#memship_uid").val();
        sales_type = $("#sales_type").val();
        sales_qty = $("#sales_qty").val();
        sales_value = $("#sales_value").val();
        sales_comments = $("#sales_comments").val();


        $.ajax({
            type: "POST",
            url: url + "/sales/save",
            data: {
                "memship_uid": memship_uid,
                "sales_type": sales_type,
                "sales_qty": sales_qty,
                "sales_value": sales_value,
                "sales_comments": sales_comments,

            },
        }).done(function(data) {
            $('#salesModal').modal('toggle');
            if (data != null) {
                $("#details").append("<br/>" + data.created_at + " - Add-on sales - " + data.action + " - Qty " + data.qty);

            }
        });

    });

});
