<?php

	$tun = 0;

	/* luettu */
       	$criteria = new CDbCriteria();
	$criteria->condition = " 
		tid = '".$tid."' AND
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
		BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' 
		AND id NOT IN (SELECT kid FROM sivexkuitti_repaired) 
	";

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


	/* toteutu */
       	$criteria = new CDbCriteria();
	$criteria->condition = " 
		tid = '".$tid."' AND
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
		BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' 
		AND kid IN (SELECT id FROM sivexkuitti)
	";

	if(Yii::app()->session['Lounastauko'])
	$criteria->addCondition (" status != '10' ");

	if(Yii::app()->session['MATKA'])

	$criteria->addCondition (" status != '2' ");

	$lu = Toteutuneet::model()->findAll($criteria); 


	foreach($lu as $tvVal){
	   if($tvVal->id){
	   if(!empty($tvVal->aloitan) and !empty($tvVal->loppui))

	  $tvVal->loppui = date("Y-m-d H:i",strtotime($tvVal->loppui));
	  $tvVal->aloitan = date("Y-m-d H:i",strtotime($tvVal->aloitan));

	   $tun += strtotime($tvVal->loppui)-strtotime($tvVal->aloitan);
	   }
	}

	echo $tun;
?>
