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

$anggota = isset($anggota) ? $anggota : old('anggota');

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

                @if(in_array($viewType, ['create']))
                    {{ Form::open(['route' => ['admin.' . $thisRoute . '.store'], 'files' => true, 'id'=>'form', 'role' => 'form'])  }}
                @elseif(in_array($viewType, ['edit']))
                    {{ Form::open(['route' => ['admin.' . $thisRoute . '.update', $data->{$masterId}], 'method' => 'PUT', 'files' => true, 'id'=>'form', 'role' => 'form'])  }}
                @else
                    {{ Form::open(['id'=>'form', 'role' => 'form'])  }}
                @endif

                <div class="card-body">
                    @foreach($passing as $fieldName => $fieldData)
                        @if ($fieldName == 'anggota.*')

                        @elseif ($fieldName == 'anggota')
                            <div class="form-group">
                                <label>Anggota</label>
                                <div id="add_anggota">
                                    @if ($anggota == null)
                                        <div class="anggota row">
                                            <div class="col-sm-10 col-md-11">
                                                {{ Form::select('anggota[]', $listSet['anggota'], null, array_merge(['required' => 'required', 'class' => 'form-control select2'], $addAttribute)) }}
                                            </div>
                                            @if (!in_array($viewType, ['show']))
                                                <div class="col-sm-2 col-md-1">
                                                    <a href="#" class="pull-left" onclick="return remove_anggota(this)">
                                                        <i class="icon-minus"></i> Hapus
                                                    </a>
                                                </div>
                                            @endif
                                            <div class="col-12">&nbsp;</div>
                                        </div>
                                    @else
                                        @foreach($anggota as $list)
                                            <div class="anggota row">
                                                <div class="col-sm-10 col-md-11">
                                                    {{ Form::select('anggota[]', $listSet['anggota'], $list, array_merge(['required' => 'required', 'class' => 'form-control select2'], $addAttribute)) }}
                                                </div>
                                                @if (!in_array($viewType, ['show']))
                                                    <div class="col-sm-2 col-md-1">
                                                        <a href="#" class="pull-left" onclick="return remove_anggota(this)">
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
                                    <a href="#" onclick="return tambah_anggota()"><i class="icon-plus"></i> Tambahkan Anggota</a>
                                @endif
                            </div>
                        @else
                            <?php
                            $fieldValue = isset($data->$fieldName) ? $data->$fieldName : null;
                            if ($fieldValue == null) {
                                $fieldValue = isset($fieldData['value']) ? $fieldData['value'] : null;
                            }
                            $listPassing = [
                                'fieldName' => $fieldName,
                                'fieldLang' => __($fieldData['lang']),
                                'fieldRequired' => isset($fieldData['validation'][$viewType]) && in_array('required', explode('|', $fieldData['validation'][$viewType])) ? 1 : 0,
                                'fieldValue' => $fieldValue,
                                'fieldMessage' => $fieldData['message'],
                                'fieldClass' => $fieldData['class'],
                                'fieldClassParent' => $fieldData['classParent'],
                                'path' => $fieldData['path'],
                                'addAttribute' => $addAttribute,
                                'fieldExtra' => isset($fieldData['extra'][$viewType]) ? $fieldData['extra'][$viewType] : [],
                                'viewType' => $viewType
                            ];

                            $arrayPassing = [];
                            if (in_array($fieldData['type'], ['select', 'select2', 'tagging'])) {
                                $arrayPassing = isset($listSet[$fieldName]) ? $listSet[$fieldName] : [];
                            }
                            $listPassing['listFieldName'] = $arrayPassing;
                            ?>
                            @component(env('ADMIN_TEMPLATE').'._component.form.'.$fieldData['type'], $listPassing)
                            @endcomponent
                        @endif
                    @endforeach
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
        let index;
        function tambah_anggota() {
            var new_html = '<div class="anggota row"><div class="col-sm-10 col-md-11">{{ Form::select('anggota[]', $listSet['anggota'], null, array_merge(['required' => 'required', 'class' => 'form-control select2'], $addAttribute)) }}</div><div class="col-sm-2 col-md-1"><a href="#" onclick="return remove_anggota(this)"><i class="icon-minus"></i> Hapus</a></div><div class="col-12">&nbsp;</div></div>';
            jQuery('#add_anggota').append(new_html);
            setTimeout(function() {
                jQuery('#add_anggota').find('.anggota.row:last .select2').select2();
            }, 500);
            return false;
        }

        function remove_anggota(curr) {
            if (jQuery('.anggota').length > 1) {
                jQuery(curr).parent().parent().remove();
            }
            else {
                alert("Tidak bisa menghapus semua anggota");
            }
            return false;
        }

    </script>
@stop
