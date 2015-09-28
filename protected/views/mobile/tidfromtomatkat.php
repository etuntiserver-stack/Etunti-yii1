<?php

       	$criteria = new CDbCriteria();
	$criteria->select = "
	SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s')))) as l_tunnit
	";
	$criteria->condition = " tid = '".$tid."' AND
	DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
	BETWEEN '".$from."' AND '".$to."' 
	AND status=2
	AND id NOT IN (SELECT kid FROM sivexkuitti_repaired) ";

	$lu = Mobile::model()->findAll($criteria); 



	/* toteutu */
       	$criteria = new CDbCriteria();
	$criteria->select = "
	SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s')))) as t_tunnit
	";
	$criteria->condition = " tid = '".$tid."' AND
	DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
	BETWEEN '".$from."' AND '".$to."' 
	AND status=2
	AND kid NOT IN (SELECT id FROM sivexkuitti) ";

	$tot = Toteutuneet::model()->findAll($criteria); 

?>
