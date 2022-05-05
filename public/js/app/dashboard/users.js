$(document).ready(function() {

    $("#flash_message").css("display", "none");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var user_member_details_table = $('#users-table').DataTable({
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
        ajax: url + "/member/data/users",
        columns: [{
            data: null,
            width: "20%",
            render: function(data, type, row) {
                return data.firstname + ' ' + data.surname;
            },
            "searchable": false,
            "orderable": false,
        }, {
            data: 'Uemail',
            width: "20%",
            name: 'users.Uemail',
            "searchable": true,
            "orderable": false,
        }, {
            data: 'ads_count',
            name: 'users.ads_count',
            "searchable": false,

        }/*, {
            data: 'last_updated_at',
            name: 'users_all_data.last_updated_at',
            "searchable": false,
            "orderable": true,
        }*/, {
            data: null,
            name: 'admin_members.updated_at',
            "orderable": true,
            render: function(data, type, row) {
                var lastUpdatedDate = new Date(data.last_updated_at);

                if (isNaN(lastUpdatedDate)) {
                    lastUpdatedDate = "";
                } else {
                    if (lastUpdatedDate.getDate() >= 1 && lastUpdatedDate.getDate() < 10) {
                        date = "0" + lastUpdatedDate.getDate();
                    } else {
                        date = lastUpdatedDate.getDate();
                    }

                    month = lastUpdatedDate.getMonth() + 1;
                    if (month >= 1 && month < 10) {
                        monthFormatted = "0" + month;
                    } else {
                        monthFormatted = month;
                    }

                    lastUpdatedDate = lastUpdatedDate.getFullYear() + "-" + monthFormatted + "-" + date;
                }

                return lastUpdatedDate;
            },
        }, {
            data: 'category',
            name: 'admin_members.category',
            "searchable": true,
            "orderable": false,
        }, {
            data: 'expiry',
            width: "10%",
            name: 'admin_members.expiry',
            "searchable": false,
            "orderable": false,
        }, {
            data: 'am',
            name: 'admin_members.am',
            "searchable": true,
            "orderable": false,
        }]
    });

});
