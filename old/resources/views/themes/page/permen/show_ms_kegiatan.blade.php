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
                    <h1>{{ $formsTitle }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo route('admin.profile') ?>"><i class="fa fa-user"></i> {{ __('general.profile') }}</a></li>
                        {{--                        <li class="breadcrumb-item"><a href="<?php echo route('admin.' . $thisRoute . '.index') ?>"> {{ __('general.title_home', ['field' => $thisLabel]) }}</a></li>--}}
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
                    {{ Form::open(['route' => ['admin.' . $thisRoute . '.update', $permenID->{$masterId}], 'method' => 'PUT', 'files' => true, 'id'=>'form', 'role' => 'form'])  }}
                @else
                    {{ Form::open(['id'=>'form', 'role' => 'form'])  }}
                @endif

                <div class="card-body">
                    <div class="form-group">
                        <label class="text"><b>Permen</b></label>
                        <input type="text" name="permen" class="form-control" value="{{$permenID->name}}" readOnly>
                    </div>
                    <div class="form-group">
                        <label class="text"><b>Parent</b></label>
                        <input type="text" name="name" class="form-control" value="{{$msKegiatan->getParent->name}}" readOnly>
                    </div>
{{--                    <div class="form-group">--}}
{{--                        <label class="text"><b>Nama</b></label>--}}
{{--                        <input type="textArea" name="name" class="form-control" value="{{$msKegiatan->name}}" readOnly>--}}
{{--                    </div>--}}
                    <div class="form-group">
                        <label for="comment">Nama</label>
                        <textarea class="form-control" rows="5" id="name" readOnly>{{$msKegiatan->name}}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="text"><b>AK</b></label>
                        <input type="text" name="ak" class="form-control" value="{{$msKegiatan->ak}}" readOnly>
                    </div>
                    <div class="form-group">
                        <label class="text"><b>Jenjang Perancang</b></label>
                        <input type="text" name="jenjang_perancang_id" class="form-control" value="{{$msKegiatan->getJenjangPerancang->name}}" readOnly>
                    </div>
                    <div class="form-group">
                        <label class="text"><b>Satuan</b></label>
                        <input type="text" name="satuan" class="form-control" value="{{$msKegiatan->satuan}}" readOnly>
                    </div>
{{--                    <div class="form-group">--}}
{{--                        <label class="text"><b>Status</b></label>--}}
{{--                        <input type="text" name="status" class="form-control" value="{{$msKegiatan->status}}" readOnly>--}}
{{--                    </div>--}}
                </div>
                @if($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="form-group">
                            <p><code>{{ $error }}</code></p>
                        </div>
                    @endforeach
                @endif
            <!-- /.card-body -->

                <div class="card-footer">
                    <a href="<?php echo route('admin.' . $thisRoute . '.show', $permenID->id) ?>" class="mb-2 mr-2 btn btn-warning"
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
        $('#tanggal_start').datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $('#tanggal_end').datetimepicker({
            format: 'YYYY-MM-DD'
        });

    </script>

@stop
