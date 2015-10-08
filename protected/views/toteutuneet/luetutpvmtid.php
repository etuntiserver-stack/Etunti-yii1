<?php

	echo '<div class="small" style="opacity:0.6">';
	$did = date("Ymd",strtotime($pvm));

       	$criteria = new CDbCriteria();
	$criteria->order = "DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'),'%Y%m%d')";
	$criteria->condition = " 
		tid = '".$tid."' 
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".date("Y-m-d",strtotime($pvm))."' 
		AND admin!='1'
	";

	if(Yii::app()->session['Lounastauko'])
	$criteria->addCondition (" status != '10' ");

	if(Yii::app()->session['MATKA'])
	$criteria->addCondition (" status != '2' ");

	$tv = Mobile::model()->findAll($criteria); 
	foreach($tv as $tvVal)
	{
	   $strlen = strlen($tvVal->kohde_kannasta);
	   if($strlen > 18)
	    $tvVal->kohde_kannasta = substr($tvVal->kohde_kannasta,0,18).'..';
	   else
	    $tvVal->kohde_kannasta = $tvVal->kohde_kannasta;

	   if($tvVal->aloitan > 0 and $tvVal->loppui > 0)
	    $al = date("H:i",strtotime($tvVal->aloitan)).'-'.date("H:i",strtotime($tvVal->loppui));
	   else
	    $al = '';

	   echo '
	   <div id="'.$tvVal->id.'_'.$did.'_'.$tid.'" class="fullRivi">';
	   echo '&nbsp;<span class="" id="tv_'.$tvVal->id.'">'.$al.' '.$tvVal->kohde_kannasta.'</span><br>
	   </div>';
	}
	echo '</div>';

//print_r($tvVal->kohde);
//exit;
?>

