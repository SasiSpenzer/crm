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
				<a href="{!! url('/dashboard') !!}">Dashboard</a> > Group Targets
			</div>

			<!-- /.panel-heading -->
			<div class="panel-body">
				<div class="tab-content">
					<div class="tab-pane fade in active" id="pending">
			
						<div style="padding: 10px">
							<div class="alert" id="flash_message" style="display:none">
							</div>
							<table width="100%" class="table table-striped table-bordered table-hover" id="users-table">
								<thead>
									<th>Name</th>
									<th width="35%">Percentage</th>
									<th>Revenue</th>
									<th width="30%">Rev. Target</th>
									<th width="30%">Mem. Target</th>
								</thead>
								<tbody>
									@foreach($output as $target)
										<?php

											if (intval($target->target) == 0)
												$precentage = 0.00;
											else
												$precentage = round(doubleval($target->package_amount) * 100 / doubleval($target->target), 2);
										?>
										<tr>
											<td>{{$target->name}}</td>
											<td>
												<div class="progress alt">
										<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="{{$precentage}}"
									  aria-valuemin="0" aria-valuemax="100" id="progress_my_target" style="width: {{$precentage}}%;">
											<span>{{$precentage}}%</span>
										</div>
										</div>
											</td>
											<td>{{ $target->package_amount }}</td>
											<td>
												<span id="target-span-{{$target->id}}">{{$target->target}}</span>
												<input class="hidden" type="text" id="target-text-{{$target->id}}" width="100%" value="{{$target->target}}">
												@if(Auth::user()->admin_level >= 2)
													<button data-id="{{$target->id}}" id="edit-btn-{{$target->id}}" class="btn btn-default btn-sm pull-right edit-btn">
														<span class="glyphicon glyphicon-pencil"></span>
													</button>
												@endif
												<button  data-id="{{$target->id}}" id="save-btn-{{$target->id}}" class="hidden btn btn-default btn-sm pull-right btn-danger save-btn">
													<span class="glyphicon glyphicon-floppy-disk"></span>
												</button>
											</td>
											<td>
												<span id="mem-target-span-{{$target->id}}">{{$target->mem_target}}</span>
												<input class="hidden" type="text" id="mem-target-text-{{$target->id}}" width="100%" value="{{$target->mem_target}}">
												@if(Auth::user()->admin_level >= 2)
													<button data-id="{{$target->id}}" id="mem-edit-btn-{{$target->id}}" class="btn btn-default btn-sm pull-right mem-edit-btn">
														<span class="glyphicon glyphicon-pencil"></span>
													</button>
												@endif
												<button  data-id="{{$target->id}}" id="mem-save-btn-{{$target->id}}" class="hidden btn btn-default btn-sm pull-right btn-danger mem-save-btn">
													<span class="glyphicon glyphicon-floppy-disk"></span>
												</button>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
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

<script type="text/javascript">
	$(document).ready(function(){

		$.ajaxSetup({
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        }
	    });

		$('.edit-btn').click(function(){
			var id = $(this).data('id');
			$('#save-btn-' + id).removeClass('hidden');
			$(this).addClass('hidden');
			$('#target-span-' + id).addClass('hidden');
			$('#target-text-' + id).removeClass('hidden');
		});

		$('.mem-edit-btn').click(function(){
			var id = $(this).data('id');
			$('#mem-save-btn-' + id).removeClass('hidden');
			$(this).addClass('hidden');
			$('#mem-target-span-' + id).addClass('hidden');
			$('#mem-target-text-' + id).removeClass('hidden');
		});

		$('.save-btn').click(function(){
			var id = $(this).data('id');
			var target = $('#target-text-' + id).val();
			$.ajax({
		        type: "post",
		        url: url + "/dashboard/group_target/save",
		        data: {
		      		user_id: id,
		      		target: target
		        }
		    }).done(function(data) {
		    	if (data == 'success') {
					$('#edit-btn-' + id).removeClass('hidden');
					$('#save-btn-' + id).addClass('hidden');
					$('#target-span-' + id).removeClass('hidden');
					$('#target-span-' + id).html(target);
					$('#target-text-' + id).addClass('hidden');
		    	}
		    });
		});

		$('.mem-save-btn').click(function(){
			let id = $(this).data('id');
			let target = $('#mem-target-text-' + id).val();
			alert(id + ' , ', target);
			$.ajax({
		        type: "post",
		        url: url + "/dashboard/group_mem_target/save",
		        data: {
		      		user_id: id,
		      		target: target
		        }
		    }).done(function(data) {
		    	if (data == 'success') {
					$('#mem-edit-btn-' + id).removeClass('hidden');
					$('#mem-save-btn-' + id).addClass('hidden');
					$('#mem-target-span-' + id).removeClass('hidden');
					$('#mem-target-span-' + id).html(target);
					$('#mem-target-text-' + id).addClass('hidden');
		    	}
		    });
		});

	})
</script>

<style type="text/css">
	.alt {
	  background-color:  #988e8c;
	  -webkit-box-shadow: none;
	  box-shadow: none;
	}

	/*input[type="text"] {
     width: 80%; 
     box-sizing: border-box;
     -webkit-box-sizing:border-box;
     -moz-box-sizing: border-box;
	}*/

.progress {
    position: relative;
}

.progress span {
    position: absolute;
    display: block;
    width: 100%;
    color: white;
 }

</style>

@endsection