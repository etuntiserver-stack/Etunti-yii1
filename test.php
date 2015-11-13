<?php

$username = 'Sivex';
$password = 'Etunti2000';
$auth_string = $username . ":" . $password;


$account_info_url = 'https://postita.fi/api/account_info/';
$send_url = 'https://postita.fi/api/send/';
$send_finvoice_url = 'https://'.$auth_string.'@postita.fi/api/job_list/';

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $account_info_url);
curl_setopt($ch, CURLOPT_USERPWD, $auth_string);
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 100);

$account_info = curl_exec($ch);
$account_info = json_decode($account_info, true);





$data = array('status' => 'CO');
curl_setopt($ch, CURLOPT_URL, $send_finvoice_url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); 

$send_response = curl_exec($ch);
if (curl_errno($ch)) {
  echo "\n\ncURL error number: " . curl_errno($ch);
  echo "\n\ncURL error: " . curl_error($ch);
}
$send_response = json_decode($send_response, true);

echo '<pre>';
print_r($send_response);
echo '</pre>';

curl_close($ch);
?>
