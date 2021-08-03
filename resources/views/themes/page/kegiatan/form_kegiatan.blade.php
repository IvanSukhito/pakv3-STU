<?php
$id = isset($id) ? $id : null;
$name = isset($name) ? $name : null;
$checkbox = isset($checkbox) ? $checkbox : false;
$tanggal = isset($tanggal) ? $tanggal : null;
$judul = isset($judul) ? $judul : null;
$deskripsi = isset($deskripsi) ? $deskripsi : null;
$disabled = isset($disabled) ? $disabled : false;

$add_attribute = [];

if($disabled == true) {
    $add_attribute = [
        'disabled' => true
    ];
}

?>
<label class="checkbox line">
    {{ Form::checkbox('checkbox['.$id.']', 1, $checkbox, array_merge(['class'=>'show_checkbox_kegiatan', 'id'=>'checkbox'.$id, 'name'=>'checkbox['.$id.']', 'data-id'=>$id ], $add_attribute))  }} {{ $name }}
</label>
@if($disabled == false)
<div id="show_detail{{ $id }}" class="show_detail" style="display: none;">
    <div class="form-group">
        <label>Tanggal</label>
        {{ Form::text('tanggal['.$id.']', $tanggal, array_merge(['id'=>'tanggal'.$id, 'name'=>'tanggal['.$id.']', 'placeholder'=>'Isi Tanggal', 'class'=>'form-control datepicker', 'autocomplete'=>'off'], $add_attribute)) }}
    </div>
    <div class="form-group">
        <label>Judul</label>
        {{ Form::text('judul['.$id.']', $judul, array_merge(['id'=>'judul'.$id, 'name'=>'judul['.$id.']', 'placeholder'=>'Isi Judul', 'class'=>'form-control'], $add_attribute)) }}
    </div>
    <?php /*
    <div class="form-group">
        <label>Deskripsi</label>
        <div class="controls">
            {{ Form::text('deskripsi['.$id.']', $deskripsi, array_merge(['id'=>'deskripsi'.$id, 'name'=>'deskripsi['.$id.']', 'placeholder'=>'Isi Deskripsi', 'class'=>'form-control'], $add_attribute)) }}
        </div>
    </div>*/ ?>
    <div class="form-group">
        <label>Dokument Pendukung</label>
        <div class="controls">
            <div id="dokument_pendukung{{ $id }}" class="dokument_pendukung">
                {{ Form::file('dokument['.$id.'][]')  }}
            </div>
            <a href="#"  class="add_more" data-id="{{ $id }}">Add More Dokument Pendukung</a>
        </div>
        <label>Dokument Fisik</label>
        <div class="controls">
            <div id="dokument_fisik{{ $id }}" class="dokument_fisik">
                {{ Form::file('dokument_fisik['.$id.'][]')  }}
            </div>
            <a href="#"  class="add_more_dokumen_fisik" data-id="{{ $id }}">Add More Dokument Fisik</a>
        </div>
    </div>
</div>
@endif
