<?php
	$getSun = 0;
	$getTot = 0;
	$ero = 0;

       	$criteria = new CDbCriteria();
	$criteria->select = " SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppu, '%H:%i'), '%H:%i'), DATE_FORMAT(STR_TO_DATE(alku, '%H:%i'), '%H:%i')))) as l_tunnit ";
	$criteria->condition = " 
		loppu !='' AND alku!=''
		AND tid = '".$tid."' and pvm = '".date("d.m.Y",strtotime($pvm))."' 
		AND tyoajanmerkinta NOT LIKE '%Ei lasketa%'
	";
	$sun = Tyovuoroot::model()->find($criteria); 
	$getSun = $sun->l_tunnit;


	if($getSun > 0){
	$sun = $getSun;
	echo Yii::t('main', 'Sun. ').sprint($sun).'<br>';
	}


       	$criteria = new CDbCriteria();
	$criteria->select = " 
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit 
	";
	$criteria->condition = " 
		tid = '".$tid."' 
		and DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".date("Y-m-d",strtotime($pvm))."' 
	"; //AND kid IN (SELECT id FROM sivexkuitti)

	if(Yii::app()->session['Lounastauko'])
	$criteria->addCondition (" status != '10' ");
	if(Yii::app()->session['MATKA'])
	$criteria->addCondition (" status != '2' ");

	$tot = Toteutuneet::model()->find($criteria); 
	$getTot += $tot->l_tunnit;


       	$criteria = new CDbCriteria();
	$criteria->select = " 
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit 
	";
	$criteria->condition = " 
		tid = '".$tid."' 
		and DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".date("Y-m-d",strtotime($pvm))."' 
		AND id NOT IN (SELECT kid FROM sivexkuitti_repaired) 
	";

	if(Yii::app()->session['Lounastauko'])
	$criteria->addCondition (" status != '10' ");
	if(Yii::app()->session['MATKA'])
	$criteria->addCondition (" status != '2' ");

	$mob = Mobile::model()->find($criteria); 
	$getTot += $mob->l_tunnit;

	if($getTot > 0){
	$tot = $getTot;
	echo Yii::t('main', 'Tot. ').sprint($tot).'<br>';
	}

	if($getSun > 0 and $getTot > $getSun)
	{
	  $ero = $getTot-$getSun;
	  echo '<span class="text-success">'.Yii::t('main', 'Ero aika: ').sprint($ero).'</span>'; 
	}
	if($getSun > 0 and $getSun > $getTot)
	{
	  $ero = $getSun-$getTot;
	  echo '<span class="text-danger">'.Yii::t('main', 'Ero aika: -').sprint($ero).'</span>'; 
	}

?>
