@extends('layouts.app')

@section('css')
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	<style type="text/css">
		.modal-body{
			padding-left: 30px;
			padding-right: 30px;
		}
		.note-editable { 
			font-size: 13px; 
		}
	</style>
@endsection

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
				Auto Boost Set Standard Hours
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
							
							<form class="form-horizontal" action="{{url('/auto-boost/'.$autoboost->auto_boost_id)}}" method="POST">

								<input type="hidden" name="_method" value="PATCH">
								{{csrf_field()}}
								<div class="form-group">
									<label class="control-label col-sm-2" for="sel1">User ID:</label>
									<div class="col-sm-10">
										<input class="form-control" id="uid" name="uid" value="<? echo htmlspecialchars($autoboost->user_id); ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2" for="sel1">When do you want to boost the ad?:</label>
									<ul class="nav nav-pills" style="margin-bottom: 5px;">
										<li class="active">
											<a href="#monday" data-toggle="tab">MON</a>
										</li>
										<li>
											<a id="btn-grace-period" href="#tuesday" data-toggle="tab">TUE</a>
										</li>
										<li>
											<a id="btn-deactivated" href="#wensday" data-toggle="tab">WED</a>
										</li>
										<li>
											<a id="btn-expired" href="#thuesday" data-toggle="tab">THU</a>
										</li>
										<li>
											<a id="btn-expired" href="#friday" data-toggle="tab">FRI</a>
										</li>
										<li>
											<a id="btn-unallocated" href="#sateday" data-toggle="tab">SAT</a>
										</li>
										<li>
											<a id="btn-unallocated" href="#sunday" data-toggle="tab">SUN</a>
										</li>
									</ul>
									
								</div> 
								<div class="tab-pane fade in active" id="monday">
									<div class="form-group">
										<label class="control-label col-sm-12" for="sel1" style="text-align: left;">Choose the time slot to boost the Ad:</label>
										<div class="col-sm-6">
											<label class="control-label col-sm-12" for="sel1" style="text-align: left;">Time slot 1:</label>
											<select name="monday1" class="form-control" id="sel1">
												<option></option>
												@foreach($slots as $key => $slot)
												@if($key<23)
													<option value="{{$slot->auto_boost_summary_id}}">{{$slot->slot_id}}</option>
													@endif
												@endforeach
											</select>
										</div>
										<div class="col-sm-6">
											<label class="control-label col-sm-12" for="sel1" style="text-align: left;">Time slot 2:</label>
											<select name="monday2" class="form-control" id="sel1">
												<option></option>
												@foreach($slots as $key => $slot)
												@if($key>=23)
													<option value="{{$slot->auto_boost_summary_id}}">{{$slot->slot_id}}</option>
													@endif
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="tuesday">
									<div class="form-group">
										<label class="control-label col-sm-12" for="sel1" style="text-align: left;">Choose the time slot to boost the Ad:</label>
										<div class="col-sm-6">
											<label class="control-label col-sm-12" for="sel1" style="text-align: left;">Time slot 1:</label>
											<select name="tuesday1" class="form-control" id="sel1">
												<option></option>
												@foreach($slots as $key => $slot)
												@if($key<23)
													<option value="{{$slot->auto_boost_summary_id}}">{{$slot->slot_id}}</option>
													@endif
												@endforeach
											</select>
										</div>
										<div class="col-sm-6">
											<label class="control-label col-sm-12" for="sel1" style="text-align: left;">Time slot 2:</label>
											<select name="tuesday2" class="form-control" id="sel1">
												<option></option>
												@foreach($slots as $key => $slot)
												@if($key>=23)
													<option value="{{$slot->auto_boost_summary_id}}">{{$slot->slot_id}}</option>
													@endif
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="wensday">
									<div class="form-group">
										<label class="control-label col-sm-12" for="sel1" style="text-align: left;">Choose the time slot to boost the Ad:</label>
										<div class="col-sm-6">
											<label class="control-label col-sm-12" for="sel1" style="text-align: left;">Time slot 1:</label>
											<select name="wensday1" class="form-control" id="sel1">
												<option></option>
												@foreach($slots as $key => $slot)
												@if($key<23)
													<option value="{{$slot->auto_boost_summary_id}}">{{$slot->slot_id}}</option>
													@endif
												@endforeach
											</select>
										</div>
										<div class="col-sm-6">
											<label class="control-label col-sm-12" for="sel1" style="text-align: left;">Time slot 2:</label>
											<select name="wensday2" class="form-control" id="sel1">
												<option></option>
												@foreach($slots as $key => $slot)
												@if($key>=23)
													<option value="{{$slot->auto_boost_summary_id}}">{{$slot->slot_id}}</option>
													@endif
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="thuesday">
									<div class="form-group">
										<label class="control-label col-sm-12" for="sel1" style="text-align: left;">Choose the time slot to boost the Ad:</label>
										<div class="col-sm-6">
											<label class="control-label col-sm-12" for="sel1" style="text-align: left;">Time slot 1:</label>
											<select name="thuesday1" class="form-control" id="sel1">
												<option></option>
												@foreach($slots as $key => $slot)
												@if($key<23)
													<option value="{{$slot->auto_boost_summary_id}}">{{$slot->slot_id}}</option>
													@endif
												@endforeach
											</select>
										</div>
										<div class="col-sm-6">
											<label class="control-label col-sm-12" for="sel1" style="text-align: left;">Time slot 2:</label>
											<select name="thuesday2" class="form-control" id="sel1">
												<option></option>
												@foreach($slots as $key => $slot)
												@if($key>=23)
													<option value="{{$slot->auto_boost_summary_id}}">{{$slot->slot_id}}</option>
													@endif
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="friday">
									<div class="form-group">
										<label class="control-label col-sm-12" for="sel1" style="text-align: left;">Choose the time slot to boost the Ad:</label>
										<div class="col-sm-6">
											<label class="control-label col-sm-12" for="sel1" style="text-align: left;">Time slot 1:</label>
											<select name="friday1" class="form-control" id="sel1">
												<option></option>
												@foreach($slots as $key => $slot)
												@if($key<23)
													<option value="{{$slot->auto_boost_summary_id}}">{{$slot->slot_id}}</option>
													@endif
												@endforeach
											</select>
										</div>
										<div class="col-sm-6">
											<label class="control-label col-sm-12" for="sel1" style="text-align: left;">Time slot 2:</label>
											<select name="friday2" class="form-control" id="sel1">
												<option></option>
												@foreach($slots as $key => $slot)
												@if($key>=23)
													<option value="{{$slot->auto_boost_summary_id}}">{{$slot->slot_id}}</option>
													@endif
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="sateday">
									<div class="form-group">
										<label class="control-label col-sm-12" for="sel1" style="text-align: left;">Choose the time slot to boost the Ad:</label>
										<div class="col-sm-6">
											<label class="control-label col-sm-12" for="sel1" style="text-align: left;">Time slot 1:</label>
											<select name="sateday1" class="form-control" id="sel1">
												<option></option>
												@foreach($slots as $key => $slot)
												@if($key<23)
													<option value="{{$slot->auto_boost_summary_id}}">{{$slot->slot_id}}</option>
													@endif
												@endforeach
											</select>
										</div>
										<div class="col-sm-6">
											<label class="control-label col-sm-12" for="sel1" style="text-align: left;">Time slot 2:</label>
											<select name="sateday2" class="form-control" id="sel1">
												<option></option>
												@foreach($slots as $key => $slot)
												@if($key>=23)
													<option value="{{$slot->auto_boost_summary_id}}">{{$slot->slot_id}}</option>
													@endif
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="sunday">
									<div class="form-group">
										<label class="control-label col-sm-12" for="sel1" style="text-align: left;">Choose the time slot to boost the Ad:</label>
										<div class="col-sm-6">
											<label class="control-label col-sm-12" for="sel1" style="text-align: left;">Time slot 1:</label>
											<select name="sunday1" class="form-control" id="sel1">
												<option></option>
												@foreach($slots as $key => $slot)
												@if($key<23)
													<option value="{{$slot->auto_boost_summary_id}}">{{$slot->slot_id}}</option>
													@endif
												@endforeach
											</select>
										</div>
										<div class="col-sm-6">
											<label class="control-label col-sm-12" for="sel1" style="text-align: left;">Time slot 2:</label>
											<select name="sunday2" class="form-control" id="sel1">
												<option></option>
												@foreach($slots as $key => $slot)
												@if($key>=23)
													<option value="{{$slot->auto_boost_summary_id}}">{{$slot->slot_id}}</option>
													@endif
												@endforeach
											</select>
										</div>
									</div>
								</div>


								<div class="form-group">
									<div class="col-sm-offset-10 col-sm-12">
									  <button type="submit" class="btn btn-default">Save</button>
									</div>
								</div>
							</form> 

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
