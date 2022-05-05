$(document).ready(function() {

    $("#flash_message").css("display", "none");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let table = $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: true,
        /*language: {
            processing: "<img src='img/loading.gif'> Loading...",
        },*/
        "order": [[ 3, "desc" ]],
        "paging": true,
        "lengthMenu": [50, 100],
        pagingType: "simple_numbers",
        "ajax": {
            "url": url + "/pvt-sellers-expired/data",
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
        }, /*{
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

        },*/ {
            data: 'posted_date',
            width: "15%",
            name: 'posted_date',
            "searchable": false,
            "orderable": true,
        },{
            data: 'payment_exp_date',
             width: "15%",
            name: 'payment_exp_date',
            "searchable": false,
            "orderable": true,
        },  {
            data: 'am',
            width: "10%",
            name: 'am',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'status',
            width: "10%",
            name: 'status',
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
            "orderable": true,
            "searchable": false,
            /*render: function(data, type, row) {
                if(data.last_updated_at != null && data.last_updated_at != '') {
                    var lastUpdatedDate = new Date(data.last_updated_at);
                }

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
            },*/
        },{
            data: null,
            name: 'last_updated_by',
            // width: "20%",
            "searchable": false,
            "orderable": true,
            render: function(data, type, row) {
                if(data.last_updated_by == 'System'){
                    return '';
                } else {
                    return data.last_updated_by;
                }
            },
        }, {
            data: 'mobile_no',/*Get adverts contract data */
            name: 'mobile_no',
            "searchable": true,
            "orderable": true,

        },{
            data: 'Uemail',
            // width: "20%",
            name: 'Uemail',
            "searchable": true,
            "orderable": true,
        },  {
            data: 'type',
            name: 'type',
            "searchable": false,
            "orderable": true,

        }, {
            data: 'propty_type',
            name: 'propty_type',
            "searchable": false,
            "orderable": true,
        }/*, {
            data: 'submit_date',
            name: 'adverts.submit_date',
            "searchable": true,
            "orderable": false,
        }, {
            data: 'expiry',
            width: "10%",
            name: 'admin_members.expiry',
            "searchable": false,
            "orderable": false,
        }*/, {
            data: 'duration',
            name: 'duration',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'package_amount',
            width: "10%",
            name: 'package_amount',
            "searchable": false,
            "orderable": true,
        }, {
            data: null,
            name: 'ad_price',
            width: "10%",
            "searchable": false,
            "orderable": true,
            render: function(data, type, row) {
                var newprice = numberWithCommas(data.ad_price);
                return newprice;
            },
        },{
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
            var datadraw1 = $('#users-table-uncon').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: true,
                /*language: {
                    processing: "<img src='img/loading.gif'> Loading...",
                },*/
                "order": [[ 3, "desc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                "ajax": {
                    "url": url + "/pvt-sellers-expired/data",
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
                }, /*{
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

        },*/{
                    data: 'posted_date',
                    width: "15%",
                    name: 'posted_date',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'payment_exp_date',
                    width: "15%",
                    name: 'payment_exp_date',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'am',
                    width: "10%",
                    name: 'am',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'status',
                    width: "10%",
                    name: 'status',
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
                    "orderable": true,
                    "searchable": false,
                    /*render: function(data, type, row) {
                        if(data.last_updated_at != null && data.last_updated_at != '') {
                            var lastUpdatedDate = new Date(data.last_updated_at);
                        }

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
                    },*/
                },{
                    data: null,
                    name: 'last_updated_by',
                    // width: "20%",
                    "searchable": false,
                    "orderable": true,
                    render: function(data, type, row) {
                        if(data.last_updated_by == 'System'){
                            return '';
                        } else {
                            return data.last_updated_by;
                        }
                    },
                }, {
                    data: 'mobile_no',/*Get adverts contract data */
                    name: 'mobile_no',
                    "searchable": true,
                    "orderable": true,

                },{
                    data: 'Uemail',
                    // width: "20%",
                    name: 'Uemail',
                    "searchable": true,
                    "orderable": true,
                },  {
                    data: 'type',
                    name: 'type',
                    "searchable": false,
                    "orderable": true,

                }, {
                    data: 'propty_type',
                    name: 'propty_type',
                    "searchable": false,
                    "orderable": true,
                }/*, {
            data: 'submit_date',
            name: 'adverts.submit_date',
            "searchable": true,
            "orderable": false,
        }, {
            data: 'expiry',
            width: "10%",
            name: 'admin_members.expiry',
            "searchable": false,
            "orderable": false,
        }*/, {
                    data: 'duration',
                    name: 'duration',
                    "searchable": false,
                    "orderable": true,
                },{
                    data: 'package_amount',
                    width: "10%",
                    name: 'package_amount',
                    "searchable": false,
                    "orderable": true,
                },  {
                    data: null,
                    name: 'ad_price',
                    width: "10%",
                    "searchable": false,
                    "orderable": true,
                    render: function(data, type, row) {
                        var newprice = numberWithCommas(data.ad_price);
                        return newprice;
                    },
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

    let btn_mycontacts = false;
    $('#btn-mycontacts').click(function() {

        if (!btn_mycontacts) {
            var datadraw1 = $('#users-table-mycon').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: true,
                /*language: {
                    processing: "<img src='img/loading.gif'> Loading...",
                },*/
                "order": [[ 1, "desc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                "ajax": {
                    "url": url + "/pvt-sellers-expired/data",
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
                }, /*{
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

        },*/ {
                    data: 'posted_date',
                    width: "15%",
                    name: 'posted_date',
                    "searchable": false,
                    "orderable": true,
                },{
                    data: 'payment_exp_date',
                    width: "15%",
                    name: 'payment_exp_date',
                    "searchable": false,
                    "orderable": true,
                },  {
                    data: 'am',
                    width: "10%",
                    name: 'am',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'status',
                    width: "10%",
                    name: 'status',
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
                    "orderable": true,
                    "searchable": false,
                    /*render: function(data, type, row) {
                        if(data.last_updated_at != null && data.last_updated_at != '') {
                            var lastUpdatedDate = new Date(data.last_updated_at);
                        }

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
                    },*/
                },{
                    data: null,
                    name: 'last_updated_by',
                    // width: "20%",
                    "searchable": false,
                    "orderable": true,
                    render: function(data, type, row) {
                        if(data.last_updated_by == 'System'){
                            return '';
                        } else {
                            return data.last_updated_by;
                        }
                    },
                }, {
                    data: 'mobile_no',/*Get adverts contract data */
                    name: 'mobile_no',
                    "searchable": true,
                    "orderable": true,

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
                }/*, {
            data: 'submit_date',
            name: 'adverts.submit_date',
            "searchable": true,
            "orderable": false,
        }, {
            data: 'expiry',
            width: "10%",
            name: 'admin_members.expiry',
            "searchable": false,
            "orderable": false,
        }*/, {
                    data: 'duration',
                    name: 'duration',
                    "searchable": false,
                    "orderable": true,
                },{
                    data: 'package_amount',
                    width: "10%",
                    name: 'package_amount',
                    "searchable": false,
                    "orderable": true,
                },  {
                    data: null,
                    name: 'ad_price',
                    width: "10%",
                    "searchable": false,
                    "orderable": true,
                    render: function(data, type, row) {
                        var newprice = numberWithCommas(data.ad_price);
                        return newprice;
                    },
                },{
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
            btn_mycontacts = true;
            datadraw1.columns.adjust().draw();
        }
    });

    let btn_follow = false;
    $('#btn-follow').click(function() {

        if (!btn_follow) {
            var datadraw1 = $('#users-table-followta').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: true,
                /*language: {
                    processing: "<img src='img/loading.gif'> Loading...",
                },*/
                "order": [[ 3, "desc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                "ajax": {
                    "url": url + "/pvt-sellers-expired/data",
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
                }, /*{
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

        },*/ {
                    data: 'posted_date',
                    width: "15%",
                    name: 'posted_date',
                    "searchable": false,
                    "orderable": true,
                },{
                    data: 'payment_exp_date',
                    width: "15%",
                    name: 'payment_exp_date',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'package_amount',
                    width: "10%",
                    name: 'package_amount',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'am',
                    width: "10%",
                    name: 'am',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'status',
                    width: "10%",
                    name: 'status',
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
                    "orderable": true,
                    "searchable": false,
                    /*render: function(data, type, row) {
                        if(data.last_updated_at != null && data.last_updated_at != '') {
                            var lastUpdatedDate = new Date(data.last_updated_at);
                        }

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
                    },*/
                },{
                    data: null,
                    name: 'last_updated_by',
                    // width: "20%",
                    "searchable": false,
                    "orderable": true,
                    render: function(data, type, row) {
                        if(data.last_updated_by == 'System'){
                            return '';
                        } else {
                            return data.last_updated_by;
                        }
                    },
                },{
                    data: 'mobile_no',/*Get adverts contract data */
                    name: 'mobile_no',
                    "searchable": true,
                    "orderable": true,

                }, {
                    data: 'Uemail',
                    // width: "20%",
                    name: 'Uemail',
                    "searchable": true,
                    "orderable": true,
                },  {
                    data: 'type',
                    name: 'type',
                    "searchable": false,
                    "orderable": true,

                }, {
                    data: 'propty_type',
                    name: 'propty_type',
                    "searchable": false,
                    "orderable": true,
                }/*, {
            data: 'submit_date',
            name: 'adverts.submit_date',
            "searchable": true,
            "orderable": false,
        }, {
            data: 'expiry',
            width: "10%",
            name: 'admin_members.expiry',
            "searchable": false,
            "orderable": false,
        }*/, {
                    data: 'duration',
                    name: 'duration',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: null,
                    name: 'ad_price',
                    width: "10%",
                    "searchable": false,
                    "orderable": true,
                    render: function(data, type, row) {
                        var newprice = numberWithCommas(data.ad_price);
                        return newprice;
                    },
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
            btn_follow = true;
            datadraw1.columns.adjust().draw();
        }
    });

    let btn_order_list = false;
    $('#btn-order-list').click(function() {

        if (!btn_order_list) {
            var datadraw1 = $('#users-table-orderdate').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: true,
                /*language: {
                    processing: "<img src='img/loading.gif'> Loading...",
                },*/
                "order": [[ 3, "desc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                "ajax": {
                    "url": url + "/pvt-sellers-expired/data",
                    data: {
                        "type": 5,
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
                }, /*{
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

        },*/ {
                    data: 'posted_date',
                    width: "15%",
                    name: 'posted_date',
                    "searchable": false,
                    "orderable": true,
                },{
                    data: 'payment_exp_date',
                    width: "15%",
                    name: 'payment_exp_date',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'package_amount',
                    width: "10%",
                    name: 'package_amount',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'am',
                    width: "10%",
                    name: 'am',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'status',
                    width: "10%",
                    name: 'status',
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
                    "orderable": true,
                    "searchable": false,
                    /*render: function(data, type, row) {
                        if(data.last_updated_at != null && data.last_updated_at != '') {
                            var lastUpdatedDate = new Date(data.last_updated_at);
                        }

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
                    },*/
                },{
                    data: null,
                    name: 'last_updated_by',
                    // width: "20%",
                    "searchable": false,
                    "orderable": true,
                    render: function(data, type, row) {
                        if(data.last_updated_by == 'System'){
                            return '';
                        } else {
                            return data.last_updated_by;
                        }
                    },
                },{
                    data: 'mobile_no',/*Get adverts contract data */
                    name: 'mobile_no',
                    "searchable": true,
                    "orderable": true,

                }, {
                    data: 'Uemail',
                    // width: "20%",
                    name: 'Uemail',
                    "searchable": true,
                    "orderable": true,
                },  {
                    data: 'type',
                    name: 'type',
                    "searchable": false,
                    "orderable": true,

                }, {
                    data: 'propty_type',
                    name: 'propty_type',
                    "searchable": false,
                    "orderable": true,
                }/*, {
            data: 'submit_date',
            name: 'adverts.submit_date',
            "searchable": true,
            "orderable": false,
        }, {
            data: 'expiry',
            width: "10%",
            name: 'admin_members.expiry',
            "searchable": false,
            "orderable": false,
        }*/, {
                    data: 'duration',
                    name: 'duration',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: null,
                    name: 'ad_price',
                    width: "10%",
                    "searchable": false,
                    "orderable": true,
                    render: function(data, type, row) {
                        var newprice = numberWithCommas(data.ad_price);
                        return newprice;
                    },
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
            btn_order_list = true;
            datadraw1.columns.adjust().draw();
        }
    });

});
function OpenMembershipModal(){
    $('body').css('overflow', 'hidden');
}
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
