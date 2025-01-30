<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="url" content="{{ url('/') }}">
    <title> @yield('title') </title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.6.7/sweetalert2.css" />
    <link href="{{ asset("global_assets/css/icons/icomoon/styles.min.css") }}" rel="stylesheet" type="text/css">
    <link href="{{ asset("global_assets/css/icons/fontawesome/styles.min.css") }}" rel="stylesheet" type="text/css">
    <link href="{{ asset("assets/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css">
    <link href="{{ asset("assets/css/bootstrap_limitless.min.css") }}" rel="stylesheet" type="text/css">
    <link href="{{ asset("assets/css/layout.min.css") }}" rel="stylesheet" type="text/css">
    <link href="{{ asset("assets/css/components.min.css") }}" rel="stylesheet" type="text/css">
    <link href="{{ asset("assets/css/colors.min.css") }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

<link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">

    <style>
        /* #spinner {
            position:fixed;
            width:100%;
            left:0;right:0;top:0;bottom:0;
            background-color: rgba(255,255,255,0.7);
            z-index:9999;
            display:none;
        }
        #spinner::after {
            content:'';
            display:block;
            position:absolute;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
        }  */

        .theme_xbox .pace_activity,
        .theme_xbox .pace_activity:after,
        .theme_xbox .pace_activity:before,
        .theme_xbox_lg .pace_activity,
        .theme_xbox_lg .pace_activity:after,
        .theme_xbox_lg .pace_activity:before,
        .theme_xbox_sm .pace_activity,
        .theme_xbox_sm .pace_activity:after,
        .theme_xbox_sm .pace_activity:before {
            border-top-color: #89c40f;
        }

        #spinner {
            z-index: 1051;
            background-color: rgba(255, 255, 255, 0.7);
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            display: none;
        }

        #spinner::after {
            content: '';
            z-index: 1051;
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            display: block;
        }

        .page-title .icon-arrow-left52 {
            cursor: pointer;
        }

        .page-title {
            text-transform: uppercase;
        }
    </style>
    <!-- for page specific stylesheet -->
    @stack('styles')


</head>

<body>

    <!-- Spinner -->
    <div class="pace-demo" id="spinner">
        <div class="theme_xbox">
            <div class="pace_progress" data-progress-text="60%" data-progress="60"></div>
            <div class="pace_activity"></div>
        </div>
    </div>

    <!-- Main navbar -->
    @include('layouts.header')
    <!-- Main navbar -->

    <!-- Page content -->
    <div class="page-content">

        <!-- Main sidebar -->
        @include('layouts.sidebar')
        <!-- Main sidebar -->

        <!-- Main content -->
        <div class="content-wrapper">


            @yield('content')


            <!-- Footer -->
            @include('layouts.footer')
            <!-- Footer -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->


    <!-- Core JS files -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.6.7/sweetalert2.all.min.js"></script>
    <script src="{{ asset("global_assets/js/main/jquery.min.js") }}"></script>
    <script src="{{ asset("global_assets/js/main/bootstrap.bundle.min.js") }}"></script>
    <script src="{{ asset("global_assets/js/plugins/loaders/blockui.min.js") }}"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    {{-- <script src="{{ asset("global_assets/js/plugins/notifications/sweet_alert.min.js") }}"></script> --}}
    <script src="{{ asset("global_assets/js/plugins/visualization/d3/d3.min.js") }}"></script>
    <script src="{{ asset("global_assets/js/plugins/visualization/d3/d3_tooltip.js") }}"></script>
    <script src="{{ asset("global_assets/js/plugins/forms/styling/switchery.min.js") }}"></script>
    <script src="{{ asset("global_assets/js/plugins/ui/moment/moment.min.js") }}"></script>
    <script src="{{ asset("global_assets/js/plugins/pickers/daterangepicker.js") }}"></script>
    <script src="{{ asset('global_assets/js/plugins/loaders/progressbar.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- add chart js -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
    <!-- add apexchart js -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- add buttons to datatable -->
    <!-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script> -->

    <script src="{{ asset("global_assets/js/plugins/tables/datatables/datatables.min.js") }}"></script>
    <script src="{{ asset("global_assets/js/plugins/forms/selects/select2.min.js") }}"></script>
    <script src="{{ asset("global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js") }}"></script>
    <script src="{{ asset("global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js") }}"></script>
    <script src="{{ asset("global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js") }}"></script>
    <script src="{{ asset("global_assets/js/plugins/tables/datatables/extensions/buttons.min.js") }}"></script>
    <script src="{{ asset("global_assets/js/plugins/forms/validation/validate.min.js") }}"></script>
    <script src="{{ asset("global_assets/js/plugins/forms/selects/bootstrap_multiselect.js") }}"></script>
    <script src="{{ asset("global_assets/js/plugins/forms/inputs/touchspin.min.js") }}"></script>
    <script src="{{ asset("global_assets/js/plugins/forms/styling/switch.min.js") }}"></script>
    <script src="{{ asset("global_assets/js/plugins/forms/styling/uniform.min.js") }}"></script>
    <script src="{{ asset("global_assets/js/plugins/forms/styling/switchery.min.js") }}"></script>
    <script src="{{ asset("global_assets/js/plugins/pickers/daterangepicker.js") }}"></script>


    <script src="{{ asset("assets/js/plugins/prism.min.js") }}"></script>
    <script src="{{ asset("assets/js/plugins/sticky.min.js") }}"></script>
    <script src="{{ asset("assets/js/app.js") }}"></script>

    <script src="{{ asset("assets/js/pages/components_scrollspy.js") }}"></script>
    <script src="{{ asset("global_assets/js/plugins/editors/summernote/summernote.min.js") }}"></script>
    {{-- <script src="{{ asset("global_assets/js/demo_pages/dashboard.js") }}"></script>
    <script src="{{ asset("global_assets/js/demo_charts/pages/dashboard/light/streamgraph.js") }}"></script>
    <script src="{{ asset("global_assets/js/demo_charts/pages/dashboard/light/sparklines.js") }}"></script>
    <script src="{{ asset("global_assets/js/demo_charts/pages/dashboard/light/lines.js") }}"></script>
    <script src="{{ asset("global_assets/js/demo_charts/pages/dashboard/light/areas.js") }}"></script>
    <script src="{{ asset("global_assets/js/demo_charts/pages/dashboard/light/donuts.js") }}"></script>
    <script src="{{ asset("global_assets/js/demo_charts/pages/dashboard/light/bars.js") }}"></script>
    <script src="{{ asset("global_assets/js/demo_charts/pages/dashboard/light/progress.js") }}"></script>
    <script src="{{ asset("global_assets/js/demo_charts/pages/dashboard/light/heatmaps.js") }}"></script>
    <script src="{{ asset("global_assets/js/demo_charts/pages/dashboard/light/pies.js") }}"></script>
    <script src="{{ asset("global_assets/js/demo_charts/pages/dashboard/light/bullets.js") }}"></script> --}}

    <script>
        $(document).ready(function() {
            // $('.table').DataTable({
            // 	autoWidth: false,
            // 	dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            // 	language: {
            // 		search: '<span>Filter:</span> _INPUT_',
            // 		lengthMenu: '<span>Show:</span> _MENU_',
            // 		paginate: { 'first': 'First', 'last': 'Last', 'next': '→', 'previous': '←' }
            // 	}
            // });

            $(".page-title .icon-arrow-left52").on("click", function() {
                window.history.back();
            })
        });
    </script>
    <!-- /theme JS files -->
    <!-- /theme JS files -->

    <script>

    </script>

    <!--for page specific script-->
    @stack('scripts')

</body>

</html>
