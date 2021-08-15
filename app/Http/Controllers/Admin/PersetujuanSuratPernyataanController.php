<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_CrudController;
use App\Codes\Logic\PakLogic;
use App\Codes\Models\JabatanPerancang;
use App\Codes\Models\Kegiatan;
use App\Codes\Models\MsKegiatan;

use App\Codes\Models\Permen;
use App\Codes\Models\SuratPernyataan;
use App\Codes\Models\JenjangPerancang;
use App\Codes\Models\UnitKerja;
use App\Codes\Models\Users;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PDF;

class PersetujuanSuratPernyataanController extends _CrudController
{
    public function __construct(Request $request)
    {
        $passingData = [
            'username' => [
                'lang' => 'general.nip'
            ],
            'name' => [
            ],
            'kegiatan' => [
                'custom' => ', name:"ms_kegiatan.name"'
            ],
            'action' => [
                'create' => 0,
                'edit' => 0,
                'show' => 0,
                'lang' => 'Aksi',
            ]
        ];

        parent::__construct(
            $request, 'general.persetujuan_surat_pernyataan', 'persetujuan-surat-pernyataan', 'SuratPernyataan', 'persetujuan-surat-pernyataan',
            $passingData
        );

        $this->listView['edit'] = env('ADMIN_TEMPLATE').'.page.persetujuan_surat_pernyataan.forms';

        $this->data['listSet']['approved'] = get_list_status_pak();

    }

    public function dataTable()
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $dataTables = new DataTables();

        $builder = Users::selectRaw('tx_surat_pernyataan.id, users.username, users.name, ms_kegiatan.name AS kegiatan')
            ->join('tx_surat_pernyataan', 'tx_surat_pernyataan.user_id', '=', 'users.id')
            ->join('ms_kegiatan', 'ms_kegiatan.id', '=', 'tx_surat_pernyataan.top_kegiatan_id')
            ->where('users.upline_id', $userId)
            ->whereIn('tx_surat_pernyataan.status', [1,2]);

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

        $getSuratPernyataan = SuratPernyataan::where('id', $id)->whereIn('status', [1,2])->first();
        $getPerancang = Users::where('id', $getSuratPernyataan->user_id)->where('upline_id', $userId)->first();

        $getJenjangPerancang = JenjangPerancang::where('status', 1)->orderBy('order_high', 'ASC')->get();

        $getNewLogic = new PakLogic();
        $getData = $getNewLogic->getSuratPernyataanUser($getSuratPernyataan->user_id, $getSuratPernyataan);

        $dataPermen = [];
        $dataKegiatan = [];
        $dataTopKegiatan = [];
        $getFilterKegiatan = [];
        $totalPermen = 0 ;
        $totalTop = 0 ;
        $totalAk = 0 ;

        if (count($getData['data']) > 0) {
            $totalPermen = count($getData['total_permen']);
            $totalTop = count($getData['total_top']);
            $totalAk = $getData['total_ak'];
            $dataPermen = $getData['permen'];
            $dataKegiatan = $getData['data'];
            $dataTopKegiatan = $getData['top_kegiatan'];
        }

        $data = $this->data;

        $data['viewType'] = 'edit';
        $data['formsTitle'] = __('general.title_edit', ['field' => $data['thisLabel']]);
        $data['passing'] = collectPassingData($this->passingData, $data['viewType']);
        $data['data'] = $getSuratPernyataan;
        $data['dataUser'] = $getPerancang;
        $data['dataJenjangPerancang'] = $getJenjangPerancang;
        $data['dataPermen'] = $dataPermen;
        $data['dataFilterKegiatan'] = $getFilterKegiatan;
        $data['dataKegiatan'] = $dataKegiatan;
        $data['dataTopKegiatan'] = $dataTopKegiatan;
        $data['totalPermen'] = $totalPermen;
        $data['totalTop'] = $totalTop;
        $data['totalAk'] = $totalAk;

        return view($this->listView[$data['viewType']], $data);
    }

    public function update($id)
    {
        $this->callPermission();

        $viewType = 'edit';

        $getData = $this->crud->show($id);
        if (!$getData) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
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

        foreach ($getListCollectData as $key => $val) {
            if($val['type'] == 'image_many') {
                $getStorage = explode(',', $this->request->get($key.'_storage')) ?? [];
                $getOldData = json_decode($getData->$key, true);
                $tempData = [];
                if ($getOldData) {
                    foreach ($getOldData as $index => $value) {
                        if (in_array($index, $getStorage)) {
                            $tempData[] = $value;
                        }
                    }
                }
                if (isset($data[$key])) {
                    foreach (json_decode($data[$key], true) as $index => $value) {
                        $tempData[] = $value;
                    }
                }
                $data[$key] = json_encode($tempData);
            }
        }

        $getData = $this->crud->update($data, $id);

        $id = $getData->id;

        if($this->request->ajax()){
            return response()->json(['result' => 1, 'message' => __('general.success_edit_', ['field' => $this->data['thisLabel']])]);
        }
        else {
            session()->flash('message', __('general.success_edit_', ['field' => $this->data['thisLabel']]));
            session()->flash('message_alert', 2);
            return redirect()->route($this->rootRoute.'.' . $this->route . '.show', $id);
        }
    }

    protected function createPDF($id, $superVisorId)
    {
        $getData = SuratPernyataan::where('id', $id)->first();
        if($getData) {
            $userId = $getData->user_id;

            $superVisorUser = Users::where('id', $superVisorId)->first();
           // $superVisorStaff = Staffs::where('user_id', $superVisorId)->first();

            $user = Users::where('id', $userId)->first();
            $staff = Users::where('id', $userId)->first();

            $getJenjangPerancang = JenjangPerancang::whereIn('id', [$staff->jenjang_perancang_id, $superVisorStaff->jenjang_perancang_id])->pluck('id', 'name')->toArray();
            $getJabatanPerancang = JabatanPerancang::whereIn('id', [$staff->jabatan_perancang_id, $superVisorStaff->jabatan_perancang_id])->pluck('id', 'name')->toArray();
            $getUnitKerja = UnitKerja::where('id', $staff->unit_kerja_id)->first();

            $userNip = $user->username;

            $surat_pernyataan_info = SuratPernyataanInfo::where('ms_kegiatan_id', $getData->parent_id)->first();
            $kegiatan = $getData->getKegiatan()->orderBy('tanggal', 'ASC')->get();

            $user_folder = 'user_'.preg_replace("/[^A-Za-z0-9?!]/",'', $userNip);
            $today_date = date('Y-m-d');
            $folder_name = $user_folder.'/surat_pernyataan/'.$today_date.'/';
            $set_file_name = 'surat_pernyataan_'.date('His').rand(10,99).'.pdf';
            $folder_path = './uploads/'.$folder_name;
            $destinationPath = './uploads/'.$folder_name.$set_file_name;
            $destinationLink = 'uploads/'.$folder_name.$set_file_name;

            if(!file_exists($folder_path)) {
                mkdir($folder_path, 755, true);
            }

            $data = [
                'title' => $getUnitKerja ? $getUnitKerja->name : '',
                'superVisorUser' => $superVisorUser,
                'superVisorStaff' => $superVisorStaff,
                'superVisorJenjangPerancang' => isset($getJenjangPerancang[$superVisorStaff->jenjang_perancang_id]) ? $getJenjangPerancang[$superVisorStaff->jenjang_perancang_id] : '',
                'superVisorJabatanPerancang' => isset($getJabatanPerancang[$superVisorStaff->jabatan_perancang_id]) ? $getJabatanPerancang[$superVisorStaff->jabatan_perancang_id] : '',
                'user' => $user,
                'staff' => $staff,
                'staffJenjangPerancang' => isset($getJenjangPerancang[$staff->jenjang_perancang_id]) ? $getJenjangPerancang[$staff->jenjang_perancang_id] : '',
                'staffJabatanPerancang' => isset($getJabatanPerancang[$staff->jabatan_perancang_id]) ? $getJabatanPerancang[$staff->jabatan_perancang_id] : '',
                'surat_pernyataan' => $getData,
                'surat_pernyataan_info' => $surat_pernyataan_info,
                'kegiatan' => $kegiatan,
            ];

            $pdf = PDF::loadView('pdf.surat_pernyataan', $data);
            $pdf->save($destinationPath);
            return [
                'name' => $set_file_name,
                'location' => $destinationLink,
            ];

        }

        return false;

    }

    protected function generatePDF($id) {

        $getData = SuratPernyataan::where('id', $id)->first();
        if($getData) {

            $userId = $getData->user_id;

            $staff = Users::where('id', $userId)->first();
            $user = Users::where('id', $userId)->first();
            $staffJenjangPerancang = $staff->getJenjangPerancang()->first();
            $staffJabatanPerancang = $staff->getJabatanPerancang()->first();
            $staffUnitKerja = $staff->getUnitKerja()->first();
            $atasan_staff = Staffs::where('user_id', $getData->supervisor_id)->first();
            $atasan_user = false;
            $atasanJenjangPerancang = false;
            $atasanJabatanPerancang = false;
            $atasanUnitKerja = false;
            if ($atasan_staff) {
                $atasan_user = Users::where('id', $atasan_staff->user_id)->first();
                $atasanJenjangPerancang = $staff->getJenjangPerancang()->first();
                $atasanJabatanPerancang = $staff->getJabatanPerancang()->first();
                $atasanUnitKerja = $staff->getUnitKerja()->first();
            }

            $surat_pernyataan_info = SuratPernyataanInfo::where('ms_kegiatan_id', $getData->parent_id)->first();
            $kegiatan = $getData->getKegiatan()->orderBy('tanggal', 'ASC')->get();

            $getUrl = explode('/', $getData->pdf_url);
            $total = count($getUrl) - 2;
            $oldPath = '';
            foreach ($getUrl as $index => $list) {
                if ($index >= $total)
                    continue;
                $oldPath .= '/'.$list;
            }

            $folder_path = '.'.$oldPath;
            $destinationPath = './'.$getData->pdf_url;

            if(!file_exists($folder_path)) {
                mkdir($folder_path, 755, true);
            }

            $data = [
                'surat_pernyataan' => $getData,
                'surat_pernyataan_info' => $surat_pernyataan_info,
                'staff' => $staff,
                'user' => $user,
                'staffJenjangPerancang' => $staffJenjangPerancang,
                'staffJabatanPerancang' => $staffJabatanPerancang,
                'staffUnitKerja' => $staffUnitKerja,
                'atasan_staff' => $atasan_staff,
                'atasan_user' => $atasan_user,
                'atasanJenjangPerancang' => $atasanJenjangPerancang,
                'atasanJabatanPerancang' => $atasanJabatanPerancang,
                'atasanUnitKerja' => $atasanUnitKerja,
                'kegiatan' => $kegiatan,
            ];

            $pdf = PDF::loadView('pdf.surat_pernyataan', $data);
            $pdf->save($destinationPath);

        }

    }

    public function getKegiatan($id)
    {
        $userId = session()->get('admin_id');

        $ajax = Kegiatan::where("permen_id",$id)
            ->where("user_id", $userId)
            ->pluck("judul","id");
        return response()->json($ajax);
    }

}
