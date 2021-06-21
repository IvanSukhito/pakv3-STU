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

            @if(in_array($viewType, ['create']))
                {{ Form::open(['route' => ['admin.' . $thisRoute . '.store'], 'files' => true, 'id'=>'form', 'role' => 'form'])  }}
            @elseif(in_array($viewType, ['edit']))
                {{ Form::open(['route' => ['admin.' . $thisRoute . '.storeDataStaff', $data->{$masterId}], 'method' => 'PUT', 'files' => true, 'id'=>'form', 'role' => 'form'])  }}
            @else
                {{ Form::open(['id'=>'form', 'role' => 'form'])  }}
            @endif

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
                        <h3 class="card-title">@lang('Akun')</h3>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">
                        @include(env('ADMIN_TEMPLATE').'._component.generate_forms')
                    </div>
                    <!-- /.card-body -->

                </div>

{{--                <div class="card {!! $printCard !!}">--}}
{{--                    <div class="card-header">--}}
{{--                        <h3 class="card-title">@lang('Akun')</h3>--}}
{{--                    </div>--}}
{{--                    <!-- /.card-header -->--}}

{{--                    <div class="card-body">--}}
{{--                        @include(env('ADMIN_TEMPLATE').'.page.staff.generate_forms4')--}}
{{--                    </div>--}}
{{--                    <!-- /.card-body -->--}}

{{--                </div>--}}




                <div class="card-footer">

                    @if(in_array($viewType, ['create']))
                        <button type="submit" class="mb-2 mr-2 btn btn-success" title="@lang('yayayya')">
                            <i class="fa fa-save"></i><span class=""> @lang('yayayya')</span>
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

            </div>

            {{ Form::close() }}

        </div>
    </section>

@stop

@section('script-bottom')
    @parent
    @include(env('ADMIN_TEMPLATE').'._component.generate_forms_script')
    <script type="text/javascript">
        'use strict';

        $(document).ready(function() {

            $('#angka_kredit').inputmask('numeric', {
                radixPoint: ".",
                groupSeparator: ",",
                digits: 3,
                autoGroup: true,
                prefix: '', //Space after $, this will not truncate the first character.
                rightAlign: true
            });

            startCheck();

        });

        function startCheck() {

            $('.setChecklistData').on('click', function() {
                $('.hideAll').hide();
                let hideAtasan = 0;
                let listData = [];
                $('.setChecklistData').each(function(index, item) {
                    if ($(item).prop('checked') === true) {
                        listData.push(item.id);
                    }
                    if ($(item).prop('checked') === true && item.id === 'top') {
                        hideAtasan = 1;
                    }
                });

                $(listData).each(function (index, item) {
                    $('.' + item).show();
                });
                if (hideAtasan === 1) {
                    $('#staff_id').parent().hide();
                }

            });

        }


    </script>
@stop
