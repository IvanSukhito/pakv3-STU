<?php

if ( ! function_exists('api_send_email_send_grid')) {
    /**
     * @param string $email_from
     * @param string $email_to
     * @param string $email_subject
     * @param string $email_content
     * @return bool|string
     */
    function api_send_email_send_grid($email_from = 'test@example.com', $email_to = 'test@example.com', $email_subject = 'SendGrid PHP Library', $email_content = '<html<p>some text here</p></html>')
    {
        try {
            $email = new \SendGrid\Mail\Mail();
            $email->setFrom($email_from);
            $email->setSubject($email_subject);
            $email->addTo($email_to);
//            $email->addContent(
//                "text/plain", "and easy to do anywhere, even with PHP"
//            );
            $email->addContent(
                "text/html", $email_content
            );
            $sendgrid = new \SendGrid(env('SENDGRID_API'));
            $response = $sendgrid->send($email);
//            var_dump($response->statusCode(), $response->headers(), $response->body()); die();
            if ($response->statusCode() >= 200 && $response->statusCode() <= 299) {
                return true;
            }
            else {
                $getError = json_decode($response->body());
                return $getError->errors[0]->message;
            }
//            print $response->statusCode() . "\n";
//            print_r($response->headers());
//            print $response->body() . "\n";
            return true;
        } catch (\SendGrid\Mail\TypeException $e) {
//            echo 'Caught exception: ',  $e->getMessage(), "\n";
            return $e->getMessage();
        } catch (\Exception $e) {
//            echo 'Caught exception: ',  $e->getMessage(), "\n";
            return $e->getMessage();
        }

    }
}

if ( ! function_exists('api_send_email')) {
    /**
     * @param string $user_id
     * @param string $userName
     * @param string $email_from
     * @param string $email_to
     * @param string $email_subject
     * @param string $email_content
     * @return int|string
     */
    function api_send_email($user_id, $userName, $email_from = 'test@example.com', $email_to = 'test@example.com', $email_subject = 'SendGrid PHP Library', $email_content = '<html<p>some text here</p></html>')
    {
        $result = api_send_email_send_grid($email_from, $email_to, $email_subject, $email_content);
        App\Codes\Models\LogEmail::create([
            'user_id' => $user_id,
            'user_name' => $userName,
            'type' => 'Forgot',
            'from' => $email_from,
            'to' => $email_to,
            'subject' => $email_subject,
            'message' => $email_content,
            'response' => $result,
            'status' => $result !== true ? 2 : 1
        ]);
    }
}
