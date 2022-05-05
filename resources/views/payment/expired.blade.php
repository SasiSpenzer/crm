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
				Payment / Expired
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				<!--  -->
				<!-- Nav tabs -->
				<ul class="nav nav-pills">
					<li class="active"><a href="#all" data-toggle="tab">All</a>
					</li>
					<li><a id="btn-yesterday" href="#today" data-toggle="tab">Yesterday</a>
					</li>
					<li><a id="btn-week" href="#one-week-before" data-toggle="tab">Last Week</a>
					</li>
					<li><a id="btn-month" href="#one-month-before" data-toggle="tab">Last Month</a>
					</li>
				</ul>
				<!-- Tab panes -->
				<div class="tab-content">
					<div class="tab-pane fade in active" id="all">
						<div class="alert" id="flash_message" style="display:none">
						</div>
						<table width="100%" class="table table-striped table-bordered table-hover" id="tbl-all">
							<thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>No. of ads</th>
									<th>Last updated date</th>
									<th>Membership category</th>
									<!--th>Membership expiry</th-->
									<th>Payment Expiry</th>
									<th>AM</th>
									<th></th>
								</tr>
							</thead>
						</table>
					</div>
					<div class="tab-pane fade" id="today">
						<div class="alert" id="flash_message" style="display:none">
						</div>
						<table width="100%" class="table table-striped table-bordered table-hover" id="tbl-today">
							<thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>No. of ads</th>
									<th>Last updated date</th>
									<th>Membership category</th>
									<!--th>Membership expiry</th-->
									<th>Payment Expiry</th>
									<th>AM</th>
									<th></th>
								</tr>
							</thead>
						</table>
					</div>
					<div class="tab-pane fade" id="one-week-before">
						<div class="alert" id="flash_message" style="display:none">
						</div>
						<table width="100%" class="table table-striped table-bordered table-hover" id="tbl-week">
							<thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>No. of ads</th>
									<th>Last updated date</th>
									<th>Membership category</th>
									<!--th>Membership expiry</th-->
									<th>Payment Expiry</th>
									<th>AM</th>
									<th></th>
								</tr>
							</thead>
						</table>
					</div>
					<div class="tab-pane fade" id="one-month-before">
						<div class="alert" id="flash_message" style="display:none">
						</div>
						<table width="100%" class="table table-striped table-bordered table-hover" id="tbl-month">
							<thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>No. of ads</th>
									<th>Last updated date</th>
									<th>Membership category</th>
									<!--th>Membership expiry</th-->
									<th>Payment Expiry</th>
									<th>AM</th>
									<th></th>
								</tr>
							</thead>
						</table>
					</div>

					<!--@ include('member.edit')-->
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
<script type="text/javascript" src="{{ URL::asset('/js/app/payment/expired.js') }}"></script>
@endsection