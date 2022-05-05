$(document).ready(function() {

    $("#flash_message").css("display", "none");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let customer_details_table = $('#customers-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        deferRender: true,
        searchDelay: 1000,
        /*language: {
            processing: "<img src='img/loading.gif'> Loading...",
        },*/
        "order": [[ 3, "desc" ]],
        "paging": true,
        "lengthMenu": [50, 100],
        pagingType: "simple_numbers",
        ajax: url + "/customer/all/data",
        columns: [{
            data: 'username',
            name: 'username',
            width: "20%",
            "searchable": true,
            "orderable": true,
        },{
            data: 'Uemail',
            name: 'Uemail',
            width: "20%",
            "searchable": true,
            "orderable": true,
        },{
            data: 'type',
            name: 'type',
            width: "20%",
            "searchable": true,
            "orderable": true,
        },{
            data: 'mobile_nos',/*Get adverts contract data */
            name: 'mobile_nos',
            width: "20%",
            "searchable": true,
            "orderable": true,

        },{
            data: 'reg_date',
            name: 'reg_date',
            "searchable": false,
            "orderable": true,
        },{
            data: 'ads_count',
            name: 'ads_count',
            "searchable": false,
            "orderable": true,
        },{
            data: 'am',
            name: 'am',
            "searchable": false,
            "orderable": true,
        },{
            data: 'ad_id',
            name: 'ad_id',
            "searchable": true,
            "orderable": true,
        },{
            data: null,
            className: "center",
            "searchable": false,
            "orderable": false,
            render: function(data, type, row) {
                //return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#my Modal">Edit</button>'
                return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#membershipModal" onclick="OpenMembershipModal()">Edit</button>'
            },
         }]
    });
    //customer_details_table.columns.adjust().draw();
    //stop ajax search
    $(".dataTables_filter input")
        .unbind()
        .bind('keyup change', function (e) {
            if (e.keyCode == 13 || this.value == "") {
                customer_details_table.search(this.value)
                    .draw();
            }
        });
});
function OpenMembershipModal(){
    $('body').css('overflow', 'hidden');
}