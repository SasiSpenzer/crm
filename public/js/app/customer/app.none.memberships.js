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
        deferRender: true,
        searchDelay: 1000,
        /*"search": {
            "smart": true,
            ajax: false,
        },
        "dom": '<"top"f>',
        "oLanguage": { "sSearch": '<a class="btn searchBtn" id="searchBtn"><i class="fa fa-search"></i></a>' },
        */
        /*language: {
            processing: "<img src='img/loading.gif'> Loading...",
        },*/
        "order": [[ 3, "desc" ]],
        "paging": true,
        "lengthMenu": [50, 100],
        pagingType: "simple_numbers",
        ajax: url + "/member/none-data",
        columns: [{
            data: null,
            width: "30%",
            name: 'users.firstname',
            render: function(data, type, row) {
                return data.firstname + ' ' + data.surname;
            },
            "searchable": false,
            "orderable": false,
        }, {
            data: 'Uemail',
            width: "30%",
            name: 'users.Uemail',
            "searchable": true,
            "orderable": false,
        }, {
            data: 'ads_count',
            name: 'users.ads_count',
            "searchable": false,
        }, {
            data: 'reg_date',
            name: 'users.reg_date',
            "searchable": false,
            "orderable": false,
        }, {
            data: 'last_updated_at',
            name: 'users.last_updated_at',
            "searchable": false,
        }, {
            data: 'latest_comment',
            name: 'admin_members.latest_comment',
            "searchable": false,
        }, {
            data: 'am',
            name: 'admin_members.am',
            "searchable": false,
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

   //stop ajax search
   $(".dataTables_filter input")
        .unbind()
        .bind('keyup change', function (e) {
        if (e.keyCode == 13 || this.value == "") {
            user_member_details_table.search(this.value)
                .draw();
        }
    });

});
function OpenMembershipModal(){
    $('body').css('overflow', 'hidden');
}
