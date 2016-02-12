<?php

  foreach($lasku as $l)
  {
	echo $l->postita_jobid.'<br>';
  }


$username = $asetukset['postita_username'];
$password = $asetukset['postita_password'];
$auth_string = $username . ":" . $password;


$url = 'https://postita.fi/api/job_list/';


$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERPWD, $auth_string);
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 100);

$account_info = curl_exec($ch);
$account_info = json_decode($account_info, true);

echo '<pre>';
print_r($account_info);
echo '</pre>';


curl_close($ch);


?>
