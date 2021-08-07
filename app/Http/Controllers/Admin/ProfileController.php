<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_GlobalFunctionController;
use App\Codes\Models\Golongan;
use App\Codes\Models\JenjangPerancang;
use App\Codes\Models\Pangkat;
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
    protected $passingSeketariat;
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
            'upline_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'lang' => 'Atasan',
                'type' => 'select2'
            ],
            'pangkat_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2'
            ],
            'golongan_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2'
            ],
            'jenjang_perancang_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2'
            ],
            'unit_kerja_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2'
            ],
            'gender' => [
                'validation' => [
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
                'type' => 'select2'
            ],
            'golongan_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2'
            ],
            'jenjang_perancang_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2'
            ],
            'unit_kerja_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2'
            ],
            'gender' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select'
            ],
        ]);
        $this->passingSeketariat = generatePassingData([
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
                'type' => 'select2'
            ],
        ]);
        $this->passingTimPerancang = $this->passingSeketariat;
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
                'jenjang_perancang_id' => JenjangPerancang::where('status', 1)->pluck('name', 'id')->toArray(),
                'unit_kerja_id' => UnitKerja::where('status', 1)->pluck('name', 'id')->toArray(),
                'gender' => get_list_gender(),
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
        $getSeketariat = isset($getRoleType['role_seketariat']) ?? 0;
        $getTim = isset($getRoleType['role_tim_penilai']) ?? 0;
        if ($getPerancang == 1) {
            $getPassing = $this->passingPerancang;
            $data['listSet']['upline_id'] = Users::where('id', '!=', $adminId)->where('atasan', 1)->pluck('name', 'id')->toArray();
        }
        elseif ($getAtasan == 1) {
            $getPassing = $this->passingAtasan;
        }
        elseif ($getSeketariat == 1) {
            $getPassing = $this->passingSeketariat;
        }
        elseif ($getTim == 1) {
            $getPassing = $this->passingTimPerancang;
        }
        else {
            $getPassing = $this->passing;
        }

        $data['data'] = $getData;
        $data['viewType'] = 'show';
        $data['formsTitle'] = __('general.profile');
        $data['thisLabel'] = __('general.title_show', ['field' => __('general.profile') . ' ' . $getData->name]);
        $data['thisRoute'] = 'profile';
        $data['passing'] = generatePassingData($getPassing);

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
        $getPerancang = isset($getRoleType['role_perancang']) ?? 0;
        $getAtasan = isset($getRoleType['role_atasan']) ?? 0;
        $getSeketariat = isset($getRoleType['role_seketariat']) ?? 0;
        $getTim = isset($getRoleType['role_tim_penilai']) ?? 0;
        if ($getPerancang == 1) {
            $getPassing = $this->passingPerancang;
            $data['listSet']['upline_id'] = Users::where('id', '!=', $adminId)->where('atasan', 1)->pluck('name', 'id')->toArray();
        }
        elseif ($getAtasan == 1) {
            $getPassing = $this->passingAtasan;
        }
        elseif ($getSeketariat == 1) {
            $getPassing = $this->passingSeketariat;
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

        $validator = [
            'username' => 'required|unique:admin,username,'.$adminId,
            'name' => 'required'
        ];

        $data = $this->validate($this->request, $validator);

        session()->put('admin_name', $data['name']);

        $getDate = Users::where('id', $adminId)->first();
        foreach ($validator as $key => $value) {
            $getDate->$key = $this->request->get($key);
        }
        $getDate->save();

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
