<?php

	$luetutpvmtid = '<div class="small" style="opacity:0.6">';
	$did = date("Ymd",strtotime($pvm));

       	$criteria = new CDbCriteria();
	$criteria->order = "DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') ASC";
	$criteria->condition = " 
		tid = '".$tid."' 
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".date("Y-m-d",strtotime($pvm))."' 
		AND admin!='1'
		AND aloitan!='' AND loppui!=''
	";

	if(Yii::app()->session['Lounastauko'])
	$criteria->addCondition (" status != '10' ");

	if(Yii::app()->session['MATKA'])
	$criteria->addCondition (" status != '2' ");

	$tv = Mobile::model()->findAll($criteria); 
	$tun = 0;
	foreach($tv as $tvVal)
	{
	   $tvVal->loppui = date("Y-m-d H:i",strtotime($tvVal->loppui));
	   $tvVal->aloitan = date("Y-m-d H:i",strtotime($tvVal->aloitan));
	   $tun += strtotime($tvVal->loppui)-strtotime($tvVal->aloitan);

	   $strlen = strlen($tvVal->kohde_kannasta);
	   if($strlen > 18)
	    $tvVal->kohde_kannasta = substr($tvVal->kohde_kannasta,0,18).'..';
	   else
	    $tvVal->kohde_kannasta = $tvVal->kohde_kannasta;

	   if($tvVal->aloitan > 0 and $tvVal->loppui > 0)
	    $al = date("H:i",strtotime($tvVal->aloitan)).'-'.date("H:i",strtotime($tvVal->loppui));
	   else
	    $al = '';

	   $luetutpvmtid .= '
	   <div id="'.$tvVal->id.'_'.$did.'_'.$tid.'" class="fullRivi">';
	   $luetutpvmtid .= '&nbsp;<span class="" id="tv_'.$tvVal->id.'">'.$al.'<br>'.$tvVal->kohde_kannasta.'</span><br>
	   </div>';
	}
	$luetutpvmtid .= '</div>';

	echo $luetutpvmtid.'explode999'.$tun;

//print_r($tvVal->kohde);
//exit;
?>

