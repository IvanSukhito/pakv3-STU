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
                    <div class="form-group">
                        <label for="permen">{{ __('general.permen') }} *</label>
                        {{ Form::select('permen', $dataPermen, old('permen'), ['id' => 'permen', 'class' => 'form-control', 'onchange' => 'changePermen()']) }}
                    </div>
                    <div class="form-group">
                        <label for="filter">{{ __('general.filter') }} *</label>
                        {{ Form::select('filter', [], old('filter'), ['id' => 'filter', 'class' => 'form-control', 'onchange' => 'changeFilter()']) }}
                    </div>
                </div>
                <div class="card-body">
                    {!!  create_kegiatan_v3($dataKegiatan, $dataJenjangPerancang, $dataUser->jenjang_perancang_id) !!}
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

    <div id="kegiatanModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h1>Judul Butir Kegiatan</h1>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <form method="post" action="#">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="date">@lang('general.date')</label>
                            <div class="input-group">
                                <div class="input-group-prepend datepicker-trigger">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                                <input type="text" name="date" value="" id="date" class="form-control datepicker" autocomplete="off" required="1">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@stop

@section('script-bottom')
    @parent
    @include(env('ADMIN_TEMPLATE').'._component.generate_forms_script')
    <script type="text/javascript">
        'use strict';

        let dataFilter = {!! json_encode($dataFilterKegiatan) !!};

        $(document).ready(function() {
            $('.all-row').hide();
            changePermen();
        });

        $('.click-kegiatan').click(function () {
            $('#kegiatanModal').modal('show');
            return false;
        });

        $('#submitForm').click(function(e) {
            e.preventDefault();
        });

        function changePermen() {
            let getPermen = $('#permen').val();
            $('#filter').empty();
            $.each(dataFilter, function(index, item) {
                if (parseInt(index) === parseInt(getPermen)) {
                    $.each(item, function(index2, item2) {
                        $('#filter').append(new Option(item2, index2));
                    });
                    changeFilter();
                }
            });
        }

        function changeFilter() {
            let getKegiatanPoint = $('#filter').val();
            $('.all-row').hide();
            $('.kegiatan-' + getKegiatanPoint).show();
        }
    </script>
@stop
