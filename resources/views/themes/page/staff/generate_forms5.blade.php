<?php
$getTotal = ceil((count($passing5) + 1) / 2);
$countStart = 0;
$first1 = true;
$first2 = true;
$first3 = true;
$first4 = true;

?>
<div class="row">
    <div class="col-md-6">
        @foreach($passing5 as $fieldName => $fieldData)
            <?php
            $fieldValue = isset($data->$fieldName) ? $data->$fieldName : null;
            $listPassing = [
                'fieldName' => $fieldName,
                'fieldLang' => __($fieldData['lang']),
                'fieldRequired' => isset($fieldData['validation'][$viewType]) && in_array('required', explode('|', $fieldData['validation'][$viewType])) ? 1 : 0,
                'fieldValue' => $fieldValue,
                'fieldMessage' => $fieldData['message'],
                'fieldClass' => $fieldData['class'],
                'fieldClassParent' => $fieldData['classParent'],
                'path' => $fieldData['path'],
                'addAttribute' => $addAttribute,
                'fieldExtra' => isset($fieldData['extra'][$viewType]) ? $fieldData['extra'][$viewType] : [],
                'viewType' => $viewType
            ];

            $arrayPassing = [];
            if (in_array($fieldData['type'], ['select', 'select2', 'tagging'])) {
                $arrayPassing = isset($listSet[$fieldName]) ? $listSet[$fieldName] : [];
            }
            $listPassing['listFieldName'] = $arrayPassing;
            ?>
            @if ($countStart++ == $getTotal)
    </div>
    <div class="col-md-6">
        @endif

        @if (in_array($fieldName, ['masa_penilaian_terkahir_start', 'masa_penilaian_terkahir_end']))
            <?php
            $fieldLang = $listPassing['fieldLang'];
            $fieldRequired = $listPassing['fieldRequired'];
            $fieldValue = $listPassing['fieldValue'];
            $fieldMessage = $listPassing['fieldMessage'];
            $path = $listPassing['path'];
            $addAttribute = $listPassing['addAttribute'];
            $fieldExtra = $listPassing['fieldExtra'];
            $viewType = $listPassing['viewType'];

            $attribute = $addAttribute;
            foreach ($fieldExtra as $extraKey => $extraVal) {
                $attribute[$extraKey] = $extraVal;
            }
            $attribute['id'] = $fieldName;
            $attribute['class'] = 'form-control pull-right datepicker';
            if ($errors->has($fieldName)) {
                $attribute['class'] .= ' is-invalid';
            }
            $attribute['autocomplete'] = 'off';
            if ($fieldRequired == 1) {
                $attribute['required'] = 'true';
            }
            ?>
            @if ($first1)
                <div class="form-group hideAll perancang">
                    <label>Masa Penilaian Terakhir</label>
                    <div class="form-row">
                        @endif
                        <div class="col-6">
                            <div class="input-group {{ $errors->has($fieldName) ? ' is-invalid' : '' }}">
                                <div class="input-group-prepend datepicker-trigger">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                                {{ Form::text($fieldName, old($fieldName, $fieldValue), $attribute) }}
                            </div>
                            @if(isset($fieldMessage)) <span class="small">{{ $fieldMessage }}</span> @endif
                            @if($errors->has($fieldName)) <div class="invalid-feedback">{{ $errors->first($fieldName) }}</div> @endif
                        </div>
                        @if ($first1 == false)
                    </div>
                </div>
            @endif
            <?php $first1 = false; ?>

        @elseif(in_array($fieldName, ['mk_golongan_baru_tahun', 'mk_golongan_baru_bulan']))
            <?php
            $fieldLang = $listPassing['fieldLang'];
            $fieldRequired = $listPassing['fieldRequired'];
            $fieldValue = $listPassing['fieldValue'];
            $fieldMessage = $listPassing['fieldMessage'];
            $path = $listPassing['path'];
            $addAttribute = $listPassing['addAttribute'];
            $fieldExtra = $listPassing['fieldExtra'];
            $viewType = $listPassing['viewType'];

            $attribute = $addAttribute;
            foreach ($fieldExtra as $extraKey => $extraVal) {
                $attribute[$extraKey] = $extraVal;
            }
            $attribute['id'] = $fieldName;
            $attribute['class'] = 'form-control pull-right';
            if ($errors->has($fieldName)) {
                $attribute['class'] .= ' is-invalid';
            }
            $attribute['autocomplete'] = 'off';
            if ($fieldRequired == 1) {
                $attribute['required'] = 'true';
            }
            ?>
            @if ($first3)
                <div class="form-group hideAll perancang">
                    <label>Masa Kerja Golongan Baru Tahun / Bulan</label>
                    <div class="form-row">
                        @endif
                        <div class="col-6">
                            <div class="input-group {{ $errors->has($fieldName) ? ' is-invalid' : '' }}">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                                {{ Form::text($fieldName, old($fieldName, $fieldValue), $attribute) }}
                            </div>
                            @if(isset($fieldMessage)) <span class="small">{{ $fieldMessage }}</span> @endif
                            @if($errors->has($fieldName)) <div class="invalid-feedback">{{ $errors->first($fieldName) }}</div> @endif
                        </div>
                        @if ($first3 == false)
                    </div>
                </div>
            @endif
            <?php $first3 = false; ?>

        @elseif(in_array($fieldName, ['mk_golongan_lama_tahun', 'mk_golongan_lama_bulan']))
            <?php
            $fieldLang = $listPassing['fieldLang'];
            $fieldRequired = $listPassing['fieldRequired'];
            $fieldValue = $listPassing['fieldValue'];
            $fieldMessage = $listPassing['fieldMessage'];
            $path = $listPassing['path'];
            $addAttribute = $listPassing['addAttribute'];
            $fieldExtra = $listPassing['fieldExtra'];
            $viewType = $listPassing['viewType'];

            $attribute = $addAttribute;
            foreach ($fieldExtra as $extraKey => $extraVal) {
                $attribute[$extraKey] = $extraVal;
            }
            $attribute['id'] = $fieldName;
            $attribute['class'] = 'form-control pull-right';
            if ($errors->has($fieldName)) {
                $attribute['class'] .= ' is-invalid';
            }
            $attribute['autocomplete'] = 'off';
            if ($fieldRequired == 1) {
                $attribute['required'] = 'true';
            }
            ?>
            @if ($first4)
                <div class="form-group hideAll perancang">
                    <label>Masa Kerja Golongan Lama Tahun / Bulan</label>
                    <div class="form-row">
                        @endif
                        <div class="col-6">
                            <div class="input-group {{ $errors->has($fieldName) ? ' is-invalid' : '' }}">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                                {{ Form::text($fieldName, old($fieldName, $fieldValue), $attribute) }}
                            </div>
                            @if(isset($fieldMessage)) <span class="small">{{ $fieldMessage }}</span> @endif
                            @if($errors->has($fieldName)) <div class="invalid-feedback">{{ $errors->first($fieldName) }}</div> @endif
                        </div>
                        @if ($first4 == false)
                    </div>
                </div>
            @endif
            <?php $first4 = false; ?>

        @elseif (in_array($fieldName, ['tahun_pelaksaan_diklat', 'tahun_diangkat']))
            <?php
            $fieldLang = $listPassing['fieldLang'];
            $fieldRequired = $listPassing['fieldRequired'];
            $fieldValue = $listPassing['fieldValue'];
            $fieldMessage = $listPassing['fieldMessage'];
            $path = $listPassing['path'];
            $addAttribute = $listPassing['addAttribute'];
            $fieldExtra = $listPassing['fieldExtra'];
            $viewType = $listPassing['viewType'];

            $attribute = $addAttribute;
            foreach ($fieldExtra as $extraKey => $extraVal) {
                $attribute[$extraKey] = $extraVal;
            }
            $attribute['id'] = $fieldName;
            $attribute['class'] = 'form-control pull-right dateyear';
            if ($errors->has($fieldName)) {
                $attribute['class'] .= ' is-invalid';
            }
            $attribute['autocomplete'] = 'off';
            if ($fieldRequired == 1) {
                $attribute['required'] = 'true';
            }
            ?>
            @if ($first2)
                <div class="form-group hideAll perancang">
                    <label>Tahun Pelaksanaan Diklat / Tahun Diangkat</label>
                    <div class="form-row">
                        @endif
                        <div class="col-6">
                            <div class="input-group {{ $errors->has($fieldName) ? ' is-invalid' : '' }}">
                                <div class="input-group-prepend datepicker-trigger">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                                {{ Form::text($fieldName, old($fieldName, $fieldValue), $attribute) }}
                            </div>
                            @if(isset($fieldMessage)) <span class="small">{{ $fieldMessage }}</span> @endif
                            @if($errors->has($fieldName)) <div class="invalid-feedback">{{ $errors->first($fieldName) }}</div> @endif
                        </div>
                        @if ($first2 == false)
                    </div>
                </div>
            @endif
            <?php $first2 = false; ?>




            {{--        @elseif (in_array($fieldName, ['mk_golongan_lama_bulan', 'mk_golongan_lama_tahun']))--}}
            {{--            <?php--}}
            {{--            $fieldLang = $listPassing['fieldLang'];--}}
            {{--            $fieldRequired = $listPassing['fieldRequired'];--}}
            {{--            $fieldValue = $listPassing['fieldValue'];--}}
            {{--            $fieldMessage = $listPassing['fieldMessage'];--}}
            {{--            $path = $listPassing['path'];--}}
            {{--            $addAttribute = $listPassing['addAttribute'];--}}
            {{--            $fieldExtra = $listPassing['fieldExtra'];--}}
            {{--            $viewType = $listPassing['viewType'];--}}

            {{--            $attribute = $addAttribute;--}}
            {{--            foreach ($fieldExtra as $extraKey => $extraVal) {--}}
            {{--                $attribute[$extraKey] = $extraVal;--}}
            {{--            }--}}
            {{--            $attribute['id'] = $fieldName;--}}
            {{--            $attribute['class'] = 'form-control pull-right dateyear';--}}
            {{--            if ($errors->has($fieldName)) {--}}
            {{--                $attribute['class'] .= ' is-invalid';--}}
            {{--            }--}}
            {{--            $attribute['autocomplete'] = 'off';--}}
            {{--            if ($fieldRequired == 1) {--}}
            {{--                $attribute['required'] = 'true';--}}
            {{--            }--}}
            {{--            ?>--}}
            {{--            @if ($first2)--}}
            {{--                <div class="form-group hideAll perancang">--}}
            {{--                    <label>Masa Kerja Golongan Bulan / Tahun </label>--}}
            {{--                    <div class="form-row">--}}
            {{--                        @endif--}}
            {{--                        <div class="col-6">--}}
            {{--                            <div class="input-group {{ $errors->has($fieldName) ? ' is-invalid' : '' }}">--}}
            {{--                                <div class="input-group-prepend datepicker-trigger">--}}
            {{--                                    <div class="input-group-text">--}}
            {{--                                        <i class="fa fa-calendar"></i>--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                                {{ Form::text($fieldName, old($fieldName, $fieldValue), $attribute) }}--}}
            {{--                            </div>--}}
            {{--                            @if(isset($fieldMessage)) <span class="small">{{ $fieldMessage }}</span> @endif--}}
            {{--                            @if($errors->has($fieldName)) <div class="invalid-feedback">{{ $errors->first($fieldName) }}</div> @endif--}}
            {{--                        </div>--}}
            {{--                        @if ($first2 == false)--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            @endif--}}
            {{--            <?php $first2 = false; ?>--}}


        @else
            @component(env('ADMIN_TEMPLATE').'._component.form.'.$fieldData['type'], $listPassing)
            @endcomponent
        @endif
        @endforeach
    </div>
</div>
