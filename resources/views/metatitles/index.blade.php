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
				Meta titles <a href="metatitles/create" class="pull-right btn btn-primary">Create Meta details</a>
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
										<th>City</th>
										<th>Site</th>
										<th>Category</th>
										<th>Sub Category</th>
										<th>Acton</th>
									</tr>
								</thead>
								<tbody>
									@foreach($metatitles as $metatitle)
										<tr>
											<td>{{ $metatitle->city_name }}</td>
											<td>{{ $metatitle->site }}</td>
											<td>{{ $metatitle->category }}</td>
											<td>{{ $metatitle->sub_category }}</td>
											<td>
												<form method="post" action="metatitles/{{$metatitle->id}}">
													<input type="hidden" name="_method" value="delete">
													{{csrf_field()}}

													<a href="metatitles/{{$metatitle->id}}/edit" class="btn btn-primary">Edit</a>

													<input type="submit" name="" class="btn btn-danger remove pull-right" value="Remove">
												</form>
											</td>
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
				var r = confirm('Your are about to remove this metatitle!');
				if (!r)
					return false;
			});
		});
	</script>
@endsection