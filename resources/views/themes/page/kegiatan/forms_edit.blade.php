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
                @else
                    {{ Form::open(['id'=>'form', 'role' => 'form'])  }}
                @endif

            
                <div class="card-body">
              
                    @csrf
                    <div class="modal-body">
                    <input type="hidden" name="ms_kegiatan_id" id="msKegiatanId" value=""/>
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <div class="input-group">
                                <input type="date" name="tanggal" value="{{$data->tanggal}}" id="tanggal" class="form-control" autocomplete="off" required="1">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="judul">Judul</label>
                            <div class="input-group">
                                <select class="form-control" data-width="100%" name="judul" readonly>
                                    <option selected="selected" value="{{$data->judul}}" readonly>{{$data->judul}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                        <label>Dokumen Pendukung</label>
                            <br/>
                            <div id="list_other1">
                                <div class="d-flex align-items-center">
                                    <div class="p-2">
                                        <input type="file" name="dokument[]" class="dropify" accept=".pdf"
                                               data-allowed-file-extensions="pdf" data-max-file-size="10M">
                                    </div>
                                </div>
                            </div>
                            <a href="#" onclick="return add_other1()" class="btn btn-warning">Tambah</a>
                            <br>
                            <label>Dokumen Fisik</label>
                            <br/>
                            <div id="list_other2">
                                <div class="d-flex align-items-center">
                                    <div class="p-2">
                                        <input type="file" name="dokument_fisik[]" class="dropify" accept=".pdf"
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


      

        function add_other1() {

            let html = '<div class="d-flex align-items-center">' +
                '<div class="p-2">' +
                '<input type="file" id="dokument_' + setIndex1 +'" name="dokument[]" class="dropify" accept=".pdf"' +
                ' data-allowed-file-extensions="pdf" data-max-file-size="10M">' +
                '</div>' +
                '<div class="p-2">' +
                '<a href="#" onclick="return remove_other(this)">{!! __('general.delete') !!}</a>' +
                '</div>' +
                '</div>';

            $('#list_other1').append(html);
            $('#dokument_' + setIndex1).dropify();

            setIndex1++;

            return false;

        }
        function add_other2() {

            let html = '<div class="d-flex align-items-center">' +
                '<div class="p-2">' +
                '<input type="file" id="dokument_fisik_' + setIndex2 +'" name="dokument_fisik[]" class="dropify" accept=".pdf" ' +
                'data-allowed-file-extensions="pdf" data-max-file-size="10M">' +
                '</div>' +
                '<div class="p-2">' +
                '<a href="#" onclick="return remove_other(this)">{!! __('general.delete') !!}</a>' +
                '</div>' +
                '</div>';

            $('#list_other2').append(html);
            $('#dokument_fisik_' + setIndex2).dropify();

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

            $('#list_other1').html('<div class="d-flex align-items-center">' +
                '<div class="p-2">' +
                '<input type="file" id="dokument_' + setIndex1 +'" name="dokument[]" class="dropify" accept=".pdf"' +
                ' data-allowed-file-extensions="pdf" data-max-file-size="10M">' +
                '</div>' +
                '</div>');

            $('#list_other2').html('<div class="d-flex align-items-center">' +
                '<div class="p-2">' +
                '<input type="file" id="dokument_fisik_' + setIndex2 +'" name="dokument_fisik[]" class="dropify" accept=".pdf" ' +
                'data-allowed-file-extensions="pdf" data-max-file-size="10M">' +
                '</div>' +
                '</div>');

            $('#dokument_' + setIndex1).dropify();
            $('#dokument_fisik_' + setIndex2).dropify();

            setIndex1++;
            setIndex2++;

        }

        $('#storeModal').on('submit', function(e) {
            e.preventDefault();

            let tanggal = $('#tanggal').val();
            let judul = $('#judul').val();
            let validateOk = 1;

            if (validateOk === 1) {
                let formData = new FormData();
                formData.append('ms_kegiatan_id', $('#msKegiatanId').val());
                formData.append('tanggal', tanggal);
                formData.append('judul', judul);

                $.each($('input[name="dokument[]"]'), function(index, item) {
                    formData.append('dokument[]', $(item).prop('files')[0]);
                });
                $.each($('input[name="dokument_fisik[]"]'), function(index, item) {
                    formData.append('dokument_fisik[]', $(item).prop('files')[0]);
                });

                let link = '{{ route('admin.kegiatan.store') }}';
                let linkSplit = link.split('/');
                let url = '';
                for(let i=3; i<linkSplit.length; i++) {
                    url += '/'+linkSplit[i];
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'text',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        if(typeof result !== "object") {
                            result = JSON.parse(result);
                        }

                        if (parseInt(result.result) === 1) {

                            $('#kegiatanModal').modal('hide');

                            $.notify({
                                message: result.message
                            },{
                                type: 'success',
                                placement: {
                                    from: "bottom",
                                    align: "right"
                                },
                            });

                        }
                        else {
                            $.notify({
                                message: result.message
                            },{
                                type: 'danger',
                                placement: {
                                    from: "bottom",
                                    align: "right"
                                },
                            });
                        }

                    },
                    complete: function(){

                    }
                });

            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    </script>
@stop
