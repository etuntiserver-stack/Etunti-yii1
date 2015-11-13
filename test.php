<?php
/*
$ch = curl_init();
$data = array('cid'=>'1008168', 'apicode'=>'kvrj44mqp9');

curl_setopt($ch, CURLOPT_URL, 'https://beta2.trustpoint.fi/API/statusupdates.php');
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$result = curl_exec($ch);
*/

$xml ='<?xml version="1.0" encoding="utf-8"?>
<result>
     <status>
         <jobid>3081412</jobid>
         <statusid>P3603682</statusid>
         <billnum>21979215</billnum>
         <statustype>payment</statustype>
         <statustime>2015-11-13 00:00:00</statustime>
         <statusref>Maksettu toimeksiantajalle</statusref>
         <statustext>1</statustext>
         <statuscode></statuscode>
         <paydate>2015-11-13</paydate>
         <amount>96.72</amount>
     </status>
     <status>
         <jobid>3081428</jobid>
         <statusid>P3603683</statusid>
         <billnum>28979215</billnum>
         <statustype>payment</statustype>
         <statustime>2015-11-13 00:00:00</statustime>
         <statusref>Maksettu toimeksiantajalle</statusref>
         <statustext>1</statustext>
         <statuscode></statuscode>
         <paydate>2015-11-13</paydate>
         <amount>1.00</amount>
     </status>
     <status>
         <jobid>3081428</jobid>
         <statusid>P3603684</statusid>
         <billnum>28979215</billnum>
         <statustype>payment</statustype>
         <statustime>2015-11-13 00:00:00</statustime>
         <statusref>Kassa-alennus</statusref>
         <statustext>8</statustext>
         <statuscode></statuscode>
         <paydate>2015-11-13</paydate>
         <amount>0.24</amount>
     </status>
     <status>
         <jobid>3081412</jobid>
         <statusid>C4660620</statusid>
         <billnum>21979215</billnum>
         <statustype>comment</statustype>
         <statustime>2015-11-13 09:59:20</statustime>
         <statusref>Viesti</statusref>
         <statustext>Elias Luoma kirjasi ohisuorituksen
Summa: 96,72 e
Maksupäivä: 13.11.2015
Kirjauspäivä: 13.11.2015</statustext>
         <statuscode>98</statuscode>
         <paydate></paydate>
         <amount></amount>
     </status>
     <status>
         <jobid>3081412</jobid>
         <statusid>C4660621</statusid>
         <billnum>21979215</billnum>
         <statustype>comment</statustype>
         <statustime>2015-11-13 09:59:20</statustime>
         <statusref>Maksettu</statusref>
         <statustext>Pääoma maksettu</statustext>
         <statuscode>101</statuscode>
         <paydate></paydate>
         <amount></amount>
     </status>
     <status>
         <jobid>3081428</jobid>
         <statusid>C4660622</statusid>
         <billnum>28979215</billnum>
         <statustype>comment</statustype>
         <statustime>2015-11-13 09:59:49</statustime>
         <statusref>Viesti</statusref>
         <statustext>Elias Luoma kirjasi ohisuorituksen
Summa: 1,00 e
Maksupäivä: 13.11.2015
Kirjauspäivä: 13.11.2015</statustext>
         <statuscode>98</statuscode>
         <paydate></paydate>
         <amount></amount>
     </status>
     <status>
         <jobid>3081428</jobid>
         <statusid>C4660623</statusid>
         <billnum>28979215</billnum>
         <statustype>comment</statustype>
         <statustime>2015-11-13 09:59:49</statustime>
         <statusref>Maksettu</statusref>
         <statustext>Pääoma maksettu</statustext>
         <statuscode>101</statuscode>
         <paydate></paydate>
         <amount></amount>
     </status>
</result>';
$result = new SimpleXMLElement($xml);


foreach ($result as $r) {
	$str = '';
    	$str = 'statustime:'.$r->statustime.'//jobid:'.$r->jobid.'//billnum:'.$r->billnum.'//statusref:'.$r->statusref.'//statustext:'.$r->statustext.'//statuscode:'.$r->statuscode;

	$l = Lasku::model()->find(" trust_jobid='".$r->jobid."' ");
	if(isset($l->id))
	{
     	Lasku::model()->updatebypk($l->id, array('tilanne'=>$r->statuscode,'response_finvoice'=>$str));
	}
}

echo '<pre>';
print_r($result);
echo '</pre>';




?>
<!--
<form action="https://www.trustpoint.fi/API/statusupdates.php" method="POST">
<input type="text" name="cid">
<input type="text" name="apicode">
<input type="submit">
</form>
-->
