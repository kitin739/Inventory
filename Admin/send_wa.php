<?php
include "config.php";

function sendWa($target, $message) {
    global $FONNTE_TOKEN;

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.fonnte.com/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array(
            "target" => $target,
            "message" => $message,
        ),
        CURLOPT_HTTPHEADER => array(
            "Authorization: $FONNTE_TOKEN"
        ),
    ));

    $response = curl_exec($curl);
    if (curl_errno($curl)) {
        return "Error: " . curl_error($curl);
    }
    curl_close($curl);
    return $response;
}
