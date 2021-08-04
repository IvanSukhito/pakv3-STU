@extends('pdf.base')

@section('content')
<footer>Page <span class="pagenum"></span></footer>
<main>
    <div class="center">BERITA ACARA PENETAPAN ANGKA KREDIT (BAPAK)</div>
    <div class="center">JABATAN FUNGSIONAL PERANCANG PERATURAN PERUNDANG-UNDANGAN</div>
    <div class="padding5">&nbsp;</div>
    <table class="no-padding table no-border">
        <tr>
            <td width="15%">Instansi</td>
            <td width="2%">:</td>
            <td width="73%">Kementerian Hukum dan Hak Asasi Manusia</td>
        </tr>
        <tr>
            <td>Masa Penilaian</td>
            <td>:</td>
            <td>{{ $dupak ? replace_month(date('d-F-Y', strtotime($dupak->penilaian_tanggal))) : '' }}</td>
        </tr>
    </table>

    <table class="table border" cellpadding="0" cellspacing="0">
        <tr>
            <td width="5%" class="center" rowspan="2">No</td>
            <td width="65%" colspan="4" class="center">Perancang yang ditetapkan angka kreditnya</td>
            <td width="30%" colspan="3" class="center">Jumlah Angka Kredit</td>
        </tr>
        <tr>
            <td width="16%" class="center">Nama</td>
            <td width="16%" class="center">NIP</td>
            <td width="16%" class="center">Jabatan</td>
            <td width="17%" class="center">Unit Kerja</td>
            <td width="10%" class="center">Unsur Utama</td>
            <td width="10%" class="center">Unsur Penunjang</td>
            <td width="10%" class="center">Total</td>
        </tr>
        <tr>
            <td class="center">1</td>
            <td class="center">{{ $staff ? $staff->name : '' }}</td>
            <td class="center">{{ $staff ? $staff->username : '' }}</td>
            <td class="center">{{ $staffJabatanPerancang ? $staffJabatanPerancang->name : '' }}</td>
            <td class="center">{{ $staffUnitKerja ? $staffUnitKerja->name : '' }}</td>
            <td class="center">{{ $unsur_utama }}</td>
            <td class="center">{{ $unsur_penunjang }}</td>
            <td class="center">{{ $unsur_utama + $unsur_penunjang }}</td>
        </tr>
    </table>
    <div class="right">{{ $dupak ? $dupak->lokasi_tanggal : '' }}, {{ $dupak ? replace_month(date('d-F-Y', strtotime($dupak->tanggal))) : '' }}</div>
    <table class="table border" cellpadding="0" cellspacing="0">
        <tr>
            <td width="5%" class="center">No</td>
            <td width="30%" class="center">NAMA</td>
            <td width="25%" class="center">NIP</td>
            <td width="20%" class="center">JABATAN</td>
            <td width="20%" class="center">TANDA TANGAN</td>
        </tr>
        <?php $i = 1; ?>
        @foreach($listAnggota as $listIndex)
            @foreach($listIndex as $list)
                <tr>
                    <td class="center"><?php echo $i++ ?></td>
                    <td class="left"><?php echo isset($list['Nama']) ? $list['Nama'] : null ?></td>
                    <td class="center"><?php echo isset($list['NIP']) ? $list['NIP'] : null ?></td>
                    <td class="center"><?php echo isset($list['Jabatan']) ? $list['Jabatan'] : null ?></td>
                    <td class="center">&nbsp;</td>
                </tr>
            @endforeach
        @endforeach
        <tr>
            <td colspan="5">
                Catatan Tim Penilai: <br/>
                <div style="padding: 0 15px;">{!! $bapak ? $bapak->berita_acara : '' !!}</div>
            </td>
        </tr>
    </table>
</main>
@stop
