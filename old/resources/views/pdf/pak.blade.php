@extends('pdf.base2')

@section('content')
<footer>Page <span class="pagenum"></span></footer>
<main style="font-size: 10px;">
    <div class="center">PENETAPAN ANGKA KREDIT</div>
    <div class="center">JABATAN FUNGSIONAL PERANCANG PERATURAN PERUNDANG-UNDANGAN</div>
    <div class="center">NOMOR: {{ $dupak ? $dupak->nomor : '' }}</div>
    <div class="underline"></div>
    <div class="padding5">&nbsp;</div>
    <p>INSTANSI: {{ $staffUnitKerja ? $staffUnitKerja->name : '' }}</p>
    <p>MASA PENILAIAN :  {{ $dupak ? replace_month(date('d-F-Y', strtotime($dupak->penilaian_tanggal))) : '' }}</p>

    <table class="table border" cellpadding="0" cellspacing="0">
        <tr>
            <td width="5%" class="left">I</td>
            <td width="5%" class="left">No</td>
            <td width="90%" colspan="5" class="center">KETERANGAN PERORANGAN</td>
        </tr>
        <tr>
            <td rowspan="10" class="left">&nbsp;</td>
            <td class="center">1</td>
            <td width="55%" colspan="2" class="left">NAMA</td>
            <td width="35%" colspan="3" class="left">{{ $staff ? $staff->name : '' }}</td>
        </tr>
        <tr>
            <td class="center">2</td>
            <td colspan="2" class="left">NIP</td>
            <td colspan="3" class="left">{{ $user ? $user->username : '' }}</td>
        </tr>
        <tr>
            <td class="center">3</td>
            <td colspan="2" class="left">NOMOR SERI KARPEG</td>
            <td colspan="3" class="left">{{ $staff ? $staff->kartu_pegawai : '' }}</td>
        </tr>
        <tr>
            <td class="center">4</td>
            <td colspan="2" class="left">JENIS KELAMIN</td>
            <td colspan="3" class="left">{{ $staffGender ? $staffGender->name : '' }}</td>
        </tr>
        <tr>
            <td class="center">5</td>
            <td colspan="2" class="left">PENDIDIKAN YANG TELAH DIPERHITUNGKAN ANGKA KREDITNYA</td>
            <td colspan="3" class="left">
                @if($staff->pendidikan_id == 6)
                    100
                @elseif($staff->pendidikan_id == 7)
                150
                @elseif($staff->pendidikan_id == 8)
                    175
                    @endif
            </td>
        </tr>
        <tr>
            <td class="center">6</td>
            <td colspan="2" class="left">PANGKAT/GOLONGAN RUANG/TMT</td>
            <td colspan="3" class="left">{{ $staffJabatanPerancang ? $staffJabatanPerancang->name : '' }}</td>
        </tr>
        <tr>
            <td class="center">7</td>
            <td colspan="2" class="left">JABATAN PERANCANG PERATURAN PERUNDANG-UNDANGAN</td>
            <td colspan="3" class="left">{{ $staffJenjangPerancang ? $staffJenjangPerancang->name : '' }}</td>
        </tr>
        <tr>
            <td rowspan="2" class="center">8</td>
            <td width="45%" rowspan="2" class="left">MASA KERJA GOLONGAN</td>
            <td width="10%" class="left">LAMA</td>
            <td colspan="3" class="left">{{ $staff ? $staff->mk_golongan_lama_bulan : '' }} &nbsp; . Bulan . &nbsp; .{{ $staff ? $staff->mk_golongan_lama_tahun : '' }} Tahun</td>
        </tr>
        <tr>
            <td class="left">BARU</td>
            <td colspan="3" class="left">{{ $staff ? $staff->mk_golongan_baru_bulan : '' }} Bulan . &nbsp; .{{ $staff ? $staff->mk_golongan_baru_tahun : '' }} Tahun </td>
        </tr>
        <tr>
            <td class="center">9</td>
            <td colspan="2" class="left">UNIT KERJA</td>
            <td colspan="3" class="left">{{ $staffUnitKerja ? $staffUnitKerja->name : '' }}</td>
        </tr>

        <tr>
            <td class="left">II</td>
            <td class="left">&nbsp;</td>
            <td colspan="2" class="center">PENETAPAN ANGKA KREDIT</td>
            <td class="center">LAMA</td>
            <td class="center">BARU</td>
            <td class="center">JUMLAH</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td class="center">1</td>
            <td class="left" colspan="2">UNSUR UTAMA</td>
            <td class="center">&nbsp;</td>
            <td class="center">&nbsp;</td>
            <td class="center">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="left" colspan="2">a. Pendidikan:</td>
            <td class="center">&nbsp;</td>
            <td class="center">&nbsp;</td>
            <td class="center">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="left" colspan="2" style="padding-left: 30px;">1) Pendidikan sekolah dan memperoleh gelar/ijasah</td>
            <td class="center">{{ $total[0]['lama'] > 0 ? $total[0]['lama'] : '-' }}</td>
            <td class="center">{{ $total[0]['baru'] > 0 ? $total[0]['baru'] : '-' }}</td>
            <td class="center">{{ $total[0]['total'] > 0 ? $total[0]['total'] : '-' }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="left" colspan="2" style="padding-left: 30px;">2) Pendidikan dan pelatihan fungsional perancang peraturan perundang-undangan dan mendapatkan Surat Tanda Tamat Pendidikan dan Pelatihan (STTPL)</td>
            <td class="center">{{ $total[1]['lama'] > 0 ? $total[1]['lama'] : '-' }}</td>
            <td class="center">{{ $total[1]['baru'] > 0 ? $total[1]['baru'] : '-' }}</td>
            <td class="center">{{ $total[1]['total'] > 0 ? $total[1]['total'] : '-' }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="left" colspan="2">b. Penyusunan Peraturan Perundang-undangan/instrumen hukum</td>
            <td class="center">{{ $total[2]['lama'] > 0 ? $total[2]['lama'] : '-' }}</td>
            <td class="center">{{ $total[2]['baru'] > 0 ? $total[2]['baru'] : '-' }}</td>
            <td class="center">{{ $total[2]['total'] > 0 ? $total[2]['total'] : '-' }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="left" colspan="2">c. Pengembangan Profesi</td>
            <td class="center">{{ $total[3]['lama'] > 0 ? $total[3]['lama'] : '-' }}</td>
            <td class="center">{{ $total[3]['baru'] > 0 ? $total[3]['baru'] : '-' }}</td>
            <td class="center">{{ $total[3]['total'] > 0 ? $total[3]['total'] : '-' }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="right" colspan="2"><b><i>Jumlah Unsur Utama</i></b></td>
            <td class="center">{{ $total[4]['lama'] > 0 ? $total[4]['lama'] : '-' }}</td>
            <td class="center">{{ $total[4]['baru'] > 0 ? $total[4]['baru'] : '-' }}</td>
            <td class="center">{{ $total[4]['total'] > 0 ? $total[4]['total'] : '-' }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td class="center">2</td>
            <td class="left" colspan="2">UNSUR PENUNJANG</td>
            <td class="center"></td>
            <td class="center"></td>
            <td class="center"></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td class="center">&nbsp;</td>
            <td class="left" colspan="2">Kegiatan yang menunjang pelaksanaan kegiatan Perancang Peraturan Perundang-undangan</td>
            <td class="center">{{ $total[5]['lama'] > 0 ? $total[5]['lama'] : '-' }}</td>
            <td class="center">{{ $total[5]['baru'] > 0 ? $total[5]['baru'] : '-' }}</td>
            <td class="center">{{ $total[5]['total'] > 0 ? $total[5]['total'] : '-' }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="right" colspan="2"><b><i>Jumlah Unsur Penunjang</i></b></td>
            <td class="center">{{ $total[6]['lama'] > 0 ? $total[6]['lama'] : '-' }}</td>
            <td class="center">{{ $total[6]['baru'] > 0 ? $total[6]['baru'] : '-' }}</td>
            <td class="center">{{ $total[6]['total'] > 0 ? $total[6]['total'] : '-' }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="right" colspan="2"><b><i>Jumlah Unsur Utama + Jumlah Unsur Penunjang</i></b></td>
            <td class="center">{{ $total[7]['lama'] > 0 ? $total[7]['lama'] : '-' }}</td>
            <td class="center">{{ $total[7]['baru'] > 0 ? $total[7]['baru'] : '-' }}</td>
            <td class="center">{{ $total[7]['total'] > 0 ? $total[7]['total'] : '-' }}</td>
        </tr>

    </table>

    <table class="table no-border new_page">
        <tr>
            <td width="60%">&nbsp;</td>
            <td width="40%">
                <p>
                    Ditetapkan di: .....................................<br/>
                    Pada tanggal: ...........................................................
                </p>
            </td>
        </tr>
        <tr>
            <td width="60%">&nbsp;</td>
            <td width="40%" class="center">
                <b>DIREKTUR JENDERAL<br/>PERATURAN PERUNDANG-UNDANGAN,</b>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 60px;">&nbsp;</td>
        </tr>
        <tr>
            <td width="60%">&nbsp;</td>
            <td width="40%" class="center">
                <b>Prof. Dr. Widodo Ekatjahjana, S.H., M.Hum.</b><br/><br/>
                <b>NIP 197105011993031001</b>
            </td>
        </tr>
    </table>
    <div class="left">
        Asli disampaikan dengan hormat kepada:<br/>
        Kepala Badan Kepegawaian Negara di Jakarta.<br/>
        <br/>
        TEMBUSAN disampaikan dengan hormat kepada:
        <ol>
            <li>Perancang Peraturan Perundang-undangan;</li>
            <li>Pimpinan Unit Kerja yang bersangkutan;</li>
            <li>Sekretaris Tim Penilai yang bersangkutan;</li>
            <li>Pejabat yang berwenang menetapkan angka kredit;</li>
            <li>Kepala Biro Kepegawaian instansi yang bersangkutan</li>
        </ol>
    </div>

</main>
@stop
