@extends('themes._base.login')

@section('title', __('general.register'))

@section('css')
    @parent
    <style>
        @import url("//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css");
        .login-block{
            position: absolute;
            top: 0;
            bottom: 0;
            float:left;
            width:100%;
            /*padding : 50px 0;*/
            height: 100%;
        }
        .banner-sec{
            background:url({{ asset('assets/images/b1090242550d49f83c4086664191e903270634f4.png') }})  no-repeat left bottom;
            background-size:cover;
            box-shadow:inset 0 0 0 2000px rgba(5, 0, 0, 0.5);
            min-height: 100%;
            min-width: 1365px;
            border-radius: 0 10px 10px 0;
            padding-top: 100px;
            padding-right: 10px;
            margin-right: 10px;}
        .container{background:#fff; border-radius: 10px;border:1px solid #DADADa;position: absolute;
            top: 0;
            bottom: 0;}
        .carousel-inner{border-radius:0 10px 10px 0;}
        .carousel-caption{text-align:left; left:5%;}
        .login-sec{padding: 50px 30px; position:relative;}
        .login-sec .copy-text{position:absolute; width:80%; bottom:15px; font-size:12px; text-align:center;color:rgb(128, 125, 125);}
        .login-sec .copy-text i{color:#FEB58A;}
        .login-sec .copy-text a{color:#E36262;}
        .login-sec h2{margin-bottom:30px; font-weight:800; font-size:30px; color: #DE6262;}
        .login-sec h2:after{content:" "; width:100px; height:5px; background:#FEB58A; display:block; margin-top:20px; border-radius:3px; margin-left:auto;margin-right:auto}
        .btn-login{background: #DE6262; color:#fff; font-weight:600;}
        .banner-text{width:70%; position:absolute; bottom:40px; padding-left:20px;}
        .banner-text h2{color:#fff; font-weight:600;}
        .banner-text h2:after{content:" "; width:100px; height:5px; background:#FFF; display:block; margin-top:20px; border-radius:3px;}
        .banner-text p{color:#fff;}
        .btn {
            width: 100%;
            flex: 1 1 auto;
            margin-top:20px ;
            padding: 10px;
            text-align: center;
            text-transform: uppercase;
            transition: 0.5s;
            background-size: 200% auto;
            color: white;
            /* text-shadow: 0px 0px 10px rgba(0,0,0,0.2);*/
            box-shadow: 0 0 20px #eee;
            border-radius: 10px;
        }
        /* Demo Stuff End -> */
        /* <- Magic Stuff Start */
        .btn:hover {
            background-position: right center; /* change the direction of the change here */
        }
        .btn-1 {
            background-image: linear-gradient(to right, #f6d365 0%, #fda085 51%, #f6d365 100%);
        }

        .btn-2 {
            background-image: linear-gradient(to right, #fbc2eb 0%, #a6c1ee 51%, #fbc2eb 100%);
        }

        .btn-3 {
            background-image: linear-gradient(to right, #03be48 0%, #8fd3f4 51%, #84fab0 100%);
        }

        .btn-4 {
            background-image: linear-gradient(to right, #a1c4fd 0%, #c2e9fb 51%, #a1c4fd 100%);
        }
        .btn-5 {
            background-image: linear-gradient(to right, #ffecd2 0%, #fcb69f 51%, #ffecd2 100%);
        }
        body {
            font-family: "Open Sans";
        }
        .color-black {
            color: black;
        }
        .title {
            font-weight: 600;
        }
        .card {
            width: 50%;
            height: 50%;
            margin-left: 8em;
            margin-top: 10em;
        }
    </style>
@show

@section('content')
    <section class="login-block">
        <div class="container" style="max-width: 100%;max-height: 100%; ">
            <div class="row" style="position: absolute;top: 0;bottom: 0;width: 100%;">
                <div class="col-sm-12 col-md-12 banner-sec" style="background-color: rgba(0, 0, 0, 0.9);">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><b><center>Profile</center></b></h5>
                                        @if(session()->get(env('APP_NAME').'admin_super_admin'))
                                    <a href="{{route('admin.staff.index')}}" class="btn btn-primary">Menu</a>
                                            @elseif(session()->get(env('APP_NAME').'admin_perancang') || session()->get(env('APP_NAME').'admin_atasan') || session()->get(env('APP_NAME').'admin_sekretariat') || session()->get(env('APP_NAME').'admin_tim_penilai') )
                                        <a href="{{route('admin.profile')}}" class="btn btn-primary">Menu</a>
                                            @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><b><center>Aplikasi Penilaian Angka Kredit</center></b></h5>
                                    @if(session()->get(env('APP_NAME').'admin_super_admin'))
                                    <a href="{{route('admin.permen.index')}}" class="btn btn-primary">Menu</a>
                                    @elseif(session()->get(env('APP_NAME').'admin_perancang'))
                                        <a href="{{route('admin.kegiatan.index')}}" class="btn btn-primary">Menu</a>
{{--                                        <a href="#" class="btn btn-primary disabled">Menu</a>--}}
                                    @elseif(session()->get(env('APP_NAME').'admin_atasan'))
                                        <a href="{{route('admin.surat-pernyataan.index')}}" class="btn btn-primary">Menu</a>
{{--                                        <a href="#" class="btn btn-primary disabled">Menu</a>--}}
                                    @elseif(session()->get(env('APP_NAME').'admin_sekretariat'))
                                        <a href="{{route('admin.dupak.index')}}" class="btn btn-primary">Menu</a>
{{--                                        <a href="#" class="btn btn-primary disabled">Menu</a>--}}
                                    @elseif(session()->get(env('APP_NAME').'admin_tim_penilai'))
                                        <a href="{{route('admin.bapak.index')}}" class="btn btn-primary">Menu</a>
{{--                                        <a href="#" class="btn btn-primary disabled">Menu</a>--}}
                                        @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@stop
