<!DOCTYPE html>
<html lang="en">
<head>
    @section('head')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    @show

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/icon') }}/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/icon') }}/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/icon') }}/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/icon') }}/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/icon') }}/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/icon') }}/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/icon') }}/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/icon') }}/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/icon') }}/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('assets/icon') }}/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/icon') }}/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/icon') }}/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/icon') }}/favicon-16x16.png">
    <link rel="manifest" href="{{ asset('assets/icon') }}/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('assets/icon') }}/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <title>{{ env('WEBSITE_NAME') }} | @yield('title')</title>

    @section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/chat.css') }}">
    @show
    @section('script-top')
    @show
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('admin.portal') }}" class="nav-link">@lang('general.portal')</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
              <a href="{{ route('admin.profile') }}" class="nav-link">@lang('general.profile')</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
              <a href="{{ route('admin.getProfile') }}" class="nav-link">@lang('general.edit_profile')</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
              <a href="{{ route('admin.logout') }}" class="nav-link">@lang('general.sign_out')</a>
            </li>

        </ul>
    </nav>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('admin.profile') }}" class="brand-link logo-switch">
            <span class="brand-image-xl logo-xs">{{ substr(env('WEBSITE_NAME'), 0, 2) }}</span>
            <span class="brand-image-xs logo-xl">{{ env('WEBSITE_NAME') }}</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar nav-child-indent flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('admin.profile') }}"
                           class="nav-link{{ in_array(Route::current()->action['as'], ['admin.profile', 'admin.getProfile', 'admin.getPassword']) ? ' active' : '' }}"
                           title="@lang('general.profile')">
                            <i class="nav-icon fa fa-user"></i>
                            <p>
                                @lang('general.profile')
                            </p>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a href="{{ route('admin.logout') }}" class="nav-link">
                            <i class="nav-icon fa fa-power-off"></i>
                            <p>
                                @lang('general.sign_out')
                            </p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        @yield('content')
    </div>

    <footer class="main-footer">
        <div class="float-right d-none d-sm-inline">
            v2.0.0
        </div>
        <strong>Copyright &copy; {{ date('Y') }} {{ env('WEBSITE_NAME') }}.</strong> All rights reserved.
    </footer>

</div>
@section('script-bottom')
<script src="{{ asset('/assets/js/app.js') }}"></script>
@if(session()->has('message'))
    <?php
    switch (session()->get('message_alert')) {
        case 2 : $type = 'success'; break;
        case 3 : $type = 'info'; break;
        default : $type = 'danger'; break;
    }
    ?>
<script type="text/javascript">
    'use strict';
    $.notify({
        // options
        message: '{!! session()->get('message') !!}'
    },{
        // settings
        type: '{!! $type !!}',
        placement: {
            from: "bottom",
            align: "right"
        },
    });
</script>
@endif
@show
</body>
</html>
