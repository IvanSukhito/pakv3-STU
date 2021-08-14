<?php
switch ($viewType) {
    case 'create': $printCard = 'card-success'; break;
    case 'edit': $printCard = 'card-primary'; break;
    default: $printCard = 'card-info'; break;
}
if (in_array($viewType, ['show'])) {
    $addAttribute = [
        'disabled' => true
    ];
}
else {
    $addAttribute = [
    ];
}
?>
@extends(env('ADMIN_TEMPLATE').'._base.layout')

@section('title', $formsTitle)

@section('css')
    @parent
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $thisLabel }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo route('admin.profile') ?>"><i class="fa fa-user"></i> {{ __('general.profile') }}</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo route('admin.' . $thisRoute . '.index') ?>"> {{ __('general.title_home', ['field' => $thisLabel]) }}</a></li>
                        <li class="breadcrumb-item active">{{ $formsTitle }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card {!! $printCard !!}">
                <div class="card-header">
                    <h3 class="card-title">{{ $formsTitle }}</h3>
                </div>
                <!-- /.card-header -->

                {{ Form::open(['route' => ['admin.' . $thisRoute . '.storeSubmitKegiatan'], 'files' => true, 'id'=>'form', 'role' => 'form', 'method' => 'post'])  }}
                <input type="hidden" value="{!! $getDateRange !!}" name="daterange1">

                <div class="card-footer">
                    <button type="submit" class="mb-2 mr-2 btn btn-success" title="@lang('general.send')">
                        <i class="fa fa-paper-plane"></i><span class=""> @lang('general.send')</span>
                    </button>

                    <a href="<?php echo route('admin.' . $thisRoute . '.index') ?>" class="mb-2 mr-2 btn btn-warning"
                       title="{{ __('general.back') }}">
                        <i class="fa fa-arrow-circle-o-left"></i><span class=""> {{ __('general.back') }}</span>
                    </a>
                </div>
                <div class="card-body">
                    <h3 class="form-section first-form">Kegiatan</h3>
                    <p>Perancang mengajukan:</p>
                    <ul>
                        <li>Permen: {!! number_format($totalPermen, 0) !!}</li>
                        <li>Surat Pernyataan: {!! number_format($totalTop, 0) !!}</li>
                        <li>Nilai AK yang di ajukan: {!! number_format($totalAk, 3) !!}</li>
                    </ul>
                </div>
                <div class="card-body" style="overflow-x: auto">

                    @if(isset($dataKegiatan))
                        @foreach($dataKegiatan as $getPermen => $listTopKegiatan)
                            @foreach($listTopKegiatan as $getTop => $listJudul)
                                <?php
                                $getTopKegiatan = isset($dataTopKegiatan[$getTop]) ? $dataTopKegiatan[$getTop] : false;
                                ?>

                                <div class="card card-permen card-permen-<?php echo $getPermen ?>">
                                    <div class="card-header"><h3>{!! $getTopKegiatan['name'] !!}</h3></div>

                                    @foreach($listJudul as $getJudul => $listKegiatan)

                                    <div class="card-header"><h3>{!! $getJudul !!}</h3></div>
                                    <div class="card-body">
                                        {!! view_kegiatan_v3($listKegiatan, $dataJenjangPerancang, $dataUser->jenjang_perancang_id) !!}
                                    </div>

                                    @endforeach

                                </div>

                            @endforeach
                        @endforeach
                    @endif

                </div>
                <!-- /.card-body -->

                <div class="card-footer">

                    <button type="submit" class="mb-2 mr-2 btn btn-success" title="@lang('general.send')">
                        <i class="fa fa-paper-plane"></i><span class=""> @lang('general.send')</span>
                    </button>
                    <a href="<?php echo route('admin.' . $thisRoute . '.index') ?>" class="mb-2 mr-2 btn btn-warning"
                       title="{{ __('general.back') }}">
                        <i class="fa fa-arrow-circle-o-left"></i><span class=""> {{ __('general.back') }}</span>
                    </a>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </section>
@stop

@section('script-bottom')
    @parent
    @include(env('ADMIN_TEMPLATE').'._component.generate_forms_script')
    <script type="text/javascript">
        'use strict';
    </script>
@stop
