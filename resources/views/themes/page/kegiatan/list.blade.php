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
                @if ($permission['create'])
                    <div class="card-header">
                        <a href="<?php echo route('admin.' . $thisRoute . '.create') ?>" class="mb-2 mr-2 btn btn-success"
                           title="@lang('general.create')">
                            <i class="fa fa-plus-square"></i> @lang('general.create')
                        </a>
                        <a href="#" onclick="showPerDate()" class="mb-2 mr-2 btn btn-primary"
                           title="@lang('general.create')">
                            <i class="fa fa-send"></i> @lang('general.submit_kegiatan')
                        </a>
                    </div>
                @endif

            <!-- /.card-header -->
                <div class="card-body">
                    @if(isset($data))
                        @foreach($data as $list)
                            <div class="card">
                                <div class="card-header"><h4>{!! $list->judul !!}</h4></div>
                            </div>
                        @endforeach
                    @else
                        <h3>@lang('Tidak ada Butir Kegiatan')</h3>
                    @endif
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>

    <div id="perDateModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <form method="get" action="{{ route('admin.kegiatan.submitKegiatan') }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="daterange1">Estimate Date</label>

                            <div class="input-group">
                                <div class="input-group-prepend datepicker-trigger">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                                <input type="text" name="daterange1" value="" id="daterange1" class="form-control daterange" autocomplete="off" required="1">
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
    <script type="text/javascript">

        function showPerDate() {
            $("#perDateModal").modal();
        }

        $('#daterange1').daterangepicker({
            // timePicker: true,
            // timePicker24Hour: true,
            // timePickerIncrement: 15,
            locale: {
                "format": "YYYY-MM-DD",
                "separator": " | "
            }
        });

    </script>
@stop
