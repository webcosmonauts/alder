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

    <!-- Styles -->
    <link href="{{ asset('css/admin.min.css') }}" rel="stylesheet">

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
<script src="{{ asset('js/admin.min.js') }}"></script>
</body>
</html>
