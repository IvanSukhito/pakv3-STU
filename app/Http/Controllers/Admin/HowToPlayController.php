<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_CrudController;
use Illuminate\Http\Request;

class HowToPlayController extends _CrudController
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
                    'edit' => 'required'
                ]
            ],
            'icon' => [
                'validate' => [
                    'create' => 'required',
                ],
                'type' => 'image',
                'path' => 'uploads/how_to_play/'
            ],
            'content' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
                'type' => 'texteditor'
            ],
            'orders' => [
                'validate' => [
                    'create' => 'required|numeric',
                    'edit' => 'required|numeric'
                ],
                'type' => 'number'
            ],
            'action' => [
                'create' => 0,
                'edit' => 0,
                'show' => 0,
            ]
        ];

        parent::__construct(
            $request, 'general.how_to_play', 'how-to-play', 'HowToPlay', 'how-to-play',
            $passingData
        );

    }

}
