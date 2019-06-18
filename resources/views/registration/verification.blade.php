<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Alder login page</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('scripts-head')
</head>
<body>

<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Registration</h1>
                                </div>
<?php

use Webcosmonauts\Alder\Models\User;
if($request) {
$user = User::where('id',$request->id)->get()->first();
if ($request->_token == md5($user->email)) {
    $User = User::where('id',$request->id)->get()->first();
    $User->is_active = 1;
    $User->email_verified_at = date("Y-m-d H:i:s");
    $User->updated_at = date("Y-m-d H:i:s");
    $User->save();
}    ?>
                                <div>
                                    Уважаемый посетитель, <br>

                                    Спасибо за активацию email! <br>

                                    Теперь вы можете войти. <br>
                                    <a href="{{url('/login')}}"> Our website</a>
                                    <br>

                                    С уважением,<br>

                                    Администрация сайта
                                </div>
<?php } else { ?>
                                <div>
                                    Ooopss)
                                    <br>

                                    С уважением,<br>

                                    Администрация сайта
                                </div>

<?php }?>



                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>


<!-- Core plugin JavaScript-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"
        integrity="sha256-H3cjtrm/ztDeuhCN9I4yh4iN2Ybx/y1RM7rMmAesA0k="
        crossorigin="anonymous"></script>


<!-- Custom scripts for all pages-->
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
<!-- Custom fonts for this template-->
<link href="{{ asset('css/all.min.css') }}" rel="stylesheet" type="text/css">
<link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
<link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
@yield('scripts-body')
</body>
</html>
<?php
