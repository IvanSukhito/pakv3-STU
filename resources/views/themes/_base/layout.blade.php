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
{{--                    <li class="nav-item">--}}
{{--                        <a href="{{ route('admin.profile') }}"--}}
{{--                           class="nav-link{{ in_array(Route::current()->action['as'], ['admin.profile', 'admin.getProfile', 'admin.getPassword']) ? ' active' : '' }}"--}}
{{--                           title="@lang('general.profile')">--}}
{{--                            <i class="nav-icon fa fa-user"></i>--}}
{{--                            <p>--}}
{{--                                @lang('general.profile')--}}
{{--                            </p>--}}
{{--                        </a>--}}
{{--                    </li>--}}
                    @if(session()->get(env('APP_NAME').'admin_perancang'))
                    <li class="nav-item{{ strpos(Route::current()->action['as'], 'admin.kegiatan.') === 0 ? ' menu-open' : '' }}">
                        <a href="#" class="nav-link{{ strpos(Route::current()->action['as'], 'admin.kegiatan.') === 0 ? ' active' : '' }}"
                           title="@lang('general.kegiatan')">
                            <i class="nav-icon fa fa-book"></i>
                            <p>
                                @lang('general.kegiatan')
                            </p>
                            <i class="right fa fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.kegiatan.index') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.kegiatan.') === 0 && Route::current()->action['as'] != 'admin.kegiatan.create' ? ' active' : '' }}"
                                   title="@lang('general.kegiatan')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.kegiatan')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.kegiatan.create') }}"
                                   class="nav-link{{ Route::current()->action['as'] == 'admin.kegiatan.create' ? ' active' : '' }}"
                                   title="@lang('general.create_kegiatan')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.create_kegiatan')</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item{{ strpos(Route::current()->action['as'], 'admin.surat-pernyataan.') === 0 ? ' menu-open' : '' }}">
                        <a href="#" class="nav-link{{ strpos(Route::current()->action['as'], 'admin.surat-pernyataan.') === 0 ? ' active' : '' }}"
                           title="@lang('general.surat_pernyataan')">
                            <i class="nav-icon fa fa-envelope"></i>
                            <p>
                                @lang('general.surat_pernyataan')
                            </p>
                            <i class="right fa fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.surat-pernyataan.index') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.surat-pernyataan.') === 0 && (!(Route::current()->action['as'] == 'admin.surat-pernyataan.create' || Route::current()->action['as'] == 'admin.surat-pernyataan.verification')) ? ' active' : '' }}"
                                   title="@lang('general.surat_pernyataan')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.surat_pernyataan')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.surat-pernyataan.create') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.surat-pernyataan.create' ? ' active' : '' }}"
                                   title="@lang('general.create_surat_pernyataan')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.create_surat_pernyataan')</p>
                                </a>
                            </li>
                            @if(session()->get(env('APP_NAME').'admin_atasan'))
                            <li class="nav-item">
                                <a href="{{ route('admin.surat-pernyataan.verification') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.surat-pernyataan.verification' ? ' active' : '' }}"
                                   title="@lang('general.verification')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.verification')</p>
                                </a>
                            </li>
                                @endif
                        </ul>
                    </li>
                    <li class="nav-item{{ strpos(Route::current()->action['as'], 'admin.dupak.') === 0 ? ' menu-open' : '' }}">
                        <a href="#" class="nav-link{{ strpos(Route::current()->action['as'], 'admin.dupak.') === 0 ? ' active' : '' }}"
                           title="@lang('general.dupak')">
                            <i class="nav-icon fa fa-briefcase"></i>
                            <p>
                                @lang('general.dupak')
                            </p>
                            <i class="right fa fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.dupak.index') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.dupak.') === 0 && (!(Route::current()->action['as'] == 'admin.dupak.create' || Route::current()->action['as'] == 'admin.dupak.verification')) ? ' active' : '' }}"
                                   title="@lang('general.dupak')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.dupak')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.dupak.create') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.dupak.create' ? ' active' : '' }}"
                                   title="@lang('general.create_dupak')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.create_dupak')</p>
                                </a>
                            </li>
                            @if(session()->get(env('APP_NAME').'admin_sekretariat')  || session()->get(env('APP_NAME').'admin_tim_penilai'))
                            <li class="nav-item">
                                <a href="{{ route('admin.dupak.verification') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.dupak.verification' ? ' active' : '' }}"
                                   title="@lang('general.verification')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.verification')</p>
                                </a>
                            </li>
                                @endif
                        </ul>
                    </li>
                    @endif

{{--                    @if(request()->route()->getName() == 'admin.profile.')--}}
{{--                        <a href="#" class="nav-link{{ strpos(Route::current()->action['as'], 'admin.surat-pernyataan.') === 0 ? ' active' : '' }}"--}}
{{--                           title="@lang('general.surat_pernyataan')" style="display: none">--}}
{{--                            <i class="nav-icon fa fa-envelope"></i>--}}
{{--                            <p>--}}
{{--                                @lang('general.surat_pernyataan')--}}
{{--                            </p>--}}
{{--                            <i class="right fa fa-angle-left"></i>--}}
{{--                        </a>--}}
{{--                    @endif--}}

                    @if(session()->get(env('APP_NAME').'admin_atasan'))
                        <li class="nav-item{{ strpos(Route::current()->action['as'], 'admin.surat-pernyataan.') === 0 ? ' menu-open' : '' }}">
                            <a href="#" class="nav-link{{ strpos(Route::current()->action['as'], 'admin.surat-pernyataan.') === 0 ? ' active' : '' }}"
                               title="@lang('general.surat_pernyataan')">
                                <i class="nav-icon fa fa-envelope"></i>
                                <p>
                                    @lang('general.surat_pernyataan')
                                </p>
                                <i class="right fa fa-angle-left"></i>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.surat-pernyataan.index') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.surat-pernyataan.') === 0 && (!(Route::current()->action['as'] == 'admin.surat-pernyataan.create' || Route::current()->action['as'] == 'admin.surat-pernyataan.verification')) ? ' active' : '' }}"
                                       title="@lang('general.surat_pernyataan')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.surat_pernyataan')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.surat-pernyataan.create') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.surat-pernyataan.create' ? ' active' : '' }}"
                                       title="@lang('general.create_surat_pernyataan')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.create_surat_pernyataan')</p>
                                    </a>
                                </li>
                                @if(session()->get(env('APP_NAME').'admin_atasan'))
                                    <li class="nav-item">
                                        <a href="{{ route('admin.surat-pernyataan.verification') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.surat-pernyataan.verification' ? ' active' : '' }}"
                                           title="@lang('general.verification')">
                                            <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.verification')</p>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        @endif

                    @if(session()->get(env('APP_NAME').'admin_sekretariat'))
                        <li class="nav-item{{ strpos(Route::current()->action['as'], 'admin.dupak.') === 0 ? ' menu-open' : '' }}">
                            <a href="#" class="nav-link{{ strpos(Route::current()->action['as'], 'admin.dupak.') === 0 ? ' active' : '' }}"
                               title="@lang('general.dupak')">
                                <i class="nav-icon fa fa-briefcase"></i>
                                <p>
                                    @lang('general.dupak')
                                </p>
                                <i class="right fa fa-angle-left"></i>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.dupak.index') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.dupak.') === 0 && (!(Route::current()->action['as'] == 'admin.dupak.create' || Route::current()->action['as'] == 'admin.dupak.verification')) ? ' active' : '' }}"
                                       title="@lang('general.dupak')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.dupak')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.dupak.create') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.dupak.create' ? ' active' : '' }}"
                                       title="@lang('general.create_dupak')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.create_dupak')</p>
                                    </a>
                                </li>
                                @if(session()->get(env('APP_NAME').'admin_sekretariat'))
                                    <li class="nav-item">
                                        <a href="{{ route('admin.dupak.verification') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.dupak.verification' ? ' active' : '' }}"
                                           title="@lang('general.verification')">
                                            <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.verification')</p>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        <li class="nav-item{{ strpos(Route::current()->action['as'], 'admin.bapak.') === 0 ? ' menu-open' : '' }}">
                        <a href="#" class="nav-link{{ strpos(Route::current()->action['as'], 'admin.bapak.') === 0 ? ' active' : '' }}"
                           title="@lang('general.bapak')">
                            <i class="nav-icon fa fa-newspaper-o"></i>
                            <p>
                                @lang('general.bapak')
                            </p>
                            <i class="right fa fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.bapak.index') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.bapak.') === 0 && Route::current()->action['as'] != 'admin.bapak.create' ? ' active' : '' }}"
                                   title="@lang('general.bapak')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.bapak')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.bapak.create') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.bapak.create' ? ' active' : '' }}"
                                   title="@lang('general.create_bapak')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.create_bapak')</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                        @endif

{{--                    <li class="nav-item{{ strpos(Route::current()->action['as'], 'admin.surat-pernyataan.') === 0 ? ' menu-open' : '' }}">--}}
{{--                        <a href="#" class="nav-link{{ strpos(Route::current()->action['as'], 'admin.surat-pernyataan.') === 0 ? ' active' : '' }}"--}}
{{--                           title="@lang('general.surat_pernyataan')">--}}
{{--                            <i class="nav-icon fa fa-envelope"></i>--}}
{{--                            <p>--}}
{{--                                @lang('general.surat_pernyataan')--}}
{{--                            </p>--}}
{{--                            <i class="right fa fa-angle-left"></i>--}}
{{--                        </a>--}}
{{--                        <ul class="nav nav-treeview">--}}
{{--                            @if(session()->get(env('APP_NAME').'admin_perancang'))--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="{{ route('admin.surat-pernyataan.index') }}"--}}
{{--                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.surat-pernyataan.') === 0 && (!(Route::current()->action['as'] == 'admin.surat-pernyataan.create' || Route::current()->action['as'] == 'admin.surat-pernyataan.verification')) ? ' active' : '' }}"--}}
{{--                                   title="@lang('general.surat_pernyataan')">--}}
{{--                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.surat_pernyataan')</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="{{ route('admin.surat-pernyataan.create') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.surat-pernyataan.create' ? ' active' : '' }}"--}}
{{--                                   title="@lang('general.create_surat_pernyataan')">--}}
{{--                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.create_surat_pernyataan')</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            @endif--}}
{{--                            @if(session()->get(env('APP_NAME').'admin_atasan'))--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="{{ route('admin.surat-pernyataan.verification') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.surat-pernyataan.verification' ? ' active' : '' }}"--}}
{{--                                   title="@lang('general.verification')">--}}
{{--                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.verification')</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                                @endif--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                    @endif--}}
{{--                    @if(session()->get(env('APP_NAME').'admin_perancang') || session()->get(env('APP_NAME').'admin_sekretariat'))--}}
{{--                    <li class="nav-item{{ strpos(Route::current()->action['as'], 'admin.dupak.') === 0 ? ' menu-open' : '' }}">--}}
{{--                        <a href="#" class="nav-link{{ strpos(Route::current()->action['as'], 'admin.dupak.') === 0 ? ' active' : '' }}"--}}
{{--                           title="@lang('general.dupak')">--}}
{{--                            <i class="nav-icon fa fa-briefcase"></i>--}}
{{--                            <p>--}}
{{--                                @lang('general.dupak')--}}
{{--                            </p>--}}
{{--                            <i class="right fa fa-angle-left"></i>--}}
{{--                        </a>--}}
{{--                        <ul class="nav nav-treeview">--}}
{{--                            @if(session()->get(env('APP_NAME').'admin_perancang'))--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="{{ route('admin.dupak.index') }}"--}}
{{--                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.dupak.') === 0 && (!(Route::current()->action['as'] == 'admin.dupak.create' || Route::current()->action['as'] == 'admin.dupak.verification')) ? ' active' : '' }}"--}}
{{--                                   title="@lang('general.dupak')">--}}
{{--                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.dupak')</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="{{ route('admin.dupak.create') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.dupak.create' ? ' active' : '' }}"--}}
{{--                                   title="@lang('general.create_dupak')">--}}
{{--                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.create_dupak')</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            @endif--}}
{{--                            @if(session()->get(env('APP_NAME').'admin_sekretariat'))--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="{{ route('admin.dupak.verification') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.dupak.verification' ? ' active' : '' }}"--}}
{{--                                   title="@lang('general.verification')">--}}
{{--                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.verification')</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                                @endif--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                    @endif--}}
                    @if(session()->get(env('APP_NAME').'admin_tim_penilai'))
                        <li class="nav-item{{ strpos(Route::current()->action['as'], 'admin.dupak.') === 0 ? ' menu-open' : '' }}">
                            <a href="#" class="nav-link{{ strpos(Route::current()->action['as'], 'admin.dupak.') === 0 ? ' active' : '' }}"
                               title="@lang('general.dupak')">
                                <i class="nav-icon fa fa-briefcase"></i>
                                <p>
                                    @lang('general.dupak')
                                </p>
                                <i class="right fa fa-angle-left"></i>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.dupak.index') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.dupak.') === 0 && (!(Route::current()->action['as'] == 'admin.dupak.create' || Route::current()->action['as'] == 'admin.dupak.verification')) ? ' active' : '' }}"
                                       title="@lang('general.dupak')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.dupak')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.dupak.create') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.dupak.create' ? ' active' : '' }}"
                                       title="@lang('general.create_dupak')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.create_dupak')</p>
                                    </a>
                                </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.dupak.verification') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.dupak.verificationPenilai' ? ' active' : '' }}"
                                           title="@lang('general.verification')">
                                            <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.verification')</p>
                                        </a>
                                    </li>
                            </ul>
                        </li>
                    <li class="nav-item{{ strpos(Route::current()->action['as'], 'admin.bapak.') === 0 ? ' menu-open' : '' }}">
                        <a href="#" class="nav-link{{ strpos(Route::current()->action['as'], 'admin.bapak.') === 0 ? ' active' : '' }}"
                           title="@lang('general.bapak')">
                            <i class="nav-icon fa fa-newspaper-o"></i>
                            <p>
                                @lang('general.bapak')
                            </p>
                            <i class="right fa fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.bapak.index') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.bapak.') === 0 && Route::current()->action['as'] != 'admin.bapak.create' ? ' active' : '' }}"
                                   title="@lang('general.bapak')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.bapak')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.bapak.create') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.bapak.create' ? ' active' : '' }}"
                                   title="@lang('general.create_bapak')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.create_bapak')</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif



                    @if(session()->get(env('APP_NAME').'admin_super_admin') && request()->route()->getName() == 'admin.profile')


                        <li class="nav-item{{ strpos(Route::current()->action['as'], 'admin.staff.') === 0 ? ' menu-open' : '' }}">
                            <a href="#" class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.') === 0 ? ' active' : '' }}"
                               title="@lang('general.staff')">
                                <i class="nav-icon fa fa-users"></i>
                                <p>
                                    @lang('general.staff')
                                </p>
                                <i class="right fa fa-angle-left"></i>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.staff.index') }}"
                                       class="nav-link{{ Route::current()->action['as'] == 'admin.staff.index' ? ' active' : '' }}"
                                       title="@lang('general.staff')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.staff')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.staff.indexAtasan') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.indexAtasan') === 0 ? ' active' : '' }}"
                                       title="@lang('general.atasan')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.atasan')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.staff.indexPerancang') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.indexPerancang') === 0 ? ' active' : '' }}"
                                       title="@lang('general.perancang')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.perancang')</p>
                                    </a>
                                </li>
                                                            <li class="nav-item">
                                                                <a href="{{ route('admin.staff.indexCalonPerancang') }}"
                                                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.indexCalonPerancang') === 0 ? ' active' : '' }}"
                                                                   title="@lang('general.calon_perancang')">
                                                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.calon_perancang')</p>
                                                                </a>
                                                            </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.staff.indexSekretariat') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.indexSekretariat') === 0 ? ' active' : '' }}"
                                       title="@lang('general.sekretariat')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.sekretariat')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.staff.indexTimPenilai') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.indexTimPenilai') === 0 ? ' active' : '' }}"
                                       title="@lang('general.tim_penilai')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.tim_penilai')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.staff.create') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.staff.create' ? ' active' : '' }}"
                                       title="@lang('general.create_staff')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.create_staff')</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if(session()->get(env('APP_NAME').'admin_super_admin') && strpos(Route::current()->action['as'], 'admin.staff.') === 0)

                    <li class="nav-item{{ strpos(Route::current()->action['as'], 'admin.staff.') === 0 ? ' menu-open' : '' }}">
                        <a href="#" class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.') === 0 ? ' active' : '' }}"
                           title="@lang('general.staff')">
                            <i class="nav-icon fa fa-users"></i>
                            <p>
                                @lang('general.staff')
                            </p>
                            <i class="right fa fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.staff.index') }}"
                                   class="nav-link{{ Route::current()->action['as'] == 'admin.staff.index' ? ' active' : '' }}"
                                   title="@lang('general.staff')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.staff')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.staff.indexAtasan') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.indexAtasan') === 0 ? ' active' : '' }}"
                                   title="@lang('general.atasan')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.atasan')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.staff.indexPerancang') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.indexPerancang') === 0 ? ' active' : '' }}"
                                   title="@lang('general.perancang')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.perancang')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.staff.indexCalonPerancang') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.indexCalonPerancang') === 0 ? ' active' : '' }}"
                                   title="@lang('general.calon_perancang')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.calon_perancang')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.staff.indexSekretariat') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.indexSekretariat') === 0 ? ' active' : '' }}"
                                   title="@lang('general.sekretariat')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.sekretariat')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.staff.indexTimPenilai') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.indexTimPenilai') === 0 ? ' active' : '' }}"
                                   title="@lang('general.tim_penilai')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.tim_penilai')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.staff.create') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.staff.create' ? ' active' : '' }}"
                                   title="@lang('general.create_staff')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.create_staff')</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                        <li class="nav-item{{ strpos(Route::current()->action['as'], 'admin.gender.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.permen.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.user-registered.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.golongan.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.jabatan-perancang.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.jenjang-perancang.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.ms-kegiatan.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.pendidikan.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.unit-kerja.') === 0
                            ? ' menu-open' : '' }}" style="display: none">
                            <a href="#" class="nav-link{{ strpos(Route::current()->action['as'], 'admin.gender.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.permen.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.golongan.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.jabatan-perancang.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.jenjang-perancang.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.ms-kegiatan.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.pendidikan.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.unit-kerja.') === 0
                            ? ' active' : '' }}"
                               title="@lang('general.setting')" style="display:none">
                                <i class="nav-icon fa fa-gear"></i>
                                <p>
                                    @lang('general.setting')
                                </p>
                                <i class="right fa fa-angle-left"></i>
                            </a>

                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.permen.index') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.permen.index') === 0 ? ' active' : '' }}"
                                       title="@lang('Peraturan Mentri')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('Peraturan Mentri')</p>
                                    </a>
                                    <a href="{{ route('admin.user-registered.index') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.user-registered.index') === 0 ? ' active' : '' }}"
                                       title="@lang('Pengguna Terdaftar')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('Pengguna Terdaftar')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.gender.index') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.gender.index') === 0 ? ' active' : '' }}"
                                       title="@lang('general.gender')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.gender')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.golongan.index') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.golongan.index') === 0 ? ' active' : '' }}"
                                       title="@lang('general.golongan')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.golongan')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.jabatan-perancang.index') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.jabatan-perancang.index') === 0 ? ' active' : '' }}"
                                       title="@lang('general.jabatan_perancang')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.jabatan_perancang')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.jenjang-perancang.index') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.jenjang-perancang.index') === 0 ? ' active' : '' }}"
                                       title="@lang('general.jenjang_perancang')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.jenjang_perancang')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.ms-kegiatan.index') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.ms-kegiatan.index') === 0 ? ' active' : '' }}"
                                       title="@lang('general.ms_kegiatan')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.ms_kegiatan')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.pendidikan.index') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.pendidikan.index') === 0 ? ' active' : '' }}"
                                       title="@lang('general.pendidikan')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.pendidikan')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.unit-kerja.index') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.unit-kerja.index') === 0 ? ' active' : '' }}"
                                       title="@lang('general.unit_kerja')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.unit_kerja')</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if(session()->get(env('APP_NAME').'admin_super_admin') || strpos(Route::current()->action['as'], 'admin.permen.') === 0 )
                        <li class="nav-item{{ strpos(Route::current()->action['as'], 'admin.staff.') === 0 ? ' menu-open' : '' }}" style="display:none;">
                            <a href="#" class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.') === 0 ? ' active' : '' }}"
                               title="@lang('general.staff')">
                                <i class="nav-icon fa fa-users"></i>
                                <p>
                                    @lang('general.staff')
                                </p>
                                <i class="right fa fa-angle-left"></i>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.staff.index') }}"
                                       class="nav-link{{ Route::current()->action['as'] == 'admin.staff.index' ? ' active' : '' }}"
                                       title="@lang('general.staff')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.staff')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.staff.indexAtasan') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.indexAtasan') === 0 ? ' active' : '' }}"
                                       title="@lang('general.atasan')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.atasan')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.staff.indexPerancang') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.indexPerancang') === 0 ? ' active' : '' }}"
                                       title="@lang('general.perancang')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.perancang')</p>
                                    </a>
                                </li>
                                                            <li class="nav-item">
                                                                <a href="{{ route('admin.staff.indexCalonPerancang') }}"
                                                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.indexCalonPerancang') === 0 ? ' active' : '' }}"
                                                                   title="@lang('general.calon_perancang')">
                                                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.calon_perancang')</p>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="{{ route('admin.staff.indexSekretariat') }}"
                                                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.indexSekretariat') === 0 ? ' active' : '' }}"
                                                                   title="@lang('general.sekretariat')">
                                                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.sekretariat')</p>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="{{ route('admin.staff.indexTimPenilai') }}"
                                                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.indexTimPenilai') === 0 ? ' active' : '' }}"
                                                                   title="@lang('general.tim_penilai')">
                                                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.tim_penilai')</p>
                                                                </a>
                                                            </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.staff.create') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.staff.create' ? ' active' : '' }}"
                                       title="@lang('general.create_staff')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.create_staff')</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item{{ strpos(Route::current()->action['as'], 'admin.gender.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.permen.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.user-registered.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.golongan.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.jabatan-perancang.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.jenjang-perancang.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.ms-kegiatan.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.pendidikan.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.unit-kerja.') === 0
                            ? ' menu-open' : '' }}">
                            <a href="#" class="nav-link{{ strpos(Route::current()->action['as'], 'admin.gender.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.permen.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.golongan.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.jabatan-perancang.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.jenjang-perancang.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.ms-kegiatan.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.pendidikan.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.unit-kerja.') === 0
                            ? ' active' : '' }}"
                               title="@lang('general.setting')" style="display: none">
                                <i class="nav-icon fa fa-gear"></i>
                                <p>
                                    @lang('general.setting')
                                </p>
                                <i class="right fa fa-angle-left"></i>
                            </a>

                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.permen.index') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.permen.index') === 0 ? ' active' : '' }}"
                                       title="@lang('Peraturan Mentri')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('Peraturan Mentri')</p>
                                    </a>
                                    <a href="{{ route('admin.user-registered.index') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.user-registered.index') === 0 ? ' active' : '' }}"
                                       title="@lang('Pengguna Terdaftar')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('Pengguna Terdaftar')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.gender.index') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.gender.index') === 0 ? ' active' : '' }}"
                                       title="@lang('general.gender')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.gender')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.golongan.index') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.golongan.index') === 0 ? ' active' : '' }}"
                                       title="@lang('general.golongan')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.golongan')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.jabatan-perancang.index') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.jabatan-perancang.index') === 0 ? ' active' : '' }}"
                                       title="@lang('general.jabatan_perancang')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.jabatan_perancang')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.jenjang-perancang.index') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.jenjang-perancang.index') === 0 ? ' active' : '' }}"
                                       title="@lang('general.jenjang_perancang')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.jenjang_perancang')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.ms-kegiatan.index') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.ms-kegiatan.index') === 0 ? ' active' : '' }}"
                                       title="@lang('general.ms_kegiatan')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.ms_kegiatan')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.pendidikan.index') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.pendidikan.index') === 0 ? ' active' : '' }}"
                                       title="@lang('general.pendidikan')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.pendidikan')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.unit-kerja.index') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.unit-kerja.index') === 0 ? ' active' : '' }}"
                                       title="@lang('general.unit_kerja')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.unit_kerja')</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

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
