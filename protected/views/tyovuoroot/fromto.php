<?php


       	$criteria = new CDbCriteria();
        $criteria->select = "
	SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppu, '%H:%i'), '%H:%i'), DATE_FORMAT(STR_TO_DATE(alku, '%H:%i'), '%H:%i')))) as l_tunnit";

        $criteria->condition = " tid = '".$tid."'  ";

	if(Yii::app()->session['from'] and Yii::app()->session['to'])
	$criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' ");

	$tv = Tyovuoroot::model()->find($criteria); 
	echo sprint($tv['l_tunnit']);

?>

