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

class PersetujuanPemuktahiranAKController extends _CrudController
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
                'create' => false,
                'lang' => 'NIP'
            ],
            'name' => [
                'create' => false,
            ],
            'tanggal_pak_terakhir' => [
                'create' => false,
                'type' => 'datepicker',
            ],
            'angka_kredit_terakhir' => [
                'create' => false,
                'type' => 'text',
            ],
            'nomor_pak_terakhir' => [
                'create' => false,
                'type' => 'text',

            ],

            'action' => [
                'create' => false,
                'edit' => false,
                'show' => 0,
                'lang' => 'Aksi',
            ]
        ];


        parent::__construct(
            $request, 'general.persetujuan-pemuktahiran-ak', 'persetujuan-pemuktahiran-ak', 'UpdateUsers', 'persetujuan-pemuktahiran-ak',
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
        $this->data['listSet']['upline_id'] = Users::where('atasan', 1)->pluck('name', 'id')->toArray();


        $this->listView['index'] = env('ADMIN_TEMPLATE').'.page.persetujuan-pemuktahiran-ak.list';
        $this->listView['show'] = env('ADMIN_TEMPLATE').'.page.persetujuan-pemuktahiran-ak.forms';
        $this->listView['create'] = env('ADMIN_TEMPLATE').'.page.persetujuan-pemuktahiran-ak.forms';
        $this->listView['dataTable'] = env('ADMIN_TEMPLATE').'.page.persetujuan-pemuktahiran-ak.list_button';

        $this->data['listSet']['status_pemuktahiran'] = get_list_status_permuktahiran();



    }

    public function show($id){
        $this->callPermission();

        $getData = $this->crud->show($id);

        $UserId =  $getData->user_id;
        //dd($UserId);
        $getDataOld = Users::where('id', $UserId)->first();
        $file = json_decode($getData->upload_file_pemuktahiran, true);
        if (!$getData) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        $data = $this->data;

        $data['viewType'] = 'show';
        $data['formsTitle'] = __('general.title_show', ['field' => $data['thisLabel']]);
        $data['passing'] = collectPassingData($this->passingData, $data['viewType']);
        $data['passing1'] = collectPassingData($this->passingData, $data['viewType']);
        $data['data'] = $getData;
        $data['dataOld'] = $getDataOld;
        $data['file'] = $file;
        $data['listSet']['upline_id'] = Users::where('id', '!=', $id)->where('atasan', 1)->pluck('name', 'id')->toArray();

        return view($this->listView[$data['viewType']], $data);
    }

    public function approve($id){
        $this->callPermission();
        $adminId = session()->get('admin_id');
        $getUsers = Users::where('id', $adminId)->first();
        //dd($getUsers->name);
        $getData = UpdateUsers::where('id', $id)->first();
        $User = Users::where('id', $getData->user_id)->first();

        $getAK = $getData->angka_kredit_terakhir;
        $getNomorPak = $getData->nomor_pak_terakhir;
        $getTanggalPak = $getData->tanggal_pak_terakhir;

        $getData->update([
            'status_pemuktahiran' => 80,
            'approved_id' => $getUsers->id,
            'approved_by' => $getUsers->name

        ]);

        $User->update([

            'tanggal_pak_terakhir' => $getTanggalPak,
            'nomor_pak_terakhir' => $getNomorPak,
            'angka_kredit_terakhir' => $getAK

        ]);


        if($this->request->ajax()){
            return response()->json(['result' => 1, 'message' => __('general.success_add')]);
        }
        else {
            session()->flash('message', __('general.success_approve_pemuktahiran'));
            session()->flash('message_alert', 2);
            return redirect()->route('admin.' . $this->route . '.index');
        }

    }

    public function reject($id){
        //dd($this->request->all());
        $this->callPermission();
        $adminId = session()->get('admin_id');
        $getUsers = Users::where('id', $adminId)->first();
        $getData = UpdateUsers::where('id', $id)->first();
        $message = $this->request->get('alasan');

        $getData->update([
            'status_pemuktahiran' => 99,
            'rejected_id' => $getUsers->id,
            'rejected_by' => $getUsers->name,
            'alasan' => $message

        ]);

        if($this->request->ajax()){
            return response()->json(['result' => 1, 'message' => __('general.success_add')]);
        }
        else {
            session()->flash('message', __('general.success_reject'));
            session()->flash('message_alert', 2);
            return redirect()->route('admin.' . $this->route . '.index');
        }
    }

    public function DataTable(){

        $this->callPermission();

        $userId = session()->get('admin_id');

        $dataTables = new DataTables();

        $builder = $this->model::query()->selectRaw('tx_update_users.id, tx_update_users.name, tx_update_users.username, tx_update_users.tanggal_pak_terakhir, tx_update_users.angka_kredit_terakhir, tx_update_users.nomor_pak_terakhir, tx_update_users.status_pemuktahiran')
            ->leftJoin('role AS B', 'B.id', '=', 'tx_update_users.role_id')
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
