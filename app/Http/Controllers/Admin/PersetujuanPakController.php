<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_CrudController;
use App\Codes\Logic\PakLogic;
use App\Codes\Models\Pak;
use App\Codes\Models\PakKegiatan;
use App\Codes\Models\JenjangPerancang;
use App\Codes\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PersetujuanPakController extends _CrudController
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
            $request, 'general.persetujuan_pak', 'persetujuan-pak', 'Pak', 'persetujuan-pak',
            $passingData
        );

        $this->listView['show'] = env('ADMIN_TEMPLATE').'.page.persetujuan_pak.forms';
        $this->listView['edit'] = env('ADMIN_TEMPLATE').'.page.persetujuan_pak.forms';
        $this->listView['dataTable'] = env('ADMIN_TEMPLATE').'.page.persetujuan_pak.list_button';

        $this->data['listSet']['status'] =  get_list_status_pak();

    }


    public function dataTable()
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $dataTables = new DataTables();

        $builder = Users::selectRaw('tx_pak.id, users.username, users.name, ms_kegiatan.name AS kegiatan, tx_pak.status,
            tx_pak.total_kredit, tx_pak.created_at, tx_pak.updated_at')
            ->join('tx_pak', 'tx_pak.user_id', '=', 'users.id')
            ->join('ms_kegiatan', 'ms_kegiatan.id', '=', 'tx_pak.top_kegiatan_id')
            ->where('tx_pak.user_id', $userId)
            ->whereIn('tx_pak.status', [1,2,3,80,99]);

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

        $getPak = Pak::where('id', $id)->whereIn('status', [1,2])->first();
        //dd($getPak->file_upload_surat_pernyataan);
        $fileSP = json_decode($getPak->file_upload_surat_pernyataan, true);

        //dd($fileSP);
        if (!$getPak) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }
        $getPerancang = Users::where('id', $userId)->where('upline_id', $getPak->upline_id)->first();
        if (!$getPerancang) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        $getJenjangPerancang = JenjangPerancang::where('status', 1)->orderBy('order_high', 'ASC')->get();

        $getNewLogic = new PakLogic();
        $getData = $getNewLogic->getPakUser($getPak);

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
        $data['data'] = $getPak;
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

    public function pdf(){
        $getPAKLogic = new PakLogic();
        $getPAKLogic->generatePak();
    }

    public function show($id)
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $getPak = Pak::where('id', $id)->whereIn('status', [1,2,3,80,99])->first();

        if (!$getPak) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }
        $getPerancang = Users::where('id', $userId)->where('upline_id', $getPak->upline_id)->first();
        if (!$getPerancang) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        if ($this->request->get('pdf') == 1) {
            $getPAKLogic = new PakLogic();
            $getPAKLogic->generatePak($id);
        }

        $getJenjangPerancang = JenjangPerancang::where('status', 1)->orderBy('order_high', 'ASC')->get();

        $getNewLogic = new PakLogic();
        $getData = $getNewLogic->getPakUser($getPak);

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
        $data['data'] = $getPak;
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

        $getData = Pak::where('id', $id)->whereIn('status', [1,2])->first();
        if (!$getData) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        $actionKegiatan = $this->request->get('action_kegiatan');
        $messageKegiatan = $this->request->get('message_kegiatan');
        $getSaveFlag = $this->request->get('save');

        DB::beginTransaction();

        $getPakKegiatan = PakKegiatan::selectRaw('tx_pak_kegiatan.*, tx_kegiatan.kredit AS kredit')
            ->join('tx_kegiatan', 'tx_kegiatan.id', '=', 'tx_pak_kegiatan.kegiatan_id')
            ->where('pak_id', $id)->get();

        $totalKredit = 0;
        foreach ($getPakKegiatan as $list) {
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
            $getData->status = $getAction;
            $getData->update();


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
