<?php
	$getSun = 0;
	$getTot = 0;
	$ero = 0;

	$sun = Tyovuoroot::model()->findAll("tid = '".$tid."' and pvm = '".date("d.m.Y",strtotime($pvm))."'  "); 

	foreach($sun as $tvVal){
	   if($tvVal->id){
	   $getSun += (strtotime($pvm.' '.$tvVal->loppu)-strtotime($pvm.' '.$tvVal->alku));
	   }
	}

	if($getSun > 0){
	$sun = $getSun;
	echo Yii::t('main', 'Sun. ').sprint($sun).'<br>';
	}


       	$criteria = new CDbCriteria();
	$criteria->condition = " tid = '".$tid."' 
	and DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".date("Y-m-d",strtotime($pvm))."' 
	AND kid IN (SELECT id FROM sivexkuitti) ";
	if(Yii::app()->session['Lounastauko'])
	$criteria->addCondition (" status != '10' ");
	if(Yii::app()->session['MATKA'])
	$criteria->addCondition (" status != '2' ");

	$tot = Toteutuneet::model()->findAll($criteria); 

	foreach($tot as $tvVal){
	   if($tvVal->id){
	   $getTot += (strtotime($tvVal->loppui)-strtotime($tvVal->aloitan));
	   }
	}

       	$criteria = new CDbCriteria();
	$criteria->condition = " tid = '".$tid."' 
	and DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".date("Y-m-d",strtotime($pvm))."' 
	AND id NOT IN (SELECT kid FROM sivexkuitti_repaired) ";
	if(Yii::app()->session['Lounastauko'])
	$criteria->addCondition (" status != '10' ");
	if(Yii::app()->session['MATKA'])
	$criteria->addCondition (" status != '2' ");

	$mob = Mobile::model()->findAll($criteria); 

	foreach($mob as $tvVal){
	   if($tvVal->id){
	   $getTot += (strtotime($tvVal->loppui)-strtotime($tvVal->aloitan));
	   }
	}

	if($getTot > 0){
	$tot = $getTot;
	echo Yii::t('main', 'Tot. ').sprint($tot).'<br>';
	}

	if($getSun > 0)
	  $ero = $getSun-$getTot;
	else
	  $ero = $getTot-$getSun;

	echo Yii::t('main', 'Ero aika: ').sprint($ero); 
	

?>
