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
				Meta titles
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
							
							<form class="form-horizontal" action="{{url('/metatitles/'.$metatitle->id)}}" method="POST">

								<input type="hidden" name="_method" value="PATCH">
								{{csrf_field()}}

								<div class="form-group">
									<label class="control-label col-sm-2" for="sel1">City:</label>
									<div class="col-sm-10">
										<select name="city" class="form-control" id="sel1">
											<option></option>
											<option value="Colombo" {{$metatitle->city_name == "Colombo" ? 'selected' : ''}}>Colombo</option>
											@foreach($cities as $city)
												<option value="{{$city->city_name}}" {{$metatitle->city_name == $city->city_name ? 'selected' : ''}}>{{$city->city_name}}</option>
											@endforeach
											{{-- <option value="colombo city" {{$metatitle->city_name == "colombo city" ? 'selected' : ''}}>Colombo City</option>
											<option value="0" {{$metatitle->city_name == "0" ? 'selected' : ''}}>Colombo All</option> --}}
										</select>
									</div>
								</div> 
								<div class="form-group">
									<label class="control-label col-sm-2" for="sel1">Site:</label>
									<div class="col-sm-10">
										<select name="site" class="form-control" id="sel1">
											<option></option>
											<option value="LPW" {{$metatitle->site == "LPW" ? 'selected' : ''}}>LPW</option>
											<option value="Houselk" {{$metatitle->site == "Houselk" ? 'selected' : ''}}>House.lk</option>
											<option value="Idealhomelk" {{$metatitle->site == "Idealhomelk" ? 'selected' : ''}}>Idealhome.lk</option>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-2" for="sel1">Category:</label>
									<div class="col-sm-10">
										<select name="category" class="form-control" id="sel1">
											<option></option>
											@foreach($categories as $category)
												<option value="{{$category}}" {{$metatitle->category == $category ? 'selected' : ''}}>{{$category}}</option>
											@endforeach
										</select>
									</div>
								</div> 

								<div class="form-group">
									<label class="control-label col-sm-2" for="sel1">Sub Category:</label>
									<div class="col-sm-10">
										<select name="sub_category" class="form-control" id="sel1">
											<option></option>
											@foreach($sub_categories as $sub)
												<option value="{{$sub}}" {{$metatitle->sub_category == $sub ? 'selected' : ''}}>{{$sub}}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2" for="sel1">meta title:</label>
									<div class="col-sm-10"></div>
									<div class="col-sm-10">
										<input class="form-control" id="meta_title" name="meta_title" value="<? echo htmlspecialchars($metatitle->meta_title); ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2" for="sel1">meta description:</label>
									<div class="col-sm-10"></div>
									<div class="col-sm-10">
										<input class="form-control" id="meta_desc" name="meta_desc" value="<? echo htmlspecialchars($metatitle->meta_desc); ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2" for="sel1">og title:</label>
									<div class="col-sm-10"></div>
									<div class="col-sm-10">
										<input class="form-control" id="og_title" name="og_title" value="<? echo htmlspecialchars($metatitle->og_title); ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2" for="sel1">og description:</label>
									<div class="col-sm-10"></div>
									<div class="col-sm-10">
										<input class="form-control" id="og_desc" name="og_desc" value="<? echo htmlspecialchars($metatitle->og_desc); ?>">
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
