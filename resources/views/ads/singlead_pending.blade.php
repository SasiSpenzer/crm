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
				Advertisement Reports
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				<!-- Tabs By Sasi Spenzer 2021-12-26 Sunday :( -->
					<ul class="nav nav-pills" style="margin-bottom: 5px;">
						<li class="active">
							<a data-toggle="tab" href="#home-pills">All (Default)</a>
						</li>
						<li>
							<a id="btn-followups" href="#followups" data-toggle="tab">Follow-Ups</a>
						</li>
						<li>
							<a id="btn_un_conecteds" href="#un-connected" data-toggle="tab">Un contacted</a>
						</li>

					</ul>
				<!-- Tab panes -->
				<div class="tab-content">
					<div class="tab-pane fade in active" id="home-pills">
						<div class="alert" id="flash_message" style="display:none;">
						</div>
						<table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="users-table">
							<thead>
								<tr>
									<th>Name</th>
									<th>Payment Expiry Date</th>

									<th>AM</th>
									<th>Status</th>
									<th>Latest Comments</th>
									<th>Last update date</th>
									<th>Last update by</th>
									<th>Tel</th>
									<th>Email</th>
									<th>Add type</th>
									<th>Property type</th>
									<th>Duration</th>
									<th>Amount</th>
									<th>Price</th>
									<th></th>
								</tr>
							</thead>
						</table>

						<!--@ include('member.edit')-->

					</div>
					<div class="tab-pane fade in" id="followups">
						<div class="alert" id="flash_message" style="display:none">
						</div>
						<table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="users-follow">
							<thead>
							<tr>
								<th>Name</th>
								<th>Payment Expiry Date</th>

								<th>AM</th>
								<th>Status</th>
								<th>Latest Comments</th>
								<th>Last update date</th>
								<th>Last update by</th>
								<th>Tel</th>
								<th>Email</th>
								<th>Add type</th>
								<th>Property type</th>
								<th>Duration</th>
								<th>Amount</th>
								<th>Price</th>
								<th></th>
							</tr>
							</thead>
						</table>

						<!--@ include('member.edit')-->

					</div>
					<div class="tab-pane fade in" id="un-connected">
						<div class="alert" id="flash_message" style="display:none">
						</div>
						<table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="users-uncon">
							<thead>
							<tr>
								<th>Name</th>
								<th>Payment Expiry Date</th>

								<th>AM</th>
								<th>Status</th>
								<th>Latest Comments</th>
								<th>Last update date</th>
								<th>Last update by</th>
								<th>Tel</th>
								<th>Email</th>
								<th>Add type</th>
								<th>Property type</th>
								<th>Duration</th>
								<th>Amount</th>
								<th>Price</th>
								<th></th>
							</tr>
							</thead>
						</table>

						<!--@ include('member.edit')-->
						@include('member.details_modal')
					</div>
				</div>
			</div>
			<!-- /.panel-body -->
		</div>
		<!-- /.panel -->
	</div>
	<!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<style>
	.yellow-class,
	.yellow-class:hover {
		background-color: #ddeb6d !important;
	}

</style>
<script type="text/javascript" src="{{ URL::asset('/js/app/ads/single-ads-pending.js?v=0.3') }}"></script>
@endsection