$(document).ready(function() {

    $("#flash_message").css("display", "none");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let table = $('#member-dashboard-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: true,
        "order": [[ 5, "desc" ]],
        "paging": true,
        "lengthMenu": [50, 100],
        pagingType: "simple_numbers",
        "ajax": {
            "url": url + "/member/dashboard/data",
            /*data: {
                "agent_id": agent_id,
            },*/
            "type": "GET",

        },
        "createdRow": function( row, data, dataIndex){
            $(row).addClass(data.class);
        },
        columns: [{
            data: 'username',
            name: 'username',
            /*width: "20%",*/
            "searchable": true,
            "orderable": true,
        },{
            data: 'category',
            name: 'category',
            /*width: "20%",*/
            "searchable": true,
            "orderable": true,
        },{
            data: 'max_page_id',
            name: 'max_page_id',
            /*width: "20%",*/
            "searchable": false,
            "orderable": true,
        },{
            data: 'pic_percentage',
            name: 'pic_percentage',
            /*width: "20%",*/
            "searchable": false,
            "orderable": true,
        }/*,{
            data: 'views_status',
            name: 'views_status',
            "searchable": false,
            "orderable": true,
        },{
            data: 'leeds_status',
            name: 'leeds_status',
            "searchable": false,
            "orderable": true,
        }*/, {
            data: null,
            className: "center",
            width: "25%",
            "searchable": false,
            "orderable": false,
            render: function (data, type, row) {
                //return data.ad_hits + data.status_img + ' (' + data.views_percentage + '%)';
                if(data.status == '1') {
                    return data.ad_hits + ' <img src="'+ url + '/uploads/up-arrow-1.png"> (' + data.views_percentage + '%)';
                } else if(data.status == '2') {
                    return data.ad_hits + ' <img src="'+ url + '/uploads/down-arrow-1.png"> (' + data.views_percentage + '%)';
                } else {
                    return data.ad_hits + ' <img src="'+ url + '/uploads/trans-equal-1.png"> (' + data.views_percentage + '%)';
                }
                //return ' '+data.status_img +' '
            }
        }, {
            data: null,
            className: "center",
            width: "25%",
            "searchable": false,
            "orderable": false,
            render: function (data, type, row) {
                //console.log(data.Uemail+'-'+data.total_stats);
                //return data.total_stats + data.status_img + ' (' + data.leads_percentage + '%)';
                if(data.leeds_status == '1') {
                    return data.total_stats + ' <img src="'+ url + '/uploads/up-arrow-1.png"> (' + data.leads_percentage + '%)';
                } else if(data.leeds_status == '2') {
                    return data.total_stats + ' <img src="'+ url + '/uploads/down-arrow-1.png"> (' + data.leads_percentage + '%)';
                } else {
                    return data.total_stats + ' <img src="'+ url + '/uploads/trans-equal-1.png"> (' + data.leads_percentage + '%)';
                }
            }
        },{
            data: 'boosts_left',
            name: 'boosts_left',
            /*width: "20%",*/
            "searchable": false,
            "orderable": true,
        },{
            data: 'ads_count',
            name: 'ads_count',
            /*width: "20%",*/
            "searchable": false,
            "orderable": true,
        },{
            data: 'payment_exp_date',
            name: 'payment_exp_date',
            /*width: "20%",*/
            "searchable": false,
            "orderable": true,
        },{
            data: 'member_since',
            name: 'member_since',
            /*width: "20%",*/
            "searchable": true,
            "orderable": true,
        },{
            data: 'membership_exp_date',
            name: 'membership_exp_date',
            /*width: "20%",*/
            "searchable": false,
            "orderable": true,
        },{
            data: 'ad_upgrade_count',
            name: 'ad_upgrade_count',
            /*width: "20%",*/
            "searchable": false,
            "orderable": true,
        },{
            data: 'Uemail',
            name: 'Uemail',
            /*width: "20%",*/
            "searchable": true,
            "orderable": true,
        },{
            data: 'am',
            name: 'am',
            /*width: "20%",*/
            "searchable": true,
            "orderable": true,
        },{
            data: 'user_type',
            name: 'user_type',
            /*width: "20%",*/
            "searchable": true,
            "orderable": true,
        }, {
            data: null,
            className: "center",
            "searchable": false,
            "orderable": false,
            render: function(data, type, row) {
                return '<button type="button" class="btn btn-warning" style="border-radius:10px;" id="member_ad_view" data-uid="' + data.UID + '">View Ads</button>';
            },
        }]
    });
    table.columns.adjust().draw();

    $(document).on("click", "#member_ad_view", function(e) { //user click on submit button
        e.preventDefault();
        let membership_uid = $(this).data("uid");
        console.log("uid : " + membership_uid);
        window.location.href = url + "/view/list/ads/by/customer?uid=" + membership_uid;
    });
});