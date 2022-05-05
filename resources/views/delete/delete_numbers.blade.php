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
			<div class="panel-heading" style="color: red;">
				Delete User's Phone Number
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				<!--  -->
				<!-- Tab panes -->
				<div class="tab-content">
					@if(session('msg'))
						<div class="alert alert-success" id="flash_message">{{ session('msg') }}</div>
					@endif

					<div class="tab-pane fade in active">
						<div class="alert" id="flash_message" style="display:none">{{session('msg')}}</div>

						<form action="" method="post" class="form-inline">
							{{ csrf_field() }}
							<input type="text" class="form-control" style="width: 40%" name="user_email" placeholder="Type user's email address. ex: username@gmail.com" autocomplete="off">
							<!--input type="text" class="form-control" style="width: 40%" name="user_number" placeholder="Type mobile number. ex: 94-719876543 (Optional)" autocomplete="off"-->
							<input class="btn btn-primary btn-sm" type="submit" name="submit" value="Search">
						</form>
						
					</div>
				</div>

				@if(isset($s_ad))
				<hr>

				<div class="tab-content">
					<div class="tab-pane fade in active">

						<h4>Email: {{$customer->Uemail}}</h4>
						<h4>Name: {{$customer->firstname . ' ' . $customer->surname}}</h4>

						<div class="col-dm-12">
							<div class="row" style="margin: 10px;">

								<table class="table">
									<thead>
										<!--th>Ad ID</th-->
										<th>Contact Name</th>
										<th>Phone Numbers</th>
									</thead>
									<tbody>
										@foreach($s_ad as $ad)
											<tr>
												<!--td width="33%">
													{{ $ad->ad_id }}
												</td-->
												<td width="33%">
													{{ $ad->mob_number_tag }}
												</td>
												<td style="padding-top: 0px;">
													<table class="table" style="margin-bottom: 0px;">
														<tbody>
															
																<tr id="tr-{{ $ad->contact_id }}">
																	<td width="33%">{{ $ad->mob_number }}</td>
																	<td><button data-cid="{{ $ad->contact_id }}" data-mob="{{ $ad->mob_number }}" class="btn btn-info btn-sm cdelete"><span class="glyphicon glyphicon-trash"></span> Delete</button></td>
																</tr>
															
														</tbody>
													</table>
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>

							</div>
						</div>
						
					</div>
				</div>
				@endif

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
		$(document).ready(function(){
			$('.cdelete').click(function(){
				cid = $(this).data('cid');
				mob = $(this).data('mob');
				var confirm = window.confirm('Are you sure, you want to delete '+mob+'?');
				if(confirm == true){
					$.ajax({
						url: 'mobile/' + cid,
						type: 'post',
						cache: false,
						data: {
				            "_token": "{{ csrf_token() }}"
				        },
						success: function(data){
							
							if(data == 1){
									$('#tr-'+cid).remove();
									$("#flash_message").css('display','block');
									$( "#flash_message" ).addClass( "alert-success" );
									$("#flash_message").html('The number has been deleted successfully!');
								}else{
									$("#flash_message").css('display','block');
									$( "#flash_message" ).addClass( "alert-warning" );
									$("#flash_message").html(data);
								}
						}
					});
				}	
			});
		});
	</script>
@endsection	