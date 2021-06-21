<?php

namespace App\Codes\Logic;

class AccessLogin
{
    public function __construct()
    {
    }

    /**
     * @param $email
     * @param $password
     * @param $model
     * @param string $key
     * @param array $setWhere
     * @return bool
     */
    public function cekLogin($email, $password, $model, $key = 'email', $setWhere = array())
    {
        $getModel = 'App\Codes\Models\\' . $model;

        $getUser = $getModel::where($key, $email);

        if (!empty($setWhere)) {
            foreach ($setWhere as $key => $value) {
                if (is_object($value) || is_array($value)) {
                    $getUser->whereIn($key, $value);
                }
                else {
                    $getUser->where($key, $value);
                }
            }
        }

        $getUser = $getUser->first();

        if($getUser) {
            $check_password = app('hash')->check($password, $getUser->password);
            if($check_password) {
                return $getUser;
            }
        }
        return false;
    }
}