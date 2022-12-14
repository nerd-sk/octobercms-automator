<?php
$name     = $argv[1];
$password = $argv[2];
$time = time();
$method = 'POST';
$path = '/v1/user/[user-id]/hosting/[hosting-id]/db';
$api = 'https://rest.websupport.sk';
$query = ''; // query part is optional and may be empty
$apiKey = 'your-api-key';
$secret = 'your-secret';
$canonicalRequest = sprintf('%s %s %s', $method, $path, $time);
$signature = hash_hmac('sha1', $canonicalRequest, $secret);

$postData = [
    "domain"=> "[your-domain]", 
    "dbName"=> "[prefix]_".$name, 
    "dbType"=> "mariadb103", 
    "dbUser"=> "[prefix]_".$name, 
    "dbPassword" => $password, 
    "defaultCollation"=> "utf8_general_ci", 
    "note"=> $name
]; 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, sprintf('%s%s%s', $api, $path, $query));
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, $apiKey.':'.$signature);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Date: ' . gmdate('Ymd\THis\Z', $time),
]);
 
$response = curl_exec($ch);
curl_close($ch);
 
echo $response;