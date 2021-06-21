<?php

if (! function_exists('check_google_recaptcha')) {
    /**
     * Require GOOGLE_API.
     *
     * @param $response
     *
     * @return string
     */
    function check_google_recaptcha($response)
    {
        $secret = env('GOOGLE_SECRET_KEY');

        $data_passing = http_build_query(['secret' => $secret, 'response' => $response]);
        $url          = 'https://www.google.com/recaptcha/api/siteverify?' . $data_passing;
        $ch           = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $ch,CURLOPT_TIMEOUT, 10);

        $result = curl_exec($ch);

        curl_close($ch);
        $getUrl = json_decode($result);

        return $getUrl->success;
    }

    /**
     * Require GOOGLE_API_GMAPS.
     *
     * @param $lat
     * @param $lng
     *
     * @return string
     */
    function check_google_address($lat, $lng)
    {
        $getFirstAddress = '';
        if (intval($lat) !== 0 && intval($lng) !== 0) {
            $keyAPI = env('GOOGLE_API_GMAPS');

            $dataPassing  = http_build_query(['key' => $keyAPI, 'latlng' => $lat.','.$lng]);
            $url          = 'https://maps.googleapis.com/maps/api/geocode/json?' . $dataPassing;
            $ch           = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
            curl_setopt( $ch,CURLOPT_TIMEOUT, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1);

            $result = curl_exec($ch);

            curl_close($ch);
            $getData = json_decode($result);

            try {
                if (count($getData->results) > 0) {
                    foreach ($getData->results as $index => $list) {
                        if ($index == 0) {
                            $getFirstAddress = $list->formatted_address;
                        }
                    }
                }
            }
            catch (Exception $e) {
                $getFirstAddress = json_encode([
                    'message' => $e->getMessage(),
                    'result' => $getData,
                ]);
            }
        }

        return $getFirstAddress;
    }

}
