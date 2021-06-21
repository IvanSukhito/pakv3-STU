<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_CrudController;
use App\Codes\Models\Dupak;
use App\Codes\Models\Kegiatan;
use App\Codes\Models\MsKegiatan;
use App\Codes\Models\Staffs;
use App\Codes\Models\SuratPernyataan;
use App\Codes\Models\Users;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PDF;

class DupakController extends _CrudController
{
    protected $passingCRUD;

    public function __construct(Request $request)
    {
        $passingData = [
            'id' => [
                'show' => 0,
                'create' => 0,
                'edit' => 0,

            ],
            'nomor' => [
                'lang' => 'Nomor Dupak',
                'show' => 0,
                'create' => 0,
                'edit' => 0,
            ],
            'surat_pernyataan' => [
                'custom' => ', name:"D.nomor"',
                'lang' => 'Nomor Surat Pernyataan',
                'show' => 0,
                'create' => 0,
                'edit' => 0,
                'type' => 'select'
            ],
            'approved' => [
                'type' => 'select',
                'lang' => 'general.status',
                'show' => 0,
                'create' => 0,
                'edit' => 0,

            ],
            'send_status' => [
                'type' => 'select',
                'lang' => 'Status terkirim',
                'show' => 0,
                'create' => 0,
                'edit' => 0,

            ],
            'file_sp' => [
                'type' => 'file_download',
                'show' => 0,
                'list' => 0,
                'create' => 0,
                'edit' => 0,

                'lang' => 'general.file_sp'
            ],
            'file_dupak' => [
                'type' => 'file_download',
                'show' => 0,
                'list' => 0,
                'create' => 0,
                'edit' => 0,
                'lang' => 'general.file_dupak',
            ],
            'action' => [
                'show' => 0,
                'create' => 0,
                'edit' => 0,
                'lang' => 'Aksi',

            ]
        ];

        $this->passingCRUD = generatePassingData([
            'nomor' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ]
            ],
            'penilaian_tanggal' => [
                'validate' => [
                    'create' => 'required|date_format:Y-m-d',
                    'edit' => 'required|date_format:Y-m-d'
                ]
            ],
            'lampiran' => [
                'validate' => [
                    'create' => 'required|array',
                    'edit' => 'required|array'
                ]
            ],
            'lampiran.*' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ]
            ],
            'lokasi_tanggal' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ]
            ],
            'tanggal' => [
                'validate' => [
                    'create' => 'required|date_format:Y-m-d',
                    'edit' => 'required|date_format:Y-m-d'
                ]
            ],
            'jabatan_pengusul' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ]
            ],

            'jabatan_pengusul_nip' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
            ],
        ]);

        parent::__construct(
            $request, 'general.dupak', 'dupak', 'Dupak', 'dupak',
            $passingData
        );

        $this->listView['index'] = env('ADMIN_TEMPLATE').'.page.dupak.list';
        $this->listView['create'] = env('ADMIN_TEMPLATE').'.page.dupak.forms';
        $this->listView['edit'] = env('ADMIN_TEMPLATE').'.page.dupak.forms';
        $this->listView['show'] = env('ADMIN_TEMPLATE').'.page.dupak.forms';
        $this->listView['dataTable'] = env('ADMIN_TEMPLATE').'.page.dupak.list_button_staff';

        $this->data['listSet']['approved'] = get_list_status_pak();
        $this->data['listSet']['send_status'] = get_list_status_send_dupak();

    }

    public function verification()
    {
        $this->callPermission();

        $data = $this->data;

        $userId = session()->get(env('APP_NAME') . 'admin_id');

        $data['passing'] = collectPassingData($this->passingData);
        $data['setFlag'] = 1;

        return view($this->listView['index'], $data);
    }

    public function dataTable()
    {
        $this->callPermission();

        $userId = session()->get(env('APP_NAME') . 'admin_id');

        $getFlag = intval($this->request->get('flag'));

        $dataTables = new DataTables();

        $builder = $this->model::query()->selectRaw("dupak.id, dupak.status_upload, dupak.send_status, dupak.nomor, dupak.approved, GROUP_CONCAT( C.nomor SEPARATOR ', ' ) AS surat_pernyataan, dupak.pdf, dupak.pdf_url");

        if ($getFlag == 1) {
            $getSekertariat = Staffs::where('sekertariat_id', $userId)->pluck('user_id');
            $getTimPenilai = Staffs::where('penilai_id', $userId)->pluck('user_id');
            if (count($getSekertariat) > 0) {
                $builder = $builder->whereIn('dupak.user_id', $getSekertariat)->where('dupak.send_status', 1);
            }
            elseif (count($getTimPenilai) > 0) {
                $builder = $builder->whereIn('dupak.user_id', $getTimPenilai)->where('dupak.approved', '>', 0);
            }
            else {
                $builder = $builder->where('dupak.user_id', 0);
            }
        }
        else {
            $builder = $builder->where('dupak.user_id', $userId);
        }

        $builder = $builder->leftJoin('dupak_surat_pernyataan AS B', 'B.dupak_id', '=', 'dupak.id')
            ->leftJoin('surat_pernyataan AS C', 'C.id', '=', 'B.surat_pernyataan_id')
            ->groupBy(['dupak.id', 'dupak.nomor', 'dupak.approved', 'dupak.pdf', 'dupak.pdf_url', 'dupak.status_upload', 'dupak.send_status']);

        $setView = $this->listView['dataTable'];
        if ($getFlag == 1) {
            if (count($getSekertariat) > 0) {
                $setView = env('ADMIN_TEMPLATE').'.page.dupak.list_button_sekertariat';
            }
            else {
                $setView = env('ADMIN_TEMPLATE').'.page.dupak.list_button_penilai';
            }
        }

        $dataTables = $dataTables->eloquent($builder)
            ->addColumn('action', function ($query) use ($setView, $getFlag) {
                return view($setView, [
                    'getFlag' => $getFlag,
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

        $userId = session()->get(env('APP_NAME') . 'admin_id');

        $surat_pernyataan = SuratPernyataan::where('user_id', $userId)->where('approved', 2)->where('connect', 0)->orderBy('created_at', 'desc')->first();


        if(!$surat_pernyataan) {
            if($this->request->ajax()){
                return response()->json(['result' => 2, 'message' => __('tidak ditemukan Surat Pernyataan yang telah di verifikasi')]);
            }
            else {
                session()->flash('message', __('tidak ditemukan Surat Pernyataan yang telah di verifikasi'));
                session()->flash('message_alert', 1);
                return redirect()->route('admin.' . $this->route . '.index');
            }
        }

        $getStaff = Staffs::where('user_id', $userId)->first();
        $getUser = Users::where('id', $userId)->first();
        $staffUnitKerja = $getStaff->getUnitKerja()->first();

        $data = $this->data;

        $data['viewType'] = 'create';
        $data['formsTitle'] = __('general.title_create', ['field' => $data['thisLabel']]);
        $data['passing'] = collectPassingData($this->passingData, $data['viewType']);
        $data['setList']['surat_pernyataan'] = SuratPernyataan::where('user_id', $userId)
            ->where('approved', 2)->where('connect', 0)->orderBy('created_at', 'desc')->pluck('nomor', 'id')->toArray();
        $data['staffUnitKerja'] = $staffUnitKerja;
        $data['getStaff'] = $getStaff;
        $data['getUser'] = $getUser;

        return view($this->listView[$data['viewType']], $data);
    }

    public function edit($id)
    {
        $this->callPermission();

        $getData = $this->crud->show($id);
        if (!$getData) {
            return redirect()->route('admin.' . $this->route . '.index');
        }

        $userId = session()->get(env('APP_NAME') . 'admin_id');

        $getStaff = Staffs::where('user_id', $userId)->first();
        $getUser = Users::where('id', $userId)->first();
        $staffUnitKerja = $getStaff->getUnitKerja()->first();

        $data = $this->data;

        $data['viewType'] = 'edit';
        $data['formsTitle'] = __('general.title_edit', ['field' => $data['thisLabel']]);
        $data['passing'] = collectPassingData($this->passingData, $data['viewType']);
        $data['data'] = $getData;
        $data['setList']['surat_pernyataan'] = SuratPernyataan::where('dupak_id', $getData->id)->pluck('nomor', 'id')->toArray();
        $data['staffUnitKerja'] = $staffUnitKerja;
        $data['getStaff'] = $getStaff;
        $data['getUser'] = $getUser;

        return view($this->listView[$data['viewType']], $data);
    }

    public function show($id)
    {
        $this->callPermission();

        $getData = $this->crud->show($id);
        if (!$getData) {
            return redirect()->route('admin.' . $this->route . '.index');
        }

        $userId = session()->get(env('APP_NAME') . 'admin_id');

        $getStaff = Staffs::where('user_id', $userId)->first();
        $getUser = Users::where('id', $userId)->first();
        $staffUnitKerja = $getStaff->getUnitKerja()->first();

        $data = $this->data;

        $data['viewType'] = 'show';
        $data['formsTitle'] = __('general.title_show', ['field' => $data['thisLabel']]);
        $data['passing'] = collectPassingData($this->passingData, $data['viewType']);
        $data['data'] = $getData;
        $data['setList']['surat_pernyataan'] = SuratPernyataan::where('dupak_id', $getData->id)->pluck('nomor', 'id')->toArray();
        $data['staffUnitKerja'] = $staffUnitKerja;
        $data['getStaff'] = $getStaff;
        $data['getUser'] = $getUser;

        return view($this->listView[$data['viewType']], $data);
    }

    public function store()
    {
        $this->callPermission();

        $viewType = 'create';

        $userId = session()->get(env('APP_NAME') . 'admin_id');

        $suratPernyataanId = $this->request->get('surat_pernyataan');

        $surat_pernyataan = SuratPernyataan::where('user_id', $userId)->where('approved', 2)->where('connect', 0)->where('id', $suratPernyataanId)->first();
        if(!$surat_pernyataan) {
            if($this->request->ajax()){
                return response()->json(['result' => 2, 'message' => __('Surat Pernyataan tidak ditemukan')]);
            }
            else {
                session()->flash('message', __('Surat Pernyataan tidak ditemukan'));
                session()->flash('message_alert', 1);
                return redirect()->route('admin.' . $this->route . '.index');
            }
        }

        $getListCollectData = collectPassingData($this->passingCRUD, $viewType);
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
        $data['lampiran'] = json_encode($data['lampiran']);
        $data['user_id'] = $userId;
        $data['kredit_baru'] = $surat_pernyataan->total_kredit;
        $data['connect'] = 0;
//        $data['created_by'] = session()->get('admin_name');

        $getData = $this->crud->store($data);

        $getSuratPernyataan = SuratPernyataan::where('user_id', $userId)->where('approved', 2)->where('id', $suratPernyataanId)->first();
        $getSuratPernyataan->connect = 1;
        $getSuratPernyataan->dupak_id = $getData->id;
        $getSuratPernyataan->save();

        $getSuratPernyataan->getKegiatan()->update([
            'dupak_id' => $getData->id
        ]);

        $id = $getData->id;

        if($this->request->ajax()){
            return response()->json(['result' => 1, 'message' => __('general.success_add')]);
        }
        else {
            session()->flash('message', __('general.success_add'));
            session()->flash('message_alert', 2);
            return redirect()->route('admin.' . $this->route . '.show', $id);
        }
    }

    public function update($id)
    {
        $this->callPermission();

        $viewType = 'edit';

        $getData = $this->crud->show($id);
        if (!$getData) {
            return redirect()->route('admin.' . $this->route . '.index');
        }

        $getListCollectData = collectPassingData($this->passingCRUD, $viewType);
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
        $data['lampiran'] = json_encode($data['lampiran']);

//        $data['updated_by'] = session()->get('admin_name');

        $getData = $this->crud->update($data, $id);

        $id = $getData->id;

        if($this->request->ajax()){
            return response()->json(['result' => 1, 'message' => __('general.success_edit')]);
        }
        else {
            session()->flash('message', __('general.success_edit'));
            session()->flash('message_alert', 2);
            return redirect()->route('admin.' . $this->route . '.show', $id);
        }
    }

    protected function previewPDF($id, $type = 1, $sample = true)
    {
        ini_set('memory_limit', '-1');

        $getData = Dupak::where('id', $id)->first();

        if (strlen($getData->pdf_preview) > 0) {
            $location = asset($getData->pdf_preview_url);
        }
        else {
            $getPDF = $this->createPDF($id, 1);
            $location = asset($getPDF['location']);
            $getData->pdf_preview = $getPDF['name'];
            $getData->pdf_preview_url = $getPDF['location'];
            $getData->save();
        }

        return redirect($location);

//        $getData = Dupak::where('id', $id)->first();
//        if($getData) {
//            $userId = $getData->user_id;
//
//            $sum_total_lama = 0;
//            $sum_total_baru = 0;
//
//            $getStaff = Staffs::where('user_id', $userId)->first();
//            $getUser = Users::where('id', $userId)->first();
//            $staffJenjangPerancang = $getStaff->getJenjangPerancang()->first();
//            $staffJabatanPerancang = $getStaff->getJabatanPerancang()->first();
//            $staffUnitKerja = $getStaff->getUnitKerja()->first();
//            $staffGender = $getStaff->getGender()->first();
//            $staffPendidikan = $getStaff->getPendidikan()->first();
//
//            $kegiatan = [];
//            $getAllKegiatan = Kegiatan::where('dupak_id', $id)->get();
//            foreach($getAllKegiatan as $list) {
//                $kegiatan[] = $list;
//            }
//
//            $get_ms_kegiatan = MsKegiatan::where('status', 1)->get();
//
//            $list_ms_kegiatan = [];
//            $list_kegiatan = [];
//            foreach($get_ms_kegiatan as $list) {
//                $list_ms_kegiatan[$list->id] = $list;
//            }
//            $list_old_kegiatan_ids = [];
//            foreach ($kegiatan as $list) {
//                $sum_total_baru += $list->kredit;
//                $list_kegiatan[$list->ms_kegiatan_id][] = $list;
//                $list_old_kegiatan_ids[] = $list->ms_kegiatan_id;
//            }
//
//            $list_old_kegiatan_ids = array_unique($list_old_kegiatan_ids);
//            $list_old_kegiatan = [];
//            if($list_old_kegiatan_ids) {
//                $temp_list_old_kegiatan = Kegiatan::selectRaw('SUM(kredit) AS kredit, ms_kegiatan_id')->whereIn('ms_kegiatan_id', $list_old_kegiatan_ids)->where('user_id', $userId)->where('approved', 1)->groupBy('ms_kegiatan_id')->get();
//                if ($temp_list_old_kegiatan) {
//                    foreach ($temp_list_old_kegiatan as $list) {
//                        $list_old_kegiatan[$list->ms_kegiatan_id] = $list->kredit;
//                        $sum_total_lama += $list->kredit;
//                    }
//                }
//            }
//
//            $list_old_kegiatan = $list;
//
//            $sum_total_lama = $getStaff->angka_kredit;
//
//            $sum_total = $sum_total_lama + $sum_total_baru;
//
//            $render_kegiatan = render_kegiatan_dupak_pdf_detail($list_ms_kegiatan, $list_kegiatan, $list_old_kegiatan, $sum_total_lama, $sum_total_baru, $sum_total);
//
//            $data = [
//                'staff' => $getStaff,
//                'user' => $getUser,
//                'staffJenjangPerancang' => $staffJenjangPerancang,
//                'staffJabatanPerancang' => $staffJabatanPerancang,
//                'staffUnitKerja' => $staffUnitKerja,
//                'staffGender' => $staffGender,
//                'staffPendidikan' => $staffPendidikan,
//                'dupak' => $getData,
//                'render_kegiatan' => $render_kegiatan,
//                'sample' => $sample
//            ];
//
//            $pdf = PDF::loadView('pdf.preview_dupak', $data);
//            return $pdf->stream();
//
//            $userId = $getData->user_id;
//
//            $sum_total_lama = 0;
//            $sum_total_baru = 0;
//
//            $getStaff = Staffs::where('user_id', $userId)->first();
//            $getUser = Users::where('id', $userId)->first();
//            $staffJenjangPerancang = $getStaff->getJenjangPerancang()->first();
//            $staffJabatanPerancang = $getStaff->getJabatanPerancang()->first();
//            $staffUnitKerja = $getStaff->getUnitKerja()->first();
//            $staffGender = $getStaff->getGender()->first();
//            $staffPendidikan = $getStaff->getPendidikan()->first();
//
//            $kegiatan = [];
//            $getAllKegiatan = Kegiatan::where('sp', $id)->get();
//
//
//            foreach($getAllKegiatan as $list) {
//                $kegiatan[] = $list;
//            }
//
//            $get_ms_kegiatan = MsKegiatan::where('status', 1)->get();
//
//            $list_ms_kegiatan = [];
//            $list_kegiatan = [];
//            foreach($get_ms_kegiatan as $list) {
//                $list_ms_kegiatan[$list->id] = $list;
//            }
//            $list_old_kegiatan_ids = [];
//            foreach ($kegiatan as $list) {
//                $sum_total_baru += $list->kredit;
//                $list_kegiatan[$list->ms_kegiatan_id][] = $list;
//                $list_old_kegiatan_ids[] = $list->ms_kegiatan_id;
//            }
//
//            $list_old_kegiatan_ids = array_unique($list_old_kegiatan_ids);
//
//
//                $temp_list_old_kegiatan = Kegiatan::selectRaw('SUM(kredit) AS kredit, ms_kegiatan_id')->whereIn('ms_kegiatan_id', $list_old_kegiatan_ids)->where('user_id', $userId)->where('approved', 1)->groupBy('ms_kegiatan_id')->get();
//                if ($temp_list_old_kegiatan) {
//                    foreach ($temp_list_old_kegiatan as $list) {
//                        $list_old_kegiatan[$list->ms_kegiatan_id] = $list->kredit;
//                        $sum_total_lama += $list->kredit;
//                    }
//                }
//
//
//            $sum_total_lama = $getStaff->angka_kredit;
//
//            $sum_total = $sum_total_lama + $sum_total_baru;
//
//            $render_kegiatan = render_kegiatan_dupak_pdf_detail($list_ms_kegiatan, $list_kegiatan, $list_old_kegiatan, $sum_total_lama, $sum_total_baru, $sum_total);
//
//            $data = [
//                'staff' => $getStaff,
//                'user' => $getUser,
//                'staffJenjangPerancang' => $staffJenjangPerancang,
//                'staffJabatanPerancang' => $staffJabatanPerancang,
//                'staffUnitKerja' => $staffUnitKerja,
//                'staffGender' => $staffGender,
//                'staffPendidikan' => $staffPendidikan,
//                'dupak' => $getData,
//                'render_kegiatan' => $render_kegiatan,
//                'sample' => $sample
//            ];
//
//            $pdf = PDF::loadView('pdf.preview_dupak', $data);
//            return $pdf->stream();
//
//        }
//
//        return false;

    }

    public function approve($id)
    {
        $userId = session()->get(env('APP_NAME') . 'admin_id');
        $getData = Dupak::where('id', $id)->first();

        if(session()->get(env('APP_NAME').'admin_sekretariat')) {
            $getData->approved = 1;
            $getData->verifikasi_sekretariat_id = $userId;
            $createPdf = $this->createPDF($id);
            $getData->pdf = $createPdf['name'];
            $getData->pdf_url = $createPdf['location'];
        }
        elseif(session()->get(env('APP_NAME').'admin_tim_penilai')) {
            $getData->approved = 2;
            $getData->verifikasi_tim_penilai_id = $userId;
        }

        $getData->save();

        $getListSuratPernyataanIds = $getData->getSuratPernyataan()->pluck('id')->toArray();

        $getData->getSuratPernyataan()->update([
            'approved' => 2
        ]);

        if (count($getListSuratPernyataanIds) > 0) {
            Kegiatan::where('dupak_id', $id)->update([
                'approved' => 2
            ]);
        }

        if($this->request->ajax()){
            return response()->json(['result' => 1, 'message' => __('general.success_approve', ['field' => 'Surat Pernyataan '.$getData->nomor])]);
        }
        else {
            session()->flash('message', __('general.success_approve', ['field' => 'Dupak '.$getData->nomor]));
            session()->flash('message_alert', 2);
            return redirect()->route('admin.' . $this->route . '.verification');
        }
    }

    public function reject($id)
    {
        $userId = session()->get(env('APP_NAME') . 'admin_id');
        $getData = Dupak::where('id', $id)->first();

        if(session()->get(env('APP_NAME').'admin_sekretariat')) {
            $getData->approved = 9;
            $getData->verifikasi_sekretariat_id = $userId;
        }
        elseif(session()->get(env('APP_NAME').'admin_tim_penilai')) {
            $getData->approved = 9;
            $getData->verifikasi_tim_penilai_id = $userId;
        }
        $getData->save();

        $getListSuratPernyataanIds = $getData->getSuratPernyataan()->pluck('id')->toArray();

        $getData->getSuratPernyataan()->update([
            'approved' => 9
        ]);

        if (count($getListSuratPernyataanIds) > 0) {
            Kegiatan::whereHas('getSuratPernyataan', function($query) use ($getListSuratPernyataanIds) {
                $query->whereIn('sp', $getListSuratPernyataanIds);
            })->update([
                'approved' => 9
            ]);
        }

        if($this->request->ajax()){
            return response()->json(['result' => 2, 'message' => __('general.success_reject', ['field' => 'Surat Pernyataan '.$getData->nomor])]);
        }
        else {
            session()->flash('message', __('general.success_reject', ['field' => 'Surat Pernyataan '.$getData->nomor]));
            session()->flash('message_alert', 2);
            return redirect()->route('admin.' . $this->route . '.verification');
        }

    }

    protected function createPDF($id, $type = 1, $sample = false)
    {
        ini_set('memory_limit', '-1');

        $getData = Dupak::where('id', $id)->first();
        if($getData) {
            $userId = $getData->user_id;

            $sum_total_lama = 0;
            $sum_total_baru = 0;

            $getStaff = Staffs::where('user_id', $userId)->first();
            $getUser = Users::where('id', $userId)->first();
            $staffJenjangPerancang = $getStaff->getJenjangPerancang()->first();
            $staffJabatanPerancang = $getStaff->getJabatanPerancang()->first();
            $staffUnitKerja = $getStaff->getUnitKerja()->first();
            $staffGender = $getStaff->getGender()->first();
            $staffPendidikan = $getStaff->getPendidikan()->first();

            $kegiatan = [];
            $getAllKegiatan = Kegiatan::where('dupak_id', $id)->get();

            $getPermenIds = [];
            foreach($getAllKegiatan as $list) {
                $getPermenIds[] = $list->permen_id;
                $kegiatan[] = $list;
            }

            $get_ms_kegiatan = MsKegiatan::where('status', 1)->whereIn('permen_id', $getPermenIds)->get();

            $list_ms_kegiatan = [];
            $list_kegiatan = [];
            foreach($get_ms_kegiatan as $list) {
                $list_ms_kegiatan[$list->id] = $list;
            }
            $list_old_kegiatan_ids = [];
            foreach ($kegiatan as $list) {
                $sum_total_baru += $list->kredit;
                $kredit = $list->kredit;
                $list_kegiatan[$list->ms_kegiatan_id][] = $list;
                $list_old_kegiatan_ids[] = $list->ms_kegiatan_id;
            }

            $list_old_kegiatan_ids = array_unique($list_old_kegiatan_ids);
            $list_old_kegiatan = [];
            if($list_old_kegiatan_ids) {
                $temp_list_old_kegiatan = Kegiatan::selectRaw('SUM(kredit) AS kredit, ms_kegiatan_id')->whereIn('ms_kegiatan_id', $list_old_kegiatan_ids)->where('user_id', $userId)->where('approved', 1)->groupBy('ms_kegiatan_id')->get();
                if ($temp_list_old_kegiatan) {
                    foreach ($temp_list_old_kegiatan as $list) {
                        $list_old_kegiatan[$list->ms_kegiatan_id] = $list->kredit;
                        $sum_total_lama += $list->kredit;
                    }
                }
            }

            $sum_total_lama = $getStaff->angka_kredit;

            $sum_total = $sum_total_lama + $sum_total_baru;

            $render_kegiatan = render_kegiatan_dupak_pdf_detail($list_ms_kegiatan, $list_kegiatan, $list_old_kegiatan, $sum_total_lama, $sum_total_baru, $sum_total);


            $user_nip = $getUser->username;
            $user_nip = preg_replace("/[^A-Za-z0-9?!]/",'', $user_nip);
            $user_folder = 'user_'.$user_nip;
            $today_date = date('Y-m-d');
            $folder_name = $user_folder.'/dupak/'.$today_date.'/';
            $set_file_name = 'dupak_'.date('His').rand(10,99).'.pdf';
            $folder_path = './uploads/'.$folder_name;
            $destinationPath = './uploads/'.$folder_name.$set_file_name;
            $destinationLink = 'uploads/'.$folder_name.$set_file_name;

            if(!file_exists($folder_path)) {
                mkdir($folder_path, 755, true);
            }

            $data = [
                'staff' => $getStaff,
                'user' => $getUser,
                'staffJenjangPerancang' => $staffJenjangPerancang,
                'staffJabatanPerancang' => $staffJabatanPerancang,
                'staffUnitKerja' => $staffUnitKerja,
                'staffGender' => $staffGender,
                'staffPendidikan' => $staffPendidikan,
                'dupak' => $getData,
                'render_kegiatan' => $render_kegiatan,
                'sample' => $sample
            ];

            $pdf = PDF::loadView('pdf.dupak', $data);

            if ($type == 1) {
                $pdf->save($destinationPath);
                return [
                    'name' => $set_file_name,
                    'location' => $destinationLink,
                ];
            }
            elseif($type == 2) {
                return $pdf->stream('pdf.dupak', $data);
            }

        }

        return false;

    }

    protected function generatePDF($id)
    {
        ini_set('memory_limit', '-1');

        $getData = Dupak::where('id', $id)->first();
        if($getData) {
            $userId = $getData->user_id;

            $sum_total_lama = 0;
            $sum_total_baru = 0;

            $getStaff = Staffs::where('user_id', $userId)->first();
            $getUser = Users::where('id', $userId)->first();
            $staffJenjangPerancang = $getStaff->getJenjangPerancang()->first();
            $staffJabatanPerancang = $getStaff->getJabatanPerancang()->first();
            $staffUnitKerja = $getStaff->getUnitKerja()->first();
            $staffGender = $getStaff->getGender()->first();
            $staffPendidikan = $getStaff->getPendidikan()->first();

            $kegiatan = [];
            $getAllKegiatan = Kegiatan::where('sp', $id)->get();
            foreach($getAllKegiatan as $list) {
                $kegiatan[] = $list;
            }

            $get_ms_kegiatan = MsKegiatan::where('status', 1)->get();

            $list_ms_kegiatan = [];
            $list_kegiatan = [];
            foreach($get_ms_kegiatan as $list) {
                $list_ms_kegiatan[$list->id] = $list;
            }
            $list_old_kegiatan_ids = [];
            foreach ($kegiatan as $list) {
                $sum_total_baru += $list->kredit;
                $list_kegiatan[$list->ms_kegiatan_id][] = $list;
                $list_old_kegiatan_ids[] = $list->ms_kegiatan_id;
            }

            $list_old_kegiatan_ids = array_unique($list_old_kegiatan_ids);
            $list_old_kegiatan = [];
            if($list_old_kegiatan_ids) {
                $temp_list_old_kegiatan = Kegiatan::selectRaw('SUM(kredit) AS kredit, ms_kegiatan_id')->whereIn('ms_kegiatan_id', $list_old_kegiatan_ids)->where('user_id', $userId)->where('approved', 1)->groupBy('ms_kegiatan_id')->get();
                if ($temp_list_old_kegiatan) {
                    foreach ($temp_list_old_kegiatan as $list) {
                        $list_old_kegiatan[$list->ms_kegiatan_id] = $list->kredit;
                        $sum_total_lama += $list->kredit;
                    }
                }
            }

            $list_old_kegiatan = $list;

            $sum_total_lama = $getStaff->angka_kredit;

            $sum_total = $sum_total_lama + $sum_total_baru;

            $render_kegiatan = render_kegiatan_dupak_pdf_detail($list_ms_kegiatan, $list_kegiatan, $list_old_kegiatan, $sum_total_lama, $sum_total_baru, $sum_total);

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
                'staff' => $getStaff,
                'user' => $getUser,
                'staffJenjangPerancang' => $staffJenjangPerancang,
                'staffJabatanPerancang' => $staffJabatanPerancang,
                'staffUnitKerja' => $staffUnitKerja,
                'staffGender' => $staffGender,
                'staffPendidikan' => $staffPendidikan,
                'dupak' => $getData,
                'render_kegiatan' => $render_kegiatan
            ];

            $pdf = PDF::loadView('pdf.dupak', $data);

            $pdf->save($destinationPath);

        }

        return false;

    }

    public function uploadFile($id)
    {
        $data = $this->data;
        $userId = session()->get(env('APP_NAME') . 'admin_id');

        $this->validate($this->request, [
            'fileSp' => 'required',
            'fileDupak' => 'required',
        ],[
            'fileSp.required' => 'File SP tidak boleh kosong',
            'fileDupak.required' => 'File dupak tidak boleh kosong',
        ]);

        $uploadSp = $this->request->file('fileSp');
        $uploadDupak = $this->request->file('fileDupak');

        $extensionSP = $uploadSp->getClientOriginalExtension();
        $extensionDupak = $uploadDupak->getClientOriginalExtension();

        $time = date("Y-m-d" , time());

        $fileSpName = 'file-sp-'. $time . '.' . $extensionSP;
        $fileDupakName = 'file-dupak-' . $time . '.' . $extensionDupak;

        $destinationPath =  './uploads/register';

        $moveFileSp =  $uploadSp->move($destinationPath, $fileSpName);
        $moveFileDupak =  $uploadDupak->move($destinationPath, $fileDupakName);

        $dupak = Dupak::where('user_id', $userId)->where('id', $id)->update([
            'file_sp'           => $fileSpName,
            'file_dupak'        => $fileDupakName,
            'status_upload'     => 1,
        ]);

        session()->flash('message', __('Unggah File Berhasil'));
        session()->flash('message_alert', 2);
        return back();
    }

    public function sendToSekretariat($id)
    {
        $dupak = Dupak::where('id', $id)->firstOrFail();
        $dupak->send_status = 1;
        $dupak->update();

        return response()->json(['result' => 2, 'message' => __('general.success')]);
    }

    public function cetakKegiatan($id)
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

        return view( env('ADMIN_TEMPLATE').'.page.dupak.cetak_kegiatan', $data);
    }


}
