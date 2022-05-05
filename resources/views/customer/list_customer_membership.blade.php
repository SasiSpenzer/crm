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
				Membership Reports
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				<!--  -->
				<!-- Nav tabs -->
				<ul class="nav nav-pills" style="margin-bottom: 5px;">
					<li class="active">
						<a href="#all" data-toggle="tab">All Agents (AM)</a>
					</li>
					<li>
						<a id="btn-grace-period" href="#grace-period" data-toggle="tab">Expired - Grace Period (AM)</a>
					</li>
					<li>
						<a id="btn-deactivated" href="#deactivated" data-toggle="tab">Expired - Deactivated (AM)</a>
					</li>
					<li>
						<a id="btn-expired" href="#expired" data-toggle="tab">Expired +2 (Hunters)</a>
					</li>
					<li>
						<a id="btn-unallocated" href="#unallocated" data-toggle="tab">Un-allocated Agents</a>
					</li>
				</ul>
				<!-- Tab panes -->
				<!-- Tab panes -->
				<div class="tab-content">
					<div class="tab-pane fade in active" id="all">
						<div class="alert" id="flash_message" style="display:none">
						</div>
						<table width="100%" class="table table-striped table-bordered table-hover" id="users-table-all">
							<thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>No. of ads</th>
									<th>Last updated date</th>
									<th>Membership category</th>
									<th>Payment exp Date</th>
									<th>AM</th>
									<th></th>
								</tr>
							</thead>
						</table>
						<!--@ include('member.edit')-->
					</div>
					<div class="tab-pane fade" id="grace-period">
						<div class="alert" id="flash_message" style="display:none">
						</div>
						<table width="100%" class="table table-striped table-bordered table-hover" id="users-table-grace-period">
							<thead>
							<tr>
								<th>Name</th>
								<th>Email</th>
								<th>No. of ads</th>
								<th>Last updated date</th>
								<th>Membership category</th>
								<th>Payment exp Date</th>
								<th>AM</th>
								<th></th>
							</tr>
							</thead>
						</table>
					</div>
					<div class="tab-pane fade" id="deactivated">
						<div class="alert" id="flash_message" style="display:none">
						</div>
						<table width="100%" class="table table-striped table-bordered table-hover" id="users-table-deactivated">
							<thead>
							<tr>
								<th>Name</th>
								<th>Email</th>
								<th>No. of ads</th>
								<th>Last updated date</th>
								<th>Membership category</th>
								<th>Payment exp Date</th>
								<th>AM</th>
								<th></th>
							</tr>
							</thead>
						</table>
					</div>
					<div class="tab-pane fade" id="expired">
						<div class="alert" id="flash_message" style="display:none">
						</div>
						<table width="100%" class="table table-striped table-bordered table-hover" id="users-table-expired">
							<thead>
							<tr>
								<th>Name</th>
								<th>Email</th>
								<th>No. of ads</th>
								<th>Last updated date</th>
								<th>Membership category</th>
								<th>Payment exp Date</th>
								<th>AM</th>
								<th></th>
							</tr>
							</thead>
						</table>
					</div>
					<div class="tab-pane fade" id="unallocated">
						<div class="alert" id="flash_message" style="display:none">
						</div>
						<table width="100%" class="table table-striped table-bordered table-hover" id="users-table-unallocated">
							<thead>
							<tr>
								<th>Name</th>
								<th>Email</th>
								<th>No. of ads</th>
								<th>Last updated date</th>
								<th>Membership category</th>
								<th>Payment exp Date</th>
								<th>AM</th>
								<th></th>
							</tr>
							</thead>
						</table>
					</div>
					@include('member.details_modal')
				</div>
			</div>
			<!-- /.panel-body -->
		</div>
		<!-- /.panel -->
	</div>
	<!-- /.col-lg-12 -->
</div>
<!-- /.row -->


<script type="text/javascript" src="{{ URL::asset('/js/app/customer/app.customer.memberships.js?v=0.1') }}"></script>
@endsection