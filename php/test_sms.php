<?php
$url = 'https://rest.clicksend.com/v3/sms/send';

$username = 'gacilloskeian02@gmail.com'; // ClickSend email
$api_key = ''; // ClickSend API key

$message = [
    "messages" => [
        [
            "source" => "php",
            "body" => "Hello Keian! This is a test SMS from your web app.",
            "to" => "+639517482469", //  PH number
            "schedule" => 0
        ]
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$api_key");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>
