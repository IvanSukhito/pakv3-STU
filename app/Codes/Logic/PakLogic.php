<?php

namespace App\Codes\Logic;

use App\Codes\Models\Dupak;
use App\Codes\Models\Kegiatan;
use App\Codes\Models\MsKegiatan;
use App\Codes\Models\Users;
use App\Codes\Models\JenjangPerancang;
use App\Codes\Models\Permen;
use App\Codes\Models\SuratPernyataan;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class PakLogic
{
    public function __construct()
    {
    }

    /**
     * @return array
     */
    public function createKegiatan()
    {
        $getPermen = Permen::where('status', 1)->get();
        $permenIds = [];
        $listPermen = [];
        foreach ($getPermen as $list) {
            $listPermen[$list->id] = $list->name;
            $permenIds[] = $list->id;
        }

        $getMsKegiatan = MsKegiatan::where('status', 1)->whereIn('permen_id', $permenIds)->get();
        $getMasterFilter = [];
        foreach ($getMsKegiatan as $list) {
            if ($list->parent_id <= 0) {
                $getMasterFilter[$list->id] = $list->name;
            }
        }

        return [
            'data' => $this->getCreateListTreeKegiatan($getMsKegiatan->toArray()),
            'permen' => $listPermen,
            'filter' => $getMasterFilter
        ];

    }

    /**
     * @param $msKegiatan
     * @param int $parentId
     * @return array
     */
    protected function getCreateListTreeKegiatan($msKegiatan, $parentId = 0)
    {
        $getTreeId = [];
        foreach ($msKegiatan as $list) {
            $getTreeId[$list['parent_id']][] = $list;
        }

        return $this->getCreateChildTreeKegiatan($getTreeId, $parentId);

    }

    /**
     * @param $msKegiatan
     * @param int $parentId
     * @return array
     */
    protected function getCreateChildTreeKegiatan($msKegiatan, $parentId = 0)
    {
        $result = [];
        if (isset($msKegiatan[$parentId])) {
            foreach ($msKegiatan[$parentId] as $list) {
                if (isset($msKegiatan[$list['id']])) {
                    $child = $this->getCreateChildTreeKegiatan($msKegiatan, $list['id']);
                    $list['have_child'] = 1;
                    $list['child'] = $child;
                }
                else {
                    $list['have_child'] = 0;
                }
                $result[] = $list;
            }
        }

        return $result;

    }

    /**
     * @param $userId
     * @param null $getDateRange
     * @param array $status
     * @return array
     */
    public function getKegiatanUser($userId, $getDateRange = null, array $status = [])
    {
        if (count($status) <= 0) {
            $status = [1];
        }

        if ($getDateRange) {
            $getSplitDate = explode(' | ', $getDateRange);
            $getDateStart = date('Y-m-d', strtotime($getSplitDate[0]));
            $getDateEnd = isset($getSplitDate[1]) ? date('Y-m-d', strtotime($getSplitDate[1])) : date('Y-m-d', strtotime($getSplitDate[0]));

            $getKegiatan = Kegiatan::where('user_id', $userId)->whereIn('status', $status)
                ->where('tanggal', '>=', $getDateStart)->where('tanggal', '<=', $getDateEnd)
                ->orderBy('tanggal', 'ASC')->get();

        }
        else {
            $getKegiatan = Kegiatan::where('user_id', $userId)->whereIn('status', $status)->orderBy('tanggal', 'ASC')->get();
        }

        if ($getKegiatan) {
            $getJudul = [];
            $getDataKegiatan = [];
            $permenIds = [];
            $topIds = [];
            $totalAk = 0;
            $kredit = [];

            foreach ($getKegiatan as $list) {
                $permenIds[] = $list->permen_id;
                $topIds[] = $list->top_id;
                $kredit[$list->top_id][] = $list->kredit;
                $getJudul[$list->permen_id][$list->top_id][$list->judul][] = $list->ms_kegiatan_id;
                $getDataKegiatan[$list->permen_id][$list->top_id][$list->judul][$list->ms_kegiatan_id][] = $list->toArray();
                $totalAk += $list->kredit;
            }

            if (count($permenIds) > 0) {
                $permenIds = array_unique($permenIds);
            }
            if (count($topIds) > 0) {
                $topIds = array_unique($topIds);
            }

            $getMsKegiatan = MsKegiatan::whereIn('permen_id', $permenIds)->get();
            $temp = [];
            $getListTopKegiatan = [];
            foreach ($getMsKegiatan->toArray() as $list) {
                if ($list['parent_id'] <= 0) {
                    $getListTopKegiatan[$list['id']] = $list;
                }
                $temp[$list['id']] = $list;
            }

            $getMsKegiatan = $temp;

            $getPermen = Permen::whereIn('id', $permenIds)->get();
            $listPermen = [];
            foreach ($getPermen as $list) {
                $listPermen[$list->id] = $list->name;
            }

            return [
                'data' => $this->getParentTreeKegiatan($getMsKegiatan, $getJudul, $getDataKegiatan),
                'total_permen' => $permenIds,
                'total_top' => $topIds,
                'total_ak' => $totalAk,
                'permen' => $listPermen,
                'top_kegiatan' => $getListTopKegiatan,
                'kredit' => $kredit,


            ];

        }

        return [];

    }

    public function getSuratPernyataanUser($getSuratpernyataanIds)
    {
        $getKegiatan = Kegiatan::selectRaw('tx_kegiatan.*, tx_surat_pernyataan_kegiatan.id AS sp_kegiatan_id, tx_surat_pernyataan_kegiatan.message AS sp_kegiatan_message, tx_surat_pernyataan_kegiatan.status AS sp_kegiatan_status')
            ->join('tx_surat_pernyataan_kegiatan', 'tx_surat_pernyataan_kegiatan.kegiatan_id', '=', 'tx_kegiatan.id')
            ->whereIn('tx_surat_pernyataan_kegiatan.surat_pernyataan_id', $getSuratpernyataanIds)
            ->orderBy('tx_kegiatan.tanggal', 'ASC')->get();

        if ($getKegiatan) {
            $getJudul = [];
            $getDataKegiatan = [];
            $permenIds = [];
            $topIds = [];
            $totalAk = 0;
            $kredit = [];

            foreach ($getKegiatan as $list) {
                $permenIds[] = $list->permen_id;
                $topIds[] = $list->top_id;
                $kredit[$list->top_id][] = $list->kredit;
                $getJudul[$list->permen_id][$list->top_id][$list->judul][] = $list->ms_kegiatan_id;
                $getDataKegiatan[$list->permen_id][$list->top_id][$list->judul][$list->ms_kegiatan_id][] = $list->toArray();
                $totalAk += $list->kredit;
            }

            if (count($permenIds) > 0) {
                $permenIds = array_unique($permenIds);
            }
            if (count($topIds) > 0) {
                $topIds = array_unique($topIds);
            }

            $getMsKegiatan = MsKegiatan::whereIn('permen_id', $permenIds)->get();
            $temp = [];
            $getListTopKegiatan = [];
            foreach ($getMsKegiatan->toArray() as $list) {
                if ($list['parent_id'] <= 0) {
                    $getListTopKegiatan[$list['id']] = $list;
                }
                $temp[$list['id']] = $list;
            }

            $getMsKegiatan = $temp;

            $getPermen = Permen::whereIn('id', $permenIds)->get();
            $listPermen = [];
            foreach ($getPermen as $list) {
                $listPermen[$list->id] = $list->name;
            }

            return [
                'data' => $this->getParentTreeKegiatan($getMsKegiatan, $getJudul, $getDataKegiatan),
                'total_permen' => $permenIds,
                'total_top' => $topIds,
                'total_ak' => $totalAk,
                'permen' => $listPermen,
                'top_kegiatan' => $getListTopKegiatan,
                'kredit' => $kredit
            ];

        }

        return [];
    }

    public function getDupakUser($dupak)
    {
        $getKegiatan = Kegiatan::selectRaw('tx_kegiatan.*, tx_dupak_kegiatan.id AS sp_kegiatan_id, tx_dupak_kegiatan.message AS sp_kegiatan_message, tx_dupak_kegiatan.status AS sp_kegiatan_status')
            ->join('tx_dupak_kegiatan', 'tx_dupak_kegiatan.kegiatan_id', '=', 'tx_kegiatan.id')
            ->where('tx_dupak_kegiatan.dupak_id', '=', $dupak->id)
            ->orderBy('tx_kegiatan.tanggal', 'ASC')->get();

        if ($getKegiatan) {
            $getJudul = [];
            $getDataKegiatan = [];
            $permenIds = [];
            $topIds = [];
            $totalAk = 0;
            $kredit = [];

            foreach ($getKegiatan as $list) {
                $permenIds[] = $list->permen_id;
                $topIds[] = $list->top_id;
                $kredit[$list->top_id][] = $list->kredit;
                $getJudul[$list->permen_id][$list->top_id][$list->judul][] = $list->ms_kegiatan_id;
                $getDataKegiatan[$list->permen_id][$list->top_id][$list->judul][$list->ms_kegiatan_id][] = $list->toArray();
                $totalAk += $list->kredit;
            }

            if (count($permenIds) > 0) {
                $permenIds = array_unique($permenIds);
            }
            if (count($topIds) > 0) {
                $topIds = array_unique($topIds);
            }

            $getMsKegiatan = MsKegiatan::whereIn('permen_id', $permenIds)->get();
            $temp = [];
            $getListTopKegiatan = [];
            foreach ($getMsKegiatan->toArray() as $list) {
                if ($list['parent_id'] <= 0) {
                    $getListTopKegiatan[$list['id']] = $list;
                }
                $temp[$list['id']] = $list;
            }

            $getMsKegiatan = $temp;

            $getPermen = Permen::whereIn('id', $permenIds)->get();
            $listPermen = [];
            foreach ($getPermen as $list) {
                $listPermen[$list->id] = $list->name;
            }

            return [
                'data' => $this->getParentTreeKegiatan($getMsKegiatan, $getJudul, $getDataKegiatan),
                'total_permen' => $permenIds,
                'total_top' => $topIds,
                'total_ak' => $totalAk,
                'permen' => $listPermen,
                'top_kegiatan' => $getListTopKegiatan,
                'kredit' => $kredit
            ];

        }

        return [];
    }

    public function getPakUser($pak)
    {
        $getKegiatan = Kegiatan::selectRaw('tx_kegiatan.*, tx_pak_kegiatan.id AS sp_kegiatan_id, tx_pak_kegiatan.message AS sp_kegiatan_message, tx_pak_kegiatan.status AS sp_kegiatan_status')
            ->join('tx_pak_kegiatan', 'tx_pak_kegiatan.kegiatan_id', '=', 'tx_kegiatan.id')
            ->where('tx_pak_kegiatan.pak_id', '=', $pak->id)
            ->orderBy('tx_kegiatan.tanggal', 'ASC')->get();

        if ($getKegiatan) {
            $getJudul = [];
            $getDataKegiatan = [];
            $permenIds = [];
            $topIds = [];
            $totalAk = 0;
            $kredit = [];

            foreach ($getKegiatan as $list) {
                $permenIds[] = $list->permen_id;
                $topIds[] = $list->top_id;
                $kredit[$list->top_id][] = $list->kredit;
                $getJudul[$list->permen_id][$list->top_id][$list->judul][] = $list->ms_kegiatan_id;
                $getDataKegiatan[$list->permen_id][$list->top_id][$list->judul][$list->ms_kegiatan_id][] = $list->toArray();
                $totalAk += $list->kredit;
            }

            if (count($permenIds) > 0) {
                $permenIds = array_unique($permenIds);
            }
            if (count($topIds) > 0) {
                $topIds = array_unique($topIds);
            }

            $getMsKegiatan = MsKegiatan::whereIn('permen_id', $permenIds)->get();
            $temp = [];
            $getListTopKegiatan = [];
            foreach ($getMsKegiatan->toArray() as $list) {
                if ($list['parent_id'] <= 0) {
                    $getListTopKegiatan[$list['id']] = $list;
                }
                $temp[$list['id']] = $list;
            }

            $getMsKegiatan = $temp;

            $getPermen = Permen::whereIn('id', $permenIds)->get();
            $listPermen = [];
            foreach ($getPermen as $list) {
                $listPermen[$list->id] = $list->name;
            }

            return [
                'data' => $this->getParentTreeKegiatan($getMsKegiatan, $getJudul, $getDataKegiatan),
                'total_permen' => $permenIds,
                'total_top' => $topIds,
                'total_ak' => $totalAk,
                'permen' => $listPermen,
                'top_kegiatan' => $getListTopKegiatan,
                'kredit' => $kredit
            ];

        }

        return [];
    }

    /**
     * @param $msKegiatan
     * @param $listJudulKegiatan
     * @param $dataKegiatan
     * @return array
     */
    protected function getParentTreeKegiatan($msKegiatan, $listJudulKegiatan, $dataKegiatan)
    {
        $result = [];

        foreach ($listJudulKegiatan as $getPermen => $listTopKegiatan) {
            foreach ($listTopKegiatan as $getTop => $listJudul) {
                foreach ($listJudul as $getJudul => $listKegiatan) {
                    $getAllKegiatanId = [];
                    foreach ($listKegiatan as $kegiatanId) {
                        $getListParent = $this->checkParentTreeKegiatan($msKegiatan, $kegiatanId);
                        $getAllKegiatanId = array_merge($getAllKegiatanId, $getListParent);
                    }
                    $getAllKegiatanId = array_unique($getAllKegiatanId);
                    sort($getAllKegiatanId);
                    $tempKegiatan = [];
                    foreach ($getAllKegiatanId as $kegId) {
                        if (isset($msKegiatan[$kegId])) {
                            $showDataKegiatan = isset($dataKegiatan[$getPermen][$getTop][$getJudul][$kegId]) ? $dataKegiatan[$getPermen][$getTop][$getJudul][$kegId] : false;
                            $temp = $msKegiatan[$kegId];
                            if ($showDataKegiatan) {
                                $temp['data'] = $showDataKegiatan;
                            }

                            $tempKegiatan[] = $temp;

                        }
                    }

                    $result[$getPermen][$getTop][$getJudul] = $this->getCreateListTreeKegiatan($tempKegiatan);

                }
            }
        }

        return $result;

    }

    /**
     * @param $msKegiatan
     * @param $childId
     * @param $fromId
     * @return array
     */
    protected function checkParentTreeKegiatan($msKegiatan, $childId)
    {
        $result = [];
        if (isset($msKegiatan[$childId])) {
            $getMsKegiatan = $msKegiatan[$childId];
            if ($getMsKegiatan['parent_id'] > 0) {
                $getParent = $this->checkParentTreeKegiatan($msKegiatan, $getMsKegiatan['parent_id']);
                $result = $getParent;
            }
            $result[] = $getMsKegiatan['id'];
        }
        return $result;
    }

    public function generateSuratPernyataan($suratPernyataanId)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);

        $getSuratPernyataan = SuratPernyataan::where('id', $suratPernyataanId)->first();
        $datenow = date("Y-m-d");
        if($getSuratPernyataan) {
            $getInfoSuratPernyataan = json_decode($getSuratPernyataan->info_surat_pernyataan, TRUE);
            $getPerancangName = $getInfoSuratPernyataan['perancang_name'] ?? '';
            $getPerancangNIP = $getInfoSuratPernyataan['perancang_nip'] ?? '';
            $getPerancangPangkat = $getInfoSuratPernyataan['perancang_pangkat'] ?? '';
            $getPerancangJabatan = $getInfoSuratPernyataan['perancang_jabatan'] ?? '';
            $getPerancangUnitKerja = $getInfoSuratPernyataan['perancang_unit_kerja'] ?? '';
            $getAtasanName = $getInfoSuratPernyataan['atasan_name'] ?? '';
            $getAtasanNIP = $getInfoSuratPernyataan['atasan_nip'] ?? '';
            $getAtasanPangkat = $getInfoSuratPernyataan['atasan_pangkat'] ?? '';
            $getAtasanJabatan = $getInfoSuratPernyataan['atasan_jabatan'] ?? '';
            $getAtasanUnitKerja = $getInfoSuratPernyataan['atasan_unit_kerja'] ?? '';

            $getData = $this->getSuratPernyataanUser($getSuratPernyataan);
            $getDataKegiatan = $getData['data'] ?? [];
            $getTotalTop = $getData['total_top'][0] ?? 0;
            $getListTopKegiatan = $getData['top_kegiatan'];
            $getTopKegiatan = $getListTopKegiatan[$getTotalTop] ?? false;

            $getTitleSuratPernyataan = $getTopKegiatan && isset($getTopKegiatan['name']) ? $getTopKegiatan['name'] : '';

            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()->setCreator('Peraturan Perundang-undangan')
                ->setLastModifiedBy('PAK')
                ->setTitle('Laporan Surat Pernyataan')
                ->setSubject('Laporan Surat Pernyataan')
                ->setDescription('Laporan Surat Pernyataan');

            $sheet = $spreadsheet->getActiveSheet();

            $column = 1;
			//$sheet->getRowDimension('2')->setRowHeight(90.23);
			$sheet->getColumnDimensionByColumn($column++)->setWidth(5.43);
            $sheet->getColumnDimensionByColumn($column++)->setWidth(2.43);
			$sheet->getColumnDimensionByColumn($column++)->setWidth(50.00);
            $sheet->getColumnDimensionByColumn($column++)->setWidth(12.00);
            $sheet->getColumnDimensionByColumn($column++)->setWidth(12.00);
			$sheet->getColumnDimensionByColumn($column++)->setWidth(6);
			$sheet->getColumnDimensionByColumn($column++)->setWidth(5);
			$sheet->getColumnDimensionByColumn($column++)->setWidth(5);
			$sheet->getColumnDimensionByColumn($column++)->setWidth(5);
			$sheet->getColumnDimensionByColumn($column++)->setWidth(7);
			$sheet->getColumnDimensionByColumn($column++)->setWidth(15);

            $totalColumn = 11;

            $row = 2;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'KEMENTERIAN HUKUM DAN HAK ASASI MANUSIA REPUBLIK INDONESIA
            DIREKTORAT JENDERAL ADMINISTRASI HUKUM UMUM');
            $sheet->mergeCellsByColumnAndRow(1,$row, $totalColumn, $row);
            $sheet->getStyleByColumnAndRow(1,$row, $totalColumn, $row)->applyFromArray(array(
				'font' => array(
					'bold' => true
				),
				'alignment' => array(
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
					'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
					'wrapText' => true
				),

			));

            $row += 1;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row,'DIREKTORAT JENDERAL ADMINISTRASI HUKUM UMUM');
            $sheet->mergeCellsByColumnAndRow(1,$row, $totalColumn, $row);
            $sheet->getStyleByColumnAndRow(1,$row, $totalColumn, $row)->applyFromArray(array(
				'font' => array(
					'bold' => true
				),
				'alignment' => array(
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
					'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
					'wrapText' => true
				),
			));

            $row += 1;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Jl. HR. Rasuna Said Kav. 6-7 Kuningan, Jakarta Selatan');
            $sheet->mergeCellsByColumnAndRow(1,$row, $totalColumn, $row);
            $sheet->getStyleByColumnAndRow(1,$row, $totalColumn, $row)->applyFromArray(array(
				'alignment' => array(
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
					'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
					'wrapText' => true
				)
			));

            $row += 1;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Telp. (021) 5221618, Fax. (021) 5265480');
            $sheet->mergeCellsByColumnAndRow(1,$row, $totalColumn, $row);
            $sheet->getStyleByColumnAndRow(1,$row, $totalColumn, $row)->applyFromArray(array(
				'alignment' => array(
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
					'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
					'wrapText' => true
				)
			));

            $row += 1;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'laman: www.ahu.go.id, Surel:humas@ahu.go.id');
            $sheet->mergeCellsByColumnAndRow(1,$row, $totalColumn, $row);
            $sheet->getStyleByColumnAndRow(1,$row, $totalColumn, $row)->applyFromArray(array(
				'alignment' => array(
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
					'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
					'wrapText' => true
				),
                'borders' => array(
					'bottom' => array(
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
						'color' => array('argb' => '00000000'),
					)
				)
			));


            $row += 2;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'SURAT PERNYATAAN');
            $sheet->mergeCellsByColumnAndRow(1,$row, $totalColumn, $row);
            $sheet->getStyleByColumnAndRow(1,$row, $totalColumn, $row)->applyFromArray(array(
				'font' => array(
					'bold' => true
				),
				'alignment' => array(
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
					'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
					'wrapText' => true
				)
			));


            $row += 1;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'MELAKUKAN '.$getTitleSuratPernyataan);
            $sheet->mergeCellsByColumnAndRow(1,$row, $totalColumn, $row);
            $sheet->getStyleByColumnAndRow(1,$row, $totalColumn, $row)->applyFromArray(array(
				'font' => array(
					'bold' => true
				),
				'alignment' => array(
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
					'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
					'wrapText' => true
				),
			));


            $row += 2;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Yang bertanda tangan dibawah ini');
            $sheet->mergeCellsByColumnAndRow(1,$row, $totalColumn, $row);

            $row += 1;
            $column = 3;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Nama');
            $sheet->setCellValueByColumnAndRow($column, $row, ': '.$getAtasanName);
            $sheet->mergeCellsByColumnAndRow($column,$row, $totalColumn, $row);

            $row += 1;
            $column = 3;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Nip');
            $sheet->setCellValueByColumnAndRow($column, $row, ': '.$getAtasanNIP);
            $sheet->mergeCellsByColumnAndRow($column,$row, $totalColumn, $row);

            $row += 1;
            $column = 3;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Pangkat/golongan ruang/TMT');
            $sheet->setCellValueByColumnAndRow($column, $row, ': '.$getAtasanPangkat);
            $sheet->mergeCellsByColumnAndRow($column,$row, $totalColumn, $row);

            $row += 1;
            $column = 3;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Jabatan');
            $sheet->setCellValueByColumnAndRow($column, $row, ': '.$getAtasanJabatan);
            $sheet->mergeCellsByColumnAndRow($column,$row, $totalColumn, $row);

            $row += 1;
            $column = 3;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Unit Kerja');
            $sheet->setCellValueByColumnAndRow($column, $row, ': '.$getAtasanUnitKerja);
            $sheet->mergeCellsByColumnAndRow($column,$row, $totalColumn, $row);

            //Atasan
            $row += 2;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Menyatakan bahwa');
            $sheet->mergeCellsByColumnAndRow(1,$row, $totalColumn, $row);

            $row += 1;
            $column = 3;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Nama');
            $sheet->setCellValueByColumnAndRow($column, $row, ': '.$getPerancangName);
            $sheet->mergeCellsByColumnAndRow($column,$row, $totalColumn, $row);

            $row += 1;
            $column = 3;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Nip');
            $sheet->setCellValueByColumnAndRow($column, $row, ': '.$getPerancangNIP);
            $sheet->mergeCellsByColumnAndRow($column,$row, $totalColumn, $row);

            $row += 1;
            $column = 3;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Pangkat/golongan ruang/TMT');
            $sheet->setCellValueByColumnAndRow($column, $row, ': '.$getPerancangPangkat);
            $sheet->mergeCellsByColumnAndRow($column,$row, $totalColumn, $row);

            $row += 1;
            $column = 3;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Jabatan');
            $sheet->setCellValueByColumnAndRow($column, $row, ': '.$getPerancangJabatan);
            $sheet->mergeCellsByColumnAndRow($column,$row, $totalColumn, $row);

            $row += 1;
            $column = 3;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Unit Kerja');
            $sheet->setCellValueByColumnAndRow($column, $row, ': '.$getPerancangUnitKerja);
            $sheet->mergeCellsByColumnAndRow($column,$row, $totalColumn, $row);

            $row += 2;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Telah melakukan kegiatan penyusunan Peraturan Perundang-undangan sebagai berikut :');
            $sheet->mergeCellsByColumnAndRow(1,$row, $totalColumn, $row);

            $row += 2;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'No');
            $column += 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Uraian Kegiatan Penyusunan Peraturan Perundang-undangan');
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Tanggal');
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Jumlah Volume Kegiatan');
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Jumlah AK');
            $column += 4;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Keterangan/ Bukti Fisik');
            $sheet->mergeCellsByColumnAndRow(1,$row, 2, $row);
            $sheet->mergeCellsByColumnAndRow(6,$row, 10, $row);

            $row += 1;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, '1');
            $column += 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, '2');
            $sheet->setCellValueByColumnAndRow($column++, $row, '3');
            $sheet->setCellValueByColumnAndRow($column++, $row, '4');
            $sheet->setCellValueByColumnAndRow($column++, $row, '5');
            $column += 4;
            $sheet->setCellValueByColumnAndRow($column++, $row, '6');

            $sheet->mergeCellsByColumnAndRow(1,$row, 2, $row);
            $sheet->mergeCellsByColumnAndRow(6,$row, 10, $row);

            $sheet->getStyleByColumnAndRow(1,$row-1, $totalColumn, $row)->applyFromArray(array(
				'font' => array(
					'bold' => true
				),
				'alignment' => array(
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
					'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
					'wrapText' => true
				),
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => array('argb' => '00000000'),
                    )
                )
			));

            $styleJudul = array(
                'font' => array(
                    'bold' => true
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ),
                'borders' => array(
                    'outline' => array(
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => array('argb' => '00000000'),
                    )
                )
            );
            $styleTopKegiatan = array(
                'font' => array(
                    'bold' => true
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ),
                'borders' => array(
                    'outline' => array(
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => array('argb' => '00000000'),
                    )
                )
            );

            $styleKegiatanL = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ),
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => array('argb' => '00000000'),
                    )
                )
            );
            $styleKegiatanC = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ),
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => array('argb' => '00000000'),
                    )
                )
            );

            //ISI
            foreach ($getDataKegiatan as $permenId => $listTopKegiatan) {
                foreach ($listTopKegiatan as $topId => $listJudul) {
                    $indexingJudul = 1;
                    foreach ($listJudul as $nameJudul => $listKegiatan) {

                        $row += 1;
                        $column = 2;
                        $sheet->setCellValueByColumnAndRow($column++, $row, $indexingJudul.'. '.$nameJudul);
                        $sheet->mergeCellsByColumnAndRow(2,$row, $totalColumn, $row);
                        $sheet->getStyleByColumnAndRow(1,$row, $totalColumn, $row)->applyFromArray($styleJudul);

                        $indexingKegiatan = 1;

                        if ($listKegiatan[0]['have_child'] == 1) {
                            foreach ($listKegiatan[0]['child'] as $list) {
                                $getName = $list['name'];
                                $row += 1;
                                $column = 3;
                                $sheet->setCellValueByColumnAndRow($column++, $row, $getName);
                                $sheet->mergeCellsByColumnAndRow(3,$row, $totalColumn, $row);
                                $sheet->getStyleByColumnAndRow(1,$row, $totalColumn, $row)->applyFromArray($styleTopKegiatan);

                                $startRow = $row + 1;
                                if ($list['have_child'] == 1) {
                                    $row = $this->generateChildSuratPernyataan($sheet, $row, $list['child'], $getName, 1);
                                }

                                $endRow = $row;
                                $sheet->setCellValueByColumnAndRow(1, $startRow, $indexingJudul.",".$indexingKegiatan);
                                $sheet->mergeCellsByColumnAndRow(1, $startRow, 1, $endRow);
                                $sheet->getStyleByColumnAndRow(1,$startRow, 2, $endRow)->applyFromArray($styleKegiatanC);
                                $sheet->getStyleByColumnAndRow(3,$startRow, 3, $endRow)->applyFromArray($styleKegiatanL);
                                $sheet->getStyleByColumnAndRow(4,$startRow, $totalColumn, $endRow)->applyFromArray($styleKegiatanC);

                                $indexingKegiatan++;
                            }
                            $indexingJudul++;
                        }

                    }
                }
            }

            $row += 3;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column, $row, 'Demikian pernyataan ini dibuat untuk dapat dipergunakan sebagaimana mestinya');
            $sheet->mergeCellsByColumnAndRow(1,$row, $totalColumn, $row);

            $row += 3;
            $column = 6;
            $startRow = $row;
            $sheet->setCellValueByColumnAndRow($column, $row, 'Jakarta, '.$datenow);
            $sheet->mergeCellsByColumnAndRow($column,$row, $totalColumn, $row);

            $row += 2;
            $column = 6;
            $sheet->setCellValueByColumnAndRow($column, $row, 'Atasan Langsung');
            $sheet->mergeCellsByColumnAndRow($column,$row, $totalColumn, $row);

            $row += 1;
            $column = 6;
            $sheet->setCellValueByColumnAndRow($column, $row, 'Kepala Bagian Program dan Pelaporan');
            $sheet->mergeCellsByColumnAndRow($column,$row, $totalColumn, $row);

            $row += 1;
            $column = 6;
            $sheet->setCellValueByColumnAndRow($column, $row, 'Direktorat Jenderal Administrasi Hukum Umum');
            $sheet->mergeCellsByColumnAndRow($column,$row, $totalColumn, $row);

            $row += 4;
            $column = 6;
            $sheet->setCellValueByColumnAndRow($column, $row, $getAtasanName);
            $sheet->mergeCellsByColumnAndRow($column,$row, $totalColumn, $row);

            $row += 1;
            $column = 6;
            $sheet->setCellValueByColumnAndRow($column, $row, 'NIP.'.$getAtasanNIP);
            $sheet->mergeCellsByColumnAndRow($column,$row, $totalColumn, $row);

            $sheet->getStyleByColumnAndRow($column,$startRow, $totalColumn, $row)->applyFromArray(array(
                'alignment' => array(
                    'wrapText' => true
                )
            ));

            // Redirect output to a client’s web browser (Xls)
//            header('Content-Type: application/vnd.ms-excel');
//            header('Content-Disposition: attachment;filename="surat_pernyataan_' . strtotime("now") . '.xlsx"');

            // Redirect output to a client’s web browser (PDF)
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment;filename="surat_pernyataan_' . strtotime("now") . '.pdf"');

            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
//            header('Cache-Control: max-age=1');

//            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            IOFactory::registerWriter('Pdf', \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf::class);
            $writer = IOFactory::createWriter($spreadsheet, 'Pdf');
            $writer->save('php://output');
            exit;

        }
    }

    protected function generateChildSuratPernyataan($sheet, $row, $getChildKegiatan, $parentName, $deep)
    {
        foreach ($getChildKegiatan as $list) {
            $getName = $list['name'];
            if ($list['have_child'] == 1) {
                $row = $this->generateChildSuratPernyataan($sheet, $row, $list['child'], $getName, $deep+1);
            }
            else {
                $row += 1;
                $this->generateChildFillSuratPernyataan($sheet, $row, $list, $parentName, $deep);
            }
        }

        return $row;

    }

    protected function generateChildFillSuratPernyataan($sheet, $row, $getChildKegiatan, $getParentName, $deep)
    {
        $column = 2;
        if ($deep > 1 && strlen($getParentName) > 0 && strlen($getChildKegiatan['name']) <= 100) {
            $getIndexData = substr($getParentName, 0, 2);
            $getKegiatanName = substr($getParentName, 2, strlen($getParentName)).": \n".$getChildKegiatan['name'];
        }
        else {
            $getName = $getChildKegiatan['name'];
            $getIndexData = substr($getName, 0, 2);
            $getKegiatanName = substr($getName, 2, strlen($getName));
        }

        $getAk = $getChildKegiatan['ak'];

        $sheet->setCellValueByColumnAndRow($column++, $row, $getIndexData);
        $sheet->setCellValueByColumnAndRow($column++, $row, $getKegiatanName);
        $sheet->getStyleByColumnAndRow($column-1,$row)->applyFromArray(array(

            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ),
        ));

        foreach ($getChildKegiatan['data'] as $listData) {
            $getNewAk = $listData['kredit'];
            if ($getAk != $getNewAk) {
                $setPercentage = '80%';
                $setPercentage2 = 'X';
            }
            else {
                $setPercentage = '';
                $setPercentage2 = '';
            }

            $setPercentage3 = '=';
            $sheet->setCellValueByColumnAndRow($column++, $row, date('d-M-Y', strtotime($listData['tanggal'])));
            $sheet->setCellValueByColumnAndRow($column++, $row, "1");
            $sheet->setCellValueByColumnAndRow($column++, $row, $getAk);
            $sheet->setCellValueByColumnAndRow($column++, $row, $setPercentage);
            $sheet->setCellValueByColumnAndRow($column++, $row, $setPercentage2);
            $sheet->setCellValueByColumnAndRow($column++, $row, $setPercentage3);
            $sheet->setCellValueByColumnAndRow($column++, $row, $getNewAk);

        }
    }

    public function generateDupak($dupakId)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);

        $getDupak = Dupak::where('id', $dupakId)->first();
        $datenow = date("Y-m-d");
        if($getDupak) {
            $getInfoSuratPernyataan = json_decode($getDupak->info_dupak, TRUE);
            $getPerancangName = $getInfoSuratPernyataan['perancang_name'] ?? '';
            $getPerancangNIP = $getInfoSuratPernyataan['perancang_nip'] ?? '';
            $getPerancangPangkat = $getInfoSuratPernyataan['perancang_pangkat'] ?? '';
            $getPerancangJabatan = $getInfoSuratPernyataan['perancang_jabatan'] ?? '';
            $getPerancangUnitKerja = $getInfoSuratPernyataan['perancang_unit_kerja'] ?? '';
            $getAtasanName = $getInfoSuratPernyataan['atasan_name'] ?? '';
            $getAtasanNIP = $getInfoSuratPernyataan['atasan_nip'] ?? '';
            $getAtasanPangkat = $getInfoSuratPernyataan['atasan_pangkat'] ?? '';
            $getAtasanJabatan = $getInfoSuratPernyataan['atasan_jabatan'] ?? '';
            $getAtasanUnitKerja = $getInfoSuratPernyataan['atasan_unit_kerja'] ?? '';

            $getData = $this->getDupakUser($getDupak);
            $getDataKegiatan = $getData['data'] ?? [];
            $getTotalTop = $getData['total_top'][0] ?? 0;
            $getListTopKegiatan = $getData['top_kegiatan'];
            $getTopKegiatan = $getListTopKegiatan[$getTotalTop] ?? false;

            $getTitleSuratPernyataan = $getTopKegiatan && isset($getTopKegiatan['name']) ? $getTopKegiatan['name'] : '';

            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()->setCreator('Peraturan Perundang-undangan')
                ->setLastModifiedBy('PAK')
                ->setTitle('Laporan DUPAK')
                ->setSubject('Laporan DUPAK')
                ->setDescription('Laporan DUPAK');

            $sheet = $spreadsheet->getActiveSheet();

            $totalColumn = 13;

            $row = 2;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'KEMENTERIAN HUKUM DAN HAK ASASI MANUSIA REPUBLIK INDONESIA
            DIREKTORAT JENDERAL ADMINISTRASI HUKUM UMUM');
            $sheet->mergeCellsByColumnAndRow(1,$row, $totalColumn, $row);
            $sheet->getStyleByColumnAndRow(1,$row, $totalColumn, $row)->applyFromArray(array(
                'font' => array(
                    'bold' => true
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ),

            ));

            $row += 1;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row,'DIREKTORAT JENDERAL ADMINISTRASI HUKUM UMUM');
            $sheet->mergeCellsByColumnAndRow(1,$row, $totalColumn, $row);
            $sheet->getStyleByColumnAndRow(1,$row, $totalColumn, $row)->applyFromArray(array(
                'font' => array(
                    'bold' => true
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ),
            ));

            $row += 1;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Jl. HR. Rasuna Said Kav. 6-7 Kuningan, Jakarta Selatan');
            $sheet->mergeCellsByColumnAndRow(1,$row, $totalColumn, $row);
            $sheet->getStyleByColumnAndRow(1,$row, $totalColumn, $row)->applyFromArray(array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                )
            ));

            $row += 1;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Telp. (021) 5221618, Fax. (021) 5265480');
            $sheet->mergeCellsByColumnAndRow(1,$row, $totalColumn, $row);
            $sheet->getStyleByColumnAndRow(1,$row, $totalColumn, $row)->applyFromArray(array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                )
            ));

            $row += 1;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'laman: www.ahu.go.id, Surel:humas@ahu.go.id');
            $sheet->mergeCellsByColumnAndRow(1,$row, $totalColumn, $row);
            $sheet->getStyleByColumnAndRow(1,$row, $totalColumn, $row)->applyFromArray(array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ),
                'borders' => array(
                    'bottom' => array(
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => array('argb' => '00000000'),
                    )
                )
            ));


            $row += 2;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'SURAT PERNYATAAN');
            $sheet->mergeCellsByColumnAndRow(1,$row, $totalColumn, $row);
            $sheet->getStyleByColumnAndRow(1,$row, $totalColumn, $row)->applyFromArray(array(
                'font' => array(
                    'bold' => true
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                )
            ));


            $row += 1;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'MELAKUKAN '.$getTitleSuratPernyataan);
            $sheet->mergeCellsByColumnAndRow(1,$row, $totalColumn, $row);
            $sheet->getStyleByColumnAndRow(1,$row, $totalColumn, $row)->applyFromArray(array(
                'font' => array(
                    'bold' => true
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ),
            ));


            $row += 2;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Yang bertanda tangan dibawah ini');
            $sheet->mergeCellsByColumnAndRow(1,$row, $totalColumn, $row);

            $row += 2;
            $column = 3;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Nama');
            $sheet->setCellValueByColumnAndRow($column++, $row, ': '.$getAtasanName);

            $row += 1;
            $column = 3;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Nip');
            $sheet->setCellValueByColumnAndRow($column++, $row, ': '.$getAtasanNIP);

            $row += 1;
            $column = 3;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Pangkat/golongan ruang/TMT');
            $sheet->setCellValueByColumnAndRow($column++, $row, ': '.$getAtasanPangkat);

            $row += 1;
            $column = 3;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Jabatan');
            $sheet->setCellValueByColumnAndRow($column++, $row, ': '.$getAtasanJabatan);

            $row += 1;
            $column = 3;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Unit Kerja');
            $sheet->setCellValueByColumnAndRow($column++, $row, ': '.$getAtasanUnitKerja);

            //Atasan
            $row += 2;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Menyatakan bahwa');
            $sheet->mergeCellsByColumnAndRow(1,$row, $totalColumn, $row);

            $row += 2;
            $column = 3;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Nama');
            $sheet->setCellValueByColumnAndRow($column++, $row, ': '.$getPerancangName);

            $row += 1;
            $column = 3;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Nip');
            $sheet->setCellValueByColumnAndRow($column++, $row, ': '.$getPerancangNIP);

            $row += 1;
            $column = 3;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Pangkat/golongan ruang/TMT');
            $sheet->setCellValueByColumnAndRow($column++, $row, ': '.$getPerancangPangkat);

            $row += 1;
            $column = 3;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Jabatan');
            $sheet->setCellValueByColumnAndRow($column++, $row, ': '.$getPerancangJabatan);

            $row += 1;
            $column = 3;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Unit Kerja');
            $sheet->setCellValueByColumnAndRow($column++, $row, ': '.$getPerancangUnitKerja);

            $row += 2;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Telah melakukan kegiatan penyusunan Peraturan Perundang-undangan sebagai berikut :');
            $sheet->mergeCellsByColumnAndRow(1,$row, $totalColumn, $row);

            $row += 2;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'No');
            $column += 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Uraian Kegiatan Penyusunan Peraturan Perundang-undangan');
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Tanggal');
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Jumlah Volume Kegiatan');
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Jumlah AK');
            $column += 4;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Keterangan/ Bukti Fisik');

            $row += 1;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, '1');
            $column += 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, '2');
            $sheet->setCellValueByColumnAndRow($column++, $row, '3');
            $sheet->setCellValueByColumnAndRow($column++, $row, '4');
            $sheet->setCellValueByColumnAndRow($column++, $row, '5');
            $column += 4;
            $sheet->setCellValueByColumnAndRow($column++, $row, '6');

            //ISI
            foreach ($getDataKegiatan as $permenId => $listTopKegiatan) {
                foreach ($listTopKegiatan as $topId => $listJudul) {
                    $indexingJudul = 1;
                    foreach ($listJudul as $nameJudul => $listKegiatan) {

                        $row += 1;
                        $column = 1;
                        $sheet->setCellValueByColumnAndRow($column++, $row, $indexingJudul.'. '.$nameJudul);

                        $indexingKegiatan = 1;

                        if ($listKegiatan[0]['have_child'] == 1) {
                            foreach ($listKegiatan[0]['child'] as $list) {
                                $getName = $list['name'];
                                $row += 1;
                                $column = 3;
                                $sheet->setCellValueByColumnAndRow($column++, $row, $getName);

                                $startRow = $row + 1;
                                if ($list['have_child'] == 1) {
                                    $row = $this->generateChildSuratPernyataan($sheet, $row, $list['child'], $getName, 1);
                                }

                                $endRow = $row;
                                $sheet->setCellValueByColumnAndRow(1, $startRow, $indexingJudul.",".$indexingKegiatan);
                                $sheet->mergeCellsByColumnAndRow(1, $startRow, 1, $endRow);

                                $indexingKegiatan++;
                            }
                            $indexingJudul++;
                        }

                    }
                }
            }

            $row += 3;
            $column = 1;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Demikian pernyataan ini dibuat untuk dapat dipergunakan sebagaimana mestinya');
            $sheet->mergeCellsByColumnAndRow(1,$row, $totalColumn, $row);

            $row += 3;
            $column = 8;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Jakarta, '.$datenow);
            $sheet->getStyleByColumnAndRow($column++, $row)->applyFromArray(array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                )
            ));

            $row += 2;
            $column = 8;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Atasan Langsung');
            $sheet->getStyleByColumnAndRow($column++, $row)->applyFromArray(array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                )
            ));

            $row += 1;
            $column = 8;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Kepala Bagian Program dan Pelaporan');
            $sheet->getStyleByColumnAndRow($column++, $row)->applyFromArray(array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                )
            ));

            $row += 1;
            $column = 8;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'Direktorat Jenderal Administrasi Hukum Umum');
            $sheet->getStyleByColumnAndRow($column++, $row)->applyFromArray(array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                )
            ));

            $row += 4;
            $column = 8;
            $sheet->setCellValueByColumnAndRow($column++, $row, $getAtasanName);
            $sheet->getStyleByColumnAndRow($column++, $row)->applyFromArray(array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                )
            ));

            $row += 1;
            $column = 8;
            $sheet->setCellValueByColumnAndRow($column++, $row, 'NIP.'.$getAtasanNIP);
            $sheet->getStyleByColumnAndRow($column++, $row)->applyFromArray(array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                )
            ));

            // Redirect output to a client’s web browser (Xls)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="surat_pernyataan_' . strtotime("now") . '.xlsx"');

            // Redirect output to a client’s web browser (PDF)
//            header('Content-Type: application/pdf');
//            header('Content-Disposition: attachment;filename="surat_pernyataan_' . strtotime("now") . '.pdf"');

            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
//            $writer = IOFactory::createWriter($spreadsheet, 'Pdf');
            $writer->save('php://output');
            exit;

        }
    }

}
