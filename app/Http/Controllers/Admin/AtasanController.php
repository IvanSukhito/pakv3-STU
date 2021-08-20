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

class AtasanController extends _CrudController
{
    public function __construct(Request $request)
    {
        $passingData = [
            'id' => [
                'create' => 0,
                'edit' => 0,
                'show' => 0
            ],
            'username' => [
                'extra' => [
                    'edit' => ['disabled' => true]
                ],
                'lang' => 'NIP'
            ],
            'name' => [
                'validation' => [
                    'edit' => 'required'
                ]
            ],
            'email' => [
                'validation' => [
                    'edit' => 'required|email'
                ],
                'type' => 'email'
            ],
            'role' => [
                'create' => false,
                'edit' => false,
                'show' => 0,
            ],
            'pangkat_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'general.pangkat'
            ],
            'golongan_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'general.golongan'
            ],
            'tmt_pangkat' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'datepicker',
                'lang' => 'general.kenaikan_jenjang_terakhir'
            ],
            'jenjang_perancang_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'general.jenjang_perancang'
            ],
            'unit_kerja_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'general.unit_kerja'
            ],
            'gender' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select',
                'lang' => 'general.jenis_kelamin'
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
            $request, 'general.atasan', 'atasan', 'Users', 'atasan',
            $passingData
        );
        $getGolongan = Golongan::where('status', 1)->pluck('name', 'id')->toArray();
        $listGolongan = [0 => 'Kosong'];
        if($getGolongan) {
            foreach($getGolongan as $key => $value) {
                $listGolongan[$key] = $value;
            }
        }

        $getJenjangPerancang = JenjangPerancang::where('status', 1)->pluck('name', 'id')->toArray();
        $listJenjangPerancang = [0 => 'Kosong'];
        if($getJenjangPerancang) {
            foreach($getJenjangPerancang as $key => $value) {
                $listJenjangPerancang[$key] = $value;
            }
        }

        $getPangkat = Pangkat::where('status', 1)->pluck('name', 'id')->toArray();
        $listPangkat = [0 => 'Kosong'];
        if($getPangkat) {
            foreach($getPangkat as $key => $value) {
                $listPangkat[$key] = $value;
            }
        }

        $getUnitKerja = UnitKerja::where('status', 1)->pluck('name', 'id')->toArray();
        $listUnitKerja = [0 => 'Kosong'];
        if($getUnitKerja) {
            foreach($getUnitKerja as $key => $value) {
                $listUnitKerja[$key] = $value;
            }
        }

        $this->data['listSet']['golongan_id'] = $listGolongan;
        $this->data['listSet']['jenjang_perancang_id'] = $listJenjangPerancang;
        $this->data['listSet']['pangkat_id'] = $listPangkat;
        $this->data['listSet']['unit_kerja_id'] = $listUnitKerja;
        $this->data['listSet']['status'] = get_list_status();
        $this->data['listSet']['gender'] = get_list_gender();
        $this->listView['index'] = env('ADMIN_TEMPLATE') . '.page.atasan.list';
        $this->listView['show'] = env('ADMIN_TEMPLATE') . '.page.atasan.forms';
    }

    public function show($id)
    {
        $this->callPermission();

        $getData = $this->crud->show($id);
        if (!$getData) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        $getPerancangData = Users::selectRaw('users.id, users.name, users.username as username, users.email, users.upline_id, users.gender, C.name AS pangkat_id, D.name as golongan_id, E.name as jenjang_perancang_id, F.name as unit_kerja_id, B.name AS role, users.status')
            ->where('users.perancang', '=', 1)
            ->where('users.upline_id','=',$id)
            ->leftJoin('role AS B', 'B.id', '=', 'users.role_id')
            ->leftJoin('pangkat AS C', 'C.id', '=', 'users.pangkat_id')
            ->leftJoin('golongan as D', 'D.id','=', 'users.golongan_id')
            ->leftJoin('jenjang_perancang as E','E.id','=','users.jenjang_perancang_id')
            ->leftJoin('unit_kerja as F','F.id','=','users.unit_kerja_id')->get();

        $data = $this->data;

        $data['viewType'] = 'show';
        $data['formsTitle'] = __('general.title_show', ['field' => $data['thisLabel']]);
        $data['passing'] = collectPassingData($this->passingData, $data['viewType']);
        $data['data'] = $getData;
        $data['getPerancangData'] = $getPerancangData;

        return view($this->listView[$data['viewType']], $data);
    }

    public function dataTable()
    {
        $this->callPermission();

        //$userId = session()->get('admin_id');

        $dataTables = new DataTables();

        $builder = $this->model::query()->selectRaw('users.id, users.name, users.username as username, users.email, C.name AS pangkat_id, D.name as golongan_id, E.name as jenjang_perancang_id, F.name as unit_kerja_id, B.name AS role,users.gender, users.status,users.tmt_pangkat')
            ->where('users.atasan', '=', 1)
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
        $getPangkat = $this->request->get('pangkat_id');
        $getGolongan = $this->request->get('golongan_id');
        $getJenjangPerancang = $this->request->get('jenjang_perancang_id');
        $getKenaikanJenjangTerakhir = $this->request->get('tmt_kenaikan_jenjang_terakhir');
        $getUnitKerja = $this->request->get('unit_kerja_id');
        $getStatus = $this->request->get('status');
        $getGender = $this->request->get('gender');

        $atasan = new Users();
        $atasan->name = $getName;
        $atasan->username = $getUsername;
        $atasan->email = $getEmail;
        $atasan->password = Hash::make('123');
        $atasan->pangkat_id = $getPangkat;
        $atasan->golongan_id = $getGolongan;
        $atasan->jenjang_perancang_id = $getJenjangPerancang;
        $atasan->tmt_kenaikan_jenjang_terakhir = $getKenaikanJenjangTerakhir;
        $atasan->unit_kerja_id = $getUnitKerja;
        $atasan->status = $getStatus;
        $atasan->gender = $getGender;
        $atasan->role_id = 2;
        $atasan->atasan = 1;
        $atasan->save();

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
