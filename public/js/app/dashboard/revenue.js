$(document).ready(function() {

    $("#flash_message").css("display", "none");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#payment_expiry_date").datetimepicker({

        showOn: "button",
        showSecond: false,
        minView: 2,
        format: "yyyy-mm-dd",
        //dateFormat: "yy-mm-dd",
        showTimepicker: false,
        autoclose: true,
        todayBtn: true
    });

    var user_member_details_table = $('#users-table').DataTable({
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
        ajax: url + "/dashboard/pending_payment/data",
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
            "searchable": false,
            "orderable": false,
        }, {
            data: 'ads_count',
            name: 'users.ads_count',
            "searchable": false,

        }, {
            data: null,
            "orderable": false,
            name: 'users.last_updated_at',
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
            "searchable": false,
            "orderable": false,
        }, {
            data: 'expiry',
            width: "10%",
            name: 'admin_members.expiry',
            "searchable": false,
            "orderable": false,
        }, {
            data: 'payment_expire',
            width: "10%",
            name: 'admin_members.payment_expire',
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
                //return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#my Modal" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
                return '<button type="button" class="btn btn-warning btn-edit-memship" data-toggle="modal" id=' + data.UID + ' data-target="#membershipModal" onclick="OpenMembershipModal()" data-m_expire="' + data.expiry + '" data-p_expire="' + data.payment_expire + '">Edit</button>'
            },
            //defaultContent: '<button type="button" class="btn btn-warning btn-view-pdf" data-toggle="modal" id='+data.firstname+' data-target="#my Modal">Edit</button>'
        }]
    });

    var btn_paid_clicked = false;

    $('#btn-paid').click(function(){
        if (btn_paid_clicked == false) {

            $('#paid-table').DataTable({
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
                ajax: url + "/dashboard/revenue",
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
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'ads_count',
                    name: 'users.ads_count',
                    "searchable": false,

                }, {
                    data: null,
                    name: 'users.last_updated_at',
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
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'expiry',
                    width: "10%",
                    name: 'admin_members.expiry',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'payment_expire',
                    width: "10%",
                    name: 'admin_members.payment_expire',
                    "searchable": false,
                    "orderable": false,
                }, {
                    data: 'am',
                    name: 'admin_members.am',
                    "searchable": true,
                    "orderable": false,
                }]
            });

            btn_paid_clicked = true;
        }
        
    });

    $(document).on("click", ".btn-edit-memship", function(e) { //user click on edit button
        e.preventDefault();
        user_id = $(this).attr("id");
        m_expire = $(this).data("m_expire");
        p_expire = $(this).data("p_expire");
        $('#payment_expiry_date').val(p_expire);
        $('#memship_uid').val(user_id);
        $('#memship_expiry').html(m_expire.split(' ')[0]);
    });  

    $(document).on("click", "#memship_submit", function(e) {
        
        e.preventDefault();
        memship_uid = $("#memship_uid").val();
        payment_expiry_date = $("#payment_expiry_date").val();
        m_expire = $("#memship_expiry").html();
        amount = $("#amount").val();

        var start = new Date(payment_expiry_date);
        var end = new Date(m_expire);
        if (start > end) {
            alert("Payment expire date can't exceed membership expire date.")
            return false;
        }

        $.ajax({
            type: "post",
            url: url + "/payment/expire/save",
            //dataType: 'json',
            //headers: {'X-CSRF-Token': token},
            //data: 'fromDate='+from_date+'&toDate='+to_date+'&adults='+adults+'&kids='+kids+'&ageString='+age_string,
            data: {
                "uid": memship_uid, "payment_exp": payment_expiry_date, "amount": amount,
            },
        }).done(function(success) {
            $('#myModal').modal('toggle');
            $("#flash_message").css("display", "block");
            if (success == "true") {
                $("#flash_message").html("Payment expire date was updated successfully");
                $("#flash_message").addClass("alert-success");
                $("#flash_message").show().delay(5000).fadeOut();
                //$('#users-table').DataTable().ajax.reload();
                user_member_details_table.draw("full-hold");
            } else {
                $("#flash_message").html("Sorry! ,Payment expire date was not updated successfully");
                $("#flash_message").addClass("alert-danger");
                $("#flash_message").show().delay(5000).fadeOut();
            }

        });

    });

});
function OpenMembershipModal(){
    $('body').css('overflow', 'hidden');
}
