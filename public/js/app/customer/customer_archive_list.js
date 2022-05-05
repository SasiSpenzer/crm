$(document).ready(function() {

    $("#flash_message").css("display", "none");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let customer_details_table = $('#customers-archive-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        /*language: {
            processing: "<img src='img/loading.gif'> Loading...",
        },*/
        "order": [[ 4, "desc" ]],
        "paging": true,
        "lengthMenu": [50, 100],
        pagingType: "simple_numbers",
        ajax: url + "/customer/archive/data",
        columns: [{
            data: 'UID',
            name: 'UID',
            width: "15%",
            "searchable": true,
            "orderable": true,
        },{
            data: 'ad_id',
            name: 'ad_id',
            width: "15%",
            "searchable": true,
            "orderable": true,
        },{
            data: 'heading',
            name: 'heading',
            width: "15%",
            "searchable": true,
            "orderable": true,
        },{
            data: 'email',
            name: 'email',
            width: "20%",
            "searchable": true,
            "orderable": true,

        },{
            data: 'tel',
            name: 'tel',
            width: "15%",
            "searchable": true,
            "orderable": true,
        },{
            data: 'archived_date',
            name: 'archived_date',
            "searchable": false,
            width: "20%",
            "orderable": true,
        }]
    });
    customer_details_table.columns.adjust().draw();
});