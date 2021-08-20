<?php
$id = isset($id) ? $id : null;
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
        CKEDITOR_BASEPATH = '/assets/cms/js/ckeditor/';
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
                        <li class="breadcrumb-item"><a href="<?php echo route('admin') ?>"><i class="fa fa-dashboard"></i> {{ __('general.home') }}</a></li>
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
                @elseif(in_array($viewType, ['uploadSP']))
                    {{ Form::open(['route' => ['admin.' . $thisRoute . '.storeSP', $data->{$masterId}], 'method' => 'POST', 'files' => true, 'id'=>'form', 'role' => 'form'])  }}    
                @else
                    {{ Form::open(['id'=>'form', 'role' => 'form'])  }}
                @endif

            
                <div class="card-body">
              
                    @csrf
                    <div class="modal-body">
                  
                        <div class="form-group">
                     
                            <label>File Surat Pernyataan</label>
                            <br/>
                            <div id="list_other2">
                                <div class="d-flex align-items-center">
                                    <div class="p-2">
                                        <input type="file" name="file_upload_surat_pernyataan[]" class="dropify" accept=".pdf"
                                               data-allowed-file-extensions="pdf" data-max-file-size="10M">
                                    </div>
                                </div>
                            </div>
                            <a href="#" onclick="return add_other2()" class="btn btn-warning">Tambah</a>
                        </div>
                    </div>

           
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
                    @elseif (in_array($viewType, ['uploadSP']))
                    <button type="submit" class="mb-2 mr-2 btn btn-primary" title="@lang('general.save')">
                        <i class="fa fa-save"></i><span class=""> @lang('general.save')</span>
                    </button>
                  
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
    <script type="text/javascript">
        'use strict';

      
        let setIndex1 = 1;
        let setIndex2 = 1;

        $(document).ready(function() {
            $('.all-row').hide();
         
            $('.dropify').dropify();
            $("#judul").select2({
                tags: true
            });           
             
        });


      

      
        function add_other2() {

            let html = '<div class="d-flex align-items-center">' +
                '<div class="p-2">' +
                '<input type="file" id="file_upload_surat_pernyataan_' + setIndex2 +'" name="file_upload_surat_pernyataan[]" class="dropify" accept=".pdf" ' +
                'data-allowed-file-extensions="pdf" data-max-file-size="10M">' +
                '</div>' +
                '<div class="p-2">' +
                '<a href="#" onclick="return remove_other(this)">{!! __('general.delete') !!}</a>' +
                '</div>' +
                '</div>';

            $('#list_other2').append(html);
            $('#file_upload_surat_pernyataan_' + setIndex2).dropify();

            setIndex2++;

            return false;

        }

        function remove_other(curr) {
            $(curr).parent().parent().remove();
            return false;
        }

        function clearAll() {
            $('#msKegiatanId').val('');
            $('#tanggal').val('');
            $('#judul').select2("val", " ");
            setIndex1 = 1;
            setIndex2 = 1;

          
            $('#list_other2').html('<div class="d-flex align-items-center">' +
                '<div class="p-2">' +
                '<input type="file" id="file_upload_surat_pernyataan_' + setIndex2 +'" name="file_upload_surat_pernyataan[]" class="dropify" accept=".pdf" ' +
                'data-allowed-file-extensions="pdf" data-max-file-size="10M">' +
                '</div>' +
                '</div>');

            $('#dokument_' + setIndex1).dropify();
            $('#file_upload_surat_pernyataan_' + setIndex2).dropify();

            setIndex1++;
            setIndex2++;

        }

      

    </script>
@stop
