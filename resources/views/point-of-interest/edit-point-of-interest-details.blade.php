@extends('layouts.app')

@section('css')
    <link href="{{asset('/vendor/summernote/summernote.css')}}" rel="stylesheet">
    <style type="text/css">
        .modal-body {
            padding-left: 30px;
            padding-right: 30px;
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

    @if(session('status'))
        <div class="alert alert-success">
            <strong>Success!</strong> {{ session('status') }}
        </div>
    @endif

    <div class="row">
        <legend>Add Details: {{ isset($poi->title) ? $poi->title : '' }} <a href="{{ route('point.of.interest.list') }}" class="btn btn-danger btn-sm pull-right">
                <i class="fa fa-arrow-circle-left"></i> Go back to Point of Interest List
            </a>
        </legend>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('submit.poi.details') }}" method="post" enctype="multipart/form-data">

                {{ csrf_field() }}

                <input type="hidden" class="form-control" name="poi_id" id="" value="{{ isset($poi->id) ? $poi->id : '' }}">

                <div class="form-group">
                    <label>City</label>
                    <div class="row">
                        <div class="col-md-5">
                            <select name="city_id" id="" class="form-control">
                                @foreach($cities as $city)
                                    <option value="{{ $city->city_no }}" {{ isset($city->city_no) ? isset($poi->city_id) == $city->city_no ? 'selected' : '' : '' }}>{{ $city->city_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Title</label>
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="title" value="{{ isset($poi->title) ? $poi->title : '' }}" placeholder="Title">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Latitude</label>
                            <input type="text" name="latitude" id="" class="form-control" value="{{ isset($poi->latitude) ? $poi->latitude : '' }}" placeholder="Latitude">
                        </div>
                        <div class="col-md-3">
                            <label>Longitude</label>
                            <input type="text" name="longitude" id="" class="form-control" value="{{ isset($poi->longitude) ? $poi->longitude : ''}}" placeholder="Longitude">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="">Upload Images</label>
                    <input type="file" class="btn btn-s btn-primary" name="poi_images[]" id="poi-images" multiple>
                </div>

                <hr>

                <div class="row">
                    @if(isset($poiImages))
                        @foreach($poiImages as $image)
                            <div class="col-md-2 text-center">
                                <img class="thumbnail" src="{{ '/../../../../../images/point-of-interest/'.$image->poi_id.'/' . $image->image }}" alt="" width="240">
                                <a href="{{route('delete.poi.image', $image->id)}}}" class="btn btn-xs btn-danger">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <div class="col-md-12">
                        <textarea class="input-block-level" id="summernotPoi" name="description"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-2 col-md-offset-10">
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
            // Sasi Spenzer 2020-05-22
            $('#summernotPoi').summernote({
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

            // populate the field

            var description = $("<div/>").html("{{ isset($poi->description) ? $poi->description : '' }}").text();

            $("#summernotPoi").summernote('code', description.replace('<div>', '<p>').replace('</div>', '</p>'));

        });
    </script>
@endsection
