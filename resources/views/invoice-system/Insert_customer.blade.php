
<!DOCTYPE html>

<!--[if lt IE 7]>
<html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>
<html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>
<html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en"> <!--<![endif]-->

<head>
    <title>InvoicePlane</title>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="NOINDEX,NOFOLLOW">

<link rel="icon" type="image/png" href="http://accounts.srilankaproperty.lk/assets/core/img/favicon.png">

<link rel="stylesheet" href="http://accounts.srilankaproperty.lk/assets/invoiceplane/css/style.css?v=1.5.9">
<link rel="stylesheet" href="http://accounts.srilankaproperty.lk/assets/core/css/custom.css?v=1.5.9">


<!--[if lt IE 9]>
<script src="http://accounts.srilankaproperty.lk/assets/core/js/legacy.min.js?v=1.5.9"></script>
<![endif]-->

<script src="http://accounts.srilankaproperty.lk/assets/core/js/dependencies.min.js?v=1.5.9"></script>

<script>
    Dropzone.autoDiscover = false;


    $(function () {
        $('.nav-tabs').tab();
        $('.tip').tooltip();

        $('body').on('focus', '.datepicker', function () {
            $(this).datepicker({
                autoclose: true,
                format: 'mm/dd/yyyy',
                language: 'en',
                weekStart: '',
                todayBtn: "linked"
            });
        });

        $(document).on('click', '.create-invoice', function () {
            $('#modal-placeholder').load("http://accounts.srilankaproperty.lk/index.php/invoices/ajax/modal_create_invoice");
        });

        $(document).on('click', '.create-quote', function () {
            $('#modal-placeholder').load("http://accounts.srilankaproperty.lk/index.php/quotes/ajax/modal_create_quote");
        });

        $(document).on('click', '#btn_quote_to_invoice', function () {
            var quote_id = $(this).data('quote-id');
            $('#modal-placeholder').load("http://accounts.srilankaproperty.lk/index.php/quotes/ajax/modal_quote_to_invoice/" + quote_id);
        });

        $(document).on('click', '#btn_copy_invoice', function () {
            var invoice_id = $(this).data('invoice-id');
            $('#modal-placeholder').load("http://accounts.srilankaproperty.lk/index.php/invoices/ajax/modal_copy_invoice", {invoice_id: invoice_id});
        });

        $(document).on('click', '#btn_create_credit', function () {
            var invoice_id = $(this).data('invoice-id');
            $('#modal-placeholder').load("http://accounts.srilankaproperty.lk/index.php/invoices/ajax/modal_create_credit", {invoice_id: invoice_id});
        });

        $(document).on('click', '#btn_copy_quote', function () {
            var quote_id = $(this).data('quote-id');
            var client_id = $(this).data('client-id');
            $('#modal-placeholder').load("http://accounts.srilankaproperty.lk/index.php/quotes/ajax/modal_copy_quote", {
                quote_id: quote_id,
                client_id: client_id
            });
        });

        $(document).on('click', '.client-create-invoice', function () {
            var client_id = $(this).data('client-id');
            $('#modal-placeholder').load("http://accounts.srilankaproperty.lk/index.php/invoices/ajax/modal_create_invoice", {client_id: client_id});
        });

        $(document).on('click', '.client-create-quote', function () {
            var client_id = $(this).data('client-id');
            $('#modal-placeholder').load("http://accounts.srilankaproperty.lk/index.php/quotes/ajax/modal_create_quote", {client_id: client_id});
        });

        $(document).on('click', '.invoice-add-payment', function () {
            invoice_id = $(this).data('invoice-id');
            invoice_balance = $(this).data('invoice-balance');
            invoice_payment_method = $(this).data('invoice-payment-method');
            $('#modal-placeholder').load("http://accounts.srilankaproperty.lk/index.php/payments/ajax/modal_add_payment", {
                invoice_id: invoice_id,
                invoice_balance: invoice_balance,
                invoice_payment_method: invoice_payment_method
            });
        });

    });
</script>
</head>
<body class="hidden-sidebar">

<noscript>
    <div class="alert alert-danger no-margin">Please enable Javascript to use InvoicePlane</div>
</noscript>

<nav class="navbar navbar-inverse" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#ip-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                Menu &nbsp; <i class="fa fa-bars"></i>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="ip-navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="http://accounts.srilankaproperty.lk/index.php/dashboard" class="hidden-md">Dashboard</a>                    <a href="http://accounts.srilankaproperty.lk/index.php/dashboard" class="visible-md-inline-block"><i class="fa fa-dashboard"></i></a>                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-caret-down"></i> &nbsp;
                        <span class="hidden-md">Clients</span>
                        <i class="visible-md-inline fa fa-users"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/clients/form">Add Client</a></li>
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/clients/index">View Clients</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-caret-down"></i> &nbsp;
                        <span class="hidden-md">Quotes</span>
                        <i class="visible-md-inline fa fa-file"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="#" class="create-quote">Create Quote</a></li>
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/quotes/index">View Quotes</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-caret-down"></i> &nbsp;
                        <span class="hidden-md">Invoices</span>
                        <i class="visible-md-inline fa fa-file-text"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="#" class="create-invoice">Create Invoice</a></li>
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/invoices/index">View Invoices</a></li>
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/invoices/recurring/index">View Recurring Invoices</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-caret-down"></i> &nbsp;
                        <span class="hidden-md">Payments</span>
                        <i class="visible-md-inline fa fa-credit-card"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/payments/form">Enter Payment</a></li>
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/payments/index">View Payments</a></li>
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/payments/online_logs">View Online Payment Logs</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-caret-down"></i> &nbsp;
                        <span class="hidden-md">Products</span>
                        <i class="visible-md-inline fa fa-database"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/products/form">Create product</a></li>
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/products/index">View products</a></li>
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/families/index">Product families</a></li>
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/units/index">Product Units</a></li>
                    </ul>
                </li>

                <li class="dropdown 1">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-caret-down"></i> &nbsp;
                        <span class="hidden-md">Tasks</span>
                        <i class="visible-md-inline fa fa-check-square-o"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/tasks/form">Create task</a></li>
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/tasks/index">Show tasks</a></li>
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/projects/index">Projects</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-caret-down"></i> &nbsp;
                        <span class="hidden-md">Reports</span>
                        <i class="visible-md-inline fa fa-bar-chart"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/reports/invoice_aging">Invoice Aging</a></li>
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/reports/payment_history">Payment History</a></li>
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/reports/sales_by_client">Sales by Client</a></li>
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/reports/sales_by_year">Sales by Date</a></li>
                    </ul>
                </li>

            </ul>


            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="http://docs.invoiceplane.com/" target="_blank"
                       class="tip icon" title="Documentation"
                       data-placement="bottom">
                        <i class="fa fa-question-circle"></i>
                        <span class="visible-xs">&nbsp;Documentation</span>
                    </a>
                </li>

                <li class="dropdown">
                    <a href="#" class="tip icon dropdown-toggle" data-toggle="dropdown"
                       title="Settings"
                       data-placement="bottom">
                        <i class="fa fa-cogs"></i>
                        <span class="visible-xs">&nbsp;Settings</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/custom_fields/index">Custom Fields</a></li>
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/email_templates/index">Email Templates</a></li>
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/invoice_groups/index">Invoice Groups</a></li>
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/invoices/archive">Invoice Archive</a></li>
                        <!-- // temporarily disabled
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/item_lookups/index">Item Lookups</a></li>
                        -->
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/payment_methods/index">Payment Methods</a></li>
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/tax_rates/index">Tax Rates</a></li>
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/users/index">User Accounts</a></li>
                        <li class="divider hidden-xs hidden-sm"></li>
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/settings">System Settings</a></li>
                        <li><a href="http://accounts.srilankaproperty.lk/index.php/import">Import Data</a></li>
                    </ul>
                </li>
                <li>
                    <a href="http://accounts.srilankaproperty.lk/index.php/users/form/2"
                       class="tip icon" data-placement="bottom"
                       title="Sajith">
                        <i class="fa fa-user"></i>
                        <span class="visible-xs">&nbsp;Sajith</span>
                    </a>
                </li>
                <li>
                    <a href="http://accounts.srilankaproperty.lk/index.php/sessions/logout"
                       class="tip icon logout" data-placement="bottom"
                       title="Logout">
                        <i class="fa fa-power-off"></i>
                        <span class="visible-xs">&nbsp;Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div id="main-area">
        <div id="main-content">

<script>
    $(function () {
        $('.item-task-id').each(function () {
            // Disable client chaning if at least one item already has a task id assigned
            if ($(this).val().length > 0) {
                $('#invoice_change_client').hide();
                return false;
            }
        });

        $('.btn_add_product').click(function () {
            $('#modal-placeholder').load(
                "http://accounts.srilankaproperty.lk/index.php/products/ajax/modal_product_lookups/" + Math.floor(Math.random() * 1000)
            );
        });

        $('.btn_add_task').click(function () {
            $('#modal-placeholder').load(
                "http://accounts.srilankaproperty.lk/index.php/tasks/ajax/modal_task_lookups/1/" +
                Math.floor(Math.random() * 1000)
            );
        });

        $('.btn_add_row').click(function () {
            $('#new_row').clone().appendTo('#item_table').removeAttr('id').addClass('item').show();
        });


        $('#btn_create_recurring').click(function () {
            $('#modal-placeholder').load(
                "http://accounts.srilankaproperty.lk/index.php/invoices/ajax/modal_create_recurring",
                {
                    invoice_id: 1                }
            );
        });

        $('#invoice_change_client').click(function () {
            $('#modal-placeholder').load("http://accounts.srilankaproperty.lk/index.php/invoices/ajax/modal_change_client", {
                invoice_id: 1,
                client_id: "1",
            });
        });

        $('#btn_save_invoice').click(function () {
            var items = [];
            var item_order = 1;
            $('table tbody.item').each(function () {
                var row = {};
                $(this).find('input,select,textarea').each(function () {
                    if ($(this).is(':checkbox')) {
                        row[$(this).attr('name')] = $(this).is(':checked');
                    } else {
                        row[$(this).attr('name')] = $(this).val();
                    }
                });
                row['item_order'] = item_order;
                item_order++;
                items.push(row);
            });
            $.post("http://accounts.srilankaproperty.lk/index.php/invoices/ajax/save", {
                    invoice_id: 1,
                    invoice_number: $('#invoice_number').val(),
                    invoice_date_created: $('#invoice_date_created').val(),
                    invoice_date_due: $('#invoice_date_due').val(),
                    invoice_status_id: $('#invoice_status_id').val(),
                    invoice_password: $('#invoice_password').val(),
                    items: JSON.stringify(items),
                    invoice_discount_amount: $('#invoice_discount_amount').val(),
                    invoice_discount_percent: $('#invoice_discount_percent').val(),
                    invoice_terms: $('#invoice_terms').val(),
                    custom: $('input[name^=custom],select[name^=custom]').serializeArray(),
                    payment_method: $('#payment_method').val(),
                },
                function (data) {
                                        var response = JSON.parse(data);
                    if (response.success === 1) {
                        window.location = "http://accounts.srilankaproperty.lk/index.php/invoices/view/" + 1;
                    } else {
                        $('#fullpage-loader').hide();
                        $('.control-group').removeClass('has-error');
                        $('div.alert[class*="alert-"]').remove();
                        var resp_errors = response.validation_errors,
                            all_resp_errors = '';
                        for (var key in resp_errors) {
                            $('#' + key).parent().addClass('has-error');
                            all_resp_errors += resp_errors[key];
                        }
                        $('#invoice_form').prepend('<div class="alert alert-danger">' + all_resp_errors + '</div>');
                    }
                });
        });

        $('#btn_generate_pdf').click(function () {
            window.open('http://accounts.srilankaproperty.lk/index.php/invoices/generate_pdf/1', '_blank');
        });

        $(document).on('click', '.btn_delete_item', function () {
            var btn = $(this);
            var item_id = btn.data('item-id');

            // Just remove the row if no item ID is set (new row)
            if (typeof item_id === 'undefined') {
                $(this).parents('.item').remove();
            }

            $.post("http://accounts.srilankaproperty.lk/index.php/invoices/ajax/delete_item/1", {
                    'item_id': item_id,
                },
                function (data) {
                                        var response = JSON.parse(data);

                    if (response.success === 1) {
                        btn.parents('.item').remove();
                    } else {
                        btn.removeClass('btn-link').addClass('btn-danger').prop('disabled', true);
                    }
                });
        });

                var fixHelper = function (e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function (index) {
                $(this).width($originals.eq(index).width());
            });
            return $helper;
        };

        $('#item_table').sortable({
            items: 'tbody',
            helper: fixHelper,
        });

        if ($('#invoice_discount_percent').val().length > 0) {
            $('#invoice_discount_amount').prop('disabled', true);
        }

        if ($('#invoice_discount_amount').val().length > 0) {
            $('#invoice_discount_percent').prop('disabled', true);
        }

        $('#invoice_discount_amount').keyup(function () {
            if (this.value.length > 0) {
                $('#invoice_discount_percent').prop('disabled', true);
            } else {
                $('#invoice_discount_percent').prop('disabled', false);
            }
        });
        $('#invoice_discount_percent').keyup(function () {
            if (this.value.length > 0) {
                $('#invoice_discount_amount').prop('disabled', true);
            } else {
                $('#invoice_discount_amount').prop('disabled', false);
            }
        });


    });
</script>

<div id="delete-invoice" class="modal modal-lg" role="dialog" aria-labelledby="delete-invoice" aria-hidden="true">

    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
            <h4 class="panel-title">Delete Invoice</h4>
        </div>
        <div class="modal-body">

            <div class="alert alert-danger">If you delete this invoice you will not be able to recover it later. Are you sure you want to permanently delete this invoice?</div>

        </div>
        <div class="modal-footer">

            <form action="http://accounts.srilankaproperty.lk/index.php/invoices/delete/1"
                  method="POST">
                <input type="hidden" name="_ip_csrf" value="fe34a23a0b0f1562eb37df1c924fda4c">
                <div class="btn-group">
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-trash-o fa-margin"></i> Confirm deletion                    </button>
                    <a href="#" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-times"></i> Cancel                    </a>
                </div>
            </form>

        </div>
    </div>

</div>
<script>
    $(function () {
        // Select2 for all select inputs
        $(".simple-select").select2();

        $('#invoice_tax_submit').click(function () {
            $.post("http://accounts.srilankaproperty.lk/index.php/invoices/ajax/save_invoice_tax_rate", {
                    invoice_id: 1,
                    tax_rate_id: $('#tax_rate_id').val(),
                    include_item_tax: $('#include_item_tax').val()
                },
                function (data) {
                                        var response = JSON.parse(data);
                    if (response.success === 1) {
                        window.location = "http://accounts.srilankaproperty.lk/index.php/invoices/view/" + 1;
                    }
                });
        });
    });
</script>

<div id="add-invoice-tax" class="modal modal-lg" role="dialog" aria-labelledby="add-invoice-tax" aria-hidden="true">
    <form class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
            <h4 class="panel-title">Add Invoice Tax</h4>
        </div>
        <div class="modal-body">

            <div class="form-group">
                <label for="tax_rate_id">Invoice Tax Rate: </label>
                <select name="tax_rate_id" id="tax_rate_id" class="form-control simple-select">
                    <option value="0">None</option>
                                    </select>
            </div>

            <div class="form-group">
                <label for="include_item_tax">Tax Rate Placement</label>
                <select name="include_item_tax" id="include_item_tax" class="form-control simple-select">
                    <option value="0">Apply Before Item Tax</option>
                    <option value="1">Apply After Item Tax</option>
                </select>
            </div>

        </div>

        <div class="modal-footer">
            <div class="btn-group">
                <button class="btn btn-success" id="invoice_tax_submit" type="button">
                    <i class="fa fa-check"></i> Submit                </button>
                <button class="btn btn-danger" type="button" data-dismiss="modal">
                    <i class="fa fa-times"></i> Cancel                </button>
            </div>
        </div>

    </form>

</div>

<div id="headerbar">
    <h1 class="headerbar-title">
        Invoice #1    </h1>

    <div class="headerbar-item pull-right btn-group">

        <div class="options btn-group btn-group-sm">
            <a class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-caret-down no-margin"></i> Options            </a>
            <ul class="dropdown-menu">
                                    <li>
                        <a href="#add-invoice-tax" data-toggle="modal">
                            <i class="fa fa-plus fa-margin"></i> Add Invoice Tax                        </a>
                    </li>
                                <li>
                    <a href="#" id="btn_create_credit" data-invoice-id="1">
                        <i class="fa fa-minus fa-margin"></i> Create credit invoice                    </a>
                </li>
                                    <li>
                        <a href="#" class="invoice-add-payment"
                           data-invoice-id="1"
                           data-invoice-balance="50.00"
                           data-invoice-payment-method="0">
                            <i class="fa fa-credit-card fa-margin"></i>
                            Enter Payment                        </a>
                    </li>
                                <li>
                    <a href="#" id="btn_generate_pdf"
                       data-invoice-id="1">
                        <i class="fa fa-print fa-margin"></i>
                        Download PDF                    </a>
                </li>
                <li>
                    <a href="http://accounts.srilankaproperty.lk/index.php/mailer/invoice/1">
                        <i class="fa fa-send fa-margin"></i>
                        Send Email                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#" id="btn_create_recurring"
                       data-invoice-id="1">
                        <i class="fa fa-repeat fa-margin"></i>
                        Create Recurring                    </a>
                </li>
                <li>
                    <a href="#" id="btn_copy_invoice"
                       data-invoice-id="1">
                        <i class="fa fa-copy fa-margin"></i>
                        Copy Invoice                    </a>
                </li>
                                    <li>
                        <a href="#delete-invoice" data-toggle="modal">
                            <i class="fa fa-trash-o fa-margin"></i>
                            Delete                        </a>
                    </li>
                            </ul>
        </div>

                    <a href="#" class="btn btn-sm btn-success ajax-loader" id="btn_save_invoice">
                <i class="fa fa-check"></i> Save            </a>
            </div>

    <div class="headerbar-item invoice-labels pull-right">
                    </div>

</div>

<div id="content">


    <div id="invoice_form">
        <div class="invoice">

            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-5">

                    <h3>
                        <a href="http://accounts.srilankaproperty.lk/index.php/clients/view/1">
                            Roshan Kumara                        </a>
                                                    <span id="invoice_change_client" class="fa fa-edit cursor-pointer small"
                                  data-toggle="tooltip" data-placement="bottom"
                                  title="Change Client"></span>
                                            </h3>
                    <br>
                    <div class="client-address">

<span class="client-address-street-line">
    </span>
<span class="client-address-street-line">
    </span>
<span class="client-adress-town-line">
            </span>
<span class="client-adress-country-line">
    </span>
                    </div>

                </div>

                <div class="col-xs-12 visible-xs"><br></div>

                <div class="col-xs-12 col-sm-5 col-sm-offset-1 col-md-6 col-md-offset-1">
                    <div class="details-box panel panel-default panel-body">
                        <div class="row">


                            <div class="col-xs-12 col-md-6">

                                <div class="invoice-properties">
                                    <label>Invoice #</label>
                                    <input type="text" id="invoice_number" class="form-control input-sm"
                                                                                    value="1"
                                                                                >
                                </div>

                                <div class="invoice-properties has-feedback">
                                    <label>Date</label>

                                    <div class="input-group">
                                        <input name="invoice_date_created" id="invoice_date_created"
                                               class="form-control input-sm datepicker"
                                               value="03/27/2019"
                                            >
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar fa-fw"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="invoice-properties has-feedback">
                                    <label>Due Date</label>

                                    <div class="input-group">
                                        <input name="invoice_date_due" id="invoice_date_due"
                                               class="form-control input-sm datepicker"
                                               value="04/26/2019"
                                            >
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar fa-fw"></i>
                                        </span>
                                    </div>
                                </div>

                                <!-- Custom fields -->

                            </div>

                            <div class="col-xs-12 col-md-6">

                                <div class="invoice-properties">
                                    <label>
                                        Status <span class="small">(Can be changed)</span>                                    </label>
                                    <select name="invoice_status_id" id="invoice_status_id"
                                            class="form-control input-sm simple-select"
                                        >
                                                                                    <option value="1"
                                                    selected="selected">
                                                Draft                                            </option>
                                                                                    <option value="2"
                                                    >
                                                Sent                                            </option>
                                                                                    <option value="3"
                                                    >
                                                Viewed                                            </option>
                                                                                    <option value="4"
                                                    >
                                                Paid                                            </option>
                                                                            </select>
                                </div>

                                <div class="invoice-properties">
                                    <label>Payment Method</label>
                                    <select name="payment_method" id="payment_method"
                                            class="form-control input-sm simple-select"
                                        >
                                        <option value="0">Select the Payment Method</option>
                                                                                    <option                                                 value="1">
                                                Cash                                            </option>
                                                                                    <option                                                 value="2">
                                                Credit Card                                            </option>
                                                                            </select>
                                </div>

                                <div class="invoice-properties">
                                    <label>PDF password (optional)</label>
                                    <input type="text" id="invoice_password" class="form-control input-sm"
                                           value=""
                                        >
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

            </div>

            <br>

            <div class="table-responsive">
    <table id="item_table" class="items table table-condensed table-bordered no-margin">
        <thead style="display: none">
        <tr>
            <th></th>
            <th>Item</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Tax Rate</th>
            <th>Subtotal</th>
            <th>Tax</th>
            <th>Total</th>
            <th></th>
        </tr>
        </thead>

        <tbody id="new_row" style="display: none;">
        <tr>
            <td rowspan="2" class="td-icon">
                <i class="fa fa-arrows cursor-move"></i>
                            </td>
            <td class="td-text">
                <input type="hidden" name="invoice_id" value="1">
                <input type="hidden" name="item_id" value="">
                <input type="hidden" name="item_product_id" value="">
                <input type="hidden" name="item_task_id" class="item-task-id" value="">

                <div class="input-group">
                    <span class="input-group-addon">Item</span>
                    <input type="text" name="item_name" class="input-sm form-control" value="">
                </div>
            </td>
            <td class="td-amount td-quantity">
                <div class="input-group">
                    <span class="input-group-addon">Quantity</span>
                    <input type="text" name="item_quantity" class="input-sm form-control amount" value="">
                </div>
            </td>
            <td class="td-amount">
                <div class="input-group">
                    <span class="input-group-addon">Price</span>
                    <input type="text" name="item_price" class="input-sm form-control amount" value="">
                </div>
            </td>
            <td class="td-amount">
                <div class="input-group">
                    <span class="input-group-addon">Item Discount</span>
                    <input type="text" name="item_discount_amount" class="input-sm form-control amount"
                           value="" data-toggle="tooltip" data-placement="bottom"
                           title="$ per Item">
                </div>
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-addon">Tax Rate</span>
                    <select name="item_tax_rate_id" class="form-control input-sm">
                        <option value="0">None</option>
                                            </select>
                </div>
            </td>
            <td class="td-icon text-right td-vert-middle">
                <button type="button" class="btn_delete_item btn btn-link btn-sm" title="Delete">
                    <i class="fa fa-trash-o text-danger"></i>
                </button>
            </td>
        </tr>
        <tr>
                            <td class="td-textarea">
                    <div class="input-group">
                        <span class="input-group-addon">Description</span>
                        <textarea name="item_description" class="input-sm form-control"></textarea>
                    </div>
                </td>
                        <td class="td-amount">
                <div class="input-group">
                    <span class="input-group-addon">Product Unit</span>
                    <select name="item_product_unit_id" class="form-control input-sm">
                        <option value="0">None</option>
                                            </select>
                </div>
            </td>
            <td class="td-amount td-vert-middle">
                <span>Subtotal</span><br/>
                <span name="subtotal" class="amount"></span>
            </td>
            <td class="td-amount td-vert-middle">
                <span>Discount</span><br/>
                <span name="item_discount_total" class="amount"></span>
            </td>
            <td class="td-amount td-vert-middle">
                <span>Tax</span><br/>
                <span name="item_tax_total" class="amount"></span>
            </td>
            <td class="td-amount td-vert-middle">
                <span>Total</span><br/>
                <span name="item_total" class="amount"></span>
            </td>
        </tr>
        </tbody>

                    <tbody class="item">
            <tr>
                <td rowspan="2" class="td-icon">
                    <i class="fa fa-arrows cursor-move"></i>
                                    </td>

                <td class="td-text">
                    <input type="hidden" name="invoice_id" value="1">
                    <input type="hidden" name="item_id" value="2"
                        >
                    <input type="hidden" name="item_task_id" class="item-task-id"
                           value="">
                    <input type="hidden" name="item_product_id" value="1">

                    <div class="input-group">
                        <span class="input-group-addon">Item</span>
                        <input type="text" name="item_name" class="input-sm form-control"
                               value="Banner Ad"
                            >
                    </div>
                </td>
                <td class="td-amount td-quantity">
                    <div class="input-group">
                        <span class="input-group-addon">Quantity</span>
                        <input type="text" name="item_quantity" class="input-sm form-control amount"
                               value="1.00"
                            >
                    </div>
                </td>
                <td class="td-amount">
                    <div class="input-group">
                        <span class="input-group-addon">Price</span>
                        <input type="text" name="item_price" class="input-sm form-control amount"
                               value="100.00"
                            >
                    </div>
                </td>
                <td class="td-amount">
                    <div class="input-group">
                        <span class="input-group-addon">Item Discount</span>
                        <input type="text" name="item_discount_amount" class="input-sm form-control amount"
                               value=""
                               data-toggle="tooltip" data-placement="bottom"
                               title="$ per Item"
                            >
                    </div>
                </td>
                <td class="td-amount">
                    <div class="input-group">
                        <span class="input-group-addon">Tax Rate</span>
                        <select name="item_tax_rate_id" class="form-control input-sm"
                            >
                            <option value="0">None</option>
                                                    </select>
                    </div>
                </td>
                <td class="td-icon text-right td-vert-middle">
                                            <button type="button" class="btn_delete_item btn btn-link btn-sm" title="Delete"
                                data-item-id="2">
                            <i class="fa fa-trash-o text-danger"></i>
                        </button>
                                    </td>
            </tr>

            <tr>
                                    <td class="td-textarea">
                        <div class="input-group">
                            <span class="input-group-addon">Description</span>
                            <textarea name="item_description"
                                      class="input-sm form-control"
                                >Banner Ad</textarea>
                        </div>
                    </td>

                <td class="td-amount">
                    <div class="input-group">
                        <span class="input-group-addon">Product Unit</span>
                        <select name="item_product_unit_id" class="form-control input-sm">
                            <option value="0">None</option>
                                                    </select>
                    </div>
                </td>
                <td class="td-amount td-vert-middle">
                    <span>Subtotal</span><br/>
                    <span name="subtotal" class="amount">
                        $100.00                    </span>
                </td>
                <td class="td-amount td-vert-middle">
                    <span>Discount</span><br/>
                    <span name="item_discount_total" class="amount">
                        $0.00                    </span>
                </td>
                <td class="td-amount td-vert-middle">
                    <span>Tax</span><br/>
                    <span name="item_tax_total" class="amount">
                        $0.00                    </span>
                </td>
                <td class="td-amount td-vert-middle">
                    <span>Total</span><br/>
                    <span name="item_total" class="amount">
                        $100.00                    </span>
                </td>
            </tr>
            </tbody>

    </table>
</div>

<br>

<div class="row">
    <div class="col-xs-12 col-md-4">
        <div class="btn-group">
                            <a href="#" class="btn_add_row btn btn-sm btn-default">
                    <i class="fa fa-plus"></i> Add new row                </a>
                <a href="#" class="btn_add_product btn btn-sm btn-default">
                    <i class="fa fa-database"></i>
                    Add product                </a>
                <a href="#" class="btn_add_task btn btn-sm btn-default">
                    <i class="fa fa-database"></i> Add task                </a>
                    </div>
    </div>

    <div class="col-xs-12 visible-xs visible-sm"><br></div>

    <div class="col-xs-12 col-md-6 col-md-offset-2 col-lg-4 col-lg-offset-4">
        <table class="table table-bordered text-right">
            <tr>
                <td style="width: 40%;">Subtotal</td>
                <td style="width: 60%;"
                    class="amount">$100.00</td>
            </tr>
            <tr>
                <td>Item Tax</td>
                <td class="amount">$0.00</td>
            </tr>
            <tr>
                <td>Invoice Tax</td>
                <td>
                    $0.00                </td>
            </tr>
            <tr>
                <td class="td-vert-middle">Discount</td>
                <td class="clearfix">
                    <div class="discount-field">
                        <div class="input-group input-group-sm">
                            <input id="invoice_discount_amount" name="invoice_discount_amount"
                                   class="discount-option form-control input-sm amount"
                                   value=""
                                >
                            <div class="input-group-addon">$</div>
                        </div>
                    </div>
                    <div class="discount-field">
                        <div class="input-group input-group-sm">
                            <input id="invoice_discount_percent" name="invoice_discount_percent"
                                   value=""
                                   class="discount-option form-control input-sm amount"
                                >
                            <div class="input-group-addon">&percnt;</div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Total</td>
                <td class="amount"><b>$100.00</b></td>
            </tr>
            <tr>
                <td>Paid</td>
                <td class="amount"><b>$50.00</b></td>
            </tr>
            <tr>
                <td><b>Balance</b></td>
                <td class="amount"><b>$50.00</b></td>
            </tr>
        </table>
    </div>

</div>

            <hr/>

            <div class="row">
                <div class="col-xs-12 col-md-6">

                    <div class="panel panel-default no-margin">
                        <div class="panel-heading">
                            Invoice Terms                        </div>
                        <div class="panel-body">
                            <textarea id="invoice_terms" name="invoice_terms" class="form-control" rows="3"
                                                            ></textarea>
                        </div>
                    </div>

                    <div class="col-xs-12 visible-xs visible-sm"><br></div>

                </div>
                <div class="col-xs-12 col-md-6">

                    <div class="panel panel-default no-margin">

    <div class="panel-heading">
        Attachments    </div>

    <div class="panel-body clearfix">
        <!-- The fileinput-button span is used to style the file input field as button -->
        <button type="button" class="btn btn-default fileinput-button">
            <i class="fa fa-plus"></i> Add Files...        </button>

        <!-- dropzone -->
        <div class="row">
            <div id="actions" class="col-xs-12">
                <div class="col-lg-7"></div>
                <div class="col-lg-5">
                    <!-- The global file processing state -->
                    <div class="fileupload-process">
                        <div id="total-progress" class="progress progress-striped active"
                             role="progressbar"
                             aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                            <div class="progress-bar progress-bar-success" style="width:0%;"
                                 data-dz-uploadprogress></div>
                        </div>
                    </div>
                </div>

                <div id="previews" class="table table-condensed files no-margin">
                    <div id="template" class="file-row">
                        <!-- This is used as the file preview template -->
                        <div>
                            <span class="preview"><img data-dz-thumbnail/></span>
                        </div>
                        <div>
                            <p class="name" data-dz-name></p>
                            <strong class="error text-danger" data-dz-errormessage></strong>
                        </div>
                        <div>
                            <p class="size" data-dz-size></p>
                            <div class="progress progress-striped active" role="progressbar"
                                 aria-valuemin="0"
                                 aria-valuemax="100" aria-valuenow="0">
                                <div class="progress-bar progress-bar-success" style=""
                                     data-dz-uploadprogress></div>
                            </div>
                        </div>
                        <div class="pull-left btn-group">
                            <button data-dz-download class="btn btn-sm btn-primary">
                                <i class="fa fa-download"></i>
                                <span>Download</span>
                            </button>
                                                            <button data-dz-remove class="btn btn-danger btn-sm delete">
                                    <i class="fa fa-trash-o"></i>
                                    <span>Delete</span>
                                </button>
                                                    </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- stop dropzone -->
    </div>

</div>

                </div>
            </div>


        </div>

    </div>
</div>

<script>
    function getIcon(fullname) {
        var fileFormat = fullname.match(/\.([A-z0-9]{1,5})$/);
        if (fileFormat) {
            fileFormat = fileFormat[1];
        }
        else {
            fileFormat = '';
        }

        var fileIcon = 'default';

        switch (fileFormat) {
            case 'pdf':
                fileIcon = 'file-pdf';
                break;

            case 'mp3':
            case 'wav':
            case 'ogg':
                fileIcon = 'file-audio';
                break;

            case 'doc':
            case 'docx':
            case 'odt':
                fileIcon = 'file-document';
                break;

            case 'xls':
            case 'xlsx':
            case 'ods':
                fileIcon = 'file-spreadsheet';
                break;

            case 'ppt':
            case 'pptx':
            case 'odp':
                fileIcon = 'file-presentation';
                break;
        }
        return fileIcon;
    }

    // Get the template HTML and remove it from the document
    var previewNode = document.querySelector('#template');
    previewNode.id = '';
    var previewTemplate = previewNode.parentNode.innerHTML;
    previewNode.parentNode.removeChild(previewNode);

    var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
        url: 'http://accounts.srilankaproperty.lk/index.php/upload/upload_file/1/UB7jvhouCdO8FVxStrPyRefE3lmqwG61',
        params: {
            '_ip_csrf': Cookies.get('ip_csrf_cookie'),
        },
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        parallelUploads: 20,
        uploadMultiple: false,
        dictRemoveFileConfirmation: 'Are you sure you wish to delete this attachment?',
        previewTemplate: previewTemplate,
        autoQueue: true, // Make sure the files aren't queued until manually added
        previewsContainer: '#previews', // Define the container to display the previews
        clickable: '.fileinput-button', // Define the element that should be used as click trigger to select files.
        init: function () {
            thisDropzone = this;
            $.getJSON('http://accounts.srilankaproperty.lk/index.php/upload/upload_file/1/UB7jvhouCdO8FVxStrPyRefE3lmqwG61',
                function (data) {
                    $.each(data, function (index, val) {
                        var mockFile = {fullname: val.fullname, size: val.size, name: val.name};

                        thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                        createDownloadButton(mockFile, 'http://accounts.srilankaproperty.lk/index.php/upload/get_file/' + val.fullname);

                        if (val.fullname.match(/\.(jpg|jpeg|png|gif)$/)) {
                            thisDropzone.options.thumbnail.call(thisDropzone, mockFile,
                                'http://accounts.srilankaproperty.lk/index.php/upload/get_file/' + val.fullname);
                        }
                        else {
                            fileIcon = getIcon(val.fullname);

                            thisDropzone.options.thumbnail.call(thisDropzone, mockFile,
                                'http://accounts.srilankaproperty.lk/assets/core/img/file-icons/' + fileIcon + '.svg');
                        }

                        thisDropzone.emit('complete', mockFile);
                        thisDropzone.emit('success', mockFile);
                    });
                });
        },
    });

    myDropzone.on('success', function (file, response) {
                if (typeof response !== 'undefined') {
            response = JSON.parse(response);
            if (response.success !== true) {
                alert(response.message);
            }
        }
    });

    myDropzone.on('addedfile', function (file) {
        var fileIcon = getIcon(file.name);
        myDropzone.emit('thumbnail', file,
            'http://accounts.srilankaproperty.lk/assets/core/img/file-icons/' + fileIcon + '.svg');
        createDownloadButton(file, 'http://accounts.srilankaproperty.lk/index.php/upload/get_file/UB7jvhouCdO8FVxStrPyRefE3lmqwG61_' +
            file.name.replace(/\s+/g, '_'));
    });

    // Update the total progress bar
    myDropzone.on('totaluploadprogress', function (progress) {
        document.querySelector('#total-progress .progress-bar').style.width = progress + '%';
    });

    myDropzone.on('sending', function (file) {
        // Show the total progress bar when upload starts
        document.querySelector('#total-progress').style.opacity = '1';
    });

    // Hide the total progress bar when nothing's uploading anymore
    myDropzone.on('queuecomplete', function (progress) {
        document.querySelector('#total-progress').style.opacity = '0';
    });

    myDropzone.on('removedfile', function (file) {
        $.post({
            url: 'http://accounts.srilankaproperty.lk/index.php/upload/delete_file/UB7jvhouCdO8FVxStrPyRefE3lmqwG61',
            data: {
                'name': file.name.replace(/\s+/g, '_'),
                _ip_csrf: Cookies.get('ip_csrf_cookie')
            }
        }, function (response) {
                    }

    );
    });

    function createDownloadButton(file, fileUrl) {
        var downloadButtonList = file.previewElement.querySelectorAll('[data-dz-download]');
        for (var $i = 0; $i < downloadButtonList.length; $i++) {
            downloadButtonList[$i].addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                location.href = fileUrl;
                return false;
            });
        }
    }
</script>
    </div>

</div>

<div id="modal-placeholder"></div>

<div id="fullpage-loader" style="display: none">
    <div class="loader-content">
        <i id="loader-icon" class="fa fa-cog fa-spin"></i>
        <div id="loader-error" style="display: none">
            It seems that the application stuck because of an error.<br/>
            <a href="https://wiki.invoiceplane.com/en/1.0/general/faq"
               class="btn btn-primary btn-sm" target="_blank">
                <i class="fa fa-support"></i> Get Help            </a>
        </div>
    </div>
    <div class="text-right">
        <button type="button" class="fullpage-loader-close btn btn-link tip" aria-label="Close"
                title="Close" data-placement="left">
            <span aria-hidden="true"><i class="fa fa-close"></i></span>
        </button>
    </div>
</div>

<script defer src="http://accounts.srilankaproperty.lk/assets/core/js/scripts.min.js"></script>

</body>
</html>
