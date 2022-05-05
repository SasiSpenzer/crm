$(document).ready(function() {
    $("#flash_message").css("display", "none");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var user_member_details_table = $('#tbl-expired-hunter').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        /*language: {
            processing: "<img src='img/loading.gif'> Loading...",
        },*/
        "order": [[ 5, "desc" ]],
        "paging": true,
        "lengthMenu": [50, 100],
        pagingType: "simple_numbers",
        ajax: url + "/payment/expire/hunters/data",
        columns: [{
            data: 'full_name',
            width: "20%",
            name: 'full_name',
            "searchable": true,
            "orderable": true,
        }, {
            data: 'Uemail',
            width: "20%",
            name: 'Uemail',
            "searchable": true,
            "orderable": true,
        }, {
            data: 'ads_count',
            name: 'ads_count',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'last_updated_at',
            name: 'last_updated_at',
            "searchable": false,
            "orderable": true,
        },  {
            data: 'category',
            name: 'category',
            "searchable": true,
            "orderable": true,
        }, {
            data: 'payment_exp_date',
            width: "10%",
            name: 'payment_exp_date',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'am',
            name: 'am',
            "searchable": true,
            "orderable": true,
        }, {
            data: null,
            className: "center",
            "searchable": false,
            "orderable": false,
            render: function(data, type, row) {
                //return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#my Modal">Edit</button>'
                return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#membershipModal" onclick="OpenMembershipModal()">Edit</button>'
            },
            //defaultContent: '<button type="button" class="btn btn-warning btn-view-pdf" data-toggle="modal" id='+data.firstname+' data-target="#my Modal">Edit</button>'
        }]
    });

});