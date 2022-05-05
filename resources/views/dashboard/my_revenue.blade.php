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
				<a href="{!! url('/dashboard') !!}">Dashboard</a> > Revenue
			</div>

			<!-- /.panel-heading -->
			<div class="panel-body">
				<!--  -->
				<!-- Nav tabs -->
				<ul class="nav nav-pills">
					<li class="active"><a href="#pending" data-toggle="tab">Pendig Payments</a>
					</li>
					<li><a id="btn-paid" href="#paid" data-toggle="tab">Paid</a>
					</li>
				</ul>
				<!-- Tab panes -->
				<div class="tab-content">
					<div class="tab-pane fade in active" id="pending">
			
						<div style="padding: 10px">
							<p>
								<div class="alert" id="flash_message" style="display:none">
								</div>
								<table width="100%" class="table table-striped table-bordered table-hover" id="users-table">
									<thead>
										<tr>
											<th>Name</th>
											<th>Email</th>
											<th>No. of ads</th>
											<th>Last updated date</th>
											<th>Membership category</th>
											<th>Membership expire</th>
											<th>Payment expire</th>
											<th>AM</th>
											<th>Edit</th>
										</tr>
									</thead>
								</table>
							</p>
						</div>
					</div>

					<div class="tab-pane fade" id="paid">
			
						<div style="padding: 10px">
							<p>
								<div class="alert" id="flash_message" style="display:none">
								</div>
								<table width="100%" class="table table-striped table-bordered table-hover" id="paid-table">
									<thead>
										<tr>
											<th>Name</th>
											<th>Email</th>
											<th>No. of ads</th>
											<th>Last updated date</th>
											<th>Membership category</th>
											<th>Membership expire</th>
											<th>Payment expire</th>
											<th>AM</th>
										</tr>
									</thead>
								</table>
							</p>
						</div>
					</div>

				</div>

		</div>
		<!-- /.panel -->

		@include('payment.edit')

	</div>
	<!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<script type="text/javascript" src="{{ URL::asset('/js/app/dashboard/revenue.js') }}"></script>
@endsection