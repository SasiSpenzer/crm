$(document).ready(function() {

    $("#list-ads-by-email-div").css("display", "none");
    $("#list-ads").css("display", "none");
    $("#save-ads").css("display", "none");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#search_email").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: url + "/autocomplete/customer/email",
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data) {
                    response(data);
                    $("#list-ads").css("display", "block");

                }
            });
        },
        min_length: 3,

    });

    $(document).on("click", "#list-ads", function(e) {
        getDataTable();
    });

    $(document).on("click", "#save-ads", function(e) {
        getDataTable();
    });

    function getDataTable() {
        $("#list-ads-by-email-div").css("display", "block");
        $("#save-ads").css("display", "block");
        $("#list-ads").removeClass().addClass("btn btn-default btn-success");

        var email = $("#search_email").val();
        if (email != "") {
            var list_ads_by_email_table = $('#list-ads-by-email').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                responsive: true,
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                "ajax": {
                    "url": url + "/list/ads/by/customer",

                    data: {
                        "email": email,
                    },
                    "type": "POST",

                },
                columns: [{
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        if (data.is_active == "1") {
                            return '<div class="checkbox"><input type="checkbox" value="' + data.ad_id + '" class ="activate" checked></div>';
                        } else {
                            return '<div class="checkbox"><input type="checkbox" value="' + data.ad_id + '" class ="activate"></div>';
                        }

                    },
                }, {
                    data: 'heading',
                    width: "20%",
                    name: 'adverts.heading',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: 'propty_type',
                    width: "20%",
                    name: 'adverts.propty_type',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: 'service_type',
                    name: 'adverts.service_type',
                    "searchable": false,

                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        if (data.is_active != 0) {
                            return '<div id="' + data.ad_id + '"><button id="btn' + data.ad_id + '" type="button" class="btn btn-success btn-circle"><i class="fa fa-check" id="i' + data.ad_id + '"></i></button><div>';
                        } else {
                            return '<div id="' + data.ad_id + '"><button id="btn' + data.ad_id + '" type="button" class="btn btn-danger btn-circle"><i class="fa fa-times" id="i' + data.ad_id + '"></i></button><div>';
                        }

                    },
                }],
            });
        }
    }

    $(document).on("click", ".activate", function(e) {
        ad_id = $(this).attr("value");
        var is_checked;
        if ($(this).prop('checked') == true) {
            is_checked = true;
        } else {
            is_checked = false;
        }

        $.ajax({
            url: url + "/activate/ad",
            dataType: "json",
            data: {
                ad_id: ad_id,
                is_checked: is_checked
            },
            "type": "POST",

        }).done(function(member) {
            if (member.is_active == "1") {
                $("#btn" + member.ad_id).removeClass().addClass("btn btn-success btn-circle");
                $("#i" + member.ad_id).removeClass().addClass("fa fa-check");
            } else if (member.is_active == "0") {
                $("#btn" + member.ad_id).removeClass().addClass("btn btn-danger btn-circle");
                $("#i" + member.ad_id).removeClass().addClass("fa fa-times");
            }
        });
    });

    $(document).on("click", "#select-all", function(e) {
        $('.activate').each(function() { //iterate all listed checkbox items
            $(this).prop('checked', true); //change ".checkbox" checked status
            /*ad_id = $(this).attr("value");
            $.ajax({
                url: url + "/activate/ad",
                dataType: "json",
                data: {
                    ad_id: ad_id,
                    is_checked: true
                },
                "type": "POST",

            }).done(function(member) {
                if (member.is_active == "1") {
                    $("#btn" + member.ad_id).removeClass().addClass("btn btn-success btn-circle");
                    $("#i" + member.ad_id).removeClass().addClass("fa fa-check");
                } else if (member.is_active == "0") {
                    $("#btn" + member.ad_id).removeClass().addClass("btn btn-danger btn-circle");
                    $("#i" + member.ad_id).removeClass().addClass("fa fa-times");
                }
            });*/
        });
    });

});