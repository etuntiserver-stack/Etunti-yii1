<?php

	$tun = 0;
       	$criteria = new CDbCriteria();
	$criteria->condition = " tid = '".$tid."' AND
	DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
	BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' ";

	if(Yii::app()->session['Lounastauko'])
	$criteria->addCondition (" status != '10' ");

	if(Yii::app()->session['MATKA'])
	$criteria->addCondition (" status != '2' ");

	$lu = Mobile::model()->findAll($criteria); 

	foreach($lu as $tvVal){
	   if($tvVal->id){

	  $tvVal->loppui = date("Y-m-d H:i",strtotime($tvVal->loppui));
	  $tvVal->aloitan = date("Y-m-d H:i",strtotime($tvVal->aloitan));

	   if(!empty($tvVal->aloitan) and !empty($tvVal->loppui))
	   $tun += strtotime($tvVal->loppui)-strtotime($tvVal->aloitan);
	   }
	}

	echo $tun;
?>
