<?php


       	$criteria = new CDbCriteria();
	$criteria->select = "
	SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s')))) as t_tunnit
	";
	$criteria->condition = " tid = '".$tid."' AND
	DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
	BETWEEN '".$from."' AND '".$to."' 
	AND aloitan!='' AND loppui!=''
	AND status=2
	AND kid IN (SELECT id FROM sivexkuitti) ";

	$tot = Toteutuneet::model()->find($criteria); 



       	$criteria = new CDbCriteria();
	$criteria->select = "
	SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s')))) as l_tunnit
	";
	$criteria->condition = " tid = '".$tid."' AND
	DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
	BETWEEN '".$from."' AND '".$to."' 
	AND aloitan!='' AND loppui!=''
	AND status=2
	AND id NOT IN (SELECT kid FROM sivexkuitti_repaired) ";

	$lu = Mobile::model()->find($criteria); 

	echo $lu->l_tunnit+$tot->t_tunnit;

?>
