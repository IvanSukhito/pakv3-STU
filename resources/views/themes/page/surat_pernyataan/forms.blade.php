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
    $flagReadonly = 1;
}
else {
    $addAttribute = [
    ];
    $flagReadonly = 0;
}
?>
@extends(env('ADMIN_TEMPLATE').'._base.layout')

@section('title', $formsTitle)

@section('css')
    @parent
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('script-top')
    @parent
    <script>
        CKEDITOR_BASEPATH = '/assets/cms/js/ckeditor/';
    </script>
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
                        <li class="breadcrumb-item"><a href="<?php echo route('admin') ?>"><i class="fa fa-dashboard"></i> {{ __('general.home') }}</a></li>
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

                @if(in_array($viewType, ['create']))
                    {{ Form::open(['route' => ['admin.' . $thisRoute . '.store'], 'files' => true, 'id'=>'form', 'role' => 'form'])  }}
                @elseif(in_array($viewType, ['edit']))
                    {{ Form::open(['route' => ['admin.' . $thisRoute . '.update', $data->{$masterId}], 'method' => 'PUT', 'files' => true, 'id'=>'form', 'role' => 'form'])  }}
                @else
                    {{ Form::open(['route' => ['admin.' . $thisRoute . '.show', $data->{$masterId}], 'method' => 'GET', 'files' => true, 'id'=>'form', 'role' => 'form'])  }}
                @endif

                <div class="card-footer">

                    @if(in_array($viewType, ['create']))
                        <button type="submit" class="mb-2 mr-2 btn btn-success" title="@lang('general.save')">
                            <i class="fa fa-save"></i><span class=""> @lang('general.save')</span>
                        </button>
                    @elseif (in_array($viewType, ['edit']))
                        <button type="submit" name="save" value="2" class="mb-2 mr-2 btn btn-primary" title="@lang('general.send')" onclick="return checkConfirm()">
                            <i class="fa fa-send"></i><span class=""> @lang('general.send')</span>
                        </button>
                        <button type="submit" name="save" value="1" class="mb-2 mr-2 btn btn-info" title="@lang('general.draft')">
                            <i class="fa fa-save"></i><span class=""> @lang('general.draft')</span>
                        </button>
                    @elseif (in_array($viewType, ['show']) && $permission['edit'] == true && in_array($data->status, [1,2]))
                        <a href="<?php echo route('admin.' . $thisRoute . '.edit', $data->{$masterId}) ?>"
                           class="mb-2 mr-2 btn btn-primary" title="{{ __('general.edit') }}">
                            <i class="fa fa-pencil"></i><span class=""> {{ __('general.edit') }}</span>
                        </a>
                    @endif
                    <a href="<?php echo route('admin.' . $thisRoute . '.index') ?>" class="mb-2 mr-2 btn btn-warning"
                       title="{{ __('general.back') }}">
                        <i class="fa fa-arrow-circle-o-left"></i><span class=""> {{ __('general.back') }}</span>
                    </a>

                </div>

                @if(!in_array($viewType, ['show']))
                <div class="card-body">
                    <label for="all_ok_check"><input type="radio" class="all_ok_check" id="all_ok_check" name="flag_check_all"/> Setuju Semua</label>
                    <label for="all_cancel_check"><input type="radio" class="all_cancel_check" id="all_cancel_check" name="flag_check_all"/> Tolak Semua</label>
                </div>
                @endif
                <div class="card-body">
                    <h3 class="form-section first-form">Kegiatan</h3>
                    @if (in_array($viewType, ['show']) && in_array($data->status, [80]))
                        <p>
                            <a href="{!! route('admin.'.$thisRoute.'.showDupakPdf', $data->dupak_id) !!}" class="mb-2 mr-2 btn btn-primary" title="@lang('general.download_pdf') DUPAK">
                                <i class="fa fa-download"></i><span class=""> @lang('general.download_pdf') DUPAK</span>
                            </a>
                        </p>
                    @endif
                    <p>Perancang mengajukan:</p>
                    <ul>
                        <li>Surat Pernyataan:
                        @foreach($topId as $top)

                        <?php $dataTop = isset($dataTopKegiatan[$top]) ? $dataTopKegiatan[$top] : false;?>
                        <?php $getSuratPernyataan = isset($listSuratPernyataan[$top]) ? $listSuratPernyataan[$top] : 0;?>
                        <?php $dataAk = isset($kredit[$top]) ? $kredit[$top] : false;?>

                           <ul>
                               <?php $sumAk = 0;?>
                               @foreach($dataAk as $listAk)
                               <?php $sumAk += $listAk ?>
                               @endforeach

                                   <li>{!! $dataTop ? $dataTop['name'] : '' !!} - {!!number_format($sumAk,3)!!}
                                       @if (in_array($viewType, ['show']) && in_array($data->status, [80]))
                                           <a href="{!! route('admin.'.$thisRoute.'.showPdf', $getSuratPernyataan) !!}" class="mb-2 mr-2 btn btn-primary" title="@lang('general.download_pdf')">
                                               <i class="fa fa-download"></i><span class=""> @lang('general.download_pdf')</span>
                                           </a>
                                       @endif
                                   </li>

                           </ul>
                           @endforeach
                        </li>

                        <li>Total AK yang di ajukan: {!! number_format($totalAk, 3) !!}</li>
                        @if(in_array($data->status, [80,88,99]))
                            <li>Total AK yang di setujui: {!! number_format($data->total_kredit, 3) !!}</li>
                        @endif
                    </ul>
                </div>
                <div class="card-body">
                    @if(isset($dataKegiatan))
                        @foreach($dataKegiatan as $getPermen => $listTopKegiatan)
                            @foreach($listTopKegiatan as $getTop => $listJudul)
                                <?php
                                $getTopKegiatan = isset($dataTopKegiatan[$getTop]) ? $dataTopKegiatan[$getTop] : false;
                                ?>

                                <div class="card card-permen card-permen-<?php echo $getPermen ?>">

                                    <div class="card-header"><h3>{!! $getTopKegiatan ? $getTopKegiatan['name'] : '' !!}</h3></div>

                                    @foreach($listJudul as $getJudul => $listKegiatan)

                                        <div class="card-header"><h4>{!! $getJudul !!}</h4></div>
                                        <div class="card-body overflow">
                                            {!! persetujuan_sp_kegiatan_v3($listKegiatan[0]['child'], $dataJenjangPerancang, $dataUser->jenjang_perancang_id, $flagReadonly) !!}
                                        </div>

                                    @endforeach

                                </div>

                            @endforeach
                        @endforeach
                    @endif
                </div>
                <!-- /.card-body -->

                <div class="card-footer">

                    @if(in_array($viewType, ['create']))
                        <button type="submit" class="mb-2 mr-2 btn btn-success" title="@lang('general.save')">
                            <i class="fa fa-save"></i><span class=""> @lang('general.save')</span>
                        </button>
                    @elseif (in_array($viewType, ['edit']))
                        <button type="submit" name="save" value="2" class="mb-2 mr-2 btn btn-primary" title="@lang('general.send')" onclick="return checkConfirm()">
                            <i class="fa fa-send"></i><span class=""> @lang('general.send')</span>
                        </button>
                        <button type="submit" name="save" value="1" class="mb-2 mr-2 btn btn-info" title="@lang('general.draft')">
                            <i class="fa fa-save"></i><span class=""> @lang('general.draft')</span>
                        </button>
                    @elseif (in_array($viewType, ['show']) && $permission['edit'] == true && in_array($data->status, [1,2]))
                        <a href="<?php echo route('admin.' . $thisRoute . '.edit', $data->{$masterId}) ?>"
                           class="mb-2 mr-2 btn btn-primary" title="{{ __('general.edit') }}">
                            <i class="fa fa-pencil"></i><span class=""> {{ __('general.edit') }}</span>
                        </a>
                    @endif
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
        $(document).ready(function (e) {

        });

        $('#all_ok_check').change(function() {
            if($(this).prop('checked') === true) {
                $('.radio_button_ok').prop('checked', true);
                $('.message_kegiatan').val('');
            }
        });

        $('#all_cancel_check').change(function() {
            if($(this).prop('checked') === true) {
                $('.radio_button_cancel').prop('checked', true);
                let getMessage = prompt('Alasan di tolak?');
                if (getMessage) {
                    $('.message_kegiatan').val(getMessage);
                }
            }
        });

        $('.radio_button_ok').click(function() {
            if($(this).prop('checked') === true) {
                let getId = $(this).data('id');
                $('#message_kegiatan_' + getId).val('');
            }
        });

        $('.radio_button_cancel').click(function() {
            if($(this).prop('checked') === true) {
                let getId = $(this).data('id');
                let getMessage = prompt('Alasan di tolak?');
                if (getMessage) {
                    $('#message_kegiatan_' + getId).val(getMessage);
                }
            }
        });

        function checkConfirm() {
            return confirm("Menyetujui Surat Pernyataan?");
        }

    </script>
@stop
