<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_CrudController;
use Illuminate\Http\Request;

class PageController extends _CrudController
{
    protected $passingDataHome;

    public function __construct(Request $request)
    {
        $passingData = [
            'id' => [
                'create' => 0,
                'edit' => 0,
                'show' => 0,
                'list' => 0
            ],
            'name' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ]
            ],
            'header_image' => [
                'list' => 0,
                'validate' => [
                    'create' => 'required',
                ],
                'type' => 'image',
                'path' => 'uploads/page/'
            ],
            'content' => [
                'list' => 0,
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
                'type' => 'texteditor'
            ],
            'action' => [
                'create' => 0,
                'edit' => 0,
                'show' => 0,
            ]
        ];

        $this->passingDataHome = generatePassingData([
            'id' => [
                'create' => 0,
                'edit' => 0,
                'show' => 0,
                'list' => 0
            ],
            'name' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ]
            ],
            'header_image' => [
                'list' => 0,
                'validate' => [
                    'create' => 'required',
                ],
                'type' => 'image',
                'path' => 'uploads/page/'
            ],
            'left_image' => [
                'list' => 0,
                'validate' => [
                    'create' => 'required',
                ],
                'type' => 'image',
                'path' => 'uploads/page/'
            ],
            'content' => [
                'list' => 0,
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
                'type' => 'texteditor',
                'lang' => 'general.right_content'
            ],
            'bottom_title' => [
                'list' => 0,
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
            ],
            'bottom_content' => [
                'list' => 0,
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
                'type' => 'texteditor'
            ],
            'action' => [
                'create' => 0,
                'edit' => 0,
                'show' => 0,
            ]
        ]);

        parent::__construct(
            $request, 'general.page', 'page', 'Page', 'page',
            $passingData
        );

    }

    public function edit($id)
    {
        $this->callPermission();

        $getData = $this->crud->show($id);
        if (!$getData) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        if ($getData->type == 'home') {
            $getAdditional = json_decode($getData->additional, true);
            $getData->bottom_title = $getAdditional['bottom_title'] ?? '';
            $getData->bottom_content = $getAdditional['bottom_content'] ?? '';
            $getData->left_image = $getAdditional['left_image'] ?? '';
        }

        $data = $this->data;

        $data['viewType'] = 'edit';
        $data['formsTitle'] = __('general.title_edit', ['field' => $data['thisLabel']]);
        $data['passing'] = $getData->type == 'home' ? collectPassingData($this->passingDataHome, $data['viewType']) :
            collectPassingData($this->passingData, $data['viewType']);
        $data['data'] = $getData;

        return view($this->listView[$data['viewType']], $data);
    }

    public function show($id)
    {
        $this->callPermission();

        $getData = $this->crud->show($id);
        if (!$getData) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        if ($getData->type == 'home') {
            $getAdditional = json_decode($getData->additional, true);
            $getData->bottom_title = $getAdditional['bottom_title'] ?? '';
            $getData->bottom_content = $getAdditional['bottom_content'] ?? '';
            $getData->left_image = $getAdditional['left_image'] ?? '';
        }

        $data = $this->data;

        $data['viewType'] = 'show';
        $data['formsTitle'] = __('general.title_show', ['field' => $data['thisLabel']]);
        $data['passing'] = $getData->type == 'home' ? collectPassingData($this->passingDataHome, $data['viewType']) :
            collectPassingData($this->passingData, $data['viewType']);
        $data['data'] = $getData;

        return view($this->listView[$data['viewType']], $data);
    }

    public function update($id)
    {
        $this->callPermission();

        $viewType = 'edit';

        $getData = $this->crud->show($id);
        if (!$getData) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        $getListCollectData = $getData->type == 'home' ? collectPassingData($this->passingDataHome, $viewType) :
            collectPassingData($this->passingData, $viewType);

        $validate = $this->setValidateData($getListCollectData, $viewType, $id);
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

        if ($getData->type == 'home') {
            $data = $this->getCollectedData($getListCollectData, $viewType, $data, $getData);
            $getAdditional = json_decode($getData->additional, true);
            $oldLeftImage = $getAdditional['left_image'] ?? '';
            $saveData = [
                'bottom_title' => $data['bottom_title'] ?? '',
                'bottom_content' => $data['bottom_content'] ?? '',
                'left_image' => $data['left_image'] ?? $oldLeftImage
            ];

            if (isset($data['bottom_title'])) {
                unset($data['bottom_title']);
            }
            if (isset($data['bottom_content'])) {
                unset($data['bottom_content']);
            }
            if (isset($data['left_image'])) {
                unset($data['left_image']);
            }

            $data['additional'] = json_encode($saveData);

        }
        else {
            $data = $this->getCollectedData($getListCollectData, $viewType, $data, $getData);
        }

//        $data['updated_by'] = session()->get($this->rootRoute.'_name');

        $getData = $this->crud->update($data, $id);

        $id = $getData->id;

        if($this->request->ajax()){
            return response()->json(['result' => 1, 'message' => __('general.success_edit_', ['field' => $this->data['thisLabel']])]);
        }
        else {
            session()->flash('message', __('general.success_edit_', ['field' => $this->data['thisLabel']]));
            session()->flash('message_alert', 2);
            return redirect()->route($this->rootRoute.'.' . $this->route . '.show', $id);
        }
    }

}
