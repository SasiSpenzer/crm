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
				Auto Boost Set Standard Hours <a href="auto-boost/create" class="pull-right btn btn-primary">Create Auto Boost</a>
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				<!--  -->
				<!-- Nav tabs -->

				<!-- Tab panes -->
				<div class="tab-content">
					<div class="tab-pane fade in active" id="all-members">
						<p>
							<div class="alert" id="flash_message" style="display:none">
							</div>
							<table width="100%" class="table table-striped table-bordered table-hover" id="users-table">
								<thead>
									<tr>
										<th>Slot</th>
										<th>Ad</th>
										<th>User</th>
										<th>Acton</th>
									</tr>
								</thead>
								<tbody>
									@foreach($autoboost as $boost)
										<tr>
											<td>{{ $autoboost->slot_id }}</td>
											<td>{{ $autoboost->ad_id }}</td>
											<td>{{ $autoboost->user_id }}</td>
											
										</tr>
									@endforeach
								</tbody>
							</table>
						</p>
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

@endsection

@section('js')
	<script type="text/javascript">
		$('document').ready(function(){
			$('.remove').click(function(){
				var r = confirm('Your are about to remove this boost!');
				if (!r)
					return false;
			});
		});
	</script>
@endsection