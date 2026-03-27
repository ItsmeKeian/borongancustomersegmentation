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
    $username = 'gacilloskeian02@gmail.com';
    $api_key  = '';

    // Format PH numbers
    if (preg_match('/^0\d{10}$/', $phone)) {
        $phone = '+63' . substr($phone, 1);
    }

    $header = "Hello, this is {$establishment}.\n";
    $finalMessage = $header . $message;

    $url = 'https://rest.clicksend.com/v3/sms/send';

    $payload = json_encode([
        "messages" => [
            [
                "source" => "php",
                "body"   => $finalMessage,
                "to"     => $phone,
                "from"   => "ClickSend"
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
        curl_close($ch);

        return [
            "success" => false,
            "phone" => $phone,
            "final_message" => $finalMessage,
            "error" => $error
        ];
    }

    curl_close($ch);

    $result = json_decode($response, true);

    return [
        "success" => ($http_code == 200 && ($result['response_code'] ?? '') === 'SUCCESS'),
        "phone"   => $phone,
        "final_message" => $finalMessage,
        "api_response"  => $result,
        "http_code"     => $http_code
    ];
}

?>
