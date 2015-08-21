<?php

	$tv = Toteutuneet::model()->findAll("tid = '".$tid."' 
	and DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".date("Y-m-d",strtotime($pvm))."' 
	AND id NOT IN (SELECT kid FROM sivexkuitti) "); 

	$get = 0;

	foreach($tv as $tvVal){
	   if($tvVal->id){
	   $get += (strtotime($tvVal->loppui)-strtotime($tvVal->aloitan));
	   }
	}

	$tv = Sivexkuitti::model()->findAll("tid = '".$tid."' 
	and DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".date("Y-m-d",strtotime($pvm))."' 
	AND id NOT IN (SELECT kid FROM sivexkuitti_repaired) "); 

	foreach($tv as $tvVal){
	   if($tvVal->id){
	   $get += (strtotime($tvVal->loppui)-strtotime($tvVal->aloitan));
	   }
	}

	if($get > 0)
	echo $get;
?>
