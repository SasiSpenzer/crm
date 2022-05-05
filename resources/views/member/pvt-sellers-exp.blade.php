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
				Pvt Sellers Expired
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				<!--  -->
				<!-- Tab panes -->
				<ul class="nav nav-pills" style="margin-bottom: 5px;">
					<li class="active">
						<a data-toggle="tab" href="#home-pills">All (Default)</a>
					</li>
					<li>
						<a id="btn_un_conected" href="#un-connected" data-toggle="tab">Un contacted</a>
					</li>
					<li>
						<a id="btn-mycontacts" href="#mycontacts" data-toggle="tab">My Contacts</a>
					</li>
					<li>
						<a id="btn-follow" href="#follow" data-toggle="tab">Follow ups</a>
					</li>
{{--					<li>--}}
{{--						<a id="btn-order-list" href="#orderdate" data-toggle="tab">Order list by Date</a>--}}
{{--					</li>--}}

				</ul>
				<div class="tab-content">
					<div class="tab-pane fade in active" id="home-pills">
						<div class="alert" id="flash_message" style="display:none">
						</div>
						<table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="users-table">
							<thead>
								<tr>
									<th>Name</th>
									<th>Payment Expiry Date</th>
									<th>Posted Date</th>

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
					<div class="tab-pane" id="un-connected">
						<div class="alert" id="flash_message" style="display:none">
						</div>
						<table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="users-table-uncon">
							<thead>
							<tr>
								<th>Name</th>
								<th>Payment Expiry Date</th>
								<th>Posted Date</th>

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
					<div class="tab-pane" id="mycontacts">
						<div class="alert" id="flash_message" style="display:none">
						</div>
						<table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="users-table-mycon">
							<thead>
							<tr>
								<th>Name</th>
								<th>Payment Expiry Date</th>
								<th>Posted Date</th>

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
					<div class="tab-pane" id="follow">
						<div class="alert" id="flash_message" style="display:none">
						</div>
						<table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="users-table-followta">
							<thead>
							<tr>
								<th>Name</th>
								<th>Payment Expiry Date</th>
								<th>Posted Date</th>

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
					<div class="tab-pane" id="orderdate">
						<div class="alert" id="flash_message" style="display:none">
						</div>
						<table width="100%" class="table table-striped table-bordered table-hover display nowrap" id="users-table-orderdate">
							<thead>
							<tr>
								<th>Name</th>
								<th>Payment Expiry Date</th>
								<th>Posted Date</th>

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
<style>
	.yellow-class,
	.yellow-class:hover {
		background-color: #ddeb6d !important;
	}

</style>
<script type="text/javascript" src="{{ URL::asset('/js/app/member/pvt-sellers-exp.js') }}"></script>
@endsection