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

class PemuktahiranAKController extends _CrudController
{
    public function __construct(Request $request)
    {
        $passingData = [
            'id' => [
                'create' => 0,
                'edit' => 0,
                'show' => 0
            ],

            'tanggal_pak_terakhir' => [

                'type' => 'datepicker',
            ],
            'angka_kredit_terakhir' => [

                'type' => 'text',
            ],
            'nomor_pak_terakhir' => [

                'type' => 'text',

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
            $request, 'general.pemuktahiran-ak', 'pemuktahiran-ak', 'UpdateUsers', 'pemuktahiran-ak',
            $passingData
        );



        $this->listView['index'] = env('ADMIN_TEMPLATE').'.page.pemuktahiran-ak.list';
        $this->listView['show'] = env('ADMIN_TEMPLATE').'.page.pemuktahiran-ak.forms';
        $this->listView['create'] = env('ADMIN_TEMPLATE').'.page.pemuktahiran-ak.forms';
        $this->listView['dataTable'] = env('ADMIN_TEMPLATE').'.page.pemuktahiran-ak.list_button';

        $this->data['listSet']['status_pemuktahiran'] = get_list_status_permuktahiran();



    }
    public function index()
    {
        $this->callPermission();
        $userId = session()->get('admin_id');
        $getData = UpdateUsers::where('user_id', $userId)->where('flag_pemuktahiran',2)->orderBy('id','DESC')->first();
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


        $adminId = session()->get('admin_id');
        $getData = Users::where('id', $adminId)->first();

        $getUsername = $getData->username;
        $getName = $getData->name;
        $getEmail = $getData->email;
        $getTanggal = $this->request->get('tanggal_pak_terakhir');
        $getAK = $this->request->get('angka_kredit_terakhir');
        $getNomorPak = $this->request->get('nomor_pak_terakhir');

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
        $perancang->nomor_pak_terakhir = $getNomorPak;
        $perancang->angka_kredit_terakhir = $getAK;
        $perancang->tanggal_pak_terakhir = $getTanggal;
        $perancang->status = $getStatus;
        $perancang->gender = $getGender;
        $perancang->role_id = $getRole;
        $perancang->status_pemuktahiran = $getStatusPemuktahiran;
        $perancang->flag_pemuktahiran = 2;
        $perancang->perancang = 1;
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

        $builder = $this->model::query()->selectRaw('tx_update_users.id, tx_update_users.tanggal_pak_terakhir, tx_update_users.angka_kredit_terakhir, tx_update_users.nomor_pak_terakhir, tx_update_users.status_pemuktahiran')
            ->leftJoin('role AS B', 'B.id', '=', 'tx_update_users.role_id')
            ->where('user_id', $userId)
            ->where('flag_pemuktahiran', 2);



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
