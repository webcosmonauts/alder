<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Alder</title>

    <link rel="icon" href="/alder.ico">

    <!-- Scripts -->


    <!-- Fonts -->

    <!--  -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>

    <link href="{{ asset('css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet">

    <!-- Material icons -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />

    <link href="{{ asset('material-dashboard/css/material-dashboard.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/icheck.css')}}">
    <link href="{{ asset('css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/select2.min.css')}}">


    <link href="{{ asset('css/custom-admin.css') }}" rel="stylesheet" type="text/css">
    @yield('scripts-head')
</head>
<body>
<!-- Page Wrapper -->
<div class="wrapper">
    <!-- Sidebar -->
@yield('sidebar')
<!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="main-panel ps-container ps-theme-default">

        <!-- Topbar -->
    @yield('topbar')
    <!-- End of Topbar -->

        <!-- Main Content -->
        <div class="content">

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

<!-- Bootstrap material design js -->
<script src="{{ asset('material-dashboard/js/core/bootstrap-material-design.min.js') }}"></script>
<script src="{{asset('material-dashboard/js/plugins/perfect-scrollbar.jquery.min.js')}}"></script>

<script src="{{ asset('material-dashboard/js/material-dashboard.min.js') }}"></script>

<!-- DataTables -->
<script src="{{asset('js/dataTables.min.js')}}"></script>
<script src="{{asset('js/dataTables.bootstrap4.min.js')}}"></script>

<!-- ICheck -->
<script src="{{asset('js/icheck.min.js')}}"></script>

<!-- Datepicker -->
<script src="{{asset('js/moment.min.js')}}"></script>
<script src="{{asset('js/tempusdominus-bootstrap-4.min.js')}}"></script>

<!-- Select 2 -->
<script src="{{asset('js/select2.min.js')}}"></script>

<!-- custom admin js / MAIN JS -->
<script src="{{asset('js/custom-admin.js')}}"></script>
</body>
</html>
