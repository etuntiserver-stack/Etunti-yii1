<?php
header("Content-type: text/xml");

function parseToXML($htmlStr) 
{ 
$xmlStr=str_replace('<','&lt;',$htmlStr); 
$xmlStr=str_replace('>','&gt;',$xmlStr); 
$xmlStr=str_replace('"','&quot;',$xmlStr); 
$xmlStr=str_replace("'",'&#39;',$xmlStr); 
$xmlStr=str_replace("&",'&amp;',$xmlStr); 
return $xmlStr; 
} 
 
 

echo '<markers>';
  foreach($model as $k){

	  $explSijainti = explode(",",$k->gps_sijainti);
	  if(isset($explSijainti[0]) and isset($explSijainti[1]))
	  {
	  	echo '<marker ';
	  	echo 'name="' . parseToXML($k->osoite) . '" ';
		  echo 'address="' . parseToXML($k->osoite) . '" ';
		  echo 'lat="' . $explSijainti[0] . '" ';
		  echo 'lng="' . $explSijainti[1] . '" ';
		  if($k->tag_id)
		  echo 'type="cafe" ';
		  else
		  echo 'type="" ';
		  echo '/>';
	  }
  }
echo '</markers>';

?>


