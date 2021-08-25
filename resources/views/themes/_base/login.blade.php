<!DOCTYPE html>
<html lang="en">
<head>
    @section('head')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    @show

    <title>{{ env('WEBSITE_NAME') }} | @yield('title')</title>

    @section('css')
    <link rel="stylesheet" href="{{ asset('assets/cms/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/cms/css/login.css') }}">
    @show
    @section('script-top')
    @show
</head>
<body class="hold-transition">
<div class="container-fluid" id="container-login">
    <div class="d-flex align-items-stretch" id="flex-login">
        <div class="col-12 col-md-6 col-xl-4 text-center">
            <h1 class="text-center" id="header-text">SISTEM INFORMASI JABATAN FUNGSIONAL PERANCANGAN PERATURAN PERUNDANG-UNDANGAN</h1>
            <img src="{{ asset('assets/cms/images/logo.png') }}" id="logo" alt="logo"/>
            <div class="login-box">
                <!-- /.login-logo -->
                @yield('content')
                <footer class="text-center">2021 &copy; PAK</footer>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-8" id="login-left-side">
            <div class="row align-items-center h-100">
                <div class="col-12 text-center">
                    <p>Klik Disini untuk Melihat Data Perancang</p>
                    <a href=""><h2 id="link-perancang">Lihat Data Perancang</h2></a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('/assets/cms/js/app.js') }}"></script>
</body>
</html>
