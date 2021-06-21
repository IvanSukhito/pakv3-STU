@extends(env('ADMIN_TEMPLATE').'._base.layout')

@section('title', __('general.title_home', ['field' => $thisLabel]))

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
                    <h1>{{ __('general.title_home', ['field' => $thisLabel]) }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo route('admin.profile') ?>"><i class="fa fa-user"></i> {{ __('general.profile') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('general.title_home', ['field' => $thisLabel]) }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Laporan Rangkuman Pegawai</h3>
                </div>
                <!-- /.card-header -->
                <form method="get">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 col-md-3">
                                <label for="unit_kerja_id">Unit Kerja</label>
                                {{ Form::select('unit_kerja_id[]', $list_unit_kerja, null, ['id'=>'unit_kerja_id', 'class'=>'form-control', 'multiple'=>'multiple']) }}
                            </div>
                            <div class="col-md-12">
                                <br />
                                <button type="submit" class="mb-2 mr-2 btn btn-success" name="export" value="1" title="Export">
                                    <i class="fa fa-file-excel-o"></i><span class=""> Buat Laporan</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </form>
            </div>
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Laporan Lengkap Pegawai</h3>
                </div>
                <!-- /.card-header -->
                <form method="get">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 col-md-3">
                                <label for="unit_kerja_id">Unit Kerja</label>
                                {{ Form::select('unit_kerja_id[]', $list_unit_kerja, null, ['id'=>'unit_kerja_id', 'class'=>'form-control', 'multiple'=>'multiple']) }}
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="status">Status</label>
                                {{ Form::select('status[]', $list_status, null, ['id'=>'status', 'class'=>'form-control', 'multiple'=>'multiple']) }}
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="jenjang_perancang">Jenjang</label>
                                {{ Form::select('jenjang_perancang[]', $list_jenjang_perancang, null, ['id'=>'jenjang_perancang', 'class'=>'form-control', 'multiple'=>'multiple']) }}
                            </div>
                            <div class="col-md-12">
                                <br />
                                <button type="submit" class="mb-2 mr-2 btn btn-success" name="export" value="2" title="Export">
                                    <i class="fa fa-file-excel-o"></i><span class=""> Buat Laporan</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </form>
            </div>
        </div>
    </section>
@stop

