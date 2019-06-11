<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->


    <!-- Fonts -->

    <!--  -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>

    @yield('scripts-head')
</head>
<body>
<!-- Page Wrapper -->
<div id="wrapper">
    <!-- Sidebar -->
@yield('sidebar')
<!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
        @yield('topbar')
        <!-- End of Topbar -->

            <!-- Begin Page Content -->
        @yield('main')
        <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="shadow sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Made with &#10084; by Webcosmonauts &copy; 2019</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Core plugin JavaScript-->


@yield('scripts-body')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"
        integrity="sha256-H3cjtrm/ztDeuhCN9I4yh4iN2Ybx/y1RM7rMmAesA0k="
        crossorigin="anonymous"></script>
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

<link href="{{ asset('css/all.min.css') }}" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
      rel="stylesheet">
<link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">


<!-- My custom stuff -->
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">


<!-- ICheck -->
<link rel="stylesheet" href="{{asset('css/icheck.css')}}">
<script src="{{asset('js/icheck.min.js')}}"></script>

<!-- Datepicker -->
<script src="{{asset('js/datepicker.min.js')}}"></script>
<link href="{{ asset('css/datepicker.min.css') }}" rel="stylesheet">


<!-- admin main js -->
<script src="{{asset('js/admin.js')}}"></script>

</body>
</html>
