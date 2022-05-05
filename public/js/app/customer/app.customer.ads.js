$(document).ready(function() {

    let email_address = $("#search_email").val();
    if(email_address != '' && email_address != null) {
        //$("#list-ads-by-email-div").css("display", "none");
        //$("#save-ads").css("display", "none");
        getDataTable();
    } else {
        $("#list-ads-by-email-div").css("display", "none");
        $("#list-ads").css("display", "none");
        $("#save-ads").css("display", "none");
    }


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#search_email").autocomplete({
        source: function(request, response) {
            if (request.term.includes("@")) {
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
            }

        },
        min_length: 3,

    });

    $(document).on("click", "#list-ads", function(e) {
        let email = $("#search_email").val();
        //check_max_ad_exceed(email);
        getDataTable();
    });

    function getDataTable() {
        $("#list-ads-by-email-div").css("display", "block");
        $("#save-ads").css("display", "block");
        $("#list-ads").removeClass().addClass("btn btn-default btn-success");

        var email = $("#search_email").val();
        if (email != "") {
            $.ajax({
                type: "GET",
                url: url + "/customer/ad/count",
                data: {
                    "email": email,
                },
            }).done(function (member) {
                $("#total_ad_count").val(member.max_ad_count);
                $("#max_ads_count").val(member.max_ad_count);
                $("#used_ad_count").val(member.use_ad_count);
            });
            var list_ads_by_email_table = $('#list-ads-by-email').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                responsive: true,
                "paging": true,
                "lengthMenu": [
                    [400, 500, 1000, -1],
                    [400, 500, 1000, "All"]
                ],
                iDisplayLength: -1,
                fixedColumns: true,
                columnDefs: [
                    { "width": "1%", "targets": 0 }
                ],
                pagingType: "simple_numbers",
                "ajax": {
                    "url": url + "/list/ads/by/customer",

                    data: {
                        "email": email,
                    },
                    "type": "GET",

                },
                "createdRow": function( row, data, dataIndex){
                    $(row).addClass(data.class);
                },
                columns: [{
                    data: null,
                    width: "1%",
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        if (data.is_active == "1") {
                            return '<div class="checkbox center"><input type="checkbox" value="' + data.ad_id + '" class ="activate" checked></div>';
                        } else {
                            return '<div class="checkbox center"><input type="checkbox" value="' + data.ad_id + '" class ="activate"></div>';
                        }

                    },
                }, {
                    data: null,
                    width: "2%",
                    className: "center",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        let page_ref = '';
                        let heading = '';
                        if(data.agent_page_ref != null && data.agent_page_ref != '') {
                            page_ref = data.agent_page_ref .replace(/ /g, "-");
                            page_ref = data.agent_page_ref .replace(/_/g, "-");
                            page_ref = data.agent_page_ref .replace(/&/g, "-");
                        }
                        if(data.heading != null && data.heading != '') {
                            heading = data.heading .replace(/ /g, "-");
                            heading = data.heading .replace(/_/g, "-");
                            heading = data.heading .replace(/&/g, "-");
                        }
                        data.heading = data.heading.substring(0,25);

                        if (data.type == 'sales') {
                            if (data.is_active == 1) {
                                return '<a target="_blank" href="https://www.lankapropertyweb.com/sale/property_details-' + data.ad_id + '.html">' + data.heading + '</a>';
                            } else {
                                return '<a target="_blank" href="https://www.lankapropertyweb.com/sale/property_details.php?prop=' + data.ad_id + '&pvtview=1">' + data.heading + '</a>';
                            }
                        } else if (data.type == 'rentals') {
                            if (data.is_active == 1) {
                                return '<a target="_blank" href="https://www.lankapropertyweb.com/rentals/property_details-' + data.ad_id + '.html">' + data.heading + '</a>';
                            } else {
                                return '<a target="_blank" href="https://www.lankapropertyweb.com/rentals/property_details.php?prop=' + data.ad_id + '&pvtview=1">' + data.heading + '</a>';
                            }
                        } else if (data.type == 'land') {
                            if (data.is_active == 1) {
                                return '<a target="_blank" href="https://www.lankapropertyweb.com/land/property_details-' + data.ad_id + '.html">' + data.heading + '</a>';
                            } else {
                                return '<a target="_blank" href="https://www.lankapropertyweb.com/land/property_details.php?prop=' + data.ad_id + '&pvtview=1">' + data.heading + '</a>';
                            }
                        } else if (data.type == 'wanted') {
                            if (data.is_active == 1) {
                                return '<a target="_blank" href="https://www.lankapropertyweb.com/wanted/property_details-' + data.ad_id + '.html">' + data.heading + '</a>';
                            } else {
                                return '<a target="_blank" href="https://www.lankapropertyweb.com/wanted/property_details.php?prop=' + data.ad_id + '&pvtview=1">' + data.heading + '</a>';
                            }
                        } else if (data.type == 'idealhome') {
                            if (data.is_active == 1) {
                                return '<a target="_blank" href="https://idealhome.lk/details' + heading + '-' + data.ad_id + '">' + data.heading + '</a>';
                            } else {
                                return '<a target="_blank" href="https://idealhome.lk/details.php?heading=' + heading + '&id=' + data.ad_id + '&pvtview=1">' + data.heading + '</a>';
                            }
                        } else if (data.type == 'agents' && data.propty_type == 'agents') {
                            if (data.is_active == 1) {
                                return '<a target="_blank" href="https://www.lankapropertyweb.com/agents/' + page_ref + '">' + data.heading + '</a>';
                            } else {
                                return '<a target="_blank" href="https://www.lankapropertyweb.com/agents/agents_details.php?name=' + page_ref + '&pvtview=1">' + data.heading + '</a>';
                            }
                        } else {
                            if (data.is_active == 1) {
                                return '<a target="_blank" href="https://www.lankapropertyweb.com/services/property_details-' + data.ad_id + '.html">' + data.heading + '</a>';
                            } else {
                                return '<a target="_blank" href="https://www.lankapropertyweb.com/services/property_details.php?prop=' + data.ad_id + '&pvtview=1">' + data.heading + '</a>';
                            }
                        }
                    },

                }, {
                    data: 'propty_type',
                    width: "5%",
                    name: 'propty_type',
                    "searchable": true,
                    "orderable": false,
                }, {
                    data: 'ad_id',
                    name: 'ad_id',
                    width: "1%",
                    "searchable": true,
                    "orderable": true,
                }, {
                    data: null,
                    name: 'ad_views',
                    width: "1%",
                    "searchable": false,
                    "orderable": true,
                    render: function(data, type, row) {
                        var ad_views = parseInt(data.this_with_last_views);
                        var total = parseInt(data.last_month_total_market_views);
                        var last_month_viewss = parseInt(data.last_month_views);
                        if (!total){
                            total = 0;
                        }
                        if(!ad_views){
                            if(last_month_viewss){
                                ad_views = last_month_viewss;
                            } else {
                                ad_views = 0;
                            }

                        }
                        if(!last_month_viewss){
                            last_month_viewss = 0;
                        }

                        if((ad_views != 0) && (total != 0)){
                            var precentage_views = ad_views / total * 100 ;
                            precentage_views = precentage_views ? precentage_views :0;

                            precentage_views = precentage_views.toFixed(2);
                        } else {
                            var precentage_views = total ;
                        }


                        if((ad_views != 0) && (total != 0)){
                            return ad_views + "</br><font color='#EC971F' style='font-size: smaller;'>("+ precentage_views+"%)</font>";
                        } else {

                            return ad_views + "</br><font color='#EC971F' style='font-size: smaller;'>("+Math.round(precentage_views)+")</font>";
                        }

                    },
                },{
                    data: null,
                    name: 'ad_leads',
                    width: "3%",
                    "searchable": false,
                    "orderable": true,
                    render: function(data, type, row) {
                        var ad_leads = parseInt(data.ad_leads);
                        var total = parseInt(data.last_month_leads);
                        if (!total){
                            total = 0;
                        }
                        if (!ad_leads){
                            ad_leads = 0;
                        }
                        if((total != 0) && (ad_leads != 0)){

                            var precentage = ad_leads / total * 100 ;
                            precentage = precentage ? precentage :0;
                            precentage = precentage.toFixed(2);

                            if(precentage > 1){
                            } else {
                                precentage = total;
                            }
                        } else {
                            var precentage = total;
                        }
                        if((total != 0) && (ad_leads != 0)){
                            return ad_leads + "</br><font color='#EC971F' style='font-size: smaller;'>("+Math.round(precentage)+"%)</font>";
                        } else {
                            return ad_leads + "</br><font color='#EC971F' style='font-size: smaller;'>("+Math.round(precentage)+")</font>";
                        }

                    },
                },{
                    data: null,
                    name: 'current_page_id',
                    "searchable": false,
                    width: "3%",
                    "orderable": true,
                    render: function(data, type, row) {
                        if(data.is_active == 1){
                            var mpID = parseInt(data.current_page_id);
                            var loss = getRange(mpID);
                            return data.current_page_id + "</br><font color='#EC971F' style='font-size: smaller;'>("+loss+" loss)</font>";
                        } else {
                            return     "<font color='black' style='font-size: smaller;'>Inactive Ad</font>";
                        }

                    },
                },{
                    data: null,
                    name: 'price_comparison',
                    "searchable": true,
                    width: "5%",
                    "orderable": true,
                    render: function(data, type, row) {
                        if((data.price_comparison != '') && (data.price_comparison != 'NULL')){

                            var type = data.type;
                            var price = '';
                            var compPrice = '';
                            if(type == 'land'){
                                 price = data.price_land_pp;
                                compPrice = data.price_land_pp;

                            } else if (type == 'sales') {
                                 price = data.price_sqft;
                                 compPrice = data.price_sqft;
                            } else if(type == 'rentals') {
                                 price = data.price_sqft;
                                 compPrice = data.price_sqft;
                            }
                            var pricebefore = compPrice;
                            if(price){

                            }
                            else {
                                price = 0;
                            }
                            if(price > 100000){
                                price = convertToInternationalCurrencySystem(price);
                            }
                            else {
                                price = numberWithCommas(price);
                            }

                            var avg_price = data.price_comparison ;
                            var price_type = data.price_type ;
                            if(!price_type){
                                if(type == 'land'){
                                    price_type = 'pp';
                                } else if (type == 'rentals') {
                                    price_type = 'psf';
                                } else {
                                    price_type = 'psf';
                                }
                            }
                            if(avg_price > 0){
                                var diff = pricebefore - avg_price;
                                //console.log(price+'--'+avg_price);
                                var avg_price_converted =  avg_price;

                                if(diff > 0){
                                    //var diff  = Math.abs(price - avg_price) ;
                                    var precentage = Math.round(diff/data.price_comparison * 100) ;
                                    precentage = Math.round(precentage);
                                    if(precentage <= 0){
                                        precentage = 0;
                                    }

                                    var makeavag = convertToInternationalCurrencySystem(avg_price);
                                    if(price){
                                        var message = ""+""+price+" "+price_type+" <font style='color: red;font-size: smaller;'>("+precentage+"% higher)</font> </br><font style='color: orange;font-size: smaller;'>AVG Price : "+ makeavag +"</font>";
                                    } else {
                                        var message = "<font style='color: black;font-size: smaller;'>(No data)</font> </br><font style='color: orange;font-size: smaller;'>AVG Price : "+ makeavag +"</font>";
                                    }

                                } else {
                                    diff = Math.abs(diff) ;
                                    var precentage = diff/data.price_comparison * 100 ;
                                    precentage = Math.round(precentage);
                                    if(precentage <= 0){
                                        precentage = 0;
                                    }
                                    avg_price = Math.round(avg_price);
                                    var makeavag = convertToInternationalCurrencySystem(avg_price);

                                    if(price){
                                        var message = ""+""+price+" "+price_type+" <font style='color: lime;font-size: smaller;'>("+precentage+"% lower)</font></br> <font style='color: orange;font-size: smaller;'>AVG Price : "+ makeavag +"</font>";
                                    } else {
                                        var message = "<font style='color: black;font-size: smaller;'>(No data)</font> </br><font style='color: orange;font-size: smaller;'>AVG Price : "+ makeavag +"</font>";
                                    }

                                }
                            }
                            else {
                                if(price){
                                    var message = ""+""+price+" "+price_type+" <font style='color: orange;font-size: smaller;'>(Not Available)</font>";
                                } else {
                                    var message = "(No data)<font style='color: orange;font-size: smaller;'>(Not Available)</font>";
                                }

                            }

                        }else {
                            var message = "Data unavailable!";
                        }
                        return message;
                    },

                },{
                    data: null,
                    name: 'priority',
                    width: "5%",
                    "searchable": false,
                    "orderable": true,
                    render: function(data, type, row) {
                        if(data.is_showcase == 1){
                            return 'Showcase Property';
                        } else {
                            return data.priority;
                        }
                    },
                },{
                    data: 'boosted_count',
                    name: 'boosted_count',
                    width: "3%",
                    "searchable": false,
                    "orderable": true,
                }, {
                    data: null,
                    "orderable": false,
                    width: "5%",
                    name: 'adverts.submit_date',
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
                    data: null,
                    "orderable": false,
                    width: "5%",
                    name: 'adverts.posted_date ',
                    render: function(data, type, row) {
                        var lastUpdatedDate = new Date(data.posted_date);
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
                    data: null,
                    className: "center",
                    width: "1%",
                    "searchable": false,
                    "orderable": false,
                    render: function(data, type, row) {
                        if (data.is_active == 1) {
                            return '<div id="' + data.ad_id + '"><button id="btn' + data.ad_id + '" type="button" class="btn btn-success btn-circle"><i class="fa fa-check" id="i' + data.ad_id + '"></i></button><div>';
                        } else if(data.is_active == 2) {
                            return '<div id="' + data.ad_id + '"><button id="btn' + data.ad_id + '" type="button" class="btn btn-warning btn-circle"><i class="fa fa-check" id="i' + data.ad_id + '"></i></button><div>';
                        }else {
                            return '<div id="' + data.ad_id + '"><button id="btn' + data.ad_id + '" type="button" class="btn btn-danger btn-circle"><i class="fa fa-times" id="i' + data.ad_id + '"></i></button><div>';
                        }

                    },
                }],
            });
        }
    }

    $(document).on("click", "#save-ads", function(e) {
        let ad_id;
        let is_checked;
        let max_count = parseInt($("#max_ads_count").val());
        let count = 0;
        let checked = parseInt(clickCheckBox());
        if(checked == 1) {
            $('.activate').each(function () {
                ad_id = $(this).attr("value");
                if (ad_id != null && ad_id != '' && parseInt(ad_id) > 0) {
                    if ($(this).prop('checked') == true) {
                        is_checked = true;
                        count += 1;
                    } else {
                        is_checked = false;
                    }
                    if (count <= max_count || parseInt(max_count) == -1) {
                        $.ajax({
                            url: url + "/activate/ad",
                            dataType: "json",
                            data: {
                                ad_id: ad_id,
                                is_checked: is_checked
                            },
                            "type": "POST",

                        }).done(function (member) {
                            if (member.is_active == "1") {
                                $("#btn" + member.ad_id).removeClass().addClass("btn btn-success btn-circle");
                                $("#i" + member.ad_id).removeClass().addClass("fa fa-check");
                            } else if (member.is_active == "0") {
                                $("#btn" + member.ad_id).removeClass().addClass("btn btn-danger btn-circle");
                                $("#i" + member.ad_id).removeClass().addClass("fa fa-times");
                            } else if (member.is_active == "-1") {
                                $("#flash_message").addClass("alert-danger");
                                $("#flash_message").css("display", "block");
                                $("#flash_message").html("Can't activate this ad. Because this user not member.");
                                $("#flash_message").show().delay(5000).fadeOut();
                                //return false;
                            }
                        });
                    } else {
                        alert("Maximum active ad count exceed. This user can only activate " + max_count + " ads.");
                        return false;
                    }
                }
            });
            getDataTable();
        }
    });
    $(document).on("click", "#boost-ads", function(e) {
        let ad_id;
        let is_checked;
        let max_count = parseInt($("#max_ads_count").val());
        let count = 0;
        let checked = parseInt(clickCheckBox());
        $.ajax({
                            url: url + "/autoboost/ad",
                            dataType: "json",
                            data: {
                                ad_id: ad_id,
                                is_checked: is_checked
                            },
                            "type": "POST",

                        });
        
    });

    $(document).on("click", "#select-all", function(e) {
        if ($(this).prop('checked') == true) {
            $('.activate').each(function() { //iterate all listed checkbox items
                $(this).prop('checked', true); //change ".activate" checked status

            });
        } else {
            $('.activate').each(function() { //iterate all listed checkbox items
                $(this).prop('checked', false); //change ".activate" checked status

            });
        }

    });

    function clickCheckBox() {
        let max_count = parseInt($("#total_ad_count").val());
        let used_count = 0;
        let output = 1;
        $('.activate:checked').each(function(){
            used_count += 1;
        });
        $("#used_ad_count").val(used_count);
        if(max_count >= 0) {
            if(max_count < used_count ) {
                alert("Customer maximum select ad count exceed.!!(Max Count : " + max_count + " | Select Count : " + used_count + ")");
                output = 0;
            }
        }
        return output;
    }
    function numberWithCommas(x) {
        if(x){
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

    }
    function convertToInternationalCurrencySystem (labelValue) {

        // Nine Zeroes for Billions
        return Math.abs(Number(labelValue)) >= 1.0e+9

            ? (Math.abs(Number(labelValue)) / 1.0e+9).toFixed(2) + "B"
            // Six Zeroes for Millions
            : Math.abs(Number(labelValue)) >= 1.0e+6

                ? (Math.abs(Number(labelValue)) / 1.0e+6).toFixed(2) + "M"
                // Three Zeroes for Thousands
                : Math.abs(Number(labelValue)) >= 1.0e+3

                    ? (Math.abs(Number(labelValue)) / 1.0e+3).toFixed(2) + "K"

                    : Math.abs(Number(labelValue));

    }

});
