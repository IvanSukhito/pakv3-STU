<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_CrudController;
use App\Codes\Models\Staffs;
use App\Codes\Models\UnitKerja;
use App\Codes\Models\UserRegister;
use App\Codes\Models\Users;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserRegisteredController extends _CrudController
{
    public function __construct(Request $request)
    {
        $passingData = [
            'id' => [
                'create' => 0,
                'edit' => 0,
                'show' => 0
            ],
            'name' => [
                'validate' => [
                    'create' => 'required',
                ],
                'edit' => 1,
                'lang' => 'Nama',
            ],
            'telp' => [
                'validate' => [
                    'create' => 'required',
                ],
                'edit' => 0,
                'lang' => 'Nomer Handphone',
            ],
            'email' => [
                'validate' => [
                    'create' => 'required',
                ],
                'edit' => 1,
            ],
            'file' => [
                'validate' => [
                    'create' => 'required',

                ],
                'type' => 'file_download',
                'path' => 'uploads/register/',
                'lang' => 'Surat Permohonan',
                'list' => 0,
                'show' => 1,
                'edit' => 0,
            ],
            'dokumen_lampiran' => [
                'validate' => [
                    'create' => 'required',

                ],
                'type' => 'file_download',
                'path' => 'uploads/register/',
                'lang' => 'Dokumen Lampiran',
                'list' => 0,
                'show' => 1,
                'edit' => 0,
            ],

            'status' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
                'type' => 'select',
                'edit' => 0,
            ],

            'create_staff_status' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
                'type' => 'select',
                'edit' => 0,
                'create' => 0,
                'lang' => 'Status Staff',
            ],

            'password' => [
                'validate' => [

                    'edit' => 'required'
                ],
                'type' => 'password',
                'edit' => 1,
                'list' => 0,
            ],

            'username' => [
                'validate' => [

                    'edit' => 'required'
                ],

                'edit' => 1,
                'list' => 0,
            ],
            'unit_kerja_id' => [
                'lang' => 'general.nama_instansi',
                'edit' => 1,
                'type' => 'select'
            ],

            'action' => [
                'create' => 0,
                'edit' => 0,
                'show' => 0,
                'lang' => 'Aksi',
            ]
        ];

        $this->passingData1 = generatePassingData([
            'top' => [
                'type' => 'checkbox',
                'lang' => 'Jabatan Tertinggi',
                'class' => 'setChecklistData',
                'edit' => 0,
            ],
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
//        $this->passingData4 = generatePassingData([
//
////            'unit' => [
////                'create' => 0,
////                'lang'  => 'general.unit_kerja',
////            ],
//        ]);

        parent::__construct(
            $request, 'general.user_registered', 'user-registered', 'UserRegister', 'user-registered',
            $passingData
        );

        $this->data['listSet']['status'] = get_list_status_register();
        $this->data['listSet']['unit_kerja_id'] = UnitKerja::pluck('name', 'id')->toArray();

        $this->data['listSet']['create_staff_status'] = status_staff();

        $this->listView['dataTable'] = env('ADMIN_TEMPLATE').'.page.user_registered.list_button';
        $this->listView['show'] = env('ADMIN_TEMPLATE').'.page.user_registered.show';
        $this->listView['edit'] = env('ADMIN_TEMPLATE').'.page.user_registered.create_staff';
    }


    public function dataTable()
    {
        $this->callPermission();

        $dataTables = new DataTables();

        $builder = $this->model::query()->select('*');


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


    public function approved($id)
    {
        $this->callPermission();

        $getData = $this->crud->show($id);
        if (!$getData) {
            return redirect()->route('admin.' . $this->route . '.index');
        }

        $getData->status = 1;
        $getData->save();

        $data = $this->data;

        $data['viewType'] = 'edit';
        $data['formsTitle'] = __('general.title_edit', ['field' => $data['thisLabel']]);
        $data['data'] = $getData;

        session()->flash('message', __('Berhasil Approved'));
        session()->flash('message_alert', 2);
        return back();
    }

    public function rejected($id)
    {
        $this->callPermission();

        $getData = $this->crud->show($id);
        if (!$getData) {
            return redirect()->route('admin.' . $this->route . '.index');
        }

        $getData->status = 2;
        $getData->save();

        $data = $this->data;

        $data['viewType'] = 'edit';
        $data['formsTitle'] = __('general.title_edit', ['field' => $data['thisLabel']]);
        $data['data'] = $getData;

        session()->flash('message', __('Berhasil Rejected user'));
        session()->flash('message_alert', 1);
        return back();
    }

    public function createStaff($id)
    {

        $cek = UserRegister::where('id', $id)->where('status', 1)->first();
        if(!$cek){
            session()->flash('message', __('User belum di setujui'));
            session()->flash('message_alert', 2);
            return redirect()->route('admin.user-registered.index');
        }


//        $this->callPermission();
//
//        $getData = $this->crud->show($id);
//        if (!$getData) {
//            return redirect()->route('admin.' . $this->route . '.index');
//        }
//
////        $getData->status = 3;
////        $getData->save();
//
//        $data = $this->data;
//
//        $data['viewType'] = 'edit';
//        $data['formsTitle'] = __('general.title_edit', ['field' => $data['thisLabel']]);
//        $data['data'] = $getData;
//
//        session()->flash('message', __('Berhasil Rejected user'));
//        session()->flash('message_alert', 1);
//        return back();

        $getData = $this->crud->show($id);
        if (!$getData) {
            return redirect()->route('admin.' . $this->route . '.index');
        }

        $getUserData = UserRegister::where('id', $getData->id)->first();

        $data = $this->data;


        $data['viewType'] = 'edit';
        $data['formsTitle'] = __('general.title_edit', ['field' => $data['thisLabel']]);
        $data['passing'] = collectPassingData($this->passingData, $data['viewType']);
        $data['passing1'] = collectPassingData($this->passingData1, $data['viewType']);
     //   $data['passing2'] = collectPassingData($this->passingData2, $data['viewType']);
//        $data['passing3'] = collectPassingData($this->passingData3, $data['viewType']);
      //  $data['passing4'] = collectPassingData($this->passingData4, $data['viewType']);
//        $data['passing5'] = collectPassingData($this->passingData5, $data['viewType']);
        $data['data'] = $getData;

        return view($this->listView[$data['viewType']], $data);
    }

    public function storeDataStaff($id)
    {

        $viewType = 'create';

        $data = $this->data;

//        $validateVersion = 1;
//        if ($this->request->get('atasan') == 1) {
//            $validateVersion = 2;
//        }
//        if ($this->request->get('perancang') == 1) {
//            $validateVersion = 3;
//        }

        $haveTop = intval($this->request->get('top')) == 1 ? 1 : 0;

        $setValidateArray = [
            'status' => 'required|min:1',
            'name' => 'required|min:1',
            'username' => 'required|min:3|unique:users,username',
            'email' => 'required|min:3|unique:users,email',
            'password' => 'required',

        ];


        $data = $this->validate($this->request, $setValidateArray);

        $setValidateArray['top'] = '';
        $setValidateArray['perancang'] = '';
        $setValidateArray['atasan'] = '';
        $setValidateArray['sekretariat'] = '';
        $setValidateArray['tim_penilai'] = '';
        $setValidateArray['status'] = ' ';

        if ($haveTop == 1) {
            $data['staff_id'] = 0;
        }


        $user = new Users();
        $user->username = $this->request->get('username');
        $user->name = $this->request->get('name');
        $user->password = bcrypt($this->request->get('password'));
        $user->email = $this->request->get('email');
        $user->status = $this->request->get('status');
        $user->save();


        $staff = new Staffs();
        $staff->user_id = $user->id;
        $staff->user_register_id = $id;
        $staff->name = $this->request->get('name');
        $staff->top = intval($this->request->get('top')) == 1 ? 1 : 0;
        $staff->perancang = intval($this->request->get('perancang')) == 1 ? 1 : 0;
        $staff->atasan = intval($this->request->get('atasan')) == 1 ? 1 : 0;
        $staff->sekretariat = intval($this->request->get('sekretariat')) == 1 ? 1 : 0;
        $staff->tim_penilai = intval($this->request->get('tim_penilai')) == 1 ? 1 : 0;

        $unit_kerja = new UnitKerja();
        $unit_kerja->name = $this->request->get('unit_kerja_id');
        $unit_kerja->save();

        $user_register = UserRegister::where('id', $id)->update([
            'create_staff_status' => 1,
        ]);


//        foreach ($setValidateArray as $key => $value) {
//            dd($data[$key]);
//            if (in_array($key, ['username', 'email', 'status', 'password', 'name'])) {
//                $staff->$key = $data[$key];
//            }
//        }
        $staff->save();

//        $id = $staff->id;
//
//        if ($staff->jenjang_perancang_id != 6) {
//            $jk = $data['gender_id'] == 1 ? '01' : '02';
//            $thn_diangkat = $data['tahun_diangkat'];
//            $staff->register_id = $id.'-'.$jk.'-'.$thn_diangkat;
//            $staff->save();
//        }

        if($this->request->ajax()){
            return response()->json(['result' => 1, 'message' => __('general.success_add')]);
        }
        else {
            session()->flash('message', __('general.success_add'));
            session()->flash('message_alert', 2);
            return redirect()->route('admin.' . $this->route . '.index');
        }
    }


}
