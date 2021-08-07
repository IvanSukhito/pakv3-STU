<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_GlobalFunctionController;
use App\Codes\Models\Users;
use Illuminate\Http\Request;

class ProfileController extends _GlobalFunctionController
{
    protected $data;
    protected $request;
    protected $passingPerancang;
    protected $passingAtasan;
    protected $passingSeketariat;
    protected $passingTimPerancang;
    protected $passingPassword;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->passingPerancang = generatePassingData([
        ]);
        $this->passingAtasan = generatePassingData([
            'name' => [
                'validation' => [
                    'edit' => 'required'
                ]
            ],
            'email' => [
                'validation' => [
                    'edit' => 'required|email'
                ]
            ],
            'pangkat_id' => [
                'validation' => [
                    'edit' => 'required'
                ]
            ],
            'golongan_id' => [
                'validation' => [
                    'edit' => 'required'
                ]
            ],
            'jenjang_perancang_id' => [
                'validation' => [
                    'edit' => 'required'
                ]
            ],
            'unit_kerja_id' => [
                'validation' => [
                    'edit' => 'required'
                ]
            ],
        ]);
        $this->passingSeketariat = generatePassingData([
            'username' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'lang' => 'general.nik'
            ],
            'name' => [
                'validation' => [
                    'edit' => 'required'
                ]
            ],
            'email' => [
                'validation' => [
                    'edit' => 'required|email'
                ]
            ],
            'unit_kerja_id' => [
                'validation' => [
                    'edit' => 'required'
                ]
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
        $this->data = [];
    }

    public function profile()
    {
        $data = $this->data;
        $admin_id = session()->get('admin_id');
        $get_data = Users::where('id', $admin_id)->first();

        $data['data'] = $get_data;
        $data['viewType'] = 'show';
        $data['formsTitle'] = __('general.profile');
        $data['thisLabel'] = __('general.title_show', ['field' => __('general.profile') . ' ' . $get_data->name]);
        $data['thisRoute'] = 'profile';
        $data['passing'] = generatePassingData([
            'name' => [],
            'username' => []
        ]);

        return view(env('ADMIN_TEMPLATE').'.page.profile.show', $data);
    }

    public function getProfile()
    {
        $data = $this->data;
        $admin_id = session()->get('admin_id');
        $get_data = Users::where('id', $admin_id)->first();

        $data['data'] = $get_data;
        $data['formsTitle'] = __('general.title_edit', ['field' => __('general.profile') . ' ' . $get_data->name]);
        $data['thisLabel'] = __('general.title_show', ['field' => __('general.profile') . ' ' . $get_data->name]);
        $data['thisRoute'] = 'profile';
        $data['viewType'] = 'edit';
        $data['passing'] = generatePassingData([
            'name' => [
                'validation' => [
                    'edit' => 'required'
                ]
            ],
            'username' => [
                'validation' => [
                    'edit' => 'required'
                ]
            ]
        ]);

        return view(env('ADMIN_TEMPLATE').'.page.profile.edit', $data);
    }

    public function postProfile()
    {
        $admin_id = session()->get('admin_id');

        $validator = [
            'username' => 'required|unique:admin,username,'.$admin_id,
            'name' => 'required'
        ];

        $data = $this->validate($this->request, $validator);

        session()->put('admin_name', $data['name']);

        $getDate = Users::where('id', $admin_id)->first();
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
        $admin_id = session()->get('admin_id');
        $data = $this->data;
        $get_data = Users::where('id', $admin_id)->first();

        $viewType = 'edit';

        $data['data'] = $get_data;
        $data['formsTitle'] = __('general.title_edit', ['field' => __('general.password') . ' ' . $get_data->name]);
        $data['thisLabel'] = __('general.title_show', ['field' => __('general.profile') . ' ' . $get_data->name]);
        $data['thisRoute'] = 'profile';
        $data['viewType'] = $viewType;
        $data['passing'] = collectPassingData($this->passingPassword, $viewType);

        return view(env('ADMIN_TEMPLATE').'.page.profile.password', $data);

    }

    public function postPassword(Request $request)
    {
        $admin_id = session()->get('admin_id');

        $viewType = 'edit';
        $getListCollectData = collectPassingData($this->passingPassword, $viewType);
        $validate = $this->setValidateData($getListCollectData, $viewType, $admin_id);
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

        $account = Users::where('id', $admin_id)->first();
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

        $account = Users::where('id', $admin_id)->first();
        $account->password = app('hash')->make($data['password']);
        $account->save();

        session()->flash('message', __('general.success_update'));
        session()->flash('message_alert', 2);

        return redirect()->route('admin.profile');
    }

}
