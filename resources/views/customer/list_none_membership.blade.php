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
				Non Members
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				<!--  -->
				<!-- Tab panes -->
				<div class="tab-content">
					<div class="tab-pane fade in active" id="home-pills">
						<div class="alert" id="flash_message" style="display:none">
						</div>
						<table width="100%" class="table table-striped table-bordered table-hover" id="users-table">
							<thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>No. of ads</th>
									<th>Registered Date</th>
									<th>Last updated date</th>
									<th>Comments</th>
									<th>AM</th>
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


<script type="text/javascript" src="{{ URL::asset('/js/app/customer/app.none.memberships.js') }}"></script>
@endsection