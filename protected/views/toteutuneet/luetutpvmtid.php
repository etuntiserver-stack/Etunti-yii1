<?php

	echo '<div class="small" style="opacity:0.6">';
	$did = date("Ymd",strtotime($pvm));
	$tv = Sivexkuitti::model()->findAll("tid = '".$tid."' and DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".date("Y-m-d",strtotime($pvm))."' "); 
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

