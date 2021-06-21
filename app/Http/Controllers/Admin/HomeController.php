<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Models\Bapak;
use App\Codes\Models\Dupak;
use App\Codes\Models\Gender;
use App\Codes\Models\Golongan;
use App\Codes\Models\JabatanPerancang;
use App\Codes\Models\JenjangPerancang;
use App\Codes\Models\Kegiatan;
use App\Codes\Models\MsKegiatan;
use App\Codes\Models\Permen;
use App\Codes\Models\Staffs;
use App\Codes\Models\SuratPernyataan;
use App\Codes\Models\UnitKerja;
use App\Codes\Models\Users;
use App\Http\Controllers\Controller;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Codes\Models\UserRegister;


class HomeController extends Controller
{
    protected $request;
    protected $data = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function dataPerancang()
    {
        $data = $this->data;

        $listAll = [
            2, 35, 36, 37, 39, 136, 41, 42, 40, 43, 44,
            4, 18, 5, 12, 1, 29, 13, 3, 16, 17, 22, 21, 23, 24, 137, 11, 8, 14, 31, 32, 19, 20, 33, 34, 9, 26, 25, 27, 28, 30, 7, 10, 6, 15,
            45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 60, 61, 62, 63, 124, 131, 73, 69, 65, 75, 66, 67, 68, 120, 121, 122, 123, 127, 129,
            91, 92, 90, 86, 87, 79, 81, 78, 79, 81, 78, 82, 83, 132, 85, 88, 89, 93, 94, 95, 96, 97, 98, 100, 101, 102, 103, 104, 105, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119,
            71,
            133
        ];
        $getData = Staffs::whereIn('unit_kerja_id', $listAll)->where('perancang', 1)->where('jenjang_perancang_id', '!=', 6);
        $result = $getData->get();

        $listUnitKerjaListing = [
            'PUSAT' => [2, 35, 36, 37, 39, 136, 41, 42, 40, 43, 44],
            'KANTOR WILAYAH' => [4, 18, 5, 12, 1, 29, 13, 3, 16, 17, 22, 21, 23, 24, 137, 11, 8, 14, 31, 32, 19, 20, 33, 34, 9, 26, 25, 27, 28, 30, 7, 10, 6, 15],
            'KEMENTERIAN' => [45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 60, 61, 62, 63, 124, 131, 73, 69, 65, 75, 66, 67, 68, 120, 121, 122, 123, 127, 129],
            'LEMBAGA' => [91, 92, 90, 86, 87, 79, 81, 78, 79, 81, 78, 82, 83, 132, 85, 88, 89, 93, 94, 95, 96, 97, 98, 100, 101, 102, 103, 104, 105, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119],
            'DPR' => [71],
            'DPR2' => [133]
        ];
        $listUnitKerjaListingTotal = [];

        $listTree = [
            'KEMENTERIAN HUKUM DAN HAM (TOTAL KESELURUHAN DATA KUMHAM)' => [
                'list' => [
                    'PUSAT' => [
                        'data' => $listUnitKerjaListing['PUSAT'],
                        'additional' => [

                        ]
                    ],
                    'KANTOR WILAYAH' => [
                        'data' => $listUnitKerjaListing['KANTOR WILAYAH'],
                        'additional' => [

                        ]
                    ]
                ],
                'additional' => [

                ]
            ],
            'KEMENTERIAN/LEMBAGA (TOTAL KESELURUHAN DATA K/L)' => [
                'list' => [
                    'KEMENTERIAN' => [
                        'data' => $listUnitKerjaListing['KEMENTERIAN'],
                        'additional' => [

                        ]
                    ],
                    'LEMBAGA' => [
                        'data' => $listUnitKerjaListing['LEMBAGA'],
                        'additional' => [

                        ]
                    ]
                ],
                'additional' => [

                ]
            ],
            'DPR (Sekertariat Jenderal dan Badan Keahlian DPR RI)' => [
                'data' => $listUnitKerjaListing['DPR'],
                'additional' => [

                ]
            ],
            'DPD (DPD RI)' => [
                'data' => $listUnitKerjaListing['DPR2'],
                'additional' => [

                ]
            ],
            'PEMDA' => [

            ]

        ];

        $listData = [];
        $listDataUnitKerja = [];
        $totalFemale = 0;
        $totalMale = 0;
        $listJenjang = [];
        $listJenjangGolongan = [];
        $totalMerge = [
            'total' => 0
        ];

        if ($result) {
            $get_gender = [0 => 'Semua'];
            $temp = Gender::where('status', 1)->pluck('name', 'id');
            if ($temp) {
                foreach ($temp as $id => $name) {
                    $get_gender[$id] = $name;
                }
            }
            $getGolongan = [0 => '-'];
            $temp = Golongan::where('status', 1)->pluck('name', 'id');
            if ($temp) {
                foreach ($temp as $id => $name) {
                    $getGolongan[$id] = $name;
                }
            }
            $getJenjangPerancang = [0 => 'Tidak Ditemukan'];
            $temp = JenjangPerancang::where('status', 1)->pluck('name', 'id');
            if ($temp) {
                foreach ($temp as $id => $name) {
                    $getJenjangPerancang[$id] = $name;
                }
            }
            $getUnitKerja = [0 => 'Tidak Ditemukan'];
            $temp = UnitKerja::where('status', 1)->pluck('name', 'id');
            if ($temp) {
                foreach ($temp as $id => $name) {
                    $getUnitKerja[$id] = $name;
                }
            }

            foreach ($result as $list) {
                if($list->gender_id == 2) {
                    $listData[$list->unit_kerja_id]['total_women'] = isset($listData[$list->unit_kerja_id]['total_women']) ? $listData[$list->unit_kerja_id]['total_women'] : 0;
                    $listData[$list->unit_kerja_id]['total_women'] += 1;
                    $totalFemale += 1;
                }
                else {
                    $listData[$list->unit_kerja_id]['total_man'] = isset($listData[$list->unit_kerja_id]['total_man']) ? $listData[$list->unit_kerja_id]['total_man'] : 0;
                    $listData[$list->unit_kerja_id]['total_man'] += 1;
                    $totalMale += 1;
                }

                $listData[$list->unit_kerja_id][$list->jenjang_perancang_id]['total'] = isset($listData[$list->unit_kerja_id][$list->jenjang_perancang_id]['total']) ? $listData[$list->unit_kerja_id][$list->jenjang_perancang_id]['total'] : 0;
                $listData[$list->unit_kerja_id][$list->jenjang_perancang_id]['total'] += 1;
                if (isset($getGolongan[$list->golongan_id])) {
                    $listData[$list->unit_kerja_id][$list->jenjang_perancang_id]['golongan'][$list->golongan_id] = isset($listData[$list->unit_kerja_id][$list->jenjang_perancang_id]['golongan'][$list->golongan_id]) ? $listData[$list->unit_kerja_id][$list->jenjang_perancang_id]['golongan'][$list->golongan_id] : 0;
                    $listData[$list->unit_kerja_id][$list->jenjang_perancang_id]['golongan'][$list->golongan_id] += 1;
                    $listJenjangGolongan[$list->jenjang_Lihatperancang_id][$list->golongan_id] = 1;
                }
                else {
                    $listData[$list->unit_kerja_id][$list->jenjang_perancang_id]['golongan'][0] = isset($listData[$list->unit_kerja_id][$list->jenjang_perancang_id]['golongan'][0]) ? $listData[$list->unit_kerja_id][$list->jenjang_perancang_id]['golongan'][0] : 0;
                    $listData[$list->unit_kerja_id][$list->jenjang_perancang_id]['golongan'][0] += 1;
                    $listJenjangGolongan[$list->jenjang_perancang_id][0] = 1;
                }

                $listJenjang[$list->jenjang_perancang_id] = 1;
                $listDataUnitKerja[] = $list->unit_kerja_id;
            }

            $temp = [];
            foreach ($listJenjang as $id => $value) {
                $temp[] = $id;
            }
            $listJenjang = $temp;

            $temp = [];
            foreach ($listJenjangGolongan as $jenjang_perancang_id => $get_list_golongan) {
                $temp2 = [];
                foreach ($get_list_golongan as $golongan_id => $value) {
                    $temp2[] = $golongan_id;
                    $totalMerge['total'] += 1;
                    $totalMerge['total_'.$jenjang_perancang_id] = isset($totalMerge['total_'.$jenjang_perancang_id]) ? $totalMerge['total_'.$jenjang_perancang_id] : 0;
                    $totalMerge['total_'.$jenjang_perancang_id] += 1;
                }
                sort($temp2);
                $temp[$jenjang_perancang_id] = $temp2;
            }
            $listJenjangGolongan = $temp;

            if (count($listJenjang) > 0) {
                sort($listJenjang);
            }
            if (count($listDataUnitKerja) > 0) {
                sort($listDataUnitKerja);
                $listDataUnitKerja = array_unique($listDataUnitKerja);
            }

            $kumham = [];
            $lembaga = [];

            foreach ($listUnitKerjaListing as $list_unit => $list_ids) {
                $list_additional = [];
                foreach ($list_ids as $id) {
                    if (isset($listData[$id])) {
                        $temp = $listData[$id];
                        $totalMale = isset($temp['total_man']) ? $temp['total_man'] : 0;
                        $totalFemale = isset($temp['total_women']) ? $temp['total_women'] : 0;

                        unset($temp['total_man']);
                        unset($temp['total_women']);

                        foreach ($temp as $key_golongan => $list) {
                            $list_additional[$key_golongan] = isset($list_additional[$key_golongan]) ? $list_additional[$key_golongan] : 0;
                            $total = isset($list['total']) ? $list['total'] : 0;
                            $list_additional[$key_golongan] += $total;

                            if (in_array($list_unit, ['PUSAT', 'KANTOR WILAYAH'])) {
                                $kumham[$key_golongan] = isset($kumham[$key_golongan]) ? $kumham[$key_golongan] : 0;
                                $kumham[$key_golongan] += $total;
                            }
                            else if (in_array($list_unit, ['KEMENTERIAN', 'LEMBAGA'])) {
                                $lembaga[$key_golongan] = isset($lembaga[$key_golongan]) ? $lembaga[$key_golongan] : 0;
                                $lembaga[$key_golongan] += $total;
                            }
                        }

                        $list_additional['total_man'] = isset($list_additional['total_man']) ? $list_additional['total_man'] : 0;
                        $list_additional['total_women'] = isset($list_additional['total_women']) ? $list_additional['total_women'] : 0;

                        $list_additional['total_man'] += $totalMale;
                        $list_additional['total_women'] += $totalFemale;

                        if (in_array($list_unit, ['PUSAT', 'KANTOR WILAYAH'])) {
                            $kumham['total_man'] = isset($kumham['total_man']) ? $kumham['total_man'] : 0;
                            $kumham['total_women'] = isset($kumham['total_women']) ? $kumham['total_women'] : 0;

                            $kumham['total_man'] += $totalMale;
                            $kumham['total_women'] += $totalFemale;
                        }
                        else if (in_array($list_unit, ['KEMENTERIAN', 'LEMBAGA'])) {
                            $lembaga['total_man'] = isset($lembaga['total_man']) ? $lembaga['total_man'] : 0;
                            $lembaga['total_women'] = isset($lembaga['total_women']) ? $lembaga['total_women'] : 0;

                            $lembaga['total_man'] += $totalMale;
                            $lembaga['total_women'] += $totalFemale;
                        }
                    }
                }
                $listUnitKerjaListing_total[$list_unit] = $list_additional;
            }

            $pusat = $listUnitKerjaListing_total['PUSAT'];
            $kantor_wilayah = $listUnitKerjaListing_total['KANTOR WILAYAH'];
            $dpr = $listUnitKerjaListing_total['DPR'];
            $dpr2 = $listUnitKerjaListing_total['DPR2'];

            $listTree['KEMENTERIAN HUKUM DAN HAM (TOTAL KESELURUHAN DATA KUMHAM)']['list']['PUSAT']['additional'] = $pusat;
            $listTree['KEMENTERIAN HUKUM DAN HAM (TOTAL KESELURUHAN DATA KUMHAM)']['list']['KANTOR WILAYAH']['additional'] = $kantor_wilayah;
            $listTree['KEMENTERIAN/LEMBAGA (TOTAL KESELURUHAN DATA K/L)']['list']['KEMENTERIAN']['additional'] = $listUnitKerjaListing_total['KEMENTERIAN'];
            $listTree['KEMENTERIAN/LEMBAGA (TOTAL KESELURUHAN DATA K/L)']['list']['LEMBAGA']['additional'] = $listUnitKerjaListing_total['LEMBAGA'];


            $listTree['KEMENTERIAN HUKUM DAN HAM (TOTAL KESELURUHAN DATA KUMHAM)']['additional'] = $kumham;
            $listTree['KEMENTERIAN/LEMBAGA (TOTAL KESELURUHAN DATA K/L)']['additional'] = $lembaga;
            $listTree['DPR (Sekertariat Jenderal dan Badan Keahlian DPR RI)']['additional'] = $dpr;
            $listTree['DPD (DPD RI)']['additional'] = $dpr2;

            $data['list_data'] = $listData;
            $data['list_jenjang'] = $listJenjang;
            $data['list_jenjang_golongan'] = $listJenjangGolongan;
            $data['list_data_unit_kerja'] = $listDataUnitKerja;
            $data['data_unit_kerja'] = $getUnitKerja;
            $data['data_jenjang_perancang'] = $getJenjangPerancang;
            $data['data_golongan'] = $getGolongan;
            $data['total_merge'] = $totalMerge;
            $data['list_tree'] = $listTree;

        }

        return view(env('ADMIN_TEMPLATE').'.page.data_perancang.index', $data);
    }

    public function checkData()
    {
        if (session()->get(env('APP_NAME').'admin_id')) {
            $adminId = session()->get(env('APP_NAME').'admin_id');
            $getUser = Users::where('id', $adminId)->where('status', 1)->first();
            if ($getUser) {
                return redirect()->route('admin.portal');
            }
        }
        return redirect()->route('admin.login');
    }

    public function fixingData()
    {
        Staffs::where('angka_kredit', '=', NULL)->orWhere('angka_kredit', '=', '-')->orWhere('angka_kredit', '=', 'Isi Angka Kredit')->update([
            'angka_kredit' => 0
        ]);

        $getStaffs = Staffs::where('angka_kredit', '!=', NULL)->where('angka_kredit', '!=', '0')->get();
        $total = 0;
        foreach ($getStaffs as $staff) {
            $staff->angka_kredit = str_replace(',', '.', $staff->angka_kredit);
            $staff->angka_kredit = str_replace(' ', '', $staff->angka_kredit);
            $staff->save();
            $total++;
        }
        return response()->json($total);
    }

    public function checkButirKegiatan()
    {
        $path = './uploads/ButirKegiatan.xlsx';
        $reader = ReaderEntityFactory::createReaderFromFile($path);
        $reader->open($path);

        $getData = [];
        $indexing = 1;
        $parentId1 = 0;
        $parentId2 = 0;
        $parentId3 = 0;
        $parentId4 = 0;
        foreach ($reader->getSheetIterator() as $sheetIndex => $sheet) {
            $getData[$indexing]['name'] = $sheet->getName();
            $getData[$indexing]['parent_id'] = 0;
            $getData[$indexing]['ak'] = '';
            $getData[$indexing]['satuan'] = '';
            $getData[$indexing]['pelaksana'] = '';
            $getData[$indexing]['id'] = $indexing;
            $parentId = $indexing;
            $indexing++;

            foreach ($sheet->getRowIterator() as $index => $row) {
                if ($index <= 1)
                    continue;

                $cells = $row->getCells();
                $getCellIndex = 0;
                $temp = [];
                foreach ($cells as $cellIndex => $val) {
                    if ($cellIndex >= 0 && $cellIndex <= 4) {
                        if (strlen($val->getvalue()) > 0) {
                            $temp['name'] = $val->getvalue();
                            $temp['path'] = $cellIndex;
                            $getCellIndex = $cellIndex;
                        }
                    }
                    else if ($cellIndex == 5) {
                        $temp['ak'] = $val->getvalue();
                    }
                    else if ($cellIndex == 6) {
                        $temp['satuan'] = $val->getvalue();
                    }
                    else if ($cellIndex == 7) {
                        $temp['pelaksana'] = $val->getvalue();
                    }
                }

                $temp['id'] = $indexing;
                switch ($getCellIndex) {
                    case 0 :
                        $temp['parent_id'] = $parentId;
                        $parentId1 = $indexing;
                        break;

                    case 1 :
                        $temp['parent_id'] = $parentId1;
                        $parentId2 = $indexing;
                        break;

                    case 2 :
                        $temp['parent_id'] = $parentId2;
                        $parentId3 = $indexing;
                        break;

                    case 3 :
                        $temp['parent_id'] = $parentId3;
                        $parentId4 = $indexing;
                        break;

                    case 4 :
                        $temp['parent_id'] = $parentId4;
                        break;
                }

                $getData[$indexing] = $temp;

                $indexing++;

            }

        }

        $getListJenjangPerancang = JenjangPerancang::pluck('id', 'name')->toArray();
        MsKegiatan::whereRaw('1=1')->update([
            'status' => 0,
        ]);

        $saveData = [];
        foreach ($getData as $index => $list) {
            $getParent = $list['parent_id'];
            $getJenjangPerancang = $list['pelaksana'];
            $getJenjangPerancangData = null;
            if (strlen($getJenjangPerancang) > 0) {
                $getJenjangPerancangData = isset($getListJenjangPerancang[$getJenjangPerancang]) ? $getListJenjangPerancang[$getJenjangPerancang] : null;
            }

            $getParentData = false;
            if ($getParent > 0) {
                $getParentData = isset($saveData[$getParent]) ? $saveData[$getParent] : false;
            }

            $getKegiatan = MsKegiatan::create([
                'parent_id' => $getParentData ? $getParentData->id : 0,
                'name' => $list['name'],
                'ak' => strlen($list['ak']) > 0 ? str_replace(',', '.', $list['ak']) : 0,
                'satuan' => strlen($list['satuan']) > 0 ? $list['satuan'] : null,
                'jenjang_perancang_id' => $getJenjangPerancangData
            ]);

            $saveData[$index] = $getKegiatan;

        }

    }


    public function register()
    {
        $data = $this->data;

        $data['listData']['unit_kerja_id'] = UnitKerja::pluck('name', 'id')->toArray();

        return view(env('ADMIN_TEMPLATE').'.page.register', $data);
    }


    public function postRegister()
    {
        $data = $this->data;

        $this->validate($this->request, [
            'name' => 'required',
            'telp' => 'required|numeric|regex:/^(08\d+)/|unique:user_register,telp',
            'email' => 'required|email|unique:user_register,email,',
            'fileDokumen' => 'required',
            'fileDokumenLampiran' => 'required',

        ], [
            'name.required' => 'Nama harus di isi',
            'telp.required' => 'Nomer HP harus diisi',
            'email.required' => 'Email harus diisi',
            'fileDokumen.required' => 'Surat permohonan tidak boleh kosong',
            'fileDokumenLampiran.required' => 'Dokumen Terlampir tidak boleh kosong',

        ]);

        $file = $this->request->file('fileDokumen');
        $dokumenTerlampir = $this->request->file('fileDokumenLampiran');


        $name = $this->request->get('name');
        $telp = $this->request->get('telp');
        $email = $this->request->get('email');
        $unit_kerja_id = $this->request->get('unit_kerja_id');

        $extensionFile = $file->getClientOriginalExtension();
        $extensionDokumenTerlampir = $dokumenTerlampir->getClientOriginalExtension();

        $time = date("Y-m-d" , time());

        $nama_file = create_slugs($name) . '-' .'file'. $time . '.' . $extensionFile;
        $namaDokumenTerlampir = create_slugs($name) . '-' .'dokumen_terlampir' . $time . '.' . $extensionDokumenTerlampir;


//        $ukuran_file = $file->getSize();
        $destinationPath =  './uploads/register';

        $movefile =  $file->move($destinationPath, $nama_file);
        $moveDokumenTerlampir =  $dokumenTerlampir->move($destinationPath, $namaDokumenTerlampir);


        $userRegister = new UserRegister;
        $userRegister->name = $name;
        $userRegister->telp = $telp;
        $userRegister->email = $email;
        $userRegister->status = 0;
        $userRegister->file = $nama_file;
        $userRegister->unit_kerja_id = $unit_kerja_id;
        $userRegister->dokumen_lampiran = $namaDokumenTerlampir;
        $userRegister->save();

        session()->flash('message', __('Pendaftaran Berhasil, tunggu sebentar admin akan memverfifikasi akun anda'));
        session()->flash('message_alert', 2);
        return back();

    }

    public function portal()
    {
        $data = $this->data;

        return view(env('ADMIN_TEMPLATE').'.page.portal', $data);
    }

    public function truncate()
    {
        $kegiatanPerancang = Kegiatan::truncate();
        $suratPernyataan = SuratPernyataan::truncate();
        $dupak = Dupak::truncate();
        $bapak = Bapak::truncate();
        $permen =  Staffs::truncate();


        return back();
    }


}
