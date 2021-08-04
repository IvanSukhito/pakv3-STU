@extends('pdf.base')

@section('content')
<?php

?>
<footer>Page <span class="pagenum"></span></footer>
<main>
    <div class="underline center"> {{ $title }}</div>
    <div class="padding5">&nbsp;</div>
    <div class="center">SURAT PERNYATAAN</div>
    <div class="center">{{ $surat_pernyataan_info ? $surat_pernyataan_info->content1 : '' }}</div>
    <div class="center">{{ $surat_pernyataan_info ? $surat_pernyataan_info->content2 : '' }}</div>
    <div class="padding5">&nbsp;</div>

    <table class="table no-border no-line-height">
        <tr>
            <td colspan="4">Yang bertanda tangan di bawah ini:</td>
        </tr>
        <tr>

            <td width="5%">&nbsp;</td>
            <td width="30%">Nama</td>
            <td width="5%">:</td>
            <td width="60%">{{ $superVisorStaff ? $superVisorStaff->name : '' }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>NIP</td>
            <td>:</td>
            <td>{{ $superVisorUser ? $superVisorUser->username : '' }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Pangkat/golongan ruang/TMT</td>
            <td>:</td>
            <td>{{ $superVisorJenjangPerancang }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ $superVisorJabatanPerancang }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Unit Kerja</td>
            <td>:</td>
            <td>{{ $staff ? $staff->unit : '' }}</td>
        </tr>
        <tr>
            <td colspan="4">Menyatakan bahwa:</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Nama</td>
            <td>:</td>
            <td>{{ $staff ? $staff->name : '' }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>NIP</td>
            <td>:</td>
            <td>{{ $user ? $user->username : '' }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Pangkat/golongan ruang/TMT</td>
            <td>:</td>
            <td>{{ $staffJabatanPerancang }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ $staffJabatanPerancang }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Unit Kerja</td>
            <td>:</td>
            <td>{{ $staff ? $staff->unit : '' }}</td>
        </tr>
    </table>

    <div class="padding5"><?php echo isset($surat_pernyataan_info->content3) ? $surat_pernyataan_info->content3 : null ?></div>

    <table class="table border" cellpadding="0" cellspacing="0">
        <tr>
            <td width="5%" class="center">No</td>
            <td width="31%" class="center"><?php echo isset($surat_pernyataan_info->content4) ? $surat_pernyataan_info->content4 : null ?></td>
            <td width="12%" class="center">Tanggal</td>
            <td width="10%" class="center">Satuan Hasil</td>
            <td width="10%" class="center">Jumlah Volume Kegiatan</td>
            <td width="10%" class="center">Jumlah AK</td>
            <td width="22%" class="center">Keterangan/ bukti fisik</td>
        </tr>
        <tr>
            <td class="center">1</td>
            <td class="center">2</td>
            <td class="center">3</td>
            <td class="center">4</td>
            <td class="center">5</td>
            <td class="center">6</td>
            <td class="center">7</td>
        </tr>
        <?php if($kegiatan): ?>
        <?php $i = 1; foreach($kegiatan as $list): ?>
        <?php
            $judul = isset($list->judul) ? $list->judul : null;
            $deskripsi = isset($list->deskripsi) ? $list->deskripsi : null;
            if(strlen($deskripsi) > 0) {
                $judul .= ' - '.$deskripsi;
            }

            $dokument = '';
            if(isset($list->dokument)) {
                $get_dokument = json_decode($list->dokument, true);
                foreach($get_dokument as $list_dokument) {
                    $dokument_name = isset($list_dokument['name']) ? $list_dokument['name'] : '';
                    $dokument .= $dokument_name.' ';
                }
            }
        ?>
        <tr>
            <td class="center"><?php echo $i++; ?></td>
            <td class="center"><span class="break-all"><?php echo $judul ?></span></td>
            <td class="center"><?php echo isset($list->tanggal) ? date('Y-m-d', strtotime($list->tanggal)) : null ?></td>
            <td class="center"><?php echo isset($list->satuan) ? $list->satuan : null ?></td>
            <td class="center">1</td>
            <td class="center"><?php echo isset($list->kredit) ? number_format($list->kredit, 3) : null ?></td>
            <td class="center"><span class="break-all"><?php echo $dokument ?></span></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
    </table>

    <div class="padding5">Demikian pernyataan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</div>

    <div class="padding10">&nbsp;</div>

    <table class="table no-border no-line-height">
        <tr>
            <td width="70%"></td>
            <td width="30%" class="center">{{ $surat_pernyataan ? $surat_pernyataan->lokasi : '..........' }}, {{ $surat_pernyataan ? replace_month(date('d-F-Y', strtotime($surat_pernyataan->tanggal))) : '' }}</td>
        </tr>
        <tr>
            <td width="70%"></td>
            <td width="30%" class="center">{{ $superVisorStaff ? $superVisorStaff->name : '' }}</td>
        </tr>
        <tr>
            <td width="70%">&nbsp;</td>
            <td width="30%">&nbsp;</td>
        </tr>
        <tr>
            <td width="70%">&nbsp;</td>
            <td width="30%">&nbsp;</td>
        </tr>
        <tr>
            <td width="70%">&nbsp;</td>
            <td width="30%">&nbsp;</td>
        </tr>
        <tr>
            <td width="70%">&nbsp;</td>
            <td width="30%">&nbsp;</td>
        </tr>
        <tr>
            <td width="70%"></td>
            <td width="30%" class="center">{{ $superVisorUser ? $superVisorUser->username : '' }}</td>
        </tr>
    </table>

</main>
@stop
