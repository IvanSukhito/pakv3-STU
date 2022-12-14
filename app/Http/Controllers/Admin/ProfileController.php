<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_GlobalFunctionController;
use App\Codes\Models\Golongan;
use App\Codes\Models\JabatanPerancang;
use App\Codes\Models\JenjangPerancang;
use App\Codes\Models\Pangkat;
use App\Codes\Models\Pendidikan;
use App\Codes\Models\Role;
use App\Codes\Models\UnitKerja;
use App\Codes\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProfileController extends _GlobalFunctionController
{
    protected $data;
    protected $request;
    protected $passing;
    protected $passingPerancang;
    protected $passingAtasan;
    protected $passingSekretariat;
    protected $passingTimPerancang;
    protected $passingPassword;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->passing = generatePassingData([
            'username' => [
                'extra' => [
                    'edit' => ['disabled' => true]
                ],
                'lang' => 'NIP'
            ],
            'name' => [
                'validation' => [
                    'edit' => 'required'
                ]
            ],
            'email' => [
                'validation' => [
                    'edit' => 'required|email'
                ],
                'type' => 'email'
            ]
        ]);
        $this->passingPerancang = generatePassingData([
            'name' => [
                'validation' => [
                    'edit' => 'required'
                ]
            ],
            'username' => [
                'extra' => [
                    'edit' => ['disabled' => true]
                ],
                'lang' => 'NIP'
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
                'lang' => 'general.tmt_pangkat_golongan'
            ],
            'jabatan_perancang_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'general.jabatan'
            ],
            'tmt_jabatan' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'datepicker',
                'lang' => 'general.tmt_jabatan'
            ],
            'pendidikan_id' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required',
                ],
                'type' => 'select',
                'lang' => 'general.gelar_akademik',
            ],
            'tempat_lahir' => [
                'validation' => [
                    'edit' => 'required',
                ],
                'lang' => 'general.tempat_lahir',
            ],
            'tgl_lahir' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'datepicker',
                'lang' => 'general.tanggal_lahir',
            ],
            'gender' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select',
                'lang' => 'general.gender'
            ],
            'instansi_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'general.nama_instansi',
                'show' => 0
            ],
            'unit_kerja_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'general.unit_kerja'
            ],
            'kartu_pegawai' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'lang' => 'general.kartu_pegawai',
            ],
            'alamat_kantor' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'lang' => 'general.alamat_kantor',
            ],
            'email' => [
                'validation' => [
                    'edit' => 'required|email'
                ],
                'type' => 'email'
            ],
            'tahun_pelaksanaan_diklat' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'datepicker',
                'lang' => 'general.tahun_pelaksanaan_diklat',
            ],
            'upline_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'lang' => 'general.atasan',
                'type' => 'select2'
            ],
            'masa_penilaian_ak_terakhir' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'datepicker',
                'lang' => 'general.masa_penilaian_ak_terakhir',
            ],
            'angka_kredit_terakhir' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'lang' => 'general.angka_kredit_terakhir',
            ],
            'jenjang_perancang_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'general.jenjang_perancang'
            ],
            'status' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
                'type' => 'select'
                ],
        ]);
        $this->passingAtasan = generatePassingData([
            'username' => [
                'extra' => [
                    'edit' => ['disabled' => true]
                ],
                'lang' => 'NIP'
            ],
            'name' => [
                'validation' => [
                    'edit' => 'required'
                ]
            ],
            'email' => [
                'validation' => [
                    'edit' => 'required|email'
                ],
                'type' => 'email'
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
            'gender' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select',
                'lang' => 'general.gender'
            ],
        ]);
        $this->passingSekretariat = generatePassingData([
            'username' => [
                'extra' => [
                    'edit' => ['disabled' => true]
                ],
                'lang' => 'NIP'
            ],
            'name' => [
                'validation' => [
                    'edit' => 'required'
                ]
            ],
            'email' => [
                'validation' => [
                    'edit' => 'required|email'
                ],
                'type' => 'email'
            ],
            'unit_kerja_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'general.unit_kerja'
            ],
        ]);
        $this->passingTimPerancang = $this->passingSekretariat;
        $this->passingPassword = generatePassingData([
            'old_password' => [
                'type' => 'password',
                'validation' => [
                    'edit' => 'required'
                ]
            ],
            'password' => [
                'type' => 'password',
                'validation' => [
                    'edit' => 'required|confirmed'
                ]
            ],
            'password_confirmation' => [
                'type' => 'password',
                'validation' => [
                    'edit' => 'required'
                ]
            ]
        ]);

        $this->data = [
            'listSet' => [
                'pangkat_id' => Pangkat::where('status', 1)->pluck('name', 'id')->toArray(),
                'golongan_id' => Golongan::where('status', 1)->pluck('name', 'id')->toArray(),
                'jabatan_perancang_id' => JabatanPerancang::where('status', 1)->pluck('name', 'id')->toArray(),
                'jenjang_perancang_id' => JenjangPerancang::where('status', 1)->pluck('name', 'id')->toArray(),
                'pendidikan_id' => Pendidikan::where('status', 1)->pluck('name', 'id')->toArray(),
                'unit_kerja_id' => UnitKerja::where('status', 1)->pluck('name', 'id')->toArray(),
                'gender' => get_list_gender(),
                'status' => get_list_status(),
            ]
        ];

    }


    public function profile()
    {
        $data = $this->data;
        $adminId = session()->get('admin_id');
        $getData = Users::where('id', $adminId)->first();
        $roleId = $getData->role_id;
        $role = Cache::remember('role'.$roleId, env('SESSION_LIFETIME'), function () use ($roleId) {
            return Role::where('id', '=', $roleId)->first();
        });
        $getRoleType = json_decode($role->permission_data, true);
        $getPerancang = isset($getRoleType['role_perancang']) ?? 0;
        $getAtasan = isset($getRoleType['role_atasan']) ?? 0;
        $getSekretariat = isset($getRoleType['role_sekretariat']) ?? 0;
        $getTim = isset($getRoleType['role_tim_penilai']) ?? 0;
        if ($getPerancang == 1) {
            $getPassing = $this->passingPerancang;
            $data['listSet']['upline_id'] = Users::where('id', '!=', $adminId)->where('atasan', 1)->pluck('name', 'id')->toArray();
            $getPerancangData = false;
        }
        elseif ($getAtasan == 1) {
            $getPassing = $this->passingAtasan;
            $getPerancangData = Users::selectRaw('users.id, users.name, users.username as username, users.email, users.upline_id,
        users.tempat_lahir, users.tgl_lahir, users.kartu_pegawai, users.gender, C.name AS pangkat_id, D.name as golongan_id,
        E.name as jenjang_perancang_id, F.name as unit_kerja_id, G.name as instansi_id, H.name as jabatan_perancang_id, B.name AS role,
        users.alamat_kantor, users.status, users.tmt_pangkat, users.tmt_jabatan, users.pendidikan_id,
        users.tahun_pelaksanaan_diklat,users.masa_penilaian_ak_terakhir, users.angka_kredit_terakhir')
                ->where('users.perancang', '=', 1)
                ->where('users.upline_id', '=', $adminId)
                ->leftJoin('role AS B', 'B.id', '=', 'users.role_id')
                ->leftJoin('pangkat AS C', 'C.id', '=', 'users.pangkat_id')
                ->leftJoin('golongan as D', 'D.id','=', 'users.golongan_id')
                ->leftJoin('jenjang_perancang as E','E.id','=','users.jenjang_perancang_id')
                ->leftJoin('unit_kerja as F','F.id','=','users.unit_kerja_id')
                ->leftJoin('instansi as G','G.id','=','users.instansi_id')
                ->leftJoin('jabatan_perancang as H','H.id','=','users.jabatan_perancang_id')->get();
        }
        elseif ($getSekretariat == 1) {
            $getPassing = $this->passingSekretariat;
            $getPerancangData = false;
        }
        elseif ($getTim == 1) {
            $getPassing = $this->passingTimPerancang;
            $getPerancangData = false;
        }
        else {
            $getPassing = $this->passing;
            $getPerancangData = false;
        }

        $data['data'] = $getData;
        $data['viewType'] = 'show';
        $data['formsTitle'] = __('general.profile');
        $data['thisLabel'] = __('general.title_show', ['field' => __('general.profile') . ' ' . $getData->name]);
        $data['thisRoute'] = 'profile';
        $data['passing'] = generatePassingData($getPassing);
        $data['getPerancangData'] = $getPerancangData;

        return view(env('ADMIN_TEMPLATE').'.page.profile.show', $data);
    }

    public function getProfile()
    {
        $data = $this->data;
        $adminId = session()->get('admin_id');
        $getData = Users::where('id', $adminId)->first();
        $roleId = $getData->role_id;
        $role = Cache::remember('role'.$roleId, env('SESSION_LIFETIME'), function () use ($roleId) {
            return Role::where('id', '=', $roleId)->first();
        });
        $getRoleType = json_decode($role->permission_data, true);
        $getPerancang = $getRoleType['role_perancang'] ?? 0;
        $getAtasan = isset($getRoleType['role_atasan']) ?? 0;
        $getSekretariat = isset($getRoleType['role_sekretariat']) ?? 0;
        $getTim = isset($getRoleType['role_tim_penilai']) ?? 0;
        if ($getPerancang == 1) {
            $getPassing = $this->passingPerancang;
            $data['listSet']['upline_id'] = Users::where('id', '!=', $adminId)->where('atasan', 1)->pluck('name', 'id')->toArray();
        }
        elseif ($getAtasan == 1) {
            $getPassing = $this->passingAtasan;
        }
        elseif ($getSekretariat == 1) {
            $getPassing = $this->passingSekretariat;
        }
        elseif ($getTim == 1) {
            $getPassing = $this->passingTimPerancang;
        }
        else {
            $getPassing = $this->passing;
        }

        $data['data'] = $getData;
        $data['formsTitle'] = __('general.title_edit', ['field' => __('general.profile') . ' ' . $getData->name]);
        $data['thisLabel'] = __('general.title_show', ['field' => __('general.profile') . ' ' . $getData->name]);
        $data['thisRoute'] = 'profile';
        $data['viewType'] = 'edit';
        $data['passing'] = generatePassingData($getPassing);

        return view(env('ADMIN_TEMPLATE').'.page.profile.edit', $data);
    }

    public function postProfile()
    {
        $adminId = session()->get('admin_id');
        $getData = Users::where('id', $adminId)->first();
        $roleId = $getData->role_id;
        $role = Cache::remember('role'.$roleId, env('SESSION_LIFETIME'), function () use ($roleId) {
            return Role::where('id', '=', $roleId)->first();
        });
        $getRoleType = json_decode($role->permission_data, true);
        $getPerancang = isset($getRoleType['role_perancang']) ?? 0;
        $getAtasan = isset($getRoleType['role_atasan']) ?? 0;
        $getSekretariat = isset($getRoleType['role_sekretariat']) ?? 0;
        $getTim = isset($getRoleType['role_tim_penilai']) ?? 0;
        if ($getPerancang == 1) {
            $getPassing = $this->passingPerancang;
            $data['listSet']['upline_id'] = Users::where('id', '!=', $adminId)->where('atasan', 1)->pluck('name', 'id')->toArray();
        }
        elseif ($getAtasan == 1) {
            $getPassing = $this->passingAtasan;
        }
        elseif ($getSekretariat == 1) {
            $getPassing = $this->passingSekretariat;
        }
        elseif ($getTim == 1) {
            $getPassing = $this->passingTimPerancang;
        }
        else {
            $getPassing = $this->passing;
        }

        $viewType = 'edit';
        $getListCollectData = collectPassingData($getPassing, $viewType);
        $validate = $this->setValidateData($getListCollectData, $viewType, $adminId);
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

        if ($getPerancang != 1) {
            $data['upline_id'] = 0;
        }

        Users::where('id', $adminId)->update($data);

        session()->put('admin_name', $data['name']);

        session()->flash('message', __('general.success_update'));
        session()->flash('message_alert', 2);

        return redirect()->route('admin.profile');
    }

    public function getPassword()
    {
        $adminId = session()->get('admin_id');
        $data = $this->data;
        $getData = Users::where('id', $adminId)->first();

        $viewType = 'edit';

        $data['data'] = $getData;
        $data['formsTitle'] = __('general.title_edit', ['field' => __('general.password') . ' ' . $getData->name]);
        $data['thisLabel'] = __('general.title_show', ['field' => __('general.profile') . ' ' . $getData->name]);
        $data['thisRoute'] = 'profile';
        $data['viewType'] = $viewType;
        $data['passing'] = collectPassingData($this->passingPassword, $viewType);

        return view(env('ADMIN_TEMPLATE').'.page.profile.password', $data);

    }

    public function postPassword(Request $request)
    {
        $adminId = session()->get('admin_id');

        $viewType = 'edit';
        $getListCollectData = collectPassingData($this->passingPassword, $viewType);
        $validate = $this->setValidateData($getListCollectData, $viewType, $adminId);
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

        $account = Users::where('id', $adminId)->first();
        if(!$account) {
            return redirect()->route('admin.profile');
        }

        if(!app('hash')->check($data['old_password'], $account->password)) {
            return redirect()->back()->withInput()->withErrors(
                [
                    'password' => __('general.error_old_password')
                ]
            );
        }

        $account = Users::where('id', $adminId)->first();
        $account->password = app('hash')->make($data['password']);
        $account->save();

        session()->flash('message', __('general.success_update'));
        session()->flash('message_alert', 2);

        return redirect()->route('admin.profile');
    }

}
