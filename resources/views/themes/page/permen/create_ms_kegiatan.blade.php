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
                <div class="card-body">

                    <form method="POST" action="{{route('admin.permen.updateMsKegiatan', $permenID->id)}}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <select class="form-control" id="parent_id" name="parent_id" data-live-search="true" style="width:100%">
                            <option value=""> --Silahkan Pilih MSKegiatan-- </option>
                            @foreach($msKegiatan as $list)
                                <option value="{{$list->id}}"> {{$list->name}}</option>
                                @endforeach
                        </select>
                        <br>
                        <div class="form-group">
                            <label class="text"><b>Name</b></label>
                            <input type="text" name="name" class="form-control" placeholder="Name" value="{{old('name')}}">
                        </div>
                        <div class="form-group">
                            <label class="text"><b>AK</b></label>
                            <input type="number" name="ak" class="form-control" placeholder="AK">
                        </div>
                        <div class="form-group">
                            <label class="text"><b>Satuan</b></label>
                            <input type="text" name="satuan" class="form-control" placeholder="Satuan">
                        </div>
                        <select class="form-control" id="jenjang_perancang_id" name="jenjang_perancang_id" data-live-search="true" style="width:100%">
                            <option value=""> --Silahkan Pilih Jenjang Perancang-- </option>
                            @foreach($listJenjangPerancang as $list)
                                <option value="{{$list->id}}"> {{$list->name}}</option>
                            @endforeach
                        </select>
                        <br>
                        <br>
                        <select class="form-control" id="status" name="status" data-live-search="true" style="width:100%">
                            <option value=""> --Silahkan Pilih Jenjang Status-- </option>

                                <option value="0"> Inactive</option>
                                <option value="1"> Active</option>
                                <option value="2"> Diberhentikan Sementara</option>
                                <option value="3"> Diberhentikan</option>
                                <option value="4"> Pensiun</option>
                                <option value="5"> Meninggal Dunia</option>

                        </select>
                        @if($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="form-group">
                                    <p><code>{{ $error }}</code></p>
                                </div>
                    @endforeach
                    @endif
                </div>
                <!-- /.card-body -->
                <div class="card-footer">

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                    </form>
            </div>
        </div>
    </section>

@stop

@section('script-bottom')
    @parent
{{--    @include(env('ADMIN_TEMPLATE').'._component.generate_forms_script')--}}

    <script>
        $("#parent_id").select2({
            allowClear:true,
            placeholder: 'Parent'
        });
        $("#jenjang_perancang_id").select2({
            allowClear:true,
            placeholder: ' Jenjang perancang'
        });
        $("#status").select2({
            allowClear:true,
            placeholder: 'Status'
        });
    </script>
@stop
