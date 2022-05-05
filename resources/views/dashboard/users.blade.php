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
				<a href="{!! url('/dashboard') !!}">Dashboard</a> > Total Users
			</div>
			
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
								<th>Membership expiry</th>
								<th>AM</th>
							</tr>
						</thead>
					</table>
				</p>
			</div>

		</div>
		<!-- /.panel -->
	</div>
	<!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<script type="text/javascript" src="{{ URL::asset('/js/app/dashboard/users.js') }}"></script>
@endsection