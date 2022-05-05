@extends('layouts.app')

@section('content')

    @if(session('status'))
        <div class="alert alert-success">
            <strong>Success!</strong> {{ session('status') }}
        </div>
    @endif

    <div class="row">
        <a href="{{ route('form.add.condo.details') }}" class="btn btn-primary btn-sm">
            <i class="fa fa-plus"></i> Add New Condo
        </a>
    </div>

    <br>

    <div class="row">
        <table id="city-list-table" class="table table-striped table-condensed table-hover table-bordered table-advance display compact stripe order-column text-center" style="width:100%">
            <thead>
            <tr>
                <th class="text-center">Condo ID</th>
                <th class="text-center">Condo Name</th>
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
            $('#city-list-table').DataTable({
                "processing": true,
                "language": {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                // "serverSide": true,
                "pageLength": 25,
                "ajax": "{{ route('json.condo.list') }}",
                "columns": [
                    { "data": "ID" },
                    { "data": "name" },
                    { "data": "action" }
                ],
                "columnDefs": [ {
                    "targets": 2,
                    "render": function ( data, type, row, meta ) {
                        return '<a href="{{ url('edit-condo-details') }}/'+row['ID']+'" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i> Edit  </a> ' +
                            ' <a href="{{ url('delete-condo') }}/'+row['ID']+'" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>';
                    }
                } ],
            });
        });

    </script>
@endsection
