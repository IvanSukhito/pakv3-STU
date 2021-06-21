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

$nomor = isset($data) ? $data->nomor : old('nomor');
$penilaian_tanggal = isset($data) ? $data->penilaian_tanggal : old('penilaian_tanggal');
$jabatan_pengusul = isset($data) ? $data->jabatan_pengusul : old('jabatan_pengusul');
$jabatan_pengusul_nip = isset($data) ? $data->jabatan_pengusul_nip : old('jabatan_pengusul_nip');
$lokasi_tanggal = isset($data) ? $data->lokasi_tanggal : old('lokasi_tanggal');
$tanggal = isset($data) ? $data->tanggal : old('tanggal');
$surat_pernyataan = isset($data) ? $data->surat_pernyataan : old('surat_pernyataan');
$lampiran = isset($data) ? json_decode($data->lampiran, true) : old('lampiran');

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
        let getUrlCkEditorFull = '{{ asset('assets/js/ckeditor/') }}';
        let getSplit = getUrlCkEditorFull.split('/');
        let getUrlCkEditor = '';
        for(let i=3; i<getSplit.length; i++) {
            getUrlCkEditor += '/'+getSplit[i];
        }
        CKEDITOR_BASEPATH = getUrlCkEditor + '/';
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
                @if($errors->any())
                <div class="card-body">
                    {!! implode('', $errors->all('<div class="col-md-12 text-red">:message</div>')) !!}
                </div>
                @endif

                @if(in_array($viewType, ['create']))
                    {{ Form::open(['route' => ['admin.' . $thisRoute . '.store'], 'files' => true, 'id'=>'form', 'role' => 'form'])  }}
                @elseif(in_array($viewType, ['edit']))
                    {{ Form::open(['route' => ['admin.' . $thisRoute . '.update', $data->{$masterId}], 'method' => 'PUT', 'files' => true, 'id'=>'form', 'role' => 'form'])  }}
                @else
                    {{ Form::open(['id'=>'form', 'role' => 'form'])  }}
                @endif

                <div class="card-body">
                    <h3 class="text-center">{{ $staffUnitKerja ? $staffUnitKerja->name : '' }}</h3>
                    <hr/>
                    <h5 class="text-center">
                        DAFTAR USUL PENETAPAN ANGKA KREDIT<br/>
                        JABATAN PERANCANG PERATURAN PERUNDANG-UNDANGAN<br/>
                        NOMOR: {{ Form::text('nomor', $nomor, array_merge(['id'=>'nomor', 'name'=>'nomor', 'placeholder'=>'Isi Nomor Dupak', 'class' => 'form-control col-12 col-sm-6 col-md-4 col-lg-3', 'style'=>'display: inline-block;'], $addAttribute)) }}
                    </h5>
                    <br/>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Masa Penilaian Tanggal</label>
                        <div class="col-sm-10">
                            {{ Form::text('penilaian_tanggal', $penilaian_tanggal, array_merge(['id'=>'penilaian_tanggal', 'name'=>'penilaian_tanggal', 'placeholder'=>'Isi Masa Penilaian Tanggal', 'class'=>'form-control datepicker', 'autocomplete'=>'off'], $addAttribute)) }}
                        </div>
                    </div>
                    <br/>
                    <div class="bg-gray-dark" style="padding-top: 75px; padding-bottom: 75px; margin-bottom: 30px; background-color: #9c9c9c;">
                        <h3 class="text-center"><b>Isi Keterangan Perorangan dan Unsur yang di nilai</b></h3>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Pilih Surat Pernyataan</label>
                        <div class="col-sm-10">
                            {{ Form::select('surat_pernyataan', $setList['surat_pernyataan'], $surat_pernyataan, array_merge(['id'=>'surat_pernyataan', 'name'=>'surat_pernyataan', 'class'=>'form-control'], $addAttribute)) }}
                        </div>
                    </div>
                    <div style="padding: 15px 0;">
                        Lampiran Usul/Bahan Yang Dinilai
                        <div id="add_lampiran">
                            @if ($lampiran == null)
                            <div class="lampiran row">
                                <div class="col-sm-10 col-md-11">
                                    {{ Form::text('lampiran[]', null, array_merge(['placeholder'=>'Isi Lampiran Usul/Bahan Yang Dinilai', 'required' => 'required', 'class' => 'form-control'], $addAttribute)) }}
                                </div>
                                @if (!in_array($viewType, ['show']))
                                <div class="col-sm-2 col-md-1">
                                    <a href="#" class="pull-left" onclick="return remove_lampiran(this)">
                                        <i class="icon-minus"></i> Hapus
                                    </a>
                                </div>
                                @endif
                                <div class="col-12">&nbsp;</div>
                            </div>
                            @else
                                @foreach($lampiran as $list)
                                    <div class="lampiran row">
                                        <div class="col-sm-10 col-md-11">
                                            {{ Form::text('lampiran[]', $list, array_merge(['placeholder'=>'Isi Lampiran Usul/Bahan Yang Dinilai', 'required' => 'required', 'class' => 'form-control'], $addAttribute)) }}
                                        </div>
                                        @if (!in_array($viewType, ['show']))
                                        <div class="col-sm-2 col-md-1">
                                            <a href="#" class="pull-left" onclick="return remove_lampiran(this)">
                                                <i class="icon-minus"></i> Hapus
                                            </a>
                                        </div>
                                        @endif
                                        <div class="col-12">&nbsp;</div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        @if (!in_array($viewType, ['show']))
                        <a href="#" onclick="return tambah_lapiran()"><i class="icon-plus"></i> Tambahkan Lampiran</a>
                        @endif
                    </div>
                    <div class="text-right">
                        <div class="row">
                            <div class="col-6 col-md-4 offset-md-4 col-lg-3 offset-lg-6 col-xl-2 offset-xl-8">
                                {{ Form::text('lokasi_tanggal', $lokasi_tanggal, array_merge(['placeholder'=>'.......', 'class'=>'form-control'], $addAttribute)) }}
                            </div>
                            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                                {{ Form::text('tanggal', $tanggal, array_merge(['placeholder'=>'..............', 'class'=>'form-control datepicker', 'autocomplete'=>'off'], $addAttribute)) }}
                            </div>
                        </div>
                    </div>
                    <table style="width: 100%; padding-bottom: 15px;">
                        <tr>
                            <td width="30%"><div class="text-center">Pejabat Pengusul</div></td>
                            <td width="40%">&nbsp;</td>
                            <td width="30%"><div class="text-center">Perancang yang bersangkutan</div></td>
                        </tr>
                        <tr>
                            <td width="30%"><div style="padding-bottom: 80px;">&nbsp;</div></td>
                            <td width="40%"><div style="padding-bottom: 80px;">&nbsp;</div></td>
                            <td width="30%"><div style="padding-bottom: 80px;">&nbsp;</div></td>
                        </tr>
                        <tr>
                            <td width="30%"><div class="text-center">{{ Form::text('jabatan_pengusul', $jabatan_pengusul, array_merge(['placeholder'=>'Isi Jabatan Pengusul', 'class' => 'form-control'], $addAttribute)) }}</div></td>
                            <td width="40%">&nbsp;</td>
                            <td width="30%"><div class="text-center">{{ $getStaff ? $getStaff->name : '' }}</div></td>
                        </tr>
                        <tr>
                            <td width="30%"><div class="text-center">{{ Form::text('jabatan_pengusul_nip', $jabatan_pengusul_nip, array_merge(['placeholder'=>'Isi Jabatan Pengusul NIP', 'class' => 'form-control'], $addAttribute)) }}</div></td>
                            <td width="40%">&nbsp;</td>
                            <td width="30%"><div class="text-center">{{ $getUser ? $getUser->username : '' }}</div></td>
                        </tr>
                    </table>
                    <div style="padding-bottom: 60px;">Catatan Tim Penilai</div>
                    <div>................, Tanggal .................,,........</div>
                    <div style="padding-bottom: 60px;">Ketua Tim Penilai</div>
                    <div>NIP</div>
                    <div style="padding-bottom: 60px;">Catatan Pejabat Penilai</div>
                    <div>................, Tanggal ...........................</div>
                    <div style="padding-bottom: 60px;">Pejabat Penilai</div>
                    <div style="padding-bottom: 15px;">NIP</div>

                    <div class="card-body">
                        @include(env('ADMIN_TEMPLATE').'._component.generate_forms')
                    </div>

                    <div class="clearfix"></div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">

                    @if(in_array($viewType, ['create']))
                        <button type="submit" class="mb-2 mr-2 btn btn-success" title="@lang('general.save')">
                            <i class="fa fa-save"></i><span class=""> @lang('general.save')</span>
                        </button>
                    @elseif (in_array($viewType, ['edit']))
                        <button type="submit" class="mb-2 mr-2 btn btn-primary" title="@lang('general.update')">
                            <i class="fa fa-save"></i><span class=""> @lang('general.update')</span>
                        </button>
                    @elseif (in_array($viewType, ['show']) && $permission['edit'] == true)
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
    <script>
        function tambah_lapiran() {
            var new_html = '<div class="lampiran row"><div class="col-sm-10 col-md-11">{{ Form::text('lampiran[]', null, array_merge(['placeholder'=>'Isi Lampiran Usul/Bahan Yang Dinilai', 'required' => 'required', 'class' => 'form-control'], $addAttribute)) }}</div><div class="col-sm-2 col-md-1"><a href="#" onclick="return remove_lampiran(this)"><i class="icon-minus"></i> Hapus</a></div><div class="col-12">&nbsp;</div></div>';
            jQuery('#add_lampiran').append(new_html);
            return false;
        }

        function remove_lampiran(curr) {
            if (jQuery('.lampiran').length > 1) {
                jQuery(curr).parent().parent().remove();
            }
            else {
                alert("Tidak bisa menghapus semua Lampiran Usul/Bahan Yang Dinilai");
            }
            return false;
        }

    </script>
@stop
