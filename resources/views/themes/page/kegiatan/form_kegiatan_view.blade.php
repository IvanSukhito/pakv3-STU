<?php
$id = isset($id) ? $id : null;
$name = isset($name) ? $name : null;
$checkbox = isset($checkbox) ? $checkbox : false;
$tanggal = isset($tanggal) ? $tanggal : null;
$judul = isset($judul) ? $judul : null;
$deskripsi = isset($deskripsi) ? $deskripsi : null;
$document = isset($document) ? $document : [];

$add_attribute = [];

?>
<label class="checkbox line">
    <span class="text bold">{{ $name }}</span>
</label>
<div id="show_detail{{ $id }}" class="show_detail">
    <?php if($judul): ?>
    <?php foreach($judul as $key => $value): ?>
    <?php
    $list_document = isset($document[$key]) ? $document[$key] : [];

    ?>
    <div class="form-group">
        <label>Tanggal</label>
        <span class="form-control bg-gray-dark">{{ isset($tanggal[$key]) ? $tanggal[$key] : null }}</span>
    </div>
    <div class="form-group">
        <label>Judul</label>
        <span class="form-control bg-gray-dark">{{ $value }}</span>
    </div>
        <?php /*
    <div class="form-group">
        <label>Deskripsi</label>
        <span class="form-control bold">{{ isset($deskripsi[$key]) ? $deskripsi[$key] : null }}</span>
    </div>
        */ ?>
    <div class="form-group">
        <label>Dokument Pendukung</label>
        <div id="dokument_pendukung" class="dokument_pendukung">
            @if(count($list_document) > 0)
                @foreach($list_document as $key2 => $list2)
                    <?php
                    $name = isset($list2['name']) ? $list2['name'] : null;
                    $location = isset($list2['location']) ? $list2['location'] : null;
                    ?>
                    <span><a class="btn-fancybox" href="<?php echo asset($location) ?>" target="_blank"><?php echo $name ?></a></span>
                    <br/>
                @endforeach
            @endif
        </div>
    </div>
        <label>Dokument Fisik</label>
        <div id="dokument_fisik" class="dokument_fisik">
            @if(count($list_document) > 0)
                @foreach($list_document as $key2 => $list2)
                    <?php
                    $name = isset($list2['name']) ? $list2['name'] : null;
                    $location = isset($list2['location']) ? $list2['location'] : null;
                    ?>
                    <span><a class="btn-fancybox" href="<?php echo asset($location) ?>" target="_blank"><?php echo $name ?></a></span>
                    <br/>
                @endforeach
            @endif
        </div>
</div>
        <div style="border: 1px #000 solid"></div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
