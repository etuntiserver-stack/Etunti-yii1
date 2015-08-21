<?php

	$tv = Tyovuoroot::model()->findAll("tid = '".$tid."' and pvm = '".date("d.m.Y",strtotime($pvm))."'  "); 

	$get = 0;
	foreach($tv as $tvVal){
	   if($tvVal->id){
	   $get += (strtotime($pvm.' '.$tvVal->loppu)-strtotime($pvm.' '.$tvVal->alku));
	   }
	}

	if($get > 0)
	echo $get;
?>
