$(document).ready(function() {

    $("#flash_message").css("display", "none");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#start_date").datetimepicker({
        showOn: "button",
        showSecond: false,
        minView: 2,
        format: "yyyy-mm-dd",
        //dateFormat: "yy-mm-dd",
        showTimepicker: false,
        autoclose: true,
        todayBtn: true,
    });
    $("#end_date").datetimepicker({
        showOn: "button",
        showSecond: false,
        minView: 2,
        format: "yyyy-mm-dd",
        //dateFormat: "yy-mm-dd",
        showTimepicker: false,
        autoclose: true,
        todayBtn: true,
    });

    $('#clear_date_range').click(function() {
        location.reload(true);
    });

    $('#add_date_range').click(function() {
        let start_date = $("#start_date").val();
        let end_date = $("#end_date").val();
        if(start_date == null || start_date == '') {
            alert("Please set start date");
        }
        else if(end_date == null || end_date == '') {
            alert("Please set end date");
        } else {
            let search_data_table = $('#search-data-tbl').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                "bDestroy": true,
                /*language: {
                    processing: "<img src='img/loading.gif'> Loading...",
                },*/
                "order": [[ 2, "desc" ]],
                "paging": true,
                "lengthMenu": [ 25, 50],
                "pageLength": 300,
                pagingType: "simple_numbers",
                "ajax": {
                    "url": url + "/member/paa/agents/search/data",
                    data: {
                        "start_date": start_date,
                        "end_date": end_date,
                    },
                    "type": "GET",

                },
                columns: [{
                    data: 'username',
                    name: 'username',
                    "searchable": true,
                    "orderable": true,
                },{
                    data: 'email',
                    name: 'email',
                    "searchable": true,
                    "orderable": true,
                },{
                    data: 'am',
                    name: 'am',
                    "searchable": true,
                    "orderable": true,
                },{
                    data: 'ads_count',
                    name: 'ads_count',
                    "searchable": false,
                    "orderable": true,
                },{
                    data: 'revenue',
                    name: 'revenue',
                    "searchable": false,
                    "orderable": true,

                },{
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        if(data.ads_count == "0"){
                            return '<a type="button" disabled readonly class="btn btn-warning btn-ppa-view" href="#">View</a>'
                            //return '<a type="button" disabled readonly class="btn btn-warning btn-ppa-view" href="#">View</a>'
                        } else {
                            return '<a type="button" class="btn btn-warning btn-ppa-view" href="'+url+'/member/paa/agent/'+data.uid+'">View</a>'
                            //return '<a type="button" class="btn btn-warning btn-ppa-view" href="#">View</a>'
                        }

                    },
                }],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;

                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    // Total over all pages
                    let month_total_revenue = api
                        .column( 3 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    // Total over all pages
                    let month_total_ads = api
                        .column( 2 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );


                    $( api.column( 3 ).footer() ).html(
                        month_total_revenue.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
                    );
                    // Update footer
                    $( api.column( 2 ).footer() ).html(
                        month_total_ads.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
                    );
                }
            });
            search_data_table.columns.adjust().draw();
        }
    });

    let customer_details_table = $('#paa-agents-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        /*language: {
            processing: "<img src='img/loading.gif'> Loading...",
        },*/
        "order": [[ 6, "desc" ]],
        "paging": true,
        "lengthMenu": [ 25, 50],
        "pageLength": 300,
        pagingType: "simple_numbers",
        ajax: url + "/member/paa/agents/data",
        columns: [{
            data: 'username',
            name: 'username',
            "searchable": true,
            "orderable": true,
        },{
                    data: 'email',
                    name: 'email',
                    "searchable": true,
                    "orderable": true,
                },{
            data: 'am',
            name: 'am',
            "searchable": true,
            "orderable": true,
        },{
            data: 'seven_day_ad_count',
            name: 'seven_day_ad_count',
            "searchable": false,
            "orderable": true,
        },{
            data: 'seven_day_revenue',
            name: 'seven_day_revenue',
            "searchable": false,
            "orderable": true,

        },{
            data: 'ads_count',
            name: 'ads_count',
            "searchable": false,
            "orderable": true,
        },{
            data: 'revenue',
            name: 'revenue',
            "searchable": false,
            "orderable": true,

        },{
            data: 'last_month_ad_count',
            name: 'last_month_ad_count',
            "searchable": false,
            "orderable": true,
        },{
            data: 'last_month_revenue',
            name: 'last_month_revenue',
            "searchable": false,
            "orderable": true,

        },{
            data: null,
            className: "center",
            "searchable": false,
            "orderable": false,
            render: function(data, type, row) {
                if(data.ads_count == "0"){
                    return '<a type="button" disabled readonly class="btn btn-warning btn-ppa-view" href="#">View</a>'
                    //return '<a type="button" disabled readonly class="btn btn-warning btn-ppa-view" href="#">View</a>'
                } else {
                    return '<a type="button" class="btn btn-warning btn-ppa-view" href="'+url+'/member/paa/agent/'+data.uid+'">View</a>'
                    //return '<a type="button" class="btn btn-warning btn-ppa-view" href="#">View</a>'
                }

            },
        }],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            let month_total_revenue = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Total over all pages
            let month_total_ads = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Total over all pages
            let today_total_revenue = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Total over all pages
            let today_total_ads = api
                .column( 2 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Total over all pages
            let week_total_revenue = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Total over all pages
            let week_total_ads = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            $( api.column( 7 ).footer() ).html(
                month_total_revenue.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            );
            // Update footer
            $( api.column( 6 ).footer() ).html(
                month_total_ads.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            );
            $( api.column( 5 ).footer() ).html(
                week_total_revenue.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            );
            // Update footer
            $( api.column( 4 ).footer() ).html(
                week_total_ads.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            );
            $( api.column( 3 ).footer() ).html(
                today_total_revenue.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            );
            // Update footer
            $( api.column( 2 ).footer() ).html(
                today_total_ads.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            );
        }
    });
    customer_details_table.columns.adjust().draw();

});
