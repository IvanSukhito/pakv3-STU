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

class PersetujuanPemuktahiranController extends _CrudController
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
            $request, 'general.persetujuan-pemuktahiran', 'persetujuan-pemuktahiran', 'UpdateUsers', 'persetujuan-pemuktahiran',
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
        $this->data['listSet']['upline_id'] = Users::where('atasan', 1)->pluck('name', 'id')->toArray();


        $this->listView['index'] = env('ADMIN_TEMPLATE').'.page.persetujuan-pemuktahiran.list';
        $this->listView['show'] = env('ADMIN_TEMPLATE').'.page.persetujuan-pemuktahiran.forms';
        $this->listView['create'] = env('ADMIN_TEMPLATE').'.page.persetujuan-pemuktahiran.forms';
        $this->listView['dataTable'] = env('ADMIN_TEMPLATE').'.page.persetujuan-pemuktahiran.list_button';

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

        $getUplineId = $getData->upline_id;
        $getPangkat = $getData->pangkat_id;
        $getJenjangPerancang = $getData->jenjang_perancang_id;
        $getUnitKerja = $getData->unit_kerja_id;
        $getGolongan = $getData->golongan_id;
        $getTmtJabatan = $getData->tmt_jabatan;
        $getTmtPangkat = $getData->tmt_pangkat;


        $getData->update([
            'status_pemuktahiran' => 80,
            'approved_id' => $getUsers->id,
            'approved_by' => $getUsers->name

        ]);

        $User->update([
            'upline_id' => $getUplineId,
            'pangkat_id' => $getPangkat,
            'golongan_id' => $getGolongan,
            'jenjang_perancang_id' => $getJenjangPerancang,
            'unit_kerja_id' => $getUnitKerja,
            'tmt_jabatan' => $getTmtJabatan,
            'tmt_pangkat' => $getTmtPangkat

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

        $builder = $this->model::query()->selectRaw('tx_update_users.id, tx_update_users.name, tx_update_users.username, tx_update_users.upline_id, C.name AS pangkat_id, D.name as golongan_id, E.name as jenjang_perancang_id, F.name as unit_kerja_id, tx_update_users.tmt_pangkat, tx_update_users.tmt_jabatan, tx_update_users.status_pemuktahiran')
        ->leftJoin('role AS B', 'B.id', '=', 'tx_update_users.role_id')
        ->leftJoin('pangkat AS C', 'C.id', '=', 'tx_update_users.pangkat_id')
        ->leftJoin('golongan as D', 'D.id','=', 'tx_update_users.golongan_id')
        ->leftJoin('jenjang_perancang as E','E.id','=','tx_update_users.jenjang_perancang_id')
        ->leftJoin('unit_kerja as F','F.id','=','tx_update_users.unit_kerja_id')
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
