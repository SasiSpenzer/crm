$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $.ajax({
        type: "GET",
        url: url + "/category/wise/member/count",
    }).done(function(resultArr) {
        /*var data = [];
        $(".list-group").append('<div class="row show-grid">' +
            '<div class="col-md-3">Category</div>' +
            '<div class="col-md-2"># Mem</div>' +
            '<div class="col-md-3">Total</div>' +
            '</div>');
        var totalAmount = 0;
        for (var i = 0; i < resultArr.members.length; i++) {
            var object = resultArr.members[i];
            data.push({
                y: object['category'],
                a: object['member_count']
            });

            if (resultArr.revenueArrQuarterly[object['category']]) {
                quarterlyAmount = resultArr.revenueArrQuarterly[object['category']];
            } else {
                quarterlyAmount = 0;
            }

            if (resultArr.revenueArrAnnually[object['category']]) {
                annualAmount = resultArr.revenueArrAnnually[object['category']];
            } else {
                annualAmount = 0;
            }

            totalAmount = totalAmount + quarterlyAmount + annualAmount;

            $(".list-group").append('<div class="row show-grid">' +
                '<div class="col-md-3">' + object['category'] + '</div>' +
                '<div class="col-md-2">' + object['member_count'] + '</div>' +
                '<div class="col-md-3">' + ((quarterlyAmount + annualAmount).toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '</div>' +
                '</div>');
        }

        $(".list-group").append('<div class="row show-grid">' +
            '<div class="col-md-5"><span> Total</span> </div>' +
            '<div class="col-md-3">' + (totalAmount.toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '</div>' +
            '</div>');

        Morris.Bar({
            barGap: 4,
            barSizeRatio: 0.55,
            element: 'morris-bar-chart',
            data: data,
            xkey: 'y',
            ykeys: ['a'],
            labels: ['Members'],
            hideHover: 'auto',
            resize: true
        });*/

    });

    $.ajax({
        type: "GET",
        url: url + "/dashboard/widgets",
    }).done(function(members) {
        console.log(members);
        $("#total_users").append(members.total_users);
        $("#total_members").append(members.total_members);
        $("#active_count").append(members.active_members);
        $("#updated_accounts").append(members.updated_accounts);
        $("#new_members").append(members.new_members);
        $("#updated_accounts_last7days").append(members.updated_accounts_last7days);
        $("#new_members_last7days").append(members.new_members_last7days);
        /*let total_revenue = members.total_revenue_dis;
        if(parseFloat(members.my_revenue) > 0) {
            total_revenue = members.my_revenue_dis;
        }*/
        $("#total_revenue").append((members.potential_value).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        let my_target = (members.my_target_dis != null && members.my_target_dis != '')?members.my_target_dis:0.00;
        //$("#my_target").append((members.my_target.split('.')[0]).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        $("#my_target").append(my_target);
        //$("#my_target").append((members.my_target.toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        $("#my_revenue").append((members.my_revenue_dis).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        let presentage = 0;
        if(parseFloat(members.my_target) > 0) {
            presentage = members.my_revenue * 100 / members.my_target;
        }

        $("#span_my_target").append(presentage.toFixed(2) + '%');
        $("#progress_my_target").css({"width": presentage.toFixed(2) + "%"});

        //$("#group_target").append(members.group_target);
        $("#group_target").append((members.group_target_dis).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        $("#group_revenue").append((members.group_revenue_dis).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        presentage = members.group_revenue * 100 / members.group_target;
        $("#span_group_target").append(presentage.toFixed(2) + '%');
        $("#progress_group_target").css({"width": presentage + "%"});
    });

});