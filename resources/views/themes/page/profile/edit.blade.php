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
@extends(env('ADMIN_TEMPLATE').'._base.profile')

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
                    <h1>{{ $formsTitle }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo route('admin.' . $thisRoute) ?>"> {{ __('general.title_home', ['field' => $thisLabel]) }}</a></li>
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

                {{ Form::open(['route' => ['admin.postProfile'], 'method' => 'POST', 'files' => true, 'id'=>'form', 'role' => 'form'])  }}

                <div class="card {!! $printCard !!}">
                    <div class="card-header">
                        <h3 class="card-title">@lang('Peran Staff')</h3>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">
                        @include(env('ADMIN_TEMPLATE').'.page.staff.generate_forms1')
                    </div>
                    <!-- /.card-body -->

                </div>

                <div class="card {!! $printCard !!}">
                    <div class="card-header">
                        <h3 class="card-title">@lang('Informasi Akun Staff')</h3>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">
                        @include(env('ADMIN_TEMPLATE').'.page.staff.generate_forms1_a')
                    </div>
                    <!-- /.card-body -->

                </div>

                <div class="card {!! $printCard !!}">
                    <div class="card-header">
                        <h3 class="card-title">@lang('Informasi Staff')</h3>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">
                        @include(env('ADMIN_TEMPLATE').'.page.staff.generate_forms2')
                        <hr class="hideAll perancang atasan"/>
                        @include(env('ADMIN_TEMPLATE').'.page.staff.generate_forms3')
                    </div>
                    <!-- /.card-body -->

                </div>

                <div class="card {!! $printCard !!}">
                    <div class="card-header hideAll perancang atasan">
                        <h3 class="card-title">@lang('Jenjang Staff')</h3>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">
                        @include(env('ADMIN_TEMPLATE').'.page.staff.generate_forms4')
                        <hr class="hideAll perancang"/>
                        @include(env('ADMIN_TEMPLATE').'.page.staff.generate_forms5')
                    </div>
                </div>

                <div class="card {!! $printCard !!}">
                    <div class="card-header hideAll perancang atasan">
                        <h3 class="card-title">@lang('Upload Dokumen')</h3>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">
                        @if(session()->get(env('APP_NAME').'admin_perancang'))
                        <div class="form-group">
                            <label class="file"><b>. SK Pengangkatan Pertama Ke Dalam Jabatan Jabatan Fungsional Perancang</b></label>
                            @if (isset($data) && strlen($data->file_sk_pengangkatan_perancang) > 0)
                                <br/>
                                <a href="{{ asset('uploads/register/'.$data->file_sk_pengangkatan_perancang) }}" target="_blank">Download</a>
                            @endif
                            <input type="file" name="sk_pengangkatan_perancang" value="{{ old('sk_pengangkatan_perancang') }}">
                        </div>
                        <div class="form-group">
                            <label class="file"><b> SK Kenaikan Pangkat Terakhir</b></label>
                            @if (isset($data) && strlen($data->file_sk_terakhir_perancang) > 0)
                                <br/>
                                <a href="{{ asset('uploads/register/'.$data->file_sk_terakhir_perancang) }}" target="_blank">Download</a>
                            @endif
                            <input type="file" name="sk_terakhir_perancang" value="{{ old('sk_terakhir_perancang') }}">
                        </div>
                        <div class="form-group">
                            <label class="file"><b>Kartu Pegawai</b></label>
                            @if (isset($data) && strlen($data->file_kartu_pegawai) > 0)
                                <br/>
                                <a href="{{ asset('uploads/register/'.$data->file_kartu_pegawai) }}" target="_blank">Download</a>
                            @endif
                            <input type="file" name="kartu_pegawai" value="{{ old('kartu_pegawai') }}">
                        </div>
                        <div class="form-group1">
                            <label class="dokument_penetapan_kredit" id="dokument_penetapan_kredit1"><b>Dokumen Penatapan Angka Kredit (Dari awal sampai akhir)</b></label>
                            @if (isset($seluruhPak))
                                @foreach($seluruhPak as $item)
                                    <br/>
                                    <a href="{{ asset('uploads/register/'.$item->file) }}" target="_blank">Download</a>
                                @endforeach
                            @endif
                            <input type="file" name="seluruh_pak[]" value="{{ old('seluruh_pak') }}">
                            <a href="#"  class="add_more_dokumen_fisik" >Tambah Dokument Penetapan angka kredit</a>
                        </div>
                        <div class="form-group">
                            <label class="file"><b>Ijazah</b></label>
                            @if (isset($data) && strlen($data->file_ijazah) > 0)
                                <br/>
                                <a href="{{ asset('uploads/register/'.$data->file_ijazah) }}" target="_blank">Download</a>
                            @endif
                            <input type="file" name="ijazah" value="{{ old('ijazah') }}">
                        </div>
                        <div class="form-group">
                            <label class="file"><b>Surat Keputusan atau Perintah Penempatan / Penugasan / Jabatan Terakhir</b></label>
                            @if (isset($data) && strlen($data->file_sttpl) > 0)
                                <br/>
                                <a href="{{ asset('uploads/register/'.$data->file_sttpl) }}" target="_blank">Download</a>
                            @endif
                            <input type="file" name="sttpl" value="{{ old('sttpl') }}">
                        </div>
                            @endif

                            @if(session()->get(env('APP_NAME').'admin_atasan'))
                                <div class="form-group">
                                    <label class="file"><b>Surat Keputusan atau Perintah Penempatan / Penugasan / Jabatan Terakhir</b></label>
                                    @if (isset($data) && strlen($data->file_sk_pengangkatan_perancang) > 0)
                                        <br/>
                                        <a href="{{ asset('uploads/register/'.$data->file_sk_pengangkatan_perancang) }}" target="_blank">Download</a>
                                    @endif
                                    <input type="file" name="sttpl" class="form-control">
                                </div>
                                @endif

                            @if(session()->get(env('APP_NAME').'admin_sekretariat'))
                                <div class="form-group">
                                    <label class="file"><b>Sk Sekretariat</b></label>
                                    @if (isset($data) && strlen($data->sk_sekretariat) > 0)
                                        <br/>
                                        <a href="{{ asset('uploads/register/'.$data->sk_sekretariat) }}" target="_blank">Download</a>
                                    @endif
                                    <input type="file" name="sk_sekretariat" class="form-control">
                                </div>
                            @endif

                            @if(session()->get(env('APP_NAME').'admin_tim_penilai'))
                                <div class="form-group">
                                    <label class="file"><b>SK Tim penilai</b></label>
                                    @if (isset($data) && strlen($data->sk_tim_penilai) > 0)
                                        <br/>
                                        <a href="{{ asset('uploads/register/'.$data->sk_tim_penilai) }}" target="_blank">Download</a>
                                    @endif
                                    <input type="file" name="sk_tim_penilai" class="form-control">
                                </div>
                            @endif
                            @if(session()->get(env('APP_NAME').'admin_atasan'))
                                <div class="form-group">
                                    <label class="file"><b>Surat Keputusan atau Perintah Penempatan / Penugasan / Jabatan Terakhir</b></label>
                                    @if (isset($data) && strlen($data->file_sttpl) > 0)
                                        <br/>
                                        <a href="{{ asset('uploads/register/'.$data->file_sttpl) }}" target="_blank">Download</a>
                                    @endif
                                    <input type="file" name="sttpl" value="{{ old('sttpl') }}">
                                </div>
                            @endif
                    </div>
                </div>

                <div class="card-footer">

                    <button type="submit" class="mb-2 mr-2 btn btn-primary" title="@lang('general.update')">
                        <i class="fa fa-save"></i><span class=""> @lang('general.update')</span>
                    </button>
                    <a href="<?php echo route('admin.profile') ?>" class="mb-2 mr-2 btn btn-warning"
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
        $(document).ready(function() {
            $('.checkThis').click(function() {
                var name = $(this).data('name');
                if ($(this).is(':checked')) {
                    $('.' + name).prop( "checked", true );
                }
                else {
                    $('#super_admin').prop( "checked", false );
                    $('.' + name).prop( "checked", false );
                }
            });
        });
        @if (in_array($viewType, ['show']))
        $('.checkThis').attr('disabled', true);
        $('#branch').attr('disabled', true);
        @endif

        $(document).ready(function() {

            let wrapper         = $(".form-group1");
            let add_button      = $(".add_more_dokumen_fisik");

            let x = 1; //
            $(add_button).click(function(e){
                e.preventDefault();
                    $(wrapper).append('<br/><input type="file" name="seluruh_pak[]"/>'); //add input box

            });

            // $(wrapper).on("click",".form-control", function(e){
            //     e.preventDefault(); $(this).parent('div').remove(); x--;
            // })
        });
    </script>
@stop
