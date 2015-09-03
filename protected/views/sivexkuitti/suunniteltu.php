<?php


	$total_sunniteltu = 0;
	$criteria = new CDbCriteria();

	$criteria->condition = " $kohde_tid = '".$id."' ";

	  if(Yii::app()->session['from'] and Yii::app()->session['to'])
	  {
	  $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' ");
	  }

	  $su = Tyovuoroot::model()->findAll($criteria);

	    foreach($su as $val)
	      $total_sunniteltu += (strtotime($val->pvm." ".$val->loppu)-strtotime($val->pvm." ".$val->alku));


	echo $total_sunniteltu;
?>
