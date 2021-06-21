<?php

namespace App\Codes\Logic;

class _CRUD
{
    protected $model;

    public function __construct($model)
    {
        $this->model = 'App\Codes\Models\\' . $model;
    }

    public function all()
    {
        return $this->model::get();
    }

    public function store($data)
    {
        $save_data = new $this->model();
        foreach ($data as $key => $value) {
            $save_data->$key = $value;
        }
        $save_data->save();

        return $save_data;
    }

    public function show($id, $key = 'id')
    {
        return $this->model::where($key, $id)->first();
    }

    public function update($data, $id, $key = 'id')
    {
        $save_data = $this->model::where($key, $id)->first();
        foreach ($data as $key => $value) {
            $save_data->$key = $value;
        }
        $save_data->save();

        return $save_data;
    }

    public function destroy($id, $key = 'id')
    {
        $get_data = $this->model::where($key, $id)->first();
        $get_data->delete();
    }

    public function saveImageFile($list_image, $request, $destinationPath)
    {
        $data = [];

        foreach ($list_image as $image_key => $list) {

            try {
                $image = $request->file($image_key);
                if ($image && $image->getError() == 1) {
                    if ($list === true) {
                        if ($image->getSize() <= 0) {
                            $message = __('general.error_max_file_', ['bytes' => '25M', 'files' => 'Image']);
                        } else {
                            $message = __('general.error_upload_file_', ['files' => 'Image']);
                        }
                        return [
                            'success' => 0,
                            'message' => [
                                $image_key => $message
                            ]
                        ];
                    }
                }
                if ($image) {
                    $get_file_name = $image->getClientOriginalName();
                    $ext = explode('.', $get_file_name);
                    $ext = end($ext);
                    $set_file_name = md5($image_key . strtotime('now') . rand(0, 100)) . '.' . $ext;

                    $image->move($destinationPath, $set_file_name);
                    $data[$image_key] = $set_file_name;

                }
            }
            catch (\Exception $e) {
                return [
                    'success' => 0,
                    'message' => [
                        $image_key => 'Error'
                    ]
                ];
            }

        }

        return [
            'success' => 1,
            'data' => $data
        ];

    }

    public function saveImageBase64($list_image, $request, $destinationPath)
    {
        $data = [];

        foreach ($list_image as $image_key => $list) {
            try {
                $image = base64_to_jpeg($request->get($image_key));
                $set_file_name = md5($image_key.strtotime('now').rand(0, 100)).'.jpg';
                file_put_contents($destinationPath.$set_file_name, $image);
                $data[$image_key] = $set_file_name;
            }
            catch (\Exception $e) {
                return [
                    'success' => 0,
                    'message' => [
                        $image_key => 'Error'
                    ]
                ];
            }
        }

        return [
            'success' => 1,
            'data' => $data
        ];

    }

}