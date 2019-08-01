<?php

       	$criteria = new CDbCriteria();
	$criteria->condition = " tid = '".$tid."' 
	AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') = '".date("Y-m-d",strtotime($pvm))."' 
	AND peruutettu=0
	";
	$tv = Tyovuoroot::model()->findAll($criteria); 

	$tun = 0;
	foreach($tv as $tvVal)
	   if($tvVal->id)
	   	if(!empty($tvVal->alku) and !empty($tvVal->loppu) and $from == 'kk')
	   		$tun += strtotime($tvVal->loppu)-strtotime($tvVal->alku);


	if($tun > 0)
	{
	echo '<div class="small">';
		echo sprint($tun)."//".$tun;
	echo '</div>';
	}
?>

