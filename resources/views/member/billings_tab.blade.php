<div class="tab-pane fade hide" id="billings">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well well-sm">
                <!-- /.panel -->
                    <div class="panel-heading">
                        <i class="fa fa-clock-o fa-fw"></i> Member Billing Log
                    </div>
                    <!-- /.panel-heading -->
                    <div class="col-xs-12 col-sm-12 col-md-12" style="text-align: right;margin-top: 5px;">
                        <form role="form" enctype="multipart/form-data" id="billingDataForm" method="POST">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-sm btn-default btn-info" id="billing_data" name="billing_data">Billing Data Update</button>
                        </form>

                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12" id="user_manage">
                                <input type="hidden" name="billing_uid" id="billing_uid">
                                <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="member-billings-table">
                                    <thead>
                                        <tr>
                                            <th>Invoice No.</th>
                                            <th>Invoice Date</th>
                                            <th>Invoiced Amount</th>
                                            <th>Paid Amount</th>
                                            <th>Pending Amount</th>
                                            <th>Product Type</th>
                                            <th>Payment Method</th>
                                            <th>Status</th>
                                            <th>Due Date</th>
                                            <th>Paid Date</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
<style>
    button#billing_data {
        position: relative !important;
        margin-top: -70px !important;
    }
</style>
