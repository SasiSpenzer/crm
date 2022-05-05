$(document).ready(function() {
    $("#flash_message").css("display", "none");
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let my_list_data_table = $('#customer-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        "order": [[ 13, "asc" ]],
        "paging": true,
        "lengthMenu": [50, 100],
        pagingType: "simple_numbers",
        ajax: url + "/my/list/data",
        "createdRow": function( row, data, dataIndex){
            $(row).addClass(data.payment_class);
        },
        columns: [{
            data: 'full_name',
            width: "20%",
            name: 'full_name',
            "searchable": true,
            "orderable": true,
        }, {
            data: 'category',
            name: 'category',
            "searchable": true,
            "orderable": true,
        }, {
            data: 'am',
            name: 'am',
            "searchable": true,
            "orderable": true,
        }, {
            data: 'pay_status',
            name: 'pay_status',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'payment_exp_date',
            name: 'payment_exp_date',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'payment_status',
            width: "10%",
            name: 'payment_status',
            "searchable": false,
            "orderable": false,
        }, {
            data: 'pending_amount',
            name: 'pending_amount',
            "searchable": true,
            "orderable": true,
        }, {
            data: 'latest_comment',
            width: "10%",
            name: 'latest_comment',
            "searchable": false,
            "orderable": false,
        }, {
            data: 'latest_commented_at',
            name: 'latest_commented_at',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'expiry',
            name: 'expiry',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'leads_count',
            name: 'leads_count',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'views_count',
            name: 'views_count',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'ads_count',
            name: 'ads_count',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'priority',
            name: 'priority',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'email_address',
            name: 'email_address',
            "searchable": true,
            "orderable": true,
        }, {
            data: null,
            className: "center",
            "searchable": false,
            "orderable": false,
            render: function(data, type, row) {
                //return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#my Modal" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
                return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#membershipModal" onclick="OpenMembershipModal()" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
            },
            //defaultContent: '<button type="button" class="btn btn-warning btn-view-pdf" data-toggle="modal" id='+data.firstname+' data-target="#my Modal">Edit</button>'
        }]
    });
});