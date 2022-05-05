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
				Check Phone Number
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
							<input type="text" class="form-control" style="width: 50%" name="mob_number" placeholder="Type mobile number. ex: 94-719876543" autocomplete="off">
							<input class="btn btn-primary btn-sm" type="submit" name="submit" value="Search">
						</form>
						
					</div>
				</div>

				@if(isset($contacts))
				<hr>

				<div class="tab-content">
					<div class="tab-pane fade in active">

						<div class="col-dm-12">
							<div class="row" style="margin: 10px;">

								<table class="table">
									<thead>
										<th>Customer Name</th>
										<th>email address</th>
										<th>Mobile Number</th>
									</thead>
									<tbody>
										@foreach($contacts as $contact)
											<tr>
												<td width="30%">
													@if($contact->customer != null)
														{{ $contact->customer->firstname . ' ' . $contact->customer->surname }}
													@endif
												</td>
												<td>
													@if($contact->customer != null)
														{{ $contact->customer->Uemail }}
													@endif
												</td>
												<td>
													{{ $contact->mob_number }}
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