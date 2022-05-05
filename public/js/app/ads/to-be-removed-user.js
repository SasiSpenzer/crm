$(document).ready(function() {

    $("#flash_message").css("display", "none");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var user_member_details_table = $('#tbl-today').DataTable({
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
        ajax: url + "/ads/manage/to-be-removed-data/" + user_id,
        columns: [{
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
            data: null,
            className: "center",
            "searchable": false,
            "orderable": false,
            render: function(data, type, row) {
                if (data.type == 'sales') {
                    return '<a target="_blank" href="https://www.lankapropertyweb.com/sale/property_details-' + data.ad_id + '.html">view</a>';
                } else if (data.type == 'rentals') {
                    return '<a target="_blank" href="https://www.lankapropertyweb.com/rentals/property_details-' + data.ad_id + '.html">view</a>';
                } else if (data.type == 'land') {
                    return '<a target="_blank" href="https://www.lankapropertyweb.com/land/property_details-' + data.ad_id + '.html">view</a>';
                } else if (data.type == 'wanted') {
                    return '<a target="_blank" href="https://www.lankapropertyweb.com/wanted/property_details-' + data.ad_id + '.html">view</a>';
                } else if (data.type == 'agents' && data.propty_type == 'agents') {
                    return '<a target="_blank" href="https://www.lankapropertyweb.com/agents/property_details-' + data.ad_id + '.html">view</a>';
                } else {
                    return '<a target="_blank" href="https://www.lankapropertyweb.com/services/property_details-' + data.ad_id + '.html">view</a>';
                }
            },

        }, {
            data: null,
            "orderable": false,
            name: 'adverts.submit_date',
            "orderable": true,
            render: function(data, type, row) {
                var lastUpdatedDate = new Date(data.submit_date);
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

                return lastUpdatedDate;
            },
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
            data: 'category',
            name: 'category',
            "searchable": true,
            "orderable": false,
        }, {
            data: 'am',
            name: 'am',
            "searchable": true,
            "orderable": false,
        }]
    });

});
