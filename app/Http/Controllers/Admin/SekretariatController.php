<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_CrudController;
use Illuminate\Http\Request;
use App\Codes\Models\Users;
use App\Codes\Models\Golongan;
use App\Codes\Models\Pangkat;
use App\Codes\Models\UnitKerja;
use App\Codes\Models\JenjangPerancang;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class SekretariatController extends _CrudController
{
    public function __construct(Request $request)
    {

        $passingData = [
            'id' => [
                'create' => 0,
                'edit' => 0,
                'show' => 0
            ],
            'name' => [
                'validation' => [
                    'edit' => 'required'
                ]
            ],
            'username' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'lang' => 'NIP'

            ],
            'role' => [
                'create' => false,
                'edit' => false,
                'show' => 0
            ],
            'email' => [
                'validation' => [
                    'edit' => 'required|email'
                ],
                'type' => 'email'
            ],
            'unit_kerja_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'general.unit_kerja'
            ],
            'status' => [
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
            $request, 'general.sekretariat', 'sekretariat', 'Users', 'sekretariat',
            $passingData
        );

        $getUnitKerja = UnitKerja::where('status', 1)->pluck('name', 'id')->toArray();
        $listUnitKerja = [0 => 'Kosong'];
        if($getUnitKerja) {
            foreach($getUnitKerja as $key => $value) {
                $listUnitKerja[$key] = $value;
            }
        }
        $this->data['listSet']['unit_kerja_id'] = $listUnitKerja;
        $this->data['listSet']['status'] = get_list_status();
        $this->listView['index'] = env('ADMIN_TEMPLATE') . '.page.sekretariat.list';
        //$this->passingData = Users::where('role_id',3);
    }

    public function dataTable()
    {
        $this->callPermission();

        //$userId = session()->get('admin_id');

        $dataTables = new DataTables();

        $builder = $this->model::query()->selectRaw('users.id, users.name, users.username as username, users.email, F.name as unit_kerja_id, B.name AS role, users.status')
            ->where('users.sekretariat', '=', 1)
            ->leftJoin('role AS B', 'B.id', '=', 'users.role_id')
            ->leftJoin('unit_kerja as F','F.id','=','users.unit_kerja_id');


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
            } else if (in_array($list['type'], ['money'])) {
                $dataTables = $dataTables->editColumn($fieldName, function ($query) use ($fieldName, $list, $listRaw) {
                    return number_format($query->$fieldName, 0);
                });
            } else if (in_array($list['type'], ['image'])) {
                $listRaw[] = $fieldName;
                $dataTables = $dataTables->editColumn($fieldName, function ($query) use ($fieldName, $list, $listRaw) {
                    return '<img src="' . asset($list['path'] . $query->$fieldName) . '" class="img-responsive max-image-preview"/>';
                });
            } else if (in_array($list['type'], ['image_preview'])) {
                $listRaw[] = $fieldName;
                $dataTables = $dataTables->editColumn($fieldName, function ($query) use ($fieldName, $list, $listRaw) {
                    return '<img src="' . $query->$fieldName . '" class="img-responsive max-image-preview"/>';
                });
            } else if (in_array($list['type'], ['code'])) {
                $listRaw[] = $fieldName;
                $dataTables = $dataTables->editColumn($fieldName, function ($query) use ($fieldName, $list, $listRaw) {
                    return '<pre>' . json_encode(json_decode($query->$fieldName, true), JSON_PRETTY_PRINT) . '"</pre>';
                });
            } else if (in_array($list['type'], ['texteditor'])) {
                $listRaw[] = $fieldName;
            }
        }

        return $dataTables
            ->rawColumns($listRaw)
            ->make(true);
    }

    public function store(){
        $this->callPermission();


        $viewType = 'create';
        $this->request->validate([
            'username' => 'required',
            'name' => 'required',
            'email' => 'required',

        ]);
        $getUsername = $this->request->get('username');
        $getName = $this->request->get('name');
        $getEmail = $this->request->get('email');
        $getUnitKerja = $this->request->get('unit_kerja_id');
        $getStatus = $this->request->get('status');

        $sekretariat = new Users();
        $sekretariat->name = $getName;
        $sekretariat->username = $getUsername;
        $sekretariat->email = $getEmail;
        $sekretariat->password = Hash::make('123');
        $sekretariat->unit_kerja_id = $getUnitKerja;
        $sekretariat->status = $getStatus;
        $sekretariat->role_id = 4;
        $sekretariat->sekretariat = 1;
        $sekretariat->save();

        if($this->request->ajax()){
            return response()->json(['result' => 1, 'message' => __('general.success_add')]);
        }
        else {
            session()->flash('message', __('general.success_add'));
            session()->flash('message_alert', 2);
            return redirect()->route('admin.' . $this->route . '.index');
        }


    }
}
