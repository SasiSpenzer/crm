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
				Replace User's Phone Number
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				<!--  -->
				<!-- Tab panes -->
				<div class="tab-content">
					@if(session('msg'))
						<div class="alert alert-success" id="flash_message">{{ session('msg') }}</div>
					@endif
					@if(isset($msg))
						<div class="alert {{ $class }}" id="flash_message">{{ $msg }}</div>
					@endif
					

					<div class="tab-pane fade in active">
						<div class="alert" id="flash_message" style="display:none">{{session('msg')}}</div>

						<form action="" method="post" class="form-inline">
							{{ csrf_field() }}
							<input type="email" class="form-control" style="width: 50%" name="email" placeholder="Type user's email address. ex: username@gmail.com" autocomplete="off" required>
							<input class="btn btn-primary btn-sm" type="submit" name="submit" value="Search">
						</form>
						
					</div>
				</div>

				@if(isset($customer))
				<hr>

				<div class="tab-content">
					<div class="tab-pane fade in active">

						<h4>Email: {{$customer->Uemail}}</h4>
						<h4>Name: {{$customer->firstname . ' ' . $customer->surname}}</h4>
						@if(isset($contacts))

						<div class="col-dm-12">
							<div class="row" style="margin: 10px;">

								<table class="table">
									<thead>
										<!--th>AD ID</th-->
										<th>Contact Name</th>
										<th>Mobile Number</th>
										<th>Action</th>
									</thead>
									<tbody>
										<?php $i = 0; ?>
										@foreach($contacts as $contact)
										<?php $i++; ?>
											<tr>
												<!--td width="20%">{{ $contact->ad_id }}</td-->
												<td width="20%">{{ $contact->mob_number_tag }}</td>
												<td width="20%">
												<input id="oldnum-{{$i}}" type="hidden" name="mob_number" value="{{ $contact->mob_number }}" disabled="">
												<input id="contact_id-{{$i}}" type="hidden" name="contact_id" value="{{ $contact->contact_id }}" disabled="">
													<p id="old-{{$i}}" name="">{{ $contact->mob_number }}</p>
													
												</td>
												<td>
													<button id="edit-{{$i}}" data-id="{{$i}}" class="btn btn-sm btn-primary edit"><span class="glyphicon glyphicon-edit"></span></button>

													<input class="hidden" id="new-{{$i}}" type="text" name="" placeholder="Type new mobile number. ex: 94-719876543" style="width: 50%;" autocomplete="off">

													<button id="replace-{{$i}}" class="btn btn-sm btn-primary hidden replace" data-id="{{$i}}">Replace</button>
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>

							</div>
						</div>
						@endif
						
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
	@if(isset($contacts))
		<script type="text/javascript">
			$(document).ready(function(){
				$('.edit').click(function(){
					id = $(this).data('id');
					$('#new-'+id).removeClass('hidden');
					$('#replace-'+id).removeClass('hidden');
				});

				$('.replace').click(function(){
					id = $(this).data('id');
					contactid =$('#contact_id-'+id).val();
					//alert(contactid);
					old_number = $('#oldnum-'+id).val();
					new_number = $('#new-'+id).val();
					var confirm = window.confirm('Are you sure, you want to replace '+old_number+' with '+ new_number +'?');
					if(confirm == true){
						$.ajax({
							url: 'mobile/ajax',
							data: {},
							type: 'post',
							cache: false,
							data: {
					            _token: "{{ csrf_token() }}", uid:{{$customer->UID}}, old_number: old_number, new_number: new_number, contact_id:contactid
					        },
							success: function(data){
								if(data == 1){
									$('#new-'+id).addClass('hidden');
									$('#replace-'+id).addClass('hidden');
									$('#old-'+id).html(new_number);
									$("#flash_message").css('display','block');
									$( "#flash_message" ).addClass( "alert-success" );
									$("#flash_message").html('The number has been replaced successfully!');
								}else{
									$("#flash_message").css('display','block');
									$( "#flash_message" ).addClass( "alert-warning" );
									$("#flash_message").html(data);
								}
							},
							error: function(){
								alert('Something went wrong. Please, try again later!');
							}
						});
					}	
				});
			});
		</script>
	@endif
@endsection	