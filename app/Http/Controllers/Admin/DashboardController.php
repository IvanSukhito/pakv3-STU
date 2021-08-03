<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    protected $data;
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->data = [
            'thisLabel' => 'Dashboard'
        ];
    }

    public function dashboard()
    {
        $data = $this->data;

        return view(env('ADMIN_TEMPLATE').'.page.dashboard', $data);
    }

}
