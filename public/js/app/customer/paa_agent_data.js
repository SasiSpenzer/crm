$(document).ready(function() {

    $("#flash_message").css("display", "none");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let agent_id = $("#agent-id").val();
    let customer_details_table = $('#paa-agent-data-table').DataTable({
        "createdRow": function (row, data, dataIndex) {
            console.log("Number : " + Number(data.days_count));
            if (Number(data.days_count) > 30) {
                $(row).addClass("high");
            }

        },
        processing: true,
        serverSide: true,
        responsive: true,
        /*language: {
            processing: "<img src='img/loading.gif'> Loading...",
        },*/
        "order": [[ 1, "asc" ]],
        "paging": true,
        "lengthMenu": [25, 50],
        "pageLength": 25,
        pagingType: "simple_numbers",
        "ajax": {
            "url": url + "/member/paa/agent/get/data",

            data: {
                "agent_id": agent_id,
            },
            "type": "GET",

        },
        columns: [{
            data: 'type',
            name: 'type',
            "searchable": true,
            "orderable": true,
        }, {
            data: 'days_count',
            name: 'days_count',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'ads_edit_count',
            name: 'ads_edit_count',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'last_edited',
            name: 'last_edited',
            "searchable": false,
            "orderable": true,
        }, {
            data: null,
            className: "center",
            "searchable": false,
            "orderable": false,
            render: function (data, type, row) {
                if(data.is_active == '3') {
                    return '<a href="#" disabled="">Archive Ad</a>';
                }
                else if (data.type == 'sales') {
                    return '<a target="_blank" href="https://www.lankapropertyweb.com/sale/property_details-' + data.ad_id + '.html">https://www.lankapropertyweb.com/sale/property_details-' + data.ad_id + '.html</a>';
                } else if (data.type == 'rentals') {
                    return '<a target="_blank" href="https://www.lankapropertyweb.com/rentals/property_details-' + data.ad_id + '.html">https://www.lankapropertyweb.com/rentals/property_details-' + data.ad_id + '.html</a>';
                } else if (data.type == 'land') {
                    return '<a target="_blank" href="https://www.lankapropertyweb.com/land/property_details-' + data.ad_id + '.html">https://www.lankapropertyweb.com/land/property_details-' + data.ad_id + '.html</a>';
                } else if (data.type == 'wanted') {
                    return '<a target="_blank" href="https://www.lankapropertyweb.com/wanted/property_details-' + data.ad_id + '.html">https://www.lankapropertyweb.com/wanted/property_details-' + data.ad_id + '.html</a>';
                } else if (data.type == 'agents' && data.propty_type == 'agents') {
                    return '<a target="_blank" href="https://www.lankapropertyweb.com/agents/property_details-' + data.ad_id + '.html">https://www.lankapropertyweb.com/agents/property_details-' + data.ad_id + '.html</a>';
                } else {
                    return '<a target="_blank" href="https://www.lankapropertyweb.com/services/property_details-' + data.ad_id + '.html">https://www.lankapropertyweb.com/services/property_details-' + data.ad_id + '.html</a>';
                }
            }
        },{
            data: null,
            className: "center",
            "searchable": false,
            "orderable": false,
            render: function (data, type, row) {
                if(data.is_active == 1){
                    return '<button type="button" class="btn btn-danger btn-ad-edit" data-toggle="modal" data-action="0" data-type="'+data.type+'" id=' + data.ad_id + ' data-target="#adAction" style="min-width: 90px !important;"><span id="btn_action_' + data.ad_id + '">Deactivate</span></button>';
                } else if(parseInt(data.is_active) == 3){
                    return '-';
                } else {
                    return '<button type="button" class="btn btn-primary btn-ad-edit" data-toggle="modal" data-action="1" data-type="'+data.type+'" id=' + data.ad_id + ' data-target="#adAction" style="min-width: 90px !important;"><span id="btn_action_' + data.ad_id + '">Activate</span></button>';
                }

            }
        }]
    });
    customer_details_table.columns.adjust().draw();

    $(document).on("click", ".btn-ad-edit", function(e) {
        e.preventDefault();
        let adId = $(this).attr("id");
        let adAction = $(this).data("action");
        let adType = $(this).data("type");
        let msg = "Are you sure? Do you really want to ";
        if(adAction == 1) {
            msg = msg + 'activate ';
        } else {
            msg = msg + 'deactivate ';
        }
        msg = msg + ' these ad?';
        if (adType == 'sales') {
            msg = msg + '(<a target="_blank" href="https://www.lankapropertyweb.com/sale/property_details-' + adId + '.html">https://www.lankapropertyweb.com/sale/property_details-' + adId + '.html</a>)';
        } else if (adType == 'rentals') {
            msg = msg + '(<a target="_blank" href="https://www.lankapropertyweb.com/rentals/property_details-' + adId + '.html">https://www.lankapropertyweb.com/rentals/property_details-' + adId + '.html</a>';
        }else if (adType == 'land') {
            msg = msg + '(<a target="_blank" href="https://www.lankapropertyweb.com/land/property_details-' + adId + '.html">https://www.lankapropertyweb.com/land/property_details-' + adId + '.html</a>)';
        }else if (adType == 'wanted') {
            msg = msg + '(<a target="_blank" href="https://www.lankapropertyweb.com/wanted/property_details-' + adId + '.html">https://www.lankapropertyweb.com/wanted/property_details-' + adId + '.html</a>)';
        }else if (adType == 'agents') {
            msg = msg + '(<a target="_blank" href="https://www.lankapropertyweb.com/agents/property_details-' + adId + '.html">https://www.lankapropertyweb.com/agents/property_details-' + adId + '.html</a>)';
        } else {
            msg = msg + '(<a target="_blank" href="https://www.lankapropertyweb.com/services/property_details-' + adId + '.html">https://www.lankapropertyweb.com/services/property_details-' + adId + '.html</a>)';
        }
        $("#ad_description").html(msg);
        $("#ad_id").val(adId);
        $("#ad_action").val(adAction);
    });

    $(document).on("click", "#ad_cancel", function(e) { //user click on submit button
        $('#adAction').modal('toggle');
    });
    $(document).on("click", "#ad_submit", function(e) {
        e.preventDefault();
        let adId = $("#ad_id").val();
        let adAction = $("#ad_action").val();
        $.ajax({
            type: "GET",
            url: url + "/member/ad/activation",
            data: {
                "ad_id": adId,
                "ad_action": adAction
            }
        }).done(function(data) {
            let btn_id = "#"+adId;
            let btn_action_id = "#btn_action_"+adId;
            if(adAction == "0"){
                $(btn_id).removeClass('btn-danger');
                $(btn_id).addClass('btn-primary');
                $( btn_id ).data( "action", 1 );
                $(btn_action_id).html( "Activate");
            } else {
                $(btn_id).removeClass('btn-primary');
                $(btn_id).addClass('btn-danger');
                $(btn_id).data( "action", 0 );
                $(btn_action_id).html( "Deactivate");
            }
            $('#adAction').modal('toggle');
        });
    });
});
