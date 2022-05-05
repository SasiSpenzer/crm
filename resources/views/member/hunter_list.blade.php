@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h2 class="label-info"></h2>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">

                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
{{--                    <div class="row">--}}
{{--                        <div style="display: inline-flex;" class="col-xs-2 col-sm-2 col-md-2">--}}
{{--                            <select id="filter" style="margin-right: 20px; width: auto" class="form-control">--}}
{{--                                <option value="Prospects">Prospects</option>--}}
{{--                                <option value="Inbound_call">Inbound Call</option>--}}
{{--                                <option value="Outbound_call">Outbound call</option>--}}
{{--                                <option value="Chat_Email">Chat/Email</option>--}}
{{--                                <option value="Newspaper">Newspaper</option>--}}
{{--                                <option value="Ikman_List">Ikman List</option>--}}
{{--                                <option value="other">Other Website</option>--}}
{{--                                <option value="fb_other">FB, Other</option>--}}
{{--                            </select>--}}
{{--                            <button type="button" class="btn btn-default btn-warning" id="filter_ads" >Filter by Source </button>--}}
{{--                        </div>--}}


{{--                    </div>--}}
{{--                    <br>--}}
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Customer Data
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">
                                    <ul class="nav nav-pills" style="margin-bottom: 5px;">
                                        <li class="active">
                                            <a href="#home-pills" data-toggle="tab">All</a>
                                        </li>
                                        <li>
                                            <a id="btn-Prospects" href="#Prospects" data-toggle="tab">Prospects</a>
                                        </li>
                                        <li>
                                            <a id="btn-Inbound_call" href="#Inbound_call" data-toggle="tab">Inbound Call</a>
                                        </li>
                                        <li>
                                            <a id="btn-Outbound_call" href="#Outbound_call" data-toggle="tab">Outbound Call</a>
                                        </li>
                                        <li>
                                            <a id="btn-Chat_Email" href="#Chat_Email" data-toggle="tab">Chat/Email</a>
                                        </li>
                                        <li>
                                            <a id="btn-Newspaper" href="#Newspaper" data-toggle="tab">Newspaper</a>
                                        </li>
                                        <li>
                                            <a id="btn-Ikman_List" href="#Ikman_List" data-toggle="tab">Ikman List</a>
                                        </li>
                                        <li>
                                            <a id="btn-other" href="#other" data-toggle="tab">Other Websites</a>
                                        </li>
                                        <li>
                                            <a id="btn-fb_other" href="#fb_other" data-toggle="tab">FB</a>
                                        </li>
                                        <li>
                                            <a id="btn-other_new" href="#other_new" data-toggle="tab">Other</a>
                                        </li>
                                        <li>
                                            <a id="btn-Agents" href="#Agents" data-toggle="tab">Agents</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                    <div class="tab-pane fade in active" id="home-pills">
                                        <div class="alert" id="flash_message" style="display:none"></div>
                                        <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="customer-table">
                                            <thead>
                                                <th>Full Name</th>
                                                <th>User Type</th>
                                                <th>AM</th>
                                                <th>Membership Status</th>
                                                <th>Created Date</th>
                                                <th>Status</th>
                                                <th>List Source</th>
                                                <th>Latest Comments</th>
                                                <th>Last Updated</th>
                                                <th>Last Updated by</th>
{{--                                                <th>Expiry</th>--}}
{{--                                                <th>Leads</th>--}}
{{--                                                <th>Views</th>--}}
{{--                                                <th>No. Of Ads</th>--}}
{{--                                                <th>Column ID</th>--}}
                                                <th>Email Address</th>
                                                <th>Edit</th>
                                            </thead>
                                        </table>
                                    </div>
                                        <div class="tab-pane fade" id="Prospects">
                                            <div class="alert" id="flash_message" style="display:none"></div>
                                            <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="customer-table-Prospects">
                                                <thead>
                                                <th>Full Name</th>
                                                <th>User Type</th>
                                                <th>AM</th>
                                                <th>Membership Status</th>
                                                <th>Created Date</th>
                                                <th>Status</th>
                                                <th>List Source</th>
                                                <th>Latest Comments</th>
                                                <th>Last Updated</th>
                                                <th>Last Updated by</th>
{{--                                                <th>Expiry</th>--}}
{{--                                                <th>Leads</th>--}}
{{--                                                <th>Views</th>--}}
{{--                                                <th>No. Of Ads</th>--}}
{{--                                                <th>Column ID</th>--}}
                                                <th>Email Address</th>
                                                <th>Edit</th>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="Inbound_call">
                                            <div class="alert" id="flash_message" style="display:none"></div>
                                            <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="customer-table-Inbound_call">
                                                <thead>
                                                <th>Full Name</th>
                                                <th>User Type</th>
                                                <th>AM</th>
                                                <th>Membership Status</th>
                                                <th>Created Date</th>
                                                <th>Status</th>
                                                <th>List Source</th>
                                                <th>Latest Comments</th>
                                                <th>Last Updated</th>
                                                <th>Last Updated by</th>
{{--                                                <th>Expiry</th>--}}
{{--                                                <th>Leads</th>--}}
{{--                                                <th>Views</th>--}}
{{--                                                <th>No. Of Ads</th>--}}
{{--                                                <th>Column ID</th>--}}
                                                <th>Email Address</th>
                                                <th>Edit</th>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="Outbound_call">
                                            <div class="alert" id="flash_message" style="display:none"></div>
                                            <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="customer-table-Outbound_call">
                                                <thead>
                                                <th>Full Name</th>
                                                <th>User Type</th>
                                                <th>AM</th>
                                                <th>Membership Status</th>
                                                <th>Created Date</th>
                                                <th>Status</th>
                                                <th>List Source</th>
                                                <th>Latest Comments</th>
                                                <th>Last Updated</th>
                                                <th>Last Updated by</th>
{{--                                                <th>Expiry</th>--}}
{{--                                                <th>Leads</th>--}}
{{--                                                <th>Views</th>--}}
{{--                                                <th>No. Of Ads</th>--}}
{{--                                                <th>Column ID</th>--}}
                                                <th>Email Address</th>
                                                <th>Edit</th>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="Chat_Email">
                                            <div class="alert" id="flash_message" style="display:none"></div>
                                            <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="customer-table-Chat_Email">
                                                <thead>
                                                <th>Full Name</th>
                                                <th>User Type</th>
                                                <th>AM</th>
                                                <th>Membership Status</th>
                                                <th>Created Date</th>
                                                <th>Status</th>
                                                <th>List Source</th>
                                                <th>Latest Comments</th>
                                                <th>Last Updated</th>
                                                <th>Last Updated by</th>
{{--                                                <th>Expiry</th>--}}
{{--                                                <th>Leads</th>--}}
{{--                                                <th>Views</th>--}}
{{--                                                <th>No. Of Ads</th>--}}
{{--                                                <th>Column ID</th>--}}
                                                <th>Email Address</th>
                                                <th>Edit</th>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="Newspaper">
                                            <div class="alert" id="flash_message" style="display:none"></div>
                                            <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="customer-table-Newspaper">
                                                <thead>
                                                <th>Full Name</th>
                                                <th>User Type</th>
                                                <th>AM</th>
                                                <th>Membership Status</th>
                                                <th>Created Date</th>
                                                <th>Status</th>
                                                <th>List Source</th>
                                                <th>Latest Comments</th>
                                                <th>Last Updated</th>
                                                <th>Last Updated by</th>
{{--                                                <th>Expiry</th>--}}
{{--                                                <th>Leads</th>--}}
{{--                                                <th>Views</th>--}}
{{--                                                <th>No. Of Ads</th>--}}
{{--                                                <th>Column ID</th>--}}
                                                <th>Email Address</th>
                                                <th>Edit</th>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="Ikman_List">
                                            <div class="alert" id="flash_message" style="display:none"></div>
                                            <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="customer-table-Ikman_List">
                                                <thead>
                                                <th>Full Name</th>
                                                <th>User Type</th>
                                                <th>AM</th>
                                                <th>Membership Status</th>
                                                <th>Created Date</th>
                                                <th>Status</th>
                                                <th>List Source</th>
                                                <th>Latest Comments</th>
                                                <th>Last Updated</th>
                                                <th>Last Updated by</th>
{{--                                                <th>Expiry</th>--}}
{{--                                                <th>Leads</th>--}}
{{--                                                <th>Views</th>--}}
{{--                                                <th>No. Of Ads</th>--}}
{{--                                                <th>Column ID</th>--}}
                                                <th>Email Address</th>
                                                <th>Edit</th>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="other">
                                            <div class="alert" id="flash_message" style="display:none"></div>
                                            <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="customer-table-other">
                                                <thead>
                                                <th>Full Name</th>
                                                <th>User Type</th>
                                                <th>AM</th>
                                                <th>Membership Status</th>
                                                <th>Created Date</th>
                                                <th>Status</th>
                                                <th>List Source</th>
                                                <th>Latest Comments</th>
                                                <th>Last Updated</th>
                                                <th>Last Updated by</th>
{{--                                                <th>Expiry</th>--}}
{{--                                                <th>Leads</th>--}}
{{--                                                <th>Views</th>--}}
{{--                                                <th>No. Of Ads</th>--}}
{{--                                                <th>Column ID</th>--}}
                                                <th>Email Address</th>
                                                <th>Edit</th>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="fb_other">
                                            <div class="alert" id="flash_message" style="display:none"></div>
                                            <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="customer-table-fb_other">
                                                <thead>
                                                <th>Full Name</th>
                                                <th>User Type</th>
                                                <th>AM</th>
                                                <th>Membership Status</th>
                                                <th>Created Date</th>
                                                <th>Status</th>
                                                <th>List Source</th>
                                                <th>Latest Comments</th>
                                                <th>Last Updated</th>
                                                <th>Last Updated by</th>
{{--                                                <th>Expiry</th>--}}
{{--                                                <th>Leads</th>--}}
{{--                                                <th>Views</th>--}}
{{--                                                <th>No. Of Ads</th>--}}
{{--                                                <th>Column ID</th>--}}
                                                <th>Email Address</th>
                                                <th>Edit</th>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="other_new">
                                            <div class="alert" id="flash_message" style="display:none"></div>
                                            <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="customer-table_other_new">
                                                <thead>
                                                <th>Full Name</th>
                                                <th>User Type</th>
                                                <th>AM</th>
                                                <th>Membership Status</th>
                                                <th>Created Date</th>
                                                <th>Status</th>
                                                <th>List Source</th>
                                                <th>Latest Comments</th>
                                                <th>Last Updated</th>
                                                <th>Last Updated by</th>
{{--                                                <th>Expiry</th>--}}
{{--                                                <th>Leads</th>--}}
{{--                                                <th>Views</th>--}}
{{--                                                <th>No. Of Ads</th>--}}
{{--                                                <th>Column ID</th>--}}
                                                <th>Email Address</th>
                                                <th>Edit</th>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="Agents">
                                            <div class="alert" id="flash_message" style="display:none"></div>
                                            <table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="customer-table_Agents">
                                                <thead>
                                                <th>Full Name</th>
                                                <th>User Type</th>
                                                <th>AM</th>
                                                <th>Membership Status</th>
                                                <th>Created Date</th>
                                                <th>Status</th>
                                                <th>List Source</th>
                                                <th>Latest Comments</th>
                                                <th>Last Updated</th>
                                                <th>Last Updated by</th>
{{--                                                <th>Expiry</th>--}}
{{--                                                <th>Leads</th>--}}
{{--                                                <th>Views</th>--}}
{{--                                                <th>No. Of Ads</th>--}}
{{--                                                <th>Column ID</th>--}}
                                                <th>Email Address</th>
                                                <th>Edit</th>
                                                </thead>
                                            </table>
                                        </div>
                                    <!--@ include('member.edit')-->
                                    @include('member.details_modal')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{ URL::asset('/js/app/dashboard/hunters_list_data.js') }}"></script>

    <style>
        tr.expired {
            background-color: #f19292 !important;
        }
        tr.deactivated {
            background-color: #e9944a !important;
        }
        tr.month_expired {
            background-color: #f8bac5 !important;
        }
        tr.two_week {
            background-color: #ddeb6d !important;
        }
    </style>
@endsection