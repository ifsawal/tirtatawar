<?php


namespace App\Fungsi;


class Flip
{

    public static function gangguan($bank) //apakah perawatan / maintanace
    {
        $ch = curl_init();
        $secret_key = config('external.key_flip');
        $url_flip_umum = config('external.url_flip_umum');

        curl_setopt($ch, CURLOPT_URL, $url_flip_umum . "general/banks?code=" . $bank);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/x-www-form-urlencoded"
        ));

        curl_setopt($ch, CURLOPT_USERPWD, $secret_key . ":");

        $response = curl_exec($ch);
        curl_close($ch);

        return ($response);
    }


    public static function perawatan() //apakah perawatan / maintanace
    {
        $ch = curl_init();
        $secret_key = config('external.key_flip');
        $url_flip_umum = config('external.url_flip_umum');

        curl_setopt($ch, CURLOPT_URL, $url_flip_umum . "general/maintenance");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/x-www-form-urlencoded"
        ));

        curl_setopt($ch, CURLOPT_USERPWD, $secret_key . ":");

        $response = curl_exec($ch);
        curl_close($ch);

        return ($response);
    }


    public static function create($title, $jumlah, $bank, $nama, $email, $alamat, $jen)
    {


        $ch = curl_init();
        $secret_key = config('external.key_flip');
        $url_flip = config('external.url_flip');

        curl_setopt($ch, CURLOPT_URL,  $url_flip . "pwf/bill");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_POST, TRUE);

        $payloads = [
            "title" => $title,
            "amount" => $jumlah,
            "type" => "SINGLE",
            "sender_bank" => $bank,
            "sender_bank_type" => $jen,
            "step" => 3,
            "sender_name" => $nama,
            "sender_email" => $email,
            "sender_address" => $alamat,


            // "expired_date" => "2022-12-30 15:50:00",
            // "redirect_url" => "https://someurl.com",
            // "is_address_required" => 0,
            // "is_phone_number_required" => 0
        ];

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payloads));

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/x-www-form-urlencoded"
        ));

        curl_setopt($ch, CURLOPT_USERPWD, $secret_key . ":");

        $response = curl_exec($ch);
        curl_close($ch);

        return ($response);
    }
}
