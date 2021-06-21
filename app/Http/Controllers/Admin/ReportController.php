<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Models\Gender;
use App\Codes\Models\Golongan;
use App\Codes\Models\JenjangPerancang;
use App\Codes\Models\Staffs;
use App\Codes\Models\UnitKerja;
use App\Codes\Models\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ReportController extends Controller
{
    protected $data;
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->data = [];
        $get_gender = [0 => 'Semua'];
        $temp = Gender::where('status', 1)->pluck('name', 'id');
        if ($temp) {
            foreach ($temp as $id => $name) {
                $get_gender[$id] = $name;
            }
        }
        $get_jenjang_perancang = [
            -1 => 'Semua',
            0 => 'Tidak Ditemukan'
        ];
        $temp = JenjangPerancang::where('status', 1)->pluck('name', 'id');
        if ($temp) {
            foreach ($temp as $id => $name) {
                $get_jenjang_perancang[$id] = $name;
            }
        }
        $get_unit_kerja = [0 => 'Semua'];
        $temp = UnitKerja::where('status', 1)->pluck('name', 'id');
        if ($temp) {
            foreach ($temp as $id => $name) {
                $get_unit_kerja[$id] = $name;
            }
        }
        $get_status = [-1 => 'Semua'];
        $temp = get_list_status();
        if ($temp) {
            foreach ($temp as $id => $name) {
                $get_status[$id] = $name;
            }
        }

        $this->data['list_gender'] = $get_gender;
        $this->data['list_jenjang_perancang'] = $get_jenjang_perancang;
        $this->data['list_unit_kerja'] = $get_unit_kerja;
        $this->data['list_status'] = $get_status;
    }

    public function index()
    {
        $data = $this->data;

        switch ($this->request->get('export')) {
            case 1 : $this->generate(); break;
            case 2 : $this->generateDetail(); break;
        }

        $data['thisLabel'] = 'Report';

        return view(env('ADMIN_TEMPLATE').'.page.report.index', $data);
    }

    protected function generateDetail()
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', -1);

        $m_unit_kerja = $this->data['list_unit_kerja'];

        $get_unit_kerja_id = $this->request->get('unit_kerja_id');
        $status = $this->request->get('status');
        $jenjang_perancang = $this->request->get('jenjang_perancang');

        $unit_kerja_all = 0;
        if (count($get_unit_kerja_id) <= 0) {
            $unit_kerja_all = 1;
        } else {
            foreach ($get_unit_kerja_id as $value) {
                if ($value == 0) {
                    $unit_kerja_all = 1;
                }
            }
        }
        $status_all = 0;
        if (isset($status)) {
            if (count($status) <= 0) {
                $status_all = 1;
            } else {
                foreach ($status as $value) {
                    if ($value == -1) {
                        $status_all = 1;
                    }
                }
            }
        }
        $jenjang_perancang_all = 0;
        if (isset($jenjang_perancang)) {
            if (count($jenjang_perancang) <= 0) {
                $jenjang_perancang_all = 1;
            } else {
                foreach ($jenjang_perancang as $value) {
                    if ($value == -1) {
                        $jenjang_perancang_all = 1;
                    }
                }
            }
        }

        $data = Staffs::select('user_staffs.*')->where('perancang', 1);
        if($unit_kerja_all == 0) {
            $data = $data->whereIn('unit_kerja_id', $get_unit_kerja_id);
        }
        if($status_all == 0 && $status !== null) {
            $data = $data->join('users', 'users.id', '=', 'user_staffs.user_id')
                ->whereIn('status', $status);
        }
        if($jenjang_perancang_all == 0 && $jenjang_perancang !== null) {
            $data = $data->join('users', 'users.id', '=', 'user_staffs.user_id')
                ->whereIn('jenjang_perancang_id', $jenjang_perancang);
        }
        $result = $data->orderBy('name', 'ASC')->get();

        $list_data_detail = [];
        $list_unit_kerja = [];
        $listUserIds = [];
        if ($result) {
            foreach ($result as $list) {
                $listUserIds[] = $list->user_id;
                $list_data_detail[$list->unit_kerja_id][] = $list;
                $list_unit_kerja[] = $list->unit_kerja_id;
            }
        }

        sort($list_unit_kerja);
        $list_unit_kerja = array_unique($list_unit_kerja);

        $listJenjangPerancang = JenjangPerancang::get();
        $temp = [];
        foreach ($listJenjangPerancang as $list) {
            $temp[$list->id] = $list->name;
        }
        $listJenjangPerancang = $temp;

        $listUnitKerja = UnitKerja::get();
        $temp = [];
        foreach ($listUnitKerja as $list) {
            $temp[$list->id] = $list->name;
        }
        $listUnitKerja = $temp;

        $listGolongan = Golongan::get();
        $temp = [];
        foreach ($listGolongan as $list) {
            $temp[$list->id] = $list->name;
        }
        $listGolongan = $temp;

        $listGender = Gender::get();
        $temp = [];
        foreach ($listGender as $list) {
            $temp[$list->id] = $list->name;
        }
        $listGender = $temp;

        $listStatus = get_list_status();
        $listUser = Users::whereIn('id', $listUserIds)->get();
        $temp = [];
        foreach ($listUser as $list) {
            $getStatus = isset($listStatus[$list->status]) ? $listStatus[$list->status] : '';
            $temp[$list->id] = $getStatus;
        }
        $listStatus = $temp;

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator('Hendra Tjua')
            ->setLastModifiedBy('Hendra Tjua')
            ->setTitle('Laporan Rekapitulasi')
            ->setSubject('Laporan Lengkap')
            ->setDescription('Laporan Lengkap PNS');

        for($i=1; $i<count($list_unit_kerja); $i++) {
            $spreadsheet->createSheet();
        }

        $list_header = [
            'NO',
            'NAMA',
            'TAHUN MASUK',
            'NIP',
            'TAHUN PELAKSANAAN DIKLAT',
            'TAHUN PENGANGKATAN SEBAGAI PERANCANG',
            'PEJABAT FUNGSIONAL PERANCANG',
            'ANGKA KREDIT',
            'MASA PENILAIAN TERAKHIR',
            'KANWIL',
            'PANGKAT/GOLONGAN',
            'JENIS KELAMIN',
            'STATUS',
        ];

        $i = 0;
        foreach ($list_unit_kerja as $unit_kerja) {
            $sheet = $spreadsheet->setActiveSheetIndex($i);
            $unit_kerja_name = $m_unit_kerja[$unit_kerja];
            $sheet->setTitle(substr($unit_kerja_name, 0, 31));

            $sheet->setCellValueByColumnAndRow(1, 1, 'DATA PERANCANG PERATURAN PERUNDANG-UNDANGAN');
            $sheet->setCellValueByColumnAndRow(1, 2, 'DI ' . $unit_kerja_name);
            $sheet->setCellValueByColumnAndRow(1, 3, 'Per ' . get_list_bulan(date('n')) . ' ' . date('Y'));
            $sheet->mergeCells('A1:L1');
            $sheet->mergeCells('A2:L2');
            $sheet->mergeCells('A3:L3');
            $sheet->getStyle('A1:L4')->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A1:L3')->getFont()->setBold(true);

            $get_data = isset($list_data_detail[$unit_kerja]) ? $list_data_detail[$unit_kerja] : [];

            $row = 7;
            $start_row = $row;
            $column = 1;
            $total_number = 1;

            foreach ($list_header as $value) {
                $sheet->setCellValueByColumnAndRow($column++, $row, $value);
            }
            $sheet->getStyle('A3:L3')->getAlignment()->setWrapText(true);

            $row += 1;
            $total_wanita = 0;
            $total_pria = 0;
            $total_pegawai = 0;
            foreach ($get_data as $detail) {
                $tahun_masuk = substr($detail->nip, 8, 4);
                $get_tahun_diangkat = $detail->tahun_diangkat;
                $split_tahun = explode('/', $get_tahun_diangkat);
                $tahun_pelaksanaan = isset($split_tahun[0]) ? $split_tahun[0] : '-';
                $tahun_diangkat = isset($split_tahun[1]) ? $split_tahun[1] : '-';
                $getJenjang = isset($listJenjangPerancang[$detail->jenjang_perancang_id]) ? $listJenjangPerancang[$detail->jenjang_perancang_id] : '';
                $getUnitKerja = isset($listUnitKerja[$detail->unit_kerja_id]) ? $listUnitKerja[$detail->unit_kerja_id] : '';
                $getGolongan = isset($listGolongan[$detail->golongan_id]) ? $listGolongan[$detail->golongan_id] : '';
                $getGender = isset($listGender[$detail->gender_id]) ? $listGender[$detail->gender_id] : '';
                $getStatus = isset($listStatus[$detail->id]) ? $listStatus[$detail->id] : '';
                $column = 1;
                $sheet->setCellValueByColumnAndRow($column++, $row, $total_number);
                $sheet->setCellValueByColumnAndRow($column++, $row, $detail->name);
                $sheet->setCellValueByColumnAndRow($column++, $row, $tahun_masuk);
                $sheet->setCellValueByColumnAndRow($column++, $row, $detail->nip.' ');
                $sheet->setCellValueByColumnAndRow($column++, $row, $tahun_pelaksanaan);
                $sheet->setCellValueByColumnAndRow($column++, $row, $tahun_diangkat);
                $sheet->setCellValueByColumnAndRow($column++, $row, $getJenjang);
                $sheet->setCellValueByColumnAndRow($column++, $row, $detail->angka_kredit);
                $sheet->setCellValueByColumnAndRow($column++, $row, $detail->masa_penilaian_terkahir);
                $sheet->setCellValueByColumnAndRow($column++, $row, $getUnitKerja);
                $sheet->setCellValueByColumnAndRow($column++, $row, $getGolongan);
                $sheet->setCellValueByColumnAndRow($column++, $row, $getGender);
                $sheet->setCellValueByColumnAndRow($column++, $row, $getStatus);
                $total_number++;
                $row += 1;
                $total_pegawai++;
                if ($detail->gender_id == 2) {
                    $total_wanita++;
                }
                else {
                    $total_pria++;
                }
            }

            $sheet->getColumnDimensionByColumn(2)->setWidth(40);
            $sheet->getColumnDimensionByColumn(3)->setWidth(15);
            $sheet->getColumnDimensionByColumn(4)->setWidth(22);
            $sheet->getColumnDimensionByColumn(5)->setWidth(15);
            $sheet->getColumnDimensionByColumn(6)->setWidth(15);
            $sheet->getColumnDimensionByColumn(7)->setWidth(15);
            $sheet->getColumnDimensionByColumn(9)->setWidth(15);
            $sheet->getColumnDimensionByColumn(10)->setWidth(20);
            $sheet->getColumnDimensionByColumn(11)->setWidth(15);
            $sheet->getColumnDimensionByColumn(12)->setWidth(15);
            $sheet->getStyle('A' . ($start_row + 1) . ':A' . ($total_number + $start_row + 1))->getAlignment()->setHorizontal('right');
            $sheet->getStyle('C' . ($start_row + 1) . ':C' . ($total_number + $start_row + 1))->getAlignment()->setHorizontal('right');
            $sheet->getStyle('E' . ($start_row + 1) . ':E' . ($total_number + $start_row + 1))->getAlignment()->setHorizontal('right');
            $sheet->getStyle('F' . ($start_row + 1) . ':F' . ($total_number + $start_row + 1))->getAlignment()->setHorizontal('right');
            $sheet->getStyle('H' . ($start_row + 1) . ':H' . ($total_number + $start_row + 1))->getAlignment()->setHorizontal('right');
            $sheet->getStyle('K' . ($start_row + 1) . ':K' . ($total_number + $start_row + 1))->getAlignment()->setHorizontal('right');
            $sheet->getStyle('L' . ($start_row + 1) . ':L' . ($total_number + $start_row + 1))->getAlignment()->setHorizontal('right');

            $sheet->setCellValueByColumnAndRow(1, 5, 'JUMLAH TENAGA PERANCANG PERATURAN PERUNDANG-UNDANGA: ' . number_format($total_pegawai));
            $sheet->mergeCells('A5:C5');
            $sheet->setCellValueByColumnAndRow(8, 5, 'Laki-laki : ' . number_format($total_pria));
            $sheet->mergeCells('H5:I5');
            $sheet->setCellValueByColumnAndRow(10, 5, 'Perempuan : ' . number_format($total_wanita));
            $sheet->mergeCells('J5:K5');

            $i++;
        }

        // Redirect output to a client’s web browser (Xls)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="laporan_lengkap_' . strtotime("now") . '.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        $writer = IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');

        exit;

    }

    protected function generate()
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', -1);

        $get_unit_kerja_id = $this->request->get('unit_kerja_id');

        $unit_kerja_all = 0;
        if (count($get_unit_kerja_id) <= 0) {
            $unit_kerja_all = 1;
        } else {
            foreach ($get_unit_kerja_id as $value) {
                if ($value == 0) {
                    $unit_kerja_all = 1;
                }
            }
        }

        $data = Staffs::where('perancang', 1);
        if($unit_kerja_all == 0) {
            $data = $data->whereIn('unit_kerja_id', $get_unit_kerja_id);
        }
        $result = $data->get();

        if ($result) {
            $list_data = [];
            $list_data_unit_kerja = [];
            foreach ($result as $list) {
                if($list->gender == 2) {
                    $list_data[$list->unit_kerja_id]['total_women'] = isset($list_data[$list->unit_kerja_id]['total_women']) ? $list_data[$list->unit_kerja_id]['total_women'] : 0;
                    $list_data[$list->unit_kerja_id]['total_women'] += 1;
                }
                else {
                    $list_data[$list->unit_kerja_id]['total_man'] = isset($list_data[$list->unit_kerja_id]['total_man']) ? $list_data[$list->unit_kerja_id]['total_man'] : 0;
                    $list_data[$list->unit_kerja_id]['total_man'] += 1;
                }

                $list_data[$list->unit_kerja_id][$list->jenjang_perancang_id] = isset($list_data[$list->unit_kerja_id][$list->jenjang_perancang_id]) ? $list_data[$list->unit_kerja_id][$list->jenjang_perancang_id] : 0;
                $list_data[$list->unit_kerja_id][$list->jenjang_perancang_id] += 1;

                $list_data_unit_kerja[] = $list->unit_kerja_id;
            }

            sort($list_data_unit_kerja);
            $list_data_unit_kerja = array_unique($list_data_unit_kerja);
        }

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator('Hendra Tjua')
            ->setLastModifiedBy('Hendra Tjua')
            ->setTitle('Laporan Rekapitulasi')
            ->setSubject('Laporan')
            ->setDescription('Laporan');

        $sheet = $spreadsheet->getActiveSheet();

        $row = 1;

        $sheet->setCellValueByColumnAndRow(1, $row++, 'DATA PERANCANG PERATURAN PERUNDANG-UNDANGAN');
        $sheet->setCellValueByColumnAndRow(1, $row++, 'Per ' . get_list_bulan(date('n')) . ' ' . date('Y'));
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');
        $sheet->getStyle('A1:I4')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:I3')->getFont()->setBold(true);

        $row++;

        if ($result) {
            $column = 2;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Pria');
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Wanita');
            $list_jenjang_perancang = $this->data['list_jenjang_perancang'];
            $list_jenjang_perancang[0] = 'Tidak Ditemukan';
            $list_unit_kerja = $this->data['list_unit_kerja'];
            unset($list_jenjang_perancang[0]);
            $list_unit_kerja[0] = 'Tidak Ditemukan';
            if ($list_jenjang_perancang) {
                foreach ($list_jenjang_perancang as $list) {
                    $sheet->setCellValueByColumnAndRow($column++, 1, $list);
                }
            }

            $calculate_total = [];
            if($list_data_unit_kerja) {
                foreach ($list_data_unit_kerja as $list) {
                    $column = 1;
                    $row++;
                    $unit_kerja = isset($list_unit_kerja[$list]) ? $list_unit_kerja[$list] : $list;
                    $total_man = isset($list_data[$list]['total_man']) ? $list_data[$list]['total_man'] : 0;
                    $total_women = isset($list_data[$list]['total_women']) ? $list_data[$list]['total_women'] : 0;

                    $calculate_total['total_man'] = isset($calculate_total['total_man']) ? $calculate_total['total_man'] : 0;
                    $calculate_total['total_man'] += $total_man;
                    $calculate_total['total_women'] = isset($calculate_total['total_women']) ? $calculate_total['total_women'] : 0;
                    $calculate_total['total_women'] += $total_women;

                    $sheet->setCellValueByColumnAndRow($column++, $row, $unit_kerja);
                    $sheet->setCellValueByColumnAndRow($column++, $row, $total_man);
                    $sheet->setCellValueByColumnAndRow($column++, $row, $total_women);

                    if ($list_jenjang_perancang) {
                        foreach ($list_jenjang_perancang as $key => $value) {
                            $jenjang_perancang = isset($list_data[$list][$key]) ? $list_data[$list][$key] : 0;

                            $calculate_total[$key] = isset($calculate_total[$key]) ? $calculate_total[$key] : 0;
                            $calculate_total[$key] += $jenjang_perancang;

                            $sheet->setCellValueByColumnAndRow($column++, $row, $jenjang_perancang);
                        }
                    }

                }
            }

            $column = 1;
            $row += 2;
            if ($calculate_total) {
                $total_man_women = 0;
                $total_jenjang = 0;
                $sheet->setCellValueByColumnAndRow($column++, $row, 'SubTotal');
                foreach ($calculate_total as $list) {
                    if($column <= 3) {
                        $total_man_women += $list;
                    }
                    else {
                        $total_jenjang += $list;
                    }
                    $sheet->setCellValueByColumnAndRow($column++, $row, $list);
                }

                $row++;
                $column = 1;
                $sheet->setCellValueByColumnAndRow($column++, $row, 'Total');
                $sheet->setCellValueByColumnAndRow($column, $row, $total_man_women);
                $sheet->mergeCellsByColumnAndRow(2, $row, 3, $row);

                $sheet->setCellValueByColumnAndRow(4, $row, $total_jenjang);
                $sheet->mergeCellsByColumnAndRow(4, $row, 1+count($calculate_total), $row);
            }
        }

        // Redirect output to a client’s web browser (Xls)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="laporan_' . strtotime("now") . '.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

//        // If you're serving to IE over SSL, then the following may be needed
//        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
//        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
//        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
//        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
        exit;
    }

}
