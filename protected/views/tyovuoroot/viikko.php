<?php


       	$criteria = new CDbCriteria();
        $criteria->select = "
	SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppu, '%H:%i'), '%H:%i'), DATE_FORMAT(STR_TO_DATE(alku, '%H:%i'), '%H:%i')))) as l_tunnit";

        $criteria->condition = " 
		tid = '".$tid."'  
		AND tyoajanmerkinta NOT LIKE '%Ei lasketa%'
	";

	$criteria->addCondition (" DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%u') = '".$viikko."' ");

	$tv = Tyovuoroot::model()->find($criteria); 
	echo sprint($tv['l_tunnit']);

?>

