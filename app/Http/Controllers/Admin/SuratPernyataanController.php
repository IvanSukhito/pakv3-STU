<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_CrudController;
use App\Codes\Logic\PakLogic;
use App\Codes\Models\SuratPernyataan;
use App\Codes\Models\JenjangPerancang;
use App\Codes\Models\Users;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SuratPernyataanController extends _CrudController
{
    public function __construct(Request $request)
    {
        $passingData = [
            'id' => [
                'edit' => 0
            ],
            'total_kredit' => [
                'type' => 'number'
            ],
            'status' => [
                'type' => 'select'
            ],
            'tanggal_mulai' => [
                'type' => 'datetime'
            ],
            'tanggal_akhir' => [
                'type' => 'datetime'
            ],
            'created_at' => [
                'lang' => 'DiKirim',
                'type' => 'datetime'
            ],
            'updated_at' => [
                'lang' => 'Disetujui',
                'type' => 'datetime'
            ],
            'action' => [
                'show' => 0,
                'lang' => 'Aksi',
            ]
        ];

        parent::__construct(
            $request, 'general.surat_pernyataan', 'surat-pernyataan', 'SuratPernyataan', 'surat-pernyataan',
            $passingData
        );

        $this->listView['show'] = env('ADMIN_TEMPLATE').'.page.surat_pernyataan.forms';
        $this->listView['dataTable'] = env('ADMIN_TEMPLATE').'.page.surat_pernyataan.list_button';

        $this->data['listSet']['status'] = get_list_status_surat_pernyataan();

    }

    public function dataTable()
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $dataTables = new DataTables();

        $builder = Users::selectRaw('users.id, tx_surat_pernyataan.status,
                tx_surat_pernyataan.tanggal_mulai, tx_surat_pernyataan.tanggal_akhir,
                SUM(tx_surat_pernyataan.total_kredit) AS total_kredit, tx_surat_pernyataan.created_at,
                tx_surat_pernyataan.updated_at')
            ->join('tx_surat_pernyataan', 'tx_surat_pernyataan.user_id', '=', 'users.id')
            ->where('users.id', $userId)
            ->whereIn('tx_surat_pernyataan.status', [80,99])
            ->groupByRaw('users.id, tx_surat_pernyataan.status,
                tx_surat_pernyataan.tanggal_mulai, tx_surat_pernyataan.tanggal_akhir,
                tx_surat_pernyataan.created_at, tx_surat_pernyataan.updated_at');

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
            else if (in_array($list['type'], ['datetime'])) {
                $listRaw[] = $fieldName;
                $dataTables = $dataTables->editColumn($fieldName, function ($query) use ($fieldName, $list, $listRaw) {
                    return date('d-M-Y', strtotime($query->$fieldName));
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

    public function show($id)
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $getSuratPernyataan = SuratPernyataan::where('user_id', $id)->whereIn('status', [80,99])->get();
        $getSuratPernyataanIds = [];
        $status = 0;
        $totalKredit = 0;
        $listSuratPernyataan = [];
        foreach ($getSuratPernyataan as $list) {
            $getSuratPernyataanIds[] = $list->id;
            $status = $list->status;
            $totalKredit += $list->total_kredit;
            $listSuratPernyataan[$list->top_kegiatan_id] = $list->id;
        }

        $getPerancang = Users::where('id', $userId)->first();
        if (!$getPerancang) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        $getPAKLogic = new PakLogic();
        $getData = $getPAKLogic->getSuratPernyataanUser($getSuratPernyataan);

        $getJenjangPerancang = JenjangPerancang::where('status', 1)->orderBy('order_high', 'ASC')->get();

        $dataPermen = [];
        $dataKegiatan = [];
        $dataTopKegiatan = [];
        $getFilterKegiatan = [];
        $totalPermen = 0 ;
        $totalTop = 0 ;
        $totalAk = 0 ;
        $topId = [];
        $kredit = [];

        if (count($getData['data']) > 0) {
            $totalPermen = count($getData['total_permen']);
            $totalTop = count($getData['total_top']);
            $totalAk = $getData['total_ak'];
            $dataPermen = $getData['permen'];
            $dataKegiatan = $getData['data'];
            $dataTopKegiatan = $getData['top_kegiatan'];
            $topId = $getData['total_top'];
            $kredit = $getData['kredit'];
        }

        $data = $this->data;

        $data['viewType'] = 'show';
        $data['formsTitle'] = __('general.title_show', ['field' => $data['thisLabel']]);
        $data['passing'] = collectPassingData($this->passingData, $data['viewType']);
        $data['data'] = (object)[
            'id' => $id,
            'dupak_id' => $id,
            'status' => $status,
            'total_kredit' => $totalKredit
        ];
        $data['dataUser'] = $getPerancang;
        $data['dataJenjangPerancang'] = $getJenjangPerancang;
        $data['dataPermen'] = $dataPermen;
        $data['dataFilterKegiatan'] = $getFilterKegiatan;
        $data['dataKegiatan'] = $dataKegiatan;
        $data['dataTopKegiatan'] = $dataTopKegiatan;
        $data['totalPermen'] = $totalPermen;
        $data['listSuratPernyataan'] = $listSuratPernyataan;
        $data['totalTop'] = $totalTop;
        $data['totalAk'] = $totalAk;
        $data['topId'] = $topId;
        $data['kredit'] = $kredit;

        return view($this->listView[$data['viewType']], $data);
    }

    public function showPdf($id)
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $getAtasan = Users::where('id', $userId)->first();
        if (!$getAtasan) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        $getSuratPernyataan = SuratPernyataan::where('id', $id)->whereIn('status', [80,88,99])->first();
        if ($getSuratPernyataan) {
            $getPAKLogic = new PakLogic();
            $getPAKLogic->generateSuratPernyataan($id);
        }
        return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
    }

    public function showDupakPdf($id)
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $getAtasan = Users::where('id', $userId)->first();
        if (!$getAtasan) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        $getSuratPernyataan = SuratPernyataan::where('id', $id)->whereIn('status', [80,88,99])->first();
        if ($getSuratPernyataan) {
            $getPAKLogic = new PakLogic();
            $getPAKLogic->generateDupak($id);
        }
        return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
    }

}
