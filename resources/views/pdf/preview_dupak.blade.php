@extends('pdf.base')

@section('content')

    <footer>Page <span class="pagenum"></span></footer>
    <main>
        <div class="underline center text-first"> {{ $staffUnitKerja ? $staffUnitKerja->name : '' }} </div>
        <div class="center half-line-height" style="padding-top: 5px;">DAFTAR USUL PENETAPAN ANGKA KREDIT</div>
        <div class="center half-line-height">JABATAN PERANCANG PERATURAN PERUNDANG-UNDANGAN</div>
        <div class="center half-line-height">NOMOR: {{ $dupak ? $dupak->nomor : '' }}</div>
        <div style="padding-top: 7px;">Masa Penilaian Tanggal {{ $dupak ? replace_month(date('d-F-Y', strtotime($dupak->penilaian_tanggal))) : '' }}</div>

        <table class="table border" cellpadding="0" cellspacing="0">
            <tr style="background-color: #c0c0c0">
                <td class="center" width="5%">I</td>
                <td class="center" colspan="3" width="95%">KETERANGAN PERORANGAN</td>
            </tr>
            <tr>
                <td class="center" width="5%">1</td>
                <td width="55%" colspan="2">Nama</td>
                <td width="40%">{{ $staff ? $staff->name : '' }}</td>
            </tr>
            <tr>
                <td class="center">2</td>
                <td colspan="2">NIP</td>
                <td>{{ $user ? $user->username : '' }}</td>
            </tr>
            <tr>
                <td class="center">3</td>
                <td colspan="2">Nomor Seri KARPEG</td>
                <td>{{ $staff ? $staff->kartu_pegawai : '' }}</td>
            </tr>
            <tr>
                <td class="center">4</td>
                <td colspan="2">Tempat dan tanggal lahir</td>
                <td>{{ $staff ? $staff->pob : '' }}, {{ $staff ? replace_month(date('d-F-Y', strtotime($staff->birthdate))) : '' }}</td>
            </tr>
            <tr>
                <td class="center">5</td>
                <td colspan="2">Jenis Kelamin</td>
                <td>{{ $staffGender ? $staffGender->name : '' }}</td>
            </tr>
            <tr>
                <td class="center">6</td>
                <td colspan="2">Pendidikan yang telah diperhitungkan angka kreditnya</td>
                <td>{{ $staffPendidikan ? $staffPendidikan->name : '' }}</td>
            </tr>
            <tr>
                <td class="center">7</td>
                <td colspan="2">Pangkat/golongan ruang/TMT</td>
                <td>{{ $staffJabatanPerancang ? $staffJabatanPerancang->name : '' }}</td>
            </tr>
            <tr>
                <td class="center">8</td>
                <td colspan="2">Jabatan Perancang Peraturan Perundang-undangan</td>
                <td>{{ $staffJenjangPerancang ? $staffJenjangPerancang->name : '' }}</td>
            </tr>
            <tr>
                <td rowspan="2" class="center">8</td>
                <td width="45%" rowspan="2" class="left">MASA KERJA GOLONGAN</td>
                <td width="10%" class="left">BARU</td>
                <td colspan="3" class="left">{{ $staff ? $staff->mk_golongan_baru_tahun : '' }} Tahun . &nbsp; .{{ $staff ? $staff->mk_golongan_baru_bulan : '' }} Bulan </td>
            </tr>
            <tr>
                <td class="left">LAMA</td>
                <td colspan="3" class="left">{{ $staff ? $staff->mk_golongan_lama_tahun : '' }} &nbsp; . Tahun . &nbsp; .{{ $staff ? $staff->mk_golongan_lama_bulan : '' }} Bulan</td>
            </tr>
            <tr>
                <td class="center">10</td>
                <td colspan="2">Unit Kerja</td>
                <td>{{ $staffUnitKerja ? $staffUnitKerja->name : '' }}</td>
            </tr>
        </table>

        {!! isset($render_kegiatan) ? $render_kegiatan : '' !!}

        <div class="left">
            Lampiran Usul/Bahan Yang Dinilai
            <ol>
                <?php
                $lampiran = json_decode($dupak->lampiran, true);
                ?>
                @if ($lampiran)
                    @foreach($lampiran as $list)
                        <li><?php echo $list ?></li>
                    @endforeach
                @endif
            </ol>
        </div>

        <table class="table no-border no-line-height" style="page-break-before: always;">
            <tr>
                <td width="30%">&nbsp;</td>
                <td width="40%">&nbsp;</td>
                <td width="30%" class="center">{{ $dupak ? $dupak->lokasi_tanggal : '..........' }}, {{ $dupak ? replace_month(date('d-F-Y', strtotime($dupak->tanggal))) : '..........' }}</td>
            </tr>
            <tr>
                <td class="center">Pejabat Pengusul</td>
                <td>&nbsp;</td>
                <td class="center">Perancang yang bersangkutan</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td class="center">{{ $dupak ? $dupak->jabatan_pengusul : '' }}</td>
                <td>&nbsp;</td>
                <td class="center">{{ $staff ? $staff->name : '' }}</td>
            </tr>
            <tr>
                <td class="center">NIP.{{ $dupak ? $dupak->jabatan_pengusul_nip : '' }}</td>
                <td>&nbsp;</td>
                <td class="center">NIP.{{ $user ? $user->username : '' }}</td>
            </tr>
        </table>
        <div class="padding5">&nbsp;</div>
        <div class="padding5">&nbsp;</div>
        <div class="underline"></div>
        <div>Catatan Tim Penilai</div>
        <div class="padding5">&nbsp;</div>
        <div class="padding5">&nbsp;</div>
        <div class="padding5">&nbsp;</div>
        <div>.........., Tanggal ...........</div>
        <div>Ketua Tim Penilai</div>
        <div class="padding5">&nbsp;</div>
        <div class="padding5">&nbsp;</div>
        <div class="padding5">&nbsp;</div>
        <div>NIP</div>
        <div>Catatan Pejabat Penilai</div>
        <div class="padding5">&nbsp;</div>
        <div class="padding5">&nbsp;</div>
        <div class="padding5">&nbsp;</div>
        <div>.........., Tanggal ...........</div>
        <div>Pejabat Penilai</div>
        <div class="padding5">&nbsp;</div>
        <div class="padding5">&nbsp;</div>
        <div class="padding5">&nbsp;</div>
        <div>NIP</div>

    </main>
@stop
