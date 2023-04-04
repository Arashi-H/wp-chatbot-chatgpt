<?php

$dTemperature = 0.9;
$iMaxTokens = 100;
$top_p = 1;
$frequency_penalty = 0.0;
$presence_penalty = 0.0;
$OPENAI_API_KEY = "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";
$sModel = "text-davinci-003";
$prompt = "greeting to the user according to the time";
$ch = curl_init();
$headers  = [
    'Accept: application/json',
    'Content-Type: application/json',
    'Authorization: Bearer ' . $OPENAI_API_KEY . ''
];

$postData = [
    'model' => $sModel,
    'prompt' => str_replace('"', '', $prompt),
    'temperature' => $dTemperature,
    'max_tokens' => $iMaxTokens,
    'top_p' => $top_p,
    'frequency_penalty' => $frequency_penalty,
    'presence_penalty' => $presence_penalty,
    'stop' => '[" Human:", " AI:"]',
];

curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

$result = curl_exec($ch);
$decoded_json = json_decode($result, true);

print_r($decoded_json['choices'][0]['text']);



?>