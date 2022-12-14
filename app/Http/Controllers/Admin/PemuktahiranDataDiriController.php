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
use App\Codes\Models\UpdateUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PemuktahiranDataDiriController extends _CrudController
{
    public function __construct(Request $request)
    {
        $passingData = [
            'id' => [
                'create' => 0,
                'edit' => 0,
                'show' => 0
            ],

            'upline_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'lang' => 'general.atasan',
                'type' => 'select2'
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
            'tmt_jabatan' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'datepicker',
                'lang' => 'general.tmt_jabatan'
            ],
            'unit_kerja_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'general.unit_kerja'
            ],

            'status_pemuktahiran' => [
                'create' => false,
                'edit' => false,
                'type' => 'select'
            ],
            'action' => [
                'create' => false,
                'edit' => false,
                'show' => 0,
                'lang' => 'Aksi',
            ]
        ];

        parent::__construct(
            $request, 'general.pemuktahiran-data-diri', 'pemuktahiran-data-diri', 'UpdateUsers', 'pemuktahiran-data-diri',
            $passingData
        );


        $getGolongan = Golongan::where('status', 1)->pluck('name', 'id')->toArray();
        if($getGolongan) {
            foreach($getGolongan as $key => $value) {
                $listGolongan[$key] = $value;
            }
        }

        $getJenjangPerancang = JenjangPerancang::where('status', 1)->pluck('name', 'id')->toArray();
        if($getJenjangPerancang) {
            foreach($getJenjangPerancang as $key => $value) {
                $listJenjangPerancang[$key] = $value;
            }
        }

        $getPangkat = Pangkat::where('status', 1)->pluck('name', 'id')->toArray();
        if($getPangkat) {
            foreach($getPangkat as $key => $value) {
                $listPangkat[$key] = $value;
            }
        }

        $getUnitKerja = UnitKerja::where('status', 1)->pluck('name', 'id')->toArray();
        if($getUnitKerja) {
            foreach($getUnitKerja as $key => $value) {
                $listUnitKerja[$key] = $value;
            }
        }

        $this->data['listSet']['golongan_id'] = $listGolongan;
        $this->data['listSet']['jenjang_perancang_id'] = $listJenjangPerancang;
        $this->data['listSet']['pangkat_id'] = $listPangkat;
        $this->data['listSet']['unit_kerja_id'] = $listUnitKerja;
        $this->data['listSet']['upline_id'] = Users::where('atasan', 1)->pluck('name', 'id')->toArray();


        $this->listView['index'] = env('ADMIN_TEMPLATE').'.page.pemuktahiran-data-diri.list';
        $this->listView['show'] = env('ADMIN_TEMPLATE').'.page.pemuktahiran-data-diri.forms';
        $this->listView['create'] = env('ADMIN_TEMPLATE').'.page.pemuktahiran-data-diri.forms';
        $this->listView['dataTable'] = env('ADMIN_TEMPLATE').'.page.pemuktahiran-data-diri.list_button';

        $this->data['listSet']['status_pemuktahiran'] = get_list_status_permuktahiran();



    }
    public function index()
    {
        $this->callPermission();
        $userId = session()->get('admin_id');
        $getData = UpdateUsers::where('user_id', $userId)->where('flag_pemuktahiran',1)->orderBy('id','DESC')->first();
        $getStatusPemuktahiran = $getData ? $getData->status_pemuktahiran : '';

        $data = $this->data;

        $data['passing'] = collectPassingData($this->passingData);
        $data['status'] = $getStatusPemuktahiran;

        return view($this->listView['index'], $data);
    }

    public function create()
    {
        //ini
        $this->callPermission();

        $adminId = session()->get('admin_id');
        $getData = Users::where('id', $adminId)->first();
        if (!$getData) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        $data = $this->data;

        $data['thisLabel'] = __('general.title_show', ['field' => __('general.pemuktahiran') . ' ' . $getData->name]);
        $data['viewType'] = 'create';
        $data['formsTitle'] = __('general.title_edit', ['field' => __('general.pemuktahiran') . ' ' . $getData->name]);
        $data['passing'] = collectPassingData($this->passingData, $data['viewType']);
        $data['data'] = $getData;

        $data['listSet']['upline_id'] = Users::where('id', '!=', $adminId)->where('atasan', 1)->pluck('name', 'id')->toArray();

        return view($this->listView[$data['viewType']], $data);
    }

    //public function store(){
    //    dd($this->request);
    //}

    public function store(){
        $this->callPermission();


        $viewType = 'create';
        $this->request->validate([
            'upline_id' => 'required',
            'pangkat_id' => 'required',
            'golongan_id' => 'required',
            'unit_kerja_id' => 'required',

        ]);

        $adminId = session()->get('admin_id');
        $getData = Users::where('id', $adminId)->first();

        $getUsername = $getData->username;
        $getName = $getData->name;
        $getEmail = $getData->email;
        $getUpline = $this->request->get('upline_id');
        $getPangkat = $this->request->get('pangkat_id');
        $getGolongan = $this->request->get('golongan_id');
        $getTmtPangkat = $this->request->get('tmt_pangkat');
        $getJenjangPerancang = $this->request->get('jenjang_perancang_id');
        $getTmtJabatan = $this->request->get('tmt_jabatan');
        $getUnitKerja = $this->request->get('unit_kerja_id');
        $dokument = $this->request->file('upload_file_pemuktahiran');
        $getStatus = 1;
        $getStatusPemuktahiran = 1;
        $getGender =  $getData->gender;
        $getRole = $getData->role_id;
        $getPassword = $getData->password;
        $getUserId = $getData->id;


        $userFolder = 'user_' . preg_replace("/[^A-Za-z0-9?!]/", '', $getUsername);
        $todayDate = date('Y-m-d');
        $folderName = $userFolder . '/pemuktahiran/' . $todayDate . '/';
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


        $perancang = new UpdateUsers();
        $perancang->user_id = $getUserId;
        $perancang->name = $getName;
        $perancang->username = $getUsername;
        $perancang->email = $getEmail;
        $perancang->password = $getPassword;
        $perancang->upline_id =$getUpline;
        $perancang->pangkat_id = $getPangkat;
        $perancang->golongan_id = $getGolongan;
        $perancang->tmt_pangkat = $getTmtPangkat;
        $perancang->jenjang_perancang_id = $getJenjangPerancang;
        $perancang->tmt_jabatan = $getTmtJabatan;
        $perancang->unit_kerja_id = $getUnitKerja;
        $perancang->status = $getStatus;
        $perancang->gender = $getGender;
        $perancang->role_id = $getRole;
        $perancang->status_pemuktahiran = $getStatusPemuktahiran;
        $perancang->flag_pemuktahiran = 1;
        $perancang->upload_file_pemuktahiran = json_encode($totalDokument);
        $perancang->save();

        if($this->request->ajax()){
            return response()->json(['result' => 1, 'message' => __('general.success_add')]);
        }
        else {
            session()->flash('message', __('general.success_add'));
            session()->flash('message_alert', 2);
            return redirect()->route('admin.' . $this->route . '.index');
        }

    }

    public function show($id){
        $this->callPermission();

        $getData = $this->crud->show($id);

        $file = json_decode($getData->upload_file_pemuktahiran, true);
        if (!$getData) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        $data = $this->data;

        $data['viewType'] = 'show';
        $data['formsTitle'] = __('general.title_show', ['field' => $data['thisLabel']]);
        $data['passing'] = collectPassingData($this->passingData, $data['viewType']);
        $data['data'] = $getData;
        $data['file'] = $file;
        $data['listSet']['upline_id'] = Users::where('id', '!=', $id)->where('atasan', 1)->pluck('name', 'id')->toArray();

        return view($this->listView[$data['viewType']], $data);
    }

    public function dataTable()
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $dataTables = new DataTables();

        $builder = $this->model::query()->selectRaw('tx_update_users.id, tx_update_users.upline_id, C.name AS pangkat_id, D.name as golongan_id, E.name as jenjang_perancang_id, F.name as unit_kerja_id, tx_update_users.tmt_pangkat, tx_update_users.tmt_jabatan, tx_update_users.status_pemuktahiran')
            ->leftJoin('role AS B', 'B.id', '=', 'tx_update_users.role_id')
            ->leftJoin('pangkat AS C', 'C.id', '=', 'tx_update_users.pangkat_id')
            ->leftJoin('golongan as D', 'D.id','=', 'tx_update_users.golongan_id')
            ->leftJoin('jenjang_perancang as E','E.id','=','tx_update_users.jenjang_perancang_id')
            ->leftJoin('unit_kerja as F','F.id','=','tx_update_users.unit_kerja_id')
            ->where('user_id', $userId)
            ->where('flag_pemuktahiran', 1);




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



}
