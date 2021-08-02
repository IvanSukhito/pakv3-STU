@extends('themes._base.login')

@section('title', __('general.login'))

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
            min-height:500px;
            border-radius: 0 10px 10px 0;
            padding:0;}
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
    </style>
@show

@section('content')
    <section class="login-block">
        <div class="container" style="max-width: 100%;max-height: 100%; ">
            <div class="row" style="position: absolute;top: 0;bottom: 0;width: 100%;">
                <div class="col-sm-12 col-md-4 login-sec">
                    <h2 class="text-center color-black"><b class="color-black title">SISTEM INFORMASI JABATAN FUNGSIONAL PERANCANGAN <br> PERATURAN PERUNDANG-UNDANGAN</b></h2>
                    <h2 class="text-center" style="margin-top: 40px;">
                        <img width="200px" height="" src="{{ asset('assets/images/e74ec2ba411c70834dbcf3dbc50a5ad6e86585af.png') }}">
                    </h2>
                    {{ Form::open(['route'=>'admin.login.post','role'=>'form', 'id'=>'form', 'class'=>'login-form', 'novalidate'=>'novalidate'])  }}
                    <div class="form-group">
                        <label class="text"><b>Username</b></label>
                        {{ Form::text('username', old('username'), ['id'=>'username', 'name'=>'username', 'placeholder'=>'Masukan username Anda', 'class'=>'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label class="text"><b>Kata Sandi</b></label>
                        {{ Form::password('password', ['id'=>'password', 'name'=>'password', 'placeholder'=>'Masukan Kata Sandi Anda', 'class'=>'form-control']) }}
                    </div>
                    @if($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="form-group">
                                <p><code>{{ $error }}</code></p>
                            </div>
                        @endforeach
                    @endif
                    <div class="form-group">
                        <button type="submit" class="btn btn-3">Masuk</button>
                        <a href="{{ URL::route('admin.get.register') }}" class="btn btn-3"> Mendaftar </a>
                    </div>
                    <div class="form-group" style="text-align: center;">
                        <a style="font-size: 18px;color:#03ee59;padding-left:5px;text-align:right !important;width:100%;" href="mailto:help.eperancang@gmail.com"><b style="text-align: right;">Butuh Bantuan? Klik Disini</b></a>
                    </div>
                    {{ Form::close() }}
                    <div class="copy-text">2020@PAK</div>
                </div>
                <div class="col-sm-12 col-md-8 banner-sec" style="background-color: rgba(0, 0, 0, 0.9);">
                    <div class="banner-text" style="padding-left: 10%;padding-right: 10%;margin: auto;vertical-align: middle;bottom:30%;">
                        <h1 style="font-size: 90px;color: #FFF;font-weight: 600;">Lihat Data Perancang</h1>
                        <a style="font-size: 18px;color:#03ee59;padding-left:5px;text-align:right !important;width:100%;" href="{{ route('admin.dataPerancang') }}"><b style="text-align: right;">Lihat Data Perancang >>></b></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
