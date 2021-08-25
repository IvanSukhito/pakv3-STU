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
    <link rel="stylesheet" href="{{ asset('assets/cms/css/full.css') }}">
    @show
    @section('script-top')
    @show
</head>
<body class="hold-transition">
<div class="bglayout"></div>
@yield('content')
<script src="{{ asset('/assets/cms/js/app.js') }}"></script>
</body>
</html>
