$(document).ready(function() {

    $("#flash_message").css("display", "none");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let user_member_details_table = $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        /*language: {
            processing: "<img src='img/loading.gif'> Loading...",
        },*/
        "order": [[ 3, "desc" ]],
        "paging": true,
        "lengthMenu": [50, 100],
        pagingType: "simple_numbers",
        ajax: url + "/member/data/members",
        columns: [{
            data: 'full_name',
            name: 'full_name',
            width: "20%",
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

        }, {
            data: 'category',
            name: 'category',
            "searchable": true,
            "orderable": true,
        }, {
            data: 'call_date_time',
            width: "10%",
            name: 'call_date_time',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'am',
            name: 'am',
            "searchable": false,
            "orderable": true,
        }]
    });

    //stop ajax search
    $(".dataTables_filter input")
        .unbind()
        .bind('keyup change', function (e) {
            if (e.keyCode == 13 || this.value == "") {
                user_member_details_table.search(this.value)
                    .draw();
            }
        });
    let add_member_tbl = true;

        $('#btn-active-members').click(function () {
            //alert("hello : " + add_member_tbl);
            if(add_member_tbl) {
                $('#active-users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    /*language: {
                        processing: "<img src='img/loading.gif'> Loading...",
                    },*/
                    "order": [[3, "desc"]],
                    "paging": true,
                    "lengthMenu": [50, 100],
                    pagingType: "simple_numbers",
                    ajax: url + "/member/data/members/active",
                    columns: [{
                        data: 'full_name',
                        name: 'full_name',
                        width: "20%",
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

                    }, {
                        data: 'category',
                        name: 'category',
                        "searchable": true,
                        "orderable": true,
                    }, {
                        data: 'call_date_time',
                        width: "10%",
                        name: 'call_date_time',
                        "searchable": false,
                        "orderable": true,
                    }, {
                        data: 'am',
                        name: 'am',
                        "searchable": false,
                        "orderable": true,
                    }]
                });
                add_member_tbl = false;
            }
        });


});
