<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_CrudController;
use App\Codes\Logic\PakLogic;
use App\Codes\Models\Dupak;
use App\Codes\Models\DupakKegiatan;
use App\Codes\Models\Golongan;
use App\Codes\Models\JabatanPerancang;
use App\Codes\Models\Pangkat;
use App\Codes\Models\SuratPernyataan;
use App\Codes\Models\JenjangPerancang;
use App\Codes\Models\SuratPernyataanKegiatan;
use App\Codes\Models\UnitKerja;
use App\Codes\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PersetujuanSuratPernyataanController extends _CrudController
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
            'email' => [
            ],
            'total_kredit' => [
                'type' => 'number'
            ],
            'status' => [
                'type' => 'select'
            ],
            'created_at' => [
                'lang' => 'DiKirim',
                'type' => 'datetime'
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
            $request, 'general.persetujuan_surat_pernyataan', 'persetujuan-surat-pernyataan', 'SuratPernyataan', 'persetujuan-surat-pernyataan',
            $passingData
        );

        $this->listView['show'] = env('ADMIN_TEMPLATE').'.page.persetujuan_surat_pernyataan.forms';
        $this->listView['edit'] = env('ADMIN_TEMPLATE').'.page.persetujuan_surat_pernyataan.forms';
        $this->listView['dataTable'] = env('ADMIN_TEMPLATE').'.page.persetujuan_surat_pernyataan.list_button';

        $this->data['listSet']['status'] = get_list_status_surat_pernyataan();

    }

    public function dataTable()
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $dataTables = new DataTables();

        $builder = Users::selectRaw('users.id, users.name, users.username, users.email, tx_surat_pernyataan.status,
                SUM(tx_surat_pernyataan.total_kredit) AS total_kredit, tx_surat_pernyataan.created_at,
                tx_surat_pernyataan.updated_at')
            ->join('tx_surat_pernyataan', 'tx_surat_pernyataan.user_id', '=', 'users.id')
            ->where('users.upline_id', $userId)
            ->whereIn('tx_surat_pernyataan.status', [1,2,80,99])
            ->groupByRaw('users.id, users.name, users.username, users.email, tx_surat_pernyataan.status,
                tx_surat_pernyataan.created_at,
                tx_surat_pernyataan.updated_at');

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
            else if (in_array($list['type'], ['number'])) {
                $dataTables = $dataTables->editColumn($fieldName, function ($query) use ($fieldName, $list, $listRaw) {
                    return number_format($query->$fieldName, 3);
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

    public function show($id)
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $getAtasan = Users::where('id', $userId)->first();
        if (!$getAtasan) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        $getSuratPernyataan = SuratPernyataan::where('user_id', $id)->whereIn('status', [1,2,80, 99])->get();
        $getSuratPernyataanIds = [];
        $status = 0;
        $totalKredit = 0;
        $perancangId = 0;
        $listSuratPernyataan = [];
        foreach ($getSuratPernyataan as $list) {
            $getSuratPernyataanIds[] = $list->id;
            $status = $list->status;
            $totalKredit += $list->total_kredit;
            $perancangId = $list->user_id;
            $listSuratPernyataan[$list->top_kegiatan_id] = $list->id;
        }

        $getPerancang = Users::where('id', $perancangId)->first();

        $getPAKLogic = new PakLogic();
        $getData = $getPAKLogic->getSuratPernyataanUser($getSuratPernyataanIds);

        $getJenjangPerancang = JenjangPerancang::where('status', 1)->orderBy('order_high', 'ASC')->get();

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
        $data['data'] = (object)[
            'id' => $id,
            'status' => $status,
            'total_kredit' => $totalKredit
        ];
        $data['dataUser'] = $getPerancang;
        $data['dataJenjangPerancang'] = $getJenjangPerancang;
        $data['dataPermen'] = $dataPermen;
        $data['dataFilterKegiatan'] = $getFilterKegiatan;
        $data['dataKegiatan'] = $dataKegiatan;
        $data['dataTopKegiatan'] = $dataTopKegiatan;
        $data['totalPermen'] = $totalPermen;
        $data['listSuratPernyataan'] = $listSuratPernyataan;
        $data['totalTop'] = $totalTop;
        $data['totalAk'] = $totalAk;
        $data['topId'] = $topId;
        $data['kredit'] = $kredit;

        return view($this->listView[$data['viewType']], $data);
    }

    public function showPdf($id)
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $getAtasan = Users::where('id', $userId)->first();
        if (!$getAtasan) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        $getSuratPernyataan = SuratPernyataan::where('id', $id)->whereIn('status', [80,88,99])->first();
        if ($getSuratPernyataan) {
            $getPAKLogic = new PakLogic();
            $getPAKLogic->generateSuratPernyataan($id);
        }
        return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
    }

    public function edit($id)
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $getAtasan = Users::where('id', $userId)->first();
        if (!$getAtasan) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        $getSuratPernyataan = SuratPernyataan::where('user_id', $id)->whereIn('status', [1,2])->get();
        $getSuratPernyataanIds = [];
        $status = 0;
        $totalKredit = 0;
        $perancangId = 0;
        foreach ($getSuratPernyataan as $list) {
            $getSuratPernyataanIds[] = $list->id;
            $status = $list->status;
            $totalKredit += $list->total_kredit;
            $perancangId = $list->user_id;
        }

        $getPerancang = Users::where('id', $perancangId)->first();

        $getJenjangPerancang = JenjangPerancang::where('status', 1)->orderBy('order_high', 'ASC')->get();

        $getPAKLogic = new PakLogic();
        $getData = $getPAKLogic->getSuratPernyataanUser($getSuratPernyataanIds);

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
        $data['data'] = (object)[
            'id' => $id,
            'status' => $status,
            'total_kredit' => $totalKredit
        ];
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

        $userId = session()->get('admin_id');

        $getAtasan = Users::where('id', $userId)->first();
        if (!$getAtasan) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        $getSuratPernyataan = SuratPernyataan::where('user_id', $id)->whereIn('status', [1,2])->get();
        $getSuratPernyataanIds = [];
        $getUserId = 0;
        $getUpLineId = 0;
        foreach ($getSuratPernyataan as $list) {
            $getSuratPernyataanIds[] = $list->id;
            $getUserId = $list->user_id;
            $getUpLineId = $list->upline_id;
        }

        $actionKegiatan = $this->request->get('action_kegiatan');
        $messageKegiatan = $this->request->get('message_kegiatan');
        $getSaveFlag = $this->request->get('save');

        DB::beginTransaction();
        $dateNow = date('Y-m-d H:i:s');

        $getSuratPernyataanKegiatan = SuratPernyataanKegiatan::selectRaw('tx_surat_pernyataan_kegiatan.*, tx_kegiatan.kredit')
            ->join('tx_kegiatan', 'tx_kegiatan.id', '=', 'tx_surat_pernyataan_kegiatan.kegiatan_id')
            ->whereIn('surat_pernyataan_id', $getSuratPernyataanIds)->get();
        $totalKredit = [];
        $allTotalKredit = 0;
        foreach ($getSuratPernyataanKegiatan as $list) {
            $getAction = isset($actionKegiatan[$list->id]) ? $actionKegiatan[$list->id] : 1;
            $getMessage = '';
            if ($getAction == 99) {
                $getMessage = isset($messageKegiatan[$list->id]) ? $messageKegiatan[$list->id] : '';
            }
            else {
                if (isset($totalKredit[$list->surat_pernyataan_id])) {
                    $totalKredit[$list->surat_pernyataan_id] += $list->kredit;
                }
                else {
                    $totalKredit[$list->surat_pernyataan_id] = $list->kredit;
                }
                $allTotalKredit += $list->kredit;
            }
            $list->message = $getMessage;
            $list->status = $getAction;
            $list->save();
        }

        if ($getSaveFlag == 2) {

            $getUser = Users::where('id', $getUserId)->first();
            $getAtasan = Users::where('id', $getUpLineId)->first();
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

            $getAtasanPangkat = $getListPangkat[$getAtasan->pangkat_id] ?? '';
            $getAtasanGolongan = $getListGolongan[$getAtasan->golongan_id] ?? '';
            $getAtasanJabatan = $getListJabatan[$getAtasan->jenjang_perancang_id] ?? '';
            $getAtasanUnitKerja = $getListUnitKerja[$getAtasan->unit_kerja_id] ?? '';
            $getAtasanPangkatTms = $getAtasan->tmt_pangkat ? date('d-M-Y', strtotime($getAtasan->tmt_pangkat)) : '';
            $getAtasanJabatanTms = $getAtasan->tmt_jabatan ? date('d-M-Y', strtotime($getAtasan->tmt_jabatan)) : '';

            $saveDupak = new Dupak();
            $saveDupak->user_id = $getUserId;
            $saveDupak->upline_id = $getUpLineId;
            $saveDupak->sekretariat_id = 0;
            $saveDupak->unit_kerja_id = 0;
            $saveDupak->info_dupak = json_encode([
                'perancang_name' => $getUser->name,
                'perancang_nip' => $getUser->username,
                'perancang_pangkat' => $getUserPangkat.'/'.$getUserGolongan.'/'.$getUserPangkatTms,
                'perancang_jabatan' => $getUserJabatan.'/'.$getUserJabatanTms,
                'perancang_unit_kerja' => $getUserUnitKerja,
                'atasan_name' => $getAtasan->name,
                'atasan_nip' => $getAtasan->username,
                'atasan_pangkat' => $getAtasanPangkat.'/'.$getAtasanGolongan.'/'.$getAtasanPangkatTms,
                'atasan_jabatan' => $getAtasanJabatan.'/'.$getAtasanJabatanTms,
                'atasan_unit_kerja' => $getAtasanUnitKerja
            ]);
            $saveDupak->total_kredit = $allTotalKredit;
            $saveDupak->status = 1;

            $saveDupak->save();

            $dupakId = $saveDupak->id;

            $saveDupakKegiatan = [];
            foreach ($getSuratPernyataanKegiatan as $list) {
                if ($getAction != 99) {
                    $saveDupakKegiatan[] = [
                        'dupak_id' => $dupakId,
                        'kegiatan_id' => $list->id,
                        'ms_kegiatan_id' => $list->ms_kegiatan_id,
                        'status' => 1
                    ];
                }
            }

            if (count($saveDupakKegiatan) > 0) {
                DupakKegiatan::insert($saveDupakKegiatan);
            }

            foreach ($getSuratPernyataan as $list) {
                $getTotalKredit = isset($totalKredit[$list->id]) ? $totalKredit[$list->id] : 0;
                $list->total_kredit = $getTotalKredit;
                $list->updated_at = $dateNow;
                $list->tanggal = date('Y-m-d');
                $list->status = 80;
                $list->dupak_id = $dupakId;

                $list->save();
            }
        }
        else {
            foreach ($getSuratPernyataan as $list) {
                $getTotalKredit = isset($totalKredit[$list->id]) ? $totalKredit[$list->id] : 0;
                $list->total_kredit = $getTotalKredit;
                $list->updated_at = $dateNow;
                $list->status = 2;
                $list->save();
            }
        }

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
