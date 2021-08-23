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
                    <h1>{{ $formsTitle }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo route('admin') ?>"><i class="fa fa-dashboard"></i> {{ __('general.home') }}</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo route('admin.' . $thisRoute.'.index') ?>"> {{ __('general.title_home', ['field' => $thisLabel]) }}</a></li>
                        <li class="breadcrumb-item active">{{ $formsTitle }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

                <!-- /.card-header -->
                @if(in_array($viewType, ['create']))
                {{ Form::open(['route' => ['admin.' . $thisRoute . '.store'], 'method' => 'POST', 'files' => true, 'id'=>'form', 'role' => 'form'])  }}
                @else
                    {{ Form::open(['id'=>'form', 'role' => 'form'])  }}
                @endif

                <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ $formsTitle }}</h3>
                </div>
                <div class="card-body">
                    @include(env('ADMIN_TEMPLATE').'._component.generate_forms')
                </div>
                </div>

                @if(in_array($viewType, ['create']) )
                <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">@lang('general.upload_berkas_pemuktahiran')</h3>
                </div>
                <div class="card-body">
                    <label>Upload File</label>
                      <br/>
                      <div id="list_other1">
                                <div class="d-flex align-items-center">
                                    <div class="p-2">
                                        <input type="file" name="upload_file_pemuktahiran[]" class="dropify" accept=".pdf"
                                               data-allowed-file-extensions="pdf" data-max-file-size="10M">
                                    </div>
                                </div>
                            </div>
                            <a href="#" onclick="return add_other1()" class="btn btn-warning">Tambah</a>
                            <br>

                </div>
                </div>
                @else
                <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">@lang('general.upload_berkas_pemuktahiran')</h3>
                </div>
                <div class="card-body">
                <?php if($file): ?>
                   <?php $no = 0;?>
                   @foreach($file as $data)
                   <?php $no++;?>
                                <div class="form-group">
                                    <p><i class="fa fa-cloud-download" aria-hidden="true"> <a href="{{asset($data['path'])}}" target="_blank">@lang('general.view_berkas_pemuktahiran') - {!! $no !!}</a></i></p>
                                </div>
                    @endforeach
                            <?php else: ?>
                        <h3>No File</h3>
                 <?php endif ?>
                </div>
                </div>



                @endif
                <!-- /.card-body -->

                <div class="card-footer">

                @if(in_array($viewType, ['create']))
                    <button type="submit" class="mb-2 mr-2 btn btn-primary" title="@lang('general.save')">
                        <i class="fa fa-save"></i><span class=""> @lang('general.save')</span>
                    </button>
                    <a href="<?php echo route('admin.' . $thisRoute.'.index') ?>" class="mb-2 mr-2 btn btn-warning"
                       title="{{ __('general.back') }}">
                        <i class="fa fa-arrow-circle-o-left"></i><span class=""> {{ __('general.back') }}</span>
                    </a>
                @else
                    <a href="<?php echo route('admin.' . $thisRoute.'.index') ?>" class="mb-2 mr-2 btn btn-warning"
                       title="{{ __('general.back') }}">
                        <i class="fa fa-arrow-circle-o-left"></i><span class=""> {{ __('general.back') }}</span>
                    </a>
                @endif
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
        let setIndex1 = 1;
        $(document).ready(function() {
            $('.dropify').dropify();
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

        function add_other1() {
        let html = '<div class="d-flex align-items-center">' +
        '<div class="p-2">' +
        '<input type="file" id="upload_file_pemuktahiran_' + setIndex1 +'" name="upload_file_pemuktahiran[]" class="dropify" accept=".pdf"' +
        ' data-allowed-file-extensions="pdf" data-max-file-size="10M">' +
        '</div>' +
        '<div class="p-2">' +
        '<a href="#" onclick="return remove_other(this)">{!! __('general.delete') !!}</a>' +
        '</div>' +
        '</div>';

            $('#list_other1').append(html);
            $('#upload_file_pemuktahiran_' + setIndex1).dropify();

            setIndex1++;

            return false;

            }
    function remove_other(curr) {
            $(curr).parent().parent().remove();
            return false;
        }


        </script>
@stop
