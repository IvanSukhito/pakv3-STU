<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_CrudController;
use App\Codes\Models\JenjangPerancang;
use App\Codes\Models\Kegiatan;
use App\Codes\Models\MsKegiatan;
use App\Codes\Models\Permen;
use App\Codes\Models\SuratPernyataan;
use App\Codes\Models\Users;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

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
                'lang' => 'Tanggal'
            ],
            'judul' => [
                'lang' => 'Judul'
            ],
            'ms_kegiatan_name' => [
                'show' => 0,
                'edit' => 0,
                'custom' => ', name:"B.name"',
                'lang' => 'general.ms_kegiatan'
            ],
            'ms_kegiatan_id' => [
                'list' => 0,
                'edit' => 0,
                'type' => 'select2',
                'lang' => 'general.ms_kegiatan',
            ],
            'kredit' => [
                'edit' => 1,
                'lang' => 'Kredit'
            ],
            'satuan' => [
                'edit' => 1,
            ],
            'sp_nomor' => [
                'edit' => 0,
                'custom' => ', name:"C.nomor"',
                'lang' => 'SP'
            ],
            'dupak_nomor' => [
                'edit' => 0,
                'custom' => ', name:"D.nomor"',
                'lang' => 'general.dupak'
            ],
            'dokument' => [
                'list' => 0,
                'edit' => 1,
                'type' => 'file2',
                'lang' => 'general.dokument'
            ],
            'dokumen_fisik' => [
                'list' => 0,
                'edit' => 1,
                'type' => 'file2',
                'lang' => 'general.dokumen_fisik'
            ],

            'action' => [
                'create' => 0,
                'edit' => 0,
                'show' => 0,
                'lang' => 'Aksi',
            ]
        ];

        parent::__construct(
            $request, 'general.kegiatan', 'kegiatan', 'Kegiatan', 'kegiatan',
            $passingData
        );

        $this->listView['index'] = env('ADMIN_TEMPLATE') . '.page.kegiatan.list';
        $this->listView['create'] = env('ADMIN_TEMPLATE') . '.page.kegiatan.forms';

        $this->data['listSet']['status'] = get_list_status();
        $this->data['listSet']['ms_kegiatan_id'] = MsKegiatan::pluck('name', 'id')->toArray();

    }

    public function dataTable()
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $dataTables = new DataTables();

        $builder = $this->model::query()->selectRaw('tx_kegiatan.id, tx_kegiatan.tanggal, tx_kegiatan.judul, tx_kegiatan.kredit,
        tx_kegiatan.satuan, B.name AS ms_kegiatan_name, C.nomor AS sp_nomor, D.nomor AS dupak_nomor')
            ->where('tx_kegiatan.user_id', '=', $userId)
            ->leftJoin('ms_kegiatan AS B', 'B.id', '=', 'tx_kegiatan.ms_kegiatan_id')
            ->leftJoin('tx_surat_pernyataan AS C', 'C.id', '=', 'tx_kegiatan.surat_pernyataan_id')
            ->leftJoin('dupak AS D', 'D.id', '=', 'tx_kegiatan.dupak_id');

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

    public function create()
    {
        $this->callPermission();

        $userId = session()->get('admin_id');

        $getStaff = Users::where('id', $userId)->first();
        if ($getStaff->upline_id <= 0) {
            session()->flash('message', __('general.error_no_upline'));
            session()->flash('message_alert', 1);
            return redirect()->route('admin.' . $this->route . '.index');
        }

        $getFilterPermen = Permen::where('status', 1)->pluck('name', 'id')->toArray();
        $getListPermen = [];
        foreach ($getFilterPermen as $key => $val) {
            $getListPermen[] = $val;
        }
        $getKegiatan = MsKegiatan::where('status', 1)->whereIn('permen_id', $getListPermen)->get();
        $getJenjangPerancang = JenjangPerancang::where('status', 1)->orderBy('order_high', 'ASC')->get();

        $getFilterKegiatan = [];
        foreach ($getKegiatan as $list) {
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

        $data = $this->data;

        $data['viewType'] = 'create';
        $data['formsTitle'] = __('general.title_create', ['field' => $data['thisLabel']]);
        $data['dataUser'] = $getStaff;
        $data['dataJenjangPerancang'] = $getJenjangPerancang;
        $data['dataKegiatan'] = $getKegiatan;
        $data['dataFilterPermen'] = $getFilterPermen;
        $data['dataFilterKegiatan'] = $getFilterKegiatan;
//            dd($data['dataKegiatan'] );

        return view($this->listView[$data['viewType']], $data);
    }

    public function show($id)
    {
        $this->callPermission();

        $getData = $this->crud->show($id);
        if (!$getData) {
            return redirect()->route('admin.' . $this->route . '.index');
        }

        $data = $this->data;

        $data['viewType'] = 'show';
        $data['formsTitle'] = __('general.title_show', ['field' => $data['thisLabel']]);
        $data['passing'] = collectPassingData($this->passingData, $data['viewType']);
        $data['data'] = $getData;

        return view($this->listView[$data['viewType']], $data);
    }

    public function store()
    {
        $this->callPermission();

        $kegiatan = $this->request->get('filter_kegiatan');

        $viewType = 'create';

        $userId = session()->get('admin_id');
        $getStaff = Users::where('id', $userId)->first();
        $getUser = Users::where('id', $userId)->first();
        $user_nip = $getUser->username;

        $list_ms_kegiatan = [];
        $ms_kegiatan = MsKegiatan::where('status', 1)->get();
        foreach ($ms_kegiatan as $list) {
            $list_ms_kegiatan[$list->id] = $list;
        }

        $list_surat_pernyataan_ids = [];

        $user_folder = 'user_' . preg_replace("/[^A-Za-z0-9?!]/", '', $user_nip);
        $today_date = date('Y-m-d');
        $folder_name = $user_folder . '/kegiatan/' . $today_date . '/';

        $permen = $this->request->get('filter_permen');
        $checkbox = $this->request->get('checkbox');
        $dokument = $this->request->file('dokument');
        $dokument_fisik = $this->request->file('dokument_fisik');
        $tanggal = $this->request->get('tanggal');

        $MsKegiatan = Permen::where('id',$permen)->select('tanggal_end')->get();

        $tanggalEnd = [];
        foreach($MsKegiatan as $list){
            $tanggalEnd[$list->tanggal_end] = $list->tanggal_end;
        }
        $tanggalEnd = $list;


        $deskripsi = $this->request->get('deskripsi');
        $judul = $this->request->get('judul');
        $rules = [];
        $message = [];
        if ($checkbox == null || count($checkbox) <= 0) {
            return redirect()->back()->withInput()->withErrors(
                [
                    'message' => 'Inputan Kosong'
                ]
            );
        }

        foreach ($checkbox as $key => $value) {
            if($tanggal[$key] > $tanggalEnd->tanggal_end) {
            session()->flash('message', __('general.date_has_passed'));
            session()->flash('message_alert', 1);
            return back();
        }

            $get_ms_kegiatan_name = '';
            $get_ms_kegiatan = isset($list_ms_kegiatan[$key]) ? $list_ms_kegiatan[$key] : null;
            if ($get_ms_kegiatan) {
                $get_ms_kegiatan_name = $get_ms_kegiatan->name;
            }
            $rules['tanggal.' . $key] = 'required';
            $rules['judul.' . $key] = 'required';
            $rules['deskripsi.' . $key] = '';
            $message['tanggal.' . $key . '.required'] = 'Tanggal ' . $get_ms_kegiatan_name . ' harus di isi.';
            $message['judul.' . $key . '.required'] = 'Judul ' . $get_ms_kegiatan_name . ' harus di isi.';
//            $message['deskripsi.' . $key . '.required'] = 'Deskripsi ' . $get_ms_kegiatan_name . ' harus di isi.';
            if (isset($dokument[$key]) && count($dokument[$key]) > 0) {
                foreach ($dokument[$key] as $key2 => $list) {

                    //$rules['dokument'] = 'max:2000';
                    $rules['dokument.' . $key . '.' . $key2] = '';
                    $message['dokument.' . $key . '.' . $key2 . '.required'] = 'Dokument Pendukung ' . $get_ms_kegiatan_name . ' harus di upload.';
                    $message['dokument.' . $key . '.' . $key2 . '.uploaded'] = 'Dokument Pendukung ' . $get_ms_kegiatan_name . ' gagal di upload.';
                }
            } else {
                return redirect()->back()->withInput()->withErrors(
                    [
                        'deskripsi' => 'Butuh file Dokument Pendukung minimal 1'
                    ]
                );
            }
            if (isset($dokument_fisik[$key]) && count($dokument_fisik[$key]) > 0) {
                foreach ($dokument_fisik[$key] as $key3 => $list1) {

                    //$rules['dokument'] = 'max:2000';
                    $rules['dokument_fisik.' . $key . '.' . $key3] = '';
                    $message['dokument_fisik.' . $key . '.' . $key3 . '.required'] = 'Dokument Fisik ' . $get_ms_kegiatan_name . ' harus di upload.';
                    $message['dokument_fisik.' . $key . '.' . $key3 . '.uploaded'] = 'Dokument Fisik ' . $get_ms_kegiatan_name . ' gagal di upload.';
                }
            } else {
                return redirect()->back()->withInput()->withErrors(
                    [
                        'deskripsi' => 'Butuh file Dokument Fisik minimal 1'
                    ]
                );
            }
        }

        $data = $this->validate($this->request, $rules, $message);

        foreach ($checkbox as $key => $value) {

            $total_dokument = [];
            $total_dokument_fisik = [];

            if (count($dokument[$key]) > 0) {
                foreach ($dokument[$key] as $list_doc) {
                    if ($list_doc->getError() == 1) {
                        if ($list_doc->getClientSize() <= 0) {
                            $message = 'Upload file terlalu besar, maximal hanya 2Mb';
                        } else {
                            $message = 'Ada kesalahan dalam upload dokument';
                        }
                        return redirect()->back()->withInput()->withErrors(
                            [
                                'deskripsi' => $message
                            ]
                        );
                    }

                    $get_file_name = $list_doc->getClientOriginalName();
                    $ext = explode('.', $get_file_name);
                    $file_name = reset($ext);
                    $ext = end($ext);
                    $set_file_name = preg_replace("/[^A-Za-z0-9?!]/", '_', $file_name) . '_' . date('His') . '.' . $ext;
                    $destinationPath = './uploads/' . $folder_name . $key . '/';

                    $destinationLink = 'uploads/' . $folder_name . $key . '/' . $set_file_name;

                    $list_doc->move($destinationPath, $set_file_name);

                    $total_dokument[] = [
                        'name' => $set_file_name,
                        'location' => $destinationLink
                    ];

                }
            }

            if (count($dokument_fisik[$key]) > 0) {
                foreach ($dokument_fisik[$key] as $list_doc) {
                    if ($list_doc->getError() == 1) {
                        if ($list_doc->getClientSize() <= 0) {
                            $message = 'Upload file terlalu besar, maximal hanya 2Mb';
                        } else {
                            $message = 'Ada kesalahan dalam upload dokument fisik';
                        }
                        return redirect()->back()->withInput()->withErrors(
                            [
                                'deskripsi' => $message
                            ]
                        );
                    }

                    $get_file_name = 'dokumen-fisik'. $list_doc->getClientOriginalName();
                    $ext = explode('.', $get_file_name);
                    $file_name = reset($ext);
                    $ext = end($ext);
                    $set_file_name = preg_replace("/[^A-Za-z0-9?!]/", '_', $file_name) . '_' . date('His') . '.' . $ext;
                    $destinationPath = './uploads/' . $folder_name . $key . '/';

                    $destinationLink = 'uploads/' . $folder_name . $key . '/' . $set_file_name;

                    $list_doc->move($destinationPath, $set_file_name);

                    $total_dokument_fisik[] = [
                        'name' => $set_file_name,
                        'location' => $destinationLink
                    ];

                }
            }

            $get_jenjang_perancang = JenjangPerancang::where('status', 1)->orderBy('order_high', 'ASC')->get();
            $list_jenjang_perancang = [];
            foreach ($get_jenjang_perancang as $list) {
                $list_jenjang_perancang[$list->order_high] = $list->id;
            }

            $data = [
                'user_id' => $userId,
                'ms_kegiatan_id' => $key,
                'permen_id' => $permen,
                'tanggal' => $tanggal[$key],
                'judul' => $judul[$key],
//                'deskripsi' => $deskripsi[$key],
                'deskripsi' => isset($deskripsi[$key]) ? count($deskripsi[$key]) : 0,
                'kredit' => number_format(calculate_jenjang($getStaff->jenjang_perancang_id, $list_ms_kegiatan[$key]->jenjang_perancang_id, $list_jenjang_perancang, $list_ms_kegiatan[$key]->ak), 3, '.', ''),
                'satuan' => $list_ms_kegiatan[$key]->satuan,
                'pelaksana' => isset($list_jenjang_perancang[$list_ms_kegiatan[$key]->jenjang_perancang_id]) ? $list_jenjang_perancang[$list_ms_kegiatan[$key]->jenjang_perancang_id] : $list_ms_kegiatan[$key]->jenjang_perancang_id,
                'pelaksana_id' => $list_ms_kegiatan[$key]->jenjang_perancang_id,
                'parent_id' => MsKegiatan::getLastParent($key),
                'dokument_pendukung' => json_encode($total_dokument),
                'dokument_fisik' => json_encode($total_dokument_fisik),
            ];

            $kegiatan = new Kegiatan();
            $kegiatan->fill($data);
            $kegiatan->save();

        }

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

    protected function check_surat_pernyataan($kegiatan = null, $user_id = null) {
        if($kegiatan) {
            $parent_id = isset($kegiatan->parent_id) ? $kegiatan->parent_id : null;
            if($parent_id) {
                $surat_pernyataan = SuratPernyataan::where('parent_id', $parent_id)->where('user_id', $user_id)->where('connect', 0)->whereIn('approved', [0, 9])->first();
                if($surat_pernyataan) {

                    $get_kegiatan = $surat_pernyataan->getKegiatan()->get();
                    $ids = [];
                    if($get_kegiatan)
                    {
                        foreach($get_kegiatan as $list) {
                            $ids[] = $list->id;
                        }
                    }
                    if($ids && isset($kegiatan->id)) {
                        $ids[] = $kegiatan->id;
                        $surat_pernyataan->getKegiatan()->sync($ids);
                        $kegiatan->sp = $surat_pernyataan->id;
                        $kegiatan->connect = 1;
                        $kegiatan->save();

                        if($surat_pernyataan->approved == 1) {
                            return $surat_pernyataan->id;
                        }

                    }
                }
            }
        }
        return false;
    }


}

