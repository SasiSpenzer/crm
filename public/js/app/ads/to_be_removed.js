$(document).ready(function() {

    $("#flash_message").css("display", "none");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '.btn-remove-membership', function(){

        var r = confirm("You are about to remove all ads!");
        if (r == false) {
            return false;
        }

        user_id = this.id;
        user_tr = $(this).closest("tr");
        $.ajax({
            type: "POST",
            url: url + "/member/deactivate",
            data: {
                "user_id": user_id,
            },
        }).done(function(data) {
            if (data == 'success') {
                user_tr.remove();
            }
        });
    });

    let user_member_details_table = $('#tbl_active_ads').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        "order": [[ 3, "desc" ]],
        "paging": true,
        "lengthMenu": [50, 100],
        pagingType: "simple_numbers",
        ajax: url + "/ads/manage/to-be-removed-data",
        columns: [{
            data: 'username',
            width: "20%",
            /*render: function(data, type, row) {
                return data.firstname + ' ' + data.surname;
            },*/
            "searchable": true,
            "orderable": true,
        }, {
            data: 'Uemail',
            width: "20%",
            name: 'Uemail',
            "searchable": true,
            "orderable": true,
        },  {
            data: 'expiry',
            name: 'expiry',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'payment_exp_date',
            name: 'payment_exp_date',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'category',
            name: 'category',
            "searchable": true,
            "orderable": true,
        }, {
            data: 'am',
            name: 'am',
            "searchable": true,
            "orderable": true,
        }, {
            data: null,
            className: "center",
            "searchable": false,
            "orderable": false,
            render: function(data, type, row) {
                return '<a target="_blank" href="'+ url +'/view/list/ads/by/customer?uid=' + data.UID + '">View</a>'
            },
        }, {
            data: null,
            className: "center",
            "searchable": false,
            "orderable": false,
            render: function(data, type, row) {
                return '<button type="button" class="btn btn-warning btn-remove-membership" id=' + data.UID + '>Deactivate Account</button>'
            },
        }]
    });

    //stop ajax search
    $(".dataTables_filter input")
        .unbind()
        .bind('keyup change', function (e) {
            if (e.keyCode == 13 || this.value == "") {
                user_member_details_table.search(this.value)
                    .draw();
            }
        });

    let btn_without_payment = false;
    $('#btn-without-payment').click(function() {
        if (!btn_without_payment) {
            let user_member_details_table = $('#tbl_without_payment').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,

                "order": [[ 3, "desc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                ajax: url + "/ads/manage/without-payment-data",
                columns: [{
                    data: 'username',
                    width: "20%",
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'Uemail',
                    width: "20%",
                    name: 'users.Uemail',
                    "searchable": true,
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
                    data: 'category',
                    name: 'category',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: 'am',
                    name: 'am',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        return '<a target="_blank" href="'+ url +'/view/list/ads/by/customer?uid=' + data.UID + '">View</a>'
                    },
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        return '<button type="button" class="btn btn-warning btn-remove-membership" id=' + data.UID + '>Deactivate Account</button>'
                    },
                }]
            });
            btn_without_payment = true;
        }
    });

    let btn_limit_exceed = false;
    $('#btn-limit-exceed').click(function() {
        if (!btn_limit_exceed) {
            let user_member_details_table = $('#tbl_limit_exceed').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,

                "order": [[ 3, "desc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                ajax: url + "/ads/manage/limit-exceed",
                columns: [{
                    data: 'username',
                    width: "20%",
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'Uemail',
                    width: "20%",
                    name: 'users.Uemail',
                    "searchable": true,
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
                    data: 'category',
                    name: 'category',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: 'am',
                    name: 'am',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        return '<a target="_blank" href="'+ url +'/view/list/ads/by/customer?uid=' + data.UID + '">View</a>'
                    },
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        return '<button type="button" class="btn btn-warning btn-remove-membership" id=' + data.UID + '>Deactivate Account</button>'
                    },
                }]
            });
            btn_limit_exceed = true;
        }
    });

    let btn_null_ads = false;
    $('#btn-null-ads').click(function() {
        if (!btn_null_ads) {
            let user_member_details_table = $('#tbl_null_ads').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,

                "order": [[ 3, "desc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                ajax: url + "/ads/manage/null-ad-data",
                columns: [{
                    data: 'username',
                    width: "20%",
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'Uemail',
                    width: "20%",
                    name: 'users.Uemail',
                    "searchable": true,
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
                    data: 'category',
                    name: 'category',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: 'am',
                    name: 'am',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        return '<a target="_blank" href="'+ url +'/view/list/ads/by/customer?uid=' + data.UID + '">View</a>'
                    },
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        return '<button type="button" class="btn btn-warning btn-remove-membership" id=' + data.UID + '>Deactivate Account</button>'
                    },
                }]
            });
            btn_null_ads = true;
        }
    });

    let btn_app_ads = false;
    $('#btn-app-ads').click(function() {
        if (!btn_app_ads) {
            let user_member_details_table = $('#tbl_app_ads').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,

                "order": [[ 3, "desc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                ajax: url + "/ads/manage/app-ad-data",
                columns: [{
                    data: 'username',
                    width: "20%",
                    name: 'username',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'Uemail',
                    width: "20%",
                    name: 'users.Uemail',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'expiry',
                    name: 'expiry',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'payment_exp_date',
                    name: 'payment_exp_date',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'category',
                    name: 'category',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'am',
                    name: 'am',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        return '<a target="_blank" href="'+ url +'/view/list/ads/by/customer?uid=' + data.UID + '">View</a>'
                    },
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        return '<button type="button" class="btn btn-warning btn-remove-membership" id=' + data.UID + '>Deactivate Account</button>'
                    },
                }]
            });
            btn_app_ads = true;
        }
    });

    let btn_exp_upgrade_ads = false;
    $('#btn-exp-upgrade-ads').click(function() {
        if (!btn_app_ads) {
            let tbl_exp_upgrade_ads = $('#tbl_exp_upgrade_ads').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,

                "order": [[ 3, "desc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                ajax: url + "/ads/manage/exp-upgrade-ad-data",
                columns: [{
                    data: 'username',
                    width: "20%",
                    name: 'username',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'Uemail',
                    width: "20%",
                    name: 'users.Uemail',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'expiry',
                    name: 'expiry',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'payment_exp_date',
                    name: 'payment_exp_date',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'category',
                    name: 'category',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'am',
                    name: 'am',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        return '<a target="_blank" href="'+ url +'/view/list/ads/by/customer?uid=' + data.UID + '">View</a>'
                    },
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        return '<button type="button" class="btn btn-warning btn-remove-membership" id=' + data.UID + '>Deactivate Account</button>'
                    },
                }]
            });
            btn_exp_upgrade_ads = true;
        }
    });

});
