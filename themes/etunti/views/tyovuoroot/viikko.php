<?php

	$site = Yii::app()->createController('Site');
	$eilasketa = $site[0]->eiLasketa();

       	$criteria = new CDbCriteria();
        $criteria->select = "
	SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppu, '%H:%i'), '%H:%i'), DATE_FORMAT(STR_TO_DATE(alku, '%H:%i'), '%H:%i')))) as l_tunnit";

        $criteria->condition = " 
		tid = '".$tid."'  
		AND $eilasketa
		AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%u') = '".$viikko."'
		AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y') = '$year'
	";

	$tv = Tyovuoroot::model()->find($criteria); 
	echo $this->sprint($tv['l_tunnit']);

?>

