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
                    {{ Form::open(['route' => ['admin.' . $thisRoute . '.store'], 'files' => true, 'id'=>'form', 'role' => 'form', 'method' => 'post'])  }}
                @elseif(in_array($viewType, ['edit']))
                    {{ Form::open(['route' => ['admin.' . $thisRoute . '.update', $data->{$masterId}], 'method' => 'PUT', 'files' => true, 'id'=>'form', 'role' => 'form'])  }}
                @else
                    {{ Form::open(['id'=>'form', 'role' => 'form'])  }}
                @endif

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
                <div class="card-body">
                    <h3 class="form-section first-form">Kegiatan</h3>
                </div>
                <div class="card-body" style="overflow-x: auto">
                    {!!  render_kegiatan_v3($dataKegiatan, $dataUser ? $dataUser->jenjang_perancang_id : 0, $dataJenjangPerancang, $listKegiatan) !!}
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

        let filterKegiatan = JSON.parse('{!! json_encode($dataFilterKegiatan ?? '') !!}');

        jQuery('.show_checkbox_kegiatan').on('change', function(e) {
            var get_id = jQuery(this).data('id');

            if(jQuery(this).prop('checked') === true) {
                jQuery('#show_detail'+get_id).show();
            }
            else {
                jQuery('#show_detail'+get_id).hide();
            }
        });
        jQuery('.add_more').on('click', function(e) {
            e.preventDefault();
            var get_id = jQuery(this).data('id');
            var html = '<input name="dokument['+get_id+'][]" type="file">';
            jQuery('#dokument_pendukung'+get_id).append(html);
        });

        jQuery('.add_more_dokumen_fisik').on('click', function(e) {
            e.preventDefault();
            var get_id = jQuery(this).data('id');
            var dokumenFisik = '<input name="dokument_fisik['+get_id+'][]" type="file">';
            jQuery('#dokument_fisik'+get_id).append(dokumenFisik);
        });

        function show_filter_kegiatan() {
            let valuePermen = parseInt(jQuery('#filter_permen').val());
            let valueKegiatan = parseInt(jQuery('#filter_kegiatan').val());

            jQuery('.all-row').hide();
            jQuery('.permen-' + valuePermen + '.kegiatan-' + valueKegiatan).show();
        }

        function load_all() {
            jQuery('.show_checkbox_kegiatan').each(function(key, item) {
                var get_id = jQuery(this).data('id');
                if(jQuery(item).prop('checked') === true) {
                    jQuery('#show_detail'+get_id).show();
                }
            });
        }

        function removeFile(curr) {
            $(curr).parent().remove();
        }

        $(document).ready(function() {
            $('#filter_permen').on('change', function() {
                let permenID = parseInt($(this).val());
                $('#filter_kegiatan').empty();
                $.each(filterKegiatan, function(index, item) {
                    if(parseInt(item.id) === permenID) {
                        $.each(item.data, function(indexKegiatan, itemKegiatan) {
                            $('#filter_kegiatan').append('<option value="'+ itemKegiatan.id +'">'+ itemKegiatan.name +'</option>');
                        });
                    }
                });

                show_filter_kegiatan();
                load_all();

            });

            $('#filter_permen').change();

        });

    </script>
@stop
