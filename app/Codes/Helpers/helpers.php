<?php
if ( ! function_exists('create_slugs')) {
    function create_slugs($string, $replace = '-')
    {
        $string = trim(strtolower($string));
        $string = preg_replace("/[^a-z0-9 -]/", "", $string);
        $string = preg_replace("/\s+/", $replace, $string);
        $string = preg_replace("/-+/", $replace, $string);
        $string = preg_replace("/[^a-zA-Z0-9]/", $replace, $string);
        return $string;
    }
}

if ( ! function_exists('base64_to_jpeg'))
{
    function base64_to_jpeg($data) {
        $data = str_replace('data:image/jpeg;base64,', '', $data);
        $data = str_replace('data:image/png;base64,', '', $data);
        $data = str_replace('[removed]', '', $data);
        $data = str_replace(' ', '+', $data);
        $data = base64_decode($data);

        return $data;
    }
}

if ( ! function_exists('clear_money_format')) {
    function clear_money_format($money) {
        return $money = preg_replace('/,/', '', $money);
    }
}

if ( ! function_exists('generateRandomString')) {
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if ( ! function_exists('generate_code_verification')) {
    function generate_code_verification($email, $length = 12) {
        $randString = md5($email.strtotime("now"));
        $randLength = strlen($randString);
        if ($randLength < $length) {
            $randString .= generateRandomString($randLength - $length);
        }
        else {
            $totalSub = $randLength - $length;
            if ($totalSub > 0) {
                $randString = substr($randString, rand(0, $totalSub), $length);
            }
        }
        return $randString;
    }
}

if ( ! function_exists('getStartAndEndDate')) {
    function getStartAndEndDate($week, $year) {
        $week = intval($week);
        $year = intval($year);
        $dto = new DateTime();
        $dto->setISODate($year, $week);
        if((0 == $year % 4) & (0 != $year % 100) | (0 == $year % 400))
        {
         $dto->modify('-1 days');
        }
        $ret['week_start'] = $dto->format('Y-m-d');
        $dto->modify('+6 days');
        $ret['week_end'] = $dto->format('Y-m-d');
        return $ret;
    }
}

if ( ! function_exists('moneyFormatWithK')) {
    function moneyFormatWithK($number) {
        $number = $number/1000;
        if(preg_match('/^[0-9]+\.[0-9]{2}$/', $number))
            return number_format($number, 2).'K';
        elseif(preg_match('/^[0-9]+\.[0-9]{1}$/', $number))
            return number_format($number, 1).'K';
        else
            return number_format($number, 0).'K';
    }
}

if ( ! function_exists('distanceFormatWithK')) {
    function distanceFormatWithK($number) {
        if ($number < 1000) {
            return number_format($number, 0).' M';
        }
        $number = $number/1000;
        $number = number_format($number, 2, '.', '');
        if(preg_match('/^[0-9]+\.[0-9]{2}$/', $number))
            return number_format($number, 2).' Km';
        elseif(preg_match('/^[0-9]+\.[0-9]{1}$/', $number))
            return number_format($number, 1).' Km';
        else
            return number_format($number, 0).' Km';
    }
}

if ( ! function_exists('getRandomNumber')) {
    function getRandomNumber($len = "15")
    {
        $better_token = $code=sprintf("%0".$len."d", mt_rand(1, str_pad("", $len,"9")));
        return $better_token;
    }
}

if ( ! function_exists('generateNewCode')) {
    function generateNewCode($length = 6, $caseSensitive = 0)
    {
        if ($caseSensitive == 1) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        else if ($caseSensitive == 2) {
            $characters = '0123456789';
        }
        else {
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (! function_exists('generatePassingData')) {
    function generatePassingData($data)
    {
        $result = [];
        foreach ($data as $fieldName => $fieldValue) {
            $result[$fieldName] = [
                'create' => isset($fieldValue['create']) ? $fieldValue['create'] : true,
                'edit' => isset($fieldValue['edit']) ? $fieldValue['edit'] : true,
                'show' => isset($fieldValue['show']) ? $fieldValue['show'] : true,
                'list' => isset($fieldValue['list']) ? $fieldValue['list'] : true,
                'type' => isset($fieldValue['type']) ? $fieldValue['type'] : 'text',
                'lang' => isset($fieldValue['lang']) ? $fieldValue['lang'] : 'general.'.$fieldName,
                'custom' => isset($fieldValue['custom']) ? $fieldValue['custom'] : '',
                'extra' => isset($fieldValue['extra']) ? $fieldValue['extra'] : [],
                'validate' => [
                    'create' => isset($fieldValue['validate']['create']) ? $fieldValue['validate']['create'] : '',
                    'edit' => isset($fieldValue['validate']['edit']) ? $fieldValue['validate']['edit'] : ''
                ],
                'class' => isset($fieldValue['class']) ? $fieldValue['class'] : '',
                'classParent' => isset($fieldValue['classParent']) ? $fieldValue['classParent'] : '',
                'value' => isset($fieldValue['value']) ? $fieldValue['value'] : '',
                'path' => isset($fieldValue['path']) ? $fieldValue['path'] : '',
                'message' => isset($fieldValue['message']) ? $fieldValue['message'] : ''
            ];
        }
        return $result;
    }
}

if (! function_exists('collectPassingData')) {
    function collectPassingData($data, $flag = 'list')
    {
        $result = array();
        foreach ($data as $fieldName => $fieldValue) {
            if ($fieldValue[$flag]) {
                $result[$fieldName] = $fieldValue;
            }
        }
        return $result;
    }
}

if ( ! function_exists('calculate_jenjang')) {
    function calculate_jenjang($jenjang_perancang_id, $get_jenjang_kegiatan_id, $list_jenjang_perancang, $ak)
    {
        $getOwner = 0;
        $getJenjang = 0;
        if($list_jenjang_perancang) {
            foreach($list_jenjang_perancang as $list) {
                if($list->id == $jenjang_perancang_id) {
                    $getOwner = $list->order_high;
                }
                if($list->id == $get_jenjang_kegiatan_id) {
                    $getJenjang = $list->order_high;
                }
            }
        }

        if($getOwner == 0 || $getJenjang == 0) {
            return $ak;
        }
        else if($getJenjang > $getOwner) {
            $ak = $ak * 0.8;
        }

        return $ak;
    }
}

if ( ! function_exists('set_deep_ms_kegiatan')) {
    function set_deep_ms_kegiatan($data, $getDeep = 1)
    {
        $getChildDeep = 0;
        foreach ($data as $list) {
            if ($list['have_child'] == 1) {
                $tempDeep = set_deep_ms_kegiatan($list['child'], $getDeep + 1);
                if ($getChildDeep < $tempDeep) {
                    $getChildDeep = $tempDeep;
                }
            }
        }
        if ($getDeep < $getChildDeep) {
            $getDeep = $getChildDeep;
        }
        return $getDeep;

    }
}

if ( ! function_exists('check_deep_ms_kegiatan')) {
    function check_deep_ms_kegiatan($data, $master, $deep = 1)
    {
        $getDeep = $deep;
        foreach ($data as $list) {;
            if (isset($master[$list])) {
                $tempDeep = check_deep_ms_kegiatan($master[$list], $master, $deep + 1);
                if ($getDeep < $tempDeep) {
                    $getDeep = $tempDeep;
                }
            }
        }
        return $getDeep;
    }
}


