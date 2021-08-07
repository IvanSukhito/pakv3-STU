<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_CrudController;
use App\Codes\Models\JenjangPerancang;
use App\Codes\Models\MsKegiatan;
use App\Codes\Models\Permen;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PermenController extends _CrudController
{
    public function __construct(Request $request)
    {
        $passingData = [
            'id' => [
                'create' => 0,
                'edit' => 0,
                'show' => 0,
                'lang' => 'general.id',
            ],
            'name' => [
                'validate' => [
                    'create' => 'required',
                ],
                'lang' => 'Nama',
            ],
            'tanggal_start' => [
                'validate' => [
                    'create' => 'required',
                ],
                'list' => 0,
                'type' => 'datepicker',
            ],
            'tanggal_end' => [
                'validate' => [
                    'create' => 'required',
                ],
                'list' => 0,
                'type' => 'datepicker',
            ],
            'orders' => [
                'validate' => [
                    'create' => 'required',
                    'update' => 'required',
                ],
                'type' => 'number',
            ],
            'status' => [
                'validate' => [
                    'create' => 'required',
                    'update' => 'required',
                ],
                'type' => 'select2',
            ],
            'action' => [
                'create' => 0,
                'edit' => 0,
                'show' => 0,
                'lang' => 'Aksi',
            ]
        ];

        parent::__construct(
            $request, 'general.permen', 'permen', 'Permen', 'permen',
            $passingData
        );

        $this->listView['index'] = env('ADMIN_TEMPLATE') . '.page.permen.list';
        $this->listView['dataTable'] = env('ADMIN_TEMPLATE') . '.page.permen.list_button';

        $this->data['listSet']['status'] = get_list_active_inactive();
    }

}
