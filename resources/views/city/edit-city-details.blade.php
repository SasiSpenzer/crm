@extends('layouts.app')

@section('css')
    <link href="{{asset('/vendor/summernote/summernote.css')}}" rel="stylesheet">
    <style type="text/css">
        .modal-body{
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

    <div class="row">
    <legend>Add Details: {{ $cities->city_name }} <a href="{{ route('list.city') }}" class="btn btn-danger btn-xs pull-right">
            <i class="fa fa-arrow-circle-left"></i> Go back to City List
        </a>
    </legend>

    </div>

    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('submit.edit.city.details') }}" method="post">

                {{ csrf_field() }}

                <input type="hidden" name="id" id="" value="{{ isset($cities->city_no) ? $cities->city_no : $city->city_id }}">

                <div class="form-group">
                    <label>Average Property
                        <label class="label label-primary">Sale</label>
                    </label>
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="sale_avg_house_price" id="" class="form-control" value="{{ $city->sale_avg_house_price }}" placeholder="Average House Sale Price (Rs.)">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="sale_avg_apartment_price" id="" class="form-control" value="{{ $city->sale_avg_apartment_price }}" placeholder="Average Apartment Sale Price (Rs.)">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="sale_avg_land_price" id="" class="form-control" value="{{ $city->sale_avg_land_price }}" placeholder="Average Land Sale Price (Rs.)">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Average Property
                        <label class="label label-primary">Rent</label></label>
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="rent_avg_house_price" id="" class="form-control" value="{{ $city->rent_avg_house_price }}" placeholder="Average House Rent Price (Rs.)">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="rent_avg_apartment_price" id="" class="form-control" value="{{ $city->rent_avg_apartment_price }}" placeholder="Average Apartment Rent Price (Rs.)">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="rent_avg_land_price" id="" class="form-control" value="{{ $city->rent_avg_land_price }}" placeholder="Average Land Rent Price (Rs.)">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Neighborhood Amenities</label>
                    <div class="row">
                        <div class="col-md-2">
                            <small>No. of Bus Stops</small>
                            <input type="text" name="num_bus_stops" id="" class="form-control" value="{{ $city->num_bus_stops }}" placeholder="No. of Bus Stops">
                        </div>
                        <div class="col-md-2">
                            <small>No. of Schools</small>
                            <input type="text" name="num_schools" id="" class="form-control" value="{{ $city->num_schools }}" placeholder="No. of Schools">
                        </div>
                        <div class="col-md-2">
                            <small>No. of Hospitals</small>
                            <input type="text" name="num_hospitals" id="" class="form-control" value="{{ $city->num_hospitals }}" placeholder="No. of Hospitals">
                        </div>
                        <div class="col-md-2">
                            <small>No. of Banks</small>
                            <input type="text" name="num_banks" id="" class="form-control" value="{{ $city->num_banks }}" placeholder="No. of Banks">
                        </div>
                        <div class="col-md-2">
                            <small>No. of Restaurants</small>
                            <input type="text" name="num_restaurants" id="" class="form-control" value="{{ $city->num_restaurants }}" placeholder="No. of Restaurants">
                        </div>
                        <div class="col-md-2">
                            <small>No. of Fuel Stations</small>
                            <input type="text" name="num_fuel_stations" id="" class="form-control" value="{{ $city->num_fuel_station }}" placeholder="No. of Fuel Stations">
                        </div>
                        {{--<div class="col-md-2">
                            <small>No. of Train Stations</small>
                            <input type="text" name="num_train_stations" id="" class="form-control" value="{{ $city->num_train_stations }}" placeholder="No. of Train Stations">
                        </div>--}}
                    </div>
                </div>

                <div class="form-group">
                <div class="row">
                    <div class="col-md-5">
                        <label>City Population</label>
                        <input type="text" class="form-control" name="city_population" value="{{ $city->city_population }}" placeholder="City Population">
                    </div>
                    <div class="col-md-2">
                        <label>City Radius (meters)</label>
                        <input type="text" class="form-control" name="city_radius" value="{{ $cities->city_radius }}" placeholder="City Radius (meters) eg: 2000">
                    </div>

                </div>
                 </div>

                <div class="form-group">
                    <label>City Description</label>
                    <div class="col-md-12">
                        <textarea class="input-block-level" id="summernote" name="city_description"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label>Maps Description</label>
                    <div class="col-md-12">
                        <textarea class="input-block-level" id="summernoteMaps" name="map_description"></textarea>
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

            $('#summernoteMaps').summernote({
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

            var cityDescription = $("<div/>").html("{{ isset($city->cityMaps->city_description) ? $city->cityMaps->city_description : $city->city_description }}").text();

            var map_description = $("<div/>").html("{{ isset($city->cityMaps->map_description) ? $city->cityMaps->map_description : $city->map_description }}").text();

            $("#summernote").summernote('code', cityDescription.replace('<div>', '<p>').replace('</div>', '</p>'));

            $("#summernoteMaps").summernote('code', map_description.replace('<div>', '<p>').replace('</div>', '</p>'));

        });
    </script>
@endsection
