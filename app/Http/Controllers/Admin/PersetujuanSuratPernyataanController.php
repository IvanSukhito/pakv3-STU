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
            'total_kredit' => [
                'type' => 'number'
            ],
            'kegiatan' => [
                'custom' => ', name:"ms_kegiatan.name"'
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

        $builder = Users::selectRaw('tx_surat_pernyataan.id, users.username, users.name, ms_kegiatan.name AS kegiatan,
            tx_surat_pernyataan.status, tx_surat_pernyataan.total_kredit, tx_surat_pernyataan.created_at,
            tx_surat_pernyataan.updated_at')
            ->join('tx_surat_pernyataan', 'tx_surat_pernyataan.user_id', '=', 'users.id')
            ->join('ms_kegiatan', 'ms_kegiatan.id', '=', 'tx_surat_pernyataan.top_kegiatan_id')
            ->where('users.upline_id', $userId)
            ->whereIn('tx_surat_pernyataan.status', [1,2,80,99]);

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

    public function show($id)
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $getSuratPernyataan = SuratPernyataan::where('id', $id)->whereIn('status', [1,2,80, 99])->first();
        if (!$getSuratPernyataan) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }
        $getPerancang = Users::where('id', $getSuratPernyataan->user_id)->where('upline_id', $userId)->first();
        if (!$getPerancang) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        if ($this->request->get('pdf') == 1) {
            $getPAKLogic = new PakLogic();
            $getPAKLogic->generateSuratPernyataan($id);
        }

        $getJenjangPerancang = JenjangPerancang::where('status', 1)->orderBy('order_high', 'ASC')->get();

        $getNewLogic = new PakLogic();
        $getData = $getNewLogic->getSuratPernyataanUser($getSuratPernyataan);

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
        $data['data'] = $getSuratPernyataan;
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

    public function edit($id)
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $getSuratPernyataan = SuratPernyataan::where('id', $id)->whereIn('status', [1,2])->first();
        if (!$getSuratPernyataan) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }
        $getPerancang = Users::where('id', $getSuratPernyataan->user_id)->where('upline_id', $userId)->first();
        if (!$getPerancang) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }


       // $exportPDF = $this->request->get('pdf');
       // dd($exportPDF);
        if($this->request->get('pdf') == 1){
            $getPAKLogic = new PakLogic();
            $getPAKLogic->generateSuratPernyataan($id);

            //$getPAKLogic->exportPDF();
        }

        //dd($this->request->get('pdf'));
        $getJenjangPerancang = JenjangPerancang::where('status', 1)->orderBy('order_high', 'ASC')->get();

        $getNewLogic = new PakLogic();
        $getData = $getNewLogic->getSuratPernyataanUser($getSuratPernyataan);

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
        $data['data'] = $getSuratPernyataan;
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

        $getData = SuratPernyataan::where('id', $id)->whereIn('status', [1,2])->first();
        if (!$getData) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        $actionKegiatan = $this->request->get('action_kegiatan');
        $messageKegiatan = $this->request->get('message_kegiatan');
        $getSaveFlag = $this->request->get('save');

        DB::beginTransaction();

        $getSuratPernyataanKegiatan = SuratPernyataanKegiatan::selectRaw('tx_surat_pernyataan_kegiatan.*, tx_kegiatan.kredit AS kredit')
            ->join('tx_kegiatan', 'tx_kegiatan.id', '=', 'tx_surat_pernyataan_kegiatan.kegiatan_id')
            ->where('surat_pernyataan_id', $id)->get();
        $totalKredit = 0;
        foreach ($getSuratPernyataanKegiatan as $list) {
            $getAction = isset($actionKegiatan[$list->id]) ? $actionKegiatan[$list->id] : 1;
            $getMessage = '';
            if ($getAction == 99) {
                $getMessage = isset($messageKegiatan[$list->id]) ? $messageKegiatan[$list->id] : '';
            }
            else {
                $totalKredit += $list->kredit;
            }
            $list->message = $getMessage;
            $list->status = $getAction;
            $list->save();

        }

        $getData->total_kredit = $totalKredit;

        if ($getSaveFlag == 2) {
            $getData->tanggal = date('Y-m-d');
            $getData->status = 80;

            $getUser = Users::where('id', $getData->user_id)->first();
            $getAtasan = Users::where('id', $getData->upline_id)->first();
            $getListPangkat = Pangkat::pluck('name', 'id')->toArray();
            $getListGolongan = Golongan::pluck('name', 'id')->toArray();
            $getListJabatan = JabatanPerancang::pluck('name', 'id')->toArray();
            $getListUnitKerja = UnitKerja::pluck('name', 'id')->toArray();

            $getUserPangkat = $getListPangkat[$getUser->pangkat_id] ?? '';
            $getUserGolongan = $getListGolongan[$getUser->golongan_id] ?? '';
            $getUserJabatan = $getListJabatan[$getUser->jenjang_perancang_id] ?? '';
            $getUserUnitKerja = $getListUnitKerja[$getUser->unit_kerja_id] ?? '';
            $getUserPangkatTms = $getUser->tmt_kenaikan_jenjang_terakhir ? date('d-M-Y', strtotime($getUser->tmt_kenaikan_jenjang_terakhir)) : '';
            $getUserJabatanTms = $getUser->kenaikan_jenjang_terakhir ? date('d-M-Y', strtotime($getUser->kenaikan_jenjang_terakhir)) : '';

            $getAtasanPangkat = $getListPangkat[$getAtasan->pangkat_id] ?? '';
            $getAtasanGolongan = $getListGolongan[$getAtasan->golongan_id] ?? '';
            $getAtasanJabatan = $getListJabatan[$getAtasan->jenjang_perancang_id] ?? '';
            $getAtasanUnitKerja = $getListUnitKerja[$getAtasan->unit_kerja_id] ?? '';
            $getAtasanPangkatTms = $getAtasan->tmt_kenaikan_jenjang_terakhir ? date('d-M-Y', strtotime($getAtasan->tmt_kenaikan_jenjang_terakhir)) : '';
            $getAtasanJabatanTms = $getAtasan->kenaikan_jenjang_terakhir ? date('d-M-Y', strtotime($getAtasan->kenaikan_jenjang_terakhir)) : '';

            $saveDupak = new Dupak();
            $saveDupak->user_id = $getData->user_id;
            $saveDupak->upline_id = $getData->upline_id;
            $saveDupak->top_kegiatan_id = $getData->top_kegiatan_id;
            $saveDupak->sekretariat_id = 0;
            $saveDupak->unit_kerja_id = 0;
            $saveDupak->surat_pernyataan_id = $getData->id;
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
            $saveDupak->total_kredit = $totalKredit;
            $saveDupak->status = 1;

            $saveDupak->save();

            $dupakId = $saveDupak->id;

            $saveDupakKegiatan = [];
            foreach ($getSuratPernyataanKegiatan as $list) {
                $saveDupakKegiatan[] = [
                    'dupak_id' => $dupakId,
                    'kegiatan_id' => $list->id,
                    'ms_kegiatan_id' => $list->ms_kegiatan_id,
                    'status' => 1
                ];
            }

            if (count($saveDupakKegiatan) > 0) {
                DupakKegiatan::insert($saveDupakKegiatan);
            }

            //Create PDF here

        }
        else {
            $getData->status = 2;
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
