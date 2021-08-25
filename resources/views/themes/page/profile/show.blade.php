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
                    @include(env('ADMIN_TEMPLATE').'._component.generate_forms')
                </div>
@if($getPerancangData)
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="data1">
                        <thead>
                        <tr>
                            <th>@lang('general.id')</th>
                            <th>@lang('NIP')</th>
                            <th>@lang('general.name')</th>
                            <th>@lang('general.email')</th>
                            <th>@lang('general.pangkat')</th>
                            <th>@lang('general.golongan')</th>
                            <th>@lang('general.tmt_pangkat_golongan')</th>
                            <th>@lang('general.jabatan')</th>
                            <th>@lang('general.tmt_jabatan')</th>
                            <th>@lang('general.unit_kerja')</th>
                            <th>@lang('general.jenis_kelamin')</th>
                            <th>@lang('general.status')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($getPerancangData as $list)
                            <tr>
                                <td>{{ $list->id }}</td>
                                <td>{{ $list->username }}</td>
                                <td>{{ $list->name }}</td>
                                <td>{{ $list->email }}</td>
                                <td>{{ $list->pangkat_id }}</td>
                                <td>{{ $list->golongan_id }}
                                <td>{{ $list->tmt_pangkat }}
                                <td>{{ $list->jabatan_perancang_id }}</td>
                                <td>{{ $list->tmt_jabatan }}
                                <td>{{ $list->unit_kerja_id }}</td>
                                <td>{{ get_list_gender2($list->gender) }}</td>
                                <td>{{ get_list_status2($list->status) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
@endif
                <!-- /.card-body -->

                <div class="card-footer">

                    <a href="<?php echo route('admin.get_profile') ?>"
                       class="mb-2 mr-2 btn btn-primary" title="{{ __('general.edit') }}">
                        <i class="fa fa-pencil"></i><span class=""> {{ __('general.edit') }}</span>
                    </a>
                    <a href="<?php echo route('admin.get_password') ?>"
                       class="mb-2 mr-2 btn btn-primary" title="{{ __('general.password') }}">
                        <i class="fa fa-lock"></i><span class=""> {{ __('general.password') }}</span>
                    </a>
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
    </script>
@stop
