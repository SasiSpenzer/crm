$(document).ready(function() {

    $("#flash_message").css("display", "none");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let user_member_details_table = $('#users-table-all').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: true,
        "order": [[ 2, "desc" ]],
        "paging": true,
        "lengthMenu": [50, 100],
        pagingType: "simple_numbers",
        /*ajax: url + "/member/data",*/
        "ajax": {
            "url": url + "/member/data",
            data: {
                "type": 0,
            },
            "type": "GET",
        },
        columns: [{
            data: 'full_name',
            width: "20%",
            name: 'full_name',
            "searchable": true,
            "orderable": true,
        }, {
            data: 'Uemail',
            width: "20%",
            name: 'Uemail',
            "searchable": true,
            "orderable": true,
        }, {
            data: 'ads_count',
            name: 'ads_count',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'last_updated_at',
            name: 'last_updated_at',
            "searchable": false,
            "orderable": true,
        }, /*{
            data: null,
            "orderable": false,
            "searchable": false,
            name: 'last_updated_at',
            render: function(data, type, row) {
                var lastUpdatedDate = new Date(data.last_updated_at);

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
            },
        },*/ {
            data: 'category',
            name: 'category',
            defaultContent: '',
            "searchable": true,
            "orderable": true,
        }, {
            data: 'payment_exp_date',
            width: "10%",
            defaultContent: '',
            name: 'payment_exp_date',
            "searchable": false,
            "orderable": true,
        }, {
            data: 'am',
            name: 'am',
            defaultContent: '',
            "searchable": true,
            "orderable": true,
        }, {
            data: null,
            className: "center",
            "searchable": false,
            "orderable": false,
            render: function(data, type, row) {
                return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#membershipModal" onclick="OpenMembershipModal()">Edit</button>'
            },
            //defaultContent: '<button type="button" class="btn btn-warning btn-view-pdf" data-toggle="modal" id='+data.firstname+' data-target="#my Modal">Edit</button>'
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

    let btn_grace_period = false;
    $('#btn-grace-period').click(function() {

        if (!btn_grace_period) {
            let user_member_details_table1 = $('#users-table-grace-period').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: true,
                deferRender: true,
                searchDelay: 1000,
                "order": [[ 3, "desc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                /*ajax: url + "/member/data",*/
                "ajax": {
                    "url": url + "/member/data",
                    data: {
                        "type": 1,
                    },
                    "type": "GET",
                },
                columns: [{
                    data: 'full_name',
                    width: "20%",
                    name: 'full_name',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: 'Uemail',
                    width: "20%",
                    name: 'Uemail',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'ads_count',
                    name: 'ads_count',
                    "searchable": false,
                    "orderable": true,
                }, /*{
            data: 'last_updated_at',
            name: 'admin_members.last_updated_at',
            "searchable": false,
            "orderable": true,
        },*/ {
                    data: null,
                    "orderable": false,
                    "searchable": false,
                    name: 'last_updated_at',
                    render: function(data, type, row) {
                        var lastUpdatedDate = new Date(data.last_updated_at);

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
                    },
                }, {
                    data: 'category',
                    name: 'category',
                    defaultContent: '',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'payment_exp_date',
                    width: "10%",
                    defaultContent: '',
                    name: 'payment_exp_date',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'am',
                    name: 'am',
                    defaultContent: '',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#membershipModal" onclick="OpenMembershipModal()">Edit</button>'
                    },
                    //defaultContent: '<button type="button" class="btn btn-warning btn-view-pdf" data-toggle="modal" id='+data.firstname+' data-target="#my Modal">Edit</button>'
                }]
            });
            btn_grace_period = true;
        }
    });

    let btn_deactivated = false;
    $('#btn-deactivated').click(function() {

        if (!btn_deactivated) {
            let user_member_details_table2 = $('#users-table-deactivated').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: true,
                deferRender: true,
                searchDelay: 1000,
                "order": [[ 3, "desc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                /*ajax: url + "/member/data",*/
                "ajax": {
                    "url": url + "/member/data",
                    data: {
                        "type": 2,
                    },
                    "type": "GET",
                },
                columns: [{
                    data: 'full_name',
                    width: "20%",
                    name: 'full_name',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: 'Uemail',
                    width: "20%",
                    name: 'Uemail',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'ads_count',
                    name: 'ads_count',
                    "searchable": false,
                    "orderable": true,
                }, /*{
            data: 'last_updated_at',
            name: 'admin_members.last_updated_at',
            "searchable": false,
            "orderable": true,
        },*/ {
                    data: null,
                    "orderable": false,
                    "searchable": false,
                    name: 'last_updated_at',
                    render: function(data, type, row) {
                        var lastUpdatedDate = new Date(data.last_updated_at);

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
                    },
                }, {
                    data: 'category',
                    name: 'category',
                    defaultContent: '',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'payment_exp_date',
                    width: "10%",
                    defaultContent: '',
                    name: 'payment_exp_date',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'am',
                    name: 'am',
                    defaultContent: '',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#membershipModal" onclick="OpenMembershipModal()">Edit</button>'
                    },
                    //defaultContent: '<button type="button" class="btn btn-warning btn-view-pdf" data-toggle="modal" id='+data.firstname+' data-target="#my Modal">Edit</button>'
                }]
            });
            btn_deactivated= true;
        }
    });

    let btn_expired = false;
    $('#btn-expired').click(function() {

        if (!btn_expired) {
            let user_member_details_table3 = $('#users-table-expired').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: true,
                deferRender: true,
                searchDelay: 1000,
                "order": [[ 3, "desc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                /*ajax: url + "/member/data",*/
                "ajax": {
                    "url": url + "/member/data",
                    data: {
                        "type": 3,
                    },
                    "type": "GET",
                },
                columns: [{
                    data: 'full_name',
                    width: "20%",
                    name: 'full_name',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: 'Uemail',
                    width: "20%",
                    name: 'Uemail',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'ads_count',
                    name: 'ads_count',
                    "searchable": false,
                    "orderable": true,
                }, /*{
            data: 'last_updated_at',
            name: 'admin_members.last_updated_at',
            "searchable": false,
            "orderable": true,
        },*/ {
                    data: null,
                    "orderable": false,
                    "searchable": false,
                    name: 'last_updated_at',
                    render: function(data, type, row) {
                        var lastUpdatedDate = new Date(data.last_updated_at);

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
                    },
                }, {
                    data: 'category',
                    name: 'category',
                    defaultContent: '',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'payment_exp_date',
                    width: "10%",
                    defaultContent: '',
                    name: 'payment_exp_date',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'am',
                    name: 'am',
                    defaultContent: '',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#membershipModal" onclick="OpenMembershipModal()">Edit</button>'
                    },
                    //defaultContent: '<button type="button" class="btn btn-warning btn-view-pdf" data-toggle="modal" id='+data.firstname+' data-target="#my Modal">Edit</button>'
                }]
            });
            btn_expired= true;
        }
    });

    let btn_unallocated = false;
    $('#btn-unallocated').click(function() {

        if (!btn_unallocated) {
            let user_member_details_table3 = $('#users-table-unallocated').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: true,
                deferRender: true,
                searchDelay: 1000,
                "order": [[ 3, "desc" ]],
                "paging": true,
                "lengthMenu": [50, 100],
                pagingType: "simple_numbers",
                /*ajax: url + "/member/data",*/
                "ajax": {
                    "url": url + "/member/data",
                    data: {
                        "type": 4,
                    },
                    "type": "GET",
                },
                columns: [{
                    data: 'full_name',
                    width: "20%",
                    name: 'full_name',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: 'Uemail',
                    width: "20%",
                    name: 'Uemail',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'ads_count',
                    name: 'ads_count',
                    "searchable": false,
                    "orderable": true,
                }, /*{
            data: 'last_updated_at',
            name: 'admin_members.last_updated_at',
            "searchable": false,
            "orderable": true,
        },*/ {
                    data: null,
                    "orderable": false,
                    "searchable": false,
                    name: 'last_updated_at',
                    render: function(data, type, row) {
                        var lastUpdatedDate = new Date(data.last_updated_at);

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
                    },
                }, {
                    data: 'category',
                    name: 'category',
                    defaultContent: '',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: 'payment_exp_date',
                    width: "10%",
                    defaultContent: '',
                    name: 'payment_exp_date',
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: 'am',
                    name: 'am',
                    defaultContent: '',
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: null,
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#membershipModal" onclick="OpenMembershipModal()">Edit</button>'
                    },
                    //defaultContent: '<button type="button" class="btn btn-warning btn-view-pdf" data-toggle="modal" id='+data.firstname+' data-target="#my Modal">Edit</button>'
                }]
            });
            btn_unallocated= true;
        }
    });

});
function OpenMembershipModal(){
    $('body').css('overflow', 'hidden');
}
