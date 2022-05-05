$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "GET",
        url: url + "/user/dashboard/widgets",
    }).done(function(members) {

        $("#total_users_data").append(members.total_users_count);
        $("#total_invoiced").append(members.total_invoiced_amount);
        $("#total_collected").append(members.total_paid_amount);
        $("#pending_payment").append(members.total_pending_amount);
        $("#total_value").append(members.total_users_value);
        $("#seller_non_paid_count").append(members.private_seller_membership_count);
        $("#seller_contact_count").append(members.private_seller_membership_value);

        $("#seller_paid_new").append(members.paid_count_value);
        $("#seller_free_new").append(members.free_count_value);

        $("#member_expire_count_new").append(members.member_expire_count_new);
        $("#member_expire_count_plus_two_new").append(members.member_expire_count_plus_two_new);
        $("#pvt_sellers_expire_count_new").append(members.pvt_sellers_expire_count_new);

        $("#online_member_count").append(members.online_user_count);
        $("#online_member_revenue").append(members.online_user_value);
        $("#unassigned_member_count").append(members.unassigned_user_count);
        $("#unassigned_member_revenue").append(members.unassigned_user_value);

        let member_type_view = '';
        console.log(members.am_payment_data);
        member_type_view = '<table width="100%" class="table table-striped table-bordered table-hover" id="users-table">'+
            '<thead>'+
            '<tr>'+
            '<th width="15%">AM Name</th>'+
            '<th width="10%">Active Account Count</th>'+
            '<th width="10%">Expired Account Count</th>'+
            '</tr>'+
            '</thead>'+
            '<tbody>';
        for (let i = 0; i < members.member_account_type_data.length; i++) {
            let account_type = members.member_account_type_data[i]['account_type'];
            let active_count = members.member_account_type_data[i]['active_count'];
            let expired_count = members.member_account_type_data[i]['expired_count'];


            member_type_view = member_type_view + '<tr>'+
                '<td>'+account_type+'</td>'+
                '<td>'+active_count+'</td>'+
                '<td>'+expired_count+'</td>'+
                '</tr>'+ '';
        }
        member_type_view =  member_type_view + '</tbody>'+
            '</table>';
        $("#widget-data").append(member_type_view);
        let admin_level = parseInt(members.admin_level);
        let data_view = '';
        //console.log(members.am_payment_data);
        if(admin_level > 2)
        {
            data_view = '<table width="100%" class="table table-striped table-bordered table-hover" id="users-table">'+
                    '<thead>'+
                    '<tr>'+
                    '<th width="15%">AM Name</th>'+
                    '<th width="10%">Member Count</th>'+
                    '<th width="10%">Expired Count</th>'+
                    '<th width="25%">Potential Value</th>'+
                    '<th width="25%">Invoiced Value</th>'+
                    '<th width="25%">Paid Value</th>'+

                    
                    '</tr>'+
                    '</thead>'+
                    '<tbody>';

            for (let i = 0; i < members.am_payment_data.length; i++) {
                //console.log('xxx');
                let am_name = members.am_payment_data[i]['am'];

                let invoiced_val = members.am_payment_data[i]['total_invoiced_amount'];
                let paid_val = members.am_payment_data[i]['total_paid_amount'];
                //let pending_val = members.am_payment_data[i]['pending_val'];
                let member_count = members.am_payment_data[i]['member_count'];
                let expired_member_count = members.am_payment_data[i]['expired_member_count'];
                let revnue_total = members.am_payment_data[i]['revnue_total'];
                let full_revnue_total_new = members.am_payment_data[i]['full_revnue_total']; // New



                data_view = data_view + '<tr>'+
                            '<td>'+am_name+'</td>'+
                            '<td>'+member_count+'</td>'+
                            '<td>'+expired_member_count+'</td>'+
                            '<td>'+revnue_total+' (' +full_revnue_total_new+ ')</td>'+
                            '<td>'+invoiced_val+'</td>'+
                            '<td>'+paid_val+'</td>'+
                            '</tr>' ;
            }
            data_view =  data_view + '</tbody>'+
                        '</table>';
            data_view = data_view + '</br>' +
                '<div>** <p>Value inside brackets under Potential is active + inactive for last 12 months</p></div>'
            $("#widget-data").append(data_view);
        } else {
            for (let i = 0; i < members.am_payment_data.length; i++) {
                //let am_user_count = members.am_data[i]['am_user_count'];
                //let am_user = members.am_data[i]['am'];
                //let department_id = members.am_data[i]['am_department_id'];
                //console.log(am_user + ' = ' + department_id);
                //let am_user_monthly_payment = members.am_data[i]['am_user_monthly_payment'];

                let am_name = members.am_payment_data[i]['am'];
                let invoiced_val = members.am_payment_data[i]['total_invoiced_amount'];
                let paid_val = members.am_payment_data[i]['total_paid_amount'];
                let member_count = members.am_payment_data[i]['member_count'];
                let revnue_total = members.am_payment_data[i]['revnue_total'];
                let full_revnue_total_new = members.am_payment_data[i]['full_revnue_total']; // New

                let color_code = '#79b4e7';
                let color_class = 'primary-item';
                let panel_name = 'primary';
                if ((i % 4) == 0) {
                    color_code = '#79b4e7';
                    color_class = 'primary-item';
                    panel_name = 'primary';
                } else if ((i % 4) == 1) {
                    color_code = '#a0ef86';
                    color_class = 'green-item';
                    panel_name = 'green';
                } else if ((i % 4) == 2) {
                    color_code = '#efcd9d';
                    color_class = 'yellow-item';
                    panel_name = 'yellow';
                } else if ((i % 4) == 3) {
                    color_code = '#e77575';
                    color_class = 'red-item';
                    panel_name = 'red';
                }
                    $("#widget-data").append(' <div class="col-lg-5 col-md-8">' +
                        '<div class="panel panel-' + panel_name + ' user-panel"> ' +
                        '<div class="panel-heading" style="margin-left: -1px !important;">' +
                        '<div class="row">' +
                        '<div class="col-12 text-center high-font-weight">' + am_name +
                        '<span class="pull-right" style="margin-right:10px; ">' +
                        '</span>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="panel-body data-panel user-panel-body">' +
                        '<div class="list-group row" style="font-size: 24px !important;">' +
                        '<div class="list-group-item ' + color_class + ' col-md-12 col-sm-12">' +
                        '<div class="text-center high-font-weight huge">'+revnue_total+'(' +full_revnue_total_new+ ')</div>' +
                        '</div>' +
                        '<div class="list-group-item ' + color_class + ' col-md-6 col-sm-6">' +
                        '<div id="total_users" class="text-center high-font-weight">' + member_count + '</div>' +
                        '<div class="text-center" style="font-size: 9px !important;">' +
                        'Memberships' +
                        '</div>' +
                        '</div>' +
                        '<div class="list-group-item ' + color_class + ' col-md-3 col-sm-3">' +
                        '<div class="text-center high-font-weight">'+invoiced_val+'</div>' +
                        '<div class="text-center" style="font-size: 9px !important;">' +
                        'Invoiced' +
                        '</div>' +
                        '</div>' +
                        '<div class="list-group-item ' + color_class + ' col-md-3 col-sm-3">' +
                        '<div class="text-center high-font-weight">'+paid_val+'</div>' +
                        '<div class="text-center" style="font-size: 9px !important;">' +
                        'Collected' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>'
                    );
            }
        }
    });

});