<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_CrudController;
use App\Codes\Logic\PakLogic;
use App\Codes\Models\Dupak;
use App\Codes\Models\DupakKegiatan;
use App\Codes\Models\JenjangPerancang;
use App\Codes\Models\Pendidikan;
use App\Codes\Models\Users;
use App\Codes\Models\Golongan;
use App\Codes\Models\JabatanPerancang;
use App\Codes\Models\PakKegiatan;
use App\Codes\Models\Pangkat;
use App\Codes\Models\Pak;
use App\Codes\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PersetujuanDupakController extends _CrudController
{
    public function __construct(Request $request)
    {
        $passingData = [
            'id' => [
            ],
            'username' => [
                'lang' => 'general.nip'
            ],
            'name' => [
            ],
            'total_kredit' => [
                'type' => 'number'
            ],
            'tanggal_mulai' => [
                'type' => 'datetime'
            ],
            'tanggal_akhir' => [
                'type' => 'datetime'
            ],
            'status' => [
                'type' => 'select'
            ],
            'updated_at' => [
                'lang' => 'DiUpdate',
                'type' => 'datetime'
            ],
            'action' => [
                'create' => 0,
                'edit' => 0,
                'show' => 0,
                'lang' => 'Aksi',
            ]
        ];

        parent::__construct(
            $request, 'general.persetujuan_dupak', 'persetujuan-dupak', 'Dupak', 'persetujuan-dupak',
            $passingData
        );

        $this->listView['show'] = env('ADMIN_TEMPLATE').'.page.persetujuan_dupak.forms';
        $this->listView['edit'] = env('ADMIN_TEMPLATE').'.page.persetujuan_dupak.forms';
        $this->listView['dataTable'] = env('ADMIN_TEMPLATE').'.page.persetujuan_dupak.list_button';

        $this->data['listSet']['status'] =  get_list_status_dupak();

    }


    public function dataTable()
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $dataTables = new DataTables();

        $builder = Dupak::selectRaw('tx_dupak.id, users.username, users.name,
            tx_dupak.tanggal_mulai, tx_dupak.tanggal_akhir, tx_dupak.status,
            tx_dupak.total_kredit, tx_dupak.created_at, tx_dupak.updated_at')
            ->join('users', 'users.id', '=', 'tx_dupak.user_id')
            ->whereIn('tx_dupak.status', [1,2,3,80,99]);

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
            else if (in_array($list['type'], ['datetime'])) {
                $listRaw[] = $fieldName;
                $dataTables = $dataTables->editColumn($fieldName, function ($query) use ($fieldName, $list, $listRaw) {
                    return date('d-M-Y', strtotime($query->$fieldName));
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

    public function edit($id)
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $getDupak = Dupak::where('id', $id)->whereIn('status', [1,2])->first();
        $fileSP = json_decode($getDupak->file_upload_surat_pernyataan, true);

        if (!$getDupak) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }
        $getPerancang = Users::where('id', $userId)->where('upline_id', $getDupak->upline_id)->first();
        if (!$getPerancang) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        $getJenjangPerancang = JenjangPerancang::where('status', 1)->orderBy('order_high', 'ASC')->get();

        $getNewLogic = new PakLogic();
        $getData = $getNewLogic->getDupakUser($getDupak);

        $dataPermen = [];
        $dataKegiatan = [];
        $dataTopKegiatan = [];
        $getFilterKegiatan = [];
        $totalPermen = 0 ;
        $totalTop = 0 ;
        $totalAk = 0 ;
        $topId = [];
        $kredit = [];

        if (count($getData['data']) > 0) {
            $totalPermen = count($getData['total_permen']);
            $totalTop = count($getData['total_top']);
            $totalAk = $getData['total_ak'];
            $dataPermen = $getData['permen'];
            $dataKegiatan = $getData['data'];
            $dataTopKegiatan = $getData['top_kegiatan'];
            $topId = $getData['total_top'];
            $kredit = $getData['kredit'];

        }

        $data = $this->data;

        $data['viewType'] = 'edit';
        $data['formsTitle'] = __('general.title_edit', ['field' => $data['thisLabel']]);
        $data['passing'] = collectPassingData($this->passingData, $data['viewType']);
        $data['data'] = $getDupak;
        $data['dataUser'] = $getPerancang;
        $data['dataJenjangPerancang'] = $getJenjangPerancang;
        $data['dataPermen'] = $dataPermen;
        $data['dataFilterKegiatan'] = $getFilterKegiatan;
        $data['dataKegiatan'] = $dataKegiatan;
        $data['dataTopKegiatan'] = $dataTopKegiatan;
        $data['totalPermen'] = $totalPermen;
        $data['totalTop'] = $totalTop;
        $data['totalAk'] = $totalAk;
        $data['topId'] = $topId;
        $data['kredit'] = $kredit;
        $data['fileSP'] = $fileSP;


        return view($this->listView[$data['viewType']], $data);
    }

    public function show($id)
    {
        $this->callPermission();

        $getDupak = Dupak::where('id', $id)->whereIn('status', [1,2,3,80,99])->first();
        if (!$getDupak) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        $userId = $getDupak->user_id;
        $getPerancang = Users::where('id', $userId)->where('upline_id', $getDupak->upline_id)->first();
        if (!$getPerancang) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        if ($this->request->get('pdf') == 1) {
            $getPAKLogic = new PakLogic();
            $getPAKLogic->generateDupak($id);
        }

        $getJenjangPerancang = JenjangPerancang::where('status', 1)->orderBy('order_high', 'ASC')->get();

        $getNewLogic = new PakLogic();
        $getData = $getNewLogic->getDupakUser($getDupak);

        $dataPermen = [];
        $dataKegiatan = [];
        $dataTopKegiatan = [];
        $getFilterKegiatan = [];
        $totalPermen = 0 ;
        $totalTop = 0 ;
        $totalAk = 0 ;
        $topId = [];
        $kredit = [];

        if (count($getData['data']) > 0) {
            $totalPermen = count($getData['total_permen']);
            $totalTop = count($getData['total_top']);
            $totalAk = $getData['total_ak'];
            $dataPermen = $getData['permen'];
            $dataKegiatan = $getData['data'];
            $dataTopKegiatan = $getData['top_kegiatan'];
            $topId = $getData['total_top'];
            $kredit = $getData['kredit'];
        }

        $data = $this->data;

        $data['viewType'] = 'show';
        $data['formsTitle'] = __('general.title_show', ['field' => $data['thisLabel']]);
        $data['passing'] = collectPassingData($this->passingData, $data['viewType']);
        $data['data'] = $getDupak;
        $data['dataUser'] = $getPerancang;
        $data['dataJenjangPerancang'] = $getJenjangPerancang;
        $data['dataPermen'] = $dataPermen;
        $data['dataFilterKegiatan'] = $getFilterKegiatan;
        $data['dataKegiatan'] = $dataKegiatan;
        $data['dataTopKegiatan'] = $dataTopKegiatan;
        $data['totalPermen'] = $totalPermen;
        $data['totalTop'] = $totalTop;
        $data['totalAk'] = $totalAk;
        $data['topId'] = $topId;
        $data['kredit'] = $kredit;

        return view($this->listView[$data['viewType']], $data);
    }

    public function update($id)
    {
        $this->callPermission();

        $adminId = session()->get('admin_id');

        $getData = Dupak::where('id', $id)->whereIn('status', [1,2,3])->get();
        if (!$getData) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        $actionKegiatan = $this->request->get('action_kegiatan');
        $messageKegiatan = $this->request->get('message_kegiatan');
        $getSaveFlag = $this->request->get('save');

        DB::beginTransaction();
        $dateNow = date('Y-m-d H:i:s');

        $getDupakKegiatan = DupakKegiatan::selectRaw('tx_dupak_kegiatan.*, tx_kegiatan.kredit AS kredit')
            ->join('tx_kegiatan', 'tx_kegiatan.id', '=', 'tx_dupak_kegiatan.kegiatan_id')
            ->where('dupak_id', $id)->get();

        $totalKredit = 0;
        $savePakKegiatan = [];
        foreach ($getDupakKegiatan as $list) {
            $getAction = isset($actionKegiatan[$list->id]) ? $actionKegiatan[$list->id] : 1;
            $getMessage = '';
            if ($getAction == 99) {
                $getMessage = isset($messageKegiatan[$list->id]) ? $messageKegiatan[$list->id] : '';
            }
            else {
                $totalKredit += $list->kredit;
                $savePakKegiatan[] = [
                    'kegiatan_id' => $list->kegiatan_id,
                    'ms_kegiatan_id' => $list->ms_kegiatan_id,
                    'status' => 1,
                    'created_at' => $dateNow,
                    'updated_at' => $dateNow
                ];
            }
            $list->message = $getMessage;
            $list->status = $getAction;
            $list->save();

        }

        $getData->total_kredit = $totalKredit;
        $getData->sekretariat_id = $adminId;

        if ($getSaveFlag == 2) {

            $getData->tanggal = date('Y-m-d');
            $getData->status = 80;

            $getUserId = $getData->user_id;
            $getUpLineId = $getData->upline_id;

            $getUser = Users::where('id', $getUserId)->first();
            $getAtasan = Users::where('id', $getUpLineId)->first();
            $getPendidikan = Pendidikan::where('id', $getUser->pendidikan_id)->first();
            $getListPangkat = Pangkat::pluck('name', 'id')->toArray();
            $getListGolongan = Golongan::pluck('name', 'id')->toArray();
            $getListJabatan = JabatanPerancang::pluck('name', 'id')->toArray();
            $getListUnitKerja = UnitKerja::pluck('name', 'id')->toArray();

            $getUserPangkat = $getListPangkat[$getUser->pangkat_id] ?? '';
            $getUserGolongan = $getListGolongan[$getUser->golongan_id] ?? '';
            $getUserJabatan = $getListJabatan[$getUser->jenjang_perancang_id] ?? '';
            $getUserUnitKerja = $getListUnitKerja[$getUser->unit_kerja_id] ?? '';
            $getUserPangkatTms = $getUser->tmt_pangkat ? date('d-M-Y', strtotime($getUser->tmt_pangkat)) : '';
            $getUserJabatanTms = $getUser->tmt_jabatan ? date('d-M-Y', strtotime($getUser->tmt_jabatan)) : '';

            $savePak = new Pak();
            $savePak->user_id = $getUserId;
            $savePak->upline_id = $getUpLineId;
            $savePak->unit_kerja_id = 0;
            $savePak->tim_penilai_id = 0;
            $savePak->dupak_id = $id;
            $savePak->info_pak = json_encode([
                'perancang_name' => $getUser->name,
                'perancang_nip' => $getUser->username,
                'perancang_karpeg' => $getUser->kartu_pegawai,
                'perancang_tempat_tgl_lahir' => $getUser->tempat_lahir.', '.date('d-M-Y', strtotime($getUser->tgl_lahir)),
                'perancang_pendidikan' => $getPendidikan ? $getPendidikan->name : '',
                'perancang_pangkat' => $getUserPangkat.'/'.$getUserGolongan.'/'.$getUserPangkatTms,
                'perancang_jabatan' => $getUserJabatan.'/'.$getUserJabatanTms,
                'perancang_golongan_lama' => '',
                'perancang_golongan_baru' => '',
                'perancang_unit_kerja' => $getUserUnitKerja,
                'atasan_name' => $getAtasan->name,
                'atasan_nip' => $getAtasan->username,
            ]);
            $savePak->tanggal_mulai = $getData->tanggal_mulai;
            $savePak->tanggal_akhir = $getData->tanggal_akhir;
            $savePak->total_kredit = $totalKredit;
            $savePak->status = 1;

            $savePak->save();

            $pakId = $savePak->id;

            if (count($savePakKegiatan) > 0) {
                $temp = [];
                foreach ($savePakKegiatan as $list) {
                    $list['pak_id'] = $pakId;
                    $temp[] = $list;
                }
                $savePakKegiatan = $temp;
                PakKegiatan::insert($savePakKegiatan);
            }

        }
        else {
            $getData->status = 3;
        }

        $getData->save();

        DB::commit();

        if($this->request->ajax()){
            return response()->json(['result' => 1, 'message' => __('general.success_edit_', ['field' => $this->data['thisLabel']])]);
        }
        else {
            session()->flash('message', __('general.success_edit_', ['field' => $this->data['thisLabel']]));
            session()->flash('message_alert', 2);
            return redirect()->route($this->rootRoute.'.' . $this->route . '.show', $id);
        }
    }

}
