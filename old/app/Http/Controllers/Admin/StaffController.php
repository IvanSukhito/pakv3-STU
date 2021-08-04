<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_CrudController;
use App\Codes\Models\Gender;
use App\Codes\Models\Golongan;
use App\Codes\Models\JabatanPerancang;
use App\Codes\Models\JenjangPerancang;
use App\Codes\Models\Pendidikan;
use App\Codes\Models\Staffs;
use App\Codes\Models\UnitKerja;
use App\Codes\Models\UserRegister;
use App\Codes\Models\Users;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StaffController extends _CrudController
{
    public $passingData1;
    public $passingData2;
    public $passingData3;
    public $passingData4;
    public $passingData5;

    public function __construct(Request $request)
    {
        $passingData = [
            'id' => [
                'list' => 0,
                'custom' => ', data:"id"'
            ],
            'register_id' => [
                'lang' => 'general.no'
            ],
            'name' => [
            ],
            'username' => [
                'custom' => ', name:"B.username"',
                'lang' => 'Username/NIP'
            ],
            'unit_kerja_name' => [
                'custom' => ', name:"C.name"',
                'lang' => 'general.unit_kerja'
            ],
            'jenjang_perancang_name' => [
                'custom' => ', name:"D.name"',
                'lang' => 'general.jenjang_perancang'
            ],
            'user_register_name' => [
                'custom' => ', name:"E.name"',
                'lang' => 'User Register ID'
            ],

            'status_diangkat' => [
            ],
            'action' => [
                'create' => 0,
                'edit' => 0,
                'show' => 0,
                'lang' => 'Aksi',
            ]
        ];

        $this->passingData1 = generatePassingData([

            'perancang' => [
                'type' => 'checkbox',
                'class' => 'setChecklistData'
            ],
            'atasan' => [
                'type' => 'checkbox',
                'class' => 'setChecklistData'
            ],
            'sekretariat' => [
                'type' => 'checkbox',
                'class' => 'setChecklistData'
            ],
            'tim_penilai' => [
                'type' => 'checkbox',
                'class' => 'setChecklistData'
            ],
            'status' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
                'type' => 'select'
            ],
        ]);
        $this->passingData2 = generatePassingData([
            'name' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
            ],
            'username' => [
                'validate' => [
                    'create' => 'required|min:3',
                    'edit' => 'required|min:3'
                ],
                'lang' => 'Username/NIP'
            ],
            'password' => [
                'validate' => [
                    'create' => 'required',
                ],
                'show' => 0,
                'edit' => 0,
                'type' => 'password'
            ],
            'email' => [
                'validate' => [
                    'create' => '',
                    'edit' => ''
                ],
                'classParent' => 'hideAll perancang'
            ],
            'kartu_pegawai' => [
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
        ]);
        $this->passingData3 = generatePassingData([
            'address' => [
                'lang' => 'Alamat Kantor',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'gender_id' => [
                'type' => 'select',
                'lang' => 'Jenis Kelamin',
                'classParent' => 'hideAll perancang atasan',
                'create' => 1,
            ],
            'birthdate' => [
                'type' => 'datepicker',
                'lang' => 'Tanggal Lahir',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'pob' => [
                'lang' => 'Tempat Lahir',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
        ]);
        $this->passingData4 = generatePassingData([
            'jabatan_perancang_id' => [
                'type' => 'select2',
                'lang' => 'Pangkat',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'golongan_id' => [
                'type' => 'select2',
                'lang' => 'Golongan',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'tmt_golongan' => [
                'type' => 'datepicker',
                'lang' => 'TMT Kenaikan Jenjang Terakhir',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'kenaikan_jenjang_terakhir' => [
                'type' => 'datepicker',
                'lang' => 'Kenaikan Jenjang Terakhir',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'staff_id' => [
                'type' => 'select2',
                'lang' => 'Atasan',
                'classParent' => 'hideAll perancang',
                'create' => 1,
            ],
            'jenjang_perancang_id' => [
                'type' => 'select2',
                'lang' => 'general.jenjang_perancang',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'pendidikan_id' => [
                'type' => 'select2',
                'lang' => 'general.pendidikan',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'unit_kerja_id' => [
                'type' => 'select2',
                'lang' => 'general.unit_kerja',
                'classParent' => 'hideAll perancang atasan',
                'create' => 0,
            ],
        ]);
        $this->passingData5 = generatePassingData([
            'masa_penilaian_terkahir_start' => [
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'masa_penilaian_terkahir_end' => [
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'tanggal' => [
                'lang' => 'Tanggal PAK Terakhir',
                'type' => 'datepicker',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'angka_kredit' => [
                'lang' => 'Angka Kredit Terakhir',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'nomor_pak' => [
                'lang' => 'Nomor PAK Terakhir',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'tahun_pelaksaan_diklat' => [
                'lang' => 'Isi Tahun Pelaksanaan Diklat',
                'type' => 'dateyear',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'tahun_diangkat' => [
                'lang' => 'Tahun Diangkat',
                'type' => 'dateyear',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'status_diangkat' => [
                'lang' => 'Alasan',
                'type' => 'select',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
        ]);

        parent::__construct(
            $request, 'general.staff', 'staff', 'Staffs', 'staff',
            $passingData
        );

        $getStaff = Staffs::orderBy('name', 'ASC')->get();
        $temp = [
            0 => 'Tidak ada Atasan'
        ];
        foreach ($getStaff as $list) {
            $temp[$list->id] = $list->name;
        }
        $getStaff = $temp;

        $this->data['listSet']['staff_id'] = $getStaff;
        $this->data['listSet']['jabatan_perancang_id'] = JabatanPerancang::pluck('name', 'id')->toArray();
        $this->data['listSet']['golongan_id'] = Golongan::pluck('name', 'id')->toArray();
        $this->data['listSet']['pendidikan_id'] = Pendidikan::pluck('name', 'id')->toArray();
        $this->data['listSet']['unit_kerja_id'] = UnitKerja::pluck('name', 'id')->toArray();
        $this->data['listSet']['jenjang_perancang_id'] = JenjangPerancang::pluck('name', 'id')->toArray();
        $this->data['listSet']['gender_id'] = Gender::pluck('name', 'id')->toArray();
        $this->data['listSet']['status'] = get_list_status();
        $this->data['listSet']['status_diangkat'] = get_list_status_diangkat();

        $this->listView['index'] = env('ADMIN_TEMPLATE').'.page.staff.list';
        $this->listView['create'] = env('ADMIN_TEMPLATE').'.page.staff.forms';
        $this->listView['edit'] = env('ADMIN_TEMPLATE').'.page.staff.forms';
        $this->listView['show'] = env('ADMIN_TEMPLATE').'.page.staff.forms';

    }


    public function indexAtasan()
    {
        $this->callPermission();

        $data = $this->data;

        $data['passing'] = collectPassingData($this->passingData);
        $data['flag'] = 1;

        return view($this->listView['index'], $data);
    }

    public function indexPerancang()
    {
        $this->callPermission();

        $data = $this->data;

        $data['passing'] = collectPassingData($this->passingData);
        $data['flag'] = 2;

        return view($this->listView['index'], $data);
    }

    public function indexCalonPerancang()
    {
        $this->callPermission();

        $data = $this->data;

        $data['passing'] = collectPassingData($this->passingData);
        $data['flag'] = 3;

        return view($this->listView['index'], $data);
    }

    public function indexSekretariat()
    {
        $this->callPermission();

        $data = $this->data;

        $data['passing'] = collectPassingData($this->passingData);
        $data['flag'] = 4;

        return view($this->listView['index'], $data);
    }

    public function indexTimPenilai()
    {
        $this->callPermission();

        $data = $this->data;

        $data['passing'] = collectPassingData($this->passingData);
        $data['flag'] = 5;

        return view($this->listView['index'], $data);
    }

    public function dataTable()
    {
        $this->callPermission();

        $dataTables = new DataTables();

        $builder = $this->model::query()->selectRaw('user_staffs.id, register_id, user_staffs.name, B.username,
            C.name AS unit_kerja_name, D.name AS jenjang_perancang_name, status_diangkat, user_register_id AS user_register_name')
            ->leftJoin('users AS B', 'B.id', '=', 'user_staffs.user_id')
            ->leftJoin('unit_kerja AS C', 'C.id', '=', 'user_staffs.unit_kerja_id')
            ->leftJoin('jenjang_perancang AS D', 'D.id', '=', 'user_staffs.jenjang_perancang_id')
            ->leftJoin('user_register AS E', 'E.id', '=', 'user_staffs.user_register_id');

        $getFlag = intval($this->request->get('flag'));
        switch ($getFlag) {
            case 1 :
                $builder = $builder->where('atasan', '=', 1);
                break;
            case 2 :
                $builder = $builder->where('perancang', '=', 1);
                break;
            case 3 :
                $builder = $builder->where('jenjang_perancang_id', '=', 6);
                break;
            case 4 :
                $builder = $builder->where('sekretariat', '=', 1);
                break;
            case 5 :
                $builder = $builder->where('tim_penilai', '=', 1);
                break;
        }

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

        $data = $this->data;

        $data['viewType'] = 'create';
        $data['formsTitle'] = __('general.title_create', ['field' => $data['thisLabel']]);
        $data['passing1'] = collectPassingData($this->passingData1, $data['viewType']);
        $data['passing2'] = collectPassingData($this->passingData2, $data['viewType']);
        $data['passing3'] = collectPassingData($this->passingData3, $data['viewType']);
        $data['passing4'] = collectPassingData($this->passingData4, $data['viewType']);
        $data['passing5'] = collectPassingData($this->passingData5, $data['viewType']);

        return view($this->listView[$data['viewType']], $data);
    }

    public function show($id)
    {
        $this->callPermission();

        $getData = $this->crud->show($id);
        if (!$getData) {
            return redirect()->route('admin.' . $this->route . '.index');
        }

        $getUserData = Users::where('id', $getData->user_id)->first();
        $getData->username = $getUserData->username;
        $getData->email = $getUserData->email;
        $getData->status = $getUserData->status;

        $data = $this->data;

        $data['viewType'] = 'show';
        $data['formsTitle'] = __('general.title_show', ['field' => $data['thisLabel']]);
        $data['passing1'] = collectPassingData($this->passingData1, $data['viewType']);
        $data['passing2'] = collectPassingData($this->passingData2, $data['viewType']);
        $data['passing3'] = collectPassingData($this->passingData3, $data['viewType']);
        $data['passing4'] = collectPassingData($this->passingData4, $data['viewType']);
        $data['passing5'] = collectPassingData($this->passingData5, $data['viewType']);
        $data['data'] = $getData;

        return view($this->listView[$data['viewType']], $data);
    }

    public function edit($id)
    {
        $this->callPermission();

        $getData = $this->crud->show($id);
        if (!$getData) {
            return redirect()->route('admin.' . $this->route . '.index');
        }

        $getUserData = Users::where('id', $getData->user_id)->first();
        $getData->username = $getUserData->username;
        $getData->email = $getUserData->email;
        $getData->status = $getUserData->status;

        $data = $this->data;

        $data['viewType'] = 'edit';
        $data['formsTitle'] = __('general.title_edit', ['field' => $data['thisLabel']]);
        $data['passing1'] = collectPassingData($this->passingData1, $data['viewType']);
        $data['passing2'] = collectPassingData($this->passingData2, $data['viewType']);
        $data['passing3'] = collectPassingData($this->passingData3, $data['viewType']);
        $data['passing4'] = collectPassingData($this->passingData4, $data['viewType']);
        $data['passing5'] = collectPassingData($this->passingData5, $data['viewType']);
        $data['data'] = $getData;

        return view($this->listView[$data['viewType']], $data);
    }

    public function store()
    {
        $this->callPermission();

        $viewType = 'create';

//        $validateVersion = 1;
//        if ($this->request->get('atasan') == 1) {
//            $validateVersion = 2;
//        }
//        if ($this->request->get('perancang') == 1) {
//            $validateVersion = 3;
//        }

        $haveTop = intval($this->request->get('top')) == 1 ? 1 : 0;

        $setValidateArray = [
            'status' => 'required',
            'name' => 'required|min:1',
            'password' => 'required',
          'email' => 'required|unique:users,email',
            'gender_id' => 'required',
            'username' => 'required',
            'staff_id' => 'required',
        ];
//       switch ($validateVersion) {
//            case 2 :
//                $setValidateArray['gender_id'] = 'required|numeric';
//                $setValidateArray['unit_kerja_id'] = 'required|numeric';
//                break;
//            case 3 :
//                if ($haveTop == 0) {
//                    $setValidateArray['staff_id'] = 'required|numeric';
//                }
//                $setValidateArray['email'] = 'email';
//                $setValidateArray['kartu_pegawai'] = '';
//                $setValidateArray['address'] = '';
//                $setValidateArray['birthdate'] = 'date_format:Y-m-d';
//                $setValidateArray['pob'] = '';
//                $setValidateArray['jabatan_perancang_id'] = 'required|numeric';
//                $setValidateArray['golongan_id'] = 'required|numeric';
//                $setValidateArray['tmt_golongan'] = 'date_format:Y-m-d';
//                $setValidateArray['kenaikan_jenjang_terakhir'] = 'date_format:Y-m-d';
//                $setValidateArray['jenjang_perancang_id'] = 'required|numeric';
//                $setValidateArray['pendidikan_id'] = 'required|numeric';
//                $setValidateArray['masa_penilaian_terkahir_start'] = 'date_format:Y-m-d';
//                $setValidateArray['masa_penilaian_terkahir_end'] = 'date_format:Y-m-d';
//                $setValidateArray['tanggal'] = 'date_format:Y-m-d';
//                $setValidateArray['angka_kredit'] = '';
//                $setValidateArray['nomor_pak'] = '';
//                $setValidateArray['tahun_pelaksaan_diklat'] = 'date_format:Y';
//                $setValidateArray['tahun_diangkat'] = 'date_format:Y';
//                $setValidateArray['status_diangkat'] = '';
//                break;
//        }

        $data = $this->validate($this->request, $setValidateArray);

        $data['top'] = intval($this->request->get('top')) == 1 ? 1 : 0;
        $data['perancang'] = intval($this->request->get('perancang')) == 1 ? 1 : 0;
        $data['atasan'] = intval($this->request->get('atasan')) == 1 ? 1 : 0;
        $data['sekretariat'] = intval($this->request->get('sekretariat')) == 1 ? 1 : 0;
        $data['tim_penilai'] = intval($this->request->get('tim_penilai')) == 1 ? 1 : 0;

        $setValidateArray['top'] = '';
        $setValidateArray['perancang'] = '';
        $setValidateArray['atasan'] = '';
        $setValidateArray['sekretariat'] = '';
        $setValidateArray['tim_penilai'] = '';

//

        $user = new Users();
        foreach ($setValidateArray as $key => $value) {
            if (in_array($key, ['username', 'email', 'name', 'status'])) {
                $user->$key = $data[$key];
            }
            else if (in_array($key, ['password'])) {
                $user->$key = bcrypt($data[$key]);
            }
        }
        $user->status = 1;
        $user->save();

        $staff = new Staffs();
        $staff->user_id = $user->id;
        $staff->register_id = $user->id;
        foreach ($setValidateArray as $key => $value) {
            if (!in_array($key, ['username', 'email', 'status', 'password'])) {
                $staff->$key = $data[$key];
            }
        }
        $staff->save();

        $id = $staff->id;

        if ($staff->jenjang_perancang_id != 6) {
            $jk = $data['gender_id'] == 1 ? '01' : '02';
            $staff->save();
        }

        if($this->request->ajax()){
            return response()->json(['result' => 1, 'message' => __('general.success_add')]);
        }
        else {
            session()->flash('message', __('general.success_edit'));
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

        $validateVersion = 1;
        if ($this->request->get('atasan') == 1) {
            $validateVersion = 2;
        }
        if ($this->request->get('perancang') == 1) {
            $validateVersion = 3;
        }

        $haveTop = intval($this->request->get('top')) == 1 ? 1 : 0;

        $setValidateArray = [
            'status' => 'required|min:1',
            'name' => 'required|min:1',
            'username' => 'required|min:3|unique:users,username,'.$getData->user_id
        ];
        switch ($validateVersion) {
            case 2 :
                $setValidateArray['gender_id'] = 'required|numeric';
                $setValidateArray['unit_kerja_id'] = 'required|numeric';
                break;
            case 3 :
                if ($haveTop == 0) {
                    $setValidateArray['staff_id'] = 'required|numeric';
                }
                $setValidateArray['email'] = '';
                $setValidateArray['kartu_pegawai'] = '';
                $setValidateArray['address'] = '';
                $setValidateArray['birthdate'] = '';
                $setValidateArray['pob'] = '';
                 $setValidateArray['unit_kerja_id'] = 'required|numeric';
                $setValidateArray['jabatan_perancang_id'] = 'required|numeric';
                $setValidateArray['golongan_id'] = 'required|numeric';
                $setValidateArray['tmt_golongan'] = '';
                $setValidateArray['kenaikan_jenjang_terakhir'] = '';
                $setValidateArray['jenjang_perancang_id'] = 'required|numeric';
                $setValidateArray['pendidikan_id'] = 'required|numeric';
                $setValidateArray['masa_penilaian_terkahir_start'] = '';
                $setValidateArray['masa_penilaian_terkahir_end'] = '';
                $setValidateArray['tanggal'] = '';
                $setValidateArray['angka_kredit'] = '';
                $setValidateArray['nomor_pak'] = '';
                $setValidateArray['tahun_pelaksaan_diklat'] = '';
                $setValidateArray['tahun_diangkat'] = '';
                $setValidateArray['status_diangkat'] = '';
                $setValidateArray['gender_id'] = 'required';
                break;
        }

        $data = $this->validate($this->request, $setValidateArray);

        $data['top'] = intval($this->request->get('top')) == 1 ? 1 : 0;
        $data['perancang'] = intval($this->request->get('perancang')) == 1 ? 1 : 0;
        $data['atasan'] = intval($this->request->get('atasan')) == 1 ? 1 : 0;
        $data['sekretariat'] = intval($this->request->get('sekretariat')) == 1 ? 1 : 0;
        $data['tim_penilai'] = intval($this->request->get('tim_penilai')) == 1 ? 1 : 0;


        $setValidateArray['top'] = '';
        $setValidateArray['perancang'] = '';
        $setValidateArray['atasan'] = '';
        $setValidateArray['sekretariat'] = '';
        $setValidateArray['tim_penilai'] = '';

        if ($haveTop == 1) {
            $data['staff_id'] = 0;
        }
        else {
            $getStaff = Staffs::where('id', $data['staff_id'])->first();
            if (!$getStaff) {
                return redirect()->back()->withInput()->withErrors(
                    [
                        'staff_id' => __('Atasan is required')
                    ]
                );
            }
        }

        $user = Users::where('id', $getData->user_id)->first();
        foreach ($setValidateArray as $key => $value) {
            if (in_array($key, ['username', 'email', 'name', 'status'])) {
                $user->$key = $data[$key];
            }
        }
        $user->save();

        foreach ($setValidateArray as $key => $value) {
            if (!in_array($key, ['username', 'email', 'status', 'password'])) {
                $getData->$key = $data[$key];
            }
            else if (in_array($key, ['birthdate', 'tmt_golongan', 'kenaikan_jenjang_terakhir', 'masa_penilaian_terkahir_start', 'masa_penilaian_terkahir_end', 'tanggal'])) {
                $getData->$key = strtotime($data[$key]) > 10000 ? date('Y-m-d', strtotime($data[$key])) : null;
            }
            else if (in_array($key, ['tahun_pelaksaan_diklat', 'tahun_diangkat'])) {
                $getData->$key = intval($data[$key]);
            }
        }
        $getData->save();

        if ($getData->jenjang_perancang_id != 6) {
            $jk = $data['gender_id'] == 1 ? '01' : '02';
            $thn_diangkat = $data['tahun_diangkat'];
            //$getData->register_id = $id.'-'.$jk.'-'.$thn_diangkat;
            $getData->register_id = $user->id;
            $getData->save();
        }

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



}
