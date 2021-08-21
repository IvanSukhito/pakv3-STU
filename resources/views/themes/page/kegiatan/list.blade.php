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
                    <div class="form-group">
                        <label for="permen">{{ __('general.permen') }} *</label>
                        {{ Form::select('permen', $dataPermen, old('permen'), ['id' => 'permen', 'class' => 'form-control', 'onchange' => 'changePermen()']) }}
                    </div>
                </div>
                <div class="card-body">
                    <h3 class="form-section first-form">Kegiatan</h3>
                    <p>Perancang mengajukan:</p>
                    <ul>

                        <li>Surat Pernyataan:
                        @foreach($topId as $top)


                        <?php $data = isset($dataTopKegiatan[$top]) ? $dataTopKegiatan[$top] : false;?>
                        <?php $dataAk = isset($kredit[$top]) ? $kredit[$top] : false;?>

                           <ul>
                               <?php $sumAk = 0;?>
                               @foreach($dataAk as $dataAk)
                               <?php $sumAk += $dataAk?>
                               @endforeach

                              <li>{!! $data ? $data['name'] : '' !!} - {!!number_format($sumAk,3)!!}</li>

                           </ul>
                           @endforeach
                        </li>

                        <li>Total AK yang di ajukan: {!! number_format($totalAk, 3) !!}</li>
                    </ul>
                </div>
                <div class="card-body">
                    @if(isset($dataKegiatan))
                        @foreach($dataKegiatan as $getPermen => $listTopKegiatan)
                            @foreach($listTopKegiatan as $getTop => $listJudul)
                            <?php

                                $getTopKegiatan = isset($dataTopKegiatan[$getTop]) ? $dataTopKegiatan[$getTop] : false;
                                ?>
                                <div class="card card-permen card-permen-<?php echo $getPermen ?>">

                                    <div class="card-header"><h3>{!! $getTopKegiatan ? $getTopKegiatan['name'] : '' !!}</h3></div>

                                    @foreach($listJudul as $getJudul => $listKegiatan)

                                        <div class="card-header"><h4>{!! $getJudul !!}</h4></div>

                                        @if(isset($listKegiatan[0]['child']))
                                        <div class="card-body overflow">
                                            {!! view_kegiatan_v3($listKegiatan[0]['child'], $dataJenjangPerancang, $dataUser->jenjang_perancang_id) !!}
                                        </div>
                                        @endif

                                    @endforeach

                                </div>

                            @endforeach
                        @endforeach
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

    <!--Modal-->
    <div id="kegiatanModal" class="modal" role="dialog" data-backdrop="static" data-width="100%" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" id="close" data-dismiss="modal">×</button>
                </div>
                <form method="post" action="#" id="#" >
                    @csrf
                    <div class="modal-body" id="modal-body">
                    <input type="hidden" id="id" name="id">
                        <div class="form-group">

                            <table >
                                <thead>
                                    <tr>
                                    <th>Tanggal</th>
                                    <th>Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    <td width="80%"><div id="tanggal"></div></td>
                                    <td width="20%"><div id="button-edit"></div></td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                    </div>


                </form>
            </div>

        </div>
    </div>
@stop

@section('script-bottom')
    @parent
    <script type="text/javascript">

        let dataFilter = {!! json_encode($dataFilterKegiatan) !!};

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

        $('.click-kegiatan').click(function (e) {
            e.preventDefault();

            let myId = $(this).data('id');
            $('#kegiatanModal').modal('show');

           //var a =  document.getElementById("kegiatan_hidden_"+myId);
            let a = $('#kegiatan_hidden_'+myId).attr('value');
            console.log(a);
            let id = []
            let tanggal = []
            try {
                //data = JSON.parse(a).reduce((acc, val)=>[...acc, val.id, val.tanggal], [])
              id= JSON.parse(a).reduce((acc, val)=>[...acc, val.id], [])
              tanggal= JSON.parse(a).reduce((acc, val)=>[...acc, val.tanggal], [])
            } catch (e){
              console.log("Invalid json")
            }

            let inputTanggal = "";
            for (let i=0; i < tanggal.length; i++){
                inputTanggal +=
                '<input type="text" name="tanggal" value="'+tanggal[i]+'"  class="form-control" autocomplete="off" readonly>';
            }
            $("#tanggal").html(inputTanggal);
            let inputID = "";
            for (let i=0; i < id.length; i++){

            inputID +=
            '<a href="kegiatan/'+id[i]+'/edit" class="form-control" title="@lang('general.edit')">'+
                            '<i class="fa fa-pencil"></i>'+
                            '<span class="d-none d-md-inline"> @lang('general.edit')</span> </a>';
            }
            $("#button-edit").html(inputID);

            return false;

        });

    </script>
@stop
