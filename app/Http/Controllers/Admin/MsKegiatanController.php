<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_CrudController;
use App\Codes\Models\JenjangPerancang;
use App\Codes\Models\MsKegiatan;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Yajra\DataTables\DataTables;

class MsKegiatanController extends _CrudController
{
    public function __construct(Request $request)
    {
        $passingData = [
            'id' => [
                'create' => 0,
                'edit' => 0,
                'show' => 0
            ],
            'parent_id' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'general.parent',
                'custom' => ', name: "C.name"'
            ],
            'name' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ]
            ],
            'ak' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ]
            ],
            'satuan' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ]
            ],
            'jenjang_perancang_id' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'general.jenjang_perancang',
                'custom' => ', name: "B.name"'
            ],
            'status' => [
                'list' => 0,
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
            $request, 'general.ms_kegiatan', 'mskegiatan', 'MsKegiatan', 'mskegiatan',
            $passingData
        );

        $getParent = MsKegiatan::where('status', 1)->get();
        $listParent = [0 => 'Tidak memiliki Parent'];
        if($getParent) {
            foreach($getParent as $list) {
                $listParent[$list->id] = $list->name;
            }
        }
        $listJenjangPerancang = JenjangPerancang::where('status', '=', 1)->pluck('name', 'id')->toArray();

        $this->data['listAttribute']['aaSorting'] = "[0,'ASC']";

        $this->data['listSet']['parent_id'] = $listParent;
        $this->data['listSet']['jenjang_perancang_id'] = $listJenjangPerancang;
        $this->data['listSet']['status'] = get_list_status();

    }

    public function index()
    {
        $this->callPermission();

        if ($this->request->get('export') == 1) {
            $this->export();
        }

        $data = $this->data;

        $data['passing'] = collectPassingData($this->passingData);

        return view($this->listView['index'], $data);
    }

    public function dataTable()
    {
        $this->callPermission();

        $dataTables = new DataTables();

        $builder = $this->model::query()->select('ms_kegiatan.*')
            ->leftJoin('jenjang_perancang AS B', 'B.id', '=', 'ms_kegiatan.jenjang_perancang_id')
            ->leftJoin('ms_kegiatan AS C', 'C.id', '=', 'ms_kegiatan.parent_id');

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

    protected function export()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', -1);

        $getData = MsKegiatan::where('status', 1)->get();

        $temp = [];
        foreach ($getData as $list) {
            $temp[$list->parent_id][] = $list;
        }

        $getListData = $temp;

        $deepPath = 0;
        $getDeep = 0;
        foreach ($getListData[0] as $list) {
            $totalDeep = $this->checkDeep($getListData, $list, $deepPath + 1);
            if ($getDeep < $totalDeep) {
                $getDeep = $totalDeep;
            }
        }

        $listJenjangPerancang = JenjangPerancang::pluck('name', 'id');

        $sheet = false;

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator('Hendra Tjua')
            ->setLastModifiedBy('Hendra Tjua')
            ->setTitle('Laporan Rekapitulasi')
            ->setSubject('Laporan Lengkap')
            ->setDescription('Laporan Lengkap PNS');

        $sheet = $spreadsheet->setActiveSheetIndex(0);

        $row = 1;
        $column = 1;
        $sheet->setCellValueByColumnAndRow($column, $row, "Butir Kegiatan");
        $sheet->setCellValueByColumnAndRow($column + $getDeep + 1, $row, "AK");
        $sheet->setCellValueByColumnAndRow($column + $getDeep + 2, $row, "Satuan");
        $sheet->setCellValueByColumnAndRow($column + $getDeep + 3, $row, "Pelaksana");

        $deepPath = 0;
        foreach ($getListData[0] as $list) {

            $getJenjangPerancang = isset($listJenjangPerancang[$list->jenjang_perancang_id]) ? $listJenjangPerancang[$list->jenjang_perancang_id] : '';

            $row++;
            $sheet->setCellValueByColumnAndRow($column, $row, $list->name);
            $sheet->setCellValueByColumnAndRow($column + $getDeep + 1, $row, $list->ak > 0 ? $list->ak : '');
            $sheet->setCellValueByColumnAndRow($column + $getDeep + 2, $row, strlen($list->satuan) > 0 ? $list->satuan : '');
            $sheet->setCellValueByColumnAndRow($column + $getDeep + 3, $row, strlen($getJenjangPerancang) > 0 ? $getJenjangPerancang : '');

            $getRow = $this->printSheet($sheet, $row, $getListData, $list, $listJenjangPerancang, $getDeep, $deepPath + 1);
            $row = $getRow;

        }

        // Redirect output to a client’s web browser (Xls)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ms_kegiatan_' . strtotime("now") . '.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        $writer = IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');

        exit;

    }

    protected function printSheet($sheet, $row, $getListData, $data, $listJenjangPerancang, $getDeep, $deepPath)
    {
        if (isset($getListData[$data->id])) {
            foreach ($getListData[$data->id] as $list) {
                $column = 1;

                $getJenjangPerancang = isset($listJenjangPerancang[$list->jenjang_perancang_id]) ? $listJenjangPerancang[$list->jenjang_perancang_id] : '';

                $row++;
                $sheet->setCellValueByColumnAndRow($column + $deepPath, $row, $list->name);
                $sheet->setCellValueByColumnAndRow($column + $getDeep + 1, $row, $list->ak > 0 ? $list->ak : '');
                $sheet->setCellValueByColumnAndRow($column + $getDeep + 2, $row, strlen($list->satuan) > 0 ? $list->satuan : '');
                $sheet->setCellValueByColumnAndRow($column + $getDeep + 3, $row, strlen($getJenjangPerancang) > 0 ? $getJenjangPerancang : '');

                $getRow = $this->printSheet($sheet, $row, $getListData, $list, $listJenjangPerancang, $getDeep, $deepPath + 1);
                $row = $getRow;

            }
        }

        return $row;

    }

    protected function checkDeep($getListData, $data, $deepPath = 1)
    {
        $totalDeep = $deepPath;
        $getDeep = $totalDeep;
        if (isset($getListData[$data->id])) {
            foreach ($getListData[$data->id] as $list) {
                $totalDeep = $this->checkDeep($getListData, $list, $deepPath + 1);
                if ($getDeep < $totalDeep) {
                    $getDeep = $totalDeep;
                }
            }
        }
        return $totalDeep;
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
            return redirect()->route('admin.permen.show', $id);
        }
    }

}
