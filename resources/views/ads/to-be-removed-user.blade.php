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
				Ads / To Be Removed
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				<b>
				<p>Name: {{$user->firstname . ' ' . $user->surname}}</p>
				<p>Email: {{$user->Uemail}}></p>
				</b><br>
				<!-- Tab panes -->
				<div class="tab-content">
					<div class="tab-pane fade in active" id="today">
						<div class="alert" id="flash_message" style="display:none">
						</div>
						<table width="100%" class="table table-striped table-bordered table-hover" id="tbl-today">
							<thead>
								<tr><th>Heading</th>
									<th>Property Type</th>
									<th>URL</th>
									<th>Last Update</th>
									<th>Member Expire</th>
									<th>Payment Expire</th>
									<th>Category</th>
									<th>AM</th>
								</tr>
							</thead>
						</table>
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
<script type="text/javascript">
	user_id = {{$user->UID}};
</script>
<script type="text/javascript" src="{{ URL::asset('/js/app/ads/to-be-removed-user.js') }}"></script>
@endsection