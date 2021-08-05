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
                'lang' => 'ID',
            ],
            'name' => [
                'validate' => [
                    'create' => 'required',
                ],
                'edit' => 1,
                'lang' => 'Nama',
            ],
            'tanggal_start' => [
                'validate' => [
                    'create' => 'required',
                ],
                'edit' => 1,
                'list' => 0,
                'type' => 'datepicker',
            ],
            'tanggal_end' => [
                'validate' => [
                    'create' => 'required',
                ],
                'edit' => 1,
                'list' => 0,
                'type' => 'datepicker',
            ],
            'status' => [
                'validate' => [
                    'create' => 'required',
                    'update' => 'required',
                ],
                'list' => 1,
                'edit' => 1 ,
                'lang' => 'Status',
                'type' => 'select2',
            ],
//            'ms_kegiatan_id' => [
//                'validate' => [
//                    'create' => 'required',
//                ],
//                'edit' => 0,
//                'list' => 0,
//                'type' => 'select',
//                'lang' => 'Peraturan Mentri',
//            ],

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

        // $this->listView['edit'] = env('ADMIN_TEMPLATE') . '.page.permen.edit';
        $this->listView['index'] = env('ADMIN_TEMPLATE') . '.page.permen.list';
//        $this->listView['show'] = env('ADMIN_TEMPLATE') . '.page.permen.ms_kegiatan';
        $this->listView['dataTable'] = env('ADMIN_TEMPLATE') . '.page.permen.list_button';

        $this->data['listSet']['status'] = get_list_active_inactive();
    }

}
