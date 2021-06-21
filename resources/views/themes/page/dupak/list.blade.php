<?php
    $setFlag = isset($setFlag) ? intval($setFlag) : 0;
?>
@extends(env('ADMIN_TEMPLATE').'._base.layout')

@section('title', __('general.title_home', ['field' => $thisLabel]))

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
                    <h1>{{ __('general.title_home', ['field' => $thisLabel]) }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo route('admin.profile') ?>"><i class="fa fa-user"></i> {{ __('general.profile') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('general.title_home', ['field' => $thisLabel]) }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                @if ($permission['create'] && $setFlag == 0)
                <div class="card-header">
                    <a href="<?php echo route('admin.' . $thisRoute . '.create') ?>" class="mb-2 mr-2 btn btn-success"
                       title="@lang('general.create')">
                        <i class="fa fa-plus-square"></i> @lang('general.create')
                    </a>
                </div>
                @endif
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="data1">
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="unggahFile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Unggah File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="form_upload" action="" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="file"><b>File Surat Permohonan</b></label>
                            <input type="file" name="fileSp" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="file"><b>File Dupak</b></label>
                            <input type="file" name="fileDupak" class="form-control">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <input type="submit" value="Unggah" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

@stop

@section('script-bottom')
    @parent
    <script type="text/javascript">
        'use strict';
        let table;
        table = jQuery('#data1').DataTable({
            serverSide: true,
            processing: true,
            autoWidth: false,
            scrollX: true,
            // pageLength: 25,
            // lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            ajax: '{{ route('admin.' . $thisRoute . '.dataTable') }}?flag={!! $setFlag !!}',
            aaSorting: [ {!! isset($listAttribute['aaSorting']) ? $listAttribute['aaSorting'] : "[0,'desc']" !!}],
            columns: [
                    @foreach($passing as $fieldName => $fieldData)
                {data: '{{ $fieldName }}', title: "{{ __($fieldData['lang']) }}" <?php echo strlen($fieldData['custom']) > 0 ? $fieldData['custom'] : ''; ?> },
                @endforeach
            ],
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
                        table.ajax.reload();
                    }
                });
            }
        }

        function actionData2(link, method, flag) {

            let message = 'Info';
            if (flag === 1) {
                message = 'Apa kamu yakin akan melakukan persetujuan?';
            }
            else if (flag === 2) {
                message = 'Apa kamu yakin akan melakukan penolakan?';
            }

            if(confirm(message)) {
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
                        table.ajax.reload();
                    }
                });
            }
        }


        function sendToSekretariat(link, method) {

            if(confirm('{{ __('Data akan dikirim ke Sekretariat ?') }}')) {
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
                    complete: function(){
                        table.ajax.reload();
                    }
                });
            }
        }

        function showUploadFile(url) {
            $('#form_upload').attr('action', url);
            $('#unggahFile').modal('show');
        }

    </script>
@stop
