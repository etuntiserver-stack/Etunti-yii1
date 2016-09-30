<?php

?>

<b>DR</b>	Draft. Only for massmail.<br>
<b>NE</b>	New. Job has been loaded but not confirmed to be sent<br>
<b>CO</b>	Confirmed. Job is confirmed to be sent at 11 o’clock next work day.<br>
<b>CA</b>	Canceled. Job is cancelled.<br>
<b>PR</b>	Processing. Job currently in been processed and printed to be mailed<br>
<b>SE</b>	Sent. Job has been sent.<br>

<br><br>
<?php


  $username = $asetukset['postita_username'];
  $password = $asetukset['postita_password'];
  $auth_string = $username . ":" . $password;

$ch = curl_init();
/*
  foreach($lasku as $l)
  {
	echo $l->postita_jobid.'<br>';

$url = 'https://postita.fi/api/job_info/'.(int)$l->postita_jobid;

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


  }
*/


$url = 'https://postita.fi/api/job_list/';

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
