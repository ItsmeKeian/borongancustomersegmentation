<?php
/**
 * Send SMS via ClickSend with a header (establishment name) and body (campaign message)
 *
 * @param string $phone Recipient phone number
 * @param string $message Campaign message body
 * @param string $establishment Establishment name to use in header
 * @return bool true if sent successfully, false otherwise
 */
function sendSMS($phone, $message, $establishment = "Our Store") {
    $username = 'gacilloskeian02@gmail.com'; // Your ClickSend email
    $api_key  = '83FE3B89-34FD-3491-D9A6-5E0A8B74F375'; // Your ClickSend API key

    // ✅ Convert PH numbers like 09171234567 → +639171234567
    if (preg_match('/^0\d{10}$/', $phone)) {
        $phone = '+63' . substr($phone, 1);
    }

    // ✅ Build final message with header + body
    $header = "Hello, this is {$establishment}.\n";
    $finalMessage = $header . $message;

    $url = 'https://rest.clicksend.com/v3/sms/send';

    $payload = json_encode([
        "messages" => [
            [
                "source" => "php",
                "body"   => $finalMessage,
                "to"     => $phone,
                "from"   => "ClickSend" // optional sender name
            ]
        ]
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$api_key");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        file_put_contents('sms_log.txt', date('[Y-m-d H:i:s] ') . "CURL ERROR: $error\n", FILE_APPEND);
        curl_close($ch);
        return false;
    }

    curl_close($ch);

    $result = json_decode($response, true);

    // ✅ Log the response for debugging
    file_put_contents(
        'sms_log.txt',
        date('[Y-m-d H:i:s] ') .
        "Phone: $phone | HTTP: $http_code | Response: " . $response . PHP_EOL,
        FILE_APPEND
    );

    // ✅ Check if message was successfully queued
    if ($http_code == 200 && isset($result['response_code']) && $result['response_code'] === 'SUCCESS') {
        return true;
    } else {
        return false;
    }
}
?>
