@extends('layouts.app')

@section('content')

    @if(session('status'))
        <div class="alert alert-success">
            <strong>Success!</strong> {{ session('status') }}
        </div>
    @endif

    <div class="row">
        <a href="{{ route('create.point.of.interest') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Create Point of Interest
        </a>
    </div>

    <br>

    <div class="row">
        <table id="poi-list-table" class="table table-striped table-condensed table-hover table-bordered table-advance display compact stripe order-column text-center" style="width:100%">
            <thead>
            <tr>
                <th class="text-center">City ID</th>
                <th class="text-center">City Name</th>
                <th class="text-center">Action</th>
            </tr>
            </thead>
            {{--<tfoot>
            <tr>
                <th>City ID</th>
                <th>City Name</th>
                <th>Action</th>
            </tr>
            </tfoot>--}}
        </table>
    </div>

@endsection

@section('js')
    <script>

        $(document).ready(function() {
            $('#poi-list-table').DataTable({
                "processing": true,
                "language": {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                // "serverSide": true,
                "pageLength": 25,
                "ajax": "{{ route('point.of.interest.list.json') }}",
                "columns": [
                    { "data": "id" },
                    { "data": "title" },
                    { "data": "action" }
                ],
                "columnDefs": [ {
                    "targets": 2,
                    "render": function ( data, type, row, meta ) {
                        return '<a href="{{ url('edit-point-of-interest') }}/'+row['id']+'" class="btn btn-success btn-xs">Edit</a>' +
                            '&nbsp;&nbsp;&nbsp;<a href="{{ url('delete-poi') }}/'+row['id']+'" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>';
                    }
                } ],
            });
        });

    </script>
@endsection
