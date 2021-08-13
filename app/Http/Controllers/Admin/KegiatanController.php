<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_CrudController;
use App\Codes\Logic\PakLogic;
use App\Codes\Models\JenjangPerancang;
use App\Codes\Models\Kegiatan;
use App\Codes\Models\MsKegiatan;
use App\Codes\Models\Permen;
use App\Codes\Models\SuratPernyataan;
use App\Codes\Models\SuratPernyataanKegiatan;
use App\Codes\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KegiatanController extends _CrudController
{
    public function __construct(Request $request)
    {
        $passingData = [
            'id' => [
                'create' => 0,
                'edit' => 0,
                'show' => 0
            ],
            'tanggal' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ]
            ],
            'judul' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ]
            ],
            'ms_kegiatan_name' => [
                'create' => 0,
                'extra' => [
                    'edit' => ['disabled' => true]
                ],
                'custom' => ', name:"B.name"',
                'lang' => 'general.ms_kegiatan'
            ],
            'ms_kegiatan_id' => [
                'list' => 0,
                'show' => 1,
                'edit' => 0,
                'type' => 'select2',
                'lang' => 'general.ms_kegiatan',
            ],
            'kredit' => [
                'create' => 0,
                'extra' => [
                    'edit' => ['disabled' => true]
                ],
            ],
            'satuan' => [
                'create' => 0,
                'extra' => [
                    'edit' => ['disabled' => true]
                ],
            ],
            'dokument_pendukung' => [
                'list' => 0,
                'type' => 'file2',
                'lang' => 'general.dokument'
            ],
            'dokument_fisik' => [
                'list' => 0,
                'type' => 'file2',
                'lang' => 'general.dokumen_fisik'
            ],
            'action' => [
                'create' => 0,
                'edit' => 0,
                'show' => 0,
            ]
        ];

        parent::__construct(
            $request, 'general.kegiatan', 'kegiatan', 'Kegiatan', 'kegiatan',
            $passingData
        );


        $this->listView['index'] = env('ADMIN_TEMPLATE') . '.page.kegiatan.list';

        $this->listView['create'] = env('ADMIN_TEMPLATE') . '.page.kegiatan.forms_create';
        $this->listView['edit'] = env('ADMIN_TEMPLATE') . '.page.kegiatan.forms_edit';
        $this->listView['submit_kegiatan'] = env('ADMIN_TEMPLATE') . '.page.kegiatan.submit_kegiatan';

        $this->data['listSet']['status'] = get_list_status();
        $this->data['listSet']['ms_kegiatan_id'] = MsKegiatan::pluck('name', 'id')->toArray();

    }

    public function index()
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $getUser = Users::where('id', $userId)->first();

        $getJenjangPerancang = JenjangPerancang::where('status', 1)->orderBy('order_high', 'ASC')->get();

        $getNewLogic = new PakLogic();
        $getData = $getNewLogic->getKegiatanUser($userId, '', [1,2]);

        $dataPermen = [];
        $dataKegiatan = [];
        $dataTopKegiatan = [];
        $getFilterKegiatan = [];

        if (count($getData['data']) > 0) {
            $dataPermen = $getData['permen'];
            $dataKegiatan = $getData['data'];
            $dataTopKegiatan = $getData['top_kegiatan'];
        }

        $data = $this->data;

        $data['dataUser'] = $getUser;
        $data['dataJenjangPerancang'] = $getJenjangPerancang;
        $data['dataPermen'] = $dataPermen;

        $data['dataFilterKegiatan'] = $getFilterKegiatan;
        $data['dataKegiatan'] = $dataKegiatan;
        $data['dataTopKegiatan'] = $dataTopKegiatan;

        return view($this->listView['index'], $data);
    }

    public function create()
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $getUser = Users::where('id', $userId)->first();
        if ($getUser->upline_id <= 0) {
            session()->flash('message', __('general.error_no_upline'));
            session()->flash('message_alert', 1);
            return redirect()->route('admin.' . $this->route . '.index');
        }

        $getNewLogic = new PakLogic();
        $getData = $getNewLogic->createKegiatan();

        $judul = Kegiatan::select('judul')->where('user_id',$userId)->where('status',1)->groupBy('judul')->get();

        $getFilterKegiatan = [];
        foreach ($getData['data'] as $list) {
            $getFilterKegiatan[$list['permen_id']][$list['id']] = $list['name'];
        }

        $getJenjangPerancang = JenjangPerancang::where('status', 1)->orderBy('order_high', 'ASC')->get();

        $data = $this->data;

        $data['viewType'] = 'create';
        $data['formsTitle'] = __('general.title_create', ['field' => $data['thisLabel']]);
        $data['judul'] = $judul;
        $data['dataUser'] = $getUser;
        $data['dataJenjangPerancang'] = $getJenjangPerancang;
        $data['dataPermen'] = $getData['permen'];
        $data['dataFilterKegiatan'] = $getFilterKegiatan;
        $data['dataKegiatan'] = $getData['data'];

        return view($this->listView[$data['viewType']], $data);
    }
    public function edit($id){
        $this->callPermission();


        //dd($getData->id);
        $userId = session()->get('admin_id');

        $getUser = Users::where('id', $userId)->first();

        $getJenjangPerancang = JenjangPerancang::where('status', 1)->orderBy('order_high', 'ASC')->get();
        $judul = Kegiatan::select('judul')->where('user_id',$userId)->where('status',1)->groupBy('judul')->get();


        $dataKegiatan = Kegiatan::where('id', $id)->get();
        //dd($data);


        $data = $this->data;


        $data['viewType'] = 'edit';
        $data['formsTitle'] = __('general.title_edit', ['field' => $data['thisLabel']]);
        $data['dataUser'] = $getUser;
        $data['dataKegiatan'] = $dataKegiatan;
        $data['judul'] = $judul;
        $data['data'] = $this->crud->show($id);



        return view($this->listView['edit'], $data);

    }

    public function store()
    {
        $this->callPermission();

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

        $userId = session()->get('admin_id');
        $getUser = Users::where('id', $userId)->first();
        if ($getUser->upline_id <= 0) {
            if ($this->request->ajax()) {
                return response()->json(['result' => 2, 'message' => __('general.error_no_upline')]);
            }
            else {
                return redirect()->back()->withInput()->withErrors(['message' => __('general.error_no_upline')]);
            }
        }

        $getAtasan = Users::where('id', $getUser->upline_id)->first();

        $msKegiatanId = $data['ms_kegiatan_id'];
        $getTanggal = date('Y-m-d', strtotime($data['tanggal']));
        $getMsKegiatan = MsKegiatan::where('id', $msKegiatanId)->first();
        if (!$getMsKegiatan) {
            if ($this->request->ajax()) {
                return response()->json(
                    ['result' => 2, 'message' => __('general.error_data_empty_', ['field' => __('general.kegiatan')])]
                );
            }
            else {
                return redirect()->back()->withInput()->withErrors(
                    ['message' => __('general.error_data_empty_', ['field' => __('general.kegiatan')])]
                );
            }
        }

        $getPermen = Permen::where('id', $getMsKegiatan->permen_id)->where('tanggal_start', '<=', $getTanggal)
            ->where('tanggal_end', '>=', $getTanggal)->first();

        if (!$getPermen) {
            if ($this->request->ajax()) {
                return response()->json(
                    ['result' => 2, 'message' => __('Tanggal Permen sudah lewat'), 'params' => $getTanggal, 'permen' => $getPermen]
                );
            }
            else {
                return redirect()->back()->withInput()->withErrors(
                    ['message' => __('Tanggal Permen sudah lewat')]
                );
            }
        }

        $getJenjangPerancang = JenjangPerancang::where('status', 1)->orderBy('order_high', 'ASC')->get();

        $userNip = $getUser->username;

        $data = $this->getCollectedData($getListCollectData, $viewType, $data);

        $userFolder = 'user_' . preg_replace("/[^A-Za-z0-9?!]/", '', $userNip);
        $todayDate = date('Y-m-d');
        $folderName = $userFolder . '/kegiatan/' . $todayDate . '/';

        $dokument = $this->request->file('dokument');
        $dokumentFisik = $this->request->file('dokument_fisik');

        $totalDokument = [];
        $totalDokumentFisik = [];

        foreach ($dokument as $listDoc) {
            if ($listDoc->getError() == 0) {
                $getFileName = $listDoc->getClientOriginalName();
                $ext = explode('.', $getFileName);
                $fileName = reset($ext);
                $ext = end($ext);
                $setFileName = preg_replace("/[^A-Za-z0-9?!]/", '_', $fileName) . '_' . date('His') . rand(0,100) . '.' . $ext;
                $destinationPath = './uploads/' . $folderName . $msKegiatanId . '/';
                $destinationLink = 'uploads/' . $folderName . $msKegiatanId . '/' . $getFileName;
                $listDoc->move($destinationPath, $setFileName);

                $totalDokument[] = [
                    'name' => $setFileName,
                    'location' => $destinationLink
                ];
            }
        }

        foreach ($dokumentFisik as $listDoc) {
            if ($listDoc->getError() == 0) {
                $getFileName = $listDoc->getClientOriginalName();
                $ext = explode('.', $getFileName);
                $fileName = reset($ext);
                $ext = end($ext);
                $setFileName = preg_replace("/[^A-Za-z0-9?!]/", '_', $fileName) . '_' . date('His') . rand(0,100) . '.' . $ext;
                $destinationPath = './uploads/' . $folderName . $msKegiatanId . '/';
                $destinationLink = 'uploads/' . $folderName . $msKegiatanId . '/' . $getFileName;
                $listDoc->move($destinationPath, $setFileName);

                $totalDokumentFisik[] = [
                    'name' => $setFileName,
                    'location' => $destinationLink
                ];
            }
        }

        $data['user_id'] = $userId;
        $data['user_name'] = $getUser->name;
        $data['top_id'] = $getMsKegiatan->top_id;
        $data['user_jenjang_id'] = $getUser->jenjang_perancang_id;
        $data['upline_id'] = $getUser->upline_id;
        $data['upline_name'] = $getAtasan ? $getAtasan->name : '';
        $data['permen_id'] = $userId;
        $data['kredit_ori'] = $getMsKegiatan->ak;
        $data['kegiatan_jenjang_id'] = $getMsKegiatan->jenjang_perancang_id;
        $data['satuan'] = $getMsKegiatan->satuan;
        $data['kredit'] = calculate_jenjang($getUser->jenjang_perancang_id, $getMsKegiatan->jenjang_perancang_id, $getJenjangPerancang, $getMsKegiatan->ak);
        $data['dokument_pendukung'] = json_encode($totalDokument);
        $data['dokument_fisik'] = json_encode($totalDokumentFisik);
        $data['status'] = 1;
        $data['approved'] = 0;
        $data['connect'] = 0;

        $getData = $this->crud->store($data);

        if ($this->request->ajax()) {
            return response()->json(['result' => 1, 'message' => __('general.success_add')]);
        } else {
            session()->flash('message', __('general.success_add'));
            session()->flash('message_alert', 2);
            return redirect()->route('admin.' . $this->route . '.index');
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

    public function submitKegiatan()
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $getUser = Users::where('id', $userId)->first();
        if ($getUser->upline_id <= 0) {
            session()->flash('message', __('general.error_no_upline'));
            session()->flash('message_alert', 1);
            return redirect()->route('admin.' . $this->route . '.index');
        }

        $getDateRange = $this->request->get('daterange1');

        $getJenjangPerancang = JenjangPerancang::where('status', 1)->orderBy('order_high', 'ASC')->get();

        $getNewLogic = new PakLogic();
        $getData = $getNewLogic->getKegiatanUser($userId, $getDateRange);

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

        $data['viewType'] = 'create';
        $data['formsTitle'] = __('general.title_create', ['field' => __('general.surat_pernyataan')]);

        $data['getDateRange'] = $getDateRange;
        $data['dataUser'] = $getUser;
        $data['dataJenjangPerancang'] = $getJenjangPerancang;
        $data['dataPermen'] = $dataPermen;
        $data['dataFilterKegiatan'] = $getFilterKegiatan;
        $data['dataKegiatan'] = $dataKegiatan;
        $data['dataTopKegiatan'] = $dataTopKegiatan;
        $data['totalPermen'] = $totalPermen;
        $data['totalTop'] = $totalTop;
        $data['totalAk'] = $totalAk;

        return view($this->listView['submit_kegiatan'], $data);

    }

    public function storeSubmitKegiatan()
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $getUser = Users::where('id', $userId)->first();
        if ($getUser->upline_id <= 0) {
            session()->flash('message', __('general.error_no_upline'));
            session()->flash('message_alert', 1);
            return redirect()->route('admin.' . $this->route . '.index');
        }

        $getDateRange = $this->request->get('daterange1');
        $getSplitDate = explode(' | ', $getDateRange);
        $getDateStart = date('Y-m-d', strtotime($getSplitDate[0]));
        $getDateEnd = isset($getSplitDate[1]) ? date('Y-m-d', strtotime($getSplitDate[1])) : date('Y-m-d', strtotime($getSplitDate[0]));

        $getKegiatan = Kegiatan::where('user_id', $userId)->where('status', 1)
            ->where('tanggal', '>=', $getDateStart)->where('tanggal', '<=', $getDateEnd)
            ->get();

        $getKredit = 0;
        foreach ($getKegiatan as $list) {
            $getKredit += $list->kredit;
        }

        DB::beginTransaction();

        $suratPernyataan = new SuratPernyataan();
        $suratPernyataan->save([
            'user_id' => $userId,
            'upline_id' => $getUser->upline_id,
            'supervisor_id' => 0,
            'dupak_id' => 0,
            'tanggal' => null,
            'nomor' => '',
            'tanggal_mulai' => $getDateStart,
            'tanggal_akhir' => $getDateEnd,
            'info_surat_pernyataan' => '',
            'status' => 1,
            'approved' => 0,
            'connect' => 0,
            'total_kredit' => $getKredit
        ]);

        $suratPernyataanId = $suratPernyataan->id;

        $saveDetails = [];
        foreach ($getKegiatan as $list) {
            $saveDetails[] = [
                'surat_pernyataan_id' => $suratPernyataanId,
                'kegiatan_id' => $list->id,
                'ms_kegiatan_id' => $list->ms_kegiatan_id,
                'status' => 1
            ];
        }

        SuratPernyataanKegiatan::insert($saveDetails);

        Kegiatan::where('user_id', $userId)->where('status', 1)
            ->where('tanggal', '>=', $getDateStart)->where('tanggal', '<=', $getDateEnd)
            ->update([
            'status' => 2
        ]);

        DB::commit();

        if ($this->request->ajax()) {
            return response()->json(['result' => 1, 'message' => __('Surat Pernyataan berhasil di ajukan')]);
        } else {
            session()->flash('message', __('Kegiatan berhasil di ajukan'));
            session()->flash('message_alert', 2);
            return redirect()->route('admin.' . $this->route . '.index');
        }

    }

}

