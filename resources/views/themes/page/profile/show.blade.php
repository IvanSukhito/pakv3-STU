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
                    <h1>{{ $thisLabel }}</h1>
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

                {{ Form::open(['id'=>'form', 'role' => 'form'])  }}

                <div class="card-body">
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
                                </div>
                                <div class="form-group">
                                    <label class="file"><b> SK Kenaikan Pangkat Terakhir</b></label>
                                    @if (isset($data) && strlen($data->file_sk_terakhir_perancang) > 0)
                                        <br/>
                                        <a href="{{ asset('uploads/register/'.$data->file_sk_terakhir_perancang) }}" target="_blank">Download</a>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="file"><b>Kartu Pegawai</b></label>
                                    @if (isset($data) && strlen($data->file_kartu_pegawai) > 0)
                                        <br/>
                                        <a href="{{ asset('uploads/register/'.$data->file_kartu_pegawai) }}" target="_blank">Download</a>
                                    @endif
                                </div>
                                <div class="form-group1">
                                    <label class="dokument_penetapan_kredit" id="dokument_penetapan_kredit1"><b>Dokumen Penatapan Angka Kredit (Dari awal sampai akhir)</b></label>
                                    @if (isset($seluruhPak))
                                        @foreach($seluruhPak as $item)
                                            <br/>
                                            <a href="{{ asset('uploads/register/'.$item->file) }}" target="_blank">Download</a>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="file"><b>Ijazah</b></label>
                                    @if (isset($data) && strlen($data->file_ijazah) > 0)
                                        <br/>
                                        <a href="{{ asset('uploads/register/'.$data->file_ijazah) }}" target="_blank">Download</a>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="file"><b>Surat Keputusan atau Perintah Penempatan / Penugasan / Jabatan Terakhir</b></label>
                                    @if (isset($data) && strlen($data->file_sttpl) > 0)
                                        <br/>
                                        <a href="{{ asset('uploads/register/'.$data->file_sttpl) }}" target="_blank">Download</a>
                                    @endif
                                </div>
                            @endif

                            @if(session()->get(env('APP_NAME').'admin_atasan'))
                                <div class="form-group">
                                    <label class="file"><b>Surat Keputusan atau Perintah Penempatan / Penugasan / Jabatan Terakhir</b></label>
                                    @if (isset($data) && strlen($data->file_sk_pengangkatan_perancang) > 0)
                                        <br/>
                                        <a href="{{ asset('uploads/register/'.$data->file_sk_pengangkatan_perancang) }}" target="_blank">Download</a>
                                    @endif
                                </div>
                            @endif

                            @if(session()->get(env('APP_NAME').'admin_sekretariat'))
                                <div class="form-group">
                                    <label class="file"><b>Sk Sekretariat</b></label>
                                    @if (isset($data) && strlen($data->sk_sekretariat) > 0)
                                        <br/>
                                        <a href="{{ asset('uploads/register/'.$data->sk_sekretariat) }}" target="_blank">Download</a>
                                    @endif
                                </div>
                            @endif

                            @if(session()->get(env('APP_NAME').'admin_tim_penilai'))
                                <div class="form-group">
                                    <label class="file"><b>SK Tim penilai</b></label>
                                    @if (isset($data) && strlen($data->sk_tim_penilai) > 0)
                                        <br/>
                                        <a href="{{ asset('uploads/register/'.$data->sk_tim_penilai) }}" target="_blank">Download</a>
                                    @endif
                                </div>
                            @endif
                            @if(session()->get(env('APP_NAME').'admin_atasan'))
                                <div class="form-group">
                                    <label class="file"><b>Surat Keputusan atau Perintah Penempatan / Penugasan / Jabatan Terakhir</b></label>
                                    @if (isset($data) && strlen($data->file_sttpl) > 0)
                                        <br/>
                                        <a href="{{ asset('uploads/register/'.$data->file_sttpl) }}" target="_blank">Download</a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-footer">

                    <a href="<?php echo route('admin.getPassword') ?>"
                       class="mb-2 mr-2 btn btn-primary" title="{{ __('general.password') }}">
                        <i class="fa fa-lock"></i><span class=""> {{ __('general.password') }}</span>
                    </a>
                    {{--                    <a href="<?php echo route('admin.profile') ?>" class="mb-2 mr-2 btn btn-warning"--}}
                    {{--                       title="{{ __('general.back') }}">--}}
                    {{--                        <i class="fa fa-arrow-circle-o-left"></i><span class=""> {{ __('general.back') }}</span>--}}
                    {{--                    </a>--}}

                </div>

                <div class="card-body">
                    <!-- /.card-header -->
    @if(session()->get(env('APP_NAME').'admin_atasan'))
   <div class="row">
       <div class="col-md-12">
           <div class="mb-3 card">
               <div class="card-header-tab card-header-tab-animation card-header">
                   <div class="card-header-title">
                       <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"> </i>
                       Staff
                   </div>
               </div>
               <div class="card-body">
                   <table class="mb-0 table" id="data1">
                       <thead>
                       <tr>
                           <th>ID</th>
                           <th>Nama</th>
                           <th>Address</th>
                           <th>Kartu Pegawai</th>
                           <th>POB</th>
                           <th>Nomer PAK</th>
                           <th>Tanggal</th>
                           <th>Tahun Diangkat</th>
                           <th>Aksi</th>
                       </tr>
                       </thead>
                       <tbody>
                       @foreach($getBawahan as $list)
                           <tr>
                               <td>{{ $list->id}}</td>
                               <td>{{ $list->name }}</td>
                               <td>{{ $list->address }}</td>
                               <td>{{ $list->kartu_pegawai }}</td>
                               <td>{{ $list->pob }}</td>
                               <td>{{ $list->nomor_pak }}</td>
                               <td>{{ $list->tanggal }}</td>
                               <td>{{ $list->tahun_diangkat }}</td>
                               <td>
                                   <a href="{{route('admin.staff.show', $list->id)}}"
                                          class="mb-1 btn btn-info btn-xs" title="@lang('general.show')">
                                           <i class="fa fa-eye"></i>
                                           <span class="d-none d-md-inline"> @lang('general.show')</span>
                                   </a>
                               </td>

                           </tr>
                       @endforeach
                       </tbody>
                   </table>
               </div>
           </div>
       </div>
   </div>@endif
                    @if(session()->get(env('APP_NAME').'admin_sekretariat') || session()->get(env('APP_NAME').'admin_tim_penilai'))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3 card">
                                    <div class="card-header-tab card-header-tab-animation card-header">
                                        <div class="card-header-title">
                                            <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"> </i>
                                            Perancang
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table class="mb-0 table" id="data2">
                                            <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nama</th>
                                                <th>Address</th>
                                                <th>Kartu Pegawai</th>
                                                <th>POB</th>
                                                <th>Nomer PAK</th>
                                                <th>Tanggal</th>
                                                <th>Tahun Diangkat</th>
                                                <th>Aksi</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($getPerancang as $list)
                                                <tr>
                                                    <td>{{ $list->id}}</td>
                                                    <td>{{ $list->name }}</td>
                                                    <td>{{ $list->address }}</td>
                                                    <td>{{ $list->kartu_pegawai }}</td>
                                                    <td>{{ $list->pob }}</td>
                                                    <td>{{ $list->nomor_pak }}</td>
                                                    <td>{{ $list->tanggal }}</td>
                                                    <td>{{ $list->tahun_diangkat }}</td>
                                                    <td>
                                                        <a href="{{route('admin.staff.show', $list->id)}}"
                                                           class="mb-1 btn btn-info btn-xs" title="@lang('general.show')">
                                                            <i class="fa fa-eye"></i>
                                                            <span class="d-none d-md-inline"> @lang('general.show')</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

</div>
<!-- /.card-body -->

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

    let table;
    let table1;
    table = jQuery('#data1').DataTable({
        serverSide: false,
        processing: true,
        autoWidth: false,
        scrollX: true,
    });
    table1 = jQuery('#data2').DataTable({
        serverSide: false,
        processing: true,
        autoWidth: false,
        scrollX: true,
    });

    </script>
    @stop
