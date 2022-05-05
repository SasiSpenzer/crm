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
        ajax: url + "/member/expire/tobe-expire/today",
        columns: [{
            data: null,
            width: "20%",
            render: function(data, type, row) {
                return data.firstname + ' ' + data.surname;
            },
            "searchable": false,
            "orderable": false,
        }, {
            data: 'Uemail',
            width: "20%",
            name: 'users.Uemail',
            "searchable": true,
            "orderable": false,
        }, {
            data: 'ads_count',
            name: 'users.ads_count',
            "searchable": false,

        }, {
            data: null,
            "orderable": false,
            name: 'last_updated_at',
            "orderable": true,
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
            name: 'admin_members.category',
            "searchable": true,
            "orderable": false,
        }, {
            data: 'expiry',
            width: "10%",
            name: 'admin_members.expiry',
            "searchable": false,
            "orderable": false,
        }, {
            data: 'am',
            name: 'admin_members.am',
            "searchable": true,
            "orderable": false,
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

    var btn_week_clicked = false;

    $('#btn-week').click(function(){

        if (!btn_week_clicked){

            $('#tbl-week').DataTable({
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
                ajax: url + "/member/expire/tobe-expire/one-week-before",
                columns: [{
                    data: null,
                    width: "20%",
                    render: function(data, type, row) {
                        return data.firstname + ' ' + data.surname;
                    },
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'Uemail',
                    width: "20%",
                    name: 'users.Uemail',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: 'ads_count',
                    name: 'users.ads_count',
                    "searchable": false,

                }, {
                    data: null,
                    "orderable": false,
                    name: 'last_updated_at',
                    "orderable": true,
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
                    name: 'admin_members.category',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: 'expiry',
                    width: "10%",
                    name: 'admin_members.expiry',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'am',
                    name: 'admin_members.am',
                    "searchable": true,
                    "orderable": false,
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

            btn_week_clicked = true;
        }
    });

    var btn_month_clicked = false;

    $('#btn-month').click(function(){

        if (!btn_month_clicked){

            $('#tbl-month').DataTable({
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
                ajax: url + "/member/expire/tobe-expire/one-month-before",
                columns: [{
                    data: null,
                    width: "20%",
                    render: function(data, type, row) {
                        return data.firstname + ' ' + data.surname;
                    },
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'Uemail',
                    width: "20%",
                    name: 'users.Uemail',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: 'ads_count',
                    name: 'users.ads_count',
                    "searchable": false,

                }, {
                    data: null,
                    "orderable": false,
                    name: 'last_updated_at',
                    "orderable": true,
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
                    name: 'admin_members.category',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: 'expiry',
                    width: "10%",
                    name: 'admin_members.expiry',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'am',
                    name: 'admin_members.am',
                    "searchable": true,
                    "orderable": false,
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

            btn_month_clicked = true;
        }
    });

});
function OpenMembershipModal(){
    $('body').css('overflow', 'hidden');
}
