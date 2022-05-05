@extends('layouts.app')

@section('css')
    <link href="{{asset('/vendor/summernote/summernote.css')}}" rel="stylesheet">
    <style type="text/css">
        .modal-body{
            padding-left: 30px;
            padding-right: 30px;
        }
        .form-group {
            padding: 15px 0;
        }
    </style>
@endsection

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <legend>{{ isset($condo->name) ? $condo->name : 'Add New Condo' }}
            <a href="{{ route('condo.list') }}" class="btn btn-danger btn-xs pull-right">
                <i class="fa fa-arrow-circle-left"></i> Go back to Condo List
            </a>
        </legend>

    </div>

    <div class="row">
        <div class="col-md-12">
            <!--form action="{{-- route('submit.condo.details') --}}" method="post" enctype="multipart/form-data"-->
            <form action="{{url('/save-condo-details')}}" method="post" enctype="multipart/form-data">

                {{ csrf_field() }}

                <input type="hidden" class="form-control" name="condo_id" id="" value="{{ isset($condo->ID) ? $condo->ID : '' }}">

                <div class="form-group">
                   <label>City</label>
                   <div class="col-md-12">
                       <select name="city_id" class="form-control" id="">

                           @foreach($cityList as $city)
                               <option value="{{ $city->city_no }}" {{ isset($condo->city_id) ? $condo->city_id == $city->city_no ? 'selected' : '' : '' }}>{{ $city->city_name }}</option>
                           @endforeach

                       </select>
                   </div>
               </div>

                <div class="form-group">
                   <label>Condo Name</label>
                   <div class="col-md-12">
                       <input type="text" class="form-control" name="condo_name" id="" value="{{ isset($condo->name) ? $condo->name : '' }}" placeholder="Condo Name">
                   </div>
               </div>

                <div class="form-group">
                   <label>Location</label>
                   <div class="col-md-12">
                       <input type="text" class="form-control" name="location" id="" value="{{ isset($condo->location) ? $condo->location : '' }}" placeholder="Location">
                   </div>
                </div>

                <div class="form-group">
                   <label>Type</label>
                   <div class="col-md-12">
                       <input type="text" class="form-control" name="type" id="" value="{{ isset($condo->type) ? $condo->type : '' }}" placeholder="Type">
                   </div>
                </div>

                <div class="form-group">
                    <label>Developer</label>
                    <div class="col-md-12">
                        <input type="text" name="developer" id="" value="{{ isset($condo->developer) ? $condo->developer : '' }}" class="form-control" placeholder="Developer">
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-2">
                            <label>Floors</label>
                            <input type="text" name="floors" id="" class="form-control" value="{{ isset($condo->floors) ? $condo->floors : '' }}" placeholder="Floors">
                        </div>
                        <div class="col-md-2">
                            <label>Units</label>
                            <input type="text" name="units" id="" class="form-control" value="{{ isset($condo->units) ? $condo->units : '' }}" placeholder="Units">
                        </div>
                        <div class="col-md-2">
                            <label>Completion Date</label>
                            <input type="text" name="completion_date" id="" class="form-control" value="{{ isset($condo->completion_date) ? $condo->completion_date : '' }}" placeholder="Completion Date">
                        </div>
                        <div class="col-md-3">
                            <label>Latitude</label>
                            <input type="text" name="latitude" id="" class="form-control" value="{{ isset($condo->lat) ? $condo->lat : '' }}" placeholder="Latitude">
                        </div>
                        <div class="col-md-3">
                            <label>Longitude</label>
                            <input type="text" name="longitude" id="" class="form-control" value="{{ isset($condo->lon) ? $condo->lon : ''}}" placeholder="Longitude">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Price Range</label>
                            <input type="text" name="price_range" id="" class="form-control" value="{{ isset($condo->price_range) ? $condo->price_range : '' }}" placeholder="Price Range">
                        </div>
                        <div class="col-md-3">
                            <label>Telephone</label>
                            <input type="text" name="tel" id="" class="form-control" value="{{ isset($condo->tel) ? $condo->tel : '' }}" placeholder="Telephone">
                        </div>
                        <div class="col-md-3">
                            <label>Email</label>
                            <input type="text" name="email" id="" class="form-control" value="{{ isset($condo->email) ? $condo->email : '' }}" placeholder="Email">
                        </div>
                        <div class="col-md-3">
                            <label>Website</label>
                            <input type="text" name="website" id="" class="form-control" value="{{ isset($condo->website) ? $condo->website : '' }}" placeholder="Website">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6">
                        <label>Images</label>
                        <input type="file" class="form-control" name="condo_images[]" multiple>
                    </div>
                </div>

                <br><br>

                <div class="form-group">
                    <label>Condo Description</label>
                    <div class="col-md-12">
                        <textarea class="input-block-level" id="summernote" name="condo_description"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-4 col-md-offset-8">
                        <button type="submit" class="btn btn-success btn-lg btn-block"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <br><br>

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
                height: 400,
            });

            $.upload = function (file) {
                let out = new FormData();
                out.append('file', file, file.name);

                $.ajax({
                    method: 'POST',
                    {{--url: '{{url('article/upload')}}',--}}
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
                var content = $('textarea[name="content"]').html($('#summernote').code());
            };


            // populate the field

            var condoDescription = $("<div/>").html("{{ isset($condo->desc) ? $condo->desc : '' }}").text();

            $("#summernote").summernote('code', condoDescription);

        });
    </script>
@endsection
