<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_CrudController;
use App\Codes\Logic\PakLogic;
use App\Codes\Models\UnitKerja;
use App\Codes\Models\Dupak;
use App\Codes\Models\Pak;
use App\Codes\Models\JenjangPerancang;
use App\Codes\Models\Users;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PakController extends _CrudController
{
    public function __construct(Request $request)
    {
        $passingData = [
            'id' => [
                'edit' => 0
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
                'lang' => 'Disetujui',
                'type' => 'datetime'
            ],
            'action' => [
                'show' => 0,
                'lang' => 'Aksi',
            ]
        ];

        parent::__construct(
            $request, 'general.pak', 'pak', 'Pak', 'pak',
            $passingData
        );

        $this->listView['show'] = env('ADMIN_TEMPLATE').'.page.pak.forms';
        $this->listView['edit'] = env('ADMIN_TEMPLATE').'.page.pak.forms';

        $this->listView['dataTable'] = env('ADMIN_TEMPLATE').'.page.pak.list_button';

        $this->data['listSet']['status'] = get_list_status_pak();

    }

    public function dataTable()
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $dataTables = new DataTables();

        $builder = $this->model::where('tx_pak.user_id', $userId)
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

    public function edit($id){
        //return view($this->listView[$data['viewType']]);
        $this->callPermission();

        $userId = session()->get('admin_id');

        $data = $this->data;

        $unitkerja = UnitKerja::all();
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



       // $data['data'] = $getDupak;
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
        $data['unitkerja'] = $unitkerja;
        $data['viewType'] = 'edit';
        $data['formsTitle'] = __('general.title_dupak_uploadSP', ['field' => $data['thisLabel']]);
        $data['data'] = $this->crud->show($id);

        return view($this->listView['edit'], $data);
    }

    public function update($id){
        //dd($this->request);
        $this->callPermission();
        $viewType = 'Upload Surat Pernyataan';
        $this->request->validate([

            'file_upload_dupak' => 'required',
            'unit_kerja_id' => 'required'
        ]);

        $getData = Pak::findOrFail($id);

        $userId = session()->get('admin_id');
        $getUser = Users::where('id', $userId)->first();
        $userNip = $getUser->username;
        $userFolder = 'user_' . preg_replace("/[^A-Za-z0-9?!]/", '', $userNip);
        $todayDate = date('Y-m-d');
        $folderName = $userFolder . '/kegiatan/' . $todayDate . '/';
        $dokument = $this->request->file('file_upload_dupak');
        $unitkerja = $this->request->get('unit_kerja_id');

        $totalDokument = [];


        foreach ($dokument as $listDoc) {
            if ($listDoc->getError() == 0) {
                $getFileName = $listDoc->getClientOriginalName();
                $ext = explode('.', $getFileName);
                $fileName = reset($ext);
                $ext = end($ext);
                $setFileName = preg_replace("/[^A-Za-z0-9?!]/", '_', $fileName) . '_' . date('His') . rand(0,100) . '.' . $ext;
                $destinationPath = './uploads/' . $folderName . $getData->id . '/';
                $destinationLink = 'uploads/' . $folderName . $getData->id . '/' . $setFileName;
                $listDoc->move($destinationPath, $setFileName);

                $totalDokument[] = [
                    'name' => $setFileName,
                    'path' => $destinationLink
                ];
            }
        }

        $getData->update([
            'file_upload_dupak' => json_encode($totalDokument),
            'unit_kerja_id' => $unitkerja,
            'status' => 2
        ]);

        if($this->request->ajax()){
            return response()->json(['result' => 1, 'message' => __('general.success_add')]);
        }
        else {
            session()->flash('message', __('general.success_dupak_uploadSP'));
            session()->flash('message_alert', 2);
            return redirect()->route('admin.' . $this->route . '.index');
        }



    }
}
