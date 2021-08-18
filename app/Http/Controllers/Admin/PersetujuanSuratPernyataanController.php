<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_CrudController;
use App\Codes\Logic\PakLogic;
use App\Codes\Models\JabatanPerancang;
use App\Codes\Models\Kegiatan;
use App\Codes\Models\MsKegiatan;

use App\Codes\Models\Permen;
use App\Codes\Models\SuratPernyataan;
use App\Codes\Models\JenjangPerancang;
use App\Codes\Models\SuratPernyataanKegiatan;
use App\Codes\Models\UnitKerja;
use App\Codes\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use PDF;

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
            'kegiatan' => [
                'custom' => ', name:"ms_kegiatan.name"'
            ],
            'status' => [
                'type' => 'select'
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
            tx_surat_pernyataan.status')
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

        $getSuratPernyataanKegiatan = SuratPernyataanKegiatan::where('surat_pernyataan_id', $id)->get();
        foreach ($getSuratPernyataanKegiatan as $list) {
            $getAction = isset($actionKegiatan[$list->id]) ? $actionKegiatan[$list->id] : 1;
            $getMessage = '';
            if ($getAction == 99) {
                $getMessage = isset($messageKegiatan[$list->id]) ? $messageKegiatan[$list->id] : '';
            }
            $list->message = $getMessage;
            $list->status = $getAction;
            $list->save();

        }

        if ($getSaveFlag == 2) {
            $getData->status = 80;
//            $pakLogic = new PakLogic();
//            $pakLogic->generateSuratPernyataan($id);
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
