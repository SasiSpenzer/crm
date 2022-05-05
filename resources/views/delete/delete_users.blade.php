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
				<h4>Delete User Account</h4>
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				<!--  -->
				<!-- Tab panes -->
				<div class="tab-content">
					@if(isset($msg))
						<div class="alert {{ $class }}" id="flash_message">{{ $msg }}</div>
					@endif

					@if(!isset($customer) and !isset($msg))
					<div class="tab-pane fade in active">
						<div class="alert" id="flash_message" style="display:none">{{session('msg')}}</div>

						<form action="" method="post" class="form-inline">
							{{ csrf_field() }}
							<input type="email" class="form-control" style="width: 50%" name="email" placeholder="Type user's email address. ex: username@gmail.com" autocomplete="off" required>
							<input class="btn btn-primary btn-sm" type="submit" name="submit" value="Search">
						</form>
						
					</div>
					@endif
				</div>

				@if(isset($customer))

					<div class="tab-content">
						<div class="tab-pane fade in active">

							<h4>Email: {{$customer->Uemail}}</h4>
							<h4>Name: {{$customer->firstname . ' ' . $customer->surname}}</h4>

							<form action="user/confirm" method="post" class="form-inline">
								{{ csrf_field() }}
								<input type="hidden" name="uid" value="{{$customer->UID}}">
								<textarea name="reason" class="form-control" cols="75" placeholder="Tye Reason Here"></textarea><br><br>
								<input class="btn btn-danger btn-sm" type="submit" name="submit" value="Confirm Delete Account">
							</form>
							
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