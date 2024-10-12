<?php
$apiKey = "goldapi-6000u1sm26alp0a-io";
$symbol = "XAU";
$curr = "EGP";
$date = "";

$myHeaders = array(
    'x-access-token: ' . $apiKey,
    'Content-Type: application/json'
);

$curl = curl_init();

$url = "https://www.goldapi.io/api/{$symbol}/{$curr}{$date}";

curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTPHEADER => $myHeaders
));

$response = curl_exec($curl);
$error = curl_error($curl);

curl_close($curl);

if ($error) {
    echo 'Error: ' . $error;
} else {
    echo $response;
}
