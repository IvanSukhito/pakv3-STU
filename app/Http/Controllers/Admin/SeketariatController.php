<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_CrudController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Codes\Models\Users;
use App\Codes\Models\Golongan;
use App\Codes\Models\Pangkat;
use App\Codes\Models\UnitKerja;
use App\Codes\Models\JenjangPerancang;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class SeketariatController extends _CrudController
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
                ]
            ],
            'role' => [
                'create' => false,
                'edit' => false
            ],
            'email' => [
                'validation' => [
                    'edit' => 'required|email'
                ],
                'type' => 'email'
            ],
            'pangkat' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'general.pangkat'
            ],
            'golongan' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'general.golongan'
            ],
            'jenjang_perancang' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'general.jenjang_perancang'
            ],
            'unit_kerja' => [
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
            $request, 'general.seketariat', 'seketariat', 'Users', 'seketariat',
            $passingData
        );
        $getGolongan = Golongan::get();
        $listGolongan = [0 => 'Kosong'];
        if($getGolongan) {
            foreach($getGolongan as $list) {
                $listGolongan[$list->id] = $list->name;
            }
        }

        $getJenjangPerancang = JenjangPerancang::get();
        $listJenjangPerancang = [0 => 'Kosong'];
        if($getJenjangPerancang) {
            foreach($getJenjangPerancang as $list) {
                $listJenjangPerancang[$list->id] = $list->name;
            }
        }

        $getPangkat = Pangkat::get();
        $listPangkat = [0 => 'Kosong'];
        if($getPangkat) {
            foreach($getPangkat as $list) {
                $listPangkat[$list->id] = $list->name;
            }
        }

        $getUnitKerja = UnitKerja::get();
        $listUnitKerja = [0 => 'Kosong'];
        if($getUnitKerja) {
            foreach($getUnitKerja as $list) {
                $listUnitKerja[$list->id] = $list->name;
            }
        }

        $this->data['listSet']['golongan'] = $listGolongan;
        $this->data['listSet']['jenjang_perancang'] = $listJenjangPerancang;
        $this->data['listSet']['pangkat'] = $listPangkat;
        $this->data['listSet']['unit_kerja'] = $listUnitKerja;
        $this->data['listSet']['status'] = get_list_status();
        $this->listView['index'] = env('ADMIN_TEMPLATE') . '.page.seketariat.list';
        //$this->passingData = Users::where('role_id',3);
    }

    public function dataTable()
    {
        $this->callPermission();

        //$userId = session()->get('admin_id');

        $dataTables = new DataTables();

        $builder = $this->model::query()->selectRaw('users.id, users.name, users.username as username, users.email, C.name AS pangkat, D.name as golongan, E.name as jenjang_perancang, F.name as unit_kerja, B.name AS role, users.status')
            ->where('users.role_id', '=', 4)
            ->leftJoin('role AS B', 'B.id', '=', 'users.role_id')
            ->leftJoin('pangkat AS C', 'C.id', '=', 'users.pangkat_id')
            ->leftJoin('golongan as D', 'D.id','=', 'users.golongan_id')
            ->leftJoin('jenjang_perancang as E','E.id','=','users.jenjang_perancang_id')
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
        $getPangkat = $this->request->get('pangkat');
        $getGolongan = $this->request->get('golongan');
        $getJenjangPerancang = $this->request->get('jenjang_perancang');
        $getUnitKerja = $this->request->get('unit_kerja');
        $getStatus = $this->request->get('status');

        $seketariat = new Users();
        $seketariat->name = $getName;
        $seketariat->username = $getUsername;
        $seketariat->email = $getEmail;
        $seketariat->password = Hash::make('123');
        $seketariat->pangkat_id = $getPangkat;
        $seketariat->golongan_id = $getGolongan;
        $seketariat->jenjang_perancang_id = $getJenjangPerancang;
        $seketariat->unit_kerja_id = $getUnitKerja;
        $seketariat->status = $getStatus;
        $seketariat->role_id = 3;
        $seketariat->save();

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
