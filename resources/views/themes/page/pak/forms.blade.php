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
    $flagReadonly = 1;
}
else {
    $addAttribute = [
    ];
    $flagReadonly = 0;
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

                <div class="card-footer">

                    @if(in_array($viewType, ['create']))
                        <button type="submit" class="mb-2 mr-2 btn btn-success" title="@lang('general.save')">
                            <i class="fa fa-save"></i><span class=""> @lang('general.save')</span>
                        </button>
                    @elseif (in_array($viewType, ['edit']))
                    <button type="submit" class="mb-2 mr-2 btn btn-success" title="@lang('general.save')">
                            <i class="fa fa-save"></i><span class=""> @lang('general.save')</span>
                        </button>
                  
                    @endif
                    <a href="<?php echo route('admin.' . $thisRoute . '.index') ?>" class="mb-2 mr-2 btn btn-warning"
                       title="{{ __('general.back') }}">
                        <i class="fa fa-arrow-circle-o-left"></i><span class=""> {{ __('general.back') }}</span>
                    </a>

                </div>

      
                @if(in_array($viewType, ['edit']))
                <div class="card-body">
              
              @csrf
              <div class="modal-body">
            
                  <div class="form-group">
               
                    
                      <div class="form-group">
                            <label for="judul">Unit Kerja</label>
                            <div class="input-group">
                                <select class="form-control" data-width="100%" name="unit_kerja_id" id="unit_kerja_id" required>
                                    <option value="0">kosong</option>
                                    @foreach($unitkerja as $list)
                                        <option value="{{$list->id}}">{{$list->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <label>File Dupak</label>
                      <br/>
                      <div class="form-group">
                          <div class="d-flex align-items-center">
                              <div class="p-2">
                                  <input type="file" data-width="100%" name="file_upload_dupak[]" class="dropify" accept=".pdf"
                                         data-allowed-file-extensions="pdf" data-max-file-size="10M" required>
                              </div>
                          </div>
                      </div>
                    
                  </div>
              </div>     
          </div>

                @endif
                <div class="card-body">
                    <h3 class="form-section first-form">Kegiatan</h3>
                    <p>Perancang mengajukan:</p>
                    <ul>

                        <li>Surat Pernyataan:
                        @foreach($topId as $top)


                        <?php $dataTop = isset($dataTopKegiatan[$top]) ? $dataTopKegiatan[$top] : false;?>
                        <?php $dataAk = isset($kredit[$top]) ? $kredit[$top] : false;?>

                           <ul>
                               <?php $sumAk = 0;?>
                               @foreach($dataAk as $dataAk)
                               <?php $sumAk += $dataAk?>
                               @endforeach

                              <li>{!! $dataTop ? $dataTop['name'] : '' !!} - {!!number_format($sumAk,3)!!}</li>

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
                                        <div class="card-body overflow">
                                            {!! persetujuan_pak_kegiatan_v3($listKegiatan[0]['child'], $dataJenjangPerancang, $dataUser->jenjang_perancang_id, $flagReadonly = 1) !!}
                                        </div>

                                    @endforeach

                                </div>

                            @endforeach
                        @endforeach
                    @endif
                </div>
                <!-- /.card-body -->

                <div class="card-footer">

                    @if(in_array($viewType, ['create']))
                        <button type="submit" class="mb-2 mr-2 btn btn-success" title="@lang('general.save')">
                            <i class="fa fa-save"></i><span class=""> @lang('general.save')</span>
                        </button>
                    @elseif (in_array($viewType, ['edit']))
                    <button type="submit" class="mb-2 mr-2 btn btn-success" title="@lang('general.save')">
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
        $(document).ready(function (e) {
            $('.dropify').dropify();
            $("#unit_kerja_id").select2({
                tags: true
            });

        });

        $('#all_ok_check').change(function() {
            if($(this).prop('checked') === true) {
                $('.radio_button_ok').prop('checked', true);
                $('.message_kegiatan').val('');
            }
        });

        $('#all_cancel_check').change(function() {
            if($(this).prop('checked') === true) {
                $('.radio_button_cancel').prop('checked', true);
                let getMessage = prompt('Alasan di tolak?');
                if (getMessage) {
                    $('.message_kegiatan').val(getMessage);
                }
            }
        });

        $('.radio_button_ok').click(function() {
            if($(this).prop('checked') === true) {
                let getId = $(this).data('id');
                $('#message_kegiatan_' + getId).val('');
            }
        });

        $('.radio_button_cancel').click(function() {
            if($(this).prop('checked') === true) {
                let getId = $(this).data('id');
                let getMessage = prompt('Alasan di tolak?');
                if (getMessage) {
                    $('#message_kegiatan_' + getId).val(getMessage);
                }
            }
        });

        function checkConfirm() {
            return confirm("Menyetujui Surat Pernyataan?");
        }

    </script>
@stop
