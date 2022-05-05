@extends('layouts.app')

@section('css')
	<link href="{{asset('/vendor/summernote/summernote.css')}}" / rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto" / rel="stylesheet">
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

@section('js')
	<script src="{{asset('/vendor/summernote/summernote.min.js')}}"></script>
	<script type="text/javascript">
		$(document).ready(function() {

	      $('#summernote').summernote({
			  toolbar: [
				  // [groupName, [list of button]]
				  ['style', ['bold', 'italic', 'underline', 'clear']],
				  ['font', ['strikethrough', 'superscript', 'subscript']],
				  ['fontsize', ['fontsize']],
				  ['color', ['color']],
				  ['para', ['ul', 'ol', 'paragraph']],
				  ['height', ['height']]
			  ],
		       callbacks: {
		            onImageUpload: function(files) {
		                for(let i=0; i < files.length; i++) {
		                    $.upload(files[i]);
		                }
		            }
		        },
		        //placeholder: 'Image upload test',
		        //width: 500,
		        height: 500,
		        fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica', 'Impact', 'Tahoma', 'Times New Roman', 'Verdana', 'Roboto'],
		        fontNamesIgnoreCheck: ['Roboto'],
		        fontSizes: ['10', '12', '13', '14', '16', '18', '24', '36', '48'],
		        toolbar: [
					['style', ['style']],
					['font', ['bold', 'underline', 'italic', 'clear']],
					['fontname', ['fontname']],
					['fontsize', ['fontsize']],
					['color', ['color']],
					['para', ['ul', 'ol', 'paragraph']],
					['insert', ['picture', 'myvideo', 'link', 'table', 'hr']],
					['view', ['fullscreen', 'codeview', 'help']],
				],
		   });

			$('#summernote').summernote('fontName', 'Roboto');
			$('#summernote').summernote('fontSize', 13);

			$.upload = function (file) {
			    let out = new FormData();
			    out.append('file', file, file.name);

			    $.ajax({
			        method: 'POST',
			        url: '{{url('article/upload')}}',
			        //check laravel document: https://laravel.com/docs/5.6/csrf#csrf-x-csrf-token
			        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			        contentType: false,
			        cache: false,
			        processData: false,
			        //dataType: 'JSON',
			        data: out,
			        success: function (img) {
			            $('#summernote').summernote('insertImage', img);
			        },
			        error: function (jqXHR, textStatus, errorThrown) {
			            console.error(textStatus + " " + errorThrown);
			        }
			    });
			};

			var postForm = function() {
				var content = $('textarea[name="article"]').html($('#summernote').code());
			}

		});
	</script>
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
							
							<form class="form-horizontal" action="{{url('/metatitles/')}}" method="POST">

								{{csrf_field()}}

								

								<div class="form-group">
									<label class="control-label col-sm-2" for="sel1">City:</label>
									<div class="col-sm-10">
										<select name="city" class="form-control" id="sel1">
											<option></option>
											<option value="Colombo">Colombo</option>
											@foreach($cities as $city)
												<option value="{{$city->city_name}}">{{$city->city_name}}</option>
											@endforeach
										</select>
									</div>
								</div> 
								<div class="form-group">
									<label class="control-label col-sm-2" for="sel1">Site:</label>
									<div class="col-sm-10">
										<select name="site" class="form-control" id="sel1">
											<option></option>
											<option value="LPW">LPW</option>
											<option value="Houselk">House.lk</option>
											<option value="Idealhomelk">Idealhome.lk</option>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-2" for="sel1">Category:</label>
									<div class="col-sm-10">
										<select name="category" class="form-control" id="sel1">
											<option></option>
											@foreach($categories as $category)
												<option value="{{$category}}">{{$category}}</option>
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
												<option value="{{$sub}}">{{$sub}}</option>
											@endforeach
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-2" for="sel1">meta title:</label>
									<div class="col-sm-10"></div>
									<div class="col-sm-10">
										<input class="form-control" id="meta_title" name="meta_title">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2" for="sel1">meta description:</label>
									<div class="col-sm-10"></div>
									<div class="col-sm-10">
										<input class="form-control" id="meta_desc" name="meta_desc">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2" for="sel1">og title:</label>
									<div class="col-sm-10"></div>
									<div class="col-sm-10">
										<input class="form-control" id="og_title" name="og_title">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2" for="sel1">og description:</label>
									<div class="col-sm-10"></div>
									<div class="col-sm-10">
										<input class="form-control" id="og_desc" name="og_desc">
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