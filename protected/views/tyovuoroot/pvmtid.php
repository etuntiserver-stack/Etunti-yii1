<?php

       	$criteria = new CDbCriteria();
	$criteria->condition = " tid = '".$tid."' 
	and DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') = '".date("Y-m-d",strtotime($pvm))."' 
	";
	$tv = Tyovuoroot::model()->findAll($criteria); 

	$tun = 0;
	foreach($tv as $tvVal)
	   if($tvVal->id)
	   	if(!empty($tvVal->alku) and !empty($tvVal->loppu) and $from == 'kk')
	   		$tun += strtotime($tvVal->loppu)-strtotime($tvVal->alku);


	echo '<div class="small">';
	if($tun > 0)
		echo sprint($tun)."//".$tun;
	echo '</div>';
?>

