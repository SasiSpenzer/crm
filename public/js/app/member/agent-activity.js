$(document).ready(function() {

    $("#flash_message").css("display", "none");

    let email_address = $("#search_email").val();
    if(email_address != '' && email_address != null) {
        getDataTable();
    } else {
        $("#list-ads-by-email-div").css("display", "none");
        $("#list-ads").css("display", "none");
        $("#reset-activity").css("display", "none");
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("#search_email").autocomplete({
        source: function(request, response) {
            if (request.term.includes("@")) {
                $.ajax({
                    url: url + "/autocomplete/customer/email",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                        $("#list-ads").css("display", "block");
                        $("#reset-activity").css("display", "block");

                    }
                });
            }

        },
        min_length: 3,

    });

    $(document).on("click", "#list-ads", function(e) {
        getDataTable();
    });
    $(document).on("click", "#reset-activity", function(e) {
        location.reload();
    });

    function getDataTable() {
        $("#list-ads-by-email-div").css("display", "block");
        $("#list-ads").attr("disabled", true);
        $("#search_email").attr("disabled", true);

        let email = $("#search_email").val();
        if (email != "") {
            let agent_activity_table = $('#agent-activity-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                //autoWidth: true,
                searching: false,
                destroy: true,
                "order": [[3, "desc"]],
                paging: false,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                "ajax": {
                    "url": url + "/agent/activity/data",
                    data: {
                        "type": 0,
                        "email": email
                    },
                    "type": "GET",
                },
                columns: [{
                    data: 'UID',
                    width: "20%",
                    name: 'UID',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: 'firstname',
                    width: "20%",
                    name: 'firstname',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: 'surname',
                    name: 'surname',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: 'Uemail',
                    name: 'Uemail',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: 'category',
                    name: 'category',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: 'am',
                    width: "10%",
                    name: 'am',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'expiry',
                    name: 'expiry',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: 'payment_exp_date',
                    name: 'payment_exp_date',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: 'test',
                    name: 'test',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'post_count_q1',
                    name: 'post_count_q1',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'edit_count_q1',
                    name: 'edit_count_q1',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'boost_count_q1',
                    name: 'boost_count_q1',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'paid_count_q1',
                    name: 'paid_count_q1',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'views_count_q1',
                    name: 'views_count_q1',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'leads_count_q1',
                    name: 'leads_count_q1',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'test',
                    name: 'test',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'post_count_q2',
                    name: 'post_count_q2',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'edit_count_q2',
                    name: 'edit_count_q2',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'boost_count_q2',
                    name: 'boost_count_q2',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'paid_count_q2',
                    name: 'paid_count_q2',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'views_count_q2',
                    name: 'views_count_q2',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'leads_count_q2',
                    name: 'leads_count_q2',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'test',
                    name: 'test',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'post_count_q3',
                    name: 'post_count_q3',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'edit_count_q3',
                    name: 'edit_count_q3',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'boost_count_q3',
                    name: 'boost_count_q3',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'paid_count_q3',
                    name: 'paid_count_q3',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'views_count_q3',
                    name: 'views_count_q3',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'leads_count_q3',
                    name: 'leads_count_q3',
                    "searchable": false,
                    "orderable": false,
                }]
            });
        }
    }

    //stop ajax search
    /*$(".dataTables_filter input")
        .unbind()
        .bind('keyup change', function (e) {
            if (e.keyCode == 13 || this.value == "") {
                agent_activity_table.search(this.value)
                    .draw();
            }
        });*/
});