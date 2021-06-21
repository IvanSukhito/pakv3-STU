<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_CrudController;
use App\Codes\Models\Dupak;
use App\Codes\Models\Kegiatan;
use App\Codes\Models\MsKegiatan;
use App\Codes\Models\Pak;
use App\Codes\Models\Staffs;
use App\Codes\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use PDF;

class BapakController extends _CrudController
{
    protected $passingCRUD;

    public function __construct(Request $request)
    {
        $passingData = [
            'id' => [
            ],
            'berita_acara' => [
                'lang' => 'Berita Acara',
                'type' => 'texteditor'
            ],
            'dupak_nomor' => [
                'custom' => ', name:"B.nomor"',
                'lang' => 'Nomor Dupak',
            ],
            'action' => [
                'lang' => 'Aksi',
            ]
        ];

        $this->passingCRUD = generatePassingData([
            'ketua_id' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'Ketua'
            ],
            'wakil_ketua_id' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'Wakil Ketua'
            ],
            'sekretariat_id' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'Sekretariat'
            ],
            'anggota' => [
                'validate' => [
                    'create' => 'required|array',
                    'edit' => 'required|array'
                ],
                'lang' => 'Anggota'
            ],
            'anggota.*' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ]
            ],
            'berita_acara' => [
                'lang' => 'Berita Acara',
                'type' => 'texteditor'
            ],
            'dupak_id' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'Dupak'
            ],
        ]);

        parent::__construct(
            $request, 'general.bapak', 'bapak', 'Bapak', 'bapak',
            $passingData
        );

        $this->listView['index'] = env('ADMIN_TEMPLATE').'.page.bapak.list';
        $this->listView['create'] = env('ADMIN_TEMPLATE').'.page.bapak.forms';
        $this->listView['edit'] = env('ADMIN_TEMPLATE').'.page.bapak.forms';
        $this->listView['show'] = env('ADMIN_TEMPLATE').'.page.bapak.forms';
        $this->listView['dataTable'] = env('ADMIN_TEMPLATE').'.page.bapak.list_button';

        $this->data['listSet']['approved'] = get_list_status_pak();

    }

    public function verification ()
    {
        $this->callPermission();

        $data = $this->data;

        $data['passing'] = collectPassingData($this->passingData);
        $data['setFlag'] = 1;

        return view($this->listView['index'], $data);
    }

    public function dataTable()
    {
        $this->callPermission();

        $userId = session()->get(env('APP_NAME') . 'admin_id');
        $getFlag = intval($this->request->get('flag'));

        $dataTables = new DataTables();

        $builder = $this->model::query()->selectRaw("bapak.id, bapak.berita_acara, bapak.dupak_id, bapak.pdf, bapak.pdf_url, bapak.pak_pdf, bapak.pak_pdf_url, B.nomor AS dupak_nomor");


    //    $builder = $this->model::query()->selectRaw("dupak.id, dupak.status_upload, dupak.send_status, dupak.nomor, dupak.approved, GROUP_CONCAT( C.nomor SEPARATOR ', ' ) AS surat_pernyataan, dupak.pdf, dupak.pdf_url");


        $builder = $builder->leftJoin('dupak AS B', 'B.id', '=', 'bapak.dupak_id');

        $dataTables = $dataTables->eloquent($builder)
            ->addColumn('action', function ($query) {
                return view($this->listView['dataTable'], [
                    'query' => $query,
                    'thisRoute' => $this->route,
                    'permission' => $this->permission,
                    'masterId' => $this->masterId
                ]);
            });

        $listRaw = [];
        $listRaw[] = 'action';
        foreach (collectPassingData($this->passingData) as $fieldName => $list) {
            if (in_array($list['type'], ['select', 'select2'])) {
                $dataTables = $dataTables->editColumn($fieldName, function ($query) use ($fieldName) {
                    $getList = isset($this->data['listSet'][$fieldName]) ? $this->data['listSet'][$fieldName] : [];
                    return isset($getList[$query->$fieldName]) ? $getList[$query->$fieldName] : $query->$fieldName;
                });
            }
            else if (in_array($list['type'], ['money'])) {
                $dataTables = $dataTables->editColumn($fieldName, function ($query) use ($fieldName, $list, $listRaw) {
                    return number_format($query->$fieldName, 0);
                });
            }
            else if (in_array($list['type'], ['image'])) {
                $listRaw[] = $fieldName;
                $dataTables = $dataTables->editColumn($fieldName, function ($query) use ($fieldName, $list, $listRaw) {
                    return '<img src="' . asset($list['path'] . $query->$fieldName) . '" class="img-responsive max-image-preview"/>';
                });
            }
            else if (in_array($list['type'], ['image_preview'])) {
                $listRaw[] = $fieldName;
                $dataTables = $dataTables->editColumn($fieldName, function ($query) use ($fieldName, $list, $listRaw) {
                    return '<img src="' . $query->$fieldName . '" class="img-responsive max-image-preview"/>';
                });
            }
            else if (in_array($list['type'], ['code'])) {
                $listRaw[] = $fieldName;
                $dataTables = $dataTables->editColumn($fieldName, function ($query) use ($fieldName, $list, $listRaw) {
                    return '<pre>' . json_encode(json_decode($query->$fieldName, true), JSON_PRETTY_PRINT) . '"</pre>';
                });
            }
            else if (in_array($list['type'], ['texteditor'])) {
                $listRaw[] = $fieldName;
            }
        }

        return $dataTables
            ->rawColumns($listRaw)
            ->make(true);
    }

    public function create()
    {
        $this->callPermission();

        $userId = session()->get(env('APP_NAME') . 'admin_id');

        $getStaff = Staffs::where('user_id', $userId)->first();
        $getUser = Users::where('id', $userId)->first();

        $getListPenilai = Staffs::where('tim_penilai', 1)->pluck('name', 'user_id');

        $query = "SELECT A.id, A.nomor, B.name FROM dupak A JOIN user_staffs B ON A.user_id=B.user_id WHERE verifikasi_tim_penilai_id = $userId AND approved = 2 AND connect = 0";
        $getListDupak = DB::select(DB::raw($query));
        $temp = [];
        foreach ($getListDupak as $list) {
            $temp[$list->id] = $list->name . ' - ' . $list->nomor;
        }
        $getListDupak = $temp;

        $data = $this->data;

        $data['viewType'] = 'create';
        $data['formsTitle'] = __('general.title_create', ['field' => $data['thisLabel']]);
        $data['passing'] = collectPassingData($this->passingCRUD, $data['viewType']);
        $data['getStaff'] = $getStaff;
        $data['getUser'] = $getUser;
        $data['listSet']['ketua_id'] = $getListPenilai;
        $data['listSet']['wakil_ketua_id'] = $getListPenilai;
        $data['listSet']['sekretariat_id'] = $getListPenilai;
        $data['listSet']['anggota'] = $getListPenilai;
        $data['listSet']['dupak_id'] = $getListDupak;

        return view($this->listView[$data['viewType']], $data);
    }

    public function edit($id)
    {
        return redirect()->route('admin.' . $this->route . '.index');
    }

    public function show($id)
    {
        return redirect()->route('admin.' . $this->route . '.index');
    }

    public function destroy($id)
    {
        return redirect()->route('admin.' . $this->route . '.index');
    }

    public function store()
    {
        $this->callPermission();

        $viewType = 'create';

        $userId = session()->get(env('APP_NAME') . 'admin_id');

        $getListCollectData = collectPassingData($this->passingCRUD, $viewType);
        $validate = $this->setValidateData($getListCollectData, $viewType);
        if (count($validate) > 0)
        {
            $data = $this->validate($this->request, $validate);
        }
        else {
            $data = [];
            foreach ($getListCollectData as $key => $val) {
                $data[$key] = $this->request->get($key);
            }

        }

        $dupakId = $this->request->get('dupak_id');

        $dupak = Dupak::where('verifikasi_tim_penilai_id', $userId)->where('id', $dupakId)->where('approved', 2)->where('connect', 0)->first();

        if (!$dupak) {
            return redirect()->back()->withInput()->withErrors(
                [
                    'dupak_id' => 'Dupak tidak ditemukan'
                ]
            );
        }

        $listAnggotaIds = $data['anggota'];
        $listAnggotaIds = array_unique($listAnggotaIds);
        unset($data['anggota']);

        $data = $this->getCollectedData($getListCollectData, $viewType, $data);

        $data['user_id'] = $dupak->user_id;
        $data['owner_id'] = $userId;

        $bapak = $this->crud->store($data);
        $bapak->getAnggota()->sync($listAnggotaIds);

        $dupak->connect = 1;
        $dupak->save();

        $listUserIds = $listAnggotaIds;
        $listUserIds[] = $data['ketua_id'];
        $listUserIds[] = $data['wakil_ketua_id'];
        $listUserIds[] = $data['sekretariat_id'];

        $getListUsers = Users::whereIn('id', $listUserIds)->get();
        $temp = [];
        foreach ($getListUsers as $list) {
            $temp[$list->id] = $list;
        }
        $getListUsers = $temp;

        $getUser = Users::where('id', $dupak->user_id)->first();
        $getStaff = Staffs::where('user_id', $dupak->user_id)->first();
        $staffJabatanPerancang = $getStaff->getJabatanPerancang()->first();
        $staffJenjangPerancang = $getStaff->getJenjangPerancang()->first();
        $staffPendidikan = $getStaff->getPendidikan()->first();
        $staffGender = $getStaff->getGender()->first();
        $staffUnitKerja = $getStaff->getUnitKerja()->first();

        $oldAngkaKredit = $getStaff->angka_kredit;
        $getStaff->angka_kredit += $dupak->kredit_baru;
        $getStaff->save();

        $unsur_utama = 0;
        $unsur_penunjang = 0;

        $listAnggota = [];
        foreach ($getListUsers as $anggotaId => $list) {
            if ($bapak->ketua_id == $anggotaId) {
                $listAnggota[1][] = [
                    'Nama' => $list->name,
                    'NIP' => $list->username,
                    'Jabatan' => 'Ketua'
                ];
            }
            if ($bapak->wakil_ketua_id == $anggotaId) {
                $listAnggota[2][] = [
                    'Nama' => $list->name,
                    'NIP' => $list->username,
                    'Jabatan' => 'Wakil Ketua'
                ];
            }
            if ($bapak->sekretariat_id == $anggotaId) {
                $listAnggota[3][] = [
                    'Nama' => $list->name,
                    'NIP' => $list->username,
                    'Jabatan' => 'Sekretaris'
                ];
            }
            if (in_array($anggotaId, $listAnggotaIds)) {
                $listAnggota[4][] = [
                    'Nama' => $list->name,
                    'NIP' => $list->username,
                    'Jabatan' => 'Anggota'
                ];
            }
        }

        $getDataPak = DB::select(DB::raw("SELECT IFNULL(SUM(point1a1_baru), 0) AS point1a1_baru,
                                IFNULL(SUM(point1a2_baru), 0) AS point1a2_baru, IFNULL(SUM(point1b_baru), 0) AS point1b_baru,
                                IFNULL(SUM(point1c_baru), 0) AS point1c_baru, IFNULL(SUM(point2_baru), 0) AS point2_baru
                                FROM `pak` WHERE user_id = ".$dupak->user_id));

        $old_point1a1 = 0;
        $old_point1a2 = 0;
        $old_point1b = 0;
        $old_point1c = 0;
        $old_point2 = 0;
        $new_point1a1 = 0;
        $new_point1a2 = 0;
        $new_point1b = 0;
        $new_point1c = 0;
        $new_point2 = 0;
        foreach ($getDataPak as $list) {
            $old_point1a1 = $list->point1a1_baru;
            $old_point1a2 = $list->point1a2_baru;
            $old_point1b = $list->point1b_baru;
            $old_point1c = $list->point1c_baru;
            $old_point2 = $list->point2_baru;
        }

        $total_temp_jumlah_lama1 = $old_point1a1 + $old_point1a2 + $old_point1b + $old_point1c;
        $total_temp_jumlah_baru1 = 0;

        $total_temp_jumlah_lama2 = $old_point2;
        $total_temp_jumlah_baru2 = 0;

        $getKegiatan = Kegiatan::where('dupak_id', $dupak->id)->get();
        foreach ($getKegiatan as $list) {
            if(in_array($list->parent_id, [1,2,3,4])) {
                $unsur_utama += $list->kredit;
            }
            else {
                $unsur_penunjang += $list->kredit;
            }

            if(in_array($list->ms_kegiatan_id, [33,34,35])) {
                $new_point1a1 += $list->kredit;
                $total_temp_jumlah_baru1 += $list->kredit;
            }
            else if(in_array($list->parent_id, [1])) {
                $new_point1a2 += $list->kredit;
                $total_temp_jumlah_baru1 += $list->kredit;
            }
            else if(in_array($list->parent_id, [2, 3])) {
                $new_point1b += $list->kredit;
                $total_temp_jumlah_baru1 += $list->kredit;
            }
            else if(in_array($list->parent_id, [4])) {
                $new_point1c += $list->kredit;
                $total_temp_jumlah_baru1 += $list->kredit;
            }
            else {
                $new_point2 += $list->kredit;
                $total_temp_jumlah_baru2 += $list->kredit;
            }
        }

        $total_temp_jumlah_total1 = $total_temp_jumlah_lama1 + $total_temp_jumlah_baru1;
        $total_temp_jumlah_total2 = $total_temp_jumlah_lama2 + $total_temp_jumlah_baru2;

        $total = [
            0 => [ 'lama' => $old_point1a1, 'baru' => $new_point1a1, 'total' => $old_point1a1 + $new_point1a1 ],
            1 => [ 'lama' => $old_point1a2, 'baru' => $new_point1a2, 'total' => $old_point1a2 + $new_point1a2 ],
            2 => [ 'lama' => $old_point1b, 'baru' => $new_point1b, 'total' => $old_point1b + $new_point1b ],
            3 => [ 'lama' => $old_point1c, 'baru' => $new_point1c, 'total' => $old_point1c + $new_point1c ],
            4 => [ 'lama' => $total_temp_jumlah_lama1, 'baru' => $total_temp_jumlah_baru1, 'total' => $total_temp_jumlah_total1 ],
            5 => [ 'lama' => $old_point2, 'baru' => $new_point2, 'total' => $old_point2 + $new_point2 ],
            6 => [ 'lama' => $total_temp_jumlah_lama2, 'baru' => $total_temp_jumlah_baru2, 'total' => $total_temp_jumlah_total2 ],
            7 => [ 'lama' => $total_temp_jumlah_lama1 + $total_temp_jumlah_lama2, 'baru' => $total_temp_jumlah_baru1 + $total_temp_jumlah_baru2, 'total' => $total_temp_jumlah_total1 + $total_temp_jumlah_total2 ],
        ];

        $getReturnBapakPDF = $this->createBapakPDF($bapak, $dupak, $getUser, $getStaff, $staffJabatanPerancang, $staffUnitKerja, $listAnggota, $unsur_utama, $unsur_penunjang);
        $getReturnPakPDF = $this->createPakPDF($bapak, $dupak, $getUser, $getStaff, $staffJenjangPerancang, $staffJabatanPerancang, $staffUnitKerja, $staffPendidikan, $staffGender, $total);

        $bapak->pdf = $getReturnBapakPDF['name'];
        $bapak->pdf_url = $getReturnBapakPDF['location'];

        $bapak->pak_pdf = $getReturnPakPDF['name'];
        $bapak->pak_pdf_url = $getReturnPakPDF['location'];

        $bapak->dupak_id = $dupak->id;
        $bapak->save();

        $saveDataPak = [
            'user_id' => $dupak->user_id,
            'owner_id' => $userId,
            'dupak_id' => $dupak->id,
            'bapak_id' => $bapak->id,
            'point1a1_lama' => $total[0]['lama'],
            'point1a1_baru' => $total[0]['baru'],
            'point1a1_total' => $total[0]['total'],
            'point1a2_lama' => $total[1]['lama'],
            'point1a2_baru' => $total[1]['baru'],
            'point1a2_total' => $total[1]['total'],
            'point1b_lama' => $total[2]['lama'],
            'point1b_baru' => $total[2]['baru'],
            'point1b_total' => $total[2]['total'],
            'point1c_lama' => $total[3]['lama'],
            'point1c_baru' => $total[3]['baru'],
            'point1c_total' => $total[3]['total'],
            'total_point_1_lama' => $total[4]['lama'],
            'total_point_1_baru' => $total[4]['baru'],
            'total_point_1' => $total[4]['total'],
            'point2_lama' => $total[5]['lama'],
            'point2_baru' => $total[5]['baru'],
            'point2_total' => $total[5]['total'],
            'total_point_2_lama' => $total[6]['lama'],
            'total_point_2_baru' => $total[6]['baru'],
            'total_point_2' => $total[6]['total'],
            'total_point_lama' => $total[7]['lama'],
            'total_point_baru' => $total[7]['baru'],
            'total_point' => $total[7]['total'],
        ];

        $pak = new Pak();
        foreach ($saveDataPak as $key => $value) {
            $pak->$key = $value;
        }
        $pak->save();

        if($this->request->ajax()){
            return response()->json(['result' => 1, 'message' => __('general.success_add')]);
        }
        else {
            session()->flash('message', __('general.success_add'));
            session()->flash('message_alert', 2);
            return redirect()->route('admin.' . $this->route . '.index');
        }
    }

    public function update($id)
    {
        return redirect()->route('admin.' . $this->route . '.index');
    }

    protected function createBapakPDF($bapak, $dupak, $getUser, $getStaff, $staffJabatanPerancang, $staffUnitKerja, $listAnggota, $unsur_utama = 0, $unsur_penunjang = 0)
    {
        ini_set('memory_limit', '-1');

        $user_nip = $getUser->username;
        $user_nip = preg_replace("/[^A-Za-z0-9?!]/",'', $user_nip);
        $user_folder = 'user_'.$user_nip;
        $today_date = date('Y-m-d');
        $folder_name = $user_folder.'/bapak/'.$today_date.'/';
        $set_file_name = 'bapak_'.date('His').rand(10,99).'.pdf';
        $folder_path = './uploads/'.$folder_name;
        $destinationPath = './uploads/'.$folder_name.$set_file_name;
        $destinationLink = 'uploads/'.$folder_name.$set_file_name;

        if(!file_exists($folder_path)) {
            mkdir($folder_path, 755, true);
        }

        $data = [
            'bapak' => $bapak,
            'dupak' => $dupak,
            'staff' => $getStaff,
            'user' => $getUser,
            'staffJabatanPerancang' => $staffJabatanPerancang,
            'staffUnitKerja' => $staffUnitKerja,
            'listAnggota' => $listAnggota,
            'unsur_utama' => $unsur_utama,
            'unsur_penunjang' => $unsur_penunjang,
        ];

        $pdf = PDF::loadView('pdf.bapak', $data);

        $pdf->save($destinationPath);
        return [
            'name' => $set_file_name,
            'location' => $destinationLink,
        ];

    }

    protected function createPakPDF($bapak, $dupak, $getUser, $getStaff, $staffJenjangPerancang, $staffJabatanPerancang, $staffUnitKerja, $staffPendidikan, $staffGender, $total)
    {
        ini_set('memory_limit', '-1');

        $user_nip = $getUser->username;
        $user_nip = preg_replace("/[^A-Za-z0-9?!]/",'', $user_nip);
        $user_folder = 'user_'.$user_nip;
        $today_date = date('Y-m-d');
        $folder_name = $user_folder.'/bapak/'.$today_date.'/';
        $set_file_name = 'pak_'.date('His').rand(10,99).'.pdf';
        $folder_path = './uploads/'.$folder_name;
        $destinationPath = './uploads/'.$folder_name.$set_file_name;
        $destinationLink = 'uploads/'.$folder_name.$set_file_name;

        if(!file_exists($folder_path)) {
            mkdir($folder_path, 755, true);
        }

        $data = [
            'bapak' => $bapak,
            'dupak' => $dupak,
            'staff' => $getStaff,
            'user' => $getUser,
            'staffJenjangPerancang' => $staffJenjangPerancang,
            'staffJabatanPerancang' => $staffJabatanPerancang,
            'staffUnitKerja' => $staffUnitKerja,
            'staffPendidikan' => $staffPendidikan,
            'staffGender' => $staffGender,
            'total' => $total,
        ];

        $pdf = PDF::loadView('pdf.pak', $data);

        $pdf->save($destinationPath);
        return [
            'name' => $set_file_name,
            'location' => $destinationLink,
        ];

    }

    protected function generatePDF($id)
    {

    }

}
