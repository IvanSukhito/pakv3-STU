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
                        <label class="text"><b>Nama</b></label>
                        <input type="text" name="name" class="form-control" value="{{$permenID->name}}" readOnly>
                    </div>
                </div>
{{--                <div class="row">--}}
{{--                    <div class="col-md-6">--}}
{{--                        <button type="submit" class="mb-2 mr-2 btn btn-success"  style="margin-left: 20px;" title="@lang('Tambah MsKegiatan')">--}}
{{--                            <i class="fa fa-save"></i><span class=""> @lang('Tambah MsKegiatan')</span>--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3 card">
                            <div class="card-header-tab card-header-tab-animation card-header">
                                <div class="card-header-title">
                                    <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"> </i>
                               MS Kegiatan
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="mb-0 table" id="data1">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>AK</th>
                                        <th>Satuan</th>
                                        <th>Aksi</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($msKegiatan as $list)
                                        <tr>
                                            <td>{{ $list->id}}</td>
                                            <td>{{ $list->name }}</td>
                                            <td>{{ $list->ak }}</td>
                                            <td>{{ $list->satuan }}</td>
                                            <td>
                                            <a href="{{ route('admin.' . $thisRoute . '.showDetailPermen', [$permenID->id , $list->{$masterId}]) }}"
                                               class="mb-1 btn btn-info btn-xs" title="@lang('general.show')">
                                                <i class="fa fa-eye"></i>
                                                <span class="d-none d-md-inline"> @lang('general.show')</span>
                                            </a>
                                                <a href="{{ route('admin.' . $thisRoute . '.editDetailPermen', [$permenID->id , $list->{$masterId}]) }}"
                                                   class="mb-1 btn btn-info btn-xs" title="@lang('general.edit')">
                                                    <i class="fa fa-eye"></i>
                                                    <span class="d-none d-md-inline"> @lang('general.edit')</span>
                                                </a>
                                                <a href="#" class="mb-1 btn btn-danger btn-sm" title="@lang('general.delete')"
                                                   onclick="return actionData('{{ route('admin.' .$thisRoute . '.destroyMsKegiatan', $list->{$masterId}) }}', 'delete')">
                                                    <i class="fa fa-trash"></i>
                                                    <span class="d-none d-md-inline"> @lang('general.delete')</span>
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

                @if($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="form-group">
                            <p><code>{{ $error }}</code></p>
                        </div>
                    @endforeach
                @endif
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
{{--                        <a href="<?php echo route('admin.' . $thisRoute . '.edit', $data->{$masterId}) ?>"--}}
{{--                           class="mb-2 mr-2 btn btn-primary" title="{{ __('general.edit') }}">--}}
{{--                            <i class="fa fa-pencil"></i><span class=""> {{ __('general.edit') }}</span>--}}
{{--                        </a>--}}
                    @endif
                    <a href="<?php echo route('admin.' . $thisRoute . '.index') ?>" class="mb-2 mr-2 btn btn-warning"
                       title="{{ __('general.back') }}">
                        <i class="fa fa-arrow-circle-o-left"></i><span class=""> {{ __('general.back') }}</span>
                    </a>
                        <a href="{{route('admin.permen.createMsKegiatan', $permenID->id)}}" class="mb-2 mr-2 btn btn-success"
                           title="{{ __('Tambah MS Kegiatan') }}">
                            <i class="fa fa-plus-circle"></i><span class=""> {{ __('Tambah MS Kegiatan') }}</span>
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

        let $button = $(this);
        let table;
        table = jQuery('#data1').DataTable({
            serverSide: false,
            processing: false,
            autoWidth: true,
            scrollX: false,

            fnDrawCallback: function( oSettings ) {
                // $('a[data-rel^=lightcase]').lightcase();
            }
        });

        function actionData(link, method) {
            if(confirm('{{ __('general.ask_delete') }}')) {
                let test_split = link.split('/');
                let url = '';
                for(let i=3; i<test_split.length; i++) {
                    url += '/'+test_split[i];
                }
                jQuery.ajax({
                    url: url,
                    type: method,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {

                    },
                    complete: function(){
                        window.location.reload();
                    }
                });
            }
        }

    </script>

@stop
