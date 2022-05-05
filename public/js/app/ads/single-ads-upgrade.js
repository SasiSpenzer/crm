$(document).ready(function() {

    $("#flash_message").css("display", "none");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,

        "order": [[ 1, "desc" ]],
        "paging": true,
        "lengthMenu": [50, 100],
        pagingType: "simple_numbers",
        // ajax: url + "/sigle-ads/pending-payment-upgrade/data",
        "ajax": {
            "url": url + "/sigle-ads/pending-payment-upgrade/data",
            data: {
                "type": 1,
            },
            "type": "GET",
        },
        "createdRow": function( row, data, dataIndex){
            $(row).addClass(data.class);
        },
        columns: [{
            data: 'username',
            name: 'username',
            width: "20%",
            "searchable": true,
            "orderable": true,
        }, {
            data: 'expiry',
            name: 'expiry',
            width: "15%",
            "searchable": false,
            "orderable": true,
        }/*, {
            data: 'package_amount',
            name: 'package_amount',
            width: "10%",
            "searchable": false,
            "orderable": true,
        }*/,{
            data: 'upgrade_type',
            name: 'upgrade_type',
            "searchable": false,
            "orderable": true,

        }, {
            data: 'am',
            name: 'am',
            width: "10%",
            "searchable": true,
            "orderable": true,
        }, {
            data: 'status',
            name: 'status',
            width: "10%",
            "searchable": false,
            "orderable": true,
        }, {
            data: 'latest_comment',
            width: "25%",
            name: 'latest_comment',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'last_updated_at',
            name: 'last_updated_at',
            // width: "20%",
            "searchable": false,
            "orderable": true,
        }, {
            data: 'mobile_no',
            name: 'mobile_no',
            "searchable": true,

        },{
            data: 'Uemail',
            // width: "20%",
            name: 'Uemail',
            "searchable": true,
            "orderable": true,
        }, {
            data: 'type',
            name: 'type',
            "searchable": false,
            "orderable": true,

        }, {
            data: 'propty_type',
            name: 'propty_type',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'duration',
            name: 'duration',
            "searchable": false,
            "orderable": true,
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

    table.columns.adjust().draw();



    let btn_un_conected = false;
    $('#btn_un_conected').click(function() {

        if (!btn_un_conected) {
            var datadraw1 = $('#users-table-un-connected').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,

                "order": [[ 1, "desc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                //ajax: url + "/sigle-ads/pending-payment-upgrade/data",
                "ajax": {
                    "url": url + "/sigle-ads/pending-payment-upgrade/data",
                    data: {
                        "type": 2,
                    },
                    "type": "GET",
                },
                "createdRow": function( row, data, dataIndex){
                    $(row).addClass(data.class);
                },
                columns: [{
                    data: 'username',
                    name: 'username',
                    width: "20%",
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'expiry',
                    name: 'expiry',
                    width: "15%",
                    "searchable": false,
                    "orderable": true,
                }/*, {
            data: 'package_amount',
            name: 'package_amount',
            width: "10%",
            "searchable": false,
            "orderable": true,
        }*/,{
                    data: 'upgrade_type',
                    name: 'upgrade_type',
                    "searchable": false,
                    "orderable": true,

                }, {
                    data: 'am',
                    name: 'am',
                    width: "10%",
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'status',
                    name: 'status',
                    width: "10%",
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'latest_comment',
                    width: "25%",
                    name: 'latest_comment',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'last_updated_at',
                    name: 'last_updated_at',
                    // width: "20%",
                    "searchable": false,
                    "orderable": true,
                },{
                    data: 'mobile_no',
                    name: 'mobile_no',
                    "searchable": true,

                }, {
                    data: 'Uemail',
                    // width: "20%",
                    name: 'Uemail',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'type',
                    name: 'type',
                    "searchable": false,
                    "orderable": true,

                }, {
                    data: 'propty_type',
                    name: 'propty_type',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'duration',
                    name: 'duration',
                    "searchable": false,
                    "orderable": true,
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
            btn_un_conected = true;
            datadraw1.columns.adjust().draw();
        }
    });

    let btn_followups = false;
    $('#btn-followups').click(function() {

        if (!btn_followups) {
            var datadraw1 = $('#users-table-follow').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,

                "order": [[ 1, "desc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                //ajax: url + "/sigle-ads/pending-payment-upgrade/data",
                "ajax": {
                    "url": url + "/sigle-ads/pending-payment-upgrade/data",
                    data: {
                        "type": 3,
                    },
                    "type": "GET",
                },
                "createdRow": function( row, data, dataIndex){
                    $(row).addClass(data.class);
                },
                columns: [{
                    data: 'username',
                    name: 'username',
                    width: "20%",
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'expiry',
                    name: 'expiry',
                    width: "15%",
                    "searchable": false,
                    "orderable": true,
                }/*, {
            data: 'package_amount',
            name: 'package_amount',
            width: "10%",
            "searchable": false,
            "orderable": true,
        }*/,{
                    data: 'upgrade_type',
                    name: 'upgrade_type',
                    "searchable": false,
                    "orderable": true,

                }, {
                    data: 'am',
                    name: 'am',
                    width: "10%",
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'status',
                    name: 'status',
                    width: "10%",
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'latest_comment',
                    width: "25%",
                    name: 'latest_comment',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'last_updated_at',
                    name: 'last_updated_at',
                    // width: "20%",
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'mobile_no',
                    name: 'mobile_no',
                    "searchable": true,

                },{
                    data: 'Uemail',
                    // width: "20%",
                    name: 'Uemail',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'type',
                    name: 'type',
                    "searchable": false,
                    "orderable": true,

                }, {
                    data: 'propty_type',
                    name: 'propty_type',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'duration',
                    name: 'duration',
                    "searchable": false,
                    "orderable": true,
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
            btn_followups = true;
            datadraw1.columns.adjust().draw();
        }
    });

    let btn_two_left = false;
    $('#btn-two-left').click(function() {

        if (!btn_two_left) {
            var datadraw1 = $('#users-table-two-left').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,

                "order": [[ 1, "desc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                //ajax: url + "/sigle-ads/pending-payment-upgrade/data",
                "ajax": {
                    "url": url + "/sigle-ads/pending-payment-upgrade/data",
                    data: {
                        "type": 4,
                    },
                    "type": "GET",
                },
                "createdRow": function( row, data, dataIndex){
                    $(row).addClass(data.class);
                },
                columns: [{
                    data: 'username',
                    name: 'username',
                    width: "20%",
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'expiry',
                    name: 'expiry',
                    width: "15%",
                    "searchable": false,
                    "orderable": true,
                }/*, {
            data: 'package_amount',
            name: 'package_amount',
            width: "10%",
            "searchable": false,
            "orderable": true,
        }*/,{
                    data: 'upgrade_type',
                    name: 'upgrade_type',
                    "searchable": false,
                    "orderable": true,

                }, {
                    data: 'am',
                    name: 'am',
                    width: "10%",
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'status',
                    name: 'status',
                    width: "10%",
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'latest_comment',
                    width: "25%",
                    name: 'latest_comment',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'last_updated_at',
                    name: 'last_updated_at',
                    // width: "20%",
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'mobile_no',
                    name: 'mobile_no',
                    "searchable": true,

                },{
                    data: 'Uemail',
                    // width: "20%",
                    name: 'Uemail',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'type',
                    name: 'type',
                    "searchable": false,
                    "orderable": true,

                }, {
                    data: 'propty_type',
                    name: 'propty_type',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'duration',
                    name: 'duration',
                    "searchable": false,
                    "orderable": true,
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
            btn_two_left = true;
            datadraw1.columns.adjust().draw();
        }
    });


});
function OpenMembershipModal(){
    $('body').css('overflow', 'hidden');
}
