$(document).ready(function() {
    $("#flash_message").css("display", "none");
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let my_list_data_table = $('#customer-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        "order": [[ 1, "asc" ]],
        "paging": true,
        "lengthMenu": [50, 100],
        pagingType: "simple_numbers",
        ajax: url + "/my/list/data",
        "createdRow": function( row, data, dataIndex){
            $(row).addClass(data.payment_class);
        },
        columns: [{
            data: 'full_name',
            width: "20%",
            name: 'full_name',
            "searchable": true,
            "orderable": true,
        }, {
            data: 'user_type',
            name: 'user_type',
            "searchable": true,
            "orderable": true,
        }, {
            data: 'am',
            name: 'am',
            "searchable": true,
            "orderable": true,
        }, {
            data: 'pay_status',
            name: 'pay_status',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'created_at',
            name: 'created_at',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'payment_status',
            width: "10%",
            name: 'payment_status',
            "searchable": false,
            "orderable": false,
        }, {
            data: 'source',
            name: 'source',
            "searchable": true,
            "orderable": true,
        }, {
            data: 'latest_comment',
            width: "10%",
            name: 'latest_comment',
            "searchable": false,
            "orderable": false,
        }, {
            data: 'latest_commented_at',
            name: 'latest_commented_at',
            "searchable": false,
            "orderable": true,
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
        },
        //     {
        //     data: 'expiry',
        //     name: 'expiry',
        //     "searchable": false,
        //     "orderable": true,
        // },
        //     {
        //     data: 'leads_count',
        //     name: 'leads_count',
        //     "searchable": false,
        //     "orderable": true,
        // }, {
        //     data: 'views_count',
        //     name: 'views_count',
        //     "searchable": false,
        //     "orderable": true,
        // },
        // {
        //     data: 'ads_count',
        //     name: 'ads_count',
        //     "searchable": false,
        //     "orderable": true,
        // },
        // {
        //     data: 'priority',
        //     name: 'priority',
        //     "searchable": false,
        //     "orderable": true,
        // },
        {
            data: 'email_address',
            name: 'email_address',
            "searchable": true,
            "orderable": true,
        },
         {
            data: null,
            className: "center",
            "searchable": false,
            "orderable": false,
            render: function(data, type, row) {
                //return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#my Modal" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
                return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#membershipModal" onclick="OpenMembershipModal()" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
            },
            //defaultContent: '<button type="button" class="btn btn-warning btn-view-pdf" data-toggle="modal" id='+data.firstname+' data-target="#my Modal">Edit</button>'
        }]
    });

    let Prospects = false;
    $('#btn-Prospects').click(function() {

        if (!Prospects) {
            let my_list_data_table = $('#customer-table-Prospects').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                "order": [[ 1, "asc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                "ajax": {
                    "url": url + "/my/list/data",
                    data: {
                        "type": 1,
                    },
                    "type": "GET",
                },
                "createdRow": function( row, data, dataIndex){
                    $(row).addClass(data.payment_class);
                },
                columns: [{
                    data: 'full_name',
                    width: "20%",
                    name: 'full_name',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'user_type',
                    name: 'user_type',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'am',
                    name: 'am',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'pay_status',
                    name: 'pay_status',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'created_at',
                    name: 'created_at',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'payment_status',
                    width: "10%",
                    name: 'payment_status',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'source',
                    name: 'source',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'latest_comment',
                    width: "10%",
                    name: 'latest_comment',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'latest_commented_at',
                    name: 'latest_commented_at',
                    "searchable": false,
                    "orderable": true,
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
                },
               // {
               //      data: 'expiry',
               //      name: 'expiry',
               //      "searchable": false,
               //      "orderable": true,
               //  }, {
               //      data: 'leads_count',
               //      name: 'leads_count',
               //      "searchable": false,
               //      "orderable": true,
               //  }, {
               //      data: 'views_count',
               //      name: 'views_count',
               //      "searchable": false,
               //      "orderable": true,
               //  }, {
               //      data: 'ads_count',
               //      name: 'ads_count',
               //      "searchable": false,
               //      "orderable": true,
               //  }, {
               //      data: 'priority',
               //      name: 'priority',
               //      "searchable": false,
               //      "orderable": true,
               //  },
                    {
                    data: 'email_address',
                    name: 'email_address',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        //return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#my Modal" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
                        return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#membershipModal" onclick="OpenMembershipModal()" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
                    },
                    //defaultContent: '<button type="button" class="btn btn-warning btn-view-pdf" data-toggle="modal" id='+data.firstname+' data-target="#my Modal">Edit</button>'
                }]
            });
            Prospects = true;
        }
    });
    let Inbound_call = false;
    $('#btn-Inbound_call').click(function() {

        if (!Inbound_call) {
            let my_list_data_table = $('#customer-table-Inbound_call').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                "order": [[ 1, "asc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                "ajax": {
                    "url": url + "/my/list/data",
                    data: {
                        "type": 2,
                    },
                    "type": "GET",
                },
                "createdRow": function( row, data, dataIndex){
                    $(row).addClass(data.payment_class);
                },
                columns: [{
                    data: 'full_name',
                    width: "20%",
                    name: 'full_name',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'user_type',
                    name: 'user_type',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'am',
                    name: 'am',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'pay_status',
                    name: 'pay_status',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'created_at',
                    name: 'created_at',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'payment_status',
                    width: "10%",
                    name: 'payment_status',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'source',
                    name: 'source',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'latest_comment',
                    width: "10%",
                    name: 'latest_comment',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'latest_commented_at',
                    name: 'latest_commented_at',
                    "searchable": false,
                    "orderable": true,
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
                },
                //     {
                //     data: 'expiry',
                //     name: 'expiry',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'leads_count',
                //     name: 'leads_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'views_count',
                //     name: 'views_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'ads_count',
                //     name: 'ads_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'priority',
                //     name: 'priority',
                //     "searchable": false,
                //     "orderable": true,
                // },
                    {
                    data: 'email_address',
                    name: 'email_address',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        //return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#my Modal" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
                        return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#membershipModal" onclick="OpenMembershipModal()" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
                    },
                    //defaultContent: '<button type="button" class="btn btn-warning btn-view-pdf" data-toggle="modal" id='+data.firstname+' data-target="#my Modal">Edit</button>'
                }]
            });
            Inbound_call = true;
        }
    });
    let Outbound_call = false;
    $('#btn-Outbound_call').click(function() {

        if (!Outbound_call) {
            let my_list_data_table = $('#customer-table-Outbound_call').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                "order": [[ 1, "asc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                "ajax": {
                    "url": url + "/my/list/data",
                    data: {
                        "type": 3,
                    },
                    "type": "GET",
                },
                "createdRow": function( row, data, dataIndex){
                    $(row).addClass(data.payment_class);
                },
                columns: [{
                    data: 'full_name',
                    width: "20%",
                    name: 'full_name',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'user_type',
                    name: 'user_type',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'am',
                    name: 'am',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'pay_status',
                    name: 'pay_status',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'created_at',
                    name: 'created_at',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'payment_status',
                    width: "10%",
                    name: 'payment_status',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'source',
                    name: 'source',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'latest_comment',
                    width: "10%",
                    name: 'latest_comment',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'latest_commented_at',
                    name: 'latest_commented_at',
                    "searchable": false,
                    "orderable": true,
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
                },
                //     {
                //     data: 'expiry',
                //     name: 'expiry',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'leads_count',
                //     name: 'leads_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'views_count',
                //     name: 'views_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'ads_count',
                //     name: 'ads_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'priority',
                //     name: 'priority',
                //     "searchable": false,
                //     "orderable": true,
                // },
                    {
                    data: 'email_address',
                    name: 'email_address',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        //return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#my Modal" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
                        return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#membershipModal" onclick="OpenMembershipModal()" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
                    },
                    //defaultContent: '<button type="button" class="btn btn-warning btn-view-pdf" data-toggle="modal" id='+data.firstname+' data-target="#my Modal">Edit</button>'
                }]
            });
            Outbound_call = true;
        }
    });

    let Chat_Email = false;
    $('#btn-Chat_Email').click(function() {

        if (!Chat_Email) {
            let my_list_data_table = $('#customer-table-Chat_Email').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                "order": [[ 1, "asc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                "ajax": {
                    "url": url + "/my/list/data",
                    data: {
                        "type": 4,
                    },
                    "type": "GET",
                },
                "createdRow": function( row, data, dataIndex){
                    $(row).addClass(data.payment_class);
                },
                columns: [{
                    data: 'full_name',
                    width: "20%",
                    name: 'full_name',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'user_type',
                    name: 'user_type',
                    "searchable": true,
                    "orderable": true,

                }, {
                    data: 'am',
                    name: 'am',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'pay_status',
                    name: 'pay_status',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'created_at',
                    name: 'created_at',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'payment_status',
                    width: "10%",
                    name: 'payment_status',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'source',
                    name: 'source',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'latest_comment',
                    width: "10%",
                    name: 'latest_comment',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'latest_commented_at',
                    name: 'latest_commented_at',
                    "searchable": false,
                    "orderable": true,
                }, {
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
                },
                //     {
                //     data: 'expiry',
                //     name: 'expiry',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'leads_count',
                //     name: 'leads_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'views_count',
                //     name: 'views_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'ads_count',
                //     name: 'ads_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'priority',
                //     name: 'priority',
                //     "searchable": false,
                //     "orderable": true,
                // },
                    {
                    data: 'email_address',
                    name: 'email_address',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        //return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#my Modal" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
                        return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#membershipModal" onclick="OpenMembershipModal()" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
                    },
                    //defaultContent: '<button type="button" class="btn btn-warning btn-view-pdf" data-toggle="modal" id='+data.firstname+' data-target="#my Modal">Edit</button>'
                }]
            });
            Chat_Email = true;
        }
    });
    let Newspaper = false;
    $('#btn-Newspaper').click(function() {

        if (!Newspaper) {
            let my_list_data_table = $('#customer-table-Newspaper').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                "order": [[ 13, "asc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                "ajax": {
                    "url": url + "/my/list/data",
                    data: {
                        "type": 5,
                    },
                    "type": "GET",
                },
                "createdRow": function( row, data, dataIndex){
                    $(row).addClass(data.payment_class);
                },
                columns: [{
                    data: 'full_name',
                    width: "20%",
                    name: 'full_name',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'user_type',
                    name: 'user_type',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'am',
                    name: 'am',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'pay_status',
                    name: 'pay_status',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'created_at',
                    name: 'created_at',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'payment_status',
                    width: "10%",
                    name: 'payment_status',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'source',
                    name: 'source',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'latest_comment',
                    width: "10%",
                    name: 'latest_comment',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'latest_commented_at',
                    name: 'latest_commented_at',
                    "searchable": false,
                    "orderable": true,
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
                },
                //     {
                //     data: 'expiry',
                //     name: 'expiry',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'leads_count',
                //     name: 'leads_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'views_count',
                //     name: 'views_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'ads_count',
                //     name: 'ads_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'priority',
                //     name: 'priority',
                //     "searchable": false,
                //     "orderable": true,
                // },
                    {
                    data: 'email_address',
                    name: 'email_address',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        //return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#my Modal" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
                        return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#membershipModal" onclick="OpenMembershipModal()" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
                    },
                    //defaultContent: '<button type="button" class="btn btn-warning btn-view-pdf" data-toggle="modal" id='+data.firstname+' data-target="#my Modal">Edit</button>'
                }]
            });
            Newspaper = true;
        }
    });
    let Ikman_List = false;
    $('#btn-Ikman_List').click(function() {

        if (!Ikman_List) {
            let my_list_data_table = $('#customer-table-Ikman_List').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                "order": [[ 1, "asc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                "ajax": {
                    "url": url + "/my/list/data",
                    data: {
                        "type": 6,
                    },
                    "type": "GET",
                },
                "createdRow": function( row, data, dataIndex){
                    $(row).addClass(data.payment_class);
                },
                columns: [{
                    data: 'full_name',
                    width: "20%",
                    name: 'full_name',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'user_type',
                    name: 'user_type',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'am',
                    name: 'am',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'pay_status',
                    name: 'pay_status',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'created_at',
                    name: 'created_at',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'payment_status',
                    width: "10%",
                    name: 'payment_status',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'source',
                    name: 'source',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'latest_comment',
                    width: "10%",
                    name: 'latest_comment',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'latest_commented_at',
                    name: 'latest_commented_at',
                    "searchable": false,
                    "orderable": true,
                }, {
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
                },
                //     {
                //     data: 'expiry',
                //     name: 'expiry',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'leads_count',
                //     name: 'leads_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'views_count',
                //     name: 'views_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'ads_count',
                //     name: 'ads_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'priority',
                //     name: 'priority',
                //     "searchable": false,
                //     "orderable": true,
                // },
                    {
                    data: 'email_address',
                    name: 'email_address',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        //return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#my Modal" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
                        return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#membershipModal" onclick="OpenMembershipModal()" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
                    },
                    //defaultContent: '<button type="button" class="btn btn-warning btn-view-pdf" data-toggle="modal" id='+data.firstname+' data-target="#my Modal">Edit</button>'
                }]
            });
            Ikman_List = true;
        }
    });
    let other = false;
    $('#btn-other').click(function() {

        if (!other) {
            let my_list_data_table = $('#customer-table-other').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                "order": [[ 1, "asc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                "ajax": {
                    "url": url + "/my/list/data",
                    data: {
                        "type": 7,
                    },
                    "type": "GET",
                },
                "createdRow": function( row, data, dataIndex){
                    $(row).addClass(data.payment_class);
                },
                columns: [{
                    data: 'full_name',
                    width: "20%",
                    name: 'full_name',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'user_type',
                    name: 'user_type',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'am',
                    name: 'am',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'pay_status',
                    name: 'pay_status',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'created_at',
                    name: 'created_at',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'payment_status',
                    width: "10%",
                    name: 'payment_status',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'source',
                    name: 'source',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'latest_comment',
                    width: "10%",
                    name: 'latest_comment',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'latest_commented_at',
                    name: 'latest_commented_at',
                    "searchable": false,
                    "orderable": true,
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
                },
                //     {
                //     data: 'expiry',
                //     name: 'expiry',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'leads_count',
                //     name: 'leads_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'views_count',
                //     name: 'views_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'ads_count',
                //     name: 'ads_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'priority',
                //     name: 'priority',
                //     "searchable": false,
                //     "orderable": true,
                // },
                    {
                    data: 'email_address',
                    name: 'email_address',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        //return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#my Modal" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
                        return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#membershipModal" onclick="OpenMembershipModal()" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
                    },
                    //defaultContent: '<button type="button" class="btn btn-warning btn-view-pdf" data-toggle="modal" id='+data.firstname+' data-target="#my Modal">Edit</button>'
                }]
            });
            other = true;
        }
    });
    let fb_other = false;
    $('#btn-fb_other').click(function() {

        if (!fb_other) {
            let my_list_data_table = $('#customer-table-fb_other').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                "order": [[ 1, "asc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                "ajax": {
                    "url": url + "/my/list/data",
                    data: {
                        "type": 8,
                    },
                    "type": "GET",
                },
                "createdRow": function( row, data, dataIndex){
                    $(row).addClass(data.payment_class);
                },
                columns: [{
                    data: 'full_name',
                    width: "20%",
                    name: 'full_name',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'user_type',
                    name: 'user_type',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'am',
                    name: 'am',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'pay_status',
                    name: 'pay_status',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'created_at',
                    name: 'created_at',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'payment_status',
                    width: "10%",
                    name: 'payment_status',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'source',
                    name: 'source',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'latest_comment',
                    width: "10%",
                    name: 'latest_comment',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'latest_commented_at',
                    name: 'latest_commented_at',
                    "searchable": false,
                    "orderable": true,
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
                },
                //     {
                //     data: 'expiry',
                //     name: 'expiry',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'leads_count',
                //     name: 'leads_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'views_count',
                //     name: 'views_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'ads_count',
                //     name: 'ads_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'priority',
                //     name: 'priority',
                //     "searchable": false,
                //     "orderable": true,
                // },
                    {
                    data: 'email_address',
                    name: 'email_address',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        //return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#my Modal" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
                        return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#membershipModal" onclick="OpenMembershipModal()" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
                    },
                    //defaultContent: '<button type="button" class="btn btn-warning btn-view-pdf" data-toggle="modal" id='+data.firstname+' data-target="#my Modal">Edit</button>'
                }]
            });
            fb_other = true;
        }
    });

    let other_new = false;
    $('#btn-other_new').click(function() {

        if (!other_new) {
            let my_list_data_table = $('#customer-table_other_new').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                "order": [[ 1, "asc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                "ajax": {
                    "url": url + "/my/list/data",
                    data: {
                        "type": 9,
                    },
                    "type": "GET",
                },
                "createdRow": function( row, data, dataIndex){
                    $(row).addClass(data.payment_class);
                },
                columns: [{
                    data: 'full_name',
                    width: "20%",
                    name: 'full_name',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'user_type',
                    name: 'user_type',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'am',
                    name: 'am',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'pay_status',
                    name: 'pay_status',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'created_at',
                    name: 'created_at',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'payment_status',
                    width: "10%",
                    name: 'payment_status',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'source',
                    name: 'source',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'latest_comment',
                    width: "10%",
                    name: 'latest_comment',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'latest_commented_at',
                    name: 'latest_commented_at',
                    "searchable": false,
                    "orderable": true,
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
                },
                //     {
                //     data: 'expiry',
                //     name: 'expiry',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'leads_count',
                //     name: 'leads_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'views_count',
                //     name: 'views_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'ads_count',
                //     name: 'ads_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'priority',
                //     name: 'priority',
                //     "searchable": false,
                //     "orderable": true,
                // },
                    {
                    data: 'email_address',
                    name: 'email_address',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        //return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#my Modal" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
                        return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#membershipModal" onclick="OpenMembershipModal()" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
                    },
                    //defaultContent: '<button type="button" class="btn btn-warning btn-view-pdf" data-toggle="modal" id='+data.firstname+' data-target="#my Modal">Edit</button>'
                }]
            });
            other_new = true;
        }
    });

    let agents = false;
    $('#btn-Agents').click(function() {

        if (!agents) {
            let my_list_data_table = $('#customer-table_Agents').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                "order": [[ 1, "asc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                "ajax": {
                    "url": url + "/my/list/data",
                    data: {
                        "type": 10,
                    },
                    "type": "GET",
                },
                "createdRow": function( row, data, dataIndex){
                    $(row).addClass(data.payment_class);
                },
                columns: [{
                    data: 'full_name',
                    width: "20%",
                    name: 'full_name',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'user_type',
                    name: 'user_type',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'am',
                    name: 'am',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'pay_status',
                    name: 'pay_status',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'created_at',
                    name: 'created_at',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'payment_status',
                    width: "10%",
                    name: 'payment_status',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'source',
                    name: 'source',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'latest_comment',
                    width: "10%",
                    name: 'latest_comment',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'latest_commented_at',
                    name: 'latest_commented_at',
                    "searchable": false,
                    "orderable": true,
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
                },
                //     {
                //     data: 'expiry',
                //     name: 'expiry',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'leads_count',
                //     name: 'leads_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'views_count',
                //     name: 'views_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'ads_count',
                //     name: 'ads_count',
                //     "searchable": false,
                //     "orderable": true,
                // }, {
                //     data: 'priority',
                //     name: 'priority',
                //     "searchable": false,
                //     "orderable": true,
                // },
                    {
                    data: 'email_address',
                    name: 'email_address',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        //return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#my Modal" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
                        return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#membershipModal" onclick="OpenMembershipModal()" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
                    },
                    //defaultContent: '<button type="button" class="btn btn-warning btn-view-pdf" data-toggle="modal" id='+data.firstname+' data-target="#my Modal">Edit</button>'
                }]
            });
            other_new = true;
        }
    });


});