<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\AccessLogin;
use App\Codes\Models\Role;
use App\Codes\Models\Staffs;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccessAdminController extends Controller
{
    protected $request;
    protected $accessLogin;
    protected $data = [];

    public function __construct(Request $request, AccessLogin $accessLogin)
    {
        $this->request = $request;
        $this->accessLogin = $accessLogin;
    }

    public function getLogin()
    {
        $data = $this->data;

        return view(env('ADMIN_TEMPLATE').'.page.login', $data);
    }

    public function postLogin()
    {
        $this->validate($this->request, [
            'username' => 'required|max:191',
            'password' => 'required'
        ], [
            'username.required' => 'NIP harus di isi',
            'username.max' => 'NIP terlalu panjang',
            'password.required' => 'Password harus di isi'
        ]);

        $user = $this->accessLogin->cekLogin($this->request->get('username'),
            $this->request->get('password'), 'Users', 'username', ['status'=>1]);
        if ($user) {
            session()->flush();
            session()->put(env('APP_NAME').'admin_id', $user->id);
            session()->put(env('APP_NAME').'admin_name', $user->name);

            $getSuperAdmin = $user->superadmin;
            $getPerancang = 0;
            $getAtasan = 0;
            $getSekretariat = 0;
            $getTimPenilai = 0;
            $getStaff = Staffs::where('user_id', $user->id)->first();
            if ($getStaff) {
                $getPerancang = $getStaff->perancang;
                $getAtasan = $getStaff->atasan;
                $getSekretariat = $getStaff->sekretariat;
                $getTimPenilai = $getStaff->tim_penilai;
            }

            session()->put(env('APP_NAME').'admin_super_admin', $getSuperAdmin);
            session()->put(env('APP_NAME').'admin_perancang', $getPerancang);
            session()->put(env('APP_NAME').'admin_atasan', $getAtasan);
            session()->put(env('APP_NAME').'admin_sekretariat', $getSekretariat);
            session()->put(env('APP_NAME').'admin_tim_penilai', $getTimPenilai);

            try {
                session_start();
                $_SESSION['set_login_ck_editor'] = 1;
            }
            catch (\Exception $e) {

            }

            return redirect()->route('admin.portal');
        }
        else {
            return redirect()->back()->withInput()->withErrors(
                [
                    'error_login' => __('general.error_login')
                ]
            );
        }
    }

    public function doLogout()
    {
        try {
            session_start();
            unset($_SESSION['set_login_ck_editor']);
            session_destroy();
        }
        catch (\Exception $e) {

        }
        session()->flush();

        return redirect()->route('admin.login');
    }



}
