<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>

    <title>{{ config('app.name', 'Lanka Property Web - LPW') }}</title>

    <!-- Styles -->

    <!-- Bootstrap Core CSS -->
    <link href="{{asset('/vendor/bootstrap/css/bootstrap.min.css')}}"" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="{{asset('/vendor/metisMenu/metisMenu.min.css')}}" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{asset('/vendor/datatables-plugins/dataTables.bootstrap.css')}}">
    <!-- DataTables Responsive CSS -->
    <link href="{{asset('/vendor/datatables-responsive/dataTables.responsive.css')}}" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{asset('/dist/css/sb-admin-2.css')}}" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="{{asset('/vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/css/bootstrap-datetimepicker.min.css')}}">
    <!-- Autocomplete CSS -->
    <link href="{{ URL::asset('/css/autocomplete/jquery-ui.css') }}" rel="stylesheet">
    <!-- Morris Charts CSS -->
    <link href="{{ URL::asset('/vendor/morrisjs/morris.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <!-- jQuery -->
    <script src="{{asset('/vendor/jquery/jquery.min.js')}}"></script>
    <!-- Autocomplete jQuery -->
    <script src="{{asset('/js/autocomplete/jquery-1.12.4.js')}}"></script>
    <script src="{{asset('/js/autocomplete/jquery-ui.js')}}"></script>
    <!-- Datetimepicker -->
    <script src="{{asset('/js/bootstrap-datetimepicker.min.js')}}"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="{{asset('/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="{{asset('/vendor/metisMenu/metisMenu.min.js')}}"></script>
    <!-- DataTables JavaScript -->
    <script src="{{asset('/vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('/vendor/datatables-plugins/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{asset('/vendor/datatables-responsive/dataTables.responsive.js')}}"></script>

    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>


    <!-- Morris Charts JavaScript -->
    <script src="{{asset('/vendor/raphael/raphael.min.js')}}"></script>
    <script src="{{asset('/vendor/morrisjs/morris.min.js')}}"></script>
    <!-- Custom Theme JavaScript -->
    <script src="{{asset('/dist/js/sb-admin-2.js')}}"></script>
    <script>

        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};

        var url = "{!! url(''); !!}";
    </script>

    @yield('css')

</head>
<body>
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            @include('includes.navbar_header')
            @includeIf('includes.navbar_top_links')
            @include('includes.navbar_side')

         </nav>
         <div id="page-wrapper">
            
            <div style="padding-top: 10px;">
                @include('includes.flash_msg')
            </div>

            @yield('content')
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->


</body>
</html>

@yield('js')
