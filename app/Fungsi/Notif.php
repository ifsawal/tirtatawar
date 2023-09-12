<?php


namespace App\Fungsi;


class Notif
{
    public static function Kirim($ke, $judul, $body, $icon = "", $url = "")
    {

        $postdata = json_encode(
            [
                'notification' =>
                [
                    'title' => $judul,
                    'color' => '#0a6d7f',
                    'body' => $body,
                    'icon' => $icon,
                    'click_action' => $url,
                ],
                'to' => $ke
            ]
        );

        $opts = array(
            'http' =>
            [
                'method'  => 'POST',
                'header'  => 'Content-type: application/json' . "\r\n"
                    . 'Authorization: key=' . config('external.fcm_key_server') . "\r\n",
                'content' => $postdata
            ]
        );

        $context  = stream_context_create($opts);

        $result = file_get_contents('https://fcm.googleapis.com/fcm/send', false, $context);
        if ($result) {
            return json_decode($result);
        } else return false;
    }
}
