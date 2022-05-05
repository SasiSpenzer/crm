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
				Ads / Summary
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
									<td>Category</td>
									<td>Members Count</td>
									<td>Invoiced Count</td>
									<td>Expired Count</td>
								</tr>
							</thead>
							<tbody>
								@foreach($data as $pack)
									<tr>
										<td>{{ $pack->category }}</td>
										<td>{{ $pack->member_count }}</td>
										<td>{{ $pack->invoiced_count }}</td>
										<td>{{ $pack->expired_count }}</td>
									</tr>
								@endforeach
							</tbody>
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

<script>
    $(document).ready(function() {
        $('#users-table').DataTable({
            responsive: true
        });
    });
</script>

@endsection