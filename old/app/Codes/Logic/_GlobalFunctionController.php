<?php

namespace App\Codes\Logic;

use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

class _GlobalFunctionController extends Controller
{
    protected $data;
    protected $permission;
    protected $module;
    protected $passingData;

    protected function setValidateData($getListCollectData, $formsType, $id = null)
    {
        $validate = [];
        foreach ($getListCollectData as $key => $setData) {
            if (strlen($setData['validate'][$formsType]) > 0) {
                $getListValidate = explode('|', $setData['validate'][$formsType]);
                $getValidate = [];
                foreach ($getListValidate as $listValidate) {
                    if(strpos($listValidate, 'unique') === 0) {
                        $setValidate = $listValidate;
                        if ($id != null) {
                            $setValidate .= ','.$id;
                        }
                        $getValidate[] = $setValidate;
                    }
                    else {
                        $getValidate[] = $listValidate;
                    }
                }

                $validate[$key] = implode('|', $getValidate);

            }
            else {

                $validate[$key] = '';

            }
        }

        return $validate;
    }

    protected function getCollectedData($getListCollectData, $formsType, $data, $getData = null)
    {
        foreach ($getListCollectData as $key => $setData) {
            if (in_array($setData['type'], ['password'])) {
                if (strlen($data[$key]) > 0) {
                    $data[$key] = bcrypt($data[$key]);
                }
                else {
                    unset($data[$key]);
                }
            }
            else if (in_array($setData['type'], ['money'])) {
                $data[$key] = clear_money_format($data[$key]);
            }
            else if (in_array($setData['type'], ['checkbox'])) {
                if (isset($data[$key])) {
                    $data[$key] = 1;
                }
                else {
                    $data[$key] = 0;
                }
            }
            else if (in_array($setData['type'], ['tagging'])) {
                $data[$key] = implode(',', $data[$key]);
            }
        }

        foreach ($getListCollectData as $image_key => $setData) {
            if (in_array($setData['type'], ['image'])) {
                unset($data[$image_key]);
                $image = $this->request->file($image_key);
                if ($image) {
                    if ($image->getError() != 1) {

                        $get_file_name = $image->getClientOriginalName();
                        $ext = explode('.', $get_file_name);
                        $ext = end($ext);
                        $set_file_name = md5(strtotime('now').rand(0, 100)).'.'.$ext;
                        $destinationPath = $setData['path'];
                        if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])) {
                            if ($getData && strlen($getData->$image_key) > 0 && is_file($destinationPath.$getData->$image_key)) {
                                unlink($destinationPath.$getData->$image_key);
                            }

                            $image->move($destinationPath, $set_file_name);
                            $img = Image::make('./'.$destinationPath.$set_file_name);
                            $img->resize(800, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            $img->save();

                            $data[$image_key] = $set_file_name;
                        }
                    }
                }
            }
            else if (in_array($setData['type'], ['video', 'file'])) {
                unset($data[$image_key]);
                $image = $this->request->file($image_key);
                if ($image) {
                    if ($image->getError() != 1) {
                        $get_file_name = $image->getClientOriginalName();
                        $ext = explode('.', $get_file_name);
                        $ext = end($ext);
                        $set_file_name = md5(strtotime('now').rand(0, 100)).'.'.$ext;
                        $destinationPath = $setData['path'];

                        if ($getData && strlen($getData->$image_key) > 0 && is_file($destinationPath.$getData->$image_key)) {
                            unlink($destinationPath.$getData->$image_key);
                        }

                        $image->move($destinationPath, $set_file_name);
                        $data[$image_key] = $set_file_name;
                    }
                }
            }
        }

        return $data;
    }

    protected function callPermission() {
        $this->permission = getDetailPermission($this->module);

        $this->permission = array(
            'create' => true,
            'edit' => true,
            'show' => true,
            'list' => true,
            'destroy' => true
        );

        $this->data['permission'] = $this->permission;
    }

}
