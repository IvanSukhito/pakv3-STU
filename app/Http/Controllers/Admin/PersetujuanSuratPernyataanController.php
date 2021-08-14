<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_CrudController;
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
            ],
            'name' => [
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

        $this->listView['index'] = env('ADMIN_TEMPLATE').'.page.surat_pernyataan.list';
        $this->listView['create'] = env('ADMIN_TEMPLATE').'.page.surat_pernyataan.forms';
        $this->listView['edit'] = env('ADMIN_TEMPLATE').'.page.surat_pernyataan.forms';
        $this->listView['show'] = env('ADMIN_TEMPLATE').'.page.surat_pernyataan.forms';
        $this->listView['dataTable'] = env('ADMIN_TEMPLATE').'.page.surat_pernyataan.list_button';

        $this->data['listSet']['approved'] = get_list_status_pak();

    }

    public function dataTable()
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $dataTables = new DataTables();

        $builder = Users::selectRaw('users.id, users.username, users.name, COUNT(tx_surat_pernyataan.id) AS total_sp')
            ->join('tx_surat_pernyataan', 'tx_surat_pernyataan.user_id', '=', 'users.id')
//            ->where('users.upline_id', $userId)
//            ->groupByRaw('users.id, users.username, users.name')
//            ->having('total_sp', '>', 0)
            ->get();
        dd($builder);

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

    public function create()
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $getStaff = Users::where('id', $userId)->first();

        $getFilterPermen = Permen::where('status', 1)->pluck('name', 'id')->toArray();
        $getKegiatanData = Kegiatan::where('user_id', $userId)->where('connect', 0)->where('permen_id', '>', 0)->get();
       // dd($getKegiatanData);
        $getMsKegiatanParentIds = [];
        $list_kegiatan_ids = [];
        $temp = [];
        foreach ($getKegiatanData as $list) {
            $getMsKegiatanParentIds[] = $list->parent_id;
            $list_kegiatan_ids[] = $list->ms_kegiatan_id;
            $temp[$list->ms_kegiatan_id][] = $list;
        }
        $getKegiatanData = $temp;
        //dd($getKegiatanData);
        //dd($getMsKegiatanParentIds);
        $getMsKegiatan = MsKegiatan::whereIn('id', $getMsKegiatanParentIds)->where('status', 1)->where('permen_id', '>', 0)->get();
       //dd($getMsKegiatan);
        $getFilterKegiatan = [];
        foreach ($getMsKegiatan as $list) {
            if ($list->parent_id > 0) {
                continue;
            }
            $getFilterKegiatan[$list->permen_id][$list->id] = $list->name;
        }

        $temp = [];
        foreach ($getFilterKegiatan as $indexPermen => $listKegiatan) {
            $temp2 = [];
            foreach ($listKegiatan as $kegiatanId => $kegiatanName) {
                $temp2[] = [
                    'id' => $kegiatanId,
                    'name' => $kegiatanName
                ];
            }

            $temp[] = [
                'id' => $indexPermen,
                'data' => $temp2
            ];

        }
        $getFilterKegiatan = $temp;

       // dd($getFilterKegiatan);
        $getKegiatan = MsKegiatan::get_all_child_from_ids($list_kegiatan_ids);
        //dd($getKegiatan);
        $getJenjangPerancang = JenjangPerancang::where('status', 1)->orderBy('order_high', 'ASC')->get();

        $data = $this->data;

        $data['viewType'] = 'create';
        $data['formsTitle'] = __('general.title_create', ['field' => $data['thisLabel']]);
        $data['passing'] = collectPassingData($this->passingData, $data['viewType']);

        $data['dataJenjangPerancang'] = $getJenjangPerancang;
        $data['dataKegiatan'] = $getKegiatan;
        $data['dataKegiatanData'] = $getKegiatanData;
        $data['dataFilterPermen'] = $getFilterPermen;
        $data['dataFilterKegiatan'] = $getFilterKegiatan;
        $data['getStaff'] = $getStaff;

        return view($this->listView[$data['viewType']], $data);
    }

    public function edit($id)
    {
        $this->callPermission();

        $getData = $this->crud->show($id);
        if (!$getData) {
            return redirect()->route('admin.' . $this->route . '.index');
        }

        $get_kegiatan = $getData->getKegiatan()->get();

        $list_kegiatan = [];
        $ms_kegiatan = [];
        if($get_kegiatan) {
            $ids = [];
            foreach($get_kegiatan as $list) {
                $ids[] = $list->ms_kegiatan_id;
                $list_kegiatan[$list->ms_kegiatan_id][] = $list;
            }

            $ms_kegiatan = MsKegiatan::get_all_child_from_ids($ids);

        }

        $data = $this->data;

        $data['viewType'] = 'edit';
        $data['formsTitle'] = __('general.title_edit', ['field' => $data['thisLabel']]);
        $data['passing'] = collectPassingData($this->passingData, $data['viewType']);
        $data['data'] = $getData;
        $data['msKegiatan'] = $ms_kegiatan;
        $data['listKegiatanTotal'] = $list_kegiatan;
        $data['listDataKegiatan'] = [];

        return view($this->listView[$data['viewType']], $data);
    }

    public function show($id)
    {
        $this->callPermission();

        $getData = $this->crud->show($id);
        if (!$getData) {
            return redirect()->route('admin.' . $this->route . '.index');
        }

        $get_kegiatan = $getData->getKegiatan()->get();
        $list_kegiatan = [];
        $ms_kegiatan = [];
        if($get_kegiatan) {
            $ids = [];
            foreach($get_kegiatan as $list) {
                $ids[] = $list->ms_kegiatan_id;
                $list_kegiatan[$list->ms_kegiatan_id][] = $list;
            }

            $ms_kegiatan = MsKegiatan::get_all_child_from_ids($ids);

        }

        $data = $this->data;

        $data['viewType'] = 'show';
        $data['formsTitle'] = __('general.title_show', ['field' => $data['thisLabel']]);
        $data['passing'] = collectPassingData($this->passingData, $data['viewType']);
        $data['data'] = $getData;
        $data['msKegiatan'] = $ms_kegiatan;
        $data['listKegiatanTotal'] = $list_kegiatan;
        $data['listDataKegiatan'] = [];

        return view($this->listView[$data['viewType']], $data);
    }

    public function store()
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $filterPermen = $this->request->get('filter_permen');
        $filterKegiatan = $this->request->get('filter_kegiatan');

        $getKegiatanData = Kegiatan::where('permen_id', $filterPermen)->where('parent_id', $filterKegiatan)
            ->where('user_id', $userId)->where('connect', 0)->get();

        if (!$getKegiatanData) {
            return redirect()->back()->withInput()->withErrors(
                [
                    'filter_permen' => 'Kategori Kegiatan Tidak ditemukan'
                ]
            );
        }

        $getKredit = 0;
        foreach ($getKegiatanData as $list) {
            $getKredit += $list->kredit;
        }

        $userId = session()->get('admin_id');

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

        $getParentId = $this->request->get('parent_id');
//        dd($getParentId);
        $kegiatanIds = [];
        $get_kegiatan = Kegiatan::where('user_id', $userId)->where('connect', 0)->get();

        if(!$get_kegiatan) {
            return redirect()->back()->withInput()->withErrors(
                [
                    'kegiatan_id' => 'Kategori Kegiatan Tidak ditemukan'
                ]
            );
        }
        foreach ($get_kegiatan as $list) {
            $kegiatanIds[] = $list->id;
        }

        $data = $this->getCollectedData($getListCollectData, $viewType, $data);
//        $data['created_by'] = session()->get('admin_name');

        $staff = Users::where('id' ,$userId)->first();

        $data['parent_id'] = $getParentId;
        $data['user_id'] = $userId;
        $data['supervisor_id'] = $staff->staff_id;
        $data['total_kredit'] = $getKredit;

        $getData = $this->crud->store($data);

        $id = $getData->id;

        $getKegiatanData = Kegiatan::where('permen_id', $filterPermen)->where('parent_id', $filterKegiatan)
            ->where('user_id', $userId)->where('connect', 0)->update([
                'sp' => $id,
                'connect' => 1
            ]);

        if($this->request->ajax()){
            return response()->json(['result' => 1, 'message' => __('general.success_add')]);
        }
        else {
            session()->flash('message', __('general.success_add'));
            session()->flash('message_alert', 2);
            return redirect()->route('admin.' . $this->route . '.show', $id);
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
