$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).on("click", ".btn-edit-memship", function(e) {
        e.preventDefault();
        userId = $(this).attr("id");
        check_leads();
        var placeholderImg = "<img src='https://cutewallpaper.org/21/loading-gif-transparent-background/My-Principal-Lifestyle.gif>"
        $("#views_status").html('Loading....');
        $("#leads_status").html('Loading..');
        $.ajax({
            type: "POST",
            url: url + "/customer/details",
            data: {
                "uid": userId,
            },
        }).done(function (member) {
            let membership_amount = 0.00;
            let membership_pending_amount = 0.00;
            let package_amount = 0.00;
            let pending_amount = 0.00;
            let is_expire = 1;

            if(member.membership_amount != null && member.membership_amount != '') {
                membership_amount = member.membership_amount;
            }
            if(member.membership_pending_amount != null && member.membership_pending_amount != '') {
                membership_pending_amount = member.membership_pending_amount;
            }
            if(member.package_amount != null && member.package_amount != '') {
                package_amount = member.package_amount;
            }
            if(member.pending_amount != null && member.pending_amount != '') {
                pending_amount = member.pending_amount;
            }
            if(member.is_expire != null) {
                is_expire = member.is_expire;
            }

            $("#membership_pending_approval_count").val(member.pending_approval_count);
            check_pending_approval();
            //For new modal member View
            $("#member_name").html(member.firstname + " " + member.surname);
            $("#member_email").html(member.Uemail);
            $("#member_user_id").html(userId);
            $("#billing_uid").val(userId);
            $("#addons_uid").val(userId);
            $("#member_contact").html(member.mobile_nos);
            $("#membership_type_dis").html(member.type);
            $("#user_type_dis").html(member.user_type);
            $("#membership_category_dis").html(member.category);
            $("#member_payment_exp").html(member.payment_exp_date);
            $("#membership_exp").html(member.expiry);
            $("#admin_level").val(member.adminLevel);
            $("#available_boosts").html(member.boosts_left);
            $("#user_source").html(member.source);
            $("#last_boost_date").html(member.last_boost_date);
            if(parseInt(member.adminLevel) < 3) {
                //$("#membership_call_date").prop('readonly', true);
                //$("#membership_call_date_div").html('<input class="form-control" id="membership_call_date" name="membership_call_date" type="text" value="'+member.payment_exp_date+'" readonly/>');
                //$("#membership_expiry_date_div").html('<input class="form-control" id="membership_expiry_date" name="membership_expiry_date" type="text" readonly value="'+member.expiry+'"/>');
                //$("#membership_expiry_date").prop('readonly', true);

                $("#package_amount").prop('readonly', true);
                $("#pending_amount").prop('readonly', true);
                $('#phone_restrictions_yes').attr('disabled','disabled');
                $('#phone_restrictions_no').attr('disabled','disabled');

            }
            if(parseInt(member.adminLevel) == 1 && member.category == 'Single Ad') {
                if (member.am == "System") {
                    $('#membership_am').prop('disabled', true);
                }
            }

            let activity_status = '-';

            if(member.payment_status == null || member.payment_status == '') {
                activity_status = '-';
            } else if (parseInt(member.payment_status) == 1 ) {
                activity_status = 'Follow-up';
            } else if (parseInt(member.payment_status) == 2 ) {
                activity_status = 'Will make payment';
            } else if (parseInt(member.payment_status) == 3 ) {
                activity_status = 'Paid';
            } else if (parseInt(member.payment_status) == 4) {
                activity_status = 'Not Interested - Advertising elsewhere';
            } else if (parseInt(member.payment_status) == 5) {
                activity_status = 'Inactive - Not in business';
            } else if (parseInt(member.payment_status) == 6) {
                activity_status = 'Inactive - Paused business';
            } else if (parseInt(member.payment_status) == 7) {
                activity_status = 'Inactive - No properties or sold';
            } else if (parseInt(member.payment_status) == 8) {
                activity_status = 'Inactive - Pending Payments';
            } else if (parseInt(member.payment_status) == 9) {
                activity_status = 'Not Interested - Low leads';
            }

            $("#activity_status_dis").html(activity_status);

            //$("#member_status").html('Expired');
            if(member.logo_path !=  undefined && member.logo_path != null && member.logo_path != ''){
                $("#member-img").attr("src", member.logo_path);
                //$("#user_image").val(member.logo_path);
            } else {
                $("#member-img").attr("src",'http://placehold.it/380x500');
            }

            if (member.is_phone_restriction ==  undefined || member.is_phone_restriction == null || member.is_phone_restriction == '') {
                if(parseInt(member.user_type) == 2) {
                    $("#phone_restrictions_yes").prop("checked", true);
                    $("#old_phone_restrictions").val(1);
                } else {
                    $("#phone_restrictions_no").prop("checked", true);
                    $("#old_phone_restrictions").val(0);
                }
            } else if(parseInt(member.is_phone_restriction) == 1) {
                $("#phone_restrictions_yes").prop("checked", true);
                $("#old_phone_restrictions").val(member.is_phone_restriction);
            } else {
                $("#phone_restrictions_no").prop("checked", true);
                $("#old_phone_restrictions").val(0);
            }

            //Used membership status & remaining days display
            if(member.status == 1 && member.membership_remaining > 0){
                let viewStatus = '<p>Member(Remaining ' + member.membership_remaining + ' days only)</p>';
                $('#member_status').html(viewStatus);
            } else {
                $('#member_renew').removeClass('hide');
            }

            //For new modal member Edit
            $("#membership_uid").val(userId);
            $("#membership_full_name").val(member.firstname + " " + member.surname);
            $("#membership_email").val(member.Uemail);
            $("#old_membership_contact_num").val(member.mobile_nos);
            $("#membership_contact_num").val(member.mobile_nos);
            $("#old_company_name").val(member.company);
            $("#company_name").val(member.company);
            $("#old_latest_comment").val(member.latest_comment);
            $("#latest_comment").html(member.latest_comment);
            $("#old_linkin_profile").val(member.linkin_id);
            $("#linkin_profile").val(member.linkin_id);
            $("#old_user_image").val(member.logo_path);
            $("#selected_image_data").html(member.logo_path);
            $("#user_image").val("");
            $("#is_active_auto_boost").val(member.is_active_auto_boost);
            $("#auto_boost_for_new_ads").val(member.auto_boost_for_new_ads);
            if(member.is_active_auto_boost=='Y'){
                $("#is_active_auto_boost_yes").prop("checked", true);
            }else{
                $("#is_active_auto_boost_no").prop("checked", true);
            }
            if(member.auto_boost_for_new_ads=='Y'){
                $("#auto_boost_for_new_ads_yes").prop("checked", true);
            }else{
                $("#auto_boost_for_new_ads_no").prop("checked", true);
            }

            //For member details tab
            $("#membership_details_uid").val(userId);
            //For Log Modal
            $("#memship_uid").val(userId);
            if (member.user_type_id != null && member.user_type_id != "") {
                $('[name=user_type]').val(member.user_type_id);
                $("#old_user_type").val(member.user_type_id);
            }
            if (member.type != null && member.type != "") {
                $('[name=membership_type]').val(member.type);
                $("#old_membership_type").val(member.type);
            }
            if (member.category != null && member.category != "") {
                $('[name=membership_category]').val(member.category);
                $("#old_membership_category").val(member.category);
            }
            $("#old_package_amount").val(parseFloat(package_amount).toFixed(2));
            $("#package_amount").val(parseFloat(package_amount).toFixed(2));
            $("#old_pending_amount").val(parseFloat(pending_amount).toFixed(2));
            $("#pending_amount").val(parseFloat(pending_amount).toFixed(2));

            $("#old_membership_payment").val(member.payment);
            $("#membership_payment").val(member.payment);
            if (member.duration != null && member.duration != "") {
                $("#membership_duration").val(member.duration);
                $("#old_membership_duration").val(member.duration);
            }
            // Changed By Sasi Spenzer  as Requested @R - 2021-08-03
            // if (member.call_date_time != null && member.call_date_time != "") {
            //     $("#old_membership_call_date").val(member.call_date_time);
            //     $("#membership_call_date").val(member.call_date_time);
            // } else if(member.category == 'Single Ad' && member.payment_exp_date != null && member.payment_exp_date != "") {
                $("#old_membership_call_date").val(member.payment_exp_date);
                $("#membership_call_date").val(member.payment_exp_date);
            //}
            if (member.expiry != null && member.expiry != "") {
                $("#old_membership_expiry_date").val(member.expiry);
                $("#membership_expiry_date").val(member.expiry);
            }
            /*if (member.active_ads != null && member.active_ads != "") {
                $("#old_membership_active_add_count").val(member.active_ads);
                $("#membership_active_add_count").val(member.active_ads);
            }
            if (member.leads != null && member.leads != "") {
                $("#old_membership_leads_count").val(member.leads);
                $("#membership_leads_count").val(member.leads);
            }*/
            if (member.am != null && member.am != "") {
                //if(!change_am)
                $("#old_membership_am").val(member.am);
                $('[name=membership_am]').val(member.am);
            }

            //For stats tab data

            $("#member_since").html(member.member_since);
            $("#boosts_left").html(member.boosts_left);
            $("#no_of_ads").html(member.ads_count);
            $("#no_of_upgraded_ads").html(member.ad_upgrade_count);
            $("#highest_page_id").html(member.max_page_id);

            var mpID = parseInt(member.max_page_id);
            var viewss = getRange(mpID);
//
//
            $("#missing_leads_percentage").html("You Lost <font color='orange'> " + viewss + " </font> of Leads");
            $("#percentage_of_ads_with_images").html(member.pic_percentage);
            check_leads(); // Calling New Check Leads Function BY Spenzer 2021-08-24
            if((member.views_percentage || 0.00) > 0){
                $("#views_status").html(parseInt(member.ad_hits) + ' <img src="'+ url + '/uploads/up-arrow-1.png"> (' + (member.views_percentage || 0.00)+ '%)');
            } else if((member.views_percentage || 0.00) < 0){
                $("#views_status").html(member.ad_hits + ' <img src="'+ url + '/uploads/down-arrow-1.png"> (' + (member.views_percentage || 0.00) + '%)');
            } else {
                let ad_hits = 0;
                let views_percentage = '0%';

                $("#views_status").html(ad_hits + ' <img src="'+ url + '/uploads/trans-equal-1.png"> (' + views_percentage + '%)');
            }
            if(member.ad_hits != null){
                ad_hits = member.ad_hits;
                $("#hiddenAdHits").val(ad_hits);
                $("#hiddenAdLeads").val(member.total_stats);
            }
            if((member.leads_percentage || 0.00) > 0){
                $("#leads_status").html(member.total_stats + ' <img src="'+ url + '/uploads/up-arrow-1.png"> (' + (member.leads_percentage || 0.00) + '%)');
            } else if((member.leads_percentage || 0.00) < 0){
                $("#leads_status").html(member.total_stats + ' <img src="'+ url + '/uploads/down-arrow-1.png"> (' + (member.leads_percentage || 0.00) + '%)');
            } else {
                let total_stats = 0;
                let leads_percentage = '0';
                if(member.total_stats != null){
                    total_stats = member.total_stats;
                }
                $("#leads_status").html(total_stats + ' <img src="'+ url + '/uploads/trans-equal-1.png"> (' + leads_percentage + '%)');
            }
            /*if(member.status == '1') {
                $("#views_status").html(parseInt(member.ad_hits) + ' <img src="'+ url + '/uploads/up-arrow-1.png"> (' + member.views_percentage + '%)');
                $("#leads_status").html(member.total_stats + ' <img src="'+ url + '/uploads/up-arrow-1.png"> (' + member.leads_percentage + '%)');
            }  else if(member.status == '2') {
                $("#views_status").html(member.ad_hits + ' <img src="'+ url + '/uploads/down-arrow-1.png"> (' + member.views_percentage + '%)');
                $("#leads_status").html(member.total_stats + ' <img src="'+ url + '/uploads/down-arrow-1.png"> (' + member.leads_percentage + '%)');
            }  else {
                let ad_hits = 0;
                let views_percentage = '0%';
                let total_stats = 0;
                let leads_percentage = '0%';
                if(member.ad_hits != null){
                    ad_hits = member.ad_hits;
                }
                if(member.views_percentage != null){
                    views_percentage = member.ad_hits;
                }
                if(member.ad_hits != null){
                    ad_hits = member.ad_hits;
                }
                if(member.total_stats != null){
                    ad_hits = member.ad_hits;
                }
                if(member.leads_percentage != null){
                    views_percentage = member.leads_percentage;
                }
                if(member.ad_hits != null){
                    ad_hits = member.ad_hits;
                }
                $("#views_status").html(ad_hits + ' <img src="'+ url + '/uploads/trans-equal-1.png"> (' + views_percentage + '%)');
                $("#leads_status").html(total_stats + ' <img src="'+ url + '/uploads/trans-equal-1.png"> (' + leads_percentage + '%)');
            }*/

            //For Billings tab
            $("#pay_uid").val(userId);
            $("#pay_membership_type").val(member.payment_membership_type);
            $("#pay_duration").val(member.payment_membership_duration);
            $("#pay_membership_exp_date").val(member.payment_expire_data);
            $("#pay_payment_exp_date").val(member.membership_expire_data);
            $("#pay_membership_amount").val(parseFloat(membership_amount).toFixed(2));
            //$("#pay_payment_amount").val(parseFloat(member.package_amount).toFixed(2));
            $("#pay_pending_amount").val(parseFloat(membership_pending_amount).toFixed(2));

            let ran_string1 = makeid(50);
            let ran_string2 = makeid(50);
            let url_pre = 'https://www.lankapropertyweb.com/';
            let payment_url =  url_pre + 'myaccount/cusomer-public-payment.php?payload='+ran_string1+'-'+userId+'-'+ran_string2;
            let pay_payment_total = parseFloat(membership_pending_amount) + parseFloat(membership_amount);
            $("#pay_payment_total").val(parseFloat(pay_payment_total).toFixed(2));
            if(is_expire == 0){
                $("#pay_url").val(payment_url);
                $("#pay_copy").prop('disabled', false);
            } else {
                $("#pay_url").val("Haven't any pending payments");
                $("#pay_copy").prop('disabled', true);
            }


            //Set activity data
            getActivityData(userId);
            //Set billing data
            get_billings_data();
            //Set addons data
            get_addons_data();
        });
    });



    //For generate random string
    function makeid(length) {
        var result           = '';
        var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for ( var i = 0; i < length; i++ ) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }
    //if(parseInt($("#admin_level").val()) > 2){
        //Not yet use time picker
        $("#membership_call_date").datetimepicker({
            showOn: "button",
            showSecond: false,
            minView: 2,
            format: "yyyy-mm-dd",
            //dateFormat: "yy-mm-dd",
            showTimepicker: false,
            autoclose: true,
            todayBtn: true,
        });
        $("#membership_expiry_date").datetimepicker({
            showOn: "button",
            showSecond: false,
            minView: 2,
            format: "yyyy-mm-dd",
            //dateFormat: "yy-mm-dd",
            showTimepicker: false,
            autoclose: true,
            todayBtn: true,
        });
    //}

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

    $('#logModal').on('hide.bs.modal', function () {
        if (log_form_reset) {
            $("#log_form").trigger("reset");
            $('#div-datetimepicker').addClass('hidden');
        }
        $('#membershipModal').css('overflow-y', 'auto');
        view_activity();
    });
    $(document).on("click", "#member_ad_view", function(e) { //user click on submit button
        e.preventDefault();
        let membership_uid = $("#memship_uid").val();
        window.location.href = url + "/view/list/ads/by/customer?uid=" + membership_uid;
        // // alert(membership_uid);
        // $.ajax({
        //     type: "GET",
        //     url: url + "/get/ads/by/customer/email",
        //     data: {
        //         "uid": membership_uid,
        //     },
        // }).done(function (member) {
        //     console.log(member.user_email);
        //    window.location.href = url + "/view/list/ads/by/customer?uid="membership_uid;
    });

    var lastExecution = 0;
    var lastExecution = "test";
    $(document).on("click", "#log_submit", function(e) { //user click on submit button
        e.preventDefault();
        let memship_uid = $("#memship_uid").val();
        let log_comments = $("#log_comments").val();
        let log_type = $("input[name='log_type']:checked"). val();
        let log_reminder = '';
        let membership_status = $("#membership_status").val();

        if (log_type == '' || log_type == undefined) {
            alert('Please select log type.');
            return false;
        }
        if (membership_status == '' || membership_status == undefined) {
            alert('Please Select Status type');
            return false;
        }
        else if (log_comments == '' || log_comments == undefined) {
            alert('Please enter your comment.');
            return false;
        }

        if($('#Reminder').is(':checked')) {
            if ($('#txt-reminder').val() == '') {
                alert('Select a date for the reminder.');
                return false;
            } else {
                log_reminder = $('#txt-reminder').val();
            }
        } else if($('#Meeting').is(':checked')) {
            if ($('#txt-reminder').val() == '') {
                alert('Select a date for the meeting.');
                return false;
            } else {
                log_reminder = $('#txt-reminder').val();
            }
        }
        else {
            log_reminder = '';
        }

        //var MemLocalID = localStorage.getItem("memberid");
        
            $.ajax({
                type: "POST",
                url: url + "/membership/log/save",
                data: {
                    "membership_uid": memship_uid,
                    "log_comments": log_comments,
                    "log_type": log_type,
                    "log_reminder": log_reminder,
                    "membership_status": membership_status
                },
            }).done(function (data) {

                $('#membershipLogModal').modal('toggle');
                $('body').css('overflow', 'hidden');
                $('#membershipModal').css('overflow-y', 'auto');
                $("#latest_comment").html(log_comments);
                getActivityData(memship_uid);
                let activity_status = '-';
                if (parseInt(membership_status) == 1) {
                    activity_status = 'Follow-up';
                } else if (parseInt(membership_status) == 2) {
                    activity_status = 'Will make payment';
                } else if (parseInt(membership_status) == 3) {
                    activity_status = 'Paid';
                } else if (parseInt(membership_status) == 4) {
                    activity_status = 'Not Interested - Advertising elsewhere';
                } else if (parseInt(membership_status) == 5) {
                    activity_status = 'Inactive - Not in business';
                } else if (parseInt(membership_status) == 6) {
                    activity_status = 'Inactive - Paused business';
                } else if (parseInt(membership_status) == 7) {
                    activity_status = 'Inactive - No properties or sold';
                } else if (parseInt(membership_status) == 8) {
                    activity_status = 'Inactive - Pending Payments';
                } else if (parseInt(membership_status) == 9) {
                    activity_status = 'Not Interested - Low leads';
                }

                //localStorage.setItem("memberid", memship_uid);
                $("#activity_status_dis").html(activity_status);
                view_activity();

                // if (data != null) {
                //     $("#details").append("<br/>" + data.created_at + " - Action " + data.action + " - Outcome : " + data.comments);
                // }

            });


    });
    $("input[name='log_type']").click(function(){
        if($('#Reminder').is(':checked') || $('#Meeting').is(':checked')){
            $('#div-datetimepicker').removeClass('hidden');
        }
        else{
            $('#txt-reminder').val('');
            $('#div-datetimepicker').addClass('hidden');
        }
    });

    $("#membership_category,#membership_payment, #membership_duration, #membership_payment").change(function () {

        let user_package = $("#membership_category").val();
        let membership_payment = $("#membership_payment").val();
        let membership_duration = $("#membership_duration").val();
        let payment_typepp = $("#membership_payment").children("option:selected").val();
        let duration = 1;
        let _token = $("#csrf-token").val();
        if(membership_payment == 'Free Trial') {
            let total_amount = 0;
            $("#package_amount").val(total_amount.toFixed(2));
        } else {
            if(membership_duration == "1 month") {
                duration = 1;
            } else if(membership_duration == "2 months") {
                duration = 2;
            } else if(membership_duration == "3 months") {
                duration = 3;
            } else if(membership_duration == "4 months") {
                duration = 4;
            } else if(membership_duration == "5 months") {
                duration = 5;
            } else if(membership_duration == "6 months") {
                duration = 6;
            } else if(membership_duration == "7 months") {
                duration = 7;
            } else if(membership_duration == "8 months") {
                duration = 8;
            } else if(membership_duration == "9 months") {
                duration = 9;
            } else if(membership_duration == "10 months") {
                duration = 10;
            } else if(membership_duration == "11 months") {
                duration = 11;
            } else if(membership_duration == "1 year") {
                duration = 12;
            } else {
                duration = 1;
            }

            $.ajax({
                type:'GET',
                url: url + '/get-package-price',
                data:{'user_package' : user_package,'payment_type':payment_typepp,'duration':duration,'_token': _token},
                contentType: 'json',
            }).done(function(data) {
                let amount = parseFloat(data.ad_rates);
                let duration_date = data.duration_date;
                let total_amount = amount * duration;
                $("#package_amount").val(total_amount.toFixed(2));
                //console.log(data);
                $("#membership_call_date").val(duration_date);
                $("#membership_expiry_date").val(duration_date);
            });
        }


    });

    $('input[type=radio][name=is_paid]').change(function() {
        alert("value : " + this.value );
    });
    //
    $(document).on("click", "#pay_copy", function(e) { //user click on submit button
        var copyText = document.getElementById("pay_url");

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /*For mobile devices*/

        /* Copy the text inside the text field */
        document.execCommand("copy");

        /* Alert the copied text */
        alert("Copied the text: " + copyText.value);
    });
    //For click member renew button in summary tab
    $(document).on("click", "#member_renew", function(e) {
        e.preventDefault();
        view_payments();
    });
    //For click generate url button in details tab
    $(document).on("click", "#member_generate_url", function(e) {
        e.preventDefault();
        $("#member_details_save").attr('disabled', true);
        $("#member_generate_url").attr('disabled', true);
        let uid = $("#membership_details_uid").val();
        let old_user_type = $("#old_user_type").val();
        let user_type = $("#user_type").val();
        let old_membership_type = $("#old_membership_type").val();
        let membership_type = $("#membership_type").val();
        let old_membership_category = $("#old_membership_category").val();
        let membership_category = $("#membership_category").val();
        let old_package_amount = $("#old_package_amount").val();
        let package_amount = $("#package_amount").val();
        let old_membership_payment = $("#old_membership_payment").val();
        let membership_payment = $("#membership_payment").val();
        let membership_exp_date = $("#membership_expiry_date").val();
        let payment_exp_date = $("#membership_call_date").val();
        let old_membership_duration = $("#old_membership_duration").val();
        let membership_duration = $("#membership_duration").val();
        let old_membership_call_date = $("#old_membership_call_date").val();
        let membership_call_date = $("#membership_call_date").val();
        let old_membership_expiry_date = $("#old_membership_expiry_date").val();
        let membership_expiry_date = $("#membership_expiry_date").val();
        let old_membership_active_add_count = $("#old_membership_active_add_count").val();
        let membership_active_add_count = $("#membership_active_add_count").val();
        let old_membership_leads_count = $("#old_membership_leads_count").val();
        let membership_leads_count = $("#membership_leads_count").val();
        let old_membership_am = $("#old_membership_am").val();
        let membership_am = $("#membership_am").val();
        let old_pending_amount = $("#old_pending_amount").val();
        let pending_amount = $("#pending_amount").val();
        let membership_comment = $("#membership_comment").val();
        let payment_total = parseFloat(package_amount) + parseFloat(pending_amount);
        let old_phone_restrictions = $("#old_phone_restrictions").val();
        let new_phone_restrictions = $("input[name='phone_restrictions']:checked"). val()
        $.ajax({
            type: "GET",
            url: url + "/membership/pending/approval/save",
            //dataType: 'json',
            //headers: {'X-CSRF-Token': token},
            //data: 'fromDate='+from_date+'&toDate='+to_date+'&adults='+adults+'&kids='+kids+'&ageString='+age_string,
            data: {
                "membership_uid": uid,
                "old_user_type": old_user_type,
                "user_type": user_type,
                "old_membership_type": old_membership_type,
                "membership_type": membership_type,
                "old_membership_category": old_membership_category,
                "membership_category": membership_category,
                "old_package_amount": old_package_amount,
                "package_amount": package_amount,
                "old_membership_payment": old_membership_payment,
                "membership_payment": membership_payment,
                "old_membership_duration": old_membership_duration,
                "membership_duration": membership_duration,
                "old_membership_call_date": old_membership_call_date,
                "membership_call_date": membership_call_date,
                "old_membership_expiry_date": old_membership_expiry_date,
                "membership_expiry_date": membership_expiry_date,
                "old_membership_active_add_count": old_membership_active_add_count,
                "membership_active_add_count": membership_active_add_count,
                "old_membership_leads_count": old_membership_leads_count,
                "membership_leads_count": membership_leads_count,
                "old_membership_am": old_membership_am,
                "membership_am": membership_am,
                "old_pending_amount": old_pending_amount,
                "pending_amount": pending_amount,
                "membership_comment": membership_comment,
                "old_phone_restrictions": old_phone_restrictions,
                "new_phone_restrictions": new_phone_restrictions
            },
        }).done(function(data) {
            let output = JSON.parse(data);

            if(output.status == 'Succeed'){
                $("#alert-block").addClass('alert-success');
                $("#alert-block-data").html("Url generate successfully");
                $("#pay_membership_type").val(membership_type);
                $("#pay_duration").val(membership_duration);
                $("#pay_membership_exp_date").val(membership_exp_date);
                $("#pay_payment_exp_date").val(payment_exp_date);
                $("#pay_membership_amount").val( parseFloat(package_amount).toFixed(2));
                //$("#pay_payment_amount").val(parseFloat(member.package_amount).toFixed(2));
                $("#pay_pending_amount").val( parseFloat(pending_amount).toFixed(2));
                $("#pay_payment_total").val(payment_total.toFixed(2));
                // Generate url
                let ran_string1 = makeid(50);
                let ran_string2 = makeid(50);
                let url_pre = 'https://www.lankapropertyweb.com/';
                let payment_url =  url_pre + 'myaccount/cusomer-public-payment.php?payload='+ran_string1+'-'+uid+'-'+ran_string2;
                $("#pay_url").val(payment_url);
                $("#pay_copy").prop('disabled', false);
                getActivityData(uid);
                view_payments();
            } else {
                $("#alert-block").addClass('alert-danger');
                $("#alert-block-data").html("Url generate with issue");
            }
            $("#member_details_save").attr('disabled', false);
            $("#member_generate_url").attr('disabled', false);
            $("#alert-block").removeClass('hide');
        });

    });
});

function edit_user_data() {

    $("#user_view").addClass("hide");
    $("#user-image").addClass("hide");
    $("#user_manage").removeClass("hide");


}
$('#membershipFormData').on('submit', function(event){
    event.preventDefault();
    $("#member_save").attr('disabled', true);
     let membership_uid = $("#membership_uid").val();
     let input_data = new FormData(this);
     input_data.append('membership_uid', membership_uid);

//     let membership_full_name = $("#membership_full_name").val();
    let membership_contact = $("#membership_contact_num").val();
    let membership_email = $("#membership_email").val();
    let user_type = $("#user_type").val();
    let user_type_view = "";
    if(user_type == '1'){
        user_type_view = "Property Agent";
    } else if(user_type == '2'){
        user_type_view = "PAA Agent";
    } else if(user_type == '3'){
        user_type_view = "Pvt Seller";
    } else if(user_type == '4'){
        user_type_view = "Ideal Home";
    } else if(user_type == '5'){
        user_type_view = "Developer";
    } else if(user_type == '6'){
        user_type_view = "Internal";
    } else {
        user_type_view = "Other";
    }
    let membership_type_select = $("#membership_type_select").val();
    let membership_exp_date = $("#membership_exp_date").val();
    let payment_exp_date = $("#payment_exp_date").val();
    let duration = $("#payment_exp_date").val();
    let amount = parseFloat($("#package_amount").val()).toFixed(2);
    let pending_amount = parseFloat($("#pending_amount").val()).toFixed(2);
    let payment_total = amount + pending_amount;
    //let user_image = $("#user_image").prop('files')[0];
//     console.log($('input[type=file]').val());
//     let user_image = $("#user_image").val();
    // File type validation
    $('input[type=file]').change(function() {
        var file = this.files[0];
        var fileType = file.type;
        var match = ['image/jpeg', 'image/png', 'image/jpg'];
        if(!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) )){
            alert('Sorry, only JPG, JPEG, & PNG files are allowed to upload.');
            $("#user_image").val('');
            return false;
        }
    });
    $.ajax({
        url:url + "/membership/data/save",
        method:"POST",
        data:input_data,
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
    }).done(function(success) {
        if(success.admin_level > 2 ){
            $("#member_email").html(membership_email);
            $("#member_contact").html(membership_contact);
            $("#user_type_dis").html(user_type_view);
            $("#membership_type").html(membership_type_select);
            $("#member_payment_exp").html(payment_exp_date);
            $("#membership_exp").html(membership_exp_date);
            if(success.member_img !=  undefined && success.member_img != null && success.member_img != ''){
                $("#member-img").attr("src", success.member_img);
            } else {
                $("#member-img").attr("src", 'http://placehold.it/380x500');
            }
        }
        //For payment tab
        $("#pay_membership_type").val(membership_type_select);
        $("#pay_pending_amount").val(pending_amount);
        $("#pay_membership_amount").val(amount);
        $("#pay_duration").val(duration);
        $("#pay_membership_exp_date").val(membership_exp_date);
        $("#pay_payment_exp_date").val(payment_exp_date);
        $("#pay_payment_total").val(payment_total);

        $("#user_view").removeClass("hide");
        $("#user-image").removeClass("hide");
        $("#user_manage").addClass("hide");
        if(success.is_view_msg == 1){
            $("#alert-block-data").html(success.description);
            $("#alert-block").removeClass('hide');
            if(success.status == "Succeed"){
                $("#alert-block").addClass('alert-success');
            } else {
                $("#alert-block").addClass('alert-danger');
            }


        }
        getActivityData(membership_uid);
        view_summary();
        $("#tabs").tabs({ active: 'home' } );
        $("#member_save").attr('disabled', false);
    });
});

$('#membershipDetailsForm').on('submit', function(event){
    event.preventDefault();
    $("#member_details_save").attr('disabled', true);
    $("#member_generate_url").attr('disabled', true);
    let membership_details_uid = $("#membership_details_uid").val();
    let input_data = new FormData(this);
    input_data.append('membership_uid', membership_details_uid);
    let user_type = $("#user_type").val();
    let uid = $("#membership_details_uid").val();
    let user_type_view = "";
    if(user_type == '1'){
        user_type_view = "Property Agent";
    } else if(user_type == '2'){
        user_type_view = "PAA Agent";
    } else if(user_type == '3'){
        user_type_view = "Pvt Seller";
    } else if(user_type == '4'){
        user_type_view = "Ideal Home";
    } else if(user_type == '5'){
        user_type_view = "Developer";
    } else if(user_type == '6'){
        user_type_view = "Internal";
    } else {
        user_type_view = "Other";
    }
    let membership_type = $("#membership_type").val();
    let membership_category = $("#membership_category").val();
    let membership_exp_date = $("#membership_expiry_date").val();
    let payment_exp_date = $("#membership_call_date").val();
    let duration = $("#membership_duration").val();
    let amount = parseFloat($("#package_amount").val());
    let pending_amount = parseFloat($("#pending_amount").val());
    let payment_total = amount + pending_amount;
    $.ajax({
        url:url + "/membership/details/save",
        method:"POST",
        data:input_data,
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
    }).done(function(success) {
        if (success.admin_level > 2) {
            $("#user_type_dis").html(user_type_view);
            $("#membership_type_dis").html(membership_type);
            $("#membership_category_dis").html(membership_category);
            $("#member_payment_exp").html(payment_exp_date);
            $("#membership_exp").html(membership_exp_date);

        }
        //For payment tab
        $("#pay_membership_type").val(user_type_view);
        $("#pay_pending_amount").val(pending_amount.toFixed(2));
        $("#pay_membership_amount").val(amount.toFixed(2));
        $("#pay_duration").val(duration);
        $("#pay_membership_exp_date").val(membership_exp_date);
        $("#pay_payment_exp_date").val(payment_exp_date);
        $("#pay_payment_total").val(payment_total.toFixed(2));
        if(success.is_view_msg == 1){
            $("#alert-block-data").html(success.description);
            $("#alert-block").removeClass('hide');
            if(success.status == "Succeed"){
                $("#alert-block").addClass('alert-success');
            } else {
                $("#alert-block").addClass('alert-success');
            }


        }
        getActivityData(membership_details_uid);

        function makeid(length) {
            var result           = '';
            var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for ( var i = 0; i < length; i++ ) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        }
        if (success.admin_level < 3) {
            // Generate url
            let ran_string1 = makeid(50);
            let ran_string2 = makeid(50);
            let url_pre = 'https://www.lankapropertyweb.com/';
            let payment_url = url_pre + 'myaccount/cusomer-public-payment.php?payload=' + ran_string1 + '-' + uid + '-' + ran_string2;
            $("#pay_url").val(payment_url);
            $("#pay_copy").prop('disabled', false);
        } else {
            $("#pay_url").val("Haven't any pending payments");
            $("#pay_copy").prop('disabled', true);
        }
        //view_summary();
        //$("#payment_tab").removeClass('hidden');
        view_payments();
        $("#member_details_save").attr('disabled', false);
        $("#member_generate_url").attr('disabled', false);
    });
});

function view_summary() {
    $("#user_view").removeClass("hide");
    $("#details").addClass("hide");
    $("#user-image").removeClass("hide");
    $("#user_manage").addClass("hide");
    $("#user-log").addClass("hide");
    $("#home").removeClass("hide");
    $("#profile").addClass("hide");
    $("#stats").addClass("hide");
    $("#payments").addClass("hide");
    $("#billings").addClass("hide");
    $("#addons").addClass("hide");
    $('.nav-tabs a[href="#home"]').tab('show');
    check_pending_approval();
}

function view_profile() {
    $("#user_view").addClass("hide");
    $("#details").addClass("hide");
    $("#user-image").addClass("hide");
    $("#user-log").addClass("hide");
    $("#user_manage").removeClass("hide");
    $("#home").addClass("hide");
    $("#profile").removeClass("hide");
    $("#stats").addClass("hide");
    $("#payments").addClass("hide");
    $("#billings").addClass("hide");
    $("#addons").addClass("hide");
    $("#user_image").val('');
    $('.nav-tabs a[href="#profile"]').tab('show');
    check_pending_approval();
}

function view_stats() {

    $("#user_view").addClass("hide");
    $("#details").addClass("hide");
    $("#user-image").addClass("hide");
    $("#user-log").addClass("hide");
    $("#user_manage").addClass("hide");
    $("#home").addClass("hide");
    $("#profile").addClass("hide");
    $("#stats").removeClass("hide");
    $("#payments").addClass("hide");
    $("#billings").addClass("hide");
    $("#addons").addClass("hide");
    $("#user_image").val('');
    $('.nav-tabs a[href="#stats"]').tab('show');
    check_pending_approval();
    check_leads(); // Calling New Check Leads Function BY Spenzer 2021-04-28

}

function view_activity() {
    $("#user_view").addClass("hide");
    $("#details").addClass("hide");
    $("#user-image").addClass("hide");
    $("#user_manage").addClass("hide");
    $("#user-log").removeClass("hide");
    $("#home").addClass("hide");
    $("#profile").addClass("hide");
    $("#activity").removeClass("hide");
    $("#stats").addClass("hide");
    $("#payments").addClass("hide");
    $("#billings").addClass("hide");
    $('.nav-tabs a[href="#activity"]').tab('show');
    $("#addons").addClass("hide");
    check_pending_approval();
}

 function closeMembershipModal(){
     $('#membershipModal').modal('toggle');
     $('body').css('overflow', 'auto');
 }
function closeMembershipModalUncon(){
    $('#membershipModalunfollow').modal('toggle');
    $('body').css('overflow', 'auto');
}
function closeMembershipModalfollow(){
    $('#membershipModalfollowup').modal('toggle');
    $('body').css('overflow', 'auto');
}
function closeMembershipModaltleft(){
    $('#membershipModaltwoleft').modal('toggle');
    $('body').css('overflow', 'auto');
}

 function getActivityData(userId){
     $.ajax({
         type: "GET",
         url: url + "/customer/activity/data",
         data: {
             "uid": userId,
         },
     }).done(function (activity) {
         let activity_view = "";
         if(activity.status == "Succeed") {
             //$("#activity_status_dis").html(activity.latest_data);
             $.each(activity.data, function( index, value ) {
                 // alert( index + ": " + value.changer_name );
                 let type_icon = "fa-check";
                 if(value.action == "Profile Approval"){
                     type_icon = "fa-lock";
                 } else if(value.action == "Reminder") {
                     type_icon = "fa-bell";
                 } else if(value.action == "Payment") {
                     type_icon = "fa-credit-card";
                 } else if(value.action == "Payment Complete") {
                     type_icon = "fa-money";
                 } else if(value.action == "Profile Manage") {
                     type_icon = "fa-user";
                 }else if(value.action == "Note") {
                     type_icon = "fa-file";
                 }else if(value.action == "Email") {
                     type_icon = "fa-envelope";
                 }else if(value.action == "Call") {
                     type_icon = "fa-phone";
                 }else if(value.action == "Meeting") {
                     type_icon = "fa-handshake-o";
                 }else if(value.action == "Details Manage") {
                     type_icon = "fa-info-circle";
                 }else if(value.action == "Details Approval") {
                     type_icon = "fa-info";
                 } else if(value.action == "Payment Completed") {
                     type_icon = "fa-money";
                 } else if(value.action == "Payment completed via payment link") {
                     type_icon = "fa-money";
                 } else {
                     type_icon = "fa-info";
                 }

                 if((value.payment_status == '') || (value.payment_status == 'NULL')){
                     value.payment_status = 'Success'
                 }
                 if(!value.payment_status){
                     value.payment_status = 'Success'
                 }
                 if((index % 2) == 0) {
                     activity_view += '<li>'+
                         '<div class="timeline-badge"><i class="fa ' + type_icon + '"></i></div>'+
                         '<div class="timeline-panel">'+
                         '<div class="timeline-heading">'+
                         '<h4 class="timeline-title">' + value.action + '</h4>'+
                         '<p><small class="text-muted"> Status - ' + value.payment_status + ' </small></p>'+
                         '<p><small class="text-muted"><i class="fa fa-clock-o"></i> ' + value.date_time + ' </small></p>'+
                         '</div>'+
                         '<div class="timeline-body">'+
                         '<p>' + value.description + '</p>'+
                         '</div>'+
                         '</div>'+
                         '</li>';
                 } else {
                     activity_view += '<li class="timeline-inverted">' +
                                            '<div class="timeline-badge warning"><i class="fa ' + type_icon + '"></i></div>' +
                                            '<div class="timeline-panel">' +
                                                '<div class="timeline-heading">' +
                                                    '<h4 class="timeline-title">' + value.action + '</h4>' +
                                                    '<p><small class="text-muted"> Status - ' + value.payment_status + ' </small></p>'+
                                                    '<p><small class="text-muted"><i class="fa fa-clock-o"></i> ' + value.date_time + ' </small></p>'+
                                                '</div>'+
                                                '<div class="timeline-body">'+
                                                    '<p>' + value.description + '</p>'+
                                                '</div>'+
                                            '</div>'+
                                        '</li>';
                 }


             });
             $("#timeline").html(activity_view);
         }
     });
 }

 function view_details(){
     $("#user_view").addClass("hide");
     $("#user-image").addClass("hide");
     $("#user-log").addClass("hide");
     $("#user_manage").removeClass("hide");
     $("#home").addClass("hide");
     $("#profile").addClass("hide");
     $("#details").removeClass("hide");
     $("#stats").addClass("hide");
     $("#payments").addClass("hide");
     $("#billings").addClass("hide");
     $("#addons").addClass("hide");
     $('.nav-tabs a[href="#details"]').tab('show');
     check_pending_approval();
 }

 function view_payments(){
     $("#user-image").addClass("hide");
     $("#user_view").addClass("hide");
     $("#user-log").addClass("hide");
     $("#home").addClass("hide");
     $("#profile").addClass("hide");
     $("#details").addClass("hide");
     $("#stats").addClass("hide");
     $("#payments").removeClass("hide");
     $("#billings").addClass("hide");
     $("#billings").removeClass("hide");
     $("#addons").addClass("hide");
     $('.nav-tabs a[href="#payments"]').tab('show');
     check_pending_approval();

 }

 function check_pending_approval(){
     if(parseInt($("#membership_pending_approval_count").val()) > 0) {
         $("#approval-alert-block").removeClass('hide').addClass('alert-danger');
         //$("#approval-alert-block").addClass('alert-danger');
         $("#approval-alert-block-data").html('This user has pending approval request');
     } else {
         close_approval_alert();
     }
 }

 // By Sasi Spenzer 2021-04-28 *** WFH
 function check_leads(){

    // Checking the leads according to type and area
     var user_id = $("#addons_uid").val();
     $.ajaxSetup({
         headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
     });
     $.ajax({
         url:url + "/leads/process",
         method:"POST",
         dataType: "json",
         data: {
             uid: user_id
         },
     }).done(function(success) {
            var views = success.views;
            var leads = success.leads;
            var totalViewsperuser  = $("#hiddenAdHits").val();
            var totalLeadsperuser  = $("#hiddenAdLeads").val();

            totalLeadsperuser = parseInt(totalLeadsperuser);
            totalViewsperuser = parseInt(totalViewsperuser);


            if((views != 0) && (totalViewsperuser !=0)){
                var Viewpercentage = (totalViewsperuser/views) * 100 ;
                views = numberWithCommas(views);
            } else {
                var Viewpercentage = 0 ;

            }



            if((leads != 0) && (totalLeadsperuser !=0)){
                var Leadpercentage = (totalLeadsperuser/leads) * 100 ;
                leads = numberWithCommas(leads);
            } else {
                var Leadpercentage = 0 ;

            }


            var viewText = "<b>Total Views <font color='orange'>"+views+"</font></b>";
            var viewPercentageText = "(<b>You Got "+Viewpercentage.toFixed(2)+"%</b>)";
            $("#total_views_display").html(viewText);
            $("#total_views_percentage").html(viewPercentageText);


            var LeadPercentageText = "<b>Total Leads <font color='orange'>"+leads+"</font></b>";
            var leadPercentageText = "(<b>You Got "+Leadpercentage.toFixed(2)+"%</b>)";
            $("#total_leads_display").html(LeadPercentageText);
            $("#total_leads_percentage").html(leadPercentageText);

     });


 }

 function close_approval_alert(){
     $("#approval-alert-block").addClass('hide').removeClass('alert-danger');
 }

function numberWithCommas(x) {
    if (x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    } else {
        return true;
    }
}

 function close_google_data_alert(){
     $("#google-sheet-alert-block").addClass('hide').removeClass('alert-danger');
 }

 function close_alert(){
     $("#alert-block").addClass('hide').removeClass('alert-danger');
 }
log_form_reset = true; //bacause of modal close when the datetimepicker was changed

function view_billings() {
    $("#user_view").addClass("hide");
    $("#details").addClass("hide");
    $("#user-image").addClass("hide");
    $("#user_manage").addClass("hide");
    $("#user-log").addClass("hide");
    $("#home").addClass("hide");
    $("#profile").addClass("hide");
    $("#activity").addClass("hide");
    $("#stats").addClass("hide");
    $("#payments").addClass("hide");
    $("#billings").removeClass("hide");
    $("#addons").addClass("hide");
    $('.nav-tabs a[href="#billings"]').tab('show');
    get_billings_data();
}

function get_billings_data() {
    let billing_uid = $("#billing_uid").val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //$("#member-billings-table").remove();
    let table = $('#member-billings-table').DataTable({
        processing: true,
        destroy: true,
        serverSide: true,
        responsive: true,
        autoWidth: true,
        "searching": false,
        "order": [[1, "desc"]],
        "paging": true,
        "lengthMenu": [50, 100],
        pagingType: "simple_numbers",
        "ajax": {
            "url": url + "/member/billing/data",
            data: {
                "billing_uid": billing_uid,
            },
            "type": "GET",

        },
        "createdRow": function (row, data, dataIndex) {
            $(row).addClass(data.class);
        },
        columns: [{
            data: 'invoice_number',
            name: 'invoice_number',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'invoiced_date',
            name: 'invoiced_date',
            /*width: "20%",*/
            "searchable": false,
            "orderable": true,
        }, {
            data: 'invoiced_amount',
            name: 'invoiced_amount',
            /*width: "20%",*/
            "searchable": false,
            "orderable": true,
        }, {
            data: 'paid_amount',
            name: 'paid_amount',
            /*width: "20%",*/
            "searchable": false,
            "orderable": true,
        }, {
            data: 'pending_amount',
            name: 'pending_amount',
            /*width: "20%",*/
            "searchable": false,
            "orderable": true,
        }, {
            data: 'product_type',
            name: 'product_type',
            /*width: "20%",*/
            "searchable": false,
            "orderable": true,
        }, {
            data: 'payment_method',
            name: 'payment_method',
            /*width: "20%",*/
            "searchable": false,
            "orderable": true,
        }, {
            data: null,
            className: "center",
            "searchable": false,
            "orderable": false,
            render: function (data, type, row) {
                //return data.total_stats + data.status_img + ' (' + data.leads_percentage + '%)';
                if (data.status == '1') {
                    return '<button type="button" readonly="" class="btn btn-success" style="border-radius:10px;">Paid</button>';
                } else if (data.status == '0') {
                    return '<button type="button" readonly="" class="btn btn-danger" style="border-radius:10px;">Not Paid</button>';
                } else if (data.status == '2') {
                    return '<button type="button" readonly="" class="btn btn-info" style="border-radius:10px;">Partially Paid</button>';
                } else {
                    return '-';
                }
            }
        }, {
            data: 'due_date',
            name: 'due_date',
            /*width: "20%",*/
            "searchable": false,
            "orderable": true,
        }, {
            data: 'paid_date',
            name: 'paid_date',
            /*width: "20%",*/
            "searchable": false,
            "orderable": true,
        }]
    })
    //table.rows().invalidate().draw();
    table.columns.draw();
    //check_pending_approval();
}

$( "#billingDataForm" ).on('submit', function(event){
    event.preventDefault();
    let billing_uid = $("#billing_uid").val();
    $.ajax({
        url:"/google-payment-data/quickstart.php",
        method:"GET",
        dataType: "json",
        data: {
            uid: billing_uid
        },
    }).done(function(success) {

    });
    $("#google-sheet-alert-block").removeClass('hide').addClass('alert-success');
    $("#google-sheet-alert-block-data").html('Google sheet data will be update within few minutes');
    view_billings();
});

function view_addons() {
    $("#user_view").addClass("hide");
    $("#details").addClass("hide");
    $("#user-image").addClass("hide");
    $("#user_manage").addClass("hide");
    $("#user-log").removeClass("hide");
    $("#home").addClass("hide");
    $("#profile").addClass("hide");
    $("#activity").addClass("hide");
    $("#stats").addClass("hide");
    $("#payments").addClass("hide");
    $("#billings").addClass("hide");
    $("#addons").removeClass("hide");
    $('.nav-tabs a[href="#addons"]').tab('show');
    get_addons_data();
}
function get_addons_data() {
    let addons_uid = $("#addons_uid").val();
    //alert(addons_uid);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let table = $('#member-addons-table').DataTable({
        processing: true,
        destroy: true,
        serverSide: true,
        responsive: true,
        autoWidth: true,
        "order": [[2, "desc"]],
        "paging": true,
        "lengthMenu": [50, 100],
        pagingType: "simple_numbers",
        "ajax": {
            "url": url + "/member/addons/data",
            data: {
                "addons_uid": addons_uid,
            },
            "type": "GET",

        },
        "createdRow": function (row, data, dataIndex) {
            $(row).addClass(data.class);
        },
        columns: [{
            data: 'ad_data',
            name: 'ad_data',
            /*width: "20%",*/
            "searchable": true,
            "orderable": true,
        }, {
            data: 'priority_name',
            name: 'priority_name',
            /*width: "20%",*/
            "searchable": true,
            "orderable": true,
        }, {
            data: 'created_at',
            name: 'created_at',
            /*width: "20%",*/
            "searchable": false,
            "orderable": true,
        }]
    })
    table.rows().invalidate().draw();
    check_pending_approval();
}