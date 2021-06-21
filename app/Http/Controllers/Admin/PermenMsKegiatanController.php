<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_CrudParentController;
use App\Codes\Models\JenjangPerancang;
use App\Codes\Models\MsKegiatan;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Yajra\DataTables\DataTables;

class PermenMsKegiatanController extends _CrudParentController
{
    public function __construct(Request $request)
    {
        $passingData = [
            'id' => [
                'create' => 0,
                'edit' => 0,
                'show' => 0
            ],
            'parent_id' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'general.parent',
//                'custom' => ', name: "C.name"'
            ],
            'name' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ]
            ],
            'ak' => [
                'validate' => [
                    'create' => 'required|numeric',
                    'edit' => 'required|numeric',
                ]
            ],
            'satuan' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ]
            ],
            'jenjang_perancang_id' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'general.jenjang_perancang',
//                'custom' => ', name: "B.name"'
            ],
            'status' => [
                'list' => 0,
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
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
            $request, 'general.ms_kegiatan', 'mskegiatan', 'MsKegiatan', 'permen', [
            'parentKeyId' => 'id',
            'parentId' => 'permen_id',
            'parentRoute' => 'permen',
            'parentModel' => 'Permen'
        ],
            $passingData
        );

        $listJenjangPerancang = JenjangPerancang::where('status', '=', 1)->pluck('name', 'id')->toArray();

        $this->data['listAttribute']['aaSorting'] = "[0,'DESC']";

//        $this->data['listSet']['parent_id'] = $listParent;
        $this->data['listSet']['jenjang_perancang_id'] = $listJenjangPerancang;
        $this->data['listSet']['status'] = get_list_status();

    }



    public function store($parentId)
    {
        $this->callPermission();

        $viewType = 'create';

        $getListCollectData = collectPassingData($this->passingData, $viewType);
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

        $data = $this->getCollectedData($getListCollectData, $viewType, $data);

        $data[$this->parentId] = $parentId;

        $getData = $this->crud->store($data);

        $id = $getData->id;

        if($this->request->ajax()){
            return response()->json(['result' => 1, 'message' => __('general.success_add')]);
        }
        else {
            session()->flash('message', __('general.success_add'));
            session()->flash('message_alert', 2);
            return redirect()->route('admin.' . $this->route . '.show', ['parent_id' => $parentId, 'mskegiatan' => $id]);
        }
    }

    public function edit($parentId, $id)
    {
        $this->callPermission();

        $getData = $this->crud->show($id);
        if (!$getData) {
            return redirect()->route('admin.' . $this->route . '.index', $parentId);
        }

        $data = $this->data;

        $getParent = MsKegiatan::where('status', 1)->where('permen_id', $parentId)->get();
        $listParent = [0 => 'Tidak memiliki Parent'];
        if($getParent) {
            foreach($getParent as $list) {
                $listParent[$list->id] = $list->name;
            }
        }

        $data['parent'] = $this->parentModel::where($this->parentKeyId, '=', $parentId)->first();
        $data['listSet']['parent_id'] = $listParent;
        $data['parentId'] = $parentId;
        $data['viewType'] = 'edit';
        $data['formsTitle'] = __('general.title_edit', ['field' => $data['thisLabel']]);
        $data['passing'] = collectPassingData($this->passingData, $data['viewType']);
        $data['data'] = $getData;

        return view($this->listView[$data['viewType']], $data);
    }

    public function update($parentId, $id)
    {
        $this->callPermission();

        $viewType = 'edit';

        $getData = $this->crud->show($id);
        if (!$getData) {
            return redirect()->route('admin.' . $this->route . '.index', $parentId);
        }

        $getListCollectData = collectPassingData($this->passingData, $viewType);
        $validate = $this->setValidateData($getListCollectData, $viewType, $id);
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

        $data = $this->getCollectedData($getListCollectData, $viewType, $data, $getData);

        $getData = $this->crud->update($data, $id);

        $id = $getData->id;

        if($this->request->ajax()){
            return response()->json(['result' => 1, 'message' => __('general.success_edit')]);
        }
        else {
            session()->flash('message', __('general.success_edit'));
            session()->flash('message_alert', 2);
            return redirect()->route('admin.' . $this->route . '.show', ['parent_id' => $parentId, 'mskegiatan' => $id]);
        }
    }

    public function create($parentId)
    {
        $this->callPermission();

        $getParent = MsKegiatan::where('status', 1)->where('permen_id', $parentId)->get();
        $listParent = [0 => 'Tidak memiliki Parent'];
        if($getParent) {
            foreach($getParent as $list) {
                $listParent[$list->id] = $list->name;
            }
        }

        $data = $this->data;

        $data['parent'] = $this->parentModel::where($this->parentKeyId, '=', $parentId)->first();
        $data['listSet']['parent_id'] = $listParent;
        $data['parentId'] = $parentId;
        $data['viewType'] = 'create';
        $data['formsTitle'] = __('general.title_create', ['field' => $data['thisLabel']]);
        $data['passing'] = collectPassingData($this->passingData, $data['viewType']);

        return view($this->listView[$data['viewType']], $data);
    }

    public function show($parentId, $id)
    {
        $this->callPermission();

        $getData = $this->crud->show($id);
        if (!$getData) {
            return redirect()->route('admin.' . $this->route . '.index', $parentId);
        }

        $data = $this->data;

        $getParent = MsKegiatan::where('status', 1)->where('permen_id', $parentId)->get();
        $listParent = [0 => 'Tidak memiliki Parent'];
        if($getParent) {
            foreach($getParent as $list) {
                $listParent[$list->id] = $list->name;
            }
        }

        $data['parent'] = $this->parentModel::where($this->parentKeyId, '=', $parentId)->first();
        $data['parentId'] = $parentId;
        $data['listSet']['parent_id'] = $listParent;
        $data['viewType'] = 'show';
        $data['formsTitle'] = __('general.title_show', ['field' => $data['thisLabel']]);
        $data['passing'] = collectPassingData($this->passingData, $data['viewType']);
        $data['data'] = $getData;

        return view($this->listView[$data['viewType']], $data);
    }




}
