<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title') </title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{ asset("global_assets/css/icons/icomoon/styles.min.css") }}" rel="stylesheet" type="text/css">
    <link href="{{ asset("assets/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css">
    <link href="{{ asset("assets/css/bootstrap_limitless.min.css") }}" rel="stylesheet" type="text/css">
    <link href="{{ asset("assets/css/layout.min.css") }}" rel="stylesheet" type="text/css">
    <link href="{{ asset("assets/css/components.min.css") }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->
    <style>
        .fx-background {
            background: linear-gradient(rgba(0, 132, 66, 0.5) 100%, rgba(1, 70, 36, 0.5)100%);
            /* url('images/background/fx-background.webp'); */
            /* background: linear-gradient( rgba(0, 0, 0, 0.5) 100%, rgba(0, 0, 0, 0.5)100%), url('images/background/fx-background.webp'); */
            background-size: cover;
            background-position: center, right bottom;
            background-repeat: no-repeat, no-repeat;
            background-blend-mode: hard-light
        }
    </style>
    <!-- for page specific stylesheet -->
    @stack('styles')


</head>

<body>
    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">


            @yield('content')


        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->


    <!-- Core JS files -->
    <script src="{{ asset("global_assets/js/main/jquery.min.js") }}"></script>
    <script src="{{ asset("global_assets/js/main/bootstrap.bundle.min.js") }}"></script>
    <script src="{{ asset("global_assets/js/plugins/loaders/blockui.min.js") }}"></script>
    <script src="{{ asset("assets/js/validation/validate.min.js") }}"></script>
    <script src="{{ asset("assets/js/styling/uniform.min.js") }}"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script src="{{ asset("assets/js/app.js") }}"></script>

    <!--for page specific script-->

    @stack('scripts')

</body>

</html>
